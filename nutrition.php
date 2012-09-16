<?php

//
// Interface to FOOD_DB for nutritional information
// (c) 2010 Carlos E. Torchia
//
// This software is licensed under the GNU GPL v2.
// It can be distributed freely under certain conditions; see fsf.org.
// There is no warranty, use at your own risk.
//

// Connect to the MySQL database
//if(!mysql_connect("sql09.freemysql.net", "ctorchia87", "Z4r54aV"))
if(!mysql_connect("localhost", "www-data", "ablative"))
{
	die("Couldn't connect to database: ".mysql_error());
}
//mysql_select_db("nutrition87");
mysql_select_db("FOOD_DB");

// Get the food and nutrient being queried.
$food = isset($_GET["food"]) ? $_GET["food"] : null;
$foodId = isset($_GET["food_id"]) ? $_GET["food_id"] : null;
$nutrient = isset($_GET["nutrient"]) ? $_GET["nutrient"] : null;
$nutrientId = isset($_GET["nutrient_id"]) ? $_GET["nutrient_id"] : null;
$measureId = isset($_GET["measure_id"]) ? $_GET["measure_id"] : null;

// Create title and body
$title = "Nutritional information: $food, $nutrient";
$body = "<h1>$title</h1>\n";
$body .= "<p><a href=\"nutrition.php\">Start over</a></p>";

// Carry out whatever queries are requested

$body .= drawNutrientAmount($foodId, $nutrientId, $measureId);

$body .= "<form action=\"nutrition.php\" method=\"get\">\n";
$body .= drawSearch($food, $nutrient);
$body .= drawFoods($food, $foodId);
$body .= drawMeasures($foodId, $measureId);
$body .= drawNutrients($nutrient, $nutrientId);
$body .= "<input type=\"submit\" value=\"submit\" />\n";
$body .= "</form>\n";

// Write the output
drawPage($title,$body);


//
// This function draws the page for searching for food and nutrients
//
function drawSearch($food, $nutrient)
{
	$output = "<div>";

	$output .= "Food: <input name=\"food\" type=\"text\" value=\"" .
		($food ? htmlentities($food) : "") .
		"\" />\n";

	$output .= "Nutrient: <input name=\"nutrient\" type=\"text\" value=\"" .
		($nutrient ? htmlentities($nutrient) : "") .
		"\" /> (e.g. \"calories\" or \"vitamin\")\n";

	$output .= "</div>\n";

	return $output;
}

// Show the food selection
function drawFoods($food, $myFoodId)
{
	if(!$food)
	{
		return("");
	}

	$output = "<div>\n";

	if(isset($food))
	{
		$output .= "<h3>Foods</h3>\n";

		$foods = getFoods($food);
		$output .= "<p>\n";
		foreach($foods as $foodId => $foodName)
		{
			$checked = (isset($myFoodId) && ($foodId == $myFoodId)) ? "checked" : "";
			$output .= "<input name=\"food_id\" type=\"radio\" value=\"$foodId\" $checked />\n";
			$output .= "$foodName\n";
			$output .= "<br />\n";
		}
		$output .= "</p>\n";
	}

	$output .= "</div>\n";

	return $output;
}

// Show the nutrient selection
function drawNutrients($nutrient, $myNutrientId)
{
	if(!$nutrient)
	{
		return("");
	}

	$output = "<div>\n";

	if(isset($nutrient))
	{
		$output .= "<h3>Nutrients</h3>\n";

		$nutrients = getNutrients($nutrient);
		$output .= "<p>\n";
		foreach($nutrients as $nutrientId => $nutrientName)
		{
			$checked = (isset($myNutrientId) && ($nutrientId == $myNutrientId)) ? "checked" : "";
			$output .= "<input name=\"nutrient_id\" type=\"radio\" value=\"$nutrientId\" $checked />\n";
			$output .= "$nutrientName\n";
			$output .= "<br />\n";
		}
		$output .= "</p>\n";
	}

	$output .= "</div>\n";

	return $output;
}

// Show the serving conversion factors
function drawMeasures($foodId, $myMeasureId)
{
	if(!$foodId)
	{
		return("");
	}

	$output = "<div>\n";

	if(isset($foodId))
	{
		$output .= "<h3>Servings</h3>\n";

		$measures = getMeasures($foodId);
		$output .= "<p>\n";
		foreach($measures as $measureId => $measureName)
		{
			$checked = (isset($myMeasureId) && ($measureId == $myMeasureId)) ? "checked" : "";
			$output .= "<input name=\"measure_id\" type=\"radio\" value=\"$measureId\" $checked />\n";
			$output .= "$measureName\n";
			$output .= "<br />\n";
		}
		$output .= "</p>\n";
	}

	$output .= "</div>\n";

	return $output;
}

