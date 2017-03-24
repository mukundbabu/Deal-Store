	<div id="main_content">
		
		<div class="content_type">
			<h3 class="content_header">Recommended Categories</h3>
			<div class="content_inner">
				<?php foreach ($cat_objs as $cat): ?>
					<div class="content_item"><a class="hyper_cat" href="<?= BASE_URL ?>/deals"><img class="home_img" src="<?= BASE_URL ?>/<?= $cat->get('image_url') ?>" alt="Category"><span class="content_item-text"> <?= $cat->get('name') ?> </span></a></div>
				<?php endforeach; ?>
			</div>
			<a class="check_all" href="<?= BASE_URL ?>/categories"> Check All Categories </a>
		</div>
		
		<div class="content_type">
			<h3 class="content_header">Popular Online Deals</h3>
			<div class="content_inner">
				<?php foreach ($deal_objs_on as $deal): ?>
					<div class="content_item"><a class="hyper_cat" href="<?= BASE_URL ?>/viewdeal/dealid=<?= $deal->get('id') ?>"><img class="home_img" src="<?= BASE_URL ?>/<?= $deal->get('image_url') ?>" alt="Deal"><span class="content_item-text"> <?= $deal->get('store_name') ?> </span></a></div>
				<?php endforeach; ?>
			</div>
			<a class="check_all" href="<?= BASE_URL ?>/onlinedeals"> Check All Online Deals </a>
		</div>
		
		<div class="content_type">
			<h3 class="content_header">Popular In-Store Deals Today</h3>
			<div class="content_inner">
				<?php foreach ($deal_objs_off as $deal): ?>
					<div class="content_item"><a class="hyper_cat" href="<?= BASE_URL ?>/viewdeal/dealid=<?= $deal->get('id') ?>"><img class="home_img" src="<?= BASE_URL ?>/<?= $deal->get('image_url') ?>" alt="Deal"><span class="content_item-text"> <?= $deal->get('store_name') ?> </span></a></div>
				<?php endforeach; ?>
			</div>
			<a class="check_all" href="<?= BASE_URL ?>/offlinedeals"> Check All Offline Deals </a>
		</div>
		
		<div class="content_type">
			<h3 class="content_header">Popular Deals Today</h3>
			<div class="content_inner">
				<?php foreach ($deal_objs_all as $deal): ?>
					<div class="content_item"><a class="hyper_cat" href="<?= BASE_URL ?>/viewdeal/dealid=<?= $deal->get('id') ?>"><img class="home_img" src="<?= BASE_URL ?>/<?= $deal->get('image_url') ?>" alt="Deal"><span class="content_item-text"> <?= $deal->get('store_name') ?> </span></a></div>
				<?php endforeach; ?>
			</div>
			<a class="check_all" href="<?= BASE_URL ?>/deals"> Check All Deals </a>
		</div>
		
	</div>