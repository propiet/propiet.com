<?php

namespace NucleusHub\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

use NucleusHub\CmsBundle\Form\Type\SellAgentType;

class DefaultController extends Controller
{    
    /**
     * @Route("/index", name="homepage")
     * @Template("NucleusHubCmsBundle:Default:index.html.twig")
     */
    public function indexAction(Request $request)
    {        
        $search_service = $this->get('search_service');
        $searchList = $search_service->getFiltersForSearch();

        return array(
            'searchList' => $searchList
            );
    }
 
   /**
     * @Route("/publicaciones/{slug}",defaults={"slug" = "\w+"},name="public_search")
     * @Method({"GET", "POST"})
     * @Template("NucleusHubCmsBundle:Default:search.html.twig")
     */
    public function searchAction($slug,Request $request)
    {
        if(!$slug){
            $slugArray = split("_", $category);
            $currencyPosition = array_search('cr', $slugArray);
            if($currencyPosition){
                $slug = $category;
                $category = NULL;
            }
        }
        $page = $request->query->get('page') ;
        $queryParameters =$this->getQueryData($category,$op,$slug);
        $search_service = $this->get('search_service');
        $searchList = $search_service->getFiltersForSearch();
        $response = $search_service->getPostListQuery($queryParameters,$page);
        $slugParameters=$this->getSlugData($slug);
        $routes = [];
        $posts = NULL;
        $pagination = NULL;
        $countPost = NULL;
        $filters = NULL;
        
        if($response['objects']){
            $posts = $response['objects'];
            $countPost = count($response['objects']);
        }
        if($response['pagination']){
            $pagination = $response['pagination'];
        }
        
        if($slugParameters){
            $filters = $slugParameters;
        }
        
        $filters['operation'] = NULL;
        $filters['category'] = NULL;
        
        $routes = $this->buildBreadcrumd(NULL,$slugParameters['region'],$category,$slugParameters['cities']);
        return array('routes' => $routes,'posts'=>$posts,
            'pagination'=>$pagination,'countPost'=>$countPost,
            "filters"=>$filters,'searchList' => $searchList);
    }
    
    /**
     * @Route("/{category}/{slug}",requirements={"category" = "departamentos|casas|ph|countries-y-barrios-cerrados|quintas|terrenos-y-lotes|campos-y-chacras|galpones-depositos-y-edificios-industriales|locales-comerciales|oficinas|consultorios|cocheras"},defaults={"slug" = false},name="public_search_category")
     * @Method({"GET", "POST"})
     * @Template("NucleusHubCmsBundle:Default:search.html.twig")
     */
    public function searchCategoryAction($category,$slug,Request $request)
    {      
        if(!$slug){
            $slugArray = split("_", $category);
            $currencyPosition = array_search('cr', $slugArray);
            if($currencyPosition){
                $slug = $category;
                $category = NULL;
            }
        }
        $page = $request->query->get('page') ;
        $queryParameters =$this->getQueryData($category,$op,$slug);
        $search_service = $this->get('search_service');
        $searchList = $search_service->getFiltersForSearch();
        $response = $search_service->getPostListQuery($queryParameters,$page);
        $slugParameters=$this->getSlugData($slug);
        $routes = [];
        $posts = NULL;
        $pagination = NULL;
        $countPost = NULL;
        $filters = NULL;
        
        if($response['objects']){
            $posts = $response['objects'];
            $countPost = count($response['objects']);
        }
        if($response['pagination']){
            $pagination = $response['pagination'];
        }
        
        if($slugParameters){
            $filters = $slugParameters;
        }
        
        $filters['operation'] = NULL;
        $filters['category'] = $category;
        
        $routes = $this->buildBreadcrumd(NULL,$slugParameters['region'],$category,$slugParameters['cities']);
        return array('routes' => $routes,'posts'=>$posts,
            'pagination'=>$pagination,'countPost'=>$countPost,
            "filters"=>$filters,'searchList' => $searchList);
    }
    
