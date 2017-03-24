<?php

class Wishlist extends DbObject {
    // name of the deal table in the database
    const DB_TABLE = 'wishlist';

    // database fields
    protected $id;
    protected $dealid;
    protected $userid;
    protected $created;


    // constructor for Deal object
    public function __construct($args = array()) {
        $defaultArgs = array(
            'id' => null,
            'dealid' => '',
            'userid' => '',
            'created' => null
            );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->dealid = $args['dealid'];
        $this->userid = $args['userid'];
        $this->created = $args['created'];

    }

    // saving changes to the deal item
    public function save() {
        $db = Db::instance();

		// omit id and any timestamps
        $db_properties = array(
            'id' => $this->id,
            'dealid' => $this->dealid,
            'userid' => $this->userid,
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

    // load all deals in descending order of added time
    /*public static function getAllDeals($limit=null) {
		if ($limit != null) {
			$query = sprintf(" SELECT id FROM %s ORDER BY added_time DESC  LIMIT %d",
            self::DB_TABLE,
			$limit
            );
		} else {
			$query = sprintf(" SELECT id FROM %s ORDER BY added_time DESC ",
            self::DB_TABLE
            );
		}

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

	// load all deals of specific type(online or offline)
    public static function getAllTypeDeals($limit=null,$type=null) {
		if ($limit != null) {
			$query = sprintf(" SELECT id FROM %s WHERE type = '%s' ORDER BY added_time DESC  LIMIT %d",
            self::DB_TABLE,
			$type,
			$limit
            );
		} else {
			$query = sprintf(" SELECT id FROM %s WHERE type = '%s' ORDER BY added_time DESC ",
            self::DB_TABLE,
			$type
            );
		}

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

	// load all deals of specific category
    public static function getAllCatDeals($limit=null,$cat=null) {
		if ($limit != null) {
			$query = sprintf(" SELECT id FROM %s WHERE category = '%s' ORDER BY added_time DESC  LIMIT %d",
            self::DB_TABLE,
			$cat,
			$limit
            );
		} else {
			$query = sprintf(" SELECT id FROM %s WHERE category = '%s' ORDER BY added_time DESC ",
            self::DB_TABLE,
			$cat
            );
		}

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

	//delete a specific deal
	public static function deleteDeal($id=null) {
		if ($id==null)
			return;

		$query = sprintf(" DELETE FROM %s WHERE id = '%d' ",
            self::DB_TABLE,
			$id
            );

		$db = Db::instance();
		$db->execute($query);
	}

	// load deal by deal description
    public static function loadByDesc ($desc=null) {
        if($desc === null)
            return null;

        $query = sprintf(" SELECT id FROM %s WHERE deal_desc = '%s' ",
            self::DB_TABLE,
            $desc
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
    }*/

    public static function checkDealExists($dealid = null, $userid = null){
      if($dealid == null || $userid == null)
        return null;

      $query =  sprintf(" SELECT id FROM %s WHERE userid = '%s' and dealid = '%s'",
          self::DB_TABLE,
          $userid,
          $dealid
          );

      $db = Db::instance();
      $result = $db->lookup($query);

      if(!mysql_num_rows($result))
          return null;
      else {
        $row = mysql_fetch_assoc($result);
        $obj = $row['id'];
        return ($obj);
    }
  }

  public static function getWishlistByUser($userid = null){
    if($userid == null)
      return 0;

  $query = sprintf("SELECT t1.id FROM %s t1, %s t2 WHERE t1.userid = '%s' and t1.dealid = t2.id",
          self::DB_TABLE,
          "deal",
          $userid);

  $db = Db::instance();
  $result = $db->lookup($query);

  $num_rows = mysql_num_rows($result);
  return ($num_rows);
  }

  public static function getAllWishlist($userid = null){
    if($userid == null)
      return null;

      $query = sprintf("SELECT * FROM %s t1, %s t2 WHERE t1.userid = '%s' and t1.dealid = t2.id",
          self::DB_TABLE,
          "deal",
          $userid);

      $db = Db::instance();
      $result = $db->lookup($query);
      if(!mysql_num_rows($result))
          return null;
      else {
          return $result;
          }

      }

      public static function removeFromWishlist($dealid = null, $userid = null){
        if($dealid == null || $userid == null)
          return null;


        $query = sprintf(" DELETE FROM %s WHERE userid = '%s' and dealid = '%s'",
            self::DB_TABLE,
            $userid,
            $dealid
            );

        $db = Db::instance();
        $result = $db->lookup($query);
      }
  }
