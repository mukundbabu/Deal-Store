		<h3  id="signin_text"> Sign in </h3>
		<form id="signin_form" action="<?= BASE_URL ?>/login/process" method="post">
			Email ID<br>
			<input id="signin_emailid" type="email" name="email" required><br>
			Password<br>
			<input id="signin_pass" type="password" name="password" required><br>
			<p id="pass_warning" hidden>Password should be greater than or equal to 8 characters </p>
			<br><br>
			<input id="log_in" type="submit" value="Sign in">
			<br><br>
		</form>
		
		<div id="account_no">
			New to DealStore?<br>
			<form action="<?= BASE_URL ?>/signup">
				<input id="signup_b" type="submit" value="Signup">
			</form>
		</div>
		
	</div>
</div>

</body>

</html>