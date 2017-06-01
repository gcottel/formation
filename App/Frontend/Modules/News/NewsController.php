<?php
namespace App\Frontend\Modules\News;

use \OCFram\BackController;
use \OCFram\HTTPRequest;
use \Entity\Comment;
use \FormBuilder\CommentFormBuilder;
use \OCFram\FormHandler;
use \Entity\News;
use \FormBuilder\NewsFormUserBuilder;
use \OCFram\RouterFactory;
use \FormBuilder\CommentFormUserBuilder;


//require \Mobile_Detect;

class NewsController extends BackController
{
    public function executeIndex(HTTPRequest $request)
    {
        $nombreNews = $this->app->config()->get('nombre_news');
        $nombreCaracteres = $this->app->config()->get('nombre_caracteres');

        // On ajoute une définition pour le titre.
        $this->page->addVar('title', 'Liste des '.$nombreNews.' dernières news');

        // On récupère le manager des news.
        $manager = $this->managers->getManagerOf('News');

        $listeNews = $manager->getList(0, $nombreNews);

        foreach ($listeNews as $news)
        {
            if (strlen($news->contenu()) > $nombreCaracteres)
            {
                $debut = substr($news->contenu(), 0, $nombreCaracteres);
                $debut = substr($debut, 0, strrpos($debut, ' ')) . '...';

                $news->setContenu($debut);
            }
        }

        // On ajoute la variable $listeNews à la vue.
        $this->page->addVar('listeNews', $listeNews);
    }

