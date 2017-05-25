<?php
namespace App\Backend\Modules\Connexion;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\User;
use \FormBuilder\UserFormBuilder;
use \OCFram\FormHandler;
use \OCFram\RouterFactory;

class ConnexionController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Connexion');

        if ($request->postExists('login'))
        {
            $login = $request->postData('login');
            $password = $request->postData('password');

            $manager = $this->managers->getManagerOf( 'User' );

            if ( $User = $manager->getUserByLoginOrEmail( $login ) )
            {

                if ($password == $User->password())
                {
                    $this->app->user()->setAuthenticated(true);
					$this->app->user()->setAttribute('User', $User );
                    $this->app->user()->setFlash('Connexion réussie');
                    $this->app->httpResponse()->redirect(RouterFactory::getRouter( 'Backend' )->getUrl( 'News', 'index' ));
                }
                else
                {
                    $this->app->user()->setFlash('Le mot de passe est incorrect.');
                }
            }

            else
            {
                $this->app->user()->setFlash('Le login est incorrect.');
            }
        }


    }


    public function executelogOut(HTTPRequest $request)
    {
        //$this->app->user()->setAuthenticated(false);
        session_unset();
        session_destroy();
        $this->app->httpResponse()->redirect(RouterFactory::getRouter( 'Backend' )->getUrl( 'News', 'index' ));
    }


    public function executeDelete(HTTPRequest $request)
    {
        $userId = $request->getData('id');

        $this->managers->getManagerOf('User')->delete($userId);

        $this->app->user()->setFlash('L\'utilisateur a bien été supprimée !');

        $this->app->httpResponse()->redirect(RouterFactory::getRouter( 'Backend' )->getUrl( 'News', 'index' ));
    }


    public function executeInsert(HTTPRequest $request)
    {
        $this->processForm($request);

        $this->page->addVar('title', 'Ajout d\'un utilisateur');
    }

    public function executeUpdate(HTTPRequest $request)
    {
        $this->processForm($request);

        $this->page->addVar('title', 'Modification d\'un utilisateur');
    }

    public function processForm(HTTPRequest $request)
    {
        if ($request->method() == 'POST')
        {
            $user = new User([
                'login' => $request->postData('login'),
                'password' => $request->postData('password'),
                'passwordVerfication' => $request->postData('passwordVerification'),
                'email' => $request->postData('email'),
                'emailVerification' => $request->postData('emailVerification'),
                'firstName' => $request->postData('firstName'),
                'lastName' => $request->postData('lastName'),
                'birthDate' => $request->postData('birthDate')
            ]);

            if ($request->getExists('id'))
            {
                $user->setId($request->getData('id'));
            }
        }
        else
        {
            // L'identifiant de l'utilisateur est transmis si on veut le modifier
            if ($request->getExists('id'))
            {
                $user = $this->managers->getManagerOf('User')->getUnique($request->getData('id'));
            }
            else
            {
                $user = new User;
            }
        }

        $formBuilder = new UserFormBuilder($user);
        $formBuilder->build();

        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('User'), $request);

        if ($formHandler->process())
        {
            $this->app->user()->setFlash($user->isNew() ? 'L\'utilisateur a bien été ajouté !' : 'L\'utilisateur a bien été modifié !');

            $this->app->httpResponse()->redirect(RouterFactory::getRouter( 'Backend' )->getUrl( 'News', 'index' ));
        }

        $this->page->addVar('form', $form->createView());
    }
}