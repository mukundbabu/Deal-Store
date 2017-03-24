<?php
	//appropriate drop down value is set.
	function rating_user($html_rating,$review_obj) {
		if ($review_obj == null)
			return;
			
		if( $review_obj->get('rating')== $html_rating) {
			return ' selected="selected" ';
		}
	}
?>	
	
	<h4 id="create_text" > Please write a review for "<?php echo $deal->get('deal_desc') ?>" </h4>
	
	<br>
	<div id = "add_review">
		<form id="review_change" action="<?= BASE_URL ?>/writereview/process/dealid=<?= $deal->get('id') ?>"" method="POST">
			<?php if ($review_obj!=null): ?>
				<p> You have already written a review for this deal. </p>
			<?php endif ?>
			<br>			
			Your Rating<br>
			<select name="rating">
				<option value="0" <?=rating_user('0', $review_obj)?> >0(Very bad)</option>
				<option value="0.5" <?=rating_user('0.5', $review_obj)?> >0.5</option>
				<option value="1" <?=rating_user('1', $review_obj)?> >1</option>
				<option value="1.5" <?=rating_user('1.5', $review_obj)?> >1.5</option>
				<option value="2" <?=rating_user('2', $review_obj)?> >2</option>
				<option value="2.5" <?=rating_user('2.5', $review_obj)?> >2.5</option>
				<option value="3" <?=rating_user('3', $review_obj)?> >3</option>
				<option value="3.5" <?=rating_user('3.5', $review_obj)?> >3.5</option>
				<option value="4" <?=rating_user('4', $review_obj)?> >4</option>
				<option value="4.5" <?=rating_user('4.5', $review_obj)?> >4.5</option>
				<option value="5" <?=rating_user('5', $review_obj)?> >5(Very good)</option>
			</select>
			<br><br>
			Review <br>
			<?php if ($review_obj!=null): ?>	
				<textarea name="review" cols="40" rows="7" required><?= $review_obj->get('review') ?></textarea>
			<?php endif ?>	
			<?php if ($review_obj==null): ?>
				<textarea name="review" cols="40" rows="7" required></textarea>
			<?php endif ?>
			
			<br><br>
			<input id="create_submit" type="submit" value="Submit">
			<br><br>
		</form>
	</div>
			