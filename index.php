<?php
// Inlcude the files
include_once 'settings.php';
include_once 'includes/picoblog.php';

// Instantiate the class with the source file
$mb = new \hxii\PicoBlog($url);

// Parse query string and get blog entries
$query = $mb->parseQuery();
$entries = ($query) ? $mb->getEntries($query) : $mb->getEntries('all');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $nick ?>@<?= $_SERVER['HTTP_HOST'] ?></title>
    <link rel="icon" href="<?php echo $avatar ?>">
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
    <h1>picoblog</h1>
    <?php
    // Display message and link to main list if viewing a filtered entry list
    if ($query!=false) {
        echo '<div>Currently viewing ' . implode('', $query) . '. Back to <a href="' . $_SERVER['PHP_SELF'] . '">list?</a></div>';
        echo($mb->renderEntries($entries, true, '{entry}'));
    } else {
      echo($mb->renderEntries($entries, false, '{entry}'));
    } ?>
</body>

</html>