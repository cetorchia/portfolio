<?php

// Handle requests
// (Remove magic quotes before please)

if(isset($_POST["filename"]) && isset($_POST["content"]))
{
	$filename = $_POST["filename"];
	$content = $_POST["content"];

	$h = fopen($filename,"w");
	$content = preg_replace("/\r\n/","\n",$content);
	fwrite($h,$content);
	fclose($h);
}
