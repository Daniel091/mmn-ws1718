<?php

// TODO: start the session.
session_start()
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hangman</title>
    <meta name="author" content="Tobias Seitz">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="hangman.css">
</head>
<body>

<div id="container">
    <h2>Hangman - the game.</h2>
    <section id="output">
        <?php

        $secretWord = 'Otto';
        $wordLength = strlen($secretWord);
        $maximumAttempts = 12; // the number of hangman images that we have...

        /**
         * Use this function to lazily instantiate a session-variable if it's not there yet.
         * example: lazyInitSessionVariable('test','banana') is the same as $_SESSION['test'] = 'banana', but has
         * the effect that it doesn't overwrite existing values.
         *
         * @param $key {string} session-variable key, preferably a string
         * @param $value {*} the value to initialize the variable with, e.g. array(), 1, ''
         */
        function lazyInitSessionVariable($key, $value)
        {
            if (!isset($_SESSION[$key])) {
                $_SESSION[$key] = $value;
            }
        }

        /**
         * takes each letter of the secret word and validates if it is already in the 'hits' array.
         * @return bool true if the word can be guessed with the letter in the 'hits' array.
         */
        function isCorrect()
        {
            global $secretWord;
            $wordLength = strlen($secretWord);
            $hits = $_SESSION['hits'];
            $correct = true;

            for ($i = 0; $i < $wordLength; $i++) {
                $secretChar = strtoupper($secretWord[$i]);
                if (!in_array($secretChar,$hits)) {
                    $correct = false;
                    break;
                }
            }
            return $correct;
        }

        // reset the guesses.
        if (isset($_POST['reset'])) {
            session_destroy();
            $_SESSION = array();
        }

        // actually initialize the session variables
        // valid and invalid attempts.
        lazyInitSessionVariable('hits', array()); // creates $_SESSION['hits']
        lazyInitSessionVariable('misses', array()); // creates $_SESSION['misses']

        // handle a valid guess.
        if (isset($_POST['guess']) AND isset($_POST['letter'])) {
            $letter = strtoupper($_POST['letter']);

            // determine if we should move the progress forward.
            if (stristr($secretWord, $letter)) { // true means: secretWord contains the letter.
                // save the letter in the 'hits' list;
                $_SESSION['hits'][] = $letter;

            } else {
                // save the letter in the 'misses' list;
                $_SESSION['misses'][] = $letter;
            }
        }

        // start the game progress at 1 (not 0).
        // but since the first miss should already advance the progress, we need to add +1;
        $progress = count($_SESSION['misses']) ? count($_SESSION['misses']) + 1 : 1;


        // if you want to make the game harder, you can start it at a later stage
        // by adding a handicap to the progress.
        $handicap = 5; // start the game at stage 5
        $progress += $handicap;


        $imageFile = 'hangman';
        // determine which of the 12 hangman files we pick.
        // if the progress is below 10, we need to prefix a '0' to the number.

        if ($progress < 10) {
            $imageFile .= '0' . $progress . '.png';
        } else {
            $imageFile .= $progress . '.png';
        }

        // before anything else, let's see if the user has now guessed correctly.
        if (isCorrect()) {
            echo '<h1> Congrats you won :-) </h1>';
        }


        // if the progress is smaller than a predefined number of attempts ==> we can play on.
        if ($progress < $maximumAttempts) {

            // reveal the letters inside the 'result' div.
            echo '<div class="result">';

            // show the image:
            echo "<img src='$imageFile' alt='Hangman - Step $progress' class='hangman'/>";

            // we go through each letter of the secret word and see if it's a hit or a miss.
            for ($i = 0; $i < $wordLength; $i++) {
                // make sure we use the uppercase version of the letters.
                $charAtI = strtoupper($secretWord[$i]); // determine the char at index $i in the $secretWord.
                echo stristr($_SESSION['hits'], $charAtI);

                // case 1: the letter of the word is in the guess array --> reveal the letter
                if (in_array($charAtI, $_SESSION['hits'])) {
                    echo $charAtI;
                } // case 2: the letter was not guessed yet --> show an underscore
                else {
                    // print an underscore for each character in the word.
                    echo '_' . ($i != $wordLength - 1 ? ' ' : '');
                }
            }


            // now, to be a little more usable, show which letters were already guessed;
            echo '<br>Misses: ';
            foreach ($_SESSION['misses'] as $result) {
                echo $result['type'], ', ';
            }

            echo '</div>'; # .result
        } else { // oh no, the user lost!
            echo "<div class=\"gameover\"><h3>Oh No!</h3><p>You lost. The solution was \"$secretWord\". </p></div>";
            session_reset();
        }


        ?>
    </section>
    <section id="formContainer">
        <form method="post" action="hangman.php">
            <input type="text" maxlength="1" minlength="1" name="letter"/>
            <button type="submit" name="guess">Guess</button>
            <button type="submit" name="reset">Reset</button>
        </form>
    </section>
</div>

</body>
</html>