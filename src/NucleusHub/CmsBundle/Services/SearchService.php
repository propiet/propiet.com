<?php
namespace NucleusHub\CmsBundle\Services;

use Lsw\ApiCallerBundle\Caller\LoggingApiCaller;
use Lsw\ApiCallerBundle\Call\HttpPost;

class SearchService 
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

    
    
    public function getSearchList(){
        $url = $this->apiUrls['base'].$this->apiUrls['list']['search'];
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
    
    public function getRegionsList(){
        $url = $this->apiUrls['base'].$this->apiUrls['list']['regions'];
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

    public function getCitiesList(){
        $url = $this->apiUrls['base'].$this->apiUrls['list']['cities'];
        $parameters = array('request'=>'{"pagination":{"page":1},"data":{"region":1}}');
        $output = $this->api_caller->call(new HttpPost($url, $parameters));
        $data = json_decode($output, true);
        if($data['response']['success'] == true){
           $response = $data['response']['data']['list'];
        } else {
            $response = array();
        }
        return $response;
    }
    
    public function getFiltersForSearch(){
        $filterSearch = NULL;
        $urlCity = $this->apiUrls['base'].$this->apiUrls['city']['list'];
        $urlRegion = $this->apiUrls['base'].$this->apiUrls['region']['list'];
        $urlCategory = $this->apiUrls['base'].$this->apiUrls['category']['list'];
        $urlCurrency = $this->apiUrls['base'].$this->apiUrls['currency']['list'];
        $urlOperation = $this->apiUrls['base'].$this->apiUrls['operation']['list'];
        $parameters = array('format'=>'json');
        $parametersCity = array('format'=>'json','limit'=>0);
        
        $cities = $this->api_caller->call(new \Lsw\ApiCallerBundle\Call\HttpGetJson ($urlCity, $parametersCity,true));
        if( $cities['objects'] ){
           $citiesSearch=NULL;
           $citySearchSemantic=NULL;
           $filterSearch['city'] = $cities['objects'];

            foreach($cities['objects'] as $key => $value){
                $citiesSearch[strtolower($value['name'])]=($value['id']);
                $citySearchSemantic[]=$value['name'];
            }
            
            $filterSearch['city_search'] = json_encode($citiesSearch,true);
            $filterSearch['city_search_semantic'] = json_encode($citySearchSemantic,true);
            
        } else {
            $filterSearch['city'] = NULL;
            $filterSearch['city_search'] = NULL;
            $filterSearch['city_search_semantic']= NULL;
        }
        
        $region = $this->api_caller->call(new \Lsw\ApiCallerBundle\Call\HttpGetJson($urlRegion, $parameters,true));
        if( $region['objects'] ){
           $filterSearch['region'] = $region['objects'];
        } else {
            $filterSearch['region'] = NULL;
        }
        
        $category = $this->api_caller->call(new \Lsw\ApiCallerBundle\Call\HttpGetJson($urlCategory, $parameters,true));
        if( $category['objects'] ){
            $categories=NULL;
            foreach($category['objects'] as $key => $value){
                $categories[$value['id']]=['id'=>$value['id'],'name'=>$value['name']];
            }
            
           $filterSearch['category'] = $categories;
        } else {
            $filterSearch['category'] = NULL;
        }
        
        $currency = $this->api_caller->call(new \Lsw\ApiCallerBundle\Call\HttpGetJson($urlCurrency, $parameters,true));
        if( $currency['objects'] ){
           $currencies=NULL;
            foreach($currency['objects'] as $key => $value){
                $currencies[]=['id'=>$value['id'],'name'=>$value['name'],'symbol'=>$value['symbol']];
            }
            $filterSearch['currency'] = $currencies;
        } else {
            $filterSearch['currency'] = NULL;
        }
        
        
        $operation = $this->api_caller->call(new \Lsw\ApiCallerBundle\Call\HttpGetJson($urlOperation, $parameters,true));
        if( $operation['objects'] ){
           $filterSearch['operation'] = $operation['objects'];
        } else {
            $filterSearch['operation'] = NULL;
        }
        
        return $filterSearch;
    }
    
    public function getPropertysIds($params){

        $url =  $this->apiUrls['base'].$this->apiUrls['property']['list'];

        if($params['address'])
            $parameters['location__address__icontains'] = $params['address'];

        $propertys = $this->api_caller->call(new \Lsw\ApiCallerBundle\Call\HttpGetJson ($url, $parameters,true));

        if( $propertys['objects'] ){
            if($propertys['meta']['total_count']){

                $property_id = $propertys['objects'][0]['id'];

                for($i = 1; $i < $propertys['meta']['total_count']; $i++){

                    $property_id .= ','.$propertys['objects'][$i]['id'];
                }
            }
        }else{
            $property_id = 0;
        } 

        return $property_id;
    }

    public function getPostListQuery($params,$page=null){
        //http://api.propiet.com/v1/post/
        //?limit=0
        //&format=json
        //&category__name__in=Departamentos
        //&operation__operation__exact=Alquiler
        //&currency__name__exact=Pesos
        //&city__name__exact=Capital%20Federal
        //&region__name__exact=Buenos%20Aires
        //             "category"=>$categories,
        //            "operation"=>$operation,
        //            "region"=>$region,
        //            "cities"=>$cities,
        //            "priceMin"=>$priceMin,
        //            "priceMax"=>$priceMax,
        //            "currency"=>$currency,
        // http:///v1/search/?format=json&property__category__name__in=Casas,Departamentos&limit=0
        
        $postList = NULL;
        $urlPost = $this->apiUrls['base'].$this->apiUrls['search']['list'];
        $parameters = array('format'=>'json',"limit"=>20);
        
        if($params['address']){
            $params_property = array('address' => $params['address']);
            $parameters['property__id__in'] = $this->getPropertysIds($params_property);
        }
        if($params['userid']){
            $parameters['user__id']=$params['userid'];
        }
        if($params['agentid']){
            $parameters['agent__id']=$params['agentid'];
        }
        if($params['category']){
            $parameters['category__name__in']=$params['category'];
        }
        if($params['operation']){
            $parameters['operation__operation__exact']=$params['operation'];
        }
        if($params['region']){
            $parameters['region__name__exact']=$params['region'];
        }
        if($params['cities']){
             foreach ($params['cities'] as $key=>$value){
               if($value){
                  $cities.= $value.',';
               }
            }
             $parameters['city__name__in']=$cities;
        }
        if($params['priceMin']){
            $parameters['price__gte']=$params['priceMin'];
        }
        if($params['priceMax']){
            $parameters['price__lte']=$params['priceMax'];
        }
        if($params['currency']){
            $parameters['currency__id']=$params['currency'];
        }
        if($params['status']){
            $parameters['status'] = $params['status'];
        }else{
            $parameters['status'] = self::STATUS_PUBLISHED;
        }
        
        if($page){
            $parameters['offset']=($page-1)*20;
        }
        
        $post = $this->api_caller->call(new \Lsw\ApiCallerBundle\Call\HttpGetJson ($urlPost, $parameters,true));
        if( $post['objects'] ){
           $postList= $post;
        } else {
            $postList= NULL;
        }
        
//         $output = super(PageNumberPaginator, self).page();
//         $output['page_number'] = int(self.offset / self.limit) + 1;

        if($post['meta']){
            $pagActual = $page ? $page : 1;
            $totalPages = 1;
               
               if($post['meta']['total_count']){
                  $totalPages = (int) ceil(($post['meta']['total_count'] / $post['meta']['limit']));
                 
               }
               foreach (range($pagActual, $totalPages) as $number) {
                    $range[]=['page'=>$number];
               }
               $postList['pagination'] = [
                   'total_pages'=>$totalPages,
                   'actual_page'=>$pagActual,
                   'page_range'=>$range,
                   'count'=>$post['meta']['total_count'],
                   'page' => $pagActual,
                       ];
            }else{
                $postList['pagination'] = [
                   'total_pages'=>NULL,
                   'actual_page'=>NULL,
                   'page_range'=>NULL,
                   'count'=>NULL,
                   'page' => NULL,
                       ];
            }
           
        
        return $postList;
    }
} 