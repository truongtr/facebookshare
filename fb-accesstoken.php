<?php
require_once('../../../wp-load.php') ;


$site = array(
		
		'pageid' => get_option('fbshare_pageID'),
		'appid' => get_option('fbshare_appID'),
		'appsecret' => get_option('fbshare_appSecret'),

	);


if($_GET['code']){

	$code = $_GET['code'];

	$returnUrl = plugins_url().'/facebookshare/fb-accesstoken.php';
	$url = ('https://graph.facebook.com/oauth/access_token?client_id='.$site['appid'].'&redirect_uri='.$returnUrl.'&client_secret='.$site['appsecret'].'&code='.$code);

	$response = file_get_contents($url);
	parse_str($response,$params);


	$access_token = $params['access_token'];
	$expires = $params['expires'] + time();
	if($access_token && $expires){
		update_option( 'fbshare_token', $access_token);
		update_option('fbshare_token_expire', $expires );
	}
	//echo 'got '.$site['pageid'];
	header("location:".home_url()."/wp-admin/admin.php?page=facebookshare/fb-admin.php");
	//dd(get_option('fb_access_token'));

}else{

		$site['access_token'] = get_option('fbshare_token_token');
		$site['expires'] = get_option('fbshare_token_expire');
		if( !$site['access_token'] || time() > $site['expires'] || $site['access_token'] == '' ){ 

			$returnUrl = plugins_url().'/facebookshare/fb-accesstoken.php';
			$url = "https://www.facebook.com/dialog/oauth?client_id=".$site['appid']."&redirect_uri=".$returnUrl."&scope=manage_pages,publish_stream,publish_actions";

			wp_redirect($url);
			die();
		}

	echo 'got all <br/>';
}
