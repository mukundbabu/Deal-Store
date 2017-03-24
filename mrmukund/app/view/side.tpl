
<div id="side_content">

	<!-- Added new code for activity feed -->
	<?php

	if(!isset($_SESSION)){
		session_start();
	}
	if(($pageName == "Home" || $pageName == "My Account") && isset($_SESSION['user_id'])) {
		$events = $this->getEvents($pageName);
		if($events != null) {
		?>
	<div id = "activity_feed" class="activity-feed">
		<h3 class="content_header">Activity Feed</h3>
		<?php foreach($events as $event): ?>
			<?php 
				$str = printevent($event);
				if ($str==null)
					continue;
			?>
			
			<div class="feed-item">
				<div class="date"><?php printEventDate($event); ?></div>
				<div class="text"><?php echo $str; ?></div>
			</div>
		<?php endforeach; ?>
	</div>
	<?php }} ?>
	<!-- code ends -->
	<div id="stores_near_me">
		<h3 class="content_header">All Stores Near Me</h3>

		<?php
			if(!isset($_SESSION)){
				session_start();
			}

			/*Store the 'nearby stores' list in a session variable to avoid recomputing them
				 Modify the list when the Ip changes. */
			if(!isset($_SESSION['stores'])) {
				$_SESSION['stores'] = $this->getNearByStores();
				$_SESSION['ip'] = $this->getIp();

			} else if ($_SESSION['ip'] != $this->getIp()) {
				$_SESSION['stores'] = $this->getNearByStores();
				$_SESSION['ip'] = $this->getIp();
			}
		?>

		<ul id="store-list">
			<?php for ($i = 0; $i < sizeof( $_SESSION['stores']); $i++):  ?>
				<li><?= $_SESSION['stores'][$i]->{'name'} ?></li>
			<?php endfor; ?>
		</ul>
	</div>
</div>
</div>