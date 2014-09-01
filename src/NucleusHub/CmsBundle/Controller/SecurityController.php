<?php

namespace NucleusHub\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\RedirectResponse;

use NucleusHub\CmsBundle\Form\Type\RegistrationType;
use NucleusHub\CmsBundle\Form\Type\ForgotPasswordType;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="user_security_login")
     * @Template()
     */
    public function loginAction()
    {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            // redirect authenticated users to homepage            
            return new RedirectResponse($this->container->get('router')->generate('homepage'));
        }
        $request = $this->container->get('request');
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $session = $request->getSession();

        /* @var $session \Symfony\Component\HttpFoundation\Session\Session */

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {            
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            
            $error = $error->getMessage();
        }
        $confirmation = '';
        $confirmation = $request->query->get('confirmation');
        $success = 0;
        if ($confirmation == 'success') {
            $success = 1;
        }
        // last username entered by the user
        $lastUsername = (null === $session) ? '' : $session->get(SecurityContext::LAST_USERNAME);

        $csrfToken = $this->container->get('form.csrf_provider')->generateCsrfToken('authenticate');
        $routes[] = array('title'=>'Ingresar', 'isActive'=> true);
        return $this->render(
            'NucleusHubCmsBundle:Security:login.html.twig',
            array(
                // last username entered by the user
                'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                'error'         => $error,
                'csrf_token' => $csrfToken,
                'routes' => $routes,
                'success' => $success
        ));
    }

    /**
     * @Route("/registro", name="user_security_registration")
     * @Template()
     */
    public function registrationAction()
    {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            // redirect authenticated users to homepage            
            return new RedirectResponse($this->container->get('router')->generate('homepage'));
        }
        $request = $this->container->get('request');
        $form = $this->createForm(new RegistrationType());
        $result = '';
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $data = $form->getData();
                if(is_null($data['agency_name'])){
                    $data['agency_name'] = $data['first_name'].' '.$data['last_name'];
                } else if (is_null($data['first_name']) && is_null($data['last_name'])) {
                    $agency = explode(' ', $data['agency_name']);
                    if(!$agency[0] == ''){
                        $data['first_name'] = $agency[0];
                    } else {
                        $data['first_name'] = $data['agency_name'];
                    }
                    if(!$agency[1] == ''){
                        $data['last_name'] = $agency[1];
                    } else {
                        $data['last_name'] = $data['agency_name'];
                    }
                } else {
                    //show error
                }
                                
                $user_service = $this->get('user_service');
                $response = $user_service->register($data);
                
                if($response['data']) {
                    switch($response['data']){
                        case 'SCC_CREATED':
                            $result = 1;
                            break;
                        case 'ERR_FORM_INVALID':
                            $result = 2;
                            break;
                        case 'ERR_USER_EXISTS':
                            $result = 3;
                            break;
                        case 'ERR_DATABASE':
                            $result = 2;
                            break;
                        case 'ERR_UNAUTHORIZED':
                            $result = 2;
                            break;
                    }

                }
            }
        }               

        $routes[] = array('title'=>'Registrarme', 'isActive'=> true);
        return $this->render(
            'NucleusHubCmsBundle:Security:registration.html.twig',
            array(       
                'form' => $form->createView(),
                'routes' => $routes,
                'show' => $result
        ));
    }

    /**
     * @Route("/confirmacion/{key}", name="user_security_confirmation")
     * @Template()
     */
    public function confirmationAction($key)
    {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            // redirect authenticated users to homepage            
            return new RedirectResponse($this->container->get('router')->generate('homepage'));
        }
        $request = $this->container->get('request');
        $data = array('activation_key'=> $key);
                
        $user_service = $this->get('user_service');
        $response = $user_service->activate($data);
        
        if($response['data']) {
            switch($response['data']){
                case 'SCC_UPDATED':
                    return new RedirectResponse($this->container->get('router')->generate('user_security_login', array('confirmation' => 'success')));
                    break;
                case 'ERR_FORM_INVALID':
                    return new RedirectResponse($this->container->get('router')->generate('homepage'));
                    break;
                case 'ERR_NOT_FOUND':
                    return new RedirectResponse($this->container->get('router')->generate('homepage'));
                    break;
                case 'ERR_DATABASE':
                    return new RedirectResponse($this->container->get('router')->generate('homepage'));
                    break;
                case 'ERR_UNAUTHORIZED':
                    return new RedirectResponse($this->container->get('router')->generate('homepage'));
                    break;
            }

        }
               

        $routes[] = array('title'=>'Confirmar', 'isActive'=> true);
        return $this->render(
            'NucleusHubCmsBundle:Security:confirmation.html.twig',
            array(       
                'message' => $message,
                'routes' => $routes,
        ));
    }

    /**
     * @Route("/forgot_password", name="user_security_forgot_password")
     * @Template()
     */
    public function forgotPasswordAction()
    {
        if ($this->container->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY'))
        {
            // redirect authenticated users to homepage            
            return new RedirectResponse($this->container->get('router')->generate('homepage'));
        }
        $request = $this->container->get('request');
        $form = $this->createForm(new ForgotPasswordType());
        $result = '';
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $user_service = $this->get('user_service');
                $response = $user_service->forgotPassword($data);
                
                if($response['data']) {
                    switch($response['data']){
                        case 'SCC_UPDATED':
                            $result = 1;
                            break;
                        case 'ERR_FORM_INVALID':
                            $result = 2;
                            break;
                        case 'ERR_NOT_FOUND':
                            $result = 3;
                            break;
                        case 'ERR_DATABASE':
                            $result = 2;
                            break;
                        case 'ERR_UNAUTHORIZED':
                            $result = 2;
                            break;
                    }

                }
            }
        }               

        $routes[] = array('title'=>'Recuperar mi clave', 'isActive'=> true);
        return $this->render(
            'NucleusHubCmsBundle:Security:forgot_password.html.twig',
            array(       
                'form' => $form->createView(),
                'routes' => $routes,
                'show' => $result
        ));
    }

    /**
     * @Route("/login_check", name="user_security_check")
     * @Template()
     */
    public function checkAction()
    {
        throw new \RuntimeException('You must configure the check path to be handled by the firewall using form_login in your security firewall configuration.');
    }

    /**
     * @Route("/logout", name="user_security_logout")
     * @Template()
     */
    public function logoutAction()
    {
        throw new \RuntimeException('You must activate the logout in your security firewall configuration.');
    }
}
