    
	
	<h2 id="category_name">Categories</h2>
	<div class="bubbleChart">
	</div>
	
	<!-- Add deal form-->
	<div id = "viz_deal_add">
		<div id="adddeal_main_content">
			<h3 id="create_text" > Add a Deal </h3>
			<form id="vdealadd_form" action="<?= BASE_URL ?>/adddeal/process" method="post" enctype="multipart/form-data">
				Store name<br>
				<input type="text" name="store_name" required><br>
				Category <br>
				<select name="category">
					<?php foreach ($cat_objs as $cat): ?>
						<option value="<?= $cat->get('name')?>"><?= $cat->get('name')?></option>
					<?php endforeach; ?>
				</select>
				<br>
				Type <br>
				<div id="rad_button">
					<input  type="radio" name="type" value="online" required> Online<br>
					<input type="radio" name="type" value="offline"> Offline<br>
				</div>
				<br>
				Start Date <br>
				<input id="start_date" type="date" name="start_date" required><br>
				End Date <br>
				<input id="end_date" type="date" name="end_date" required><br>
				Deal Description <br>
				<textarea name="deal_desc" cols="40" rows="7" required></textarea>
				Store URL <br>
				<input type="url" name="store_url" required><br>
				
				<br><br>
				<input id="add_b" type="submit" value="Add Deal">
				<button class="cancel_b" type="button" name="cancel">Cancel</button>
				<br><br>
				
			</form>
		
		</div>
	</div>
	
	<!-- Edit deal form-->
	<div id = "viz_deal_edit">
		<div id="adddeal_main_content">
			<h3 id="create_text" > Edit a Deal </h3>
			<form id="vdealedit_form" action="<?= BASE_URL ?>/editdeal/process" method="post" enctype="multipart/form-data">
				Store name<br>
				<input type="text" name="store_name1" required><br>
				Category <br>
				<select name="category1">
					<?php foreach ($cat_objs as $cat): ?>
						<option value="<?= $cat->get('name')?>"><?= $cat->get('name')?></option>
					<?php endforeach; ?>
				</select>
				<br>
				Type <br>
				<div id="rad_button1">
					<input  type="radio" name="type1" value="online" required> Online<br>
					<input type="radio" name="type1" value="offline"> Offline<br>
				</div>
				<br>
				Start Date <br>
				<input id="start_date1" type="date" name="start_date1" required><br>
				End Date <br>
				<input id="end_date1" type="date" name="end_date1" required><br>
				Deal Description <br>
				<textarea name="deal_desc1" cols="40" rows="7" required></textarea>
				Store URL <br>
				<input type="url" name="store_url1" required><br>
				
				<input id="add_b" type="submit" value="Edit Deal">
				<input type="hidden" name="dealid">
			</form>
				
				<input id="vdel_deal" type="submit" value="Delete Deal" > <br>
				<button class="cancel_b" type="button" name="cancel">Cancel</button>
		</div>
	</div>
	
	</div>