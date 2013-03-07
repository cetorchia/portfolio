<?php

require_once 'geturl.php';

$ip_cache = array();

/**
 * (c) Carlos Torchia (licensed under LGPL)
 * @return array( $city, $province, $country_code, $zip )
 */
function getLocation( $ip ) {
	// Try to get data from the cache
	if ( array_key_exists( $ip, $ip_cache) ) {
		return $ip_cache[ $ip ];
	}

	// Validate request
	if ( empty( $ip ) || !preg_match( '/^[\d\.]+$/', $ip ) ) {
		return array( null, null );
	}

	// Get the data from ipinfodb
	$data = geturl( 'http://api.ipinfodb.com/v3/ip-city/?key=' . trim( file_get_contents( dirname( __FILE__ ) . '/ipinfodb.key' ) ) . '&ip=' . $ip . '&format=json' );
	if ( empty( $data) ) {
		return array( null, null );
	}

	$info = json_decode( $data, true );

	// Extract the data
	$city = null;
	$province = null;
	$country_code = null;
	$zip = null;
	if ( !empty( $info[ 'cityName' ] ) ) {
		$city = ucwords( strtolower( $info[ 'cityName' ] ) );
	}
	if ( !empty( $info[ 'regionName' ] ) ) {
		$province = ucwords( strtolower( $info[ 'regionName' ] ) );
	}
	if ( !empty( $info[ 'countryCode' ] ) ) {
		$country_code = $info[ 'countryCode' ];
	}
	if ( !empty( $info[ 'zipCode' ] ) ) {
		$zip = $info[ 'zipCode' ];
	}

	$ipcache[ $ip ] = array( $city, $province, $country_code, $zip );
	return $ipcache[ $ip ];
}
