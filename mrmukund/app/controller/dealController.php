<?php

include_once '../global.php';
//include_once 'commonController.php';

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a dealController and route it
$pc = new dealController();
$pc->route($action);

//This contains all the functions for Deal Navigations
class dealController {

	// route us to the appropriate class method for this action
	public function route($action) {
		switch($action) {

			case 'deals':
				$this->deals();
				break;

			case 'offlineDeals':
				$this->offlineDeals();
				break;

			case 'onlineDeals':
				$this->onlineDeals();
				break;

			case 'addDeal':
				$this->addDeal();
				break;

			case 'viewDeal':
				$this->viewDeal();
				break;

			case 'deleteDeal':
				$this->deleteDeal();
				break;

			case 'editDeal' :
				$this->editDeal();
				break;

			case 'addDealProcess':
				$this->addDealProcess();
				break;

			case 'editDealProcess':
				$this->editDealProcess();
				break;

			case 'filterDeals':
				$deal_type = $_GET['type'];
				$time_type = $_GET['time'];
				$this->filterDeals($deal_type,$time_type);
				break;

			case 'addWishlist':
				$this->addWishlist();
				break;

			case 'wishlist':
				$this->wishlist();
				break;

			case 'removeWishlist':
				$this->removeWishlist();
				break;

			case 'writeReview':
				$this->writeReview();
				break;

			case 'writeReviewProcess':
				$this->writeReviewProcess();
				break;

			case 'suggestFriend':
				$this->suggestFriend();
				break;

			case 'dealData':
				$this->dealData();
				break;

			case 'vdealaddProcess':
				$this->vdealaddProcess();
				break;

			case 'vdealeditProcess':
				$this->vdealeditProcess();
				break;

			case 'vdealdelProcess':
				$this->vdealdelProcess();
				break;

			case 'getReviews':
				$this->getReviews();
				break;

			case 'editReview':
				$this->editReview();
				break;

			// redirect to home page if everything else fails
			default:
				header('Location: '.BASE_URL);
				exit();
		}
	}