     /**
     * @Route("/{op}/{slug}",requirements={"op" = "venta|alquiler|emprendimiento"},defaults={"slug" = false},name="public_search_operation")
     * @Method({"GET", "POST"})
     * @Template("NucleusHubCmsBundle:Default:search.html.twig")
     */
    public function searchOperationAction($op,$slug,Request $request)
    {      
        if(!$slug){
            $slugArray = split("_", $op);
            $currencyPosition = array_search('cr', $slugArray);
            if($currencyPosition){
                $slug = $op;
                $category = NULL;
            }
        }
        $page = $request->query->get('page') ;
        $queryParameters =$this->getQueryData($category,$op,$slug);
        $search_service = $this->get('search_service');
        $searchList = $search_service->getFiltersForSearch();
        $response = $search_service->getPostListQuery($queryParameters,$page);
        $slugParameters=$this->getSlugData($slug);
        $routes = [];
        $posts = NULL;
        $pagination = NULL;
        $countPost = NULL;
        $filters = NULL;
        
        if($response['objects']){
            $posts = $response['objects'];
            $countPost = count($response['objects']);
        }
        if($response['pagination']){
            $pagination = $response['pagination'];
        }
        
        if($slugParameters){
            $filters = $slugParameters;
        }
        
        $filters['operation'] = $op;
        $filters['category'] = NULL;
        
        $routes = $this->buildBreadcrumd($op,$slugParameters['region'],$category,$slugParameters['cities']);
        return array('routes' => $routes,'posts'=>$posts,
            'pagination'=>$pagination,'countPost'=>$countPost,
            "filters"=>$filters,'searchList' => $searchList);
    }
    
