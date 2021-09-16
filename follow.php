<?php
// Inlcude the files
include_once 'settings.php';
if($url_in_this_server==true){$url=$_SERVER['HTTP_HOST']."/".$url;}
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo "{$name} ({$nick}@{$_SERVER['HTTP_HOST']})" ?></title>
  <link rel="icon" href="<?php echo $avatar ?>">
  <link rel="stylesheet" type="text/css" href="styles.css">
</head>

<body>
  <a href="javascript:history.back()"><header>
    <h1><img src="<?= $avatar ?>" alt="Avatar"> <?= $name ?> <span id="nick" style="font-weight:normal;font-size:50%"><?php echo "({$nick}@{$_SERVER['HTTP_HOST']})" ?></span></h1>
  </header></a>
  <div id="follow_inst"><br>
    <a href="javascript:history.back()">Go back</a><br>
    <h2>Follow <?php echo "{$nick}@{$_SERVER['HTTP_HOST']}" ?></h2>
    <p>Enter a TXTWT client, modify de placeholder with the name of the program and execute it: <code><?php echo("[software] follow $nick $url</code>"); ?></code></p>
  </div>
</body>

</html>