    public function executeShow(HTTPRequest $request)
    {
        $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));

        if (empty($news))
        {
            $this->app->httpResponse()->redirect404();
        }
	
		if ($this->app->user()->isAuthenticated()){
			$formBuilder = new CommentFormUserBuilder( new Comment, $this->managers->getManagerOf( 'User' ), $this->app->user() );
		}
		else {
			$formBuilder = new CommentFormBuilder( new Comment, $this->managers->getManagerOf( 'User' ), $this->app->user() );
		}
		$formBuilder->build();
		$form = $formBuilder->form();
		$this->page->addVar( 'form', $form->createView() );

        $this->page->addVar('title',$news->titre());
        $this->page->addVar('news', $news);
        $this->page->addVar('comments', $this->managers->getManagerOf('Comments')->getListOf($news->id()));
    }
    
    
	
	public function executeInsertComment(HTTPRequest $request)
	{
		// Si le formulaire a été envoyé.
		if ( $request->method() == 'POST' AND !$this->app->user()->isAuthenticated()) {
			$comment = new Comment( [
				'news' => $request->getData('news'),
				'auteur' => $request->postData('auteur'),
				'contenu' => $request->postData('contenu')
			] );
			
			
		}
		elseif ($request->method() == 'POST' AND $this->app->user()->isAuthenticated()){
			$comment = new Comment( [
				'news' => $request->getData('news'),
				'auteur' => $this->app->user()->getAttribute('User')->login(),
				'contenu' => $request->postData('contenu')
			] );
		}
		else
		{
			$comment = new Comment;
		}
		
		if ($this->app->user()->isAuthenticated()){
			$formBuilder = new CommentFormUserBuilder( $comment, $this->managers->getManagerOf( 'User' ), $this->app->user() );
		}
		else {
			$formBuilder = new CommentFormBuilder( $comment, $this->managers->getManagerOf( 'User' ), $this->app->user() );
		}
		
		$formBuilder->build();
		$form = $formBuilder->form();
		$formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);
		if ($formHandler->process())
		{
			$this->app->user()->setFlash('Le commentaire a bien été ajouté, merci !');
			$this->app->httpResponse()->redirect(RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'show', [ 'id' => $request->getData('id') ] ));
		}
		$this->page->addVar('comment', $comment);
		$this->page->addVar('form', $form->createView());
		$this->page->addVar('title', 'Ajout d\'un commentaire');
	}
	
	
	public function executeInsertCommentJson( HTTPRequest $request )
	{
		
		if ( $request->method() == 'POST' AND !$this->app->user()->isAuthenticated()) {
			$comment = new Comment( [
				'news' => $request->getData('news'),
				'auteur' => $request->postData('auteur'),
				'contenu' => $request->postData('contenu')
			] );
			
			
		}
		elseif ($request->method() == 'POST' AND $this->app->user()->isAuthenticated()){
			$comment = new Comment( [
				'news' => $request->getData('news'),
				'auteur' => $this->app->user()->getAttribute('User')->login(),
				'contenu' => $request->postData('contenu')
			] );
		}
		else
		{
			$comment = new Comment;
		}
		
		if ($this->app->user()->isAuthenticated()){
			$formBuilder = new CommentFormUserBuilder( $comment, $this->managers->getManagerOf( 'User' ), $this->app->user() );
		}
		else {
			$formBuilder = new CommentFormBuilder( $comment, $this->managers->getManagerOf( 'User' ), $this->app->user() );
		}
		
		$formBuilder->build();
		
		$form = $formBuilder->form();
		
		$formHandler = new FormHandler( $form, $this->managers->getManagerOf( 'Comments' ), $request );
		
		if ( $formHandler->process() ) {
			//var_dump('0');
			//var_dump($request->postData( 'auteur' ));
			//var_dump('1');
			//var_dump($this->managers->getManagerOf( 'Comments' )->getLastDateAuthor( $request->postData( 'author' ) ));
			//$comment->setDate($this->managers->getManagerOf( 'Comments' )->getLastDateAuthor( $request->postData( 'author' ) ));
			$this->page->addVar( 'comment', $comment );
			//var_dump($comment);
			$this->page->addVar( 'comment_auteur', $this->app->user()->getAttribute( 'user' )[ 'login' ] );
			//$this->app->httpResponse()->redirect(RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'show', [ 'id' => $comment['news'] ] ));
		}
		else{
			$this->app->httpResponse()->addHeader('HTTP/1.0 404 Error');
			$this->page->addVar('errors', 'Une erreur est survenue');
		}
	}
	
	
	public function executeIndexMyNews(HTTPRequest $request)
    {
        $this->page->addVar('title', 'Gestion des news');

        $manager = $this->managers->getManagerOf('News');

        $this->page->addVar('listeNews', $manager->getListUser(-1,-1,$this->app->user()->getAttribute('User')->login()));
        $this->page->addVar('nombreNews', $manager->countMyNews($this->app->user()->getAttribute('User')->login()));
    }

    public function executeInsert(HTTPRequest $request)
    {
        $this->processForm($request);

        $this->page->addVar('title', 'Ajout d\'une news');
    }

    public function executeUpdate(HTTPRequest $request)
    {
        $this->processForm($request);

        $this->page->addVar('title', 'Modification d\'une news');
    }
	
	

    public function processForm(HTTPRequest $request)
    {
        if ($request->method() == 'POST')
        {
            $news = new News([
                'auteur' => $this->app->user()->getAttribute('User')->login(),
                'titre' => $request->postData('titre'),
                'contenu' => $request->postData('contenu')
            ]);

            if ($request->getExists('id'))
            {
                $news->setId($request->getData('id'));
            }
        }
        else
        {
            // L'identifiant de la news est transmis si on veut la modifier
            if ($request->getExists('id'))
            {
                $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));
            }
            else
            {
                $news = new News;
            }
        }

        $formBuilder = new NewsFormUserBuilder($news);
        $formBuilder->build();

        $form = $formBuilder->form();

        $formHandler = new FormHandler($form, $this->managers->getManagerOf('News'), $request);

        if ($formHandler->process())
        {
            $this->app->user()->setFlash($news->isNew() ? 'La news a bien été ajoutée !' : 'La news a bien été modifiée !');

            $this->app->httpResponse()->redirect(RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'IndexMyNews' ));
        }

        $this->page->addVar('form', $form->createView());
    }
	
	public function executeDelete(HTTPRequest $request)
	{
		$newsId = $request->getData('id');
		
		$this->managers->getManagerOf('News')->delete($newsId);
		$this->managers->getManagerOf('Comments')->deleteFromNews($newsId);
		
		$this->app->user()->setFlash('La news a bien été supprimée !');
		
		$this->app->httpResponse()->redirect(RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'IndexMyNews' ));
	}
	
	public function executeDeleteComment( HTTPRequest $request )
	{
    	
		$newsId = $this->managers->getManagerOf( 'Comments' )->getNewsId( $request->getData( 'id' ) );
		$this->managers->getManagerOf( 'Comments' )->delete( $request->getData( 'id' ) );
		
		$this->app->user()->setFlash( 'Le commentaire a bien été supprimé !' );
		
		$this->app->httpResponse()->redirect( RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'show', [ 'id' => $newsId ] ) );
	}
	
	/**
	 *
	 *
	 * @param HTTPRequest $request
	 */
	
	public function executeDeleteCommentJson( HTTPRequest $request ) {
		$comment = $this->managers->getManagerOf( 'Comments' )->get( $request->getData( 'id' ) );
		
		if ( !$comment ) {
			$this->app->httpResponse()->addHeader( 'HTTP/1.0 404 Not Found ' );
			$this->page->addVar( 'errors', 'Le commentaire n\'existe pas' );
		}
		else {
			$this->managers->getManagerOf( 'Comments' )->delete( $request->getData( 'id' ) );
		}
		$this->page->addVar( 'comment_id', $request->getData( 'id' ) );
	}
	
	
	
	public function executeUpdateComment(HTTPRequest $request)
	{
		$this->page->addVar('title', 'Modification d\'un commentaire');
		
		
		
		
		
		if ($request->method() == 'POST')
		{
			$comment = new Comment([
				'id' => $request->getData('id'),
				'auteur' => $request->postData('auteur'),
				'contenu' => $request->postData('contenu')
			]);
		}
		else
		{
			//$Comment = $this->managers->getManagerOf('Comment')->getCommentUsingId($request->getData('id'));
			//if ( null === $Comment ) {
				// erreur ccommentaire non existant
				//return;
			//}
			
			$comment = $this->managers->getManagerOf('Comments')->get($request->getData('id'));
		}
		
		if ($this->app->user()->isAuthenticated()){
			$formBuilder = new CommentFormUserBuilder( $comment, $this->managers->getManagerOf( 'User' ), $this->app->user() );
		}
		else {
			$formBuilder = new CommentFormBuilder( $comment, $this->managers->getManagerOf( 'User' ), $this->app->user() );
		}
		$formBuilder->build();
		
		$form = $formBuilder->form();
		
		$formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);
		
		if ($formHandler->process())
		{
			$this->app->user()->setFlash('Le commentaire a bien été modifié');
			$newsId = $this->managers->getManagerOf( 'Comments' )->getNewsId( $request->getData( 'id' ) );
			
			$this->app->httpResponse()->redirect(self::getLinkToShow(new News(['id' => $newsId])));
		}
		
		$this->page->addVar('form', $form->createView());
	}
	
	/**
	 * Fonction de generation du lien vers l'action show du controlleur news du frontend
	 *
	 * @param News $News
	 *
	 * @return string
	 */
	public static function getLinkToShow( News $News ) {
		return RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'show', [ 'id' => $News->id() ]);
	}
	
	public function executeUpdateCommentFormJson( HTTPRequest $request )
	{
		
	}
	
	
	public function executeUpdateCommentJson( HTTPRequest $request )
	{
			
		
		if ( $request->method() == 'POST' AND !$this->app->user()->isAuthenticated()) {
			$comment = new Comment( [
				'id' => $request->postData('id'),
				'news' => $request->getData('news'),
				'auteur' => $request->postData('auteur'),
				'contenu' => $request->postData('contenu')
			] );
			
			
		}
		elseif ($request->method() == 'POST' AND $this->app->user()->isAuthenticated()){
			$comment = new Comment( [
				'id' => $request->postData('id'),
				'news' => $request->getData('news'),
				'auteur' => $this->app->user()->getAttribute('User')->login(),
				'contenu' => $request->postData('contenu')
			] );
		}
		else
		{
			$comment = $this->managers->getManagerOf('Comments')->get($request->getData('id'));
		}
		
		if ($this->app->user()->isAuthenticated()){
			$formBuilder = new CommentFormUserBuilder( $comment, $this->managers->getManagerOf( 'User' ), $this->app->user() );
		}
		else {
			$formBuilder = new CommentFormBuilder( $comment, $this->managers->getManagerOf( 'User' ), $this->app->user() );
		}
		$formBuilder->build();
		
		$form = $formBuilder->form();
		
		$formHandler = new FormHandler($form, $this->managers->getManagerOf('Comments'), $request);
		
		if ( $formHandler->process() ) {
			//var_dump('0');
			//var_dump($request->postData( 'auteur' ));
			//var_dump('1');
			//var_dump($this->managers->getManagerOf( 'Comments' )->getLastDateAuthor( $request->postData( 'author' ) ));
			//$comment->setDate($this->managers->getManagerOf( 'Comments' )->getLastDateAuthor( $request->postData( 'author' ) ));
			$this->page->addVar( 'comment', $comment );
			//var_dump($comment);
			$this->page->addVar( 'comment_auteur', $this->app->user()->getAttribute( 'user' )[ 'login' ] );
			//$this->app->httpResponse()->redirect(RouterFactory::getRouter( 'Frontend' )->getUrl( 'News', 'show', [ 'id' => $comment['news'] ] ));
		}
		else{
			$this->app->httpResponse()->addHeader('HTTP/1.0 404 Error');
			$this->page->addVar('errors', 'Une erreur est survenue');
		}
	}

}