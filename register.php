<?php 
    session_start();
    //DATABASE
    $servername = "localhost";
    $dbusername = "ruadatascientist_admin";
    $dbpassword = "JgFuXukjLwUScgAf";
    $dbname = "ruadatascientist";

    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname); 

    $registered = false;
    $pwderror = false;
    $usernameerror = false;
    
    /**
     * 1. Check whether someone is allready registered as the given user.
     * 2. Check if the passwords are correct.
     * 3. If there are no errors, create a new account and login the user.
     */
    if ($_POST["submit"] === "Register") {
        if ($_POST["username"] != null && $_POST["username"] != "") {
            $usernameerror = checkIfUserExists(test_input($_POST["username"]));
        } else {
            $usernameerror = true;
        }
        if ($_POST["password"] === null || $_POST["password"] == "" || $_POST["password"] != $_POST["password-repeat"]) {
            $pwderror = true;
        }   

        if (!$pwderror && !$usernameerror) {
            $username = test_input($_POST["username"]);
            addUserToDatabase($username , test_input($_POST["password"]));
            $_SESSION["logged_in"] = true;
            $_SESSION["currentuser"] = $username;
            $registerd = true;
        }        
    }    
    
    $conn->close();
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Are you a data scientist?</title>
    <link rel="icon" type="image/ico" href="pictures/icon.svg" />
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="css/register.css">
</head>

<body>
    <!-- navbar -->
    <nav>
        <div class="nav-logo">
            <h4><a href="index.html">Are you a data scientist?</a></h4>
        </div>
        <ul class="nav-links">
            <li>
                <a class="active" href="test.html">Test</a>
            </li>
            <li>
                <a href="topics.html">Topics</a>
            </li>
            <li>
                <a href="about.html">About</a>
            </li>
        </ul>
        <div class="burger">
            <div class="bLine-1"></div>
            <div class="bLine-2"></div>
            <div class="bLine-3"></div>
        </div>
    </nav>
    <script src="js/nav.js"></script>
    <div class="nav-blocker">''</div>

    <!-- dynamic serverside form -->
    <div class='register-content'>
        <?php
        if ($_SESSION["logged_in"]) {
            echo "<br><br><h2 class='form-title'>You were registered succefully, " . $_SESSION["currentuser"] . ".<h2><br>";
            echo "<h2 class='form-title'><a href='result.php'>back to result page</a><h2>";
        } else {
            echo "<br><br><h2 class='form-title'>Fill out the form below to register for saving your score in our database:<h2>";
            echo "<br><br>";
            echo "<form class='register-form' method='POST'>";
            echo "<label for='username'>Username:     </label>";
            echo "<input type='text' name='username'>";
            if ($usernameerror) {
                echo "  <div class='input-error'>Username allready in use!</div>";
            }
            echo "<br><br>";
            echo "<label for='password'>Password:     </label>";
            echo "<input type='password' name='password'>"; 
            if ($pwderror) {
                echo "  <div class='input-error'>Passwords don't match!</div>";
            }
            echo "<br>";
            echo "<label for='password-repeat'>Repeat Password:</label>";
            echo "<input type='password' name='password-repeat'>";
            if ($pwderror) {
                echo "  <div class='input-error'>Passwords don't match!</div>";
            }
            echo "<br><br>";
            echo "<input type='submit' name='submit' value='Register'>";
            echo "</form>";
        }  
        ?>
    </div>

    <!-- footer -->
    <footer class="fixed-footer">
        <ul class="footer-elements">
            <li>
                <a class="footer-link" href="#">Imprint</a>
            </li>
            <li>
                <a class="footer-link" href="#">Data Protection</a>
            </li>
            <li>
                <a class="footer-link" href="mailto:ruadatascientist@gmail.com">Contact Us</a>
            </li>
            <li>
                <div class="footer-element">© Copyright 2020</div>
            </li>
        </ul>
    </footer>
</body>

<?php
/*
 * FUNCTIONS
 */

 //VALIDATE INPUT DATA
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function checkIfUserExists($user) {
    global $conn;

    $sql = "SELECT username FROM users WHERE username='". $user ."'";

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        return true;
    }  else {
        $query_result = $conn->query($sql);
        if ($query_result->num_rows < 1) {
            return false;
        } else {
            return true;
        }
    }
}

function addUserToDatabase($username, $password) {
    global $conn;

    $sql = "INSERT INTO users (username, password) VALUES ('" . $username . "', '" . hash("sha256", $password) . "')";

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }  else {
        //try to insert the values
        if ($conn->query($sql) === TRUE) {
            //echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } 
}
?>