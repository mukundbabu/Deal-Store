
<div >

<div id = 'review_edit_div'>
<form id="edit_review_form" action="<?= BASE_URL ?>/editreview/process/dealid=<?=$deal_obj->get('id')?>" method="POST">

		<br>
		Your Rating<br>
		<select name="rating">
			<option value="0" >0(Very bad)</option>
			<option value="0.5" >0.5</option>
			<option value="1" >1</option>
			<option value="1.5" >1.5</option>
			<option value="2" >2</option>
			<option value="2.5" >2.5</option>
			<option value="3" >3</option>
			<option value="3.5" >3.5</option>
			<option value="4" >4</option>
			<option value="4.5">4.5</option>
			<option value="5" >5(Very good)</option>
		</select>
		<br><br>
		Review <br>
			<textarea name="review" cols="40" rows="7" required></textarea>
			<input id="review_id" name="review_id" type="hidden" />
		<br><br>
		<input type="submit" value="Submit" name="submit"/>
		<input type="submit" id="delete_review" name="delete" value = "Delete" />
		<input type="button" id="cancel_edit" value="Cancel"/>
		<br><br>

	</form>
</div>
<div id = 'review_write_div'>
<form id="write_review_form" action="<?= BASE_URL ?>/writereview/process/dealid=<?=$deal_obj->get('id')?>" method="POST">

		<br>
		Your Rating<br>
		<select name="rating">
			<option value="0" >0(Very bad)</option>
			<option value="0.5" >0.5</option>
			<option value="1" >1</option>
			<option value="1.5" >1.5</option>
			<option value="2" >2</option>
			<option value="2.5" >2.5</option>
			<option value="3" >3</option>
			<option value="3.5" >3.5</option>
			<option value="4" >4</option>
			<option value="4.5">4.5</option>
			<option value="5" >5(Very good)</option>
		</select>
		<br><br>
		Review <br>
			<textarea name="review" cols="40" rows="7" required></textarea>
		<br><br>
		<input type="submit" value="Submit" name="submit"/>
		<input type="button" id="cancel_create" value="Cancel"/>
		<br><br>

	</form>
</div>
<div id = 'visualization_div'>
	<svg width = "960px" height = "450px">

	</svg>
</div>


</div>
</div>
