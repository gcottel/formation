<?php
namespace Model;

use \OCFram\Manager;
use \Entity\User;

abstract class UserManager extends Manager
{
    /**
     * Méthode permettant d'ajouter une user.
     * @param $user user La user à ajouter
     * @return void
     */
    abstract protected function add(User $user);

    /**
     * Méthode permettant d'enregistrer une user.
     * @param $user user la user à enregistrer
     * @see self::add()
     * @see self::modify()
     * @return void
     */
    public function save(User $user)
    {
        if ($user->isValid())
        {
            $user->isNew() ? $this->add($user) : $this->modify($user);
        }
        else
        {
            throw new \RuntimeException('Le user doit être validé pour être enregistré');
        }
    }

    /**
     * Méthode renvoyant le nombre de user total.
     * @return int
     */
    abstract public function count();

    /**
     * Méthode permettant de supprimer une user.
     * @param $id int L'identifiant de la user à supprimer
     * @return void
     */
    abstract public function delete($id);

    /**
     * Méthode retournant une liste de news demandée.
     * @param $debut int La première news à sélectionner
     * @param $limite int Le nombre de news à sélectionner
     * @return array La liste des news. Chaque entrée est une instance de News.
     */
    abstract public function getList($debut = -1, $limite = -1);

    /**
     * Méthode retournant une news précise.
     * @param $id int L'identifiant de la news à récupérer
     * @return News La news demandée
     */
    abstract public function getUnique($id);

    /**
     * Méthode permettant de modifier une news.
     * @param $news news la news à modifier
     * @return void
     */
    abstract protected function modify(User $user);
}