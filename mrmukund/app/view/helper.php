<?php
	//prints rating stars.
	function printStars($rating) {

		$rating+=0.25;

		$ret = null;

		for ($i=1; $i<= $rating; $i++) {
			$ret .= '<i class="fa fa-star" aria-hidden="true"></i>';
		}

		if ($i - $rating <= 0.5)
			$ret .= '<i class="fa fa-star-half" aria-hidden="true"></i>';

		echo $ret;
	}

	//prints event date in Letters.
	function printEventDate($event) {
		$mydate = strtotime($event->get('date_created'));
		echo date('F j', $mydate);
	}

	//print an event based on the switch case.
	function printEvent($event) {
		$event_type = $event->get('event_type');

		$url = BASE_URL;

		$str = null;
		
		switch ($event_type) {
			case 1:
				$deal_id = $event->get('deal_1');
				$deal_obj = Deal::loadById($deal_id);
				
				if ($deal_obj == null)
					return $str;
				
				$user_obj1 = User::loadById($event->get('user_1'));
				$user_obj2 = User::loadById($event->get('user_2'));
				
				$str = $user_obj1->get('f_name');
				$url .= "/viewdeal/dealid=";
				$url .= $event->get('deal_1');
				$str .= " has suggested <a href=$url>this </a> deal to ";
				$str .= $user_obj2->get('f_name');
				//echo $str;
				break;

			case 2:
				$user_obj1 = User::loadById($event->get('user_1'));
				$user_obj2 = User::loadById($event->get('user_2'));
				$str = $user_obj1->get('f_name');
				$str .= " has followed ";
				$str .= $user_obj2->get('f_name');
				//echo $str;
				break;

			case 3:
				$deal_id = $event->get('deal_1');
				$deal_obj = Deal::loadById($deal_id);
				
				if ($deal_obj == null)
					return $str;
				
				$user_obj = User::loadById($event->get('user_1'));
				$str = $user_obj->get('f_name');
				$url .= "/viewdeal/dealid=";
				$url .= $event->get('deal_1');
				$str .= " has added <a href=$url>this </a> deal";
				//echo $str;
				break;

			case 4:
				$deal_id = $event->get('deal_1');
				$deal_obj = Deal::loadById($deal_id);
				
				if ($deal_obj == null)
					return $str;
				
				$user_obj = User::loadById($event->get('user_1'));
				$str = $user_obj->get('f_name');
				$url .= "/viewdeal/dealid=";
				$url .= $event->get('deal_1');
				$str .= " has reviewed <a href=$url>this </a> deal";
				//echo $str;
				break;

			case 5:
				$deal_id = $event->get('deal_1');
				$deal_obj = Deal::loadById($deal_id);
				
				if ($deal_obj == null)
					return $str;
				
				$user_obj = User::loadById($event->get('user_1'));
				$str = $user_obj->get('f_name');
				$url .= "/viewdeal/dealid=";
				$url .= $event->get('deal_1');
				$str .= " has modified the store name of <a href=$url>this </a> deal from ";
				$str .= $event->get('data_1');
				$str .= " to ";
				$str .= $event->get('data_2');
				//echo $str;
				break;

			case 6:
				$deal_id = $event->get('deal_1');
				$deal_obj = Deal::loadById($deal_id);
				
				if ($deal_obj == null)
					return $str;
				
				$user_obj = User::loadById($event->get('user_1'));
				$str = $user_obj->get('f_name');
				$url .= "/viewdeal/dealid=";
				$url .= $event->get('deal_1');
				$str .= " has modified the category name of <a href=$url>this </a> deal from ";
				$str .= $event->get('data_1');
				$str .= " to ";
				$str .= $event->get('data_2');
				//echo $str;
				break;

			case 7:
				$deal_id = $event->get('deal_1');
				$deal_obj = Deal::loadById($deal_id);
				
				if ($deal_obj == null)
					return $str;
				
				$user_obj = User::loadById($event->get('user_1'));
				$str = $user_obj->get('f_name');
				$url .= "/viewdeal/dealid=";
				$url .= $event->get('deal_1');
				$str .= " has modified the start date of <a href=$url>this </a> deal from ";
				$str .= $event->get('data_1');
				$str .= " to ";
				$str .= $event->get('data_2');
				//echo $str;
				break;

			case 8:
				$deal_id = $event->get('deal_1');
				$deal_obj = Deal::loadById($deal_id);
				
				if ($deal_obj == null)
					return $str;
				
				$user_obj = User::loadById($event->get('user_1'));
				$str = $user_obj->get('f_name');
				$url .= "/viewdeal/dealid=";
				$url .= $event->get('deal_1');
				$str .= " has modified the end date of <a href=$url>this </a> deal from ";
				$str .= $event->get('data_1');
				$str .= " to ";
				$str .= $event->get('data_2');
				//echo $str;
				break;

			case 9:
				$deal_id = $event->get('deal_1');
				$deal_obj = Deal::loadById($deal_id);
				
				if ($deal_obj == null)
					return $str;
				
				$user_obj = User::loadById($event->get('user_1'));
				$str = $user_obj->get('f_name');
				$url .= "/viewdeal/dealid=";
				$url .= $event->get('deal_1');
				$str .= " has modified the description of <a href=$url>this </a> deal from ";
				$str .= $event->get('data_1');
				$str .= " to ";
				$str .= $event->get('data_2');
				//echo $str;
				break;

			case 10:
				$deal_id = $event->get('deal_1');
				$deal_obj = Deal::loadById($deal_id);
				
				if ($deal_obj == null)
					return $str;
				
				$user_obj = User::loadById($event->get('user_1'));
				$str = $user_obj->get('f_name');
				$url .= "/viewdeal/dealid=";
				$url .= $event->get('deal_1');
				$str .= " has modified his review for <a href=$url>this </a> deal from ";
				$str .= $event->get('data_1');
				$str .= " to ";
				$str .= $event->get('data_2');
				//echo $str;
				break;

			case 11:
				$deal_id = $event->get('deal_1');
				$deal_obj = Deal::loadById($deal_id);
				
				if ($deal_obj == null)
					return $str;
				
				$user_obj = User::loadById($event->get('user_1'));
				$str = $user_obj->get('f_name');
				$url .= "/viewdeal/dealid=";
				$url .= $event->get('deal_1');
				$str .= " has modified his rating for <a href=$url>this </a> deal from ";
				$str .= $event->get('data_1');
				$str .= " to ";
				$str .= $event->get('data_2');
				//echo $str;
				break;

			case 12:
				$deal_id = $event->get('deal_1');
				$deal_obj = Deal::loadById($deal_id);
				
				if ($deal_obj == null)
					return $str;
				
				$user_obj = User::loadById($event->get('user_1'));
				$str = $user_obj->get('f_name');
				$url .= "/viewdeal/dealid=";
				$url .= $event->get('deal_1');
				$str .= " has added <a href=$url>this </a> deal to his wishlist ";
				//echo $str;
				break;

			default:
				break;
		}
		
		return $str;
	}
?>
