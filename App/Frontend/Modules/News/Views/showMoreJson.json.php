<?php
/**
 * @var
 */

/**
 * Created by PhpStorm.
 * User: gcottel
 * Date: 02/06/2017
 * Time: 15:21
 */
ob_end_flush();

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

return [$commentList,$loginIfIsAuthenticated];