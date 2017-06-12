<?php
/**
 * Created by PhpStorm.
 * User: gcottel
 * Date: 09/06/2017
 * Time: 15:09
 */
//var_dump($Lastnews_a);
if ($this->app->user()->isAdmin())
{
	$loginIfIsAuthenticated = -1;
}
elseif ($this->app->user()->isAuthenticated())
{
	
	$loginIfIsAuthenticated = $this->app->user()->getAttribute('User')->login();
}
else
{
	$loginIfIsAuthenticated = NULL;
}

return ['Lastnews_a' => $Lastnews_a, 'loginIfIsAuthenticated' => $loginIfIsAuthenticated];