<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="data:image/x-icon;," type="image/x-icon">
    <link rel="stylesheet" href="assets/css/assets.css">
    <script src="assets/js/app.js"></script>
    <title><?=isset($title)?$title:"Document";?></title>
</head>
<body>
    <?php require_once "_header.php";?>
    <main>
        <?=$main?>
    </main>
    <?php require_once "_footer.php" ;?>
</body>
</html>