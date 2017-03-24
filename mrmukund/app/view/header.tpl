<?php
error_reporting(E_ERROR | E_WARNING | E_PARSE | E_NOTICE);
error_reporting(E_ALL & ~E_NOTICE);
ob_start();
	//to highlight selected tab.
	function isSelected($pn, $link) {
		if($pn == $link) {
			return ' id="selected-nav" ';
		}
	}

	//to see if the user is allowed to add deals.
	function isAllowed() {
		if(!isset($_SESSION)){
			session_start();
		}

		if(isset($_SESSION['user_add'])) {
			if ($_SESSION['user_add']==0)
				return 'style="display:none;"';
		} else {
			return 'style="display:none;"';
		}
	}


?>

<?php
	//only admin can add categories.
	function isCatAllowed() {
		if(!isset($_SESSION)){
			session_start();
		}

		if(isset($_SESSION['user_id'])) {
			if ($_SESSION['user_id']!=1)
				return 'style="display:none;"';
		} else {
			return 'style="display:none;"';
		}
	}

	//MB - added function to calculate the number of items in wishlist everytime header loads.
	function calculateWishlist(){
		if(!isset($_SESSION)){
			session_start();
		}
		if(isset($_SESSION['user_id'])) {
			$userid = $_SESSION['user_id'];
			$num_rows = Wishlist::getWishlistByUser($userid);
			if($num_rows != null){
			setcookie("wishlist", $num_rows);
			return $num_rows;
			}
			else{
				setcookie("wishlist", 0);
				return 0;
			}
		}
		else{
			return 0;
		}
	}

	function copyCookies() {
		if(!isset($_SESSION))
			session_start();

		if(!isset($_SESSION['user_id'])&& isset($_COOKIE['f_name']) && isset($_COOKIE['user_id'])) {

			$_SESSION['user_id'] = $_COOKIE['user_id'];

			$user = User::loadById($_SESSION['user_id']);

			if ($user->get('add_deal')=='yes')
				$_SESSION['user_add'] = 1;
			else
				$_SESSION['user_add'] = 0;
		}
	}
?>


<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="description" content="Deal Store is a one stop place for all the deals.">
	<title>Deal Store | <?= $pageName ?></title>


	<script type="text/javascript">
		var baseURL = '<?= BASE_URL ?>';
		var pageName = '<?= $pageName ?>';
	</script>

	<script type="text/javascript" src="<?= BASE_URL ?>/public/js/jquery-3.1.0.min.js"></script>
	<script type="text/javascript" src="<?= BASE_URL ?>/public/js/jquery-ui-1.12.1.custom/jquery-ui.js"></script>

	<!Graphics>
	<?php if(strcmp($pageName,'Categories') == 0): ?>
		<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:200,600,200italic,600italic&subset=latin,vietnamese' rel='stylesheet' type='text/css'>

		<script src="http://phuonghuynh.github.io/js/bower_components/jquery/dist/jquery.min.js"></script>
		<script src="http://phuonghuynh.github.io/js/bower_components/d3/d3.min.js"></script>
		<script src="http://phuonghuynh.github.io/js/bower_components/d3-transform/src/d3-transform.js"></script>
		<script src="http://phuonghuynh.github.io/js/bower_components/cafej/src/extarray.js"></script>
		<script src="http://phuonghuynh.github.io/js/bower_components/cafej/src/misc.js"></script>
		<script src="http://phuonghuynh.github.io/js/bower_components/cafej/src/micro-observer.js"></script>
		<script src="http://phuonghuynh.github.io/js/bower_components/microplugin/src/microplugin.js"></script>
		<script src="<?= BASE_URL ?>/public/js/bubble-chart.js"></script>
		<script src="<?= BASE_URL ?>/public/js/central-click.js"></script>
		<script src="http://phuonghuynh.github.io/js/bower_components/bubble-chart/src/plugins/lines/lines.js"></script>
		<script src="<?= BASE_URL ?>/public/js/bubbleChart.js"></script>
	<?php endif; ?>

	<!https://plugins.jquery.com/cookie/>
	<script type="text/javascript" src="<?= BASE_URL ?>/public/js/carhartl-jquery-cookie-v1.4.1-0-g7f88a4e\carhartl-jquery-cookie-92b7715/jquery.cookie.js"></script>

	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/font-awesome-4.7.0/css/font-awesome.min.css">

	<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/public/css/styles.css">
	<?php if($pageName == 'View-Deal'): ?>
		<script type="text/javascript" src="<?= BASE_URL ?>/public/js/review_visualization.js"></script>
		<script type="text/javascript" src="<?= BASE_URL ?>/public/js/button.js"></script>
		<script src="http://d3js.org/d3.v3.min.js"></script>
	<?php endif; ?>
	<!-- using a new external css file for activity feed styling. code used from bootsnipp -->
	<link rel="stylesheet" type="text/css" href="<?= BASE_URL ?>/public/css/feed.css">
	<script type="text/javascript" src="<?= BASE_URL ?>/public/js/scripts.js"></script>

	<!Load this script only for deals pages>
	<?php if(strpos($pageName,'Deals') != FALSE): ?>
		<script type="text/javascript" src="<?= BASE_URL ?>/public/js/filter.js"></script>
	<?php endif; ?>
