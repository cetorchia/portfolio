<?php

/**
 * This script is intended as a temporary Thinklog in the inevitable
 * event that I am hacking at Thinklog and have nowhere nice to put
 * my thoughts. This is a simple list of thoughts stored in a flat
 * XML file.
 *
 * @author carlos
 *
 * (c) 2011 Carlos E. Torchia (GNU Public License)
 */

require_once("magic-quotes.php");

// Adds a thought to the file

function addThought($doc, $body, $keywords) {
  // Create the elements
  $thoughtElement = $doc->createElement("thought");
  $bodyElement = $doc->createElement("body", $body);
  $keywordsElement = $doc->createElement("keywords", $keywords);

  // Get the root node
  $thoughtsTags = $doc->getElementsByTagName("thoughts");
  if($thoughtsTags->length > 0) {
    $thoughtsNode = $thoughtsTags->item(0);
  }
  else {
    $thoughtsElement = $doc->createElement("thoughts");
    $thoughtsNode = $doc->appendChild($thoughtsElement);
  }

  // Add the thought element to the root node, and get back the thought
  // node that is in the document.
  $thoughtNode = $thoughtsNode->appendChild($thoughtElement);

  // Add the body and keywords elements to the tree
  $thoughtNode->appendChild($bodyElement);
  $thoughtNode->appendChild($keywordsElement);
}

// Imports thoughts from an external XML file
function importThoughts($doc, $url) {
  // Get XML from URL
  $opts = array("http" => array(
                "method"  => "GET",
                "header"  => "User-Agent: Thinklog\r\n",
  ));
  $context = stream_context_create($opts);
  $xml = file_get_contents($url, false, $context);

  // Extract thoughts from response
  $newDoc = new DOMDocument();
  $newDoc->loadXML($xml);

  $oldThoughts = getAllThoughts($doc, null);
  $newThoughts = getAllThoughts($newDoc, null);

  // Add each new thought
  foreach ($newThoughts as $key => $newThought) {
    // Check if it's not in the old thoughts
    if (!isset($oldThoughts[$key])) {
      addThought($doc, $newThought["body"], implode(' ', $newThought["keywords"]));
    }
  }  
}

// Displays the thought node list as a table

function displayThoughts($doc, $filter) {
  $filter = getKeywords($filter);
  $thoughts = getAllThoughts($doc, $filter);

  if(count($thoughts) == 0) {
    noSuchThoughts($filter);
    return;
  }

  echo "<h2>Thoughts</h2>\n";
  echo "<table border=\"1\">\n";

  foreach ($thoughts as $key => $thought) {
    displayThought($thought["body"], $thought["keywords"]);        
  }

  echo "</table>\n";
}

// Retrieves all thoughts in a map
function getAllThoughts($doc, $filter) {
  $thoughtMap = array();

  // Get the thought nodes from the document
  $thoughts = $doc->getElementsByTagName("thought");
  if($thoughts->length == 0) {
    return $thoughtMap;
  }

  $length = $thoughts->length;
  for ($i = $length - 1; $i >= 0; $i = $i - 1) {
    $thought = $thoughts->item($i);

    // Get the body tag for this thought
    $bodyTags = $thought->getElementsByTagName("body");

    if ($bodyTags->length > 0) {
      $body = $bodyTags->item(0)->nodeValue;

      $keywords = getThoughtKeywords($thought);

      // Display the thought if it is relevant
      if(!$filter || relevant($filter, $keywords)) {
        $thoughtMap[$body] = array("body" => $body, "keywords" => $keywords);
      }
    }
  }

  return $thoughtMap;
}

// Displays a message saying there are no such thoughts
function noSuchThoughts($filter) {
  if(!$filter) {
    echo "<p>None this month.</p>\n";
  }
  else if (count($filter) > 2) {
    $last = $filter[count($filter) - 1];
    unset($filter[count($filter) - 1]);
    echo "<p>No ideas about " . implode(", ", $filter) . ", and " . $last . ".</p>\n";
  }
  else if (count($filter) == 2) {
    echo "<p>No ideas about " . $filter[0] . " and " . $filter[1] . ".</p>\n";
  }
  else {
    echo "<p>No ideas about " . $filter[0] . ".</p>\n";
  }
}