     /**
     * @Route("/alquiler/{category}/{slug}",requirements={"category" = "departamentos|casas|ph|countries-y-barrios-cerrados|quintas|terrenos-y-lotes|campos-y-chacras|galpones-depositos-y-edificios-industriales|locales-comerciales|oficinas|consultorios|cocheras"},defaults={"slug" = false,"category" = false},name="public_search_rent_categ")
     * @Method({"GET", "POST"})
     * @Template("NucleusHubCmsBundle:Default:search.html.twig")
     */
    public function searchRentCategoryAction($category,$slug,Request $request)
    {
        if(!$slug){
            $slugArray = split("_", $category);
            $currencyPosition = array_search('cr', $slugArray);
            if($currencyPosition){
                $slug = $category;
                $category = NULL;
            }
        }
        $page = $request->query->get('page') ;
        $queryParameters =$this->getQueryData($category,'alquiler',$slug);
        $search_service = $this->get('search_service');
        $searchList = $search_service->getFiltersForSearch();
        $response = $search_service->getPostListQuery($queryParameters,$page);
        $slugParameters=$this->getSlugData($slug);
        $routes = [];
        $posts = NULL;
        $pagination = NULL;
        $countPost = NULL;
        $filters = NULL;
        
        if($response['objects']){
            $posts = $response['objects'];
            $countPost = count($response['objects']);
        }
        if($response['pagination']){
            $pagination = $response['pagination'];
        }
        
        if($slugParameters){
            $filters = $slugParameters;
        }
        
        $filters['operation'] = 'alquiler';
        $filters['category'] = $category;
        
        $routes = $this->buildBreadcrumd('alquiler',$slugParameters['region'],$category,$slugParameters['cities']);
        return array('routes' => $routes,'posts'=>$posts,
            'pagination'=>$pagination,'countPost'=>$countPost,
            "filters"=>$filters,'searchList' => $searchList);
    }
    
    
    /**
     * @Route("/emprendimiento/{category}/{slug}",requirements={"category" = "departamentos|casas|ph|countries-y-barrios-cerrados|quintas|terrenos-y-lotes|campos-y-chacras|galpones-depositos-y-edificios-industriales|locales-comerciales|oficinas|consultorios|cocheras"},defaults={"slug" = false,"category" = false},name="public_search_entrepreneurship_categ")
     * @Method({"GET", "POST"})
     * @Template("NucleusHubCmsBundle:Default:search.html.twig")
     */
    public function searchEntrepreneurshipCategoryAction($category,$slug,Request $request)
    {
        if(!$slug){
            $slugArray = split("_", $category);
            $currencyPosition = array_search('cr', $slugArray);
            if($currencyPosition){
                $slug = $category;
                $category = NULL;
            }
        }
        $page = $request->query->get('page') ;
        $queryParameters =$this->getQueryData($category,'emprendimiento',$slug);
        $search_service = $this->get('search_service');
        $searchList = $search_service->getFiltersForSearch();
        $response = $search_service->getPostListQuery($queryParameters,$page);
        $slugParameters=$this->getSlugData($slug);
        $routes = [];
        $posts = NULL;
        $pagination = NULL;
        $countPost = NULL;
        $filters = NULL;
        
        if($response['objects']){
            $posts = $response['objects'];
            $countPost = count($response['objects']);
        }
        if($response['pagination']){
            $pagination = $response['pagination'];
        }
        
        if($slugParameters){
            $filters = $slugParameters;
        }
        
         $filters['operation'] = 'emprendimiento';
        $filters['category'] = $category;
        
        $routes = $this->buildBreadcrumd('emprendimiento',$slugParameters['region'],$category,$slugParameters['cities']);
        return array('routes' => $routes,'posts'=>$posts,
            'pagination'=>$pagination,'countPost'=>$countPost,
            "filters"=>$filters,'searchList' => $searchList);
    }
    
    
    /**
     * @Route("/venta/{category}/{slug}",requirements={"category" = "departamentos|casas|ph|countries-y-barrios-cerrados|quintas|terrenos-y-lotes|campos-y-chacras|galpones-depositos-y-edificios-industriales|locales-comerciales|oficinas|consultorios|cocheras"},defaults={"slug" = false,"category" = false},name="public_search_sell_categ")
     * @Method({"GET", "POST"})
     * @Template("NucleusHubCmsBundle:Default:search.html.twig")
     */
    public function searchSellCategoryAction($category,$slug,Request $request)
    {
        if(!$slug){
            $slugArray = split("_", $category);
            $currencyPosition = array_search('cr', $slugArray);
            if($currencyPosition){
                $slug = $category;
                $category = NULL;
            }
        }
        $page = $request->query->get('page') ;
        $queryParameters =$this->getQueryData($category,'venta',$slug);
        $search_service = $this->get('search_service');
        $searchList = $search_service->getFiltersForSearch();
        $response = $search_service->getPostListQuery($queryParameters,$page);
        $slugParameters=$this->getSlugData($slug);
        $routes = [];
        $posts = NULL;
        $pagination = NULL;
        $countPost = NULL;
        $filters = NULL;
        
        if($response['objects']){
            $posts = $response['objects'];
            $countPost = count($response['objects']);
        }
        if($response['pagination']){
            $pagination = $response['pagination'];
        }
        
        if($slugParameters){
            $filters = $slugParameters;
        }
        
        $filters['operation'] = 'venta';
        $filters['category'] = $category;
        
        $routes = $this->buildBreadcrumd('venta',$slugParameters['region'],$category,$slugParameters['cities']);
        return array('routes' => $routes,'posts'=>$posts,
            'pagination'=>$pagination,'countPost'=>$countPost,
            "filters"=>$filters,'searchList' => $searchList);
    }
    
    
    /**
     * @Route("/vender-alquilar-info", name="sell_rent_info")
     * @Method({"GET", "POST"})
     * @Template("NucleusHubCmsBundle:Default:sell_info.html.twig")
     */
    public function sellRentInfoAction()
    {
        $routes[] = array('title'=>'Info Vender, Alquilar', 'isActive'=> true,'href'=>'#');
        return array('routes' => $routes,'data'=>$response);
    }
    
    
    /**
     * @Route("/vender-alquilar-buscar-info", name="sell_rent_search_info")
     * @Method({"GET", "POST"})
     * @Template("NucleusHubCmsBundle:Default:search_info.html.twig")
     */
    public function sellRentSearchInfoAction()
    {
        $routes[] = array('title'=>'Info Vender,Alquilar,Búsqueda', 'isActive'=> true,'href'=>'#');
        return array('routes' => $routes,'data'=>$response);
    }
    
    
    /**
     * @Route("/publicacion/{id}-{title}", requirements={"title" = ".+"},name="public_post")
     * @Method({"GET", "POST"})
     * @Template("NucleusHubCmsBundle:Default:public_post.html.twig")
     */
    public function postAction($id,$title)
    {
        $data["data"]=["id"=>$id];
        $post_service = $this->get('post_service');
        $response = $post_service->getPublicPost(json_encode($data));

        if(!$response){
           throw $this->createNotFoundException('La Publicacion no existe');
        }
        $routes[] = array('title'=>'Publicacion', 'isActive'=> false,'href'=>'#');
        $routes[] = array('title'=>$id.'-'.$title, 'isActive'=> true,'href'=>'#');
        $postUrl = 'http://www.propiet.com/publicacion/'.$id.'-'.$title;
        $features = json_encode($response['form'],true);
        return array(
            'routes' => $routes,
            'data'=>$response, 
            'post_url'=>$postUrl,
            'features'=>$features
            );
    }
    
    
    /**
     * @Route("/agentes/perfil/{id}", name="agent_profile")
     * @Method({"GET", "POST"})
     * @Template("NucleusHubCmsBundle:Default:agent_profile.html.twig")
     */
    public function agentProfileAction($id)
    {
        $routes[] = array('title'=>'agentes', 'isActive'=> false,'href'=>'#');
        $routes[] = array('title'=>'perfil', 'isActive'=> false,'href'=>'#');
        $routes[] = array('title'=>'name', 'isActive'=> true,'href'=>'#');
        return array('routes' => $routes,'data'=>$response);
    }
    
    
    
    
    
