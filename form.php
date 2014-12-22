<form  method="POST" id="fbshare_form" action="<?= $actual_link?>">
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
			<th>Facebook App Secret </th>
			<td><input style="width: 400px;" type="text" name="fbshare_appSecret" id="fbshare_appSecret" value="<?= get_option('fbshare_appSecret'); ?>" /></td>
		</tr>
		<tr>
			<th>Facebook App Token <a href="<?= $url?>">here</a></th>
			<td><input style="width: 400px;" type="text" name="fbshare_token" id="fbshare_token" value="<?= get_option('fbshare_token'); ?>" /></td>
		</tr>
		<tr>
			<td colspan="2"><p>Notice: You can get App Token correctly only after saving other information like App ID, etc.</p></td>
		</tr>
		<tr>
			<th>Post sharing type </th>
		
		
			<td><fieldset><legend class="screen-reader-text"><span>Päivämäärän oletusmuoto</span></legend>
					<label>
						<input id="fb_show_type" type="radio" name="fb_show_type" value="0"  <?php if(show_value("fb_show_type")  == 0) echo 'checked="checked"'; ?>/>
						<span>Share all posts</span>
					</label>
					<br/>
	
					<label>
						<input id="fb_show_type" type="radio" name="fb_show_type" value="1" <?php if(show_value("fb_show_type") == 1) echo 'checked="checked"'; ?>/>
						<span>Only share new posts</span>
					</label>
					<br/>

					<label>
						<input id="fb_show_type" type="radio" name="fb_show_type" value="2" <?php if(show_value("fb_show_type") == 2) echo 'checked="checked"'; ?>/>
						<span> Share all posts under links</span>
					</label>
					<br/>

					<label>
						<input id="fb_show_type" type="radio" name="fb_show_type" value="3" <?php if(show_value("fb_show_type") == 3) echo 'checked="checked"'; ?>/></a>
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
	
	<input type="submit" name="submit" id="submit"   class="button button-primary" value="Save your options" class="button-primary"/>

</form>