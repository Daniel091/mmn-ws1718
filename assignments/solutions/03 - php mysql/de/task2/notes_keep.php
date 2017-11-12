<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notes Webapp</title>
    <style>
        body, html {
            font-family: 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif;
            font-size: 20px;
            margin: 0;
            padding: 0;
            background-color: #333;
            color: white;
        }

        #container {
            margin-top: 100px;
            width: 90%;
        }

        #formContainer {
            width: 100%;
        }

        label {
            margin-right: 2em;
        }

        .notes {
            margin-top: 100px;
            margin-bottom: 200px;
        }

        .note {
            border: solid white;
            margin-top: 30px;
            padding: 10px;
            margin-left: 10px;
            margin-right: 10px;
            display: inline-block;
        }

        .info {
            color: darkgreen;
        }

        .error {
            color: darkred;
        }
    </style>
</head>
<body>


<div id="container">
    <div id="formContainer">
        <form method="post">
            <label>
                Title:
                <input required type="text" name="title"/>
            </label>

            <label>
                Text:
                <textarea type="text" name="text"></textarea>
            </label>

            <input type="submit" name="submit" value="Add Note"/>
        </form>
    </div>

    <div class="info">
        <?php

        // this file holds the connection info in $host, $user, $password, $db;
        include_once('connectionInfo.private.php');

        // the DBHandler takes care of all the direct database interaction.
        require_once('DBHandler.php');

        $dbHandler = new DBHandler($host, $user, $password, $db);


        // now, let's see whether the user has submitted the form
        if (isset($_POST['submit'])) {
            $dbHandler->sanitizeInput($_POST['title']);
            $dbHandler->sanitizeInput($_POST['text']);

            $ret = $dbHandler->insertNote($_POST['title'], $_POST['text']);

            if ($ret) {
                echo "<p class='info'>Inserted Note with Title: {$_POST['title']} </p>";
            } else {
                echo "<p class='error' >Error :(</p>";
            }
        }

        // check for deleteAction
        if (isset($_POST['deleteAction'])) {
            foreach ($_POST['Notes'] as $noteID) {
                $ret = $dbHandler->deleteNote($noteID);
                if ($ret) {
                    echo "<p class='info'>Deleted a Note</p>";
                } else {
                    echo "<p class='error' >Error :(</p>";
                }
            }
        }


        ?>
    </div>
</div>


<div class="notes">
    <form method="post">
        <?php
        $notes = $dbHandler->fetchNotes();

        if (count($notes) == 0) {
            echo '<p>There are no notes yet. You can add one in the form above..</p>';
        } else {

            foreach ($notes as $note) {
                $id = $note['id'];
                $title = $note['title'];
                $text = str_replace(array('\r\n', '\r', '\n'), "<br />", $note['text']);

                $html = "<div class='note' id='$id'><input type='checkbox' name='Notes[]' value='$id'>
                        <h3>$title</h3><p>$text</p></div>";
                echo $html;
            }
        }
        ?>
        <input type="submit" name="deleteAction" value="Delete Note(s)"/>
    </form>
</div>


</body>
</html>