			<h3 id="create_text" > Create a DealStore Account </h3>
	
			<form id="signup_form" action="<?= BASE_URL ?>/signup/create" method="POST">
				First name<br>
				<input type="text" name="f_name" required> <br>
				Last name<br>
				<input type="text" name="l_name" required> <br>
				Email ID<br>
				<input id ="signup_emailid" type="email" name="email" required><br>
				Password<br>
				<input id="signup_pass" type="password" name="password" required><br>			
				<p id="pass_warning" hidden>Password should be greater than or equal to 8 characters </p>
				Re-enter Password<br>
				<input id="signup_repass" type="password" name="reenterpassword" required><br>
				<p id="repass_warning" hidden>Passwords should match </p>
				<p title="This kind of a user can post different types of deals, other than viewing them"> Would like to post deals? </p>
				<div id="rad_button">
					<input type="radio" name="add_deal" required value="yes"> Yes<br>
					<input type="radio" name="add_deal" value="no"> No<br>
				</div>
				<br>
				City <br>
				<select name="city">
					<option value="Blacksburg">Blacksburg</option>
					<option value="NewYork">NewYork</option>
					<option value="Richmond">Richmond</option>
				</select>
				<br><br>
				<input id="create_submit" type="submit" value="Create">
				<br><br>
			</form>	
			
			<div id="account_yes">
				Already have a DealStore account?<br>
				<form action="<?= BASE_URL ?>/signin">
					<input id="signin_b" type="submit" value="Signin">
				</form>
			</div>
		
	</div>
</div>

</body>

</html>