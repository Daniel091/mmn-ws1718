<!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <title>What's wrong here?</title>
</head>
<body>
<?php
function loginUser($email, $password)
{
    echo $email;
    if ($password === 'test.pass') {
        echo '<p>You are now logged in.</p>';
    } else {
        echo '<p>Wrong password</p>';
    }
}

if ($_POST['submit']) {
    loginUser($_POST['email'], $_POST['password']);
} else { ?>
    <form action="task1.php" method="post">
        <label>
            Email: <input type="email" name="email"/>
        </label>
        <label>
            Password: <input type="password" name="password"/>
        </label>
        <input name="submit" type="submit" value="Submit"/>
    </form>
<?php } ?>
</body>
</html>

<!--
Problems:
 1. no type="submit" on last input
 2. used Get instead of Post for everything
 3. no action on form and no method
-->