// Displays the thought with this body and keyword array in HTML
function displayThought($body, $keywords) {
  // Print the row
  echo "<tr>\n";
  echo "<td>" . htmlentities($body) . "</td>\n";
  echo "<td>\n";
  foreach ($keywords as $keyword) {
    echo "<a href=\"thought.php?filter=" . htmlentities($keyword) . "\">" . htmlentities($keyword) . "</a>\n";
  }
  echo "</td>\n";
  echo "</tr>\n";
}

// Returns true iff each word in $filter is related to a word in $keywords
function relevant($filter, $keywords) {
  $value = 1;
  foreach ($filter as $word1) {
    $found = false;
    foreach ($keywords as $word2) {
      if (getRelationship($word1, $word2) >= 0.6 ) {
        $found = true;
      }
    }
    if (!$found) {
      return false;
    }
  }

  return true;
}

// Gets the relationship between 0 and 1 of these two words
function getRelationship($word1, $word2) {
  $n1 = strlen($word1);
  $n2 = strlen($word2);

  if ($n1 > $n2) {
    list($word1, $word2) = array($word2, $word1);
    list($n1, $n2) = array($n2, $n1);
  }

  list($word1, $word2) = array(strtolower($word1), strtolower($word2));

  // See what the largest substring of the smaller word is a substring
  // of the larger word.
  for ($i = $n1; $i >= 3; $i = $i - 1) {
    for ($j = 0; $j <= $n1 - $i; $j = $j + 1) {
      $word = substr($word1, $j, $i);
      if (strstr($word2, $word)) {
        return ($i / $n2);
      }
    }
  }

  return 0;
}

// Gets the keywords from the given thought node
function getThoughtKeywords($thought) {
  $keywordsTags = $thought->getElementsByTagName("keywords");
  $keywords = array();

  // Get the keywords of this thought
  if($keywordsTags->length > 0) {
    $keywords = getKeywords($keywordsTags->item(0)->nodeValue);
  }

  return $keywords;
}

// Splits the text into keywords
function getKeywords($text) {
  // Get filter keywords
  if($text) {
    $text = trim($text);
    $keywords = preg_split('/\s+/', $text);
  }
  else {
    $keywords = array();
  }

  return $keywords;
}

// Retrieves all possible keywords and their frequency
// In order from most common to least common
function getAllKeywords($doc) {
  $map = array();
  $nodes = $doc->getElementsByTagName("keywords");
  foreach ($nodes as $node) {
    $text = $node->nodeValue;
    $keywords = getKeywords($text);
    foreach($keywords as $keyword) {
      if(!isset($map[$keyword])) {
        $map[$keyword] = 0;
      }
      $map[$keyword] = $map[$keyword] + 1;
    }
  }
  // Now normalize each frequency to be relative
  $sum = array_sum($map);
  foreach ($map as $keyword => $frequency) {
    $map[$keyword] = $frequency / $sum;
  }
  arsort($map);

  return $map;
}

// Removes the least common keywords that are not even relatively common either
function getCommonUncommonKeywords($map) {
  $n = 0;
  $total = count($map);
  $common = array();
  $uncommon = array();
  foreach ($map as $keyword => $frequency) {
    $n = $n + 1;
    if ($n <= 5 || $frequency > 0.15) {
      $common[$keyword] = $frequency;
    }
    if ($n >= $total - 4 || $frequency < 0.000005) {
      $uncommon[$keyword] = $frequency;
    }
  }

  return array("common" => $common, "uncommon" => $uncommon);
}

// Get all keywords that were specified in a checkbox in the request
// Note: this returns a string
function getCheckboxKeywords() {
  global $_GET;
  $keywords = "";
  foreach ($_GET as $key => $value) {
    if (preg_match('/^keyword_/', $key)) {
      $keywords .= " " . $value;
    }
  }
  return $keywords;
}

// Displays the form for the user to enter a thought

