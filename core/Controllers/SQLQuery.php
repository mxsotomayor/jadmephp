<?php

namespace Controllers;

use Exceptions\jDbException as jDbException;

/**
 * 
 * @author Maxwell Sotomayor <mailwebdeveloper001@gmail.com> 
 */
class SQLQuery {
    /* le pngo a todos estos attr de esta clase un underscore delante para diferenciarlos de los attr de la clase origen y pode asi procesarlos de forma diferenciada */

    protected $_dbHandle;
    protected $_result;
    protected $_types_array;

    /** Connects to database * */
    function connect($address, $account, $pwd, $name) {
        $this->_types_array = array(
            'string' => 'varchar(255)',
            'integer' => 'int(11)',
            'text' => 'longtext',
            'double' => "bigint(20)",
            'float' => "decimal(20,0)"
            );

        $this->_dbHandle = @mysql_connect($address, $account, $pwd);
        if ($this->_dbHandle != 0) {
            if (mysql_select_db($name, $this->_dbHandle)) {
                return 1;
            } else {
                throw new jDbException("Error trying select database <b>$name</b>, seems it does't exists", 1);
            }
        } else {
            throw new jDbException(mysql_error(), 1);
        }
    }

    /** Disconnects from database * */
    function disconnect() {
        if (@mysql_close($this->_dbHandle) != 0) {
            return 1;
        } else {
            throw new jDbException(mysql_error(), 1);
        }
    }

    /**
     * Select all entitys of a specific Entity
     */
    function selectAll() {
        $query = 'select * from `' . $this->_table . '`';
        return $this->query($query);
    }

    /* Find a Entity given a Id */

    function find($id) {
        $query = 'select * from `' . $this->_table . '` where `id` = \'' . mysql_real_escape_string($id) . '\'';
        return $this->query($query, 1);
    }

    /**
     * Check attendance of a Entity given a array of attr. 
     * Keys of array must match with attrbs of the entity wanted
     */
    function exist($params = array()) {
        $sql = 'select * from `' . $this->_table . '` where ';
        $it = 0;
        foreach ($params as $key => $value) {
            $sql.=$key . ' = \'' . $value . '\'';
            $it+=1;
            $it < count($params) ? $sql.=' and ' : -1;
        }
        $sql.=";";
        // echo $sql;
        return $this->query($sql, 1);
    }

    function findBy($params = array()) {

        if(!is_array($params))
            die("parametros no validos");
        if(count($params) == 0)
            die("parametros no validos");


        $sql = 'select * from `' . $this->_table . '` where ';
        $it = 0;
        foreach ($params as $key => $value) {
            $sql.=$key . ' = \'' . $value . '\'';
            $it+=1;
            $it < count($params) ? $sql.=' and ' : -1;
        }
        $sql.=";";
        return $this->query($sql);
    }

    function numberResults($query) {
        $this->_result = mysql_query($query, $this->_dbHandle);
        return mysql_fetch_array($this->_result)[0];
    }

    /** Custom SQL Query * */
    function query($query, $singleResult = 0) {
        $this->_result = mysql_query($query, $this->_dbHandle);
        if (!$this->_result) {
            header('HTTP/1.0 501');
            throw new jDbException(mysql_error(), 1);
        }


        if (preg_match("/select/i", $query)) {
            $result = array();
            $table = array();
            $field = array();
            $tempResults = array();
            $numOfFields = mysql_num_fields($this->_result);
            for ($i = 0; $i < $numOfFields; ++$i) {
                array_push($table, mysql_field_table($this->_result, $i));
                array_push($field, mysql_field_name($this->_result, $i));
            }

            while ($row = mysql_fetch_row($this->_result)) {
                for ($i = 0; $i < $numOfFields; ++$i) {
                    $table[$i] = trim(ucfirst($table[$i]));
                    $tempResults[$table[$i]][$field[$i]] = $row[$i];
                }
                if ($singleResult == 1) {
                    mysql_free_result($this->_result);
                    return $tempResults;
                }
                array_push($result, $tempResults);
            }
            mysql_free_result($this->_result);
            //  $this->disconnect();
            return($result);
        }
    }

