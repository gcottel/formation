<?php
/**
 * Created by PhpStorm.
 * User: gcottel
 * Date: 07/06/2017
 * Time: 11:48
 */

namespace OCFram;


class AttributMenu
{
	protected $app,
		$module,
		$action,
		$name;
	
	use Hydrator;
	
	public function __construct(array $donnees = [])
	{
		if (!empty($donnees))
		{
			$this->hydrate($donnees);
		}
	}

	
	public function setApp($app)
	{
		$this->app = $app;
	}
	
	public function setModule($module)
	{
		$this->module = $module;
	}
	
	public function setAction($action)
	{
		$this->action = $action;
	}
	
	public function setName($name)
	{
		$this->name = $name;
	}
	
	
	public function app()
	{
		return $this->app;
	}
	
	public function module()
	{
		return $this->module;
	}
	
	public function action()
	{
		return $this->action;
	}
	
	public function name()
	{
		return $this->name;
	}
	
	
}