<?php
namespace OCFram;

use Model\UserManager;
use \OCFram\Managers;



class ExistValidator extends Validator {
	protected $value;
	protected $managers = null;
	
	/**
	 * ExistValidator constructor.
	 *
	 * @param string $errorMessage
	 * @param TextField $field
	 */
	public function __construct( $errorMessage, Field $field ) {
		parent::__construct( $errorMessage );
		
		$this->value = $field->value();
		$this->managers = new Managers('PDO', PDOFactory::getMysqlConnexion());
	}
	
	public function isValid( $value ) {
		
		$manager = $this->managers->getManagerOf( 'User' );
		
		
		return is_null($User = $manager->getUserByLoginOrEmail( $value ));
	}
}