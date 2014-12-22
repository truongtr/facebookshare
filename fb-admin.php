
<?php

$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
if($_GET["code"]!="" && strlen($_GET["code"])>10){
	//update_option('fbshare_token',$_GET["code"]);
	$code = $_GET["code"];
	$returnUrl = plugins_url().'/facebookshare/fb-accesstoken.php';
	$pieces = explode('&code=', $actual_link);
	$redirect_uri = $pieces[0];
	$client_id =get_option('fbshare_appID');
	$client_secret =get_option('fbshare_appSecret');
	$response = get_facebook_token($client_id,$client_secret,$code,$redirect_uri);
	parse_str($response,$params);
	$access_token = $params['access_token'];
	$expires = $params['expires'] + time();
	if($access_token && $expires){
		update_option( 'fbshare_token', $access_token);
		update_option('fbshare_token_expire', $expires );
	}
}

$url = "https://www.facebook.com/dialog/oauth?client_id=".get_option('fbshare_appID')."&redirect_uri=".$actual_link."&scope=manage_pages,publish_stream,publish_actions";

 ?>
<?php screen_icon('themes'); ?>
<div id="wrap">
<h2> <img src="<?= plugins_url( 'facebookshare/images/facebookshare_larger.png' );?>" alt="Facebookshare logo"/> Welcome to Facebookshare Panel</h2><br />



 <?php

 if(isset($_POST['submit'])):

 	update_option('fbshare_pageID',$_POST['fbshare_pageID']);
	update_option('fbshare_appID',$_POST['fbshare_appID']);
	update_option('fbshare_appSecret',$_POST['fbshare_appSecret']);
	update_option('fbshare_token',$_POST['fbshare_token']);
	update_option('fb_show_type',$_POST['fb_show_type']);

	echo '<div id="setting-error-settings_updated" class="updated settings-error"> 
<p><strong>Settings saved!</strong></p></div>';
	require_once("form.php");


$site = array(
		
		'pageid' => get_option('fbshare_pageID'),
		'appid' => get_option('fbshare_appID'), 
		'appsecret' => get_option('fbshare_appSecret')

	);
else:
	require_once("form.php");
endif;
function show_value($string){
	 if($_SERVER['REQUEST_METHOD'] == 'POST'):
	 	return get_option($string);
	else:
		return get_option($string);
	endif;
}

?>
</div>