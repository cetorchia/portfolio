<?php

function getObjects($filename)
{
	$objects = array();
	$newObject = false;
	$title = "null";

	$h = fopen($filename,"r");

	while(($l = fgets($h)))
	{
		$l = preg_replace('/\n$/', '', $l);

		// Blank line
		if(strlen($l) == 0)
		{
			$newObject = true;
		}

		// Title of new object
		else if($newObject)
		{
			$title = $l;
			$objects[$title] = array();
			$newObject = false;
		}

		// Add line to object
		else
		{
			$objects[$title][] = $l;
		}
	}

	fclose($h);

	return($objects);
}

function printList($filename, $style = null)
{
	// Print all the habits!
	$id = sha1($filename);
	$title = getTitle($filename);
	echo "<div class=\"section\">\n";
	printLink($filename,$id);
	echo "<div id=\"".htmlentities($id)."\" style=\"".htmlentities($style)."\">\n";
	$objects = getObjects($filename);

	foreach($objects as $title => $rows)
	{
		if($title != "null")
		{
			printContent($title,"h2");
		}

		echo "<ul>\n";

		foreach($rows as $row)
		{
			printContent($row,"li");
		}

		echo "</ul>\n";
	}

	printEdit($filename,$id);
	echo "</div>\n";
	echo "</div>\n";
}

function printTable($filename, $style = null)
{
	$id = sha1($filename);
	$title = getTitle($filename);
	echo "<div class=\"section\">\n";
	printLink($filename,$id);
	echo "<div id=\"".htmlentities($id)."\" style=\"".htmlentities($style)."\">\n";
	$objects = getObjects($filename);

	foreach($objects as $title => $rows)
	{
		if($title != "null")
		{
			printContent($title,"h2");
		}

		echo "<table>\n";

		foreach($rows as $row)
		{
			echo "<tr>\n";
			$tuple = preg_split('/\s-\s/', $row);
			foreach($tuple as $value)
			{
				printContent($value,"td");
			}
			echo "</tr>\n";
		}

		echo "</table>\n";
	}

	printEdit($filename,$id);
	echo "</div>\n";
	echo "</div>\n";
}

// Allow user to edit
function printEdit($filename, $id)
{
	$content = file_get_contents($filename);
	$n = preg_match_all("/\n/",$content,$matches) + 1;

	// Print the form
	echo "<form id=\"".htmlentities($id)."edit\" method=\"post\" action=\"index.php\" style=\"display:none\">\n";
	echo "<input type=\"hidden\" name=\"filename\" value=\"".htmlentities($filename)."\" />\n";
	echo "<textarea rows=\"".htmlentities($n)."\" style=\"width: 100%\" name=\"content\">\n";
	if(preg_match("/^\n/",$content)) echo "&#10;";
	printContent($content);
	echo "</textarea>\n";
	echo "<input type=\"submit\" value=\"save\" />";
	echo "</form>\n";
	echo "<a href=\"javascript:toggle('".htmlentities($id)."edit')\">edit</a>\n";
}

function printLink($filename,$id)
{
	echo "<h1><a href=\"javascript:toggle('".htmlentities($id)."')\">".getTitle($filename)."</a></h1>\n";
}

function getTitle($filename)
{
	$title = "null";
	if(preg_match('/([^\/]+)$/',$filename,$matches))
	{
		$title = $matches[1];
	}

	return $title;
}

function printContent($val,$tag = null)
{
	$str = htmlentities($val);

	if(isset($tag))
	{
		$str = "<$tag>" . $str . "</$tag>";
	}

	echo $str;
}
