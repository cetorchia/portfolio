<?php

/**
 * geturl (c) 2012 Carlos E. Torchia (licensed under LGPL, no warranty)
 *
 * Get contents of a URL through HTTP
 * using fsockopen(), so that you do not need
 * curl, just basic PHP.
 *
 * @param url: URL of the page e.g. http://google.ca/helloWorld.php?silly=true
 * @param follow (optional): whether to follow redirects; default true
 * @param timeout (optional): seconds to timeout; default 30
 *
 * @return array($data, $headers as $name => $value) if successful
 * @return null if unsuccessful
 */
function geturl($url, $follow=true, $timeout=30) {
	// Parse the URL
	$siteInfo = parse_url($url);
	if (isset($siteInfo["host"])) {
		$host = $siteInfo["host"];
	} else {
		return null;
	}
	if (isset($siteInfo["port"])) {
		$port = $siteInfo["port"];
	} else {
		$port = 80;
	}
	if (isset($siteInfo["path"])) {
		$uri = $siteInfo["path"];
		if (isset($siteInfo["query"])) {
			$uri .= "?".$siteInfo["query"];
		}
	} else {
		$uri = "/";
	}

	// Request and store the page
	$h = fsockopen($host, $port, $errno, $errstr, $timeout);
	if (!$h) {
		echo "<p>\n<b>geturl</b>: $errstr ($errno)\n</p>\n";
		return null;
	} else {
		$inData = "GET $uri HTTP/1.1\r\n";
		$inData .= "Host: $host\r\n";
		$inData .= "Connection: Close\r\n\r\n";
		fwrite($h, $inData);
		$outData = "";
		while (!feof($h)) {
			$outData .= fgets($h, 128);
		}
		fclose($h);
	}

	// Recurse if we are redirected
	if ($follow && preg_match('/Location: (.*)\r\n/', $outData, $match)) {
		$url = $match[1];
		return geturl($url, $timeout);
	}

	// Return the response
	$s = preg_split("/\r\n\r\n/", $outData);
	return $s[1];
}
