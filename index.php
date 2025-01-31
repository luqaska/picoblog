<?php include_once 'settings.php';
if(isset($_GET["feed"])){
	if($https==true){$furl="https://";}else{$furl="http://";}
	$furl .= "$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
	header("content-type: text/plain");
	echo "# nick\t\t= {$nick}\n# url\t\t= {$furl}\n# avatar\t= {$avatar}\n# description\t= {$description}\n";
	include_once $route;
}else{
include_once 'includes/picoblog.php';
$mb = new \hxii\PicoBlog($route);
$query = $mb->parseQuery();
$entries = ($query) ? $mb->getEntries($query) : $mb->getEntries('all'); if(isset($_GET["tag"])!=true){$css="#subh{margin:0 0 10px 0;}".$css;} ?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo "{$name} ({$nick}@{$_SERVER['HTTP_HOST']})" ?></title>
	<link rel="icon" href="<?php echo $avatar ?>">
	<link rel="stylesheet" type="text/css" href="styles.css">
	<style>
<?= $css ?>
	</style>
</head>
<body>
	<header>
		<h1><img src="<?= $avatar ?>" alt="Avatar"> <?= $name ?> <span id="nick" style="font-weight:normal;font-size:50%"><?php echo "({$nick}@{$_SERVER['HTTP_HOST']})" ?></span></h1>
	</header>
	<div id="subh"><br>
		<desc><?= $description ?><?php echo("<a style='color:transparent;cursor:default'>.</a><a href='?feed'><button id='follow'>Follow</button></a>"); ?></desc>
		<p><?php if($ubication!=false){
			echo("<span><i class='fa fa-map-marker'></i> $ubication</span>");
		} ?>
		<?php if($website!=false){
			echo("<span><i class='fa fa-globe'></i> <a href='http://$website'>$website</a></span>");
		} ?></p>
	</div>
	<?php
	// Display message and link to main list if viewing a filtered entry list
	if ($query==false or isset($_GET["tag"])) {
		//echo '<div>Currently viewing ' . implode('', $query) . '. Back to <a href="' . $_SERVER['PHP_SELF'] . '">list?</a></div>';
		if(isset($_GET["tag"])){echo "<h1 class='st' id='$_GET[tag]'>#$_GET[tag]</h1>";}
		echo($mb->renderEntries($entries, false, '{entry}'));
	} else {
		echo($mb->renderEntries($entries, true, '{entry}'));
} ?>

	<footer><span style="float:left">&copy;2021 <a href="https://lucas.koyu.space">luqaska</a></span>.<a style="float:right" href="https://github.com/luqaska/twtxt-frontend">Code</a></footer>
</body>
</html>
<?php } ?>
