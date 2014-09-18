<?php

namespace NucleusHub\CmsBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;


class UserController extends Controller
{
    

    /**
     * @Route("/admin/publicaciones", name="admin_publications_list")
     * @Template()
     */
    public function indexAction()
    {       
        $securityContext = $this->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();
        $apiKey = $user->getToken();
       

        if($user->getRoles()[0] == 'ROLE_AGENT') {
            return $this->redirect( $this->get('router')->generate('admin_publications_list_red_interna'));            
        } else {
            return $this->redirect( $this->get('router')->generate('admin_publications_list_new'));
        }

        
    }

  
    /**
     * @Route("/admin/perfil", name="admin_profile")
     * @Template()
     */
    public function profileAction()
    {       
        $securityContext = $this->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();

        $user_service = $this->get('user_service');
        $user = $user_service->getUser($user->getEmail());
        
        $routes[] = array('title'=>'Mi Perfil', 'isActive'=> false,'href'=> $this->get('router')->generate('admin_profile'));
        $routes[] = array('title'=>$user['firstname'].' '.$user['lastname'], 'isActive'=> true,'href'=> $this->get('router')->generate('admin_profile'));

        
        return array('routes' => $routes, 'user' => $user, 'role'=>$user['role']);
    }

      /**
    * @Route("/admin/publicaciones/search", name="admin_publicaciones_search")
    * @Template()
    */
    public function adminSearchAction(Request $request){

        $securityContext = $this->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();
        $apiKey = $user->getToken();
        $hasPosts = true;
       
        $queryParameters = array("status" => 3,"address" => $request->request->get('address'));

        if($request->request->get('page'))
            $page_name = $request->request->get('page');

        if($page_name == "Red Interna"){
            $url = "admin_publications_list_red_interna";
        }elseif($page_name == "Publicadas"){
            $url = "admin_publications_list_published";
            $queryParameters['userid'] = $user->getId();            
        }elseif($page_name == "Asignadas"){
            $url = "admin_publications_list_assigned";
            $queryParameters['agentid'] = $user->getId();
        }elseif($page_name == "Nuevas"){
            $url = "admin_publications_list_new";
            $queryParameters['userid'] = $user->getId();
            $queryParameters['status'] = 1;
        }else{
            $url = "admin_publications_list_published"; 
        }

        
        $search_service = $this->get('search_service');
        $response = $search_service->getPostListQuery($queryParameters,$page);


        if($response['objects']){
            $posts = $response['objects'];
        }else{
            $hasPosts = false;
        }
        if($response['pagination']){
            $pagination = $response['pagination'];
            $pagination['page'] = $pagination['actual_page'];
        }
        
        

        $routes[] = array('title'=>'Publicaciones', 'isActive'=> false,'href'=> $this->get('router')->generate('admin_publications_list'));
        $routes[] = array('title'=> $page_name, 'isActive'=> true,'href'=> $this->get('router')->generate($url));
        
        return $this->render('NucleusHubCmsBundle:User:index.html.twig',
            array('routes' => $routes, 'user' => $user, 'posts'=> $posts, 'pagination'=> $pagination, 'hasPosts' => $hasPosts,'search' => $request->request->get('address'))
            );
    }

    /**
     * @Route("/admin/publicaciones/terceros", name="admin_publications_list_de_terceros")
     * @Template()
     */
    public function postListTercerosAction(Request $request){

        $securityContext = $this->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();
        $apiKey = $user->getToken();
        $hasPosts = true;
        $page = 1;

        if($request->query->get('page'))
            $page = $request->query->get('page');
        

        $queryParameters = array("status" => 1);
        $search_service = $this->get('search_service');

        $response = $search_service->getPostListQuery($queryParameters,$page);
        // $post_service = $this->get('post_service');
        // $response = $post_service->getPostList($apiKey, $post_service::STATUS_NEW, 0,0, $page);
        #$searchList = $search_service->getFiltersForSearch();
        
        if($response['objects']){
            $posts = $response['objects'];
        }else{
            $hasPosts = false;
        }
        if($response['pagination']){
            $pagination = $response['pagination'];
            $pagination['page'] = $pagination['actual_page'];
        }

        $routes[] = array('title'=>'Publicaciones', 'isActive'=> false,'href'=> $this->get('router')->generate('admin_publications_list'));
        $routes[] = array('title'=>'De Terceros', 'isActive'=> true,'href'=> $this->get('router')->generate('admin_publications_list_de_terceros'));
        
        return $this->render('NucleusHubCmsBundle:User:index.html.twig',
            array('routes' => $routes, 'user' => $user, 'posts'=> $posts, 
                'pagination'=> $pagination, 'hasPosts' => $hasPosts
                ));

    }