function displayThoughtForm($doc) {
  echo "<h2>Add a thought...</h2>\n";
  echo "<form method=\"GET\" action=\"thought.php\">\n";
  echo "<p>\n";
  echo "<input type=\"text\" size=\"65\" name=\"body\" value=\"\" /><br />\n";
  echo "Keywords:\n";
  echo "<input type=\"text\" size=\"45\" name=\"keywords\" value=\"\" /><br />\n";
  echo "</p>\n";
  $keywords = getAllKeywords($doc);
  $commonUncommon = getCommonUncommonKeywords($keywords);
  $common = $commonUncommon["common"];
  $uncommon = $commonUncommon["uncommon"];
  echo "<p><b>On the surface:</b><br />";
  foreach($common as $keyword => $frequency) {
    $keyword = htmlentities($keyword);
    echo "<input type=\"checkbox\" name=\"keyword_$keyword\" value=\"$keyword\" />";
    echo "<span style=\"font-size: " . (sqrt(sqrt($frequency)) * 36) . "px;\">";
    echo "<a href=\"thought.php?filter=" . htmlentities($keyword) . "\">" . htmlentities($keyword) . "</a>\n";
    echo "</span>";
  }
  echo "</p>\n";
  echo "<p><b>What you are not paying attention to:</b><br />";
  foreach($uncommon as $keyword => $frequency) {
    $keyword = htmlentities($keyword);
    echo "<input type=\"checkbox\" name=\"keyword_$keyword\" value=\"$keyword\" />";
    echo "<span style=\"font-size: " . (sqrt(sqrt(1 - $frequency)) * 17) . "px;\">";
    echo "<a href=\"thought.php?filter=" . htmlentities($keyword) . "\">" . htmlentities($keyword) . "</a>\n";
    echo "</span>";
  }
  echo "</p>\n";
  echo "<input type=\"submit\" value=\"think\" />\n";
  echo "</form>\n";
}

// Displays the search bar
function displayFilterForm() {
  echo "<h2>Filter thoughts...</h2>\n";
  echo "<p>\n";
  echo "<form method=\"GET\" action=\"thought.php\">\n";
  echo "<input type=\"text\" size=\"65\" name=\"filter\" value=\"\" />\n";
  echo "<input type=\"submit\" value=\"filter\" />\n";
  echo "</form>\n";
  echo "</p>\n";
}

// Displays the import UI
function displayImportForm() {
  echo "<h2>Import thoughts...</h2>\n";
  echo "<p>\n";
  echo "<form method=\"GET\" action \"thought.php\">\n";
  echo "URL (XML): <input type=\"text\" size=\"65\" name=\"url\" value=\"\" />\n";
  echo "<input type=\"submit\" value=\"import\" />\n";
  echo "</form>\n";
  echo "</p>\n";
}

// Gets the filename for the thought data
function getFilename()
{
  return "./thoughts/" . date("Y-m") . ".xml";
}

// Get request vars
function getRequest()
{
  global $_GET;

  $request = array(
    "body" => null,
    "keywords" => null,
    "filter" => null,
    "url" => null,
  );

  if (isset($_GET["body"])) {
    $request["body"] = $_GET["body"];
  }

  if (isset($_GET["keywords"])) {
    $request["keywords"] = $_GET["keywords"];
    $request["keywords"] .= getCheckboxKeywords();
  }

  if (isset($_GET["filter"])) {
    $request["filter"] = $_GET["filter"];
  }

  if (isset($_GET["url"])) {
    $request["url"] = $_GET["url"];
  }

  return $request;
}

// The main method
function main() {
  $file = getFilename();

  $doc = new DOMDocument();
  $doc->load($file);

  $request = getRequest();

  if ($request["url"]) {
    importThoughts($doc, $request["url"]);

    // Save what we have back to the file
    $doc->save($file);

    // Return to the thought page
    header('Location: thought.php');
    exit();
  }

  if ($request["body"]) {
    addThought($doc, $request["body"], $request["keywords"]);

    // Save what we have back to the file
    $doc->save($file);

    // Return to the thought page
    header('Location: thought.php');
    exit();
  }

  header("Content-Type: text/html");
  echo "<html>\n";
  echo "<head><title>Thoughts</title>\n";
  echo "</head>\n";
  echo "<body>\n";
  echo "<h1><a href=\"thought.php\">Thoughts</a></h1>\n";

  displayThoughtForm($doc);

  displayFilterForm();

  displayImportForm();

  displayThoughts($doc, $request["filter"]);

  echo "</body>\n";
  echo "</html>\n";
}

main();

