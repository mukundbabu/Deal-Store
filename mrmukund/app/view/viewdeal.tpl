<?php

	//Admin can modify all the deals.
	//Other users can only modify deals they have added.
	function isModDealAllowed($deal_obj) {
		if(!isset($_SESSION)){
			session_start();
		}

		if(isset($_SESSION['user_id'])) {
			if ($_SESSION['user_id']!=($deal_obj->get('added_by')) && ($_SESSION['user_id']!=1))
				return 'hidden';
		} else {
			return 'hidden';
		}
	}

	function isLoggedin() {
		if(!isset($_SESSION)){
			session_start();
		}

		if(isset($_SESSION['user_id'])) {
		} else {
			return 'hidden';
		}
	
	}
?>


	<div id="main_content">
		<h3 > <?= $deal_obj->get('category') ?> -> <?= $deal_obj->get('type') ?> </h3>
		<div id="deal_descContainer">
			<div id="category_name">    <?= $deal_obj->get('deal_desc') ?> </div>
			<div class="star_rating"> <?php printStars($star) ?> <?php echo "$num_rating Rating(s)" ?> </div>
			
		</div>	
		

		<img style="vertical-align:middle" id="store_view_img" src="<?= BASE_URL ?><?= $deal_obj->get('image_url') ?>" alt="deal"> <br><br>

		<div id = "deal_info">
			<div id = "store_name"> Store: <a href="<?= $deal_obj->get('store_url') ?>">   <?= $deal_obj->get('store_name') ?> </a> <br>
			</div>
			Startdate: <?= $deal_obj->get('start_date') ?> <br>
			Enddate: <?= $deal_obj->get('end_date') ?> <br>
			<!--MB - Add the lines below for the add to wishlist functionality -->
			<input id = "deal_id" type="hidden" value="<?= $deal_obj->get('id') ?>"/>
			<button class = "listButton" <?= isLoggedin() ?> > Add to List </button>
			<br/>
			<div id = "addResult"></div>
		</div>

		<br><br>
		<div id="my_reviews">
			<div id = "review_info">
				<div id="review_name"> Customer Reviews </div> <br>
				<?php if ($num_rating==0): ?>
					<div class="review_text"> No Reviews are available. </div>
				<?php endif ?>
				
				<?php if ($num_rating!=0): ?>
					<?php foreach ($reviews as $rev_obj): ?>
						
						<div class="star_rating"> <?php printStars($rev_obj->get('rating')) ?> </div>
						<br>
						<div class="review_text"> <?php echo $rev_obj->get('review') ?> </div>
						<br>
						<div class="posted_by"> 
							<?php 
								$id = $rev_obj->get('user_id');
								$user_obj = User::loadById($id);
								$f_name = $user_obj->get('f_name');
								echo " $f_name .";
								$mydate = strtotime($rev_obj->get('added_time'));
								echo date('F jS Y', $mydate); 
							?>		
						</div>
						<br>
					<?php endforeach; ?>
				<?php endif ?>
				<br>
			
			</div>
				
			<div id="write_review" <?= isLoggedin() ?> >
				<form action="<?= BASE_URL ?>/writereview/dealid=<?= $deal_obj->get('id') ?>">
					<input type="submit" value="Write a Review">
				</form>
			</div>
		</div>
		<br>
		<div id = "deal_mod"  >
			<div id="edit_deal_div"  <?= isModDealAllowed($deal_obj) ?> >
				<form action="<?= BASE_URL ?>/editdeal/dealid=<?= $deal_obj->get('id') ?>" method="get">
					<input id = "edit_deal" input type="submit" value="Edit Deal">
				</form>
			</div>

			<div id="delete_deal_div" <?= isModDealAllowed($deal_obj) ?> >
				<form  id="del_deal_form" action="<?= BASE_URL ?>/deletedeal/dealid=<?= $deal_obj->get('id') ?>" method="get">
					<input id="delete_deal" type="submit" value="Delete Deal" >
				</form>
			</div>

		</div>

	</div>
