<?php 

abstract class Operation {
  protected $operand_1;
  protected $operand_2;
  public function __construct($o1, $o2) {
    // Make sure we're working with numbers...
    if (!is_numeric($o1) || !is_numeric($o2)) {
      throw new Exception('Non-numeric operand.');
    }
    
    // Assign passed values to member variables
    $this->operand_1 = $o1;
    $this->operand_2 = $o2;
  }
  public abstract function operate();
  public abstract function getEquation(); 
}

// Addition subclass inherits from Operation
class Addition extends Operation {
  public function operate() {
    return $this->operand_1 + $this->operand_2;
  }
  public function getEquation() {
    return $this->operand_1 . ' + ' . $this->operand_2 . ' = ' . $this->operate();
  }
}

// Part 1 - Add subclasses for Subtraction, Multiplication and Division here

// Subtraction subclass inherits from Operation
class Subtraction extends Operation {
  public function operate() {
    return $this->operand_1 - $this->operand_2;
  }
  public function getEquation() {
    return $this->operand_1 . ' - ' . $this->operand_2 . ' = ' . $this->operate();
  }
}

// Multiplication subclass inherits from Operation
class Multiplication extends Operation {
  public function operate() {
    return $this->operand_1 * $this->operand_2;
  }
  public function getEquation() {
    return $this->operand_1 . ' * ' . $this->operand_2 . ' = ' . $this->operate();
  }
}

// Division subclass inherits from Operation
class Division extends Operation {
  public function operate() {
    if ($this->operand_2 == 0) {
      throw new Exception('Division by zero.');
    }
    return $this->operand_1 / $this->operand_2;
  }
  public function getEquation() {
    return $this->operand_1 . ' / ' . $this->operand_2 . ' = ' . $this->operate();
  }
}

// End Part 1




// Some debugs - un comment them to see what is happening...
//echo '$_POST print_r=>',print_r($_POST);
//echo "<br>",'$_POST vardump=>',var_dump($_POST);
//echo '<br/>$_POST is ', (isset($_POST) ? 'set' : 'NOT set'), "<br/>";
//echo "<br/>---";




// Check to make sure that POST was received 
// upon initial load, the page will be sent back via the initial GET at which time
// the $_POST array will not have values - trying to access it will give undefined message

  if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $o1 = $_POST['op1'];
    $o2 = $_POST['op2'];
  }
  $err = Array();


// Part 2 - Instantiate an object for each operation based on the values returned on the form
//          For example, check to make sure that $_POST is set and then check its value and 
//          instantiate its object
// 
// The Add is done below.  Go ahead and finish the remiannig functions.  
// Then tell me if there is a way to do this without the ifs

  try {
    if (isset($_POST['add']) && $_POST['add'] == 'Add') {
      $op = new Addition($o1, $o2);
    }

// Put the code for Part 2 here  \\/
    elseif (isset($_POST['sub']) && $_POST['sub'] == 'Subtract') {
      $op = new Subtraction($o1, $o2);
    }
    elseif (isset($_POST['mult']) && $_POST['mult'] == 'Multiply') {
      $op = new Multiplication($o1, $o2);
    }
    elseif (isset($_POST['div']) && $_POST['div'] == 'Divide') {
      $op = new Division($o1, $o2);
    }

// End of Part 2   /\

  }
  catch (Exception $e) {
    $err[] = $e->getMessage();
  }
?>

<!doctype html>
<html>
<head>
<title>Lab 6</title>
</head>
<body>
  <pre id="result">
  <?php 
    if (isset($op)) {
      try {
        echo $op->getEquation();
      }
      catch (Exception $e) { 
        $err[] = $e->getMessage();
      }
    }
      
    foreach($err as $error) {
        echo $error . "\n";
    } 
  ?>
  </pre>
  <form method="post" action="lab6start.php">
    <input type="text" name="op1" id="op1" value="" />
    <input type="text" name="op2" id="op2" value="" />
    <br/>
    <!-- Only one of these will be set with their respective value at a time -->
    <input type="submit" name="add" value="Add" />  
    <input type="submit" name="sub" value="Subtract" />  
    <input type="submit" name="mult" value="Multiply" />  
    <input type="submit" name="div" value="Divide" />  
  </form>
  <script>
    // Simple client-side validation: ensure both operands are numeric before submitting
    document.addEventListener('DOMContentLoaded', function() {
      var form = document.querySelector('form');
      var result = document.getElementById('result');

      function isNumeric(val) {
        if (typeof val !== 'string') return false;
        val = val.trim();
        return val !== '' && !isNaN(Number(val));
      }

      form.addEventListener('submit', function(e) {
        var v1 = form.op1.value;
        var v2 = form.op2.value;
        if (!isNumeric(v1) || !isNumeric(v2)) {
          e.preventDefault();
          // show a friendly message in the same result area used by the server
          result.textContent = 'Error: Please enter numeric values for both operands.';
          // focus the first invalid field
          if (!isNumeric(v1)) {
            document.getElementById('op1').focus();
          } else {
            document.getElementById('op2').focus();
          }
        }
      });
    });
  </script>
</body>
</html>

<?php
/*
---- LAB 6  QUESTIONS ----

1) Explain what each of your classes and methods does, the order in which methods are invoked, and the flow of execution after one 
of the operation buttons has been clicked.

  I use an abstract Operation that stores the two inputs and defines operate() and getEquation(). The four subclasses—Addition, Subtraction, Multiplication, 
  Division—implement the math in operate() and return a full string like “a op b = result” in getEquation(). After I click a button, the form sends a POST back 
  to this file. The code checks which button was clicked, instantiates the matching class (constructor runs) with the two values, then calls operate() and getEquation(), 
  and finally echoes the string inside <pre id="result">. I also handle invalid inputs, and for division I guard against divide-by-zero.


2) Also explain how the application would differ if you were to use $_GET, and why this may or may not be preferable.

  With $_GET, the values and the chosen operation show up in the URL (e.g., ?val1=10&val2=3&op=add). That makes results easy to bookmark/share and helps with debugging 
  and back-button behavior, but it exposes inputs in browser history/server logs, has URL length limits, and can look messy. Conceptually, GET is for “readable” requests 
  and POST is nicer for form submissions that perform work. My calculator would still function similarly (I’d read $_GET instead of $_POST), but for this assignment I 
  prefer POST because it’s cleaner, keeps inputs out of the address bar, and avoids length/privacy issues.


3) Finally, please explain whether or not there might be another (better +/-) way to determine which button has been pressed and take the 
appropriate action

  Right now, I use an if/elseif structure to check which button was clicked. Another way would be to use an associative array that maps button names 
  to their corresponding class names. Then I could loop through the array, see which key exists in $_POST, and create that operation dynamically. This 
  makes the code shorter and easier to maintain. A different option would be to use JavaScript to store the selected operation in a hidden input field,
  then read that one variable in PHP. Both methods simplify the logic and reduce repetition.

  Instead of having multiple if/elseif checks, I could use one submit name with different values:
    <button type= "submit" name="op" value= "add">Add</button>
  Then server-side I would switch on $_POST['op']. Another clean pattern would be a mapping array:
    $ops = ['add' => Addition::class, 'sub' => Subtraction::class, 'mult' => Multiplication::class, 'div' => Division::class];
  
  Loop the keys to find the first that exists in $_POST or use $_POST['op'] with the single-name pattern, and instantiate dynamically: new $ops[$op]($a, $b). 
  Both approaches would reduce repetition, make it easier to add operations later, and keep the control flow simple.

*/
?>