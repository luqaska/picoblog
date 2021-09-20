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
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo "{$name} ({$nick}@{$_SERVER['HTTP_HOST']})" ?></title>
  <link rel="icon" href="<?php echo $avatar ?>">
  <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
  <a href="?"><header>
    <h1><img src="<?= $avatar ?>" alt="Avatar"> <?= $name ?> <span id="nick" style="font-weight:normal;font-size:50%"><?php echo "({$nick}@{$_SERVER['HTTP_HOST']})" ?></span></h1>
  </header></a>
  <div id="subh"><br>
    <desc><?= $description ?><?php echo("<a style='color:#e0e0e0;cursor:default'>.</a><a href='$url'><button id='follow'>Follow</button></a>"); ?></desc>
    <p><?php if($ubication!=false){
      echo("<span><i class='fa fa-map-marker'></i> $ubication</span>");
    } ?>
    <?php if($website!=false){
      echo("<span><i class='fa fa-globe'></i> <a href='http://$website'>$website</a></span>");
    } ?></p>
  </div><br>
  <?php
  // Display message and link to main list if viewing a filtered entry list
  if ($query!=false) {
    //echo '<div>Currently viewing ' . implode('', $query) . '. Back to <a href="' . $_SERVER['PHP_SELF'] . '">list?</a></div>';
    echo($mb->renderEntries($entries, true, '{entry}'));
  } else {
    echo($mb->renderEntries($entries, false, '{entry}'));
  } ?>
</body>

</html>
