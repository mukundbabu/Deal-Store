<?php

class Review extends DbObject {
    // name of the review table
    const DB_TABLE = 'review';

    // database fields
    protected $id;
    protected $deal_id;
    protected $user_id;
    protected $review;
	protected $rating;
	protected $added_time;

    // constructor for user object
    public function __construct($args = array()) {
        $defaultArgs = array(
            'id' => null,
            'deal_id' => null,
            'user_id' => null,
            'review' => '',
            'rating' => '',
			'added_time' =>null
            );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->deal_id = $args['deal_id'];
		$this->user_id = $args['user_id'];
        $this->review = $args['review'];
        $this->rating = $args['rating'];
        $this->added_time = $args['added_time'];
    }

    // save changes to user object
    public function save() {
        $db = Db::instance();
        // omit id and any timestamps
        $db_properties = array(
            'id' => $this->id,
            'deal_id' => $this->deal_id,
			'user_id' => $this->user_id,
            'review' => $this->review,
            'rating' => $this->rating,
            'added_time' => $this->added_time
            );
        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
    }

    // load object by ID
    public static function loadById($id) {
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }

    // load user by user id
    public static function loadByUserId($user_id=null) {
        if($user_id == null)
            return null;

        $query = sprintf(" SELECT id FROM %s WHERE user_id = '%d' ",
            self::DB_TABLE,
            $user_id
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $objects = array();
            while($row = mysql_fetch_assoc($result)) {
                $objects[] = self::loadById($row['id']);
            }
            return ($objects);
        }
    }

	 // load user by deal id
    public static function loadByDealId($deal_id=null) {
        if($deal_id == null)
            return null;

        $query = sprintf(" SELECT id FROM %s WHERE deal_id = '%d' ",
            self::DB_TABLE,
            $deal_id
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $objects = array();
            while($row = mysql_fetch_assoc($result)) {
                $objects[] = self::loadById($row['id']);
            }
            return ($objects);
        }
    }

	//load review by both deal and user id
	public static function loadByDealUserId($deal_id=null,$user_id=null) {
		if ($deal_id==null || $user_id == null)
			return null;

		$query = sprintf(" SELECT id FROM %s WHERE deal_id = '%d' and user_id = '%d' ",
            self::DB_TABLE,
            $deal_id,
			$user_id
            );
        $db = Db::instance();
        $result = $db->lookup($query);
        if(!mysql_num_rows($result))
            return null;
        else {
            $row = mysql_fetch_assoc($result);
            $obj = self::loadById($row['id']);
            return ($obj);
        }
	}

	//avg rating of a deal.
	public static function avgByDealId($deal_id=null) {

		if($deal_id == null)
            return null;

		$query = sprintf(" SELECT AVG(rating) FROM %s WHERE deal_id = '%d' ",
            self::DB_TABLE,
            $deal_id
            );
		$db = Db::instance();
        $result = $db->lookup($query);
		$row = mysql_fetch_row($result);

		return $row[0];

	}

  //Added new function to delete a review
  public static function deleteReview($reviewid = null){
    if($reviewid == null)
            return null;

    $query = sprintf("DELETE FROM %s WHERE id = '%d'",
              self::DB_TABLE,
              $reviewid);

    $db = Db::instance();
    $result = $db->lookup($query);
  }
}
