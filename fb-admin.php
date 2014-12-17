<?php screen_icon('themes'); ?>
<h2> <img src="<?= plugins_url( 'facebookshare/images/facebookshare_larger.png' );?>" alt="Facebookshare logo"/> Welcome to Facebookshare Panel</h2><br />
<div id="fb-root"></div>
<form enctype="multipart/form-data" method="POST" id="fbshare_form"action="">
	<input type="hidden" name="update_settings" value="Y" />

	<table class="form-table">
	<h1> Basic Configurations </h1>
		<tr>
			<th>Facebook Fanpage ID</th>
			<td><input style="width: 400px;" type="text" name="fbshare_pageID" id="fbshare_pageID" value="<?= get_option('fbshare_pageID'); ?>" /></td>
		</tr>

		<tr>
			<th>Facebook App ID</th>
			<td><input style="width: 400px;" type="text" name="fbshare_appID" id="fbshare_appID" value="<?= get_option('fbshare_appID'); ?>" /></td>
		</tr>
		<tr>
			<th>Facebook App Secret</th>
			<td><input style="width: 400px;" type="text" name="fbshare_appSecret" id="fbshare_appSecret" value="<?= get_option('fbshare_appSecret'); ?>" /></td>
		</tr>
		<tr>
			<th>Facebook App Token <a href="<?= plugins_url( "fb-accesstoken.php")?>">here</a></th>
			<td><input style="width: 400px;" type="text" name="fbshare_token" id="fbshare_token" value="<?= get_option('fbshare_token'); ?>" /></td>
		</tr>
		<tr>
			<td colspan="2"><p>Notice: You can get App Token correctly only after saving other information like App ID, etc.</p></td>
		</tr>
		<tr>
			<th>Post sharing type:</th>
		
		
			<td><fieldset><legend class="screen-reader-text"><span>Päivämäärän oletusmuoto</span></legend>
					<label>
						<input id="fb_show_type" type="radio" name="fb_show_type" value="0"  <?php if(get_option("fb_show_type")  == 0) echo 'checked="checked"'; ?>/>
						<span>Share all posts</span>
					</label>
					<br/>
	
					<label>
						<input id="fb_show_type" type="radio" name="fb_show_type" value="1" <?php if(get_option("fb_show_type") == 1) echo 'checked="checked"'; ?>/>
						<span>Only share new posts</span>
					</label>
					<br/>

					<label>
						<input id="fb_show_type" type="radio" name="fb_show_type" value="2" <?php if(get_option("fb_show_type") == 2) echo 'checked="checked"'; ?>/>
						<span> Share all posts under links</span>
					</label>
					<br/>

					<label>
						<input id="fb_show_type" type="radio" name="fb_show_type" value="3" <?php if(get_option("fb_show_type") == 3) echo 'checked="checked"'; ?>/>
						<span> Only share new posts and under links</span>
					</label>
					<br/>
				</fieldset>
			</td>
			
		
		</tr>


	</table>


	<?php /* Show errors here */ ?>
	<?php if( isset( $error ) ): ?>
		<p class="error" style="color:red;font-weight:bold;"><?php echo $error; ?></p>
	<?php endif; ?>

	<p><input type="submit" name="submit" value="Save your options" class="button-primary"/></p>
</form>

 <?php

 if($_SERVER['REQUEST_METHOD'] == 'POST'):

 	update_option('fbshare_pageID',$_POST['fbshare_pageID']);
	update_option('fbshare_appID',$_POST['fbshare_appID']);
	update_option('fbshare_appSecret',$_POST['fbshare_appSecret']);
	update_option('fbshare_token',$_POST['fbshare_token']);
	update_option('fb_show_type',$_POST['fb_show_type']);
	echo "<p> Saved! </p>";
	echo "<script>location.reload();</script>";


$site = array(
		
		'pageid' => get_option('fbshare_pageID'),
		'appid' => get_option('fbshare_appID'), 
		'appsecret' => get_option('fbshare_appSecret')

	);
add_action('init', 'do_output_buffer');
function do_output_buffer() {
        ob_start();
}
$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
$returnUrl = $actual_link;
	if($_GET['code']){

		$code = $_GET['code'];

		$url = ('https://graph.facebook.com/oauth/access_token?client_id='.$site['appid'].'&redirect_uri='.$returnUrl.'&client_secret='.$site['appsecret'].'&code='.$code);

		$response = file_get_contents($url);
		parse_str($response,$params);


		$access_token = $params['access_token'];
		$expires = $params['expires'] + time();
		if($access_token && $expires){
			update_option( 'fbshare_token', $access_token);
			update_option('fbshare_token_expire', $expires );
		}
		echo 'got '.$site['pageid'];
		//dd(get_option('fbshare_token'));

	}else{

			$site['access_token'] = get_option('fbshare_token');
			$site['expires'] = get_option('fbshare_token_expire');
			if( !$site['access_token'] || time() > $site['expires'] || $site['access_token'] == '' ){ 

				$actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
				$returnUrl = $actual_link;
				$url = "https://www.facebook.com/dialog/oauth?client_id=".$site['appid']."&redirect_uri=".$returnUrl."&scope=manage_pages,publish_stream,publish_actions";
				//ob_start();
				//session_start();
				//header("Location: ".$url);
				//wp_redirect($url);
				die();
			}

		echo '<h1>got all </h1><br/>';
	}

 endif;