<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;

class CommentFormUserBuilder extends FormBuilder
{
	public function build()
	{
		
		$this->form->add(new TextField([
					   'label' => 'Contenu',
					   'name' => 'contenu',
					   'rows' => 7,
					   'cols' => 50,
					   'validators' => [
						   new NotNullValidator('Merci de spécifier votre commentaire'),
					   ],
				   ]));
	}
}