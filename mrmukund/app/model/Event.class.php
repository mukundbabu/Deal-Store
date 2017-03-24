<?php

class Event extends DbObject {
    // name of the user table
    const DB_TABLE = 'event';

    // database fields
    protected $id;
    protected $user_1;
    protected $user_2;
    protected $deal_1;
  	protected $event_type;
  	protected $date_created;
  	protected $data_1;
  	protected $data_2;

    // constructor for user object
    public function __construct($args = array()) {
        $defaultArgs = array(
            'id' => null,
            'user_1' => null,
            'user_2' => null,
            'deal_1' => null,
            'event_type' => '',
            'date_created' => null,
      		'data_1' =>'',
      		'data_2' =>''
            );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->user_1 = $args['user_1'];
		$this->user_2 = $args['user_2'];
        $this->deal_1 = $args['deal_1'];
        $this->event_type = $args['event_type'];
        $this->date_created = $args['date_created'];
        $this->data_1 = $args['data_1'];
		$this->data_2 = $args['data_2'];
    }

    // save changes to user object
    public function save() {
        $db = Db::instance();
        // omit id and any timestamps
        $db_properties = array(
            'id' => $this->id,
            'user_1' => $this->user_1,
			'user_2' => $this->user_2,
            'deal_1' => $this->deal_1,
            'event_type' => $this->event_type,
            'date_created' => $this->date_created,
      		'data_1' => $this->data_1,
      		'data_2' => $this->data_2
            );
        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
    }

    // load object by ID
    public static function loadById($id) {
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }

	//get events of logged in user and his followees  
    public static function getMineAndFollowers($user_id = null) {
      if($user_id == null)
        return null;

      $query = sprintf(" SELECT id FROM %s WHERE (user_1 = '%d' or user_1 IN (SELECT followee from follow where follower = '%d')) or ( user_2 = '%d') ORDER BY date_created DESC LIMIT 15",
              self::DB_TABLE,
              $user_id,
              $user_id,
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

	//get only logged in user's events-for display in profile page.
    public static function getMyEvents($user_id = null) {
      if($user_id == null)
        return null;

      $query = sprintf(" SELECT id FROM %s WHERE user_1 = '%d' ORDER BY date_created DESC LIMIT 15",
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

}
