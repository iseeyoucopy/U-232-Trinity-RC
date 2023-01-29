<?php

/**
 * -------   U-232 Codename Trinity   ----------*
 * ---------------------------------------------*
 * ------------  @author ShadoW69   ------------*
 * ---------------------------------------------*
 * -----  @site https://u-232.duckdns.org/  ----*
 * ---------------------------------------------*
 * -----  @copyright 2023 U-232 Team  ----------*
 * ---------------------------------------------*
 * ------------  @version V6  ------------------*
 */

class UrlBuilder {

	/**
	 * Build a url within the app
	 * @param string|null $url
	 * @param array $args [ 'example' => 123, 'foo' => 'bar' ]
	 * which produces the following for ex:
	 * https://site.url/?example=123&amp;foo=bar
	 * @param string $separator
	 * @return void
	 */
	public static function build( ?string $url = null, array $args = [], string $separator = '&amp;' ) : string {
		global $TRINITY20;

		// build query
		$get = $args ? '?' . http_build_query( $args, '', $separator ) : '';

		// if url not empty 
		if( ! empty( $url ) ){

			// left trim slash
			$url = ltrim( $url, '/' );

			// return baseurl appended by url and get parameters
			return rtrim( $TRINITY20['baseurl'], '/' ) . '/' . $url . $get;

		}

		// return baseurl appended by get parameters
		return rtrim( $TRINITY20['baseurl'], '/' ) . '/' . $get;

	}

}

/**
 * Wrapper function for UrlBuilder::build.
 * @param string|null $url
 * @param array $args [ 'example' => 123, 'foo' => 'bar' ]
 * which produces the following for ex:
 * https://site.url/?example=123&amp;foo=bar
 * @param string $separator
 * @return void
 */
function url( ?string $url = null, array $args = [], string $separator = '&amp;' ) : string {

	return UrlBuilder::build( $url, $args, $separator );

}

// TODO: move this function somewhere more appropriate
// prevent error if fn redirect is defined already
if( ! function_exists( 'redirect' ) ){

	/**
	 * Redirect to a specific resource
	 * @param string|null $url
	 * @param array $args
	 * @param string $separator
	 * @return void
	 */
	function redirect( ?string $url = null, array $args = [], string $separator = '&amp;' ) : void {

		// if missing http from url start we call
		// the url function to build our full url
		if( false === stripos( $url, 'http' ) ){

			$url = url( $url, $args, '&' );

		}

		// redirect to location
		header( sprintf( 'Location: %s', $url ) );

		// immediately stop script execution 
		exit;

	}

}
