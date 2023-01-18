<?php
/**
* @file
* Contains \Drupal\dynamic_theme_switcher\Theme\ThemeNegotiator
*/
namespace Drupal\dynamic_theme_switcher\Theme;
 
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\Theme\ThemeNegotiatorInterface;
use Drupal\session_inspector\Plugin\BrowserFormatInterface;
 
class ThemeNegotiator implements ThemeNegotiatorInterface {
 
   /**
    * @param RouteMatchInterface $route_match
    * @return bool
    */
   public function applies(RouteMatchInterface $route_match)
   {
       return $this->negotiateRoute($route_match) ? true : false;
   }
 
   /**
    * @param RouteMatchInterface $route_match
    * @return null|string
    */
   public function determineActiveTheme(RouteMatchInterface $route_match)
   {
       return $this->negotiateRoute($route_match) ?: null;
   }
 
   /**
    * Function that does all of the work in selecting a theme
    * @param RouteMatchInterface $route_match
    * @return bool|string
    */
   private function negotiateRoute(RouteMatchInterface $route_match)
   {

    $mob=array();
    $user_agent = $_SERVER["HTTP_USER_AGENT"];
    $server_name = $_SERVER['SERVER_NAME'];
    $mob = explode('m.', $server_name);
    $mob_val = isset($mob[1]) ? $mob[1] : null;
    if ($mob_val != NULL) {
      $val = 'mobile';
    }else{
      $val = 'desktop';
      // Here user agent is checked with common mobile devices.
      // You can add more devices here.
      if (stripos($user_agent, 'ipod') || stripos($user_agent, 'iphone') ||   
          stripos($user_agent, 'ipad') || stripos($user_agent, 'android') || 
          stripos($user_agent, 'opera_mini') || stripos($user_agent, 'blackberry')) {
        $val = 'mobile';
        // Here you can write code to redirect user to the mobile url. 
        // Example url is www.m.example.com.
      }
    }

    $userRolesArray = \Drupal::currentUser()->getRoles();
 
    $path = \Drupal::request()->getpathInfo();

/*
    if($_COOKIE['myCookie']=='temple' && $route_match->getRouteName() != 'admin'){
        return 'bou';

    }
*/
$skin=$_COOKIE['myCookie']??'';
//$skin=$_COOKIE['myCookie']??'';


//$skin=$_COOKIE['myCookie']??'';


$admin_context = \Drupal::service('router.admin_context');

if(in_array("administrator", $userRolesArray) && !$admin_context->isAdminRoute()){
    return 'bartick';
}

if(!in_array("administrator", $userRolesArray) && $admin_context->isAdminRoute()){
    return 'seven';
}


if(in_array("administrator", $userRolesArray)){
    return 'seven';
}

if($skin=='big_eagle' && $val=="desktop" && $route_match->getRouteName() != 'admin'){
    return 'bou_big_eagle';
}

if($skin=='' && $val=="desktop" && $route_match->getRouteName() != 'admin'){
    return 'bou';
}

if($skin=='' && $val=="mobile" && $route_match->getRouteName() != 'admin'){
    return 'bou_eagle';
}


    if($skin=='temple' && $path=='/admin/config'){
        return 'seven';

    }

    if($skin=='eagle' && $path=='/user/login'){
        return 'seven';

    }

    if($skin=='temple' && $path=='/admin'){
        return 'seven';

    }
    
    if($skin=='eagle' && $route_match->getRouteName() != 'admin'){
        return 'eagle';

    }

    if($skin=='eagle' && $route_match->getRouteName() != 'user.login'){
        return 'bou_eagle';

    }

    if($skin=='eagle' && $route_match->getRouteName() == 'user.login'){
        return 'seven';

    }

    if($skin!='eagle' && $skin!='temple' && $route_match->getRouteName() == 'admin'){
        return 'seven';

    }

    if($route_match->getRouteName() == 'user.login'){
        return 'bou';

    }
       //  dynamically change theme based on user roles.

       if ($skin=='temple' && !in_array("administrator", $userRolesArray))
       {
           return 'bou';
       }

       if ($skin=='temple' && in_array("administrator", $userRolesArray))
       {
           return 'seven';
       }
 
 
       return false;
   }
 
}
