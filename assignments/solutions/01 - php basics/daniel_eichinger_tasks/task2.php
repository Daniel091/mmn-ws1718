<!DOCTYPE html>
<html>
<head lang="de">
    <meta charset="UTF-8">
    <title>Text Shuffler</title>

    <style>
        textarea {
            width: 360px;
            height: 200px;
        }
    </style>

</head>
<body>
<?php

function shuffleWord($word)
{
    // get first and last char
    $f_char = $word[0];
    $l_char = $word[-1];

    // shuffle the rest
    $word = substr($word, 1, -1);
    $word = str_shuffle($word);

    // put back together
    $word = $f_char . $word . $l_char;

    return $word;
}

function shuffleText($text)
{
    $shuffeldText = [];
    $textArray = explode(" ", $text);

    foreach ($textArray as $word) {
        array_push($shuffeldText, shuffleWord($word));
    }

    return implode(" ", $shuffeldText);
}

$text = "Default Text";


if ($_POST['submit']) {
    $text = shuffleText($_POST['text']);
}
?>


<form action="task2.php" method="post">
    <textarea name="text"><? echo $text ?></textarea>
    <input class="btn" name="submit" type="submit" value="Scamble"/>
</form>
</body>
</html>