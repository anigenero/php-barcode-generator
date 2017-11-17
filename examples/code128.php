<!DOCTYPE html>
<html lang="en-US">

<head>
    <title>Code 128 Barcode Generator</title>
</head>

<body>

<form action="code128.php" method="post">

    <label for="code-text">Enter Your Text Here: </label>
    <input id="code-text" type="text" style="width:200px;" name="text"/>

    <input type="submit" value="Create Barcode"/>

</form>

<?php

if ($_POST['text']) {

    include('../src/Code128Barcode.php');

    ob_start();
    imagepng(Code128Barcode::generate($_POST['text']));

    echo '<img src="data:image/png;base64,' . base64_encode(ob_get_clean()) . '" />';

} ?>

</body>

</html>