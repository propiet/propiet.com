<?php

namespace NucleusHub\CmsBundle\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;

class WebserviceUser implements UserInterface
{
    private $username;
    private $password;
    private $email;
    private $firstname;
    private $lastname;
    private $phone;
    private $salt;
    private $roles;
    private $token;
    private $id;

    public function __construct($username, $password, $salt, array $roles, $userid, $email, $firstname, $lastname, $token, $phone)
    {        
        $this->username = $username;        
        $this->password = $password;
        $this->salt = $salt;
        $this->roles = $roles;
        $this->email = $email;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->phone = $phone;
        $this->token = $token;
        $this->id = $userid;
    }

    public function getRoles()
    {
        return $this->roles;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getPhone()
    {
        return $this->phone;
    }

    public function getFirstName()
    {
        return $this->firstname;
    }

    public function getLastName()
    {
        return $this->lastname;
    }

    public function getToken()
    {
        return $this->token;
    }

    public function eraseCredentials()
    {
    }

    public function equals(UserInterface $user)
    {        
        if (!$user instanceof WebserviceUser) {
            return false;
        }

        if ($this->password !== $user->getPassword()) {
            return false;
        }

        if ($this->getSalt() !== $user->getSalt()) {
            return false;
        }

        if ($this->username !== $user->getUsername()) {
            return false;
        }

        return true;
    }
}