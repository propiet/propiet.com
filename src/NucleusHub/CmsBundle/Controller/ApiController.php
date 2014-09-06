<?php

namespace NucleusHub\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use JMS\SecurityExtraBundle\Annotation\Secure;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class ApiController extends Controller
{
    /**
     * @Route("/api/v1/categories/list.json", name="api_get_categories")
     * @Template()
     * 
     */
    public function getCategoriesAction(Request $request)
    {        
        $list_service = $this->get('list_service');
        $categories = $list_service->getCategories();                
        return new JsonResponse($categories[0]);    
    }

    /**
     * @Route("/api/v1/subcategories/list.json", name="api_get_subcategories")
     * @Template()
     * 
     */
    public function getSubCategoriesAction(Request $request)
    {        
        $category = $request->get('category');                    
        $list_service = $this->get('list_service');
        $subcategories = $list_service->getSubcategories($category);        
        return new JsonResponse($subcategories[0]);    
    }

    /**
     * @Route("/api/v1/wizard/step/1.json", name="api_get_wizard_step_1")
     * @Template()
     * 
     */
    public function getWizardStepOneAction(Request $request)
    {        
    	$category = $request->get('category');
    	$subcategory = $request->get('subcategory');
    	$operation_type = $request->get('operation_type');
        $list_service = $this->get('list_service');
        $form = $list_service->getFormFields($category, $subcategory, $operation_type);     
        return new JsonResponse($form[0]);    
    }

    /**
     * @Route("/api/v1/wizard/step/2.json", name="api_get_wizard_step_2")
     * @Template()
     * 
     */
    public function getWizardStepTwoAction(Request $request)
    {        
    	$list_service = $this->get('list_service');
        $data['services'] = $list_service->getServices();        
        $data['ambiences'] = $list_service->getAmbiences();        
        $data['features'] = $list_service->getFeatures();

        return new JsonResponse($data);    
    }

    /**
     * @Route("/api/v1/cities/list.json", name="api_get_cities")
     * @Template()
     * 
     */
    public function getCitiesAction(Request $request)
    {   
    	$region = $request->get('region');
        $list_service = $this->get('list_service');
        $cities = $list_service->getCities($region);     
        return new JsonResponse($cities);    
    }

    /**
     * @Route("/api/v1/services/list.json", name="api_get_services")
     * @Template()
     * 
     */
    public function getServicesAction(Request $request)
    {            	
        $list_service = $this->get('list_service');
        $services = $list_service->getServices();     
        return new JsonResponse($services);    
    }

    /**
     * @Route("/api/v1/ambiences/list.json", name="api_get_ambiences")
     * @Template()
     * 
     */
    public function getAmbiencesAction(Request $request)
    {            	
        $list_service = $this->get('list_service');
        $ambiences = $list_service->getAmbiences();     
        return new JsonResponse($ambiences);    
    }

    /**
     * @Route("/api/v1/features/list.json", name="api_get_features")
     * @Template()
     * 
     */
    public function getFeaturesAction(Request $request)
    {            	
        $list_service = $this->get('list_service');
        $features = $list_service->getFeatures();     
        return new JsonResponse($features);    
    }

