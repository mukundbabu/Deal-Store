<?php

class Follow extends DbObject {
    // name of the deal table in the database
    const DB_TABLE = 'follow';

    // database fields
    protected $id;
    protected $follower;
    protected $followee;
    protected $created;


    // constructor for Deal object
    public function __construct($args = array()) {
        $defaultArgs = array(
            'id' => null,
            'follower' => '',
            'followee' => '',
            'created' => null
            );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->follower = $args['follower'];
        $this->followee = $args['followee'];
        $this->created = $args['created'];

    }

    // saving changes to the deal item
    public function save() {
        $db = Db::instance();

		// omit id and any timestamps
        $db_properties = array(
            'id' => $this->id,
            'follower' => $this->follower,
            'followee' => $this->followee,
            'created' => $this->created
            );
        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
    }

    // load deal object by ID
    public static function loadById($id) {
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }

    public function removeFollow($userid, $followeeid){
      if($userid == null || $followeeid == null)
        return null;

        $query = sprintf("DELETE FROM %s WHERE FOLLOWER = '%s' AND FOLLOWEE = '%s'",
          self::DB_TABLE,
          $userid,
          $followeeid);

        $db = Db::instance();
        $result = $db->lookup($query);

        return $result;
    }

    

  }
