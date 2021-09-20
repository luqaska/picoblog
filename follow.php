<?php
// Inlcude the files
include_once 'settings.php';
if($url_in_this_server==true){$url=$_SERVER['HTTP_HOST']."/".$url;}

if(isset($_POST["twtsocial_url"])){
  echo "<meta http-equiv=\"Refresh\" content=\"0; url='http://".$_POST["twtsocial_url"]."/follow?nick=".$nick."&url=".$url."'\" />";
}else{
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
  <a href="javascript:history.back()"><header>
    <h1><img src="<?= $avatar ?>" alt="Avatar"> <?= $name ?> <span id="nick" style="font-weight:normal;font-size:50%"><?php echo "({$nick}@{$_SERVER['HTTP_HOST']})" ?></span></h1>
  </header></a>
  <div id="follow_inst"><br>
    <a href="javascript:history.back()">Go back</a><br>
    <h1>Follow <?php echo "{$nick}@{$_SERVER['HTTP_HOST']}" ?></h1>
    <h2>Using a terminal client</h2>
    <p>Enter a TXTWT client, modify de placeholder with the name of the program and execute it: <code><?php echo("[software] follow $nick $url</code>"); ?></code></p>
    <h2>Using Twt.social</h2>
    <p><form method="POST"><label for="twtsocial_url">Instance URL: http://</label><input type="text" name="twtsocial_url"> <button>Follow</button></form></p>
  </div>
</body>

</html>
<?php } ?>