    /**
     * @Route("/admin/publicaciones/test", name="admin_publications_test")
     * @Template("NucleusHubCmsBundle:Default:test.html.twig")
     */
    public function testAction(){

       $queryParameters = array("status" => 3);
        $search_service = $this->get('search_service');

        $response = $search_service->getPostListQuery($queryParameters,$page);

        if ($response == 'ERR_EMPTY_LIST') {
            $hasPosts = false;
        } else {
            
            if($response['objects']){
                $posts = $response['objects'];
            }
        }

        return $this->render('NucleusHubCmsBundle:Default:test.html.twig',
            array('posts'=> $posts));
    }

    /**
     * @Route("/admin/publicaciones/red", name="admin_publications_list_red_interna")
     * @Template()
     */
    public function postListRedInternaAction(Request $request){

        $securityContext = $this->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();
        $apiKey = $user->getToken();
        $hasPosts = true;
        $page = 1;

        if($request->query->get('page'))
            $page = $request->query->get('page');
        

        //$post_service = $this->get('post_service');
        //$r = $post_service->getPostList($apiKey, $post_service::STATUS_PUBLISHED, (-1),0, $page);
        //$r = $post_service->getAgent(); 
        $queryParameters = [];
        $search_service = $this->get('search_service');
        $response = $search_service->getPostListQuery($queryParameters,$page);
        #$searchList = $search_service->getFiltersForSearch();
        
        if($response['objects']){
            $posts = $response['objects'];
        }else{
            $hasPosts = false;
        }
        if($response['pagination']){
            $pagination = $response['pagination'];
            $pagination['page'] = $pagination['actual_page'];
        }

        $routes[] = array('title'=>'Publicaciones', 'isActive'=> false,'href'=> $this->get('router')->generate('admin_publications_list'));
        $routes[] = array('title'=>'Red Interna', 'isActive'=> true,'href'=> $this->get('router')->generate('admin_publications_list_red_interna'));
        
        return $this->render('NucleusHubCmsBundle:User:index.html.twig',
            array('routes' => $routes, 'user' => $user, 'posts'=> $posts, 
                'pagination'=> $pagination, 'hasPosts' => $hasPosts
                ));
    }
    /**
     * @Route("/admin/publicaciones/asignadas", name="admin_publications_list_assigned")
     * @Template()
     */
    public function postListAssignedAction(Request $request)
    {       

        $securityContext = $this->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();
        $apiKey = $user->getToken();
        $hasPosts = true;        
        $page = 1;

        if($request->query->get('page')){
            $page = $request->query->get('page');
        }
        
        if($user->getRoles()[0] != 'ROLE_AGENT') {
            return new RedirectResponse($this->container->get('router')->generate('admin_publications_list'));
        }

        //$post_service = $this->get('post_service');
        //$response = $post_service->getPostList($apiKey, $post_service::STATUS_NONE, 0, $user->getId());

        //$post_service = $this->get('post_service');
        //$r = $post_service->getPostList($apiKey, $post_service::STATUS_PUBLISHED, (-1),0, $page);
        //$r = $post_service->getAgent(); 
        $queryParameters = array("status" => 3,"agentid" => $user->getId());
        $search_service = $this->get('search_service');
        $response = $search_service->getPostListQuery($queryParameters,$page);
        
                  
        if($response['objects']){
            $posts = $response['objects'];
        }else{
            $hasPosts = false;
        }
        if($response['pagination']){
            $pagination = $response['pagination'];
            $pagination['page'] = $pagination['actual_page'];
        }
    

        $routes[] = array('title'=>'Publicaciones', 'isActive'=> false,'href'=> $this->get('router')->generate('admin_publications_list'));
        $routes[] = array('title'=>'Asignadas', 'isActive'=> true,'href'=> $this->get('router')->generate('admin_publications_list_assigned'));
        
        return $this->render('NucleusHubCmsBundle:User:index.html.twig',
            array('routes' => $routes, 'user' => $user, 'posts'=> $posts, 'pagination'=> $pagination, 'hasPosts' => $hasPosts));
    }

    /**
     * @Route("/admin/publicaciones/nuevas", name="admin_publications_list_new")
     * @Template()
     */
    public function postListNewAction()
    {       

        $securityContext = $this->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();
        $apiKey = $user->getToken();
        $hasPosts = true;


        $queryParameters = array("status" => 1,"userid" => $user->getId());
        $search_service = $this->get('search_service');
        $response = $search_service->getPostListQuery($queryParameters,$page);
        
        if($response['objects']){
            $posts = $response['objects'];
        }else{
            $hasPosts = false;
        }
        if($response['pagination']){
            $pagination = $response['pagination'];
            $pagination['page'] = $pagination['actual_page'];
        }

        $routes[] = array('title'=>'Publicaciones', 'isActive'=> false,'href'=> $this->get('router')->generate('admin_publications_list'));
        $routes[] = array('title'=>'Nuevas', 'isActive'=> true,'href'=> $this->get('router')->generate('admin_publications_list_new'));
        
        return $this->render('NucleusHubCmsBundle:User:index.html.twig',
            array('routes' => $routes, 'user' => $user, 'posts'=> $posts, 'pagination'=> $pagination, 'hasPosts' => $hasPosts));
    }

