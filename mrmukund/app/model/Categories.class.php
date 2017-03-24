<?php

class Categories extends DbObject {
    // name of the categories table in database
    const DB_TABLE = 'categories';

    // database fields
    protected $id;
    protected $name;
	protected $image_url;

    // constructor for Categories object
    public function __construct($args = array()) {
        $defaultArgs = array(
            'id' => null,
            'name' => '',
			'image_url' => '/public/img/deal.jpg',
            );

        $args += $defaultArgs;

        $this->id = $args['id'];
		$this->image_url = $args['image_url'];
		$this->name = $args['name'];
		
    }

    // saving changes to category item
    public function save() {
        $db = Db::instance();
        // omit id and any timestamps
        $db_properties = array(
            'id' => $this->id,
            'name' => $this->name,
            'image_url' => $this->image_url	
            );
        $db->store($this, __CLASS__, self::DB_TABLE, $db_properties);
    }

    // load object by ID
    public static function loadById($id) {
        $db = Db::instance();
        $obj = $db->fetchById($id, __CLASS__, self::DB_TABLE);
        return $obj;
    }

    // load all categories
    public static function getAllCategories($limit=null) {
        
		if ($limit != null) {
			$query = sprintf(" SELECT id FROM %s ORDER BY name ASC LIMIT %d",
				self::DB_TABLE,
				$limit
				);
		}
		
		if ($limit == null) {
			$query = sprintf(" SELECT id FROM %s ORDER BY name ASC",
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

	// load deals by category name
    public static function loadByName ($name=null) {
        if($name === null)
            return null;

        $query = sprintf(" SELECT id FROM %s WHERE name = '%s' ",
            self::DB_TABLE,
            $name
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
