<?php
namespace App\Frontend\Modules\News;

use App\Frontend\addButtonToPage;
use App\Frontend\FrontendController;
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

class NewsController extends FrontendController
{
	use addButtonToPage;
	
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
        
        //$this->addButtonToPage($this->page());
    }

    public function executeShow(HTTPRequest $request)
    {
		
        $news = $this->managers->getManagerOf('News')->getUnique($request->getData('id'));

        if (empty($news))
        {
            $this->app->httpResponse()->redirect404();
        }
	
		
		$formBuilder = new CommentFormUserBuilder( new Comment, $this->managers->getManagerOf( 'User' ), $this->app->user() );
		
		$formBuilder->build();
		$form = $formBuilder->form();
		$this->page->addVar( 'form', $form->createView() );

        $this->page->addVar('title',$news->titre());
        $this->page->addVar('news', $news);
		$nombreComment = $this->app->config()->get('nombre_comment');
		$manager = $this->managers->getManagerOf('Comments');
		$commentList = $manager->getList(0, $nombreComment, $news->id());
        $this->page->addVar('comments', $commentList);
         /*
		var_dump($comment['contenu']);
		if (preg_match("#63#", $comment['contenu']))
		{
			$comment['contenu'] = $comment['contenu'].'aaaaaaaaaaaa';
		}*/
		



    }
	
	public function executeShowMoreJson(HTTPRequest $request)
	{
		
		$news = $request->postData('news');
		$Lastid = $request->postData('Lastid');
		$nombreComment = $this->app->config()->get('nombre_comment');
		$manager = $this->managers->getManagerOf('Comments');
		$commentList = $manager->getList($Lastid, $nombreComment, $news);
		$this->page->addVar('commentList', $commentList);
	}
    
	public function executeRefreshJson(HTTPRequest $request)
	{
		
		$news = $request->postData('news');
		$Lastid = $request->postData('Lastid');
		$manager = $this->managers->getManagerOf('Comments');
		$commentListDelete = $manager->getListDelete($Lastid, $news);
		$this->page->addVar('commentListDelete', $commentListDelete);
		$commentListUpdate = $manager->getListUpdate($Lastid, $news);
		$this->page->addVar('commentListUpdate', $commentListUpdate);
		$commentListAdd = $manager->getListAdd($Lastid, $news);
		$this->page->addVar('commentListAdd', $commentListAdd);
		
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
		$Comment= $request->postData('contenu');
		$Content= preg_replace("(\r\n|\n|\r)",' ',$Comment); //remplacesaut de lignes... etc par des ' '
		$Content_a = explode(' ', $Content);
		$FinalContent ='';
		require_once 'C:\Users\gcottel\Desktop\UwAmp\www\formation\vendor\curl\curl\src\Curl\Curl.php';
		
		foreach ( $Content_a as $content ):
			$content = trim($content); // supprime les ' '
						
			if (preg_match("#^https://www.youtube.com/watch\?v=#", $content) AND filter_var($content, FILTER_VALIDATE_URL)) // reconnais url youtube
			{
				var_dump('aaaaa');
				$curl = new \Curl\Curl();
				$curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
				$curl->get($content);
				var_dump($curl);
				if ($curl->response_headers[0] != 'HTTP/1.1 200 OK') //test si url reachable, si non, remplacement du lien
				{
					$content = '[Lien youtube périmé]';
				}
			}
			
			else if ((preg_match("#^https://youtu.be/#", $content)) AND filter_var($content, FILTER_VALIDATE_URL)) // autre type de lien (lien de partage)
			{
				$curl = new \Curl\Curl();
				$curl->setOpt(CURLOPT_SSL_VERIFYPEER, FALSE);
				$curl->get($content);
				if (preg_match("#Location#", $curl->response_headers[1])) //url de redirection faite par le lien de partage, Location peut être en première ou deuxième position dans la response
				{
					$response = $curl->response_headers[1];
				}
				else
				{
					$response = $curl->response_headers[2];
				}
				$response = substr($response, strlen(-$response) + 9); //supression de 'Location '
				if ($curl->response_headers[0] == 'HTTP/1.1 302 Found') //
				{
					$content = $response; //remplace le lien de partage part le lien de redirection
				}
				else
				{
					$content = '[Lien youtube périmé]';
				}
			}
			$FinalContent = $FinalContent.$content.' ';
		endforeach;
		
		if ( $request->method() == 'POST' AND !$this->app->user()->isAuthenticated()) {
			$comment = new Comment( [
				'news' => $request->getData('news'),
				'auteur' => 'Anonyme',
				'contenu' => $FinalContent
			] );
			
			
		}
		elseif ($request->method() == 'POST' AND $this->app->user()->isAuthenticated()){
			$comment = new Comment( [
				'news' => $request->getData('news'),
				'auteur' => $this->app->user()->getAttribute('User')->login(),
				'contenu' => $FinalContent
			] );
		}
		else
		{
			$comment = new Comment;
		}
		
		
		$formBuilder = new CommentFormUserBuilder( $comment, $this->managers->getManagerOf( 'User' ), $this->app->user() );
		
		
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
		$comment = $this->managers->getManagerOf( 'Comments' )->get( $request->postData( 'id' ) );
		
		if ( !$comment ) {
			$this->app->httpResponse()->redirect404();
			//$this->app->httpResponse()->addHeader( 'HTTP/1.0 404 Not Found ' );
			//$this->page->addVar( 'errors', 'Le commentaire n\'existe pas' );
		}
		else {
			$this->managers->getManagerOf( 'Comments' )->delete( $comment->id() );
		}
		$this->page->addVar( 'comment_id', $comment->id()  );
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
	
	
	public function executeUpdateCommentJson( HTTPRequest $request )
	{
			
		
		if ( $request->method() == 'POST' AND !$this->app->user()->isAuthenticated()) {
			$comment = new Comment( [
				'id' => $request->postData('id'),
				'auteur' => 'Anonyme',
				'contenu' => $request->postData('contenu')
			] );
			
			
		}
		elseif ($request->method() == 'POST' AND $this->app->user()->isAuthenticated()){
			$comment = new Comment( [
				'id' => $request->postData('id'),
				'auteur' => $this->app->user()->getAttribute('User')->login(),
				'contenu' => $request->postData('contenu')
			] );
		}
		else
		{
			$comment = $this->managers->getManagerOf('Comments')->get($request->postData('id'));
		}
		
		
		$formBuilder = new CommentFormUserBuilder( $comment, $this->managers->getManagerOf( 'User' ), $this->app->user() );
		
		$formBuilder->build();
		/** @var Form $form */
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