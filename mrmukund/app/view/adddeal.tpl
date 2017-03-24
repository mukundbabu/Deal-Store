	<div id="adddeal_main_content">
			<h3 id="create_text" > Add a Deal </h3>
			<form id="dealadd_form" action="<?= BASE_URL ?>/adddeal/process" method="post" enctype="multipart/form-data">
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
				Select Deal Image to upload. <br> 
				<input type="file" name="deal_img" id="deal_img" required> <br>
				<br><br>
				<input id="add_b" type="submit" value="Add Deal">
				<br><br>
		    </form>
		
	</div>