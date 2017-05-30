<?php
/**
 * Created by PhpStorm.
 * User: gcottel
 * Date: 24/05/2017
 * Time: 17:08
 */

namespace OCFram;


class RouterFactory
{
	protected static $RouterForApplication = [];
	
	
	public static function getRouter( $applicationName ) {
		self::buildRouteur( $applicationName );
		
		return self::$RouterForApplication[ $applicationName ];
	}
	

	private static function buildRouteur( $applicationName ) {
		if ( isset( self::$RouterForApplication[ $applicationName ] ) )
		{
			return;
		}
		$router = new Router;
		
		$xml = new \DOMDocument;
		$xml->load( __DIR__ . '/../../App/' . $applicationName . '/Config/routes.xml' );
		
		$routes = $xml->getElementsByTagName( 'route' );
		
		foreach ( $routes as $route )
		{
			$vars = [];
			
			// If $route has some attributes in the url
			if ( $route->hasAttribute( 'vars' ) )
			{
				$vars = explode( ',', $route->getAttribute( 'vars' ) );
			}
			
			$format  = ( $route->getAttribute( 'format' ) ) ? $route->getAttribute( 'format' ) : 'html';
			$pattern = ( $route->getAttribute( 'pattern' ) ) ? $route->getAttribute( 'pattern' ) : $route->getAttribute( 'url' );
			if ( $format != 'html' ) {
				$pattern .= '.'.$format;
			}
			
			
			$router->addRoute( new Route( $route->getAttribute( 'url' ), $route->getAttribute( 'module' ), $route->getAttribute('action'), $pattern, $format, $vars ) );
			
		}
		
		self::$RouterForApplication[ $applicationName ] = $router;
	}
	
}