// Show the nutrient amount
function drawNutrientAmount($foodId, $nutrientId, $measureId)
{
	if(!($foodId && $nutrientId && $measureId))
	{
		return("");
	}

	$output = "<div>\n";

	if(isset($foodId))
	{
		$nutrientAmount = getNutrientAmount($foodId, $nutrientId, $measureId);
		$output .= "<p><b>Amount</b>: \n";
		$output .= $nutrientAmount["value"];
		$output .= " " . $nutrientAmount["unit"];
		$output .= "</p>\n";
	}

	$output .= "</div>\n";

	return $output;
}

//
// This function prints out the HTML for the title and body.
//
function drawPage($title, $body)
{
	// Set up the page
	header("Content-type: text/html");
	header("Expires: 0");
	echo "<html>\n";
	echo "<head>\n";
	echo "<meta http-equiv=\"Expires\" content=\"0\" />\n";
	echo "<title>$title</title>";
	echo "</head>\n";
	echo "<body>\n";
	echo "$body\n";
	echo "<p>(c) 2010 Carlos E. Torchia</p>\n";
	echo "<p>\n";
	echo "This software is licensed under the GNU GPL v2. It can be\n";
	echo "freely distributed under certain conditions; see <a href=\"http://www.fsf.org\">fsf.org</a>.\n";
	echo "There is no warranty, use at your own risk.\n";
	echo "</p>\n";
	echo "</body>\n";
	echo "</html>\n";
}

//
// Queries the database for matching foods
// Returns a food id to food name mapping
//
function getFoods($food)
{
	// Send the command to the database
	$food = mysql_real_escape_string($food);
	$query = "select FD_ID, L_FD_NME from FOOD_NM where L_FD_NME like '%$food%'";
	$result = mysql_query($query);
	if(!$result)
	{
		die("Could not get foods from database: ".mysql_error());
	}

	// Process the rows and return the data
	$foods = array();
	while(($row = mysql_fetch_array($result)))
	{
		$foods[$row["FD_ID"]] = $row["L_FD_NME"];
	}
	return $foods;
}

//
// Queries the database for matching nutrients
// Returns a nutrient id to nutrient name mapping.
//
function getNutrients($nutrient)
{
	// Send the command to the database
	$nutrient = mysql_real_escape_string($nutrient);
	$query = "select NT_ID, NT_NME from NT_NM where NT_NME like '%$nutrient%'";
	$result = mysql_query($query);
	if(!$result)
	{
		die("Could not get nutrients from database: ".mysql_error());
	}

	// Process the rows and return the data
	$nutrients = array();
	while(($row = mysql_fetch_array($result)))
	{
		$nutrients[$row["NT_ID"]] = $row["NT_NME"];
	}
	return $nutrients;
}

//
// Queries the database for the measures associated with the given food id
// Returns a measure id to measure name mapping.
//
function getMeasures($foodId)
{
	// Send the command to the database
	$foodId = mysql_real_escape_string($foodId);
	$query = "select MEASURE.MSR_ID as MSR_ID, MSR_NME as MSR_NME " .
		"from MEASURE, CONV_FAC " .
		"where MEASURE.MSR_ID=CONV_FAC.MSR_ID and FD_ID = $foodId ";
	$result = mysql_query($query);
	if(!$result)
	{
		die("Could not get measures from database: ".mysql_error());
	}

	// Process the rows and return the data
	$measures = array();
	while(($row = mysql_fetch_array($result)))
	{
		$measures[$row["MSR_ID"]] = $row["MSR_NME"];
	}
	return $measures;
}

//
// Queries the database for the nutrient amount for the given food id and nutrient id, 
// and converts to the proper quantity it using the conversion factor for the 
// given measure id and food id.
//
// Returns a mapping with 'value' as the amount of the nutrient in the food, and 
// 'unit' as the units in which it is measured.
//
function getNutrientAmount($foodId, $nutrientId, $measureId)
{
	// Send the command to the database
	$foodId = mysql_real_escape_string($foodId);
	$nutrientId = mysql_real_escape_string($nutrientId);
	$measureId = mysql_real_escape_string($measureId);
	$query = "select NT_VALUE*CONV_FAC.CONV_FAC as value, UNIT as unit " .
		"from NT_AMT, CONV_FAC, NT_NM " .
		"where NT_AMT.FD_ID=CONV_FAC.FD_ID " .
		"  and NT_AMT.NT_ID=NT_NM.NT_ID " .
		"  and NT_AMT.FD_ID=$foodId " .
		"  and NT_AMT.NT_ID=$nutrientId " .
		"  and CONV_FAC.MSR_ID=$measureId "
	;
	$result = mysql_query($query);
	if(!$result)
	{
		die("Could not get nutrition amounts from database: ".mysql_error());
	}

	// Process the rows and return the data
	$nutrientAmount = array();
	while(($row = mysql_fetch_array($result)))
	{
		$nutrientAmount["value"] = $row["value"];
		$nutrientAmount["unit"] = $row["unit"];
	}
	return $nutrientAmount;
}
