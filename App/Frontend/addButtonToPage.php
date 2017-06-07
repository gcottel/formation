<?php
/**
 * Created by PhpStorm.
 * User: gcottel
 * Date: 07/06/2017
 * Time: 16:40
 */
namespace App\Frontend;
trait addButtonToPage
{
	public function addButtontoPage($page, $listNews ) {
		
		if ($this->app->user())
		{
			$user = $this->app->user();
		}
		else
		{
			$user = $this->user();
		}
		
		$bouton_connexion = $this->getButton( 'bouton_connexion' );
		$bouton_logout = $this->getButton( 'bouton_logout' );
		$bouton_mynews = $this->getButton( 'bouton_mynews' );
		$bouton_ajouterunenews = $this->getButton( 'bouton_ajouterunenews' );
		$bouton_signin = $this->getButton( 'bouton_signin' );
		
		if ( $user->isAuthenticated() ) {
			$List_bouton_a = [
				$bouton_mynews,
				$bouton_ajouterunenews,
				$bouton_logout
			];
			$page->addVar( 'List_bouton_a', $List_bouton_a );
		}
		else {
			$List_bouton_a = [
				$bouton_connexion,
				$bouton_signin
			];
			$page->addVar( 'List_bouton_a', $List_bouton_a );
		}
		
	}
	
	private function getButton($name)
	{
		if ($this->app->config())
		{
			$bouton_xml = $this->app->config()->get($name);
		}
		else
		{
			$bouton_xml = $this->config()->get($name);
		}
			
		
		list($app, $module, $action, $name) = explode(',', $bouton_xml);
		$bouton_connexion = ['app'=>$app, 'module'=>$module, 'action'=>$action, 'name'=>$name];
		return $bouton_connexion;
	}
}