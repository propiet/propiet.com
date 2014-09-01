<?php

namespace NucleusHub\CmsBundle\Services;

use Lsw\ApiCallerBundle\Caller\LoggingApiCaller;
use Lsw\ApiCallerBundle\Call\HttpPost;

class UserService 
{

    /**     
    * @var LoggingApiCaller
    */
    protected $api_caller;

    public function __construct(LoggingApiCaller $apiCaller) {
                
        $this->api_caller = $apiCaller;
    }

    public function authenticate($username, $password){

        $url = 'http://api.propiet.com/v1/user/auth';
        $parameters = array('request'=>'{"username":"'.$username.'","password":"'.$password.'"}');
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        $userData = json_decode($output, true);
        if($userData['response']['success'] == true){
            $userData['response']['data']['user_password'] = $password;
        }         
        return $userData['response']['data'];
    }

    public function getUser($username){

        $url = 'http://api.propiet.com/v1/user/me';
        $parameters = array('request'=>'{"username":"'.$username.'"}');
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        
        $userData = json_decode($output, true);        
              
        return $userData['response']['data'];
    }

    public function updateProfile($formData, $apiKey){

        $url = 'http://api.propiet.com/v1/user/update';
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

    public function updatePassword($formData, $apiKey){

        $url = 'http://api.propiet.com/v1/user/chpwd';
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

    public function register(array $userData){

        $url = 'http://api.propiet.com/v1/user/add';
        $parameters = array(
            'request'=>'{"data":'.json_encode($userData).'}',
            'k' => '5696476e19bcb1459dd9d63ddfdba957f290ff74');
        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));

        $userData = json_decode($output, true);        
              
        return $userData['response'];
    }

    public function activate(array $userData){

        $url = 'http://api.propiet.com/v1/user/activate';
        $parameters = array(
            'request'=>'{"data":'.json_encode($userData).'}',
            'k' => '5696476e19bcb1459dd9d63ddfdba957f290ff74');
        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        
        $userData = json_decode($output, true);        
              
        return $userData['response'];
    }

    public function forgotPassword(array $userData){

        $url = 'http://api.propiet.com/v1/user/forgot_password';
        $parameters = array(
            'request'=>'{"data":'.json_encode($userData).'}',
            'k' => '5696476e19bcb1459dd9d63ddfdba957f290ff74');
        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));

        $userData = json_decode($output, true);        
              
        return $userData['response'];
    }

    public function contactAgent(array $userData){

        $url = 'http://api.propiet.com/v1/user/contact_agent';
        $parameters = array(
            'request'=>'{"data":'.json_encode($userData).'}',
            'k' => '5696476e19bcb1459dd9d63ddfdba957f290ff74');
        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        
        $userData = json_decode($output, true);        
              
        return $userData['response'];
    }

    public function sellAgent(array $userData){

        $url = 'http://api.propiet.com/v1/user/sell_agent';
        $parameters = array(
            'request'=>'{"data":'.json_encode($userData).'}',
            'k' => '5696476e19bcb1459dd9d63ddfdba957f290ff74');
        
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        
        $userData = json_decode($output, true);        
              
        return $userData['response'];
    }
}