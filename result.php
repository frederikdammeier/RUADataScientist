<?php
    /*
     * STATIC VARIABLES
     */
    //MAPPING TABLE FOR KNOWLEGDE QUESTIONS AND THEIR RESULTS
    $RESULTS_MAP = array("knowledge_1"=>"3", "knowledge_2"=>"2", "knowledge_3"=>"1", "knowledge_4"=>"2", "knowledge_5"=>"1");
    $RECOMMENDATIONS = array("0"=>"Your low results indicate that a profession in datascience is probably not your best option. However - if you're still interested you might like checking out our collection of datscience-related topics.",
                             "50"=>"You're allready quite well informed and your results indicate that datascience might be for you. We suggest you to gather some more information about the subject. You might consider starting in our topics tab.",
                             "80"=>"Wow! What an amazing result! You should consider doing a deep-dive into the subject of datascience.");
    $RECOMMENDATIONS_ORDER = array(80, 50, 0);
    $LOGINBUTTON = "Log Me In";
    $LOGOUTBUTTON = "Log Me Out";

    //REGEX PATTERNS
    $KNOWLEDGE = "/^knowledge/";
    $LIKERT = "/^likert/";

    //A fixed amount for every knowledge question, and a fixed amount for every likert question
    $MAX_SCORE = (5*5) + (9*5); //5 knowledge questions with a max of 5 points each, 9 likert questions with a max of 5 points each.

    //DATABASE
    $servername = "localhost";
    $username = "ruadatascientist_admin";
    $password = "JgFuXukjLwUScgAf";
    $dbname = "ruadatascientist";

    /*
     * SESSION START
     */
    session_start();
    if($_SESSION["results"] === null || $_POST["submit"] === "test") {
        $_SESSION["results"] = getResultsFromForm($_POST);
    }

    /*
     * CONNECT TO THE DATABASE
     */
    $conn = new mysqli($servername, $username, $password, $dbname); 

    /*
     * TRY TO LOG IN THE USER
     */
    $login_success = null;
    $logged_out = false;
    if ($_POST["submit"] === $LOGINBUTTON && $_POST["username"] != "") {
        $login_success = try_login($_POST);
        if ($login_success) {
            $_SESSION["logged_in"] = true;
            $_SESSION["currentuser"] = test_input($_POST["username"]);
        } else {
            $_SESSION["currentuser"] = null;
        }
    } elseif ($_POST["username"] === "") {
        $login_success = false;
    }   elseif ($_POST["submit"] === $LOGOUTBUTTON) {
        session_unset();
        session_destroy();
        $logged_out = true;
    }   

    /*
     * LOGIC
     */
    if(!$logged_out) {
        if ($_SESSION["logged_in"] && $_SESSION["results"] === null) {
            $_SESSION["results"] = getLastResultFromDatabase($_SESSION["currentuser"]);
        } elseif ($_SESSION["logged_in"]) {
            writeScoreToDatabase($_SESSION["results"], $_SESSION["currentuser"]);
        } 
    }
    

    /*
     * CLOSE CONNECTION TO DATABASE
     */
    $conn->close();  
?>

<!DOCTYPE html>
<html>
    <title>Result</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/result.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" type="image/ico" href="pictures/icon.svg" />
<body>
    <!--NAV BAR-->
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

    <!--CONTENT-->
    <?php
    echo <<<HTML
        <div class='result-page-content'>
    HTML;
    if(!$logged_out) {
       if ($_SESSION["results"] != null) {
            $score = calculateScore($_SESSION["results"]);
            $percentage = scorePercentage($score, $MAX_SCORE);

            if ($_SESSION["logged_in"]) {
                //Greet the user and show him his most recent score.
                echo <<<HTML
                    <h2 class='result-page-headline-2'>Hello {$_SESSION["currentuser"]}.</h1>
                    <h1 class='result-page-headline'>Your most recent score is <b> $score</b> out of <b>$MAX_SCORE</b> possible Points!</h1>
                HTML;
            } else {
                //Show the user his score.
                echo <<<HTML
                    <h1 class='result-page-headline'>You scored <b>$score</b> out of <b>$MAX_SCORE!</b> possible Points!</h1>
                HTML;
            }
            echo <<<HTML
                <hr class='result-seperator'>
            HTML;

            //Show the scores percentage value and the appropriate recommendation.
            $yourFeedbackSmiley = feedbackSmiley($percentage);
            $yourRecommedation = recommendation($percentage);
            echo <<<HTML
                <h2 class='result-page-headline-2'>Thats <b>$percentage</b> percent!</h2>
                <img class='feedback-smiley' src='$yourFeedbackSmiley'/>
                <h3 class='result-page-recommendation'>$yourRecommedation</h3>
            HTML;
        } else {
                echo <<<HTML
                    <h1 class='result-page-headline'>An error occured while retrieving your score.</h1>
                HTML;
        }
        
        /* LOGIN FORM */
        if(!$_SESSION["logged_in"]) {
            if($login_success === false) {
                echo <<<HTML
                    <h3 class='form-title'>An error occured while logging you in. Please try again.</h3>
                HTML;
            } else {
                echo <<<HTML
                    <h3 class='form-title'>Would like to save your score to review it in the future? Log in below or create a new account <a href='register.php'>here</a>.</h3>
                HTML;
            }        
            echo <<<HTML
                <form method='POST'>
                    <label for='username'>Username: </label>
                    <input type='text' name='username' id='input-username'>
                    <br>
                    <label for='password'>Password: </label>
                    <input type='password' name='password' id='input-password'>
                    <br>
                    <input class='submit-button' type='submit' name='submit' value='$LOGINBUTTON'>
                </form>
            HTML;
            
        } else {

            /* LOGOUT FORM / RETAKE TEST FORM */
            echo <<<HTML
                <form class='button-form' method='POST'>
                <input class='submit-button' type ='submit' name='submit' value='$LOGOUTBUTTON'>
                </form>
                <form class='button-from' action='test.html'>
                <input class='submit-button' type ='submit' value='Retake Test'>
                </form>
            HTML;
        }
    } else {
        echo <<<HTML
            <h1 class = 'result-page-headline-2'>Logged out successfully.<h1>
        HTML;
    }
    echo <<<HTML
        </div>
    HTML;    
    ?>

    <!--FOOTER-->
    <footer class="fixed-footer">
        <ul class="footer-elements">
            <li>
                <div class="footer-element">© Copyright 2020</div>
            </li>
            <li>
                <a class="footer-link" href="#">Imprint</a>
            </li>
            <li>
                <a class="footer-link" href="#">Data Protection</a>
            </li>
            <li>
                <a class="footer-link" href="mailto:ruadatascientist@gmail.com">Contact Us</a>
            </li>
        </ul>
    </footer>
