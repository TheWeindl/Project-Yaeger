<?php
/**
 * Created by PhpStorm.
 * User: dprinzensteiner
 * Date: 28.01.2018
 * Time: 17:25
 */

abstract class BaseClass {

    public function __construct($id = null) {
        if (!is_null($id)) {
            $this->init($id);
        }
    }

    public function init($id) {
        $result = Database::getInstance()->getTableData(static::DATABASE_TABLE, "*", "id={$id}");
        if (empty($result)) {
            throw new Exception("Item with id {$id} does not exist");
        } else {
            foreach ($result[0] as $key => $value) {
                $this->{$key} = $value;
            }
        }
        return $this;
    }

    public function set($property, $value, $type = null) {
        if ($value !== $this->$property) {		//$this->$property translates to the variable name, for example $this->_name or $this->_address
            !empty($type) OR $type = 'generic';		//The default type to check against is 'generic', which cuts off non-scalar values
            if (!empty($value) && BaseClass::checkParameter($value, $type) === false) {
                throw new Exception("Invalid type '{$type}' for '{$property}'");
            }
            if ($value && $type == 'generic' && @unserialize($value) === false) {
                $value = htmlspecialchars(strip_tags($value));
            }
            $this->$property = $value;
            $this->_must_persist = true;
        }
        return $this;
    }

    /**
     * Useful if we already have the database data for this object, so that we don't have to query again
     * @return self
     */
    public function setFields(array $fields) {
        foreach ($this->_fields as $db_field => $characteristics) {
            if (array_key_exists($db_field, $fields)) {
                $this->set($db_field, $fields[$db_field], $characteristics['type']);
            }
        }
        return $this;
    }
    /**
     * Useful for returning the whole object's database properties at once (for example, when populating a form)
     */
    public function getFields() {
        $fields = array();
        foreach (array_keys($this->_fields) as $db_field) {
            $fields[$db_field] = $this->{$db_field};
        }
        return $fields;
    }
    public function getTypesOfFields() {
        return $this->_fields;
    }
    public function save() {
        if ($this->id) {		//if this is an existing entity and nothing's changed, do nothinge
            return $this;
        }
        // $this->setLastUpdate(false);
        $db = Database::getInstance();
        $fields = $this->getFields();
        if (!$this->id) {
            $this->id = $db->insertTableData(static::DATABASE_TABLE, $fields);
        } else {
            $db->updateTableData(static::DATABASE_TABLE, $fields, "id={$this->id}");
        }
        return $this;
    }

