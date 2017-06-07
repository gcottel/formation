<?php
/**
 * Created by PhpStorm.
 * User: gcottel
 * Date: 07/06/2017
 * Time: 17:22
 */

namespace App\Frontend;


use OCFram\BackController;

class FrontendController extends BackController
{
	use addButtonToPage;
	
	public function before()
	{
		if ($this->page()->format() == 'html')
		{
			$this->beforeHtml();
		}
	
	}
	
	public function beforeHtml() {
		$manager = $this->managers->getManagerOf('News');
		$listeNews = $manager->getList(0, 10);
		
		$this->addButtonToPage($this->page(), $listeNews);
		
	}
	
	public function after()
	{
		if ($this->page()->format() == 'html') {
			$this->afterHtml();
		}
	}
	
	public function afterHtml() {
			$this->managers;
		
	}
}