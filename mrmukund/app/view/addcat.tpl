	<div id="adddeal_main_content">
		<h3 id="create_text" > Add Category </h3>
		
		<form id="dealadd_form" action="<?= BASE_URL ?>/addcat/process/" method="post" enctype="multipart/form-data">
			Category Name <br>
			<input type="text" name="cat_name"  required><br>
			Select Category Image to upload. <br> 
			<input type="file" name="cat_img" id="cat_img" required> <br>
			
			<input id="add_b" type="submit" value="Add category">
			
		</form>
	</div>	
			