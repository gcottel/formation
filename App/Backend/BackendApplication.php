<?php
namespace App\Backend;

use \OCFram\Application;
use \OCFram\RouterFactory;

class BackendApplication extends Application
{
    public function __construct()
    {
        parent::__construct();

        $this->name = 'Backend';
    }

    public function run()
    {
        if ($this->user->isAdmin())
        {
            $controller = $this->getController();
        }
        else
        {
            $controller = new Modules\Connexion\ConnexionController($this, 'Connexion', 'index', 'html');
        }

        $controller->execute();

        $this->httpResponse->setPage($controller->page());
        $this->httpResponse->send();
    }
}



	