    /**
     * @Route("/api/v1/submit/wizard.json", name="api_post_wizard")
     * @Template()
     * 
     */
    public function submitWizardAction(Request $request)
    {             	
        $response = '{}';   	
    	if ($request->getMethod() == 'POST') {    		

    		$securityContext = $this->get('security.context');
	        $token = $securityContext->getToken();
	        $user = $token->getUser();
	        $apiKey = $user->getToken();

    		$data = $request->request->get('form');

    		$property = array();
	        foreach ($data['property'] as $element) {
	        	$name = $element['name'];
	        	$value = $element['value'];
	        	$property[$name] = $value;
	        }

	        $services = array();
	        $ambiences = array();
	        $features = array();
	        foreach ($data['others'] as $element) {
	        	if($element['name'] == 'services[]') {
	        		$services[] = $element['value'];
	        	} else if($element['name'] == 'ambiences[]'){
	        		$ambiences[] = $element['value'];
	        	} else {
	        		$features[] = $element['value'];
	        	}
	        }
	        $property['services'] = $services;
	        $property['ambiences'] = $ambiences;
	        $property['features'] = $features;
	        $property['user'] = $user->getId();

            if(!isset($property['suitableProfessional'])){
                $property['suitableProfessional'] = 0;
            }


	        $location = array();
            $post = array();
	        foreach ($data['location-post'] as $element) {
	        	$name = $element['name'];
	        	$value = $element['value'];
                if($name == 'title') {
                    $post[$name] = $value;
                } else if($name == 'description'){
                    $post[$name] = $value;
                } else if($name == 'hidden_note'){
                    $post[$name] = $value;
                } else {
                    $location[$name] = $value;
                }	        	
	        }
	        // TO-DO: Remove hardcoded country and region
	        $location['country'] = 1;
	        $location['region'] = 1;	        
	        
	        $post['user'] = $property['user'];
	        $post['category'] = $property['category'];
	        $post['operation'] = $property['operation'];
            $post['currency'] = $property['currency'];
            $post['price'] = $property['price'];
	        $post['region'] = $location['region'];
	        $post['city'] = $location['city'];

	        $userRoles = $user->getRoles();
	        if($userRoles[0] == 'ROLE_USER' || $userRoles[0] == 'ROLE_COMPANY'){
				$post['status'] = 1; //NEW
	        }
	        if($userRoles[0] == 'ROLE_AGENT'){
	        	$post['status'] = 3; //PUBLISHED
                $post['agent'] = $property['user']; //PUBLISHED
	        }

    		$form_data = array('property' => $property,
    				 'location' => $location,
    				 'post' => $post);
    		
    		$post_service = $this->get('post_service');
        	$response = $post_service->addPost($form_data, $apiKey);

            if ($response != 'ERR_EMPTY_LIST') {
                $integration_service = $this->get('integration_service');
                $responseInteg = $integration_service->addPostProperati($response['id'], $apiKey);
            }
    	}
            
        return new JsonResponse($response);    
    }

        /**
     * @Route("/api/v1/edit/wizard.json", name="api_post_edit_wizard")
     * @Template()
     * 
     */
    public function updateWizardAction(Request $request)
    {               
        $response = '{}';       
        if ($request->getMethod() == 'POST') {          

            $securityContext = $this->get('security.context');
            $token = $securityContext->getToken();
            $user = $token->getUser();
            $apiKey = $user->getToken();

            $data = $request->request->get('form');
            $postId = $request->request->get('postId');
            $locationId = $request->request->get('locationId');
            $propertyId = $request->request->get('propertyId');
            $category = $request->request->get('category');
            $subcategory = $request->request->get('subcategory');
            $operation = $request->request->get('operation');

            $property = array();
            foreach ($data['property'] as $element) {
                $name = $element['name'];
                $value = $element['value'];
                $property[$name] = $value;
            }
            $property['category'] = $category;
            $property['subcategory'] = $subcategory;
            $property['operation'] = $operation;

            $services = array();
            $ambiences = array();
            $features = array();
            foreach ($data['others'] as $element) {
                if($element['name'] == 'services[]') {
                    $services[] = $element['value'];
                } else if($element['name'] == 'ambiences[]'){
                    $ambiences[] = $element['value'];
                } else {
                    $features[] = $element['value'];
                }
            }
            $property['services'] = $services;
            $property['ambiences'] = $ambiences;
            $property['features'] = $features;            
            $property['user'] = $user->getId();
            $property['id'] = $propertyId;


            $location = array();
            $post = array();
            foreach ($data['location-post'] as $element) {
                $name = $element['name'];
                $value = $element['value'];
                if($name == 'title') {
                    $post[$name] = $value;
                } else if($name == 'description'){
                    $post[$name] = $value;
                } else if($name == 'hidden_note'){
                    $post[$name] = $value;
                } else {
                    $location[$name] = $value;
                }               
            }
            // TO-DO: Remove hardcoded country and region
            $location['country'] = 1;
            $location['region'] = 1;
            $location['id'] = $locationId; //PUBLISHED            
            
            $post['user'] = $property['user'];
            $post['category'] = $property['category'];
            $post['operation'] = $property['operation'];
            $post['currency'] = $property['currency'];
            $post['price'] = $property['price'];
            $post['region'] = $location['region'];
            $post['city'] = $location['city'];
            $post['id'] = $postId; //PUBLISHED
            

            $userRoles = $user->getRoles();
            if($userRoles[0] == 'ROLE_USER' || $userRoles[0] == 'ROLE_COMPANY'){
                
            }
            if($userRoles[0] == 'ROLE_AGENT'){
                
                $post['agent'] = $property['user']; //PUBLISHED
            }

            $form_data = array('property' => $property,
                     'location' => $location,
                     'post' => $post);
            
            $post_service = $this->get('post_service');
            $response = $post_service->updatePost($form_data, $apiKey);

            if ($response != 'ERR_EMPTY_LIST') {
                $integration_service = $this->get('integration_service');
                $responseIntegEl = $integration_service->deletePostProperati($postId, $apiKey);
                $responseInteg = $integration_service->addPostProperati($postId, $apiKey);
                
            } 
        }
            
        return new JsonResponse($response);    
    }

