<h1><?
    // Include variables in echo do it with double quoted strings
    $myvar = "Daniel";
    echo "Hi there $myvar <br>";

    $arbitArray = [1, 'Hansi', 2, 3, 4, 5];
    echo json_encode($arbitArray) . '<br>';


    for ($index = 0; $index <= count($arbitArray) - 1; $index++) {

        if (is_string($arbitArray[$index])) {
            continue;
        }
        $arbitArray[$index] = $arbitArray[$index] * 2;
    }

    echo json_encode($arbitArray) . '<br>';

    ?>
</h1>

<ul>
    <?
    foreach ($arbitArray as $ele) {
        echo '<li>' . $ele . '</li>';
    }

    ?>
</ul>

<br>

<p>
    <?
    // Fancy dic
    $assiArray = array("apple(s)" => 1, "banana(s)" => 4);
    foreach ($assiArray as $key => $value) {
        echo $assiArray[$key] . ' ';
        echo $key . ' ';
    }
    ?>
</p>


<p>
    <?
    // == value is compared
    // === value and type is compared

    ?>
</p>


    <p> Please pick color:</p>
    <form action="test.php" method="post">
        <label> <input name="color" value=""/> Color</label>
        <input type="submit" name="submit" value="Save">
    </form>

<? if (isset($_POST['color'])) {
    $color = $_POST['color'];
} else {
    $color = 'black';
}
echo "<p style=color:".$color."> Lorum ipsum default text bla bla </p>"
?>
