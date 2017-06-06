<?php
namespace Model;

use \Entity\Comment;

class CommentsManagerPDO extends CommentsManager
{
    protected function add(Comment $comment)
    {
        $q = $this->dao->prepare('INSERT INTO comments SET news = :news, auteur = :auteur, contenu = :contenu, date = NOW(), state = 1');

        $q->bindValue(':news', $comment->news(), \PDO::PARAM_INT);
        $q->bindValue(':auteur', $comment->auteur(), \PDO::PARAM_STR);
        $q->bindValue(':contenu', $comment->contenu(), \PDO::PARAM_STR);

        $q->execute();
		//var_dump('ici');
		$Comment_new = $this->get($this->dao->lastInsertId());
		$comment->setId($Comment_new->id());
		$comment->setDate($Comment_new->date());
        //var_dump($comment);
        //$comment->setId($this->dao->lastInsertId());
    }

    public function delete($id)
    {
        $this->dao->exec('UPDATE comments SET state = 2, date = NOW() WHERE id ='.(int) $id);
    }

    public function deleteFromNews($news)
    {
        $this->dao->exec('UPDATE comments SET state = 2, date = NOW() WHERE news = '.(int) $news);
    }

    public function getListOf($news)
    {
        if (!ctype_digit($news))
        {
            throw new \InvalidArgumentException('L\'identifiant de la news passé doit être un nombre entier valide');
        }

        $q = $this->dao->prepare('SELECT id, news, auteur, contenu, date FROM comments WHERE news = :news AND state = 1');
        $q->bindValue(':news', $news, \PDO::PARAM_INT);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        $comments = $q->fetchAll();

        foreach ($comments as $comment)
        {
            $comment->setDate(new \DateTime($comment->date()));
        }

        return $comments;
    }

    protected function modify(Comment $comment)
    {
		date_default_timezone_set('Europe/Paris');
        $q = $this->dao->prepare('UPDATE comments SET auteur = :auteur, contenu = :contenu, date = :date, state = 3 WHERE id = :id');
	
		$date = date("Y-m-d H:i:s");
		$q->bindValue('date', $date);
        $q->bindValue(':auteur', $comment->auteur(), \PDO::PARAM_STR);
        $q->bindValue(':contenu', $comment->contenu(), \PDO::PARAM_STR);
        $q->bindValue(':id', $comment->id(), \PDO::PARAM_INT);
	
		$Comment_new = $this->get($comment->id());
		$comment->setDate($Comment_new->date());

        $q->execute();
    }

    public function get($id)
    {
        $q = $this->dao->prepare('SELECT id, news, auteur, contenu,date FROM comments WHERE id = :id');
        $q->bindValue(':id', (int) $id, \PDO::PARAM_INT);
        $q->execute();

        $q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');

        $Comment = $q->fetch();
	
		$Comment->setDate(new \DateTime($Comment->date()));
		return $Comment;
    }
	
	public function getNewsId( $id ) {

		$q = $this->dao->prepare( 'SELECT news FROM comments WHERE id = :id' );
		$q->bindValue( ':id', (int)$id, \PDO::PARAM_INT );
		$q->execute();
		
		return $q->fetchColumn();
	}
	
	public function getLastDateAuthor( $id ) {
		
		$q = $this->dao->prepare( 'SELECT date FROM comments WHERE id = :id' );
		$q->bindValue( ':id', (int)$id, \PDO::PARAM_INT );
		$q->execute();
		
		return $q->fetchColumn();
	}
	
	
	public function getList($debut = -1, $limite = -1, $news)
	{
		
		$sql = 'SELECT id, news, auteur, contenu, date FROM comments WHERE news = :news AND (state = 1 OR state = 3) ORDER BY id DESC';
		date_default_timezone_set('Europe/Paris');
		
		if ($debut != -1 || $limite != -1)
		{
			$sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
		}
		
		$q = $this->dao->prepare($sql);
		$q->bindValue(':news', $news, \PDO::PARAM_INT);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
		
		$commentList = $q->fetchAll();
		
		foreach ($commentList as $comment)
		{
			$comment->setDate(new \DateTime($comment->date()));
		}
		
		$q->closeCursor();
		
		return $commentList;
	
		
	}
	
	public function getListDelete($debut = -1, $limite = -1, $news)
	{
		date_default_timezone_set('Europe/Paris');
		/*
		$test = 'SELECT date, TIMESTAMPDIFF(second, date, :date) FROM comments WHERE news = :news AND TIMESTAMPDIFF(second, date, :date) < 8000';
		$date = date("Y-m-d H:i:s");
		$p = $this->dao->prepare($test);
		$p->bindValue(':news', $news, \PDO::PARAM_INT);
		$p->bindValue(':date', $date);
		$p->execute();
		$p->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
		
		$aa = $p->fetchAll();
		var_dump($aa);*/
		
		$sql = 'SELECT id FROM comments WHERE news = :news AND state = 2 AND TIMESTAMPDIFF(second, date, :date) < 10 ';
		
		
		if ($debut != -1 || $limite != -1)
		{
			$sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
		}
		
		$date = date("Y-m-d H:i:s");
		$q = $this->dao->prepare($sql);
		$q->bindValue(':news', $news, \PDO::PARAM_INT);
		$q->bindValue(':date', $date);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
		
		$commentListDelete = $q->fetchAll();
		
		foreach ($commentListDelete as $comment)
		{
			$comment->setDate(new \DateTime($comment->date()));
		}
		
		$q->closeCursor();
		
		return $commentListDelete;
		
		
	}
	
	
	public function getListUpdate($debut = -1, $limite = -1, $news)
	{
		
		$sql = 'SELECT id, news, auteur, contenu, date FROM comments WHERE TIMESTAMPDIFF(second, date, :date) < 10 AND news = :news AND state = 3 ';
		date_default_timezone_set('Europe/Paris');
		
		if ($debut != -1 || $limite != -1)
		{
			$sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
		}
		
		$date = date("Y-m-d H:i:s");

		
		$q = $this->dao->prepare($sql);
		$q->bindValue(':news', $news, \PDO::PARAM_INT);
		$q->bindValue(':date', $date);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
		
		$commentListUpdate = $q->fetchAll();
		
		foreach ($commentListUpdate as $comment)
		{
			$comment->setDate(new \DateTime($comment->date()));
		}
		
		$q->closeCursor();
		
		return $commentListUpdate;
		
		
	}
	
	
	public function getListAdd($debut = -1, $limite = -1, $news)
	{
		
		$sql = 'SELECT id, news, auteur, contenu, date FROM comments WHERE TIMESTAMPDIFF(second, date, :date) < 10 AND news = :news AND state = 1 ';
		date_default_timezone_set('Europe/Paris');
		
		if ($debut != -1 || $limite != -1)
		{
			$sql .= ' LIMIT '.(int) $limite.' OFFSET '.(int) $debut;
		}
		
		$date = date("Y-m-d H:i:s");
		
		
		$q = $this->dao->prepare($sql);
		$q->bindValue(':news', $news, \PDO::PARAM_INT);
		$q->bindValue(':date', $date);
		$q->execute();
		
		$q->setFetchMode(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\Comment');
		
		$commentListAdd = $q->fetchAll();
		
		foreach ($commentListAdd as $comment)
		{
			$comment->setDate(new \DateTime($comment->date()));
		}
		
		$q->closeCursor();
		
		return $commentListAdd;
		
		
	}
	
	
}