</head>

<body onload="checkCookie()">

<div id="wrapper">

	<?php copyCookies() ?>
	<div id="header">
		<div id="head_content1">
			<a id="website_name" href="<?= BASE_URL ?>/">
				<h1 > DEALSTORE </h1>
			</a>

			<form id="store_search">
				<input class="round" type="text" name="search" placeholder="Search on DEALSTORE..">
			</form>

			<form id="place_search">
				<input id = "placesearchtextbox" class="round" type="text" name="search" placeholder="Find Your Place..">
			</form>

			<button id="search_button"> Search </button>

		</div>

		<div id="head_content2">
			<?php if(!isset($_SESSION))
					session_start();
			?>

			<?php if (!isset($_SESSION['user_id'])): ?>
				<div id="pre_login" >
					<a class="loggingIn" href="<?= BASE_URL ?>/signin">Sign in</a>
					<a class="loggingIn" href="<?= BASE_URL ?>/signup">Sign up</a>
				</div>
			<?php endif ?>

			<?php if (isset($_SESSION['user_id'])): ?>
				<div id="post_login" >
					<div class="container">
						<div class="dropdown">
							<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown" id = "btn_dropdown"><h6 id="user_name"> Hi</h6>
							<span class="caret"></span></button>
							<ul class="dropdown-menu">
								<li>
									<a href="<?= BASE_URL ?>/account">View Account</a>
								</li>
								<li>
									<a  <?= isAllowed() ?> href = "<?= BASE_URL ?>/adddeal">Add Deal</a>
								</li>
								<li>
									<a  <?= isCatAllowed() ?> href = "<?= BASE_URL ?>/addcat">Add Category</a>
								</li>
								<li>
									<a <?= isCatAllowed() ?> href = "<?= BASE_URL ?>/editUser">Edit Users</a>
								</li>
								<li>
									<a id="signout_b" href = "<?= BASE_URL ?>/logout/process">Sign out</a>
								</li>
							</ul>
						</div>
					</div>
				</div>
			<?php endif ?>
		</div>

		<!--MB - added the code below for the wishlist functionality -->
		<!--extended the code to include to find people -->
		<?php if(!isset($_SESSION)){
			session_start();
		}

		if(isset($_SESSION['user_id']) && isCatAllowed() != null) {
			$num_rows = calculateWishlist();
			?>
		<div id="wishlist_div">
				<div id = "wishlist_div_1">
					<a href= "<?= BASE_URL ?>/wishlist"><img src="<?=BASE_URL?>/public/img/wishlist.png" height = 25 width = 20/><label>Wishlist</label></a>
					<p id = "wishlist_count">
						<?php if($num_rows == 0) {
						echo "No items in your wishlist";
					}
						else{
						echo $num_rows." items in your wishlist";
					}
						?>
					</p>
			</div>
			<div id = "find_people_div">
				<a href= "<?= BASE_URL ?>/people"><img src="<?=BASE_URL?>/public/img/person.png" height = 20 width = 20/><label>Find People you know</label></a>
			</div>
		</div>
		<?php } ?>


		<!-- MB new code ends -->

	</div>

	<div id="content">
		<ul id="main-nav">
			<li ><a <?= isSelected($pageName, 'Home') ?> href="<?= BASE_URL ?>/">Home</a></li>
			<li><a <?= isSelected($pageName, 'Categories') ?> href="<?= BASE_URL ?>/categories">Categories</a></li>
			<li><a <?= isSelected($pageName, 'Offline-Deals') ?> href="<?= BASE_URL ?>/offlinedeals">Offline Deals</a></li>
			<li><a <?= isSelected($pageName, 'Online-Deals') ?> href="<?= BASE_URL ?>/onlinedeals">Online Deals</a></li>
			<li><a <?= isSelected($pageName, 'All-Deals') ?> href="<?= BASE_URL ?>/deals">All Deals</a></li>
		</ul>
