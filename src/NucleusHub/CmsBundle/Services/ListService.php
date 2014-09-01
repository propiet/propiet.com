<?php

namespace NucleusHub\CmsBundle\Services;

use Lsw\ApiCallerBundle\Caller\LoggingApiCaller;
use Lsw\ApiCallerBundle\Call\HttpPost;

class ListService 
{

    /**     
    * @var LoggingApiCaller
    */
    protected $api_caller;

    public function __construct(LoggingApiCaller $apiCaller) {
                
        $this->api_caller = $apiCaller;
    }

    public function getCategories(){

        $url = 'http://api.propiet.com/v1/list/categories';
        $parameters = array('request'=>'{"pagination":{"page":1}}');
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        $data = json_decode($output, true);
        if($data['response']['success'] == true){
           $response = $data['response']['data']['list'];
        } else {
            $response = array();
        }      
        return $response;
    }

    public function getSubcategories($category_id){

        $url = 'http://api.propiet.com/v1/list/subcategories';
        $parameters = array('request'=>'{"pagination":{"page":1},"data":{"category":'.$category_id.'}}');
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        $data = json_decode($output, true);
        if($data['response']['success'] == true){
           $response = $data['response']['data']['list'];
        } else {
            $response = array();
        }      
        return $response;
    }

    public function getFormFields($category, $subcategory, $operation_type){

        $url = 'http://api.propiet.com/v1/list/form/fields';
        $parameters = array('request'=>'{"pagination":{"page":1},"data":{"category":'.$category.',"subcategory":'.$subcategory.',"operation_type":'.$operation_type.'}}');        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        
        $data = json_decode($output, true);
        if($data['response']['success'] == true){
           $response = $data['response']['data']['form'];
        } else {
            $response = array();
        }      
        return $response;
    }

    public function getCities($region){

        $url = 'http://api.propiet.com/v1/list/cities';
        $parameters = array('request'=>'{"pagination":{"page":1},"data":{"region":'.$region.'}}');        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        $data = json_decode($output, true);
        if($data['response']['success'] == true){
           $response = $data['response']['data']['list'];
        } else {
            $response = array();
        }      
        return $response;
    }

    public function getServices(){

        $url = 'http://api.propiet.com/v1/list/services';
        $parameters = array('request'=>'{"pagination":{"page":1}}');        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        $data = json_decode($output, true);
        if($data['response']['success'] == true){
           $response = $data['response']['data']['list'];
        } else {
            $response = array();
        }      
        return $response;
    }

    public function getAmbiences(){

        $url = 'http://api.propiet.com/v1/list/ambiences';
        $parameters = array('request'=>'{"pagination":{"page":1}}');        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        $data = json_decode($output, true);
        if($data['response']['success'] == true){
           $response = $data['response']['data']['list'];
        } else {
            $response = array();
        }      
        return $response;
    }

    public function getFeatures(){

        $url = 'http://api.propiet.com/v1/list/features';
        $parameters = array('request'=>'{"pagination":{"page":1}}');        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        $data = json_decode($output, true);
        if($data['response']['success'] == true){
           $response = $data['response']['data']['list'];
        } else {
            $response = array();
        }      
        return $response;
    }
}