<?php

/**
 * 2015-02-14: WTF is this
 */

function getColor($dir)
{
	$randomPart = 24;
	$countWeight = 0.5;		// How much number of words contributes to colour

	$h = opendir($dir);
	if(preg_match('/[^\/]$/',$dir))
	{
		$dir = $dir . '/';
	}

	$y = "";
	while(($filename = readdir($h)))
	{
		$y = $y . " " . file_get_contents($dir . $filename);
	}
	$n = 127 - min(count(split(" ",$y))*$countWeight,127);

	$v1 = 255-$randomPart - $n - rand(0,$randomPart);
	$v3 = 128 + $n - rand(0,$randomPart);
	//$v2 = round(($v1 + $v3) / 2 + rand(0,$randomPart));
	$v2 = 128;

	$x1 = sprintf("%02x",$v1);
	$x2 = sprintf("%02x",$v2);
	$x3 = sprintf("%02x",$v3);

	$colour = "#" . $x1.$x2.$x3;

	return $colour;
}


