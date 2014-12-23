<?php
//require 'facebook-php-sdk-v4/autoload.php';
require_once( 'Facebook/HttpClients/FacebookHttpable.php' );
require_once( 'Facebook/HttpClients/FacebookCurl.php' );
require_once( 'Facebook/HttpClients/FacebookCurlHttpClient.php' );

require_once( 'Facebook/Entities/AccessToken.php' );
require_once( 'Facebook/Entities/SignedRequest.php' );

require_once( 'Facebook/FacebookSession.php' );
require_once( 'Facebook/FacebookRedirectLoginHelper.php' );
require_once( 'Facebook/FacebookRequest.php' );
require_once( 'Facebook/FacebookResponse.php' );
require_once( 'Facebook/FacebookSDKException.php' );
require_once( 'Facebook/FacebookRequestException.php' );
require_once( 'Facebook/FacebookPermissionException.php' );
require_once( 'Facebook/FacebookOtherException.php' );
require_once( 'Facebook/FacebookAuthorizationException.php' );
require_once( 'Facebook/GraphObject.php' );
require_once( 'Facebook/GraphSessionInfo.php' );

use Facebook\HttpClients\FacebookHttpable;
use Facebook\HttpClients\FacebookCurl;
use Facebook\HttpClients\FacebookCurlHttpClient;

use Facebook\Entities\AccessToken;
use Facebook\Entities\SignedRequest;

use Facebook\FacebookSession;
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookRequest;
use Facebook\FacebookResponse;
use Facebook\FacebookSDKException;
use Facebook\FacebookPermissionException;
use Facebook\FacebookRequestException;
use Facebook\FacebookOtherException;
use Facebook\FacebookAuthorizationException;
use Facebook\GraphObject;
use Facebook\GraphSessionInfo;

//Sanitize the string
function filterThis($string) {
    return mysql_real_escape_string($string);
}

// start session
session_start();
$api = filterThis($_GET["api"]);
$secret = filterThis($_GET["secret"]);

$message = filterThis($_GET["message"]);
$link = filterThis($_GET["link"]);
$pageId = filterThis($_GET["pageId"]);
$token = filterThis($_GET["token"]);
$redirect = filterThis($_GET["redirect"]);
$image = filterThis($_GET["image"]);
$post_with_link=filterThis($_GET["post_with_link"]);
$permissions = 'publish_actions,manage_pages,status_update';


if($message == "" OR $link =="" OR $pageId =="" OR $token =="" OR $redirect ==""){
    echo "incomplete";
    return;
}

$url_to_get = file_get_contents('https://graph.facebook.com/me/accounts?access_token='.$token);
$url_data = json_decode($url_to_get);

foreach ($url_data->data as $key => $value) {
    if($value->id== $pageId ){
        $token = filterThis($value->access_token);
    }
}


FacebookSession::setDefaultApplication($api ,$secret);
$helper = new FacebookRedirectLoginHelper($redirect);

$session = new FacebookSession($token);
$access_token = $session->getAccessToken();


if($image!=false && $post_with_link!="2" && $post_with_link!="3"  ):
    if($session) {
        try {
            $response = (new FacebookRequest(
                $session, 'POST', '/'.$pageId.'/photos', array(
                        'message'       => $message,
                        'url'          => $image
                    )
           
            ))->execute()->getGraphObject();
          //  echo "Posted with id: " . $response->getProperty('id')."<br/>";
           // echo $access_token;
            $data = ["status" => "success"];
            http_response_code(200);
            echo json_encode($response);

        } catch(FacebookRequestException $e) {
            echo "Exception occured, code: " . $e->getCode();
            echo " with message: " . $e->getMessage();
        }
    } else {
        echo "No Session available!";
    }

elseif($post_with_link==2 || $post_with_link==3 ):
    if($session) {
        try {
            $response = (new FacebookRequest(
                 $session, 'POST', '/'.$pageId.'/feed', array(
                        'link' => $link ,
                        'message'       => $message
                )
           
            ))->execute()->getGraphObject();
          //  echo "Posted with id: " . $response->getProperty('id')."<br/>";
           // echo $access_token;
            $data = ["status" => "success"];
            http_response_code(200);
            echo json_encode($response);

        } catch(FacebookRequestException $e) {
            echo "Exception occured, code: " . $e->getCode();
            echo " with message: " . $e->getMessage();
        }
    } else {
        echo "No Session available!";
    }
//with image
else:
    if( $post_with_link!=2 & $post_with_link!=3):
            if($session) {
            try {
                $response = (new FacebookRequest(
                     $session, 'POST', '/'.$pageId.'/feed', array(
                            'message'       => $message
                    )
               
                ))->execute()->getGraphObject();
              //  echo "Posted with id: " . $response->getProperty('id')."<br/>";
               // echo $access_token;
                $data = ["status" => "success"];
                http_response_code(200);
                echo json_encode($response);

            } catch(FacebookRequestException $e) {
                echo "Exception occured, code: " . $e->getCode();
                echo " with message: " . $e->getMessage();
            }
        } else {
            echo "No Session available!";
        }
    endif;
endif;





