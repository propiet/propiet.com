<?php

namespace NucleusHub\CmsBundle\Services;

use Lsw\ApiCallerBundle\Caller\LoggingApiCaller;
use Lsw\ApiCallerBundle\Call\HttpPost;

class SavedQueryService 
{

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
    public function addSavedQueryQuery($formData, $apiKey){
        
        $url = 'http://api.propiet.com/v1/saved_query/add';

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
    
     public function addSavedQueryAlert($formData,$type=null){
        if($type){
            $url = $this->apiUrls['base'].$this->apiUrls['query']['add_logged_out'];
        }else{
            $url = $this->apiUrls['base'].$this->apiUrls['query']['add_logged_in'];
        }
        

        $formData = json_encode($formData);
        $parameters = array('request'=>'{"data":'.$formData.'}',
            'k'=>$this->apikey);
        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));        
        $data = json_decode($output, true);
        var_dump($data);die;
        if($data['response']['success'] == true){
           $response = $data['response']['data'];
        } else {
            $response = $data['response'];
        }      
        return $response;
    }
    

    public function updateSavedQuery($formData, $apiKey){

        $url = 'http://api.propiet.com/v1/saved_query/update';

        $formData = json_encode($formData);

        $parameters = array('request'=>'{"data":'.$formData.'}',
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

    public function deleteSavedQuery($apiKey, $userId = 0, $savedQueryId = 0){

        $url = 'http://api.propiet.com/v1/saved_query/delete';

        $formData = json_encode($formData);
        $parameters = array('request'=>'{"data":{"user":'.$userId.',"id":'.$savedQueryId.'}}',
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

    public function getSavedQuery($apiKey, $userId = 0, $savedQueryId = 0){

        $url = 'http://api.propiet.com/v1/saved_query/get';

        $formData = json_encode($formData);
        $parameters = array('request'=>'{"data":{"user":'.$userId.',"id":'.$savedQueryId.'}}',
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

    public function getSavedQueryList($apiKey, $userId = 0){

        $url = 'http://api.propiet.com/v1/saved_query/list';

        $formData = json_encode($formData);
        $parameters = array('request'=>'{"pagination":{"page":1}, "data":{"user":'.$userId.'}}',
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
}
