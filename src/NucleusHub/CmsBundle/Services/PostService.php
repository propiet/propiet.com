<?php

namespace NucleusHub\CmsBundle\Services;

use Lsw\ApiCallerBundle\Caller\LoggingApiCaller;
use Lsw\ApiCallerBundle\Call\HttpPost;

class PostService 
{
    const STATUS_NONE = -1;
    const STATUS_INACTIVE = 0;
    const STATUS_NEW = 1;
    const STATUS_PAUSED = 2;
    const STATUS_PUBLISHED = 3;
    const STATUS_SOLD = 4;

    /**     
    * @var LoggingApiCaller
    */
    protected $api_caller;
    protected $apikey;
    protected $apiUrls;

    public function __construct(LoggingApiCaller $apiCaller,$apikey,$apiUrls) {
                
        $this->api_caller = $apiCaller;
        $this->apikey = $apikey;
        $this->apiUrls = $apiUrls;
    }
    public function addPost($formData, $apiKey){

        $url = 'http://api.propiet.com/v1/post/add';

        $formData = json_encode($formData);
        $parameters = array('request'=>'{"data":'.$formData.'}',
            'k'=>$apiKey);
        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        
        $data = json_decode($output, true);
        
        if($data['response']['success'] == true){
           $response = $data['response'];
        } else {
            $response = $data['response'];
        }      
        return $response;
    }

