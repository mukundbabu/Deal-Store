<?php
	function isNeeded($pageName) {
		if($pageName != 'All-Deals')
			return 'hidden';
	}
?>	
	
	
	
	<!-- Deals Page -->
	<div class="clothes_main_content">
		<h2 id="category_name"> <?= $pageName ?> </h2>

		<div class="clothes_remaining">
			<div id="filter_text">
				<b>Filters</b> <br><br>
				
				<div id="store_filter" <?= isNeeded($pageName) ?>>
					Store <br>
					<input type="checkbox" name="store_sort" id="store_sort_on" value="online"> Online<br>
					<input type="checkbox" name="store_sort" id="store_sort_off" value="offline"> Offline<br>
				</div>
				
				Deals <br>
				<input type="checkbox" name="deals_sort" id="deal_sort_today" value="today"> Today's<br>
				<input type="checkbox" name="deals_sort" id="deal_sort_future" value="future"> Future<br>
			</div>

			<div id="deals_list">
				<?php foreach ($deal_objs as $deal): ?>
					<div class="deal_detail">
						<img style="vertical-align:middle" class="store_img" src="<?= BASE_URL ?>/<?= $deal->get('image_url') ?>" alt="Deal""> 
						<b><?= $deal->get('deal_desc') ?></b>
						<a class="view_deal" href="<?= BASE_URL ?>/viewdeal/dealid=<?= $deal->get('id') ?>"> View Deal </a>
						<a class= "view_store" href="<?= $deal->get('store_url') ?>"> <?= $deal->get('store_name') ?> </a>
					</div>
				<?php endforeach; ?>	
				
			</div>
			
		</div>
			
	</div>