    /**
     * @Route("/admin/publicaciones/publicadas", name="admin_publications_list_published")
     * @Template()
     */
    public function postListPublishedAction()
    {       

        $securityContext = $this->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();
        $apiKey = $user->getToken();
        $hasPosts = true;        
        $page = 1;
        // $post_service = $this->get('post_service');
        // $response = $post_service->getPostList($apiKey, $post_service::STATUS_PUBLISHED, $user->getId(),0);

        $queryParameters = array("status" => 3,"userid" => $user->getId());
        $search_service = $this->get('search_service');
        $response = $search_service->getPostListQuery($queryParameters,$page);
        
        if($response['objects']){
            $posts = $response['objects'];
        }else{
            $hasPosts = false;
        }
        if($response['pagination']){
            $pagination = $response['pagination'];
            $pagination['page'] = $pagination['actual_page'];
        }

        $routes[] = array('title'=>'Publicaciones', 'isActive'=> false,'href'=> $this->get('router')->generate('admin_publications_list'));
        $routes[] = array('title'=>'Publicadas', 'isActive'=> true,'href'=> $this->get('router')->generate('admin_publications_list_published'));
        
        return $this->render('NucleusHubCmsBundle:User:index.html.twig',
            array('routes' => $routes, 'user' => $user, 'posts'=> $posts, 'pagination'=> $pagination, 'hasPosts' => $hasPosts));
    }

    /**
     * @Route("/admin/publicaciones/pausadas", name="admin_publications_list_paused")
     * @Template()
     */
    public function postListPausedAction()
    {       

        $securityContext = $this->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();
        $apiKey = $user->getToken();
        $hasPosts = true;        

        $post_service = $this->get('post_service');
        $response = $post_service->getPostList($apiKey, $post_service::STATUS_PAUSED, $user->getId());
        
        if ($response == 'ERR_EMPTY_LIST') {
            $hasPosts = false;
        } else {
            $posts = $response['data']['list'];
            $pagination = $response['pagination'];
        }

        $routes[] = array('title'=>'Publicaciones', 'isActive'=> false,'href'=> $this->get('router')->generate('admin_publications_list'));
        $routes[] = array('title'=>'Pausadas', 'isActive'=> true,'href'=> $this->get('router')->generate('admin_publications_list_paused'));
        
        return $this->render('NucleusHubCmsBundle:User:index.html.twig',
            array('routes' => $routes, 'user' => $user, 'posts'=> $posts, 'pagination'=> $pagination, 'hasPosts' => $hasPosts));
    }

    /**
     * @Route("/admin/publicaciones/archivadas", name="admin_publications_list_inactive")
     * @Template()
     */
    public function postListInactiveAction()
    {       

        $securityContext = $this->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();
        $apiKey = $user->getToken();
        $hasPosts = true;        

        $post_service = $this->get('post_service');
        $response = $post_service->getPostList($apiKey, $post_service::STATUS_INACTIVE, $user->getId());
        
        if ($response == 'ERR_EMPTY_LIST') {
            $hasPosts = false;
        } else {
            $posts = $response['data']['list'];
            $pagination = $response['pagination'];
        }

        $routes[] = array('title'=>'Publicaciones', 'isActive'=> false,'href'=> $this->get('router')->generate('admin_publications_list'));
        $routes[] = array('title'=>'Archivadas', 'isActive'=> true,'href'=> $this->get('router')->generate('admin_publications_list_inactive'));
        
        return $this->render('NucleusHubCmsBundle:User:index.html.twig',
            array('routes' => $routes, 'user' => $user, 'posts'=> $posts, 'pagination'=> $pagination, 'hasPosts' => $hasPosts));
    }

    /**
     * @Route("/admin/publicaciones/finalizadas", name="admin_publications_list_sold")
     * @Template()
     */
    public function postListSoldAction()
    {       

        $securityContext = $this->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();
        $apiKey = $user->getToken();
        $hasPosts = true;        

        $post_service = $this->get('post_service');
        $response = $post_service->getPostList($apiKey, $post_service::STATUS_SOLD, $user->getId());
        
        if ($response == 'ERR_EMPTY_LIST') {
            $hasPosts = false;
        } else {
            $posts = $response['data']['list'];
            $pagination = $response['pagination'];
        }

        $routes[] = array('title'=>'Publicaciones', 'isActive'=> false,'href'=> $this->get('router')->generate('admin_publications_list'));
        $routes[] = array('title'=>'Finalizadas', 'isActive'=> true,'href'=> $this->get('router')->generate('admin_publications_list_sold'));
        
        return $this->render('NucleusHubCmsBundle:User:index.html.twig',
            array('routes' => $routes, 'user' => $user, 'posts'=> $posts, 'pagination'=> $pagination, 'hasPosts' => $hasPosts));
    }

