<?php
namespace FormBuilder;

use \OCFram\FormBuilder;
use \OCFram\StringField;
use \OCFram\TextField;
use \OCFram\MaxLengthValidator;
use \OCFram\NotNullValidator;

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
            ->add(new StringField([
                'label' => 'Password',
                'name' => 'password',
                'type' => 'password',
                'maxLength' => 30,
                'validators' => [
                    new MaxLengthValidator('Le mot de passe spécifié est trop long (30 caractères maximum)', 30),
                    new NotNullValidator('Merci de spécifier le mot de passe'),
                ],
            ]))
            ->add(new StringField([
                'label' => 'Password confirmation',
                'name' => 'passwordConfirmation',
                'type' => 'password',
                'maxLength' => 30,
                'validators' => [
                    new EqualsValidator( 'Mots de passes différents', $this->form->getField( 'password' ) ),
                ],
            ]))
            ->add(new StringField([
                'label' => 'FirstName',
                'name' => 'firsName',
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
            ->add(new StringField([
                'label' => 'Email',
                'name' => 'email',
                'type' => 'email',
                'maxLength' => 50,
                'validators' => [
                    new MaxLengthValidator('Le mail spécifié est trop long (50 caractères maximum)', 50),
                    new NotNullValidator('Merci de spécifier le mail'),
                ],
            ]))
            ->add(new StringField([
                'label' => 'Email confirmation',
                'name' => 'emailConfirmation',
                'type' => 'email',
                'maxLength' => 50,
                'validators' => [
                    new EqualsValidator( 'Mots de passes différents', $this->form->getField( 'email' ) ),
                ],
            ]))
            ->add(new \DateTime([
                'label' => 'birthDate',
                'name' => 'birthDate',
            ]))
    }
}