<?php

namespace NucleusHub\CmsBundle\Security\User;

use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use NucleusHub\CmsBundle\Services\UserService;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WebserviceUserProvider implements UserProviderInterface
{

    /**     
    * @var UserService
    */
    protected $user_service;
    protected $encoder_factory;
    protected $container;

    public function __construct(UserService $userService, $encoderFactory, ContainerInterface $container) {
                
        $this->user_service = $userService;
        $this->encoder_factory = $encoderFactory;
        $this->container = $container;   
    }

    public function loadUserByUsername($username)
    {

        // make a call to your webservice here
        $request = $this->container->get('request');
        $session = $request->getSession();
        $currentUser = $session->get('current_user');

        if( $currentUser ){
            $userData = $currentUser;#$this->user_service->getLoggedUser($currentUser['username']);

        } else {
            $username = $request->request->get('_username');
            $password = $request->request->get('_password');
            $userData = $this->user_service->authenticate($username,$password);
            $session->set('current_user', $userData);
        }

        
        if ($userData) {                        
            $encoder = $this->encoder_factory->getEncoder(new WebserviceUser('','','',array(),'','','','','',''));
            $password = $encoder->encodePassword($userData['user_password'], $userData['user_token']);            
            $salt = $userData['user_token'];
            $token = $userData['user_token'];
            $username = $userData['username'];
            $firstname = $userData['user_firstname'];
            $lastname = $userData['user_lastname'];
            $email = $userData['user_email'];
            $userid = $userData['user_id'];
            $roles = array($userData['user_role']);
            $phone = $userData['user_phone'];
            
            return new WebserviceUser($username, $password, $salt, $roles, $userid, $email, $firstname, $lastname, $token, $phone);
        }

        throw new UsernameNotFoundException(sprintf('Username "%s" does not exist.', $username));
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', get_class($user)));
        }

        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return $class === 'NucleusHub\CmsBundle\Security\User\WebserviceUser';
    }
}