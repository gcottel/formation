<?php
namespace App\Frontend;

use \OCFram\Application;
use OCFram\AttributMenu;
use OCFram\BackController;

class FrontendApplication extends Application
{
	use addButtonToPage;
	
    public function __construct()
    {
        parent::__construct();

        $this->name = 'Frontend';
    }
	
	

    public function run()
    {
        $controller = $this->getController();
		$controller->before();
        $controller->execute();
		$controller->after();
		$page = $controller->page();
		$user = $this->user();
		
		if ($page->format() == 'html')
		{
			//$this->addButtonToPage($page);
		}
		
        $this->httpResponse->setPage($page);
				
        $this->httpResponse->send();
    }
	
	

}