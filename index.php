<?php

require_once("only-localhost.php");
require_once("magic-quotes.php");
require_once("render.php");
require_once("request.php");
require_once("color.php");

$bkg = getColor("/home/carlos/life/");

?>

<html>
<head>
<meta http-equiv="Expires" content="0" />
<link rel="StyleSheet" href="style.css" type="text/css" />
<title>Home</title>
<script type="text/javascript" src="toggle.js"></script>
</head>

<?php echo "<body style=\"background: ".htmlentities($bkg)."\">\n"; ?>

<div id="left_side" style="float: left; width: 320px">

	<?php printList("/home/carlos/life/Mind", "display:none"); ?>

</div>

<div id="right_side" style="float: right; width: 320px">

	<?php printList("/home/carlos/life/Projects", "display:none"); ?>

	<?php printTable("/home/carlos/life/Schedule", "display:none"); ?>

</div>

</body>

</html>
