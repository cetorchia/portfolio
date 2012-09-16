<?php

//
// Generates the RNA secondary structures of an RNA sequence
//
// This software is licensed under the GNU GPL v2.
// It can be distributed freely under certain conditions; see fsf.org.
// There is no warranty, use at your own risk.
//

function rnass($sequence)
{
	$M  = array();		// memoization
	$i  = 0; $j = strlen($sequence);
	$a = opt($sequence,$M,$i,$j);
	foreach($a as $ss) {
		printSS($ss);
	}
	echo "Count: " . count($a) . " <br />\n";
}

//
// Computes OPT(i,j), the optimal RNA secondary structure for sequence
// elements $i through $j for $sequence. Output the secondary structures
//
// OPT(i,j) = 0 if i >= j - 4
// OPT(i,j) = max{OPT(i,j-1), max{1+OPT(i,t-1)+OPT(t+1,j-1) | st,sj are a valid base pair}} otherwise
//
// In otherwords, consider the cases where s[j] is not paired up with anybody
// and where s[j] is paired up with some s[t]. If it is paired up with s[t],
// then consider all the possible pairings of elements s[i] to s[t-1] with each 
// other and all the possible pairings of elements s[t+1] to s[j-1].
//

function opt($sequence,&$M,$i,$j)
{
	if(!isset($M[$i][$j]))
	{
		if($i >= $j - 4)
		{
			$M[$i][$j] = array(array());
		}

		else
		{
			$a = getPossibilities($sequence,$M,$i,$j);
			getMax($a);
			$M[$i][$j] = $a;
		}
	}

	return $M[$i][$j];
}

// Gets the possibilities adding the next base pair between i and j,
// or not adding any at all.
function getPossibilities($sequence,&$M,$i,$j)
{
	// Compute the values
	$a = opt($sequence,$M,$i,$j-1);
	for($t = $i; $t < $j - 4; $t = $t + 1)
	{
		if(validBasePair(substr($sequence,$t,1),substr($sequence,$j,1)))
		{
			// Choose base pair t,j
			//   We've got to tie each of the possibilities for i..t-1 with
			//   the possibilities for t+1..j-1.
			$opt1 = opt($sequence,$M,$i,$t-1);
			$opt2 = opt($sequence,$M,$t+1,$j-1);
			foreach($opt1 as $ss1) {
				foreach($opt2 as $ss2) {
					$ss = array();
					foreach($ss1 as $pair) $ss[] = $pair;
					foreach($ss2 as $pair) $ss[] = $pair;
					$ss[] = "$t $j";
					$a[] = $ss;
				}
			}
		}
	}

	return($a);
}

//
// Filters the set for sets that are maximal in size
//

function getMax(&$a)
{
	// Get maximum
	$m = null;
	foreach($a as $i => $ss) {
		if(!isset($m) || ($m < count($ss))) {
			$m = count($ss);
		}
	}

	// Filter for maximal sets
	foreach($a as $i => $ss) {
		if(count($ss) < $m) {
			unset($a[$i]);			// Remove it if it isn't maximal
		}
	}
}

//
// The predicate such that bases $a and $b make a valid base pair.
//

function validBasePair($a,$b)
{
	if((($a == "G") && ($b == "C")) ||
	   (($b == "G") && ($a == "C")) ||
	   (($a == "A") && ($b == "U")) ||
	   (($b == "A") && ($a == "U")))
	{
		return(true);
	}

	return(false);
}

// Prints the secondary structure
function printSS($ss)
{
	foreach($ss as $pair)
	{
		echo "($pair) ";
	}

	echo "<br />\n";
}

//
// Output the document, along with any responses to input sequence
//

echo "<html>\n";
echo "<head>\n";
echo "<title>RNASS: An RNA secondary structure generator</title>\n";
echo "</head>\n";
echo "<body>\n";
echo "<h1>RNASS: An RNA secondary structure generator</h1>\n";
echo "<p>By Carlos Torchia</p>\n";

echo "<p>\n";
echo "<i>Examples</i>: GC, GUAUAUAC, GUAUAUCA, CAGAUCGGCGAUACGAGCAUAGCAAUGCUAAGCGAGCUUAGCUGCA\n";
echo "</p>\n";

echo "<p>\n";
echo "<form action=\"rnass.php\" method=\"get\">\n";
echo "Sequence: <input name=\"q\" type=\"text\" />\n";
echo "<input type=\"submit\" value=\"submit\" />\n";
echo "</form>\n";
echo "</p>\n";

if(isset($_GET["q"]))
{
	rnass($_GET["q"]);
}

echo "</body>\n";
echo "</html>\n";
