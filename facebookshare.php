<?php
	/*
	Plugin Name: Facebookshare - Share posts to Facebook page automatically
	Version: 1.0
	Plugin URI: http://www.g-works.fi
	Description: Auto post to facebook any new posts - By Truc Truong 
	Author: Truc Truong :: G-Works Oy
	Author URI: http://www.g-works.fi
	*/


add_action('publish_post','fbshare_post_facebook',10,2);
add_action( 'admin_menu', 'register_my_custom_menu_page' );

	function register_my_custom_menu_page(){
	    add_menu_page( 'Facebookshare', 'FacebookShare', 'manage_options', 'facebookshare/fb-admin.php', '', plugins_url( 'facebookshare/images/facebookshare.png' ), 75 );	  
	}

add_action( 'publish_post', 'fbshare_post_facebook',10, 2);

function fbshare_post_facebook($ID,$post){
     

	// Load FB API
	require_once ('facebook-php-sdk/src/facebook.php');
	require_once ('facebook-php-sdk/autoload.php');
	require_once( 'Facebook/FacebookSession.php' );

	// Set UTC time for Feed
	date_default_timezone_set('UTC');


	// My facebook api
	$api = get_option('fbshare_appID');
	$secret = get_option('fbshare_appSecret');
	$pageId = get_option('fbshare_pageID');
	$token = get_option('fbshare_token');
	 

	$title = get_the_title($post);
   
	$link = get_permalink($ID);

    $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $ID ), 'large' );
  
    if($thumbnail==false){
        
            $image = false;
    }else{
        $image = $thumbnail[0];
    }

    // $meta_values = get_post_meta($ID);
    // if($meta_values['post_to_facebook'][0]==1){

    $api_url= plugins_url().'/facebookshare/fb-cron.php';
    //Check if posts are newly created or not
    //Share all posts ==0
    if(get_option("fb_show_type")==0|| get_option("fb_show_type")==2){
        $post_with_link = get_option("fb_show_type");
        //Get the content
        if(get_option("fb_show_type")==0){
                $title.="
                ";
              $title .= strip_tags(gw_filter_content(get_post_field('post_content', $ID)));
                $title = preg_replace("/&#?[a-z0-9]+;/i","",$title);
        }
        // Call the fb-cron.php with POST method including some parameters
        $post_to_facebook = share_to_facebook($title,$link,$api,$secret,$token,$pageId,$api_url,$image,$post_with_link);
        
    }elseif (get_option("fb_show_type")==1 || get_option("fb_show_type")==3) {
         $post_with_link = get_option("fb_show_type");
         //Get the content
        if(get_option("fb_show_type")==1){
                $title.="
                ";
              $title .= strip_tags(gw_filter_content(get_post_field('post_content', $ID)));
                $title = preg_replace("/&#?[a-z0-9]+;/i","",$title);
        }
       if($post->post_modified_gmt ==$post->post_date_gmt ){
            $post_to_facebook = share_to_facebook($title,$link,$api,$secret,$token,$pageId,$api_url,$image,$post_with_link);
       }
    }  
	//dd($post_to_facebook);
}
function gw_filter_content($content){
    $content = apply_filters('the_content', $content);
    $content = str_replace(']]>', ']]&gt;', $content);
    return $content;
}




/******** 12-12-2014 *********
 * Post to facebook function
 * @param  some nesscessary infomation to post to fb 
 * @return  JSON failed or success
 * Ask TRUC if you need help with this code
 *******************************/
function share_to_facebook($title,$link,$api,$secret,$token,$pageId,$api_url,$image,$post_with_link){

     /* Script URL */
    $url = $api_url;

    /* $_GET Parameters to Send */
    $params = array(
            "message" => $title,
            "link" => $link,
            "api" => $api,
            "token" => $token,
            "pageId" => $pageId,
            "secret" => $secret,
            "redirect" =>$api_url,
            "image" => $image,
            "post_with_link"=>$post_with_link
     );

    /* Update URL to container Query String of Paramaters */
    $url .= '?' . http_build_query($params);

    /* cURL Resource */
    $ch = curl_init();

    /* Set URL */
    curl_setopt($ch, CURLOPT_URL, $url);

    /* Tell cURL to return the output */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    /* Tell cURL NOT to return the headers */
    curl_setopt($ch, CURLOPT_HEADER, false);

    /* Execute cURL, Return Data */
    $data = curl_exec($ch);

    /* Check HTTP Code */
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    /* Close cURL Resource */
    curl_close($ch);

    /* 200 Response! */
    if ($status == 200) {

        /* Debug */
       $data =["status"=>"success"];

    } else {

        /* Debug */
       $data =["status"=>"failed"];

    }
    return json_encode($data);

}

function get_facebook_token($client_id,$client_secret,$code,$redirect_uri){

     /* Script URL */
    $url = 'https://graph.facebook.com/oauth/access_token';

    /* $_GET Parameters to Send */
    $params = array(
            "client_id" => $client_id,
            "client_secret" => $client_secret,
            "code" => $code,
            "redirect_uri" => $redirect_uri

     );

    /* Update URL to container Query String of Paramaters */
    $url .= '?' . http_build_query($params);

    /* cURL Resource */
    $ch = curl_init();

    /* Set URL */
    curl_setopt($ch, CURLOPT_URL, $url);

    /* Tell cURL to return the output */
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    /* Tell cURL NOT to return the headers */
    curl_setopt($ch, CURLOPT_HEADER, false);

    /* Execute cURL, Return Data */
    $data = curl_exec($ch);

    /* Check HTTP Code */
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    /* Close cURL Resource */
    curl_close($ch);

  //  var_dump($params);
    return ($data);

}

