<?php
session_start();

// Only logged-in users can access this page
if (!isset($_SESSION['userId'])) {
    header("Location: login.php");
    exit;
}

require 'config.php';

$error = '';
$highlightId = null;

// Get all users to show in the member selection list
$usersStmt = $pdo->query("
    SELECT userId, firstName, lastName
    FROM users
    ORDER BY lastName, firstName
");
$allUsers = $usersStmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $members     = $_POST['members'] ?? [];   // array of userIds

    // Basic validation
    if ($name === '' || $description === '') {
        $error = "Project name and description are required.";
    } elseif (count($members) < 3) {
        // Must have at least 3 members
        $error = "Each project must have at least 3 members.";
    } else {
        // Check for duplicate project name
        $checkStmt = $pdo->prepare("SELECT COUNT(*) FROM projects WHERE name = ?");
        $checkStmt->execute([$name]);
        if ($checkStmt->fetchColumn() > 0) {
            $error = "A project with that name already exists. Please choose a different name.";
        } else {
            // Insert into projects table
            $insertProj = $pdo->prepare("
                INSERT INTO projects (name, description)
                VALUES (?, ?)
            ");
            $insertProj->execute([$name, $description]);
            $projectId = $pdo->lastInsertId();
            $highlightId = $projectId;  // remember which one to highlight

            // Insert members into projectmembership
            $insertMember = $pdo->prepare("
                INSERT INTO projectmembership (projectId, memberId)
                VALUES (?, ?)
            ");
            foreach ($members as $memberId) {
                $insertMember->execute([$projectId, $memberId]);
            }
        }
    }
}

// Fetch all projects from the database
$projectsStmt = $pdo->query("
    SELECT projectId, name, description
    FROM projects
    ORDER BY projectId
");
$projectsData = $projectsStmt->fetchAll(PDO::FETCH_ASSOC);

// Build projects array with members
$projects = [];
foreach ($projectsData as $proj) {
    $projectId = $proj['projectId'];
    
    // Get members for this project
    $membersStmt = $pdo->prepare("
        SELECT u.firstName, u.lastName
        FROM projectmembership pm
        JOIN users u ON pm.memberId = u.userId
        WHERE pm.projectId = ?
        ORDER BY u.lastName, u.firstName
    ");
    $membersStmt->execute([$projectId]);
    $memberRows = $membersStmt->fetchAll(PDO::FETCH_ASSOC);
    
    $memberNames = [];
    foreach ($memberRows as $m) {
        $memberNames[] = $m['firstName'] . ' ' . $m['lastName'];
    }
    
    $projects[$projectId] = [
        'name' => $proj['name'],
        'description' => $proj['description'],
        'members' => $memberNames
    ];
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Projects - Quiz 2</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Project Manager</h1>
        
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>

<form method="post" action="project.php">
            <label>
                Project Name:
                <input type="text" name="name" required placeholder="Enter project name">
            </label>
            
            <label>
                Description:
                <textarea name="description" rows="4" required placeholder="Describe your project..."></textarea>
            </label>
            
            <label>
                Members (hold Ctrl/Cmd to select at least 3):
                <select name="members[]" multiple required>
                    <?php foreach ($allUsers as $u): ?>
                        <option value="<?php echo $u['userId']; ?>">
                            <?php echo htmlspecialchars($u['lastName'] . ', ' . $u['firstName']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <small style="display: block; margin-top: 5px; color: #666;">💡 Tip: Hold Ctrl (Windows) or Cmd (Mac) to select multiple members</small>
            </label>
            
            <button type="submit">💾 Save Project</button>
        </form>
        
        <h2>All Projects</h2>
        
        <?php if (!$projects): ?>
            <div class="no-data">
                <p>📭 No projects yet. Create your first project above!</p>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Description</th>
                        <th>Members</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($projects as $pid => $p): ?>
                        <tr class="<?php echo ($highlightId == $pid) ? 'highlight' : ''; ?>">
                            <td><strong><?php echo htmlspecialchars($p['name']); ?></strong></td>
                            <td><?php echo nl2br(htmlspecialchars($p['description'])); ?></td>
                            <td><?php echo htmlspecialchars(implode(', ', $p['members'])); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="index.php" class="back-link">Back to Home</a>
        </div>
    </div>
</body>
</html>
