			<h3 id="create_text" > My DealStore Account </h3>
	
			<form id="editAccount_form" action="<?= BASE_URL ?>/editaccount/userid=<?= $user->get('id') ?>" method="get">

					<b> First name: </b>  <?= $user->get('f_name') ?> <br><br>
					<b> Last name: </b> <?= $user->get('l_name') ?> <br><br>
					<b> Email Id: </b>  <?= $user->get('email') ?> <br><br>
					<b> Password </b> ******** <br><br>
					<b> Privileges to Add a Deal: </b> <?= $user->get('add_deal') ?> <br><br>
					<b> City: </b>  <?= $user->get('city') ?> <br><br>
					
					<br><br>
					<input id="create_submit" type="submit" value="Edit Details">
					<br><br>
					
			</form>	
		
