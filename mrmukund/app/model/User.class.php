<?php

class User extends DbObject {
    // name of the user table
    const DB_TABLE = 'user';

    // database fields
    protected $id;
    protected $f_name;
    protected $l_name;
    protected $email;
	protected $password;
	protected $add_deal;
	protected $city;
	protected $admin;

    // constructor for user object
    public function __construct($args = array()) {
        $defaultArgs = array(
            'id' => null,
            'f_name' => '',
            'l_name' => '',
            'email' => '',
            'password' => '',
            'add_deal' => '',
			'city' =>'',
			'admin' =>'no'
            );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->f_name = $args['f_name'];
		$this->l_name = $args['l_name'];
        $this->email = $args['email'];
        $this->password = $args['password'];
        $this->add_deal = $args['add_deal'];
        $this->city = $args['city'];
		$this->admin = $args['admin'];
    }

    // save changes to user object
    public function save() {
        $db = Db::instance();
        // omit id and any timestamps
        $db_properties = array(
            'id' => $this->id,
            'f_name' => $this->f_name,
			'l_name' => $this->l_name,
            'email' => $this->email,
            'password' => $this->password,
            'add_deal' => $this->add_deal,
			'city' => $this->city,
			'admin' => $this->admin
            );
        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
    }

    // load object by ID
    public static function loadById($id) {
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }

    // load user by email
    public static function loadByEmail($email=null) {
        if($email == null)
            return null;

        $query = sprintf(" SELECT id FROM %s WHERE email = '%s' ",
            self::DB_TABLE,
            $email
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

    public static function getUserCount(){

      $query = sprintf("SELECT id FROM %s",
          self::DB_TABLE);

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
          //return $result;
      }
    }

    public static function getUsersByPage($page = null, $limit = null, $userid = null){
      if($page === null || $limit === null || $userid == null)
        return null;

      $startfrom = ($page-1)*$limit;


      $query = sprintf(" SELECT id FROM %s WHERE id <> '%s' LIMIT %d, %d ",
          self::DB_TABLE,
          $userid,
          $startfrom,
          $limit
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
          //return $result;
      }

    }

    public static function updateDeals($id, $deal){
      if($id === null || $deal === null)
        return null;

      $query = sprintf("UPDATE %s SET %s = '%s' WHERE id = '%s'",
        self::DB_TABLE,
        "add_deal",
        $deal,
        $id);

      $db = Db::instance();
      $result = $db->lookup($query);

      return $result;
    }

    public static function loadUsers($follower = null){

      $query = sprintf("SELECT t1.*, t2.id AS follow, t2.created AS created FROM %s t1 LEFT OUTER JOIN %s t2 ON t1.id = t2.followee AND t2.follower = '%s' WHERE t1.id <> '%s' ",
          self::DB_TABLE,
          "follow",
          $follower,
          $follower);

      $db = Db::instance();
      $result = $db->lookup($query);

      if(!mysql_num_rows($result))
          return null;
      else {
            return $result;
          }
    }

    public static function findUser($userid =null, $searchstring = null){
      if($searchstring == null || $userid == null)
          return null;

          $query = sprintf("SELECT t1.*, t2.id AS follow FROM %s t1 LEFT OUTER JOIN %s t2 ON t1.id = t2.followee AND t2.follower = '%s' WHERE t1.id = '%s'",
              self::DB_TABLE,
              "follow",
              $userid,
              $searchstring);

      $db = Db::instance();
      $result = $db->lookup($query);

      if(!mysql_num_rows($result))
          return null;
      else {
            return $result;
      }
    }

    public static function userSearch($keyword = null){
      if($keyword == null)
        return null;

      $query = sprintf("SELECT * FROM %s WHERE l_name LIKE '%%%s%%' OR f_name LIKE '%%%s%%'",
        self::DB_TABLE,
        $keyword,
        $keyword);

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
          //return $result;
      }

    }

    public static function findFollowers($userid = null){
      if($userid == null)
        return null;

      $query = sprintf("SELECT t1.f_name, t1.l_name, t1.id, t1.email FROM %s t1, %s t2 WHERE t2.follower = '%s' AND t2.followee = t1.id",
        self::DB_TABLE,
        "follow",
        $userid);

      $db = Db::instance();
      $result = $db->lookup($query);

      if(!mysql_num_rows($result))
          return null;
      else {
          return $result;
      }

    }

    public static function finduserbyname($firstname = null, $lastname = null){
      if($firstname == null || $lastname == null){
        return null;
      }

      $query = sprintf("SELECT id FROM %s WHERE f_name = '%s' AND l_name = '%s'",
        self::DB_TABLE,
        $firstname,
        $lastname);

        $db = Db::instance();
        $result = $db->lookup($query);

        if(!mysql_num_rows($result))
            return null;
        else {
            $row = mysql_fetch_assoc($result);
            return $row['id'];
        }

    }
}