    /**
     * @Route("/api/v1/post/agent/assign.json", name="api_post_assign")
     * @Template()
     * 
     */
    public function agentAssignAction(Request $request)
    {               
        $response = '{}';       
        if ($request->getMethod() == 'POST') {          

            $securityContext = $this->get('security.context');
            $token = $securityContext->getToken();
            $user = $token->getUser();
            $apiKey = $user->getToken();

            $data = $request->request->get('form');

            $posts = array();
            foreach ($data['posts'] as $element) {               
                $value = $element['value'];
                $posts[] = $value;              
            }
            
            $form_data = array('posts' => $posts,
                     'agent' => $user->getId());
                        
            $post_service = $this->get('post_service');
            $response = $post_service->assignAgent($form_data, $apiKey); 
        }
            
        return new JsonResponse($response);    
    }

    /**
     * @Route("/api/v1/post/agent/unassign.json", name="api_post_unassign")
     * @Template()
     * 
     */
    public function agentUnassignAction(Request $request)
    {               
        $response = '{}';       
        if ($request->getMethod() == 'POST') {          

            $securityContext = $this->get('security.context');
            $token = $securityContext->getToken();
            $user = $token->getUser();
            $apiKey = $user->getToken();

            $data = $request->request->get('form');

            $posts = array();
            foreach ($data['posts'] as $element) {               
                $value = $element['value'];
                $posts[] = $value;              
            }
            
            $form_data = array('posts' => $posts,
                     'agent' => $user->getId());
                        
            $post_service = $this->get('post_service');
            $response = $post_service->unassignAgent($form_data, $apiKey); 
        }
            
        return new JsonResponse($response);    
    }

    /**
     * @Route("/api/v1/post/status/update.json", name="api_post_update_status")
     * @Template()
     * 
     */
    public function postUpdateStatusAction(Request $request)
    {               
        $response = '{}';       
        if ($request->getMethod() == 'POST') {          

            $securityContext = $this->get('security.context');
            $token = $securityContext->getToken();
            $user = $token->getUser();
            $apiKey = $user->getToken();

            $data = $request->request->get('form');            
            
            $form_data = array('post' => $data['post'],
                     'status' => $data['status']);
                        
            $post_service = $this->get('post_service');
            $response = $post_service->updateStatus($form_data, $apiKey); 
        }
            
        return new JsonResponse($response);    
    }

    /**
     * @Route("/api/v1/saved_query/update.json", name="api_saved_query_update")
     * @Template()
     * 
     */
    public function savedQueryUpdateAction(Request $request)
    {               
        $response = '{}';         
        if ($request->getMethod() == 'POST') {          

            $securityContext = $this->get('security.context');
            $token = $securityContext->getToken();
            $user = $token->getUser();
            $apiKey = $user->getToken();

            $form = $request->request->get('form');
            foreach ($form as $element) {
                $form_data[$element['name']] = $element['value'];
            }
              
            $saved_query_service = $this->get('saved_query_service');        
            $result = $saved_query_service->updateSavedQuery($form_data, $apiKey);
            $response = $result['data'];
        }
            
        return new JsonResponse($response);    
    }

    /**
     * @Route("/api/v1/saved_query/delete.json", name="api_saved_query_delete")
     * @Template()
     * 
     */
    public function savedQueryDeleteAction(Request $request)
    {               
        $response = '{}';       
        if ($request->getMethod() == 'POST') {          

            $securityContext = $this->get('security.context');
            $token = $securityContext->getToken();
            $user = $token->getUser();
            $apiKey = $user->getToken();

            $form_data = $request->request->get('form');
            $userId = $form_data['user'];
            $savedQueryId = $form_data['id'];
            $saved_query_service = $this->get('saved_query_service');        
            $response = $saved_query_service->deleteSavedQuery($apiKey, $userId, $savedQueryId);            
        }
            
        return new JsonResponse($response);    
    }

