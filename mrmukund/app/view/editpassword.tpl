		<h3 id="create_text" > <?= $user->get('f_name') ?>'s Account </h3>

		<form id="pass_change" action="<?= BASE_URL ?>/editpassword/process/userid=<?= $user->get('id') ?>" method="POST">
			
			<br>Current Password<br>
				<input id="signup_oldpass" type="password" name="oldpassword" required><br>			
				<p id="oldpass_warning" hidden>Password should be greater than or equal to 8 characters </p>
			New Password<br>
				<input id="signup_pass" type="password" name="password" required><br>			
				<p id="pass_warning" hidden>Password should be greater than or equal to 8 characters </p>
			Re-enter New Password<br>
				<input id="signup_repass" type="password" name="reenterpassword" required><br>
				<p id="repass_warning" hidden>Passwords should match </p>
			<br><br>
			<input id="create_submit" type="submit" value="Save">
			<br><br>
		</form>