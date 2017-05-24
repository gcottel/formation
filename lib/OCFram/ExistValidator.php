<?php
namespace OCFram;

use Model\UserManager;



class ExistValidator extends Validator {
	protected $value;
	
	/**
	 * ExistValidator constructor.
	 *
	 * @param string $errorMessage
	 * @param TextField $field
	 */
	public function __construct( $errorMessage, $field ) {
		parent::__construct( $errorMessage );
		
		$this->value = $field->value();
	}
	
	public function isValid( $value ) {
		
		$manager = $this->managers->getManagerOf( 'User' );
		
		
		return is_null($User = $manager->getUserByLoginOrEmail( $value ));
	}
}