<?php

class Deal extends DbObject {
    // name of the deal table in the database
    const DB_TABLE = 'deal';

    // database fields
    protected $id;
    protected $store_name;
    protected $category;
    protected $type;
    protected $start_date;
    protected $end_date;
    protected $deal_desc;
	protected $store_url;
	protected $image_url;
	protected $added_by;
	protected $added_time;

    // constructor for Deal object
    public function __construct($args = array()) {
        $defaultArgs = array(
            'id' => null,
            'store_name' => '',
            'category' => '',
            'type' => '',
            'start_date' => null,
            'end_date' => null,
            'deal_desc' => '',
            'store_url' => '',
			'image_url' => '/public/img/deal.jpg',
			'added_by' => null,
			'added_time' => null
            );

        $args += $defaultArgs;

        $this->id = $args['id'];
        $this->store_name = $args['store_name'];
        $this->category = $args['category'];
        $this->type = $args['type'];
        $this->start_date = $args['start_date'];
        $this->end_date = $args['end_date'];
        $this->deal_desc = $args['deal_desc'];
        $this->store_url = $args['store_url'];
		$this->image_url = $args['image_url'];
		$this->added_by = $args['added_by'];
		$this->added_time = $args['added_time'];
		
    }

    // saving changes to the deal item
    public function save() {
        $db = Db::instance();
        
		// omit id and any timestamps
        $db_properties = array(
            'id' => $this->id,
            'store_name' => $this->store_name,
            'category' => $this->category,
            'type' => $this->type,
            'start_date' => $this->start_date,
			'end_date' => $this->end_date,
            'deal_desc' => $this->deal_desc,
			'store_url' => $this->store_url,
            'image_url' => $this->image_url,
            'added_by' => $this->added_by,
			'added_time' => $this->added_time	
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
    public static function getAllDeals($limit=null) {
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
    }

}