    public function updatePost($formData, $apiKey){

        $url = 'http://api.propiet.com/v1/post/update';

        $formData = json_encode($formData);
        $parameters = array('request'=>'{"data":'.$formData.'}',
            'k'=>$apiKey);
        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));  
              
        $data = json_decode($output, true);
        
        if($data['response']['success'] == true){
           $response = $data['response']['data'];
        } else {
            $response = $data['response'];
        }      
        return $response;
    }

    public function getPostList($apiKey, $postStatus = self::STATUS_NEW, $userId = 0, $agentId = 0, $page = 1){

        $url = 'http://api.propiet.com/v1/post/list';

        $formData = json_encode($formData);
        $parameters = array('request'=>'{"pagination":{"page":'.$page.'}, "data":{"user":'.$userId.',"status":'.$postStatus.',"agent":'.$agentId.'}}',
            'k'=>$apiKey);
        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        
        $data = json_decode($output, true);
        if($data['response']['success'] == true){
           $response = $data['response'];
        } else {
            $response = $data['response']['error'];
        }      
        return $response;
    }
        
    public function SearchPosts($request,$user){
        
        #$formData = json_encode($formData);
        $request = array('address' => $request->request->get('address'), 
            'page' => $request->request->get('page'),
            'user' => $user);
        $request = json_encode($request);
        $url = $this->apiUrls['base'].$this->apiUrls['post']['search'];
        $parameters = array('request'=>'{"pagination":{"page":1},"data":'.$request.'}','k'=>  $this->apikey);
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        $data = json_decode($output, true);
        if($data['response']['success'] == true){
           $response = $data['response'];
        } else {
            $response = $data['response']['error'];
        }      
        return $response;
    }

    public function assignAgent($formData, $apiKey){

        $url = 'http://api.propiet.com/v1/post/assign';

        $formData = json_encode($formData);
        $parameters = array('request'=>'{"data":'.$formData.'}',
            'k'=>$apiKey);
        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));        
        $data = json_decode($output, true);
        
        if($data['response']['success'] == true){
           $response = $data['response']['data'];
        } else {
            $response = $data['response'];
        }      
        return $response;
    }

    public function unassignAgent($formData, $apiKey){

        $url = 'http://api.propiet.com/v1/post/unassign';

        $formData = json_encode($formData);
        $parameters = array('request'=>'{"data":'.$formData.'}',
            'k'=>$apiKey);
        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));        
        $data = json_decode($output, true);
        
        if($data['response']['success'] == true){
           $response = $data['response']['data'];
        } else {
            $response = $data['response'];
        }      
        return $response;
    }

    public function updateStatus($formData, $apiKey){

        $url = 'http://api.propiet.com/v1/post/status';

        $formData = json_encode($formData);
        $parameters = array('request'=>'{"data":'.$formData.'}',
            'k'=>$apiKey);
        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));        
        $data = json_decode($output, true);
        
        if($data['response']['success'] == true){
           $response = $data['response']['data'];
        } else {
            $response = $data['response'];
        }      
        return $response;
    }
    
    public function getPost($request, $apikey){
        $url = 'http://api.propiet.com/v1/post/get';
        $parameters = array('request'=>$request,'k'=>  $apikey);
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        
        $data = json_decode($output, true);
        if($data['response']['success'] == true){
          $response = $data['response']['data'];
           
        } else {
            $response = array();
        }
        return $response;
    }

    public function getPostForm($request, $apikey){
        $url = 'http://api.propiet.com/v1/post/get_form';
        $parameters = array('request'=>$request,'k'=>  $apikey);
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        
        $data = json_decode($output, true);
        if($data['response']['success'] == true){
            $response = $data['response']['data'];
        } else {
            $response = array();
        }
        return $response;
    }

    public function getPostFormValue($request, $apikey){
        $url = 'http://api.propiet.com/v1/post/get_form_value';
        $parameters = array('request'=>$request,'k'=>  $apikey);
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        
        $data = json_decode($output, true);
        if($data['response']['success'] == true){
            $response = $data['response']['data'];
        } else {
            $response = array();
        }
        return $response;
    }
    
    public function getPublicPost($request){
        $url = $this->apiUrls['base'].$this->apiUrls['post']['get'];
        $parameters = array('request'=>$request,'k'=>  $this->apikey);
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        // echo($output);die;
        $form = $this->getPostFormValue($request, $this->apikey);

        $data = json_decode($output, true);
        if($data['response']['success'] == true){
           $response = [
               "post" => json_decode($data['response']['data']['post'],true),
               "post_data" => $data['response']['data']['post_data'],
               "post_ambiences" => json_decode($data['response']['data']['ambiences'],true),
               "post_features" => json_decode($data['response']['data']['features'],true),
               "post_images" => json_decode($data['response']['data']['images'],true),
               "post_services" => json_decode($data['response']['data']['services'],true),
               "post_property" => json_decode($data['response']['data']['property'],true),
               "agent" => json_decode($data['response']['data']['agent'],true),
               "agent_profile" => json_decode($data['response']['data']['agent_profile'],true),
               "location" => json_decode($data['response']['data']['location'],true),
               "user" => json_decode($data['response']['data']['user'],true),
               "form" => $form,
               ];
         }
        return $response;
    }
    
    public function getAgentData($request){
        $url = $this->apiUrls['base'].$this->apiUrls['post']['agent'];
        $parameters = array('request'=>$request,'k'=>  $this->apikey);
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        $data = json_decode($output, true);       
        if($data['response']['success'] == true){
           $response = [
               "agent" => $data['response']['data'],
               ];
        } else {
            $response = array();
        }
        return $response;
    }

    public function addPostPhoto($formData, $apiKey){

        $url = 'http://api.propiet.com/v1/post/photo/add';

        $formData = json_encode($formData);
        $parameters = array('request'=>'{"data":'.$formData.'}',
            'k'=>$apiKey);
        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        
        $data = json_decode($output, true);
        
        if($data['response']['success'] == true){
           $response = $data['response']['data'];
        } else {
            $response = $data['response'];
        }      
        return $response;
    }

    public function removePostPhoto($formData, $apiKey){

        $url = 'http://api.propiet.com/v1/post/photo/remove';

        $formData = json_encode($formData);
        $parameters = array('request'=>'{"data":'.$formData.'}',
            'k'=>$apiKey);
        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        
        $data = json_decode($output, true);
        
        if($data['response']['success'] == true){
           $response = $data['response']['data'];
        } else {
            $response = $data['response'];
        }      
        return $response;
    }

   public function getAgent($id){
    
        $url = "http://api.propiet.com/v1/agent/".$id;

        $parameters = '';

        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        
        $data = json_decode($output, true);
        
        if($data['response']['success'] == true){

           $response = $data['response']['data'];
        } else {
            $response = $data['response'];
        }      
        return $response;

   }
    
}
