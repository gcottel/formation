<?php
namespace Model;

use \Entity\Comment;

class CommentsManagerPDO extends CommentsManager
{
    protected function add(Comment $comment)
    {
        $q = $this->dao->prepare('INSERT INTO comments SET news = :news, auteur = :auteur, contenu = :contenu, date = NOW()');

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
        $this->dao->exec('DELETE FROM comments WHERE id = '.(int) $id);
    }

    public function deleteFromNews($news)
    {
        $this->dao->exec('DELETE FROM comments WHERE news = '.(int) $news);
    }

    public function getListOf($news)
    {
        if (!ctype_digit($news))
        {
            throw new \InvalidArgumentException('L\'identifiant de la news passé doit être un nombre entier valide');
        }

        $q = $this->dao->prepare('SELECT id, news, auteur, contenu, date FROM comments WHERE news = :news');
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
        $q = $this->dao->prepare('UPDATE comments SET auteur = :auteur, contenu = :contenu WHERE id = :id');

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
		
		$sql = 'SELECT id, news, auteur, contenu, date FROM comments WHERE news = :news ORDER BY id DESC ';
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
	
	
}