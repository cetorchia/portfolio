<?php

//
// PTAS implementation of the 0/1 knapsack problem
// Carlos E. Torchia, 2011-04-09
//
// This software is licensed under the GNU GPL v2.
// It can be distributed freely under certain conditions; see fsf.org.
// There is no warranty, use at your own risk.
//

//
// Given: max weight $w, a set of $weights i => w_i, and $values i => v_i
//        approximate the set that maximizes sum(v_i) with sum(w_i) <= $w
// Note: i must start at 1
//
// Return: the approximate value of the set, within a factor of (1 - $e)
//

define("DEBUG_MODE",false);

function knapsack($weights, $values, $w, $e)
{
	$m = count($weights);				// Number of elements
	$opt = array();						// Memoization of min weights

	// Scale the values
	$k = scaleValues($values, $e);
	foreach($values as $n => $value) {
		debug("($n,$weights[$n],$value)\n");
	}
	$total_value = array_sum($values);	// Total value
	debug("Total value: $total_value\n");

	// Set OPT(i,0) = 0
	for($n = 0; $n <= $m; $n = $n + 1) {
		$opt[$n][0] = 0;
	}

	// Set OPT(0,v) = inf for v > 0
	for($v = 1; $v <= $total_value; $v = $v + 1) {
		$opt[0][$v] = "INF";			// infinity
	}

	// Given value $v, find the minimum weight subset that has total value $v
	// with indices 0 <= i <= $n

	for($v = 1; $v <= $total_value; $v = $v + 1)
	{
		for($n = 1; $n <= $m; $n = $n + 1)
		{
			if($values[$n] <= $v && is_numeric($opt[$n - 1][$v - $values[$n]])) {
				$opt[$n][$v] = myMin(
					$opt[$n - 1][$v],
					$weights[$n] + $opt[$n - 1][$v - $values[$n]]
				);
				debug("OPT(".($n-1).",".($v-$values[$n]).") = " . $opt[$n-1][$v-$values[$n]] . ", ");
				debug("OPT(".($n-1).",".$v.") = ".$opt[$n-1][$v]." ");
			}
			else {
				$opt[$n][$v] = $opt[$n - 1][$v];
				debug("OPT(".($n-1).",".$v.") = ".$opt[$n-1][$v]." ");
			}

			debug("--> OPT($n,$v) = " . $opt[$n][$v] . "\n");
		}
	}

	// Find the best $v
	for($v = $total_value; $v >= 0; $v = $v - 1)
	{
		// Non-infinite minimum weight less than $w for such a high value?
		if($opt[$m][$v] != "INF" && $opt[$m][$v] <= $w) {
			return($v * $k);
		}
	}

	// Couldn't attain any value
	return 0;
}

// Scales the values for the epsilon factor $e
function scaleValues(&$values, $e)
{
	$max_value = max($values);			// Max. value of any element
	$m = count($values);

	// Get the scale factor
	$k = $e * $max_value / $m;

	// Scale each value
	foreach($values as $i => $value)
	{
		$values[$i] = floor($value / $k);
	}

	// Return the scale factor
	return $k;
}

// Finds the minimum, excludes "INF"
function myMin($a,$b) {
	if($a == "INF") return $b;
	if($b == "INF") return $a;
	return(min($a,$b));
}

// Debug statements
function debug($s)
{
	if(DEBUG_MODE) {
		echo $s;
	}
}

?>

<html>

<head>
<title>PTAS 0/1 Knapsack solver</title>
<meta http-equiv="Expires" content="0" />
</head>

<body>
<h1>PTAS 0/1 Knapsack solver</h1>
<p>By Carlos E. Torchia, 2011-04-09</p>
<p>
This script uses a polynomial time approximation scheme to compute the value
of a subset of weight less than W within a factor of 1-epsilon of the maximal value
subset with weight less than W.
</p>
<p>
The input file must be in the following format:
<pre>
n
   1 w1 v1
   2 w2 v2
   ...
   n wn vn
W
</pre>
where n is the number of elements, wi/vi are the weights and values of each
and W is the maximum weight of the knapsack.
</p>
<p>
<form method="post" enctype="multipart/form-data">
Epsilon: <input type="text" name="epsilon" /><br />
Problem file: <input type="file" name="problem" /><br />
<input type="submit" value="solve" />
</form>
</p>
<p>
<pre>
<?php

	// Produce output
	if(isset($_FILES["problem"]))
	{
		echo "Output:\n";

		//
		// Parse the input file
		//

		$filename = $_FILES["problem"]["tmp_name"];
		$lines = preg_split('/\n/',file_get_contents($filename));

		// Remove blank lines
		foreach($lines as $i => $line) {
			if(preg_match('/^\s*$/',$line)) {
				unset($lines[$i]);
			}
		}

		// Process each line
		$weights = array();
		$values = array();
		$i = 0;
		$numLines = count($lines);
		foreach($lines as $line) {
			if($i == 0) {
				if(preg_match('/^s*(\d+)\s*$/',$line,$matches)) {
					$m = $matches[1];
				}
			}
			else if($i == $numLines - 1) {
				if(preg_match('/^s*(\d+)\s*$/',$line,$matches)) {
					$w = $matches[1];
				}
			}
			else {
				if(preg_match('/^\s*(\d+)\s+(\d+)\s+(\d+)\s*$/',$line,$matches)) {
					$n = $matches[1];
					$weight = $matches[2];
					$weights[$n] = $weight;
					$value = $matches[3];
					$values[$n] = $value;
				}
			}
			$i = $i + 1;
		}
		$e = $_POST["epsilon"];
		debug("Epsilon: $e\n");

		// Run the algorithm
		$val = knapsack($weights, $values, $w, $e);
		echo "Approx Max Subset Value: $val\n";
	}

?>
</pre>

</body>

</html>
