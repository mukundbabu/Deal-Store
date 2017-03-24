<?php
	//appropriate drop down value is set.
	function cat_deal($html_cat,$deal_obj) {
		if( $deal_obj->get('category')== $html_cat) {
			return ' selected="selected" ';
		}
	}
?>	

<?php
	//appropriate radio button is set.
	function type_deal($html_type,$deal_obj) {
		if( $deal_obj->get('type')== $html_type) {
			return '  checked="checked" ';
		}
	}
?>		

<?php
	//date from database is set in the date field.
	function date_deal($datestr) {
		$date = date_create($datestr);
		echo date_format($date,"m/d/Y");	
	}
?>

	
	<div id="adddeal_main_content">
			<h3 id="create_text" > Edit Deal </h3>
			<form id="dealadd_form" action="<?= BASE_URL ?>/editdeal/process/dealid=<?= $deal_obj->get('id') ?>" method="post" enctype="multipart/form-data">

				Store name<br>
				<input type="text" name="store_name" value="<?= $deal_obj->get('store_name') ?> " required><br>

				Category <br>
				<select name="category">
					<?php foreach ($cat_objs as $cat): ?>
						<option value="<?= $cat->get('name')?>" <?=cat_deal($cat->get('name'), $deal_obj)?> > <?= $cat->get('name')?> </option>
					<?php endforeach; ?>	
				</select>
				<br>

				Type <br>
				<div id="rad_button">
					<input  type="radio" name="type" value="online" <?=type_deal('online', $deal_obj)?> required> Online<br>
					<input type="radio" name="type" value="offline" <?=type_deal('offline', $deal_obj)?>> Offline<br>
				</div>
				<br>

				Start Date <br>
				<input id="start_date" type="date" name="start_date" value="<?php 
				echo date('Y-m-d',strtotime($deal_obj->get('start_date'))); ?>" required><br>
	
				End Date <br>
				<input id="end_date" type="date" name="end_date"  value="<?php 
				echo date('Y-m-d',strtotime($deal_obj->get('end_date'))); ?>" required><br>

				Deal Description <br>
				<textarea name="deal_desc" cols="40" rows="7" required><?= $deal_obj->get('deal_desc') ?></textarea>

				Store URL <br>
				<input type="url" name="store_url" value="<?= $deal_obj->get('store_url') ?> " required><br>

				Select Deal Image to upload. <br> 
				<input type="file" name="deal_img" id="deal_img" > <br>

				Current Deal Image path is: "<?= $deal_obj->get('image_url') ?>" <br>
				<br><br>
				<input id="add_b" type="submit" value="Save Deal">
				<br><br>
		    </form>
	</div>