    /**
     * @Route("/admin/publicaciones/crear", name="add_post_user")
     * @Template()
     */
    public function addUserPostAction()
    {   
       
        //var_dump($this->container->getServiceIds());
        $securityContext = $this->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();
        
        $routes[] = array('title'=>'Publicaciones', 'isActive'=> false,'href'=> $this->get('router')->generate('admin_publications_list'));
        $routes[] = array('title'=>'Crear', 'isActive'=> true,'href'=> $this->get('router')->generate('add_post_user'));
        return array('routes' => $routes, 'user' => $user);
    }

    /**
     * @Route("/admin/publicaciones/editar/{id}", name="edit_post_user")
     * @Template()
     */
    public function editUserPostAction($id)
    {       
        $securityContext = $this->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();
        $apiKey = $user->getToken();

        $data["data"]=["id"=>$id];

        $post_service = $this->get('post_service');
        $post =$post_service->getPost(json_encode($data), $apiKey);
        
        
        $propertyForm = $post_service->getPostForm(json_encode($data), $apiKey);

        $post['location'] = json_decode($post['location'], true);
        $post['property'] = json_decode($post['property'], true);
        $post['images'] = json_decode($post['images'], true);

        $routes[] = array('title'=>'Publicaciones', 'isActive'=> false,'href'=> $this->get('router')->generate('admin_publications_list'));
        $routes[] = array('title'=>'Editar', 'isActive'=> false,'href'=> $this->get('router')->generate('edit_post_user', array('id'=>$id)));
        $routes[] = array('title'=>$post['post_data']['title'], 'isActive'=> true,'href'=> $this->get('router')->generate('edit_post_user', array('id'=>$id)));
        return array('routes' => $routes, 'post' => $post, 'postForm'=> $propertyForm['form'][0], 'user'=>$user);
    }
    
    /**
     * @Route("/admin/busquedas", name="admin_saved_query_list")
     * @Template()
     */
    public function savedQuerysAction()
    {       
        $securityContext = $this->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();
        $apiKey = $user->getToken();
        $hasElements = true;

        $saved_query_service = $this->get('saved_query_service');        
        $response = $saved_query_service->getSavedQueryList($apiKey, $user->getId());
        $routes[] = array('title'=>'Búsquedas', 'isActive'=> true,'href'=> $this->get('router')->generate('admin_saved_query_list'));        

        if ($response == 'ERR_EMPTY_LIST') {
            $hasElements = false;
        } else {
            $savedQuerys = $response['data']['list'];
            $pagination = $response['pagination'];
        }        
        return array('routes' => $routes, 'user' => $user, 'savedQuerys'=> $savedQuerys, 'pagination'=> $pagination, 'hasElements' => $hasElements);
    }

    /**
     * @Route("/admin/busquedas/editar/{id}", name="admin_saved_query_edit")
     * @Template()
     */
    public function savedQueryEditAction($id)
    {       
        $securityContext = $this->get('security.context');
        $token = $securityContext->getToken();
        $user = $token->getUser();
        $apiKey = $user->getToken();
        $exists = true;

        $saved_query_service = $this->get('saved_query_service');        
        $response = $saved_query_service->getSavedQuery($apiKey, $user->getId(), $id);

        $routes[] = array('title'=>'Búsquedas', 'isActive'=> false,'href'=> $this->get('router')->generate('admin_saved_query_list'));
        $routes[] = array('title'=>'Editar', 'isActive'=> false,'href'=> $this->get('router')->generate('admin_saved_query_list'));

        if ($response == 'ERR_NOT_FOUND' || $response == null) {
            $exists = false;
        } else {
            $savedQuery = $response['data'];
            $routes[] = array('title'=>$savedQuery['name'], 'isActive'=> true,'href'=> $this->get('router')->generate('admin_saved_query_edit', array('id'=>$id)));             
        }        
        return array('routes' => $routes, 'user' => $user, 'savedQuery'=> $savedQuery, 'exists' => $exists);
    }

    public function listallpostsinfo($posts){

        
        $post_service = $this->get('post_service');
        $i = 0;

        foreach ($ids as $posts) {
            $data["data"]=["id"=>$ids->id];
            $response = $post_service->getPublicPost(json_encode($data));
            $propertys[$i] = $response;
            $i++;
        } 

        return $propertys;
    }

}
