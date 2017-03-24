<?php

include_once '../global.php';
//require_once "commonController.php";

// get the identifier for the page we want to load
$action = $_GET['action'];

// instantiate a SiteController and route it
$pc = new SiteController();
$pc->route($action);

//This is used to navigate the site(other than deal related stuff).
class SiteController {

	// route us to the appropriate class method for this action
	public function route($action) {
		switch($action) {
			case 'home':
				$this->home();
				break;

			case 'categories':
				$this->categories();
				break;

			case 'aboutus':
				$this->aboutus();
				break;

			case 'signin':
				$this->signin();
				break;

			case 'signup':
				$this->signup();
				break;

			case 'createAccount':
				$this->createAccount();
				break;

			//admin has a special sign in and can only sign in from that page.
			//http://localhost/cs5774/project5/nischelk/admin-signin
			case 'adminSignin':
				$this->adminSignin();
				break;

			case 'processLogin':
				$email = $_POST['email'];
				$password = $_POST['password'];
				$this->processLogin($email, $password);
				break;

			case 'processadminLogin':
				$email = $_POST['email'];
				$password = $_POST['password'];
				$this->processadminLogin($email, $password);
				break;

			case 'processLogout':
				$this->processLogout();
				break;

			case 'addCat':
				$this->addCat();
				break;

			case 'addCatProcess':
				$this->addCatProcess();
				break;

			case 'placesAutocomplete':
				$this->autocomplete();
				break;

			case 'viewAccount':
				$this->viewAccount();
				break;

			case 'editAccount':
				$this->editAccount();
				break;

			case 'editPassword':
				$this->editPassword();
				break;

			case 'editAccountProcess':
				$this->editAccountProcess();
				break;

			case 'editPasswordProcess':
				$this->editPasswordProcess();
				break;

			case 'edituser':
				$this->edituser();
				break;

			case 'userupdate':
				$this->userupdate();
				break;

			case 'findpeople':
				$this->findPeople();
				break;

			case 'follow':
				$this->follow();
				break;

			case 'usersAutocomplete':
				$this->userAutocomplete();
				break;

			case 'connections':
				$this->connections();
				break;

			case 'Connections':
				$this->friendConnections();
				break;
				
			case 'categoriesData':
				$this->categoriesData();
				break;

			// redirect to home page if all the above cases fail
			default:
				header('Location: '.BASE_URL);
				exit();
		}

	}

