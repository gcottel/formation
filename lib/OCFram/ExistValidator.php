<?php
namespace OCFram;

use Model\UserManager;
use \OCFram\Entity;



class ExistValidator extends Validator {
	protected $value;
	
	/**
	 * ExistValidator constructor.
	 *
	 * @param string $errorMessage
	 * @param TextField $field
	 */
	public function __construct( $errorMessage, Entity $user ) {
		parent::__construct( $errorMessage );
		
		$this->value = $user;
	}
	
	public function isValid( $value )
	{
		
		return is_null($value);
	}
}