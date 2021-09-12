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
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><link rel="stylesheet" type="text/css" href="styles.css"><?php
  // Display message and link to main list if viewing a filtered entry list
if ($query!=false) {
  //echo '<div>Currently viewing ' . implode('', $query) . '. Back to <a href="' . $_SERVER['PHP_SELF'] . '">list?</a></div>';
  echo($mb->renderEntries($entries, true, '{entry}'));
} else {
  echo($mb->renderEntries($entries, false, '{entry}'));
} ?>