</body>
</html>

<?php
/*
 * FUNCTIONS
 */

//ESCAPE TEST RESULTS FROM POST
function getResultsFromForm($posted_data) {
    $results = array();
    global $KNOWLEDGE;
    global $LIKERT;
    foreach ($posted_data as $question => $answer) {
        if (preg_match($KNOWLEDGE, $question) || preg_match($LIKERT, $question)) {
            $results[$question] = test_input($answer);
        } 
    } 
    if(count($results) < 1){
        return null;
    } else {
        return $results;
    }
}

//GET RESULTS FROM DATABASE
function getLastResultFromDatabase($user) {
    global $conn;

    $sql = "SELECT question_1 'knowledge_1', question_2 'knowledge_2', question_3 'knowledge_3', question_4 'knowledge_4', question_5 'knowledge_5',
            question_6 'likert_1', question_7 'likert_2', question_8 'likert_3', question_9 'likert_4', question_10 'likert_5', question_11 'likert_6', question_12 'likert_7', question_13 'likert_8', question_14 'likert_9'
            FROM results WHERE username = '" . $user . "' AND timestamp = (SELECT MAX(timestamp) FROM results WHERE username = '" . $user . "')";

    //check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
        return null;
    } else {
        $query_result = $conn->query($sql);
        if ($query_result->num_rows < 1) {
            echo "no  score found";
            return null;
        } else {
            return $query_result->fetch_assoc();
        }
    }
}

//DETERMINE THE CORRECT RECOMMENDATION BASED ON THE USERS SCORED PERCENTAGE
function recommendation($score_percent) {
    global $RECOMMENDATIONS_ORDER, $RECOMMENDATIONS;
    foreach($RECOMMENDATIONS_ORDER as $percentage) {
        if($score_percent >= $percentage) {
            return $RECOMMENDATIONS[(string)$percentage];
        }
    }
    return "An error occured while fetching your recommendation :(.";
}

//RETURN THE LOCATION OF THE CORRECT FEEDBACK SMILEY
function feedbackSmiley($score_percent) {
    global $RECOMMENDATIONS_ORDER;
    $count = 1;
    foreach($RECOMMENDATIONS_ORDER as $percentage) {
        if($score_percent >= $percentage) {
            return <<<ANSWER
                    pictures/feedbackSmiley-$count.svg
                    ANSWER;
        } else {
            $count++;
        }
    }
    return "An error occured while fetching your feedback smiley :(.";
}

//CALCULATE THE USERS SCORE PERCENTAGE FROM HIS POINTS
function scorePercentage($act, $max) {
    return round(($act/$max)*100);
}

//TRY TO LOG THE USER IN
function try_login($posted_data) {
    $password = test_input($posted_data["password"]);
    $username = test_input($posted_data["username"]);
    
    global $conn;
    $sql = "SELECT username, password FROM users WHERE username='". $username ."'";
    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        $query_result = $conn->query($sql);
        if ($query_result->num_rows < 1) {
            echo "user not found";
            return false;
        } else {
            $row = $query_result->fetch_assoc();
            if($row["passoword"] = hash("sha256", $password)) {
                return true;
            }
            return false;
        }
    }    
}

//WRITE A SCORE TO THE DATABASE
function writeScoreToDatabase($data, $user) {
    global $conn;
    $sql = "INSERT INTO results (username, question_1, question_2, question_3, question_4, question_5, question_6, question_7, question_8, question_9, question_10, question_11, question_12, question_13, question_14) 
            VALUES ('unknown_user', knowledge_1, knowledge_2, knowledge_3, knowledge_4, knowledge_5, likert_1, likert_2, likert_3, likert_4, likert_5, likert_6, likert_7, likert_8, likert_9)";

    //check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }  else {
        //replace placeholders in query
        foreach ($data as $question => $answer) {
            $sql = str_replace($question, $answer, $sql);
        }
        $sql = str_replace("unknown_user", $user, $sql);

        //try to insert the values
        if ($conn->query($sql) === TRUE) {
            //echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }       
}

//VALIDATE INPUT DATA
function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

//CALCULATE THE USERS SCORE
function calculateScore($results) {
    global $RESULTS_MAP;
    global $KNOWLEDGE;
    $score = 0;
    foreach ($results as $question => $answer) {
        if(preg_match($KNOWLEDGE, $question)){
            if($RESULTS_MAP[$question] == $answer) {
                $score += 5;
            }
        } else {
            $score = $score + (int)$answer;
        }
    }
    return $score;
}
?>