    /** Get number of rows * */
    function getNumRows() {
        return mysql_num_rows($this->_result);
    }

    /** Free resources allocated by a query * */
    function freeResult() {
        mysql_free_result($this->_result);
    }

    /** Get error string * */
    function getError() {
        return mysql_error($this->_dbHandle);
    }

    /**
     * Get the last real Id inserted.
     * WARNING THE LAST ID INSERTED OF ANY TABLE PREVIOUSLY HANDLED
     */
    function getLastInserted() {
        return $this->find(mysql_insert_id());
    }

    /**
     * Get the last real Id of the current table handling
     */
    function getRealLastInsertedPerTableId() {
        $sql = "SELECT MAX(id) FROM " . $this->_table;
        $this->_result = mysql_query($sql, $this->_dbHandle);
        return mysql_fetch_array($this->_result)[0];
    }

    /** Insert a generic entity */
    function insert() {
        $sql = "INSERT INTO $this->_table VALUES(null,";
            $all_vars = array_keys(get_class_vars(get_class($this)));
            foreach ($all_vars as $value) {
                if (substr($value, 0, 1) != "_" && $value != "id") {
                    /* convert attr to getAttr */
                    $value = "get" . ucwords(str_replace("_", "", $value));
                    is_integer($value) ? $sql.=$this->$value() : $sql.="'" . $this->$value() . "',";
                }
            }

            $sql = rtrim($sql, ",") . ');';
     $this->query($sql, 0);
     return $this->getLastInserted();
}

/** Insert a generic entity */
function createTable() {
    echo PHP_EOL;
    $sql = " CREATE TABLE IF NOT EXISTS $this->_table (id int(11) NOT NULL AUTO_INCREMENT,";
        $cls = new ReflectionClass(ucwords($this->_table));
        $properties = $cls->getProperties(ReflectionProperty::IS_PUBLIC | ReflectionProperty::IS_PROTECTED);

        $annotations;
        foreach ($properties as $key => $value) {
            if (substr($value->name, 0, 1) != "_" && $value->name != "id") {
                $doc_comment = $cls->getProperty($value->name)->getDocComment();
                preg_match_all('#@(.*?)\n#s', $doc_comment, $annot);
                $sql.=$value->name . " " . $this->_types_array[trim(str_replace("var", "", $annot[1][0]))] . ", ";
            }
        }
        $sql.="PRIMARY KEY( id )";
        $sql.=");";

$hd = fopen('qqqq', "w+");
fwrite($hd, $sql);
fclose($hd);

echo $sql;
sleep(60);
die;
}

function delete($id) {
    $sql = "DELETE FROM $this->_table WHERE id = ".(integer)$id.";";
    return $this->query($sql, 0);
}

function update($id) {

    $sql = "UPDATE $this->_table SET ";
    $all_vars = array_keys(get_class_vars(get_class($this)));

    /* array donde guardo la claves que de verdad usare */
    $array_usable_keys = array();

    /* barro las claves que no necesito como los attr que comienzan con _ o los NULL */
    foreach ($all_vars as $value) {
        $fnct = "get" . ucwords(str_replace("_", "", $value));
        if ($value != "id" && substr($value, 0, 1) != "_" && $this->$fnct() != "")
            $array_usable_keys[] = $value;
    }

    $iteration = 0;
    foreach ($array_usable_keys as $value) {
        $fnct = "get" . ucwords(str_replace("_", "", $value));
        $sql.=$value . " = '" . $this->$fnct() . "'";
        if ($iteration < count($array_usable_keys) - 1)
            $sql.= " , ";
        $iteration+=1;
    }
    $sql = rtrim($sql, ",") . ' WHERE id = ' . (integer)$id;
    echo $sql;
    die;
    return $this->query($sql, 1);
}

}