    public static function checkParameter($parameter, $type)
    {
        $result = true;
        switch ($type) {
            case 'generic': case 'wysiwig': case 'editor': case 'path': is_scalar($parameter) && !is_null($parameter) OR $result = false; break;		//Cuts off objects, arrays, resources
            case 'uuid': (preg_match("/^[A-Fa-f0-9\-]{36}$/", $parameter))  OR $result = false; break;      //for tincan UUIDs
            case 'string': (preg_match("/^[A-Za-z]{1,100}$/", $parameter))  OR $result = false; break;
            case 'string_with_spaces': (preg_match("/^[A-Za-z_]+[0-9]*$/", $parameter))  OR $result = false; break;
            case 'domain': (preg_match("/^([A-Za-z]+[0-9]*){3,50}$/", $parameter))  OR $result = false; break;  //DON'T ALLOW DASH! it will make subdomains stop working (because it tries to create a db with the same name
            case 'uint':
            case 'id': (preg_match("/^[0-9]{1,100}$/", $parameter)) OR $result = false; break;
            case 'login': ((preg_match("/^_*[\p{L}\d]+([\p{L}\d]*[._@-]*[\p{L}\d]*)*$/u", $parameter) && mb_strlen($parameter) <= User::MAXIMUM_LOGIN_LENGTH && mb_strlen($parameter) >= User::MINIMUM_LOGIN_LENGTH)) OR $result = false; break;                      //This means: begins with 0 or more '_', followed by at least 1 word character, followed by any combination of .,_,-,@ and word characters.
            case 'login_with_trailing_spaces': ((preg_match("/^_*[\p{L}\d]+([\p{L}\d]*[._@-]*[\p{L}\d]*)*\s*$/u", $parameter) && mb_strlen($parameter) <= User::MAXIMUM_LOGIN_LENGTH && mb_strlen($parameter) >= User::MINIMUM_LOGIN_LENGTH)) OR $result = false; break;  //Same as above, but we are also allowing trailing spaces, since these are trimmed by default, but we want such a username to be able to be submitted in the first place
            case 'name': preg_match("/^[\p{L} \d-,'\.\(\)]+$/", $parameter) OR $result = false; break; //A "name" (first name, last name etc) can only consist of letters, numbers, dots, commans, dashes, quotes,parentheses, spaces
            //case 'email': (preg_match("/^([a-zA-Z0-9+_\.\-'])+\@(([a-zA-Z0-9_\-])+\.)+([a-zA-Z0-9]{2,4})+$/", $parameter))  OR $result = false; break;  //This means: begins with 0 or more '_' or '-', followed by at least 1 word character, followed by any combination of '_', '-', '.', '+' and word characters, then '@', then the same as before, then the '.' and then 1 ore more characters.
            case 'email': (preg_match("/^([\p{L}\d_\.\-\+'])+\@(([a-zA-Z0-9_\-])+\.)+([a-zA-Z0-9]{2,4})+$/u", $parameter))  OR $result = false; break;       //unicode equivalent of above
//            case 'email': $result = filter_var($parameter, FILTER_VALIDATE_EMAIL, FILTER_FLAG_EMAIL_UNICODE); break;  //we're not using this, because FILTER_FLAG_EMAIL_UNICODE is only supported in PHP 7.1+
            case 'filename':
            case 'file': if (preg_match("/^.*((\.\.)|(\/)|(\\\)).*$/", $parameter)) $result = false; break;                      //File name must not contain .. or slashes of any kind
            case 'directory': (preg_match("/^.*((\.\.)|(\\\)).*$/", $parameter))  OR $result = false; break;                      //Directory is the same as filename, except that it may contain forward slashes
            case 'hex': (preg_match("/^[0-9a-fA-F]{1,100}$/", $parameter))  OR $result = false; break;
            case 'timestamp': (preg_match("/^[0-9]{9,10}$/", $parameter))  OR $result = false; break;
            case 'date': (preg_match("/^[0-3]?[0-9][\-\/][0-1]?[0-9][\-\/][0-9]{4}$/", $parameter))  OR $result = false; break;
            case 'alnum': (preg_match("/^[A-Za-z0-9_]{1,100}$/", $parameter))  OR $result = false; break;
            case 'ldap_attribute': (preg_match("/^[A-Za-z0-9:;\-_]{1,100}$/", $parameter))  OR $result = false;                     //An ldap attribute may be of the form: cn:lang-el; break;
            case 'alnum_with_spaces': (preg_match("/^[A-Za-z0-9_\s]{1,100}$/", $parameter) )  OR $result = false; break;
            case 'alnum_with_dashes': (preg_match("/^[A-Za-z0-9\-]{1,100}$/", $parameter) )  OR $result = false; break;
            case 'alnum_general': (preg_match("/^[\.,_\-A-Za-z0-9\s]{1,100}$/", $parameter) )  OR $result = false; break;
            case 'locale': (preg_match("/^\w\w_\w\w\.utf8$/", $parameter) )  OR $result = false; break;
            case 'text': if (preg_match("/^.*[$\/\'\"]+.*$/", $parameter) )  $result = false; break;
            case 'noscript': (preg_match("/^.*<script>.*<\/script>.*$/i", $parameter) )  OR $result = false; break;
            // 			case 'path': if (preg_match("/^.*[\"]+.*$/", $parameter) ) $result = false; break;
            case 'numeric': is_numeric($parameter) OR $result = false; break;
            case 'lang': (preg_match("/^[a-zA-Z0-9\.\-]+$/", $parameter) )  OR $result = false; break;
            case 'int':  $result = filter_var($parameter, FILTER_VALIDATE_INT); break;
            case 'float':  $result = filter_var($parameter, FILTER_VALIDATE_FLOAT); break;
            case 'boolean': $result = filter_var($parameter, FILTER_VALIDATE_BOOLEAN); break;
            case 'version': (preg_match("/^[0-9\.]+$/", $parameter))  OR $result = false; break;
            default:
                throw new EfrontException("Undefined check type: {$type}");
                break;
        }
        return $result;
    }

}