     /**
     * @Route("/api/v1/saved_query/add.json", name="api_saved_query_add")
     * @Template()
     * 
     */
    public function savedQueryAddAction(Request $request)
    {               
        $response = '{}';       
        if (true) {          

            $securityContext = $this->get('security.context');
            $token = $securityContext->getToken();
            $user = $token->getUser();
            $apiKey = $user->getToken();

            $form_data = $request->request->get('form');
            $userId = $form_data['user'];
            $savedQueryId = $form_data['id'];
            $form_data['name'] = 'test';
            $form_data['email'] = 'test@test.com';
            $form_data['query'] = 'alsdalsjdlakjsdlkjaslkdjalsj';
            $saved_query_service = $this->get('saved_query_service');        
            $response = $saved_query_service->addSavedQueryAlert($form_data,null);            
        }
            
        return new JsonResponse($response);    
    }
    
    
    /**
     * @Route("/api/v1/user/update.json", name="api_user_update")
     * @Template()
     * 
     */
    public function userUpdateAction(Request $request)
    {               
        $response = '{}';         
        if ($request->getMethod() == 'POST') {          

            $securityContext = $this->get('security.context');
            $token = $securityContext->getToken();
            $user = $token->getUser();
            $apiKey = $user->getToken();

            $form = $request->request->get('form');
            $form_data['id'] = $user->getId();
            foreach ($form as $element) {
                $form_data[$element['name']] = $element['value'];
            }
              
            $user_service = $this->get('user_service');        
            $result = $user_service->updateProfile($form_data, $apiKey);
            $response = $result['data'];
        }
            
        return new JsonResponse($response);    
    }

    /**
     * @Route("/api/v1/user/password/update.json", name="api_user_password_update")
     * @Template()
     * 
     */
    public function userPasswordUpdateAction(Request $request)
    {               
        $response = '{}';         
        if ($request->getMethod() == 'POST') {          

            $securityContext = $this->get('security.context');
            $token = $securityContext->getToken();
            $user = $token->getUser();
            $apiKey = $user->getToken();

            $form = $request->request->get('form');
            $form_data['id'] = $user->getId();
            foreach ($form as $element) {
                $form_data[$element['name']] = $element['value'];
            }
              
            $user_service = $this->get('user_service');        
            $result = $user_service->updatePassword($form_data, $apiKey);
            $response = $result['data'];
        }
            
        return new JsonResponse($response);    
    }

    /**
     * @Route("/api/v1/send/message/agent", name="api_send_agent_message")
     * @Template()
     * 
     */
    public function sendAgentMessageAction(Request $request)
    {   
        $response = '{}';         
        if ($request->getMethod() == 'POST') {
            $user_service = $this->get('user_service');  
            $form = $request->request->get('form');
            foreach ($form as $element) {
                $form_data[$element['name']] = $element['value'];
            }
            $response = $user_service->contactAgent($form_data);
        }
                       
        return new JsonResponse($response);    
    }

    /**
     * @Route("/api/v1/file/upload.json", name="api_file_upload")
     * @Template()
     * 
     */
    public function uploadFileAction(Request $request)
    { 
        $response = '0';
        if ($request->getMethod() == 'POST') {

            $fileTypes = array('jpg','jpeg','png');
            $post = $request->request->get('post');

            foreach($request->files as $uploadedFile) {
                $type = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_EXTENSION);
                // var_dump($uploadedFile->getClientOriginalExtension());die;
                //if (in_array($uploadedFile->getExtension(),$fileTypes)) {
                $imagedata = file_get_contents($uploadedFile->getPathName());
                $base64 = base64_encode($imagedata);                              
                $form_data = array('post' => $post, 'file'=>$base64);
                
                $post_service = $this->get('post_service');
                $response = $post_service->addPostPhoto($form_data, $apiKey);
                //} else {
                //    die(0);
               //}
            }
            
            //Add photos to properati files
            $integration_service = $this->get('integration_service');
            $responseIntegEl = $integration_service->deletePostProperati($post, $apiKey);            
            $responseInteg = $integration_service->addPostProperati($post, $apiKey);

        }
        return new JsonResponse($response);
    }

    /**
     * @Route("/api/v1/file/delete.json", name="api_file_delete")
     * @Template()
     * 
     */
    public function fileDeleteAction(Request $request)
    {               
        $response = '{}';       
        if ($request->getMethod() == 'POST') {          

            $securityContext = $this->get('security.context');
            $token = $securityContext->getToken();
            $user = $token->getUser();
            $apiKey = $user->getToken();

            $imgId = $request->request->get('imgId');            
            $form_data['id'] = $imgId;
            $post_service = $this->get('post_service');        
            $response = $post_service->removePostPhoto($form_data, $apiKey);            
        }
            
        return new JsonResponse($response);    
    }
}