<?php // Settings (most of them will be eliminated soon)
$url="blog.txt";
$name = "Name";
$nick = "luqaska";
$avatar = "";
$description = "Q?";
$ubication = "";
$website = ""; // Please! Don't put the protocol (https:// | http://)

// Utterances (using og:title)
$utterances = false;
$utterances_repo = "owner/repo";
$utterances_label = "label"; //Leave this in blank if you don't need it
$utterances_theme = "github-dark";

// Custom CSS
$css = '
/* Put your CSS here */
';

// Defaulters
if($name==false){$name=$nick;}if($avatar==false){$avatar='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>👤</text></svg>';} ?>
