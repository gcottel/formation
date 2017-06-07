<?php
namespace Model;

use \OCFram\Manager;
use \Entity\Comment;

abstract class CommentsManager extends Manager
{
    /**
     * Méthode permettant d'ajouter un commentaire.
     * @param $comment Le commentaire à ajouter
     * @return void
     */
    abstract protected function add(Comment $comment);

    /**
     * Méthode permettant de supprimer un commentaire.
     * @param $id L'identifiant du commentaire à supprimer
     * @return void
     */
    abstract public function delete($id);

    /**
     * Méthode permettant de supprimer tous les commentaires liés à une news
     * @param $news L'identifiant de la news dont les commentaires doivent être supprimés
     * @return void
     */
    abstract public function deleteFromNews($news);

    /**
     * Méthode permettant d'enregistrer un commentaire.
     * @param $comment Le commentaire à enregistrer
     * @return void
     */
    public function save(Comment $comment)
    {
        if ($comment->isValid())
        {
            $comment->isNew() ? $this->add($comment) : $this->modify($comment);
        }
        else
        {
            throw new \RuntimeException('Le commentaire doit être validé pour être enregistré');
        }
    }

    /**
     * Méthode permettant de récupérer une liste de commentaires.
     * @param $news La news sur laquelle on veut récupérer les commentaires
     * @return array
     */
    abstract public function getListOf($news);

    /**
     * Méthode permettant de modifier un commentaire.
     * @param $comment Le commentaire à modifier
     * @return void
     */
    abstract protected function modify(Comment $comment);

    /**
     * Méthode permettant d'obtenir un commentaire spécifique.
     * @param $id L'identifiant du commentaire
     * @return Comment
     */
    abstract public function get($id);
	
	/**
	 * Méthode permettant d'obtenir l\'identifiant de la news d\'un commentaire spécifique.
	 *
	 * @param int $id L'identifiant du commentaire
	 *
	 * @return int
	 */
	abstract public function getNewsId( $id );
	
	/**
	 * méthode renvoyant la date du commentaire grace à son id
	 *
	 * @param $id
	 *
	 * @return \DateTime
	 */
	 abstract public function getLastDateAuthor( $author );
	
	
	/**
	 * Méthode retournant une liste de commentaires demandée.
	 * @param $debut int La première commentaires à sélectionner
	 * @param $limite int Le nombre de commentaires à sélectionner
	 * @param $news int id de la news actuelle
	 * @return array La liste des commentaires. Chaque entrée est une instance de Comment.
	 */
	abstract public function getList($Lastid, $limite = -1, $news);
	
	/**
	 * Méthode retournant une liste de commentaires demandée (ceux qui ont été supprimés depuis le dernier update).
	 * @param $debut int La première commentairesà sélectionner
	 * @param $limite int Le nombre decommentaires à sélectionner
	 * @param $news int id de la news actuelle
	 * @return array La liste des commentaires. Chaque entrée est une instance de Comment.
	 */
	abstract public function getListDelete($Lastid, $news);
	
	/**
	 * Méthode retournant une liste de commentaires demandée(ceux qui on été modifiés depuis le dernier update).
	 * @param $debut int La première commentaires à sélectionner
	 * @param $limite int Le nombre de commentaires à sélectionner
	 * @param $news int id de la news actuelle
	 * @return array La liste des commentaires. Chaque entrée est une instance de Comment.
	 */
	abstract public function getListUpdate($Lastid, $news);
	
	/**
	 * Méthode retournant une liste de commentaires demandée(ceux qui on été modifiés depuis le dernier update).
	 * @param $debut int La première commentaires à sélectionner
	 * @param $limite int Le nombre de commentaires à sélectionner
	 * @param $news int id de la news actuelle
	 * @return array La liste des commentaires. Chaque entrée est une instance de Comment.
	 */
	abstract public function getListAdd($Lastid, $news);
}



