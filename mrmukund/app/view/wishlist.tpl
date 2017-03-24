

	<!-- Deals Page -->
	<div class="clothes_main_content">
		<h2 id="category_name"> <?= $pageName ?> </h2>

		<div class="clothes_remaining">

			<div id="wishlist_list">
				<div id = "addResult"></div>
				<?php
				if($result != null){
				while($row = mysql_fetch_assoc($result)):	?>
					<div class="wishlist_detail">

						<img style="vertical-align:middle" class="store_img" src="<?= BASE_URL ?>/<?= $row['image_url'] ?>" alt="Deal"">
						<b><?= $row['deal_desc'] ?></b>
						<a class="view_deal" href="<?= BASE_URL ?>/viewdeal/dealid=<?= $row['id'] ?>"> View Deal </a>
						<a class= "view_store" href="<?= $row['store_url'] ?>"> <?= $row['store_name'] ?> </a>
						<br><br>
						<input id = "deal_id<?= $row['id'] ?>" type="hidden" value="<?= $row['id'] ?>"/>
						<div class="wishlist_actions">
							<a href="<?= BASE_URL ?>/removeWishlist/dealid=<?= $row['id'] ?>"><button> Remove from List </button></a>
							<!-- added new code below to add the suggest to friend functionality -->
							<div class="wishlist_suggest"> <label>Suggest to a friend: </label>
								<form method="POST" action="suggestFriend">
								<select name="friend_name" >
										<?php if($friend != null) {
											while($eachrow = mysql_fetch_assoc($friend)): ?>
											<option> <?= $eachrow['l_name'].','.$eachrow['f_name'] ?> </option>
										<?php endwhile;
											mysql_data_seek($friend, 0);
								} ?>
								</select>
								<input type="hidden" name = "dealid" value="<?= $row['id'] ?>"/>
								<input type="Submit" value="Suggest!"/>
							</form>
							</div>
							<!--code ends -->
						</div>
					</div>
				<?php endwhile; }
				else
				echo "<p>No items in wishlist to display</p>";?>

			</div>

		</div>

	</div>
