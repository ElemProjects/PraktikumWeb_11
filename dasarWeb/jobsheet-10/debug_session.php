<?php
session_start();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Debug Session</title>
</head>
<body>

<h1>Debug Session</h1>

<pre><?php print_r($_SESSION); ?></pre>

</body>
</html>