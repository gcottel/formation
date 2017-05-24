<?php
namespace Model;

use \Entity\User;

class UserManagerPDO extends UserManager {
	protected function add( User $user ) {
		$requete = $this->dao->prepare( 'INSERT INTO user SET login = :login, password = :password, firstName = :firstName, lastName = :lastName, email = :email, birthDate = :birthDate, dateAjout = NOW(), dateModif = NOW()' );
		
		$requete->bindValue( ':login', $user->login() );
		$requete->bindValue( ':password', $user->password() );
		$requete->bindValue( ':firstName', $user->firstName() );
		$requete->bindValue( ':lastName', $user->lastName() );
		$requete->bindValue( ':email', $user->email() );
		$requete->bindValue( ':birthDate', $user->birthDate() );
		
		$requete->execute();
	}
	
	public function count() {
		return $this->dao->query( 'SELECT COUNT(*) FROM user' )->fetchColumn();
	}
	
	public function delete( $id ) {
		$this->dao->exec( 'DELETE FROM user WHERE id = ' . (int)$id );
	}
	
	public function getList( $debut = -1, $limite = -1 ) {
		$sql = 'SELECT id, firstName, lastName, birthDate, dateAjout, dateModif FROM user ORDER BY id DESC';
		date_default_timezone_set( 'Europe/Paris' );
		
		if ( $debut != -1 || $limite != -1 ) {
			$sql .= ' LIMIT ' . (int)$limite . ' OFFSET ' . (int)$debut;
		}
		
		$requete = $this->dao->query( $sql );
		$requete->setFetchMode( \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\User' );
		
		$listeUser = $requete->fetchAll();
		
		foreach ( $listeUser as $user ) {
			$user->setbirthDate( new \DateTime( $user->birthDate() ) );
			$user->setDateAjout( new \DateTime( $user->dateAjout() ) );
			$user->setDateModif( new \DateTime( $user->dateModif() ) );
		}
		
		$requete->closeCursor();
		
		return $listeUser;
	}
	
	public function getUnique( $id ) {
		$requete = $this->dao->prepare( 'SELECT id, login, password, firstName, lastName, birthDate, dateAjout, dateModif FROM user WHERE id = :id' );
		$requete->bindValue( ':id', (int)$id, \PDO::PARAM_INT );
		$requete->execute();
		
		$requete->setFetchMode( \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\User' );
		
		if ( $user = $requete->fetch() ) {
			return $user;
		}
		
		return null;
	}
	
	public function getIdByLoginOrEmail( $login )
	{
		$requete = $this->dao->prepare( 'SELECT id FROM user WHERE login = :login OR email = :email' );
		$requete->bindValue( ':login', $login );
		$requete->bindValue( ':email', $login );
		$requete->execute();
		
		return $requete->fetch();
	}
	
	public function getRoleByLoginOrEmail( $login )
	{
		$requete = $this->dao->prepare( 'SELECT MMC_fk_MMY FROM user WHERE login = :login OR email = :email' );
		$requete->bindValue( ':login', $login );
		$requete->bindValue( ':email', $login );
		$requete->execute();
		
		return $requete->fetch();
	}
	
	public function getUserByLoginOrEmail( $login )
	{
		$sql = 'SELECT * 
				FROM user 
				WHERE login = :login 
				OR email = :email';
		
		$requete = $this->dao->prepare( $sql );
		$requete->bindValue( ':login', $login );
		$requete->bindValue( ':email', $login );
		$requete->execute();
		
		$requete->setFetchMode( \PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, '\Entity\User' );
		
		if ( $user = $requete->fetch() ) {
			return $user;
		}
		
		return null;
	}
	
	protected function modify( User $user ) {
		$requete = $this->dao->prepare( 'UPDATE user SET login = :login, password = :password, firstName = :firstName, lastName = :lastName, email = :email, birthDate = :birthDate, dateAjout = NOW(), dateModif = NOW() WHERE id = :id' );
		
		$requete->bindValue( ':login', $user->login() );
		$requete->bindValue( ':password', $user->password() );
		$requete->bindValue( ':firstName', $user->firstName() );
		$requete->bindValue( ':lastName', $user->lastName() );
		$requete->bindValue( ':email', $user->email() );
		$requete->bindValue( ':birthDate', $user->birthDate() );
		
		$requete->execute();
	}
}