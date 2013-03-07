<?php
require_once 'location.php';

$h = fopen( './clients.csv', 'a' );
list( $city, $province, $country, $zip ) = getLocation( $_SERVER[ 'REMOTE_ADDR' ] );
fputcsv(
	$h,
	array(
		date('r'),
		$_SERVER[ 'REMOTE_HOST' ],
		$_SERVER[ 'REMOTE_ADDR' ],
		$city, $province, $country, $zip,
		$_SERVER[ 'HTTP_REFERER' ],
		$_SERVER[ 'HTTP_USER_AGENT' ],
		$_SERVER[ 'HTTP_ACCEPT_LANGUAGE' ],
	)
);
fclose( $h );