     /**
     * @Route("/tasaciones", name="price_your_home")
     * @Method({"GET", "POST"})
     * @Template("NucleusHubCmsBundle:Default:price_your_home.html.twig")
     */
    public function priceYourHomeAction()
    {
        $request = $this->container->get('request');
        $form = $this->createForm(new SellAgentType());
        $result = '';
        if ($request->getMethod() == 'POST') {
            $form->bind($request);

            if ($form->isValid()) {
                $data = $form->getData();
                $user_service = $this->get('user_service');
                $response = $user_service->sellAgent($data);
                
                if($response['data']) {
                    switch($response['data']){
                        case 'SCC_SENT':
                            $result = 1;
                            break;
                        case 'ERR_UNAUTHORIZED':
                            $result = 0;
                            break;
                    }

                }
            }
        } 
       $routes[] = array('title'=>'Tasaciones', 'isActive'=> true,'href'=>'#');
        return array('routes' => $routes, 'form'=>$form->createView(),'data'=>$response, 'sent' => $result);
    }
    


    /**
     * @Route("/privacidad", name="privacity_condition")
     * @Method({"GET", "POST"})
     * @Template("NucleusHubCmsBundle:Default:privacity.html.twig")
     */
    public function privacityAction()
    {
       $routes[] = array('title'=>'Privacidad', 'isActive'=> true,'href'=>'#');
        return array('routes' => $routes,'data'=>$response);
    }
    
    /**
     * @Route("/terminos-condiciones", name="terms_condition")
     * @Method({"GET", "POST"})
     * @Template("NucleusHubCmsBundle:Default:terms_cond.html.twig")
     */
    public function termsAction()
    {
       $routes[] = array('title'=>'Términos y Condiciones', 'isActive'=> true,'href'=>'#');
        return array('routes' => $routes,'data'=>$response);
    }
    
    
    
    
    
    
    
