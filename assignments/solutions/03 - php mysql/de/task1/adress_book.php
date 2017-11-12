<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Adress Book</title>
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
            min-width: 700px;
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
        }

        #formContainer {
            width: 100%;
        }

        label {
            margin-right: 2em;
        }

        .contacts {margin-top: 300px}
    </style>
</head>
<body>


<div id="container">


    <?php

    // this file holds the connection info in $host, $user, $password, $db;
    include_once('connectionInfo.private.php');

    // the DBHandler takes care of all the direct database interaction.
    require_once('DBHandler.php');

    $dbHandler = new DBHandler($host, $user, $password, $db);


    // now, let's see whether the user has submitted the form
    if (isset($_POST['submit'])) {

        $dbHandler->sanitizeInput($_POST['firstname']);
        $dbHandler->sanitizeInput($_POST['lastname']);
        $dbHandler->sanitizeInput($_POST['street']);
        $dbHandler->sanitizeInput($_POST['postalcode']);
        $dbHandler->sanitizeInput($_POST['city']);
        $dbHandler->sanitizeInput($_POST['email']);

        $ret = $dbHandler->insertContact($_POST['firstname'], $_POST['lastname'], $_POST['street'], $_POST['postalcode'], $_POST['city'], $_POST['email']);

        if ($ret) {
            echo "<p>Inserted {$_POST['firstname']} {$_POST['lastname']} etc.</p>";
        } else {
            echo "<p>Error :(</p>";
        }

    }

    ?>

    <div id="formContainer">
        <form method="post">
            <label>
                Firstname:
                <input type="text" name="firstname"/>
            </label>

            <label>
                Lastname:
                <input type="text" name="lastname"/>
            </label>

            <label>
                Street Adress:
                <input type="text" name="street"/>
            </label>

            <label>
                Postal Code:
                <input type="text" name="postalcode"/>
            </label>

            <label>
                City:
                <input type="text" name="city"/>
            </label>

            <label>
                Email:
                <input type="text" name="email"/>
            </label>

            <input type="submit" name="submit" value="Add Contact"/>
        </form>
    </div>
</div>


<table class="contacts">
    <thead>
    <tr>
        <td>ID</td>
        <td>Firstname</td>
        <td>Lastname</td>
        <td>Postal</td>
        <td>Street</td>
        <td>City</td>
        <td>Email</td>
    </tr>
    </thead>
    <tbody>
    <?php
    $contacts = $dbHandler->fetchContacts();
    if (count($contacts) == 0) {
        echo '<tr class="notification"><td colspan="3">There are no contacts yet. You can enter the artist and album title below, then click save.</td></tr>';
    } else {
        foreach ($contacts as $al) {
            $html = "<tr><td>$al[0]</td><td>$al[1]</td><td>$al[2]</td><td>$al[3]</td><td>$al[4]</td><td>$al[5]</td><td>$al[6]</td></tr>";
            echo $html;
        }
    }
    ?>
    </tbody>
</table>

</body>
</html>