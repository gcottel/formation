<?php
namespace Entity;

use \OCFram\Entity;

class User extends Entity
{
    protected $login,
        $password,
        $firstName,
        $lastName,
        $email,
        $birthDate,
        $dateAjout,
        $dateModif,
        $MMC_fk_MMY;

    const LOGIN_INVALIDE = 1;
    const PASSWORD_INVALIDE = 2;
    const FIRSTNAME_INVALIDE = 3;
    const LASTNAME_INVALIDE = 4;
    const EMAIL_INVALIDE = 5;
    const BIRTHDATE_INVALIDE = 6;
    const DATEAJOUT_INVALIDE = 7;
    const DATEMODIF_INVALIDE = 8;
    const MMC_fk_MMY_INVALIDE = 9;


    public function isValid()
    {
        return !(empty($this->login) || empty($this->password));
    }


    // SETTERS //

    public function setLogin($login)
    {
        if (!is_string($login) || empty($login))
        {
            $this->erreurs[] = self::LOGIN_INVALIDE;
        }

        $this->login = $login;
    }

    public function setMMC_fk_MMY($MMC_fk_MMY)
    {
        if (!is_string($MMC_fk_MMY) || empty($MMC_fk_MMY))
        {
            $this->erreurs[] = self::MMC_fk_MMY_INVALIDE;
        }

        $this->MMC_fk_MMY = $MMC_fk_MMY;
    }

    public function setPassword($password)
    {
        if (!is_string($password) || empty($password))
        {
            $this->erreurs[] = self::PASSWORD_INVALIDE;
        }

        $this->password = $password;
    }

    public function setFirstName($firstName)
    {
        if (!is_string($firstName) || empty($firstName))
        {
            $this->erreurs[] = self::FIRSTNAME_INVALIDE;
        }

        $this->firstName = $firstName;
    }

    public function setLastname($lastName)
    {
        if (!is_string($lastName) || empty($lastName))
        {
            $this->erreurs[] = self::LASTNAME_INVALIDE;
        }

        $this->lastName = $lastName;
    }

    public function setEmail($email)
    {
        if (!is_string($email) || empty($email))
        {
            $this->erreurs[] = self::EMAIL_INVALIDE;
        }

        $this->email = $email;
    }

    public function setbirthDate($birthDate)
    {
        $this->birthDate = $birthDate;
    }

    public function setDateAjout(\DateTime $dateAjout)
    {

        $this->dateAjout = $dateAjout;
    }

    public function setDateModif(\DateTime $dateModif)
    {
        $this->dateModif = $dateModif;
    }

    // GETTERS //

    public function login()
    {
        return $this->login;
    }

    public function MMC_fk_MMY()
    {
        return $this->MMC_fk_MMY;
    }

    public function firstName()
    {
        return $this->firstName;
    }

    public function lastName()
    {
        return $this->lastName;
    }

    public function email()
    {
        return $this->email;
    }

    public function birthDate()
    {
        return $this->birthDate;
    }

    public function dateAjout()
    {
        return $this->dateAjout;
    }

    public function dateModif()
    {
        return $this->dateModif;
    }

    public function Password()
    {
        return $this->password;
    }
}