    public function getSlugData($slug)
    {
        $slugArray = split("_", $slug);
        $params=[];
        $citiesPosition = array_search('barrio', $slugArray);
        $regionPosition = array_search('ciudad', $slugArray);
        $priceMinPosition = array_search('pmin', $slugArray);
        $priceMaxPosition = array_search('pmax', $slugArray);
        $currencyPosition = array_search('cr', $slugArray);
        $cities = $citiesPosition ? $slugArray[$citiesPosition+1] : NULL;
        $region = $regionPosition ? $slugArray[$regionPosition+1] : NULL;
        $priceMin = $priceMinPosition ? $slugArray[$priceMinPosition+1] : NULL;
        $priceMax = $priceMaxPosition ? $slugArray[$priceMaxPosition+1] : NULL;
        $currency = $currencyPosition ? $slugArray[$currencyPosition+1] : NULL;
        $cities = $cities ? explode('|', $cities) : NULL;
        $region = $region ? str_replace("-"," ", $region) : NULL;
        $priceMin = $priceMin ? str_replace([",","."],"", $priceMin) : NULL;
        $priceMax = $priceMax ? str_replace([",","."],"", $priceMax) : NULL;
        $params = [
            "region"=>$region,
            "cities"=>$cities,
            "priceMin"=>$priceMin,
            "priceMax"=>$priceMax,
            "currency"=>$currency,
        ];
        return $params;
    }
    public function getQueryResquestParameters($params,$search_service,$operation,$category,$page)
    {
        $citiesList = json_decode($search_service->getCitiesListJson(),true);
        $regionList = json_decode($search_service->getRegionListJson(),true);
        $categoriesList = json_decode($search_service->getCategoriesListJson(),true);
        $citiesIds=[];
        $regionId=NULL;
        $operationId=NULL;
        $categoryId=NULL;
        $queryRequest=NULL;
        
        if($params["cities"]){
            foreach ($params["cities"] as $key => $cityName) {                
                foreach($citiesList as $key => $value){
                    if($value['display_name'] == $cityName){
                        $citiesIds[]=["id"=>$value['id']];
                    }
                }
            }
        }
        
        if($params["region"]){
                foreach($regionList as $key => $value){
                    if(strtolower($value['name']) == $params["region"]){
                        $regionId=$value['id'];
                    }
                }
        }
        
        if($operation){
            foreach($categoriesList[0]['OPERATION_TYPE'] as $key => $value){
                    if(strtolower($value) === $operation){
                        $operationId=$key;
                    }
            }
        }
        
        
        if($category){
            foreach($categoriesList[0]['CATEGORIES'] as $key => $value){
                    if(strtolower($value) === $category){
                        $categoryId=$key;
                    }
            }
        }
        
    
        
        if($operationId){
            $queryRequest['operation'] = $operationId;
        }    

        if($categoryId){
            $queryRequest['category'] = $categoryId;
        } 

        if($regionId){
            $queryRequest['region'] = $regionId;
        }

        if($citiesIds){
            $queryRequest['city'] = $citiesIds;
        }

        if($params['priceMin']){
            $queryRequest['price_min'] = $params['priceMin'];
        }

        if($params['priceMax']){
            $queryRequest['price_min'] = $params['priceMax'];
        }

        if($params['currency']){
            $queryRequest['price_min'] = $params['currency'];
        }

        $queryRequest['pagination'] = ["page" => $page ? $page : 1 ];    

        return json_encode($queryRequest);
        
    }
    public function buildBreadcrumd($operation,$region,$category,$cities)
    {
        
        $routes = [];
        if($operation){
            $routes[] = array('title'=>ucwords($operation),'isActive'=> false,'href'=>'#');
        }
        
        
        if($region){
            $routes[] = array('title'=>ucwords($region), 'isActive'=> false,'href'=>'#');
        }
        
        
        if($category){
            $description = ucwords($category)." en ".ucwords($operation);
            if($cities){
                foreach ($cities as $key => $value){
                    if($value){
                        if( $key > 0 ){
                            $description .=" o ".$value;
                        }else{
                            $description .=" en ".$value;
                        }
                    }
                }
                $description.= ", ".ucwords($region);
                $routes[] = array('title'=>$description, 'isActive'=> false,'href'=>'#');
            } else {
                $description.= ", ".ucwords($region);
                $routes[] = array('title'=>$description, 'isActive'=> false,'href'=>'#');
            }
        }
        
        return $routes;
    }
    public function buildPostViewModel($posts)
    {
        $countPosts = count($posts);
        $search_service = $this->get('search_service');
        $regionList = $regionList = json_decode($search_service->getRegionListJson(),true);
        for ($i=0;$i<$countPosts;$i++){
            if($posts[$i]['currency_id']){
                $posts[$i]['currency_name']= $posts[$i]['currency_id'] == 1 ? "AR" :"US";
            }
            
            if($posts[$i]['region_id']){
                foreach($regionList as $key => $value){
                    if(strtolower($value['id']) == $posts[$i]['region_id']){
                        $posts[$i]['region_name']= $value['name'];
                    }
                }
            }
            
            if(!$posts[$i]['img']){
                $posts[$i]['img'] = NULL;
            }
        }
       return $posts;
    }
    
    public function getQueryData($category,$op,$slug)
    {
        $slugArray = split("_", $slug);
        $params=[];
        $citiesPosition = array_search('barrio', $slugArray);
        $regionPosition = array_search('ciudad', $slugArray);
        $priceMinPosition = array_search('pmin', $slugArray);
        $priceMaxPosition = array_search('pmax', $slugArray);
        $currencyPosition = array_search('cr', $slugArray);
        
        $cities = $citiesPosition ? $slugArray[$citiesPosition+1] : NULL;
        $region = $regionPosition ? $slugArray[$regionPosition+1] : NULL;
        $priceMin = $priceMinPosition ? $slugArray[$priceMinPosition+1] : NULL;
        $priceMax = $priceMaxPosition ? $slugArray[$priceMaxPosition+1] : NULL;
        $currency = $currencyPosition ? $slugArray[$currencyPosition+1] : NULL;
        
        $operation = $op ? ucwords($op)  :NULL;
        $categories = $category ? $category : NULL;
        $cities = $cities ? explode('|', $cities) : NULL;
        $region = $region ? str_replace("-"," ", $region) : NULL;
        $priceMin = $priceMin ? str_replace([",","."],"", $priceMin) : NULL;
        $priceMax = $priceMax ? str_replace([",","."],"", $priceMax) : NULL;
        
        $params = [
            "category"=>$categories,
            "operation"=>$operation,
            "region"=>$region,
            "cities"=>$cities,
            "priceMin"=>$priceMin,
            "priceMax"=>$priceMax,
            "currency"=>$currency,
        ];
        return $params;
    }
    
}