	public function home() {
		$pageName = 'Home';

		//Retrieves all the home page elements.
		$cat_objs = Categories::getAllCategories(8);
		$deal_objs_on = Deal::getAllTypeDeals(8,"online");
		$deal_objs_off = Deal::getAllTypeDeals(8,"offline");
		$deal_objs_all = Deal::getAllDeals(8);

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/home.tpl';
		include_once SYSTEM_PATH.'/view/side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	public function categories() {
		$pageName = 'Categories';
		$cat_objs = Categories::getAllCategories();

		$first_char = ""; //used to travserse the category objects and display them in a required format

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/categories.tpl';
		//include_once SYSTEM_PATH.'/view/side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	//Add a category.
	public function addCat() {
		$pageName = 'Add Category';
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/addcat.tpl';
		include_once SYSTEM_PATH.'/view/side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	//View User Account
	public function viewAccount() {
		$pageName = 'My Account';

		if(!isset($_SESSION)){
			session_start();
		}

		$user = User::loadById($_SESSION['user_id']);

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/account.tpl';
		include_once SYSTEM_PATH.'/view/side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	//Edit User Account
	public function editAccount() {
		$pageName = 'Edit Details';

		if(!isset($_SESSION)){
			session_start();
		}

		$user = User::loadById($_GET['userid']);

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/editaccount.tpl';
		include_once SYSTEM_PATH.'/view/side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	//Edit Password of User Account
	public function editPassword() {
		$pageName = 'Edit Password';

		if(!isset($_SESSION)){
			session_start();
		}

		$user = User::loadById($_GET['userid']);

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/editpassword.tpl';
		include_once SYSTEM_PATH.'/view/side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	//Process Edit Password feature
	public function editPasswordProcess() {
		$user = User::loadById($_GET['userid']);

		if ($user->get('password')==$_POST['oldpassword']) {
			$user->set('password',$_POST['password']);
			$user->save();
			$this->viewAccount();
		} else {
			$pageName = 'Edit Password';

			include_once SYSTEM_PATH.'/view/header.tpl';

			echo '<h3  id="resign_text" >  Error: Your Current Password is incorrect </h3>
						<br><br>';

			$this->editPassword();
		}
	}

	//Process Edit User Account
	public function editAccountProcess() {
		$user = new User();

		$user_temp = User::loadById($_GET['userid']);

		$user->set('id',$_GET['userid']);
		$user->set('f_name',$_POST['f_name']);
		$user->set('l_name',$_POST['l_name']);
		$user->set('password',$user_temp->get('password'));
		$user->set('add_deal',$_POST['add_deal']);
		$user->set('city',$_POST['city']);
		$user->set('email',$user_temp->get('email'));
		$user->save();

		//cookie will expire after 30 days.
		setcookie("f_name", $_POST['f_name'], time() + (86400 * 30), "/");

		if(!isset($_SESSION)){
			session_start();
		}

		if ($_POST['add_deal']=='yes')
			$_SESSION['user_add'] = 1;
		else
			$_SESSION['user_add'] = 0;

		$this->viewAccount();
	}

	//Process category addition.
	public function addCatProcess() {
		$new_cat = new Categories();

		$cat_name = $_POST['cat_name'];

		//Check if that category name already exists.
		$cat_obj = Categories::loadByName($cat_name);

		if ($cat_obj != null ) {
			//Redirect to category addition page with appropriate errors.
			$pageName = 'Add-Category';

			include_once SYSTEM_PATH.'/view/header.tpl';

			echo '<h3  id="resign_text" >  Error: Similar category already exists </h3>
						<p id="warn_text" > Please check your category name.</p>
						<br><br><br>';

			$this->addCat();

			return;
		}

		//image upload
		$upload_dir = "/public/img/";
		$upload_img = $upload_dir.basename($_FILES["cat_img"]["name"]);

		//get current path
		$base = substr(BASE_URL,strpos(BASE_URL,'/cs5774'));

		$cur_path = $_SERVER["DOCUMENT_ROOT"];
		$cur_path .= $base;

		//After concatenation, it contains the path to "img" folder in the project.
		$full_upload_path = $cur_path . $upload_img;

		// Check if file already exists in our server directory, if it doesn't then add it
		if (file_exists($upload_img)) {
		} else {
			move_uploaded_file($_FILES["cat_img"]["tmp_name"],$full_upload_path);
		}

		$new_cat->set('name',$cat_name);
		$new_cat->set('image_url',$upload_img);
		$new_cat->save();

		header('Location: '.BASE_URL);
	}

	public function aboutus() {
		$pageName = 'About-Us';
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/aboutus.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}


	public function signin() {
		$pageName = 'Signin';
		include_once SYSTEM_PATH.'/view/headersign.tpl';
		include_once SYSTEM_PATH.'/view/signin.tpl';
	}

	//Separate page for admin sign in
	public function adminSignin() {
		$pageName = 'adminSignin';
		include_once SYSTEM_PATH.'/view/headersign.tpl';
		include_once SYSTEM_PATH.'/view/adminsignin.tpl';
	}

	public function signup() {
		$pageName = 'Create Account';
		include_once SYSTEM_PATH.'/view/headersign.tpl';
		include_once SYSTEM_PATH.'/view/signup.tpl';
	}

	//Create user account.
	public function createAccount() {
		$user = new User();
		$email = $_POST['email'];

		if(($user->loadByEmail($email)) != null) {
			$pageName = 'Signin/Signup';
			include_once SYSTEM_PATH.'/view/headersign.tpl';

			echo '<h3  id="resign_text" >  Email Already in Use </h3>
					<p id="warn_text" > You indicated that you are a new customer, but an account already exists with your email id: <b> '.$email.' </b></p>
					<br><br><br><br><br>';
			include_once SYSTEM_PATH.'/view/resigninup.tpl';

			return;
		}

		$user->set('email',$email);
		$user->set('f_name',$_POST['f_name']);
		$user->set('l_name',$_POST['l_name']);
		$user->set('password',$_POST['password']);
		$user->set('add_deal',$_POST['add_deal']);
		$user->set('city',$_POST['city']);
		$user->save();

		//cookie will expire after 30 days.
		setcookie("f_name", $_POST['f_name'], time() + (86400 * 30), "/");

		//store in session--user's id and if user can add deal or not.
		if(!isset($_SESSION)){
			session_start();
		}

		$u_obj = $user->loadByEmail($email);
		$_SESSION['user_id'] = $u_obj->get('id');

		setcookie("user_id", $_SESSION['user_id'], time() + (86400 * 30), "/");

		if ($u_obj->get('add_deal')=='yes')
			$_SESSION['user_add'] = 1;
		else
			$_SESSION['user_add'] = 0;

		header('Location: '.BASE_URL);
	}

	public function processLogout() {
		if(!isset($_SESSION)){
			session_start();
		}

		//remove from session
		session_unset();
		setcookie("f_name", "", time() - (86400 * 30), "/");
		setcookie("user_id", "", time() - (86400 * 30), "/");

		header('Location: '.BASE_URL);
	}

	public function processadminLogin($email, $pw) {
		$adminEmail = "dealadmin@dealstore.com";
		$adminPassword = "dealadmin123";

		if ( ($email == $adminEmail) && ($pw == $adminPassword)) {
			//cookie will expire after 30 days.
			setcookie("f_name", "admin", time() + (86400 * 30), "/");

			if(!isset($_SESSION)){
				session_start();
			}

			$u_obj = User::loadByEmail($email);
			$_SESSION['user_id'] = $u_obj->get('id');
			setcookie("user_id", $_SESSION['user_id'], time() + (86400 * 30), "/");

			$_SESSION['user_add'] = 1; //admin can always add deals.

			header('Location: '.BASE_URL);

			return;
		}

		//any other email ids cant login from this page.
		$this->adminSignin();
		return;

	}

	public function processLogin($email, $pw) {
		$adminEmail = "dealadmin@dealstore.com";
		$user = new User();

		//Admin login is not possible from normal login page.
		$obj = $user->loadByEmail($email);
		if ($obj == null ||($obj->get('email') == $adminEmail )) {
			$pageName = 'Signin';
			include_once SYSTEM_PATH.'/view/headersign.tpl';

			echo '<h3  id="resign_text" >  Error: Email does not exist (or) inappropriate </h3>
					<p id="warn_text" > Please check your email id.</p>
					<br><br><br>';

			include_once SYSTEM_PATH.'/view/signin.tpl';

		} else {
			if (($obj->get('password'))==$pw) {

				//cookie will expire after 30 days.
				setcookie("f_name", $obj->get('f_name'), time() + (86400 * 30), "/");

				//set session variables appropriately.
				if(!isset($_SESSION)){
					session_start();
				}

				$u_obj = User::loadByEmail($email);
				$_SESSION['user_id'] = $u_obj->get('id');
				setcookie("user_id", $_SESSION['user_id'], time() + (86400 * 30), "/");

				if ($u_obj->get('add_deal')=='yes')
					$_SESSION['user_add'] = 1;
				else
					$_SESSION['user_add'] = 0;

				header('Location: '.BASE_URL);
			} else {
				$pageName = 'Signin';
				include_once SYSTEM_PATH.'/view/headersign.tpl';

				echo '<h3  id="resign_text" >  Error: Incorrect password </h3>
						<p id="warn_text" > Please check your password.</p>
						<br><br><br>';

				include_once SYSTEM_PATH.'/view/signin.tpl';
			}

		}
	}

	//MB - added function below for autocomplete of places
	public function autocomplete(){

		$keyword = $_GET['keyword'];

		$url = 'https://maps.googleapis.com/maps/api/place/autocomplete/json?input='.$keyword.'&types=(cities)&key=AIzaSyD5oBwZbM9DWht3dvtfC2fGY8KGwvRLliQ';

		$result = file_get_contents($url);
		//$json = array( 'status' => 'available' );
		//header('Content-Type: application/json');
		$decoded = json_decode($result);
		//$json = new array();
		foreach($decoded->predictions as $place)
		{
			list($city, $state, $country) = explode(",", $place->description);
			$json[] = $city.','.$state;
		}
		header('Content-Type: application/json');
		echo json_encode($json);
	}

	//removes whitespaces
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

	//Admin edits other user's privileges.
	public function edituser(){

		$pageName = 'Edit Users';

		if(!isset($_SESSION)){
			session_start();
		}

		$userid = $_SESSION['user_id'];

		if (isset($_GET["page"])) {
			$page  = $_GET["page"];
		}
		else {
			$page=1;
		};
		$limit = 15;

		$count = User::getUserCount();
		$totalpages = (count($count)-1)/$limit;

		$result = User::getUsersByPage($page, $limit, $userid);

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/userlist.tpl';
		include_once SYSTEM_PATH.'/view/side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';

	}

	//Update the privileges.
	public function userupdate(){
		$id = $_POST['userid'];
		$deal = $_POST['user_add_deals'];

		$result = User::updateDeals($id, $deal);

		header('Location: '.BASE_URL.'/editUser');
	}

	//find people to follow
	public function findPeople(){

			$pageName = "Find People";
			if(!isset($_SESSION)){
				session_start();
			}

			$userid = $_SESSION['user_id'];

			if(isset($_POST['username_search']) && $_POST['username_search'] != null){
				$searchstring = $_POST['user_search_id'];
				if($searchstring == null){
					$result = null;
				}
				else{
				$result = User::findUser($userid,$searchstring);
				}
			}
			else{
				$result = User::loadUsers($userid);
			}


			include_once SYSTEM_PATH.'/view/header.tpl';
			include_once SYSTEM_PATH.'/view/people.tpl';
			include_once SYSTEM_PATH.'/view/side.tpl';
			include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	//follow a person
	public function follow(){
		$operation = $_POST['operation'];

		$followeeid = $_POST['userid'];

		if(!isset($_SESSION)){
			session_start();
		}

		$userid = $_SESSION['user_id'];

		if($operation == "follow"){
			$follow = new Follow();

			$follow->set("follower", $userid);
			$follow->set("followee", $followeeid);

			$follow->save();

			$typeid = EventType::getIdByName("Follower/Myself Followed a person");

			$event = new Event();

			$event->set("user_1", $userid);
			$event->set("user_2", $followeeid);
			$event->set("event_type", $typeid);
			$event->save();
		}
		else if($operation == "unfollow"){
			$result = Follow::removeFollow($userid, $followeeid);
		}
		$json = array("status" => "success");
		header('Content-Type: application/json');
		echo json_encode($json);
	}

	//AutoComplete while finding people
	public function userAutocomplete(){
		$keyword = $_GET['keyword'];

		$result = User::userSearch($keyword);
		$json = array();
		if($result != null){

			foreach($result as $row){
				$name = array();
				$name['name'] = $row->get('f_name').' '.$row->get('l_name');
				$name['id'] = $row->get('id');
				array_push($json, $name);
			}

		}

		header('Content-Type: application/json');
		echo json_encode($json);
	}

	//List of connections of that user.
	public function connections(){

		$pageName = "My Connections";

		if(!isset($_SESSION)){
			session_start();
		}

		$userid = $_SESSION['user_id'];

		$friend = User::findFollowers($userid);
		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/connections.tpl';
		include_once SYSTEM_PATH.'/view/side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	//List of other's connections.
	public function friendConnections(){
		$userid = $_GET['user'];

		$user = User::loadById($userid);

		$pageName = $user->get('f_name').'\'s Connections';
		$friend = User::findFollowers($userid);

		include_once SYSTEM_PATH.'/view/header.tpl';
		include_once SYSTEM_PATH.'/view/connectionsFriend.tpl';
		include_once SYSTEM_PATH.'/view/side.tpl';
		include_once SYSTEM_PATH.'/view/footer.tpl';
	}

	public function getEvents($pageName) {

		if(!isset($_SESSION)){
			session_start();
		}

		if (isset($_SESSION['user_id']))
		{

			$user_id = $_SESSION['user_id'];

			if ($pageName=='Home')
				return Event::getMineAndFollowers($user_id);

			if ($pageName== 'My Account')
				return Event::getMyEvents($user_id);
		}
		return null;
	}
	
	//json objects of all categories
	public function categoriesData() {
		//get all categories
		$cat_objs = Categories::getAllCategories();
		
		//array to hold category, count and id.
		$jsonCats = array();
		
		foreach($cat_objs as $cat) {
			$deal_objs = Deal::getAllCatDeals(null,$cat->get('name'));
			
			$online_count = 0;
			
			if (count($deal_objs)>0) {
				
				foreach($deal_objs as $deal) {
					if ($deal->get('type')=="online")
						$online_count++;
				}
				
				//json category object
				$jsonCat = array(
					'text' => $cat->get('name'),
					'count' => count($deal_objs),
					'id' => $cat->get('id'),
					'json_type' => 0,
					'online_count' => $online_count 
				);

				$jsonCats[] = $jsonCat;
			}
		}
		
		header('Content-Type: application/json');
		echo json_encode($jsonCats);
	}
}
