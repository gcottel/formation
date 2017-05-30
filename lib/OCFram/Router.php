<?php
namespace OCFram;

class Router
{
    protected $routes = [];

    const NO_ROUTE = 1;
	
	public function addRoute( Route $route ) {
		if ( !in_array( $route, $this->routes ) ) {
			$this->routes[$route->module().'|'.$route->action().'|'.$route->format()]  = $route;
		}
	}

    public function getRoute($url)
    {
        foreach ($this->routes as $route)
        {
            // Si la route correspond à l'URL
            if (($varsValues = $route->match($url)) !== false)
            {
                // Si elle a des variables
                if ($route->hasVars())
                {
                    $varsNames = $route->varsNames();
                    $listVars = [];

                    // On crée un nouveau tableau clé/valeur
                    // (clé = nom de la variable, valeur = sa valeur)
                    foreach ($varsValues as $key => $match)
                    {
                        // La première valeur contient entièrement la chaine capturée (voir la doc sur preg_match)
                        if ($key !== 0)
                        {
                            $listVars[$varsNames[$key - 1]] = $match;
                        }
                    }

                    // On assigne ce tableau de variables � la route
                    $route->setVars($listVars);
                }

                return $route;
            }
        }

        throw new \RuntimeException('Aucune route ne correspond à l\'URL', self::NO_ROUTE);
    }

    
	public function getUrl( $module, $action,array $vars = [], $format = 'html' )
	{
		if ($format == false)
		{
			$format = 'html';
		}
		
		$route = $this->routes[ $module . '|' . $action . '|' . $format ];
		
		if ( !empty( $route ) )
		{
			if ( $route->hasVars() )
			{
				$url = $route->pattern();
				foreach ( $vars as $key => $var ) {
					$url = str_replace( '{{' . $key . '}}', $var, $url );
				}
				
				return $url;
			}
			
			return $route->url();
		}
		throw new \RuntimeException( 'Aucune route ne correspond à ' . $module . ':' . $action. ':' . $format, self::NO_ROUTE );
	}
}