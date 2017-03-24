<?php
	//appropriate radio button is set.
	function type_user($html_type,$user_obj) {
		if( $user_obj->get('add_deal')== $html_type) {
			return '  checked="checked" ';
		}
	}
	
	//appropriate drop down value is set.
	function city_user($html_city,$user_obj) {
		if( $user_obj->get('city')== $html_city) {
			return ' selected="selected" ';
		}
	}
?>			
			
		<h3 id="create_text" > <?= $user->get('f_name') ?>'s Account </h3>

		<form id="signup_form" action="<?= BASE_URL ?>/editaccount/process/userid=<?= $user->get('id') ?>" method="POST">
			First name<br>
			<input type="text" name="f_name" value="<?= $user->get('f_name') ?>" required> <br>
			Last name<br>
			<input type="text" name="l_name" value="<?= $user->get('l_name') ?>" required> <br>
			Email ID<br>
			<p> <?= $user->get('email') ?> </p>
			
			<p title="This kind of a user can post different types of deals, other than viewing them"> Would like to post deals? </p>
			<div id="rad_button">
				<input type="radio" name="add_deal" <?=type_user('yes', $user)?> required value="yes"> Yes<br>
				<input type="radio" name="add_deal" <?=type_user('no', $user)?> value="no"> No<br>
			</div>
			<br>
			City <br>
			<select name="city">
				<option value="Blacksburg" <?=city_user('Blacksburg', $user)?> >Blacksburg</option>
				<option value="Newyork" <?=city_user('Newyork', $user)?> >Newyork</option>
				<option value="Richmond" <?=city_user('Richmond', $user)?> >Richmond</option>
			</select>
			<br><br>
			<input id="create_submit" type="submit" value="Save">
			<br><br>
		</form>

		<div id="account_yes">
			Do you want to change the password?<br>
			<form action="<?= BASE_URL ?>/editpassword/userid=<?= $user->get('id') ?>" method="get">
				<input id="change_pass" type="submit" value="Change Password" >
			</form>
		</div>