	// View all types of deals
	public function deals() {
		$pageName = 'All-Deals';
		$deal_objs = Deal::getAllDeals(100);
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/deals.tpl';
		include_once SYSTEM_PATH.'/view/side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	//View all offline deals
	public function offlineDeals() {
		$pageName = 'Offline-Deals';
		$deal_objs = Deal::getAllTypeDeals(100,"offline");
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/deals.tpl';
		include_once SYSTEM_PATH.'/view/side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	//View all online deals
	public function onlineDeals() {
		$pageName = 'Online-Deals';
		$deal_objs = Deal::getAllTypeDeals(100,"online");
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/deals.tpl';
		include_once SYSTEM_PATH.'/view/side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	//Filter Deals based on filters on deals pages.
	public function filterDeals($deal_type,$time_type) {
		if ($deal_type == null)
			$deal_objs = Deal::getAllDeals(100,$deal_type);
		else
			$deal_objs = Deal::getAllTypeDeals(100,$deal_type);

		$i = 0;
		foreach ($deal_objs as $deal) {
			if ($time_type != null) {
				$today = date("Y-m-d");
				$deal_edate = $deal->get('end_date');

				if ($time_type == "today") {
					if ($deal_edate != $today)
						continue;
				} else {
					if ($deal_edate < $today)
						continue;
				}
			}

			$deal_json[$i] = array(
							'image_url' => $deal->get('image_url'),
							'deal_desc' => $deal->get('deal_desc'),
							'id' => $deal->get('id'),
							'store_url' => $deal->get('store_url'),
							'store_name' => $deal->get('store_name')
						);
			$i++;
		}

		header('Content-Type: application/json');
		echo json_encode($deal_json);
	}

	//To add a deal
	public function addDeal() {
		$pageName = 'Add-Deal';
		//To fill category-drop down in deal form
		$cat_objs = Categories::getAllCategories();

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/adddeal.tpl';
		include_once SYSTEM_PATH.'/view/side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	//To view a deal
	public function viewDeal() {
		$pageName = 'View-Deal';
		$deal_obj = Deal::loadById($_GET['dealid']);
		//$rating = Review::avgByDealId($_GET['dealid']);

		$reviews =Review::loadByDealId($_GET['dealid']);

		$star = 0;
		$num_rating = 0;

		if ($reviews!= null) {
			foreach ($reviews as $rev_obj) {
				$star+=$rev_obj->get('rating');
				$num_rating++;
			}

			$star = $star/$num_rating;
		}

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/viewdeal.tpl';
		include_once SYSTEM_PATH.'/view/viewDeal_side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	public function writeReview() {
		$pageName = 'Write-Review';

		if(!isset($_SESSION)){
			session_start();
		}

		$deal = Deal::loadById($_GET['dealid']);

		$review_obj = Review::loadByDealUserId($_GET['dealid'],$_SESSION['user_id']);

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/writereview.tpl';
		include_once SYSTEM_PATH.'/view/side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';

	}

	public function writeReviewProcess() {
		if(!isset($_SESSION)){
			session_start();
		}

		$review_obj = Review::loadByDealUserId($_GET['dealid'],$_SESSION['user_id']);

		$review = new Review();

		$review->set('user_id',$_SESSION['user_id']);
		$review->set('deal_id',$_GET['dealid']);
		$review->set('rating',$_POST['rating']);
		$review->set('review',$this->r_whitespaces($_POST['review']));

		//Make an entry into the events table
		$event = new Event();
		$event->set('user_1',$_SESSION['user_id']);
		$event->set('deal_1',$_GET['dealid']);
		$event_id = EventType::getIdByName("Follower/Myself  Reviewed a deal");

		if ($review_obj) {
			$review->set('id',$review_obj->get('id'));

			if ($this->r_whitespaces($review_obj->get('review'))!=$this->r_whitespaces($_POST['review'])) {
				$event_id = EventType::getIdByName("Edited a review: review");
				$event->set('data_1',$this->r_whitespaces($review_obj->get('review')));
				$event->set('data_2',$this->r_whitespaces($_POST['review']));
			} elseif ($this->r_whitespaces($review_obj->get('rating'))!=$this->r_whitespaces($_POST['rating'])) {
				$event_id = EventType::getIdByName("Edited a review: rating");
				$event->set('data_1',$this->r_whitespaces($review_obj->get('rating')));
				$event->set('data_2',$this->r_whitespaces($_POST['rating']));
			}
		}

		//save review
		$review->save();

		//save event
		$event->set('event_type',$event_id);
		$event->save();

		$this->viewDeal();
	}

	//To edit a deal
	public function editDeal() {
		$pageName = 'Edit-Deal';
		//get that particular deal object.
		$deal_obj = Deal::loadById($_GET['dealid']);

		//To fill category-drop down in deal form
		$cat_objs = Categories::getAllCategories();

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/editdeal.tpl';
		include_once SYSTEM_PATH.'/view/side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	//Process deal addition
	public function addDealProcess() {
		if(!isset($_SESSION)){
			session_start();
		}

		$deal = new Deal();
		$deal_desc = $this->r_whitespaces($_POST['deal_desc']);
		$deal_sdate = $_POST['start_date'];
		$deal_edate = $_POST['end_date'];

		$deal_obj = $deal->loadByDesc($deal_desc);

		//Check if a deal exists with same deal description, start date and end date.
		if ($deal_obj != null) {
			if (($deal_sdate == $deal_obj->get('start_date')) &&  ($deal_edate == $deal_obj->get('end_date')) ) {
				//redirect to add deal page with appropriate error message.
				$pageName = 'Add-Deal';
				include_once SYSTEM_PATH.'/view/header.tpl';
				echo '<h3  id="resign_text" >  Error: Similar deal already exists </h3>
							<p id="warn_text" > Please check your deal description.</p>
							<br><br><br>';

				$this->addDeal();
				return;
			}
		}

		$deal->set('store_name',$this->r_whitespaces($_POST['store_name']));
		$deal->set('category',$this->r_whitespaces($_POST['category']));
		$deal->set('type',$_POST['type']);
		$deal->set('start_date',$_POST['start_date']);
		$deal->set('end_date',$_POST['end_date']);
		$deal->set('deal_desc',$deal_desc);
		$deal->set('store_url',$_POST['store_url']);
		$deal->set('added_by',$_SESSION['user_id']);

		//deal image upload
		$upload_dir = "/public/img/";
		$upload_img = $upload_dir.basename($_FILES["deal_img"]["name"]);

		//get current path
		$base = substr(BASE_URL,strpos(BASE_URL,'/cs5774'));

		$cur_path = $_SERVER["DOCUMENT_ROOT"];
		$cur_path .= $base;

		//After concatenation, it contains the path to "img" folder in the project.
		$full_upload_path = $cur_path . $upload_img;
		//echo $full_upload_path;

		// Check if file already exists in our server directory, if it doesn't then add it
		if (file_exists($upload_img)) {
		} else {
			move_uploaded_file($_FILES["deal_img"]["tmp_name"],$full_upload_path);
		}

		$deal->set('image_url',$upload_img);
		$deal->save();

		//Make an entry into the events table
		$event = new Event();
		$event->set('user_1',$_SESSION['user_id']);
		$event->set('deal_1',$deal->get('id'));

		$event_id = EventType::getIdByName("Follower/Myself Added a deal");

		$event->set('event_type',$event_id);
		$event->save();

		header('Location: '.BASE_URL);
	}

	//delete a deal
	public function deleteDeal() {
		Deal::deleteDeal($_GET['dealid']);

		header('Location: '.BASE_URL);
	}

	//Process Deal edit
	public function editDealProcess() {
		if(!isset($_SESSION)){
			session_start();
		}

		$deal = new Deal();
		$deal_id = $_GET['dealid'];
		$deal_desc = $this->r_whitespaces($_POST['deal_desc']);
		$deal_sdate = $_POST['start_date'];
		$deal_edate = $_POST['end_date'];

		$deal_obj = $deal->loadByDesc($deal_desc);

		//similar comparision as that of deal addition.
		if ($deal_obj != null&&($deal_obj->get('id')!=$deal_id)) {
			if (($deal_sdate == $deal_obj->get('start_date')) &&  ($deal_edate == $deal_obj->get('end_date')) ) {
				//redirect to edit deal page.
				$pageName = 'Edit-Deal';
				include_once SYSTEM_PATH.'/view/header.tpl';
				echo '<h3  id="resign_text" >  Error: Similar deal already exists </h3>
							<p id="warn_text" > Please check your deal description.</p>
							<br><br><br>';

				$this->editDeal();

				return;
			}
		}

		//Make appropriate entry into event's table
		$deal_old = Deal::loadById($deal_id);

		$event = new Event();
		$event->set('user_1',$_SESSION['user_id']);
		$event->set('deal_1',$deal_id);

		if ($this->r_whitespaces($deal_old->get('deal_desc'))!=$this->r_whitespaces($_POST['deal_desc'])) {
			$event_id = EventType::getIdByName("Edited a deal: Deal Description");
			$event->set('data_1',$this->r_whitespaces($deal_old->get('deal_desc')));
			$event->set('data_2',$this->r_whitespaces($_POST['deal_desc']));
		} elseif ($this->r_whitespaces($deal_old->get('start_date'))!=$this->r_whitespaces($_POST['start_date'])) {
			$event_id = EventType::getIdByName("Edited a deal: Start date");
			$event->set('data_1',$deal_old->get('start_date'));
			$event->set('data_2',$_POST['start_date']);
		} elseif ($this->r_whitespaces($deal_old->get('end_date'))!=$this->r_whitespaces($_POST['end_date'])) {
			$event_id = EventType::getIdByName("Edited a deal: End date");
			$event->set('data_1',$deal_old->get('end_date'));
			$event->set('data_2',$_POST['end_date']);
		} elseif ($this->r_whitespaces($deal_old->get('category'))!=$this->r_whitespaces($_POST['category'])) {
			$event_id = EventType::getIdByName("Edited a deal: Category");
			$event->set('data_1',$this->r_whitespaces($deal_old->get('category')));
			$event->set('data_2',$this->r_whitespaces($_POST['category']));
		} elseif ($this->r_whitespaces($deal_old->get('store_name'))!=$this->r_whitespaces($_POST['store_name'])) {
			$event_id = EventType::getIdByName("Edited a deal: Store name");
			$event->set('data_1',$this->r_whitespaces($deal_old->get('store_name')));
			$event->set('data_2',$this->r_whitespaces($_POST['store_name']));
		}

		if (isset($event_id)) {
			$event->set('event_type',$event_id);
			$event->save();
		}

		$deal->set('store_name',$_POST['store_name']);
		$deal->set('category',$_POST['category']);
		$deal->set('type',$_POST['type']);
		$deal->set('start_date',$_POST['start_date']);
		$deal->set('end_date',$_POST['end_date']);
		$deal->set('deal_desc',$deal_desc);
		$deal->set('store_url',$_POST['store_url']);
		$deal->set('added_by',$_SESSION['user_id']);
		$deal->set('id',$deal_id);

		//image upload

		$upload_dir = "/public/img/";
		$upload_img = $upload_dir.basename($_FILES["deal_img"]["name"]);

		if ($upload_dir != $upload_img) {
			$base = substr(BASE_URL,strpos(BASE_URL,'/cs5774'));

			$cur_path = $_SERVER["DOCUMENT_ROOT"];
			$cur_path .= $base;

			$full_upload_path = $cur_path . $upload_img;

			// Check if file already exists in our server directory, if it doesn't then add it
			if (file_exists($upload_img)) {
			} else {
				move_uploaded_file($_FILES["deal_img"]["tmp_name"],$full_upload_path);
			}

			$deal->set('image_url',$upload_img);
		} else {
			$deal->set('image_url',$deal_old->get('image_url'));
		}

		$deal->save();

		//echo getcwd();

		header('Location: '.BASE_URL);
	}

	//MB - function to add a deal to wishlist through AJAX
	public function addWishlist(){
		$dealid = $_POST['dealID'];
		if(!isset($_SESSION)){
			session_start();
		}

		$userid = $_SESSION['user_id'];
		$wishlist = new Wishlist();

		$result = $wishlist->checkDealExists($dealid, $userid);
		if($result != null)
			$json = array("status" => "exists");
		else{
			$wishlist->set("userid", $userid);
			$wishlist->set("dealid", $dealid);

			$wishlist->save();

			$typeid = EventType::getIdByName("Follower added a deal to his wishlist.");

			$event = new Event();

			$event->set("user_1", $userid);
			$event->set("deal_1", $dealid);
			$event->set("event_type", $typeid);
			$event->save();

			$json = array("status" => "success");
		}

		header('Content-Type: application/json');
		echo json_encode($json);
	}

	//MB - function to display the wishlist page
	public function wishlist(){
		$pageName = "Wishlist";
		if(!isset($_SESSION)){
			session_start();
		}

		$userid = $_SESSION['user_id'];
		$result = Wishlist::getAllWishlist($userid);
		$friend = User::findFollowers($userid);

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/wishlist.tpl';
		include_once SYSTEM_PATH.'/view/side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	//MB - function to remove a deal from wishlist
	public function removeWishlist(){
		$dealid = $_GET['dealid'];

		if(!isset($_SESSION)){
			session_start();
		}

		$userid = $_SESSION['user_id'];

		Wishlist::removeFromWishlist($dealid, $userid);

		header('Location: '.BASE_URL.'/wishlist');
	}

	//remove leading and trailing whitespaces.
	public function r_whitespaces($str) {
		return trim($str, " ");
	}

	/* Get latitude and longitude from Ip adress. */
	public function getLocation($ip) {
		$locUrl = 'https://freegeoip.net/json/' . $ip;
		$contents = file_get_contents($locUrl);
		$obj = json_decode($contents);

		$loc['lat'] = $obj->{'latitude'};
		$loc['long'] = $obj->{'longitude'};

		return $loc;
	}

	/* Get all the stores near a location, sorted by distance(closest to farthest) */
	public function getStores($loc) {
		$storeUrl = 'https://maps.googleapis.com/maps/api/place/nearbysearch/json?location='
				. $loc['lat'] . ',' . $loc['long'] . '&type=store&key=AIzaSyD5oBwZbM9DWht3dvtfC2fGY8KGwvRLliQ&rankby=distance';
		$contents = file_get_contents($storeUrl);
		$obj = json_decode($contents);

		$res = $obj->{'results'};

		//echo $res[0];
		//var_dump($geoloc['results'][0]['geometry']['location']['lat']);
		return array_slice($res, 0, 10);
	}

	/* Get Client IP address. */
	public function getIp() {
		$ipUrl = 'https://jsonip.com';
		$contents = file_get_contents($ipUrl);
		$obj = json_decode($contents);
		$ip = $obj->{'ip'};
		return $ip;
	}

	/* Obtains all NearBy Stores */
	public function getNearByStores() {
		$ip = $this->getIp();

		$loc = $this->getLocation($ip);

		$stores = $this->getStores($loc);

		return $stores;
	}

	//suggest a deal to a friend
	public function suggestFriend(){
		if(!isset($_SESSION)){
			session_start();
		}

		$userid = $_SESSION['user_id'];
		$dealid = $_POST['dealid'];
		$name = $_POST['friend_name'];

		$names = explode(",", $name);

		$id = User::finduserbyname($names[1], $names[0]);

		$typeid = EventType::getIdByName("Suggest a deal to a follower");

		$event = new Event();

		$event->set("user_1", $userid);
		$event->set("user_2", $id);
		$event->set("deal_1", $dealid);
		$event->set("event_type", $typeid);
		$event->save();

		header('Location: '.BASE_URL.'/wishlist');
	}

	//create json objects of all deals for a specific category
	public function dealData() {
		//get all deals
		$deal_objs = Deal::getAllCatDeals(null,$_GET['category']);

		//array to hold entire deal details.
		$jsonDeals = array();

		if(!isset($_SESSION)){
			session_start();
		}

		$userid = -1;
		if (isset($_SESSION['user_id']))
			$userid = $_SESSION['user_id'];

		foreach($deal_objs as $deal) {

			$reviews =Review::loadByDealId($deal->get('id'));

			$star = 0;
			$num_rating = 0;

			if ($reviews!= null) {
				foreach ($reviews as $rev_obj) {
					$star+=$rev_obj->get('rating');
					$num_rating++;
				}

				$star = round($star/$num_rating);
			}

			//to check if user can modify the deal
			$edit_deal = 0;

			if($userid == 1 || $deal->get('added_by') == $userid) {
				$edit_deal = 1;
			}

			$text = "Star::";
			$text .= $deal->get('store_name');
			$text .= "'s deal";
			//json category object
			$jsonDeal = array(
				'text' => nl2br($text),
				'count' => $star,
				'id' => $deal->get('id'),
				'store_name' => $deal->get('store_name'),
				'category' => $deal->get('category'),
				'type' => $deal->get('type'),
				'start_date' => $deal->get('start_date'),
				'end_date' => $deal->get('end_date'),
				'store_url' => $deal->get('store_url'),
				'image_url' => $deal->get('image_url'),
				'deal_desc' => $deal->get('deal_desc'),
				'json_type' => 1,
				'edit_deal' => $edit_deal,
				'adddeal_text' => 0
			);

			$jsonDeals[] = $jsonDeal;
		}

		//If user can add a deal,create a json object--a bubble will appear to add a deal.
		if ($userid!=-1 && $_SESSION['user_add']) {
			$jsonDeal = array(
				'text' => nl2br("Add a deal"),
				'count' => 100,
				'id' => null,
				'store_name' => null,
				'category' => null,
				'type' => null,
				'start_date' => null,
				'end_date' => null,
				'store_url' => null,
				'image_url' => null,
				'deal_desc' => null,
				'json_type' => 1,
				'edit_deal' => 1,
				'adddeal_text' => 1
			);

			$jsonDeals[] = $jsonDeal;
		}


		header('Content-Type: application/json');
		echo json_encode($jsonDeals);
	}

	//process deal add from viz
	public function vdealaddProcess() {
		header('Content-Type: application/json');

		if(!isset($_SESSION)){
			session_start();
		}

		$deal = new Deal();
		$deal_desc = $this->r_whitespaces($_POST['deal_desc']);
		$deal_sdate = $_POST['start_date'];
		$deal_edate = $_POST['end_date'];

		$deal_obj = $deal->loadByDesc($deal_desc);

		//Check if a deal exists with same deal description, start date and end date.
		if ($deal_obj != null) {
			if (($deal_sdate == $deal_obj->get('start_date')) &&  ($deal_edate == $deal_obj->get('end_date')) ) {
				//redirect to add deal page with appropriate error message.
				//$pageName = 'Add-Deal';
				//include_once SYSTEM_PATH.'/view/header.tpl';
				$json = array('error' => 'Similar deal already exists. Please check your deal description');
				echo json_encode($json);
				exit();
			}
		}

		$deal->set('store_name',$this->r_whitespaces($_POST['store_name']));
		$deal->set('category',$this->r_whitespaces($_POST['category']));
		$deal->set('type',$_POST['type']);
		$deal->set('start_date',$_POST['start_date']);
		$deal->set('end_date',$_POST['end_date']);
		$deal->set('deal_desc',$deal_desc);
		$deal->set('store_url',$_POST['store_url']);
		$deal->set('added_by',$_SESSION['user_id']);

		/*
		//deal image upload
		$upload_dir = "/public/img/";
		$upload_img = $upload_dir.basename($_FILES["deal_img"]["name"]);

		//get current path
		$base = substr(BASE_URL,strpos(BASE_URL,'/cs5774'));

		$cur_path = $_SERVER["DOCUMENT_ROOT"];
		$cur_path .= $base;

		//After concatenation, it contains the path to "img" folder in the project.
		$full_upload_path = $cur_path . $upload_img;
		//echo $full_upload_path;

		// Check if file already exists in our server directory, if it doesn't then add it
		if (file_exists($upload_img)) {
		} else {
			move_uploaded_file($_FILES["deal_img"]["tmp_name"],$full_upload_path);
		}

		$deal->set('image_url',$upload_img);*/
		$deal->save();

		//Make an entry into the events table
		$event = new Event();
		$event->set('user_1',$_SESSION['user_id']);
		$event->set('deal_1',$deal->get('id'));

		$event_id = EventType::getIdByName("Follower/Myself Added a deal");

		$event->set('event_type',$event_id);
		$event->save();

		// on success print the JSON
		$json = array('success' => 'success');
		echo json_encode($json);
		exit();
	}

	//Process Deal edit from viz
	public function vdealeditProcess() {
		header('Content-Type: application/json');

		if(!isset($_SESSION)){
			session_start();
		}

		$deal = new Deal();
		$deal_id = $_POST['dealid'];
		$deal_desc = $this->r_whitespaces($_POST['deal_desc']);
		$deal_sdate = $_POST['start_date'];
		$deal_edate = $_POST['end_date'];

		$deal_obj = $deal->loadByDesc($deal_desc);

		//similar comparision as that of deal addition.
		if ($deal_obj != null&&($deal_obj->get('id')!=$deal_id)) {
			if (($deal_sdate == $deal_obj->get('start_date')) &&  ($deal_edate == $deal_obj->get('end_date')) ) {
				//redirect to edit deal page.
				//$pageName = 'Edit-Deal';
				//include_once SYSTEM_PATH.'/view/header.tpl';
				$json = array('error' => 'Similar deal already exists. Please check your deal description');
				echo json_encode($json);
				exit();
			}
		}

		//Make appropriate entry into event's table
		$deal_old = Deal::loadById($deal_id);

		$event = new Event();
		$event->set('user_1',$_SESSION['user_id']);
		$event->set('deal_1',$deal_id);

		if ($this->r_whitespaces($deal_old->get('deal_desc'))!=$this->r_whitespaces($_POST['deal_desc'])) {
			$event_id = EventType::getIdByName("Edited a deal: Deal Description");
			$event->set('data_1',$this->r_whitespaces($deal_old->get('deal_desc')));
			$event->set('data_2',$this->r_whitespaces($_POST['deal_desc']));
		} elseif ($this->r_whitespaces($deal_old->get('start_date'))!=$this->r_whitespaces($_POST['start_date'])) {
			$event_id = EventType::getIdByName("Edited a deal: Start date");
			$event->set('data_1',$deal_old->get('start_date'));
			$event->set('data_2',$_POST['start_date']);
		} elseif ($this->r_whitespaces($deal_old->get('end_date'))!=$this->r_whitespaces($_POST['end_date'])) {
			$event_id = EventType::getIdByName("Edited a deal: End date");
			$event->set('data_1',$deal_old->get('end_date'));
			$event->set('data_2',$_POST['end_date']);
		} elseif ($this->r_whitespaces($deal_old->get('category'))!=$this->r_whitespaces($_POST['category'])) {
			$event_id = EventType::getIdByName("Edited a deal: Category");
			$event->set('data_1',$this->r_whitespaces($deal_old->get('category')));
			$event->set('data_2',$this->r_whitespaces($_POST['category']));
		} elseif ($this->r_whitespaces($deal_old->get('store_name'))!=$this->r_whitespaces($_POST['store_name'])) {
			$event_id = EventType::getIdByName("Edited a deal: Store name");
			$event->set('data_1',$this->r_whitespaces($deal_old->get('store_name')));
			$event->set('data_2',$this->r_whitespaces($_POST['store_name']));
		}

		if (isset($event_id)) {
			$event->set('event_type',$event_id);
			$event->save();
		}

		$deal->set('store_name',$_POST['store_name']);
		$deal->set('category',$_POST['category']);
		$deal->set('type',$_POST['type']);
		$deal->set('start_date',$_POST['start_date']);
		$deal->set('end_date',$_POST['end_date']);
		$deal->set('deal_desc',$deal_desc);
		$deal->set('store_url',$_POST['store_url']);
		$deal->set('added_by',$_SESSION['user_id']);
		$deal->set('id',$deal_id);

		/*image upload

		$upload_dir = "/public/img/";
		$upload_img = $upload_dir.basename($_FILES["deal_img"]["name"]);

		if ($upload_dir != $upload_img) {
			$base = substr(BASE_URL,strpos(BASE_URL,'/cs5774'));

			$cur_path = $_SERVER["DOCUMENT_ROOT"];
			$cur_path .= $base;

			$full_upload_path = $cur_path . $upload_img;

			// Check if file already exists in our server directory, if it doesn't then add it
			if (file_exists($upload_img)) {
			} else {
				move_uploaded_file($_FILES["deal_img"]["tmp_name"],$full_upload_path);
			}

			$deal->set('image_url',$upload_img);
		} else {
			$deal->set('image_url',$deal_old->get('image_url'));
		}*/

		$deal->save();

		//echo getcwd();

		// on success print the JSON
		$json = array('success' => 'success');
		echo json_encode($json);
		exit();
	}

	//delete a deal from visualization
	public function vdealdelProcess() {
		header('Content-Type: application/json');

		Deal::deleteDeal($_POST['dealid']);

		//echo "here";
		// on success print the JSON
		$json = array('success' => 'success');
		echo json_encode($json);
		exit();
	}

	//function for ajax request to fetch review data for visualization
	public function getReviews()
	{
		$dealid = $_POST['dealID'];
		$reviews =Review::loadByDealId($dealid);

		//$json = array("status" => $dealid);
		$json = array();
		if($reviews != null){

			foreach($reviews as $row){
				$obj = array();
				$obj['rating'] = $row->get('rating');
				$obj['id'] = $row->get('id');
				$reviewtext = $row->get('review');
				$length = strlen($reviewtext);
				if($length > 15)
				{
					$reviewtext = substr($reviewtext,0,15);
					$reviewtext = $reviewtext."..";
				}
				$obj['review'] = $reviewtext;
				$obj['fullreview'] = $row->get('review');
				$userid = $row->get('user_id');
				$user_obj = User::loadById($userid);
				$f_name = $user_obj->get('f_name');
				$obj['user'] = $f_name;
				$mydate = strtotime($row->get('added_time'));
				$newdate =  date('F jS Y', $mydate);
				$obj['date'] = $newdate;
				array_push($json, $obj);
			}

		}
		else{
			$obj = array();
			$obj['rating'] = 5.5;
			$obj['review'] = "No reviews are available";
			$obj['fullreview'] = "No reviews are available";
			$obj['user'] = "";
			$obj['date'] = "";
			$obj['id'] = "";
			array_push($json, $obj);
		}
		header('Content-Type: application/json');
		echo json_encode($json);

	}
	//function to edit or delete a review from visualization
	public function editReview(){
		$dealid = $_GET['dealid'];

		if(!isset($_SESSION)){
			session_start();
		}

		$userid = $_SESSION['user_id'];

		if(isset($_POST['submit']) && $_POST['submit']){
			$review = new Review();
			$review->set("rating", $_POST['rating']);
			$review->set("id", $_POST['review_id']);
			$review->set("review", $_POST['review']);
			$review->set("deal_id", $dealid);
			$review->set("user_id", $userid);
			$review->save();
		}


		if(isset($_POST['delete']) && $_POST['delete']){
			$reviewid = $_POST['review_id'];

			$result = Review::deleteReview($reviewid);
		}

		header('Location: '.BASE_URL.'/viewdeal/dealid='.$dealid);
	}
}
