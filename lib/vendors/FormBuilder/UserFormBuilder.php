<?php
namespace FormBuilder;

use OCFram\ExistValidator;
use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\DatetimeField;
use \OCFram\TextField;
use \OCFram\PasswordField;
use \OCFram\MailField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;
use \OCFram\EqualsValidator;

class UserFormBuilder extends FormBuilder
{
    public function build()
    {
        $this->form->add(new StringField([
            'label' => 'Login',
            'name' => 'login',
            'maxLength' => 20,
            'validators' => [
                new MaxLengthValidator('Le login spécifié est trop long (20 caractères maximum)', 20),
                new NotNullValidator('Merci de spécifier login'),
				
            ],
        ]))
            ->add(new PasswordField([
                'label' => 'Password',
                'name' => 'password',
                'maxLength' => 30,
                'validators' => [
                    new MaxLengthValidator('Le mot de passe spécifié est trop long (30 caractères maximum)', 30),
                    new NotNullValidator('Merci de spécifier le mot de passe'),
					new ExistValidator('Login déja utilisé', $this->form->getField( 'login' )),
                ],
            ]))
            ->add(new PasswordField([
                'label' => 'Password confirmation',
                'name' => 'passwordConfirmation',
                'maxLength' => 30,
                'validators' => [
                    new EqualsValidator( 'les mots de passes sont différents', $this->form->getField( 'password' ) ),
                ],
            ]))
            ->add(new StringField([
                'label' => 'FirstName',
                'name' => 'firstName',
                'maxLength' => 20,
                'validators' => [
                    new MaxLengthValidator('Le prénom spécifié est trop long (20 caractères maximum)', 20),
                    new NotNullValidator('Merci de spécifier le prénom'),
                ],
            ]))
            ->add(new StringField([
                'label' => 'LastName',
                'name' => 'lastName',
                'maxLength' => 20,
                'validators' => [
                    new MaxLengthValidator('Le nom spécifié est trop long (20 caractères maximum)', 20),
                    new NotNullValidator('Merci de spécifier le nom'),
                ],
            ]))
            ->add(new MailField([
                'label' => 'Email',
                'name' => 'email',
                'maxLength' => 50,
                'validators' => [
                    new MaxLengthValidator('Le mail spécifié est trop long (50 caractères maximum)', 50),
                    new NotNullValidator('Merci de spécifier le mail'),
                ],
            ]))
            ->add(new MailField([
                'label' => 'Email confirmation',
                'name' => 'emailConfirmation',
                'type' => 'email',
                'maxLength' => 50,
                'validators' => [
                    new EqualsValidator( 'mails différents', $this->form->getField( 'email' ) ),
					new ExistValidator('Le mail déja utilisé', $this->form->getField( 'email' )),
                ],
            ]))

            ->add(new DatetimeField([
                'label' => 'birthDate',
                'name' => 'birthDate',
            ]));
    }
}