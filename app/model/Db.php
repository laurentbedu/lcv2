<?php
class Db{
    private static $db = null;
    private static function getDb() {
        if (self::$db === null) {
            // Paramètres de configuration DB
            $dsn = "mysql:host=localhost;port=3308;dbname=ludicrea_db";
            $user = "ludicrea_user";
            $pass = "abM524ZhunNFVB3y!";
            if(App::MODE == "test"){
                $dsn = "mysql:host=localhost;port=3306;dbname=c1466609c_ludicrea_db";
                $user = "c1466609c_ludicrea_user";
            }
            if(App::MODE == "prod"){

            }
            // Création de la connexion
            try{
                self::$db = new PDO($dsn, $user, $pass,
                array(
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                    PDO::ATTR_PERSISTENT => true
                ));
            } catch(PDOException $e){
                var_dump($e);
            }
        }
        return self::$db;
    }

    protected static function query($sql, $params = null) {
        if ($params == null) {
            // var_dump($params);
            $stmt = self::getDb()->query($sql);   // exécution directe
        }
        else {
            // var_dump($sql);
            // var_dump($params);
            $stmt = self::getDb()->prepare($sql); // requête préparée
            $result = $stmt->execute($params);
        }
        return $stmt;
    }

    public static function selectAll($where = "1"){
        $classe = get_called_class();
        $table = strtolower($classe);
        $sql = "SELECT * FROM $table WHERE $where";
        $resp = self::query($sql);
        $rows = $resp->fetchAll(PDO::FETCH_CLASS, $classe);
        return $rows;
    }

    public static function selectById($id, $where = "1"){
        $classe = get_called_class();
        $table = strtolower($classe);
        //$where = "id = $id AND " . $where;
        // $sql = "SELECT * FROM $table WHERE $where";
        // $resp = self::query($sql);
        $where = "id = ? AND " . $where;
        $sql = "SELECT * FROM $table WHERE $where";
        $resp = self::query($sql, array($id));
        $row = $resp->fetchObject($classe);
        return $row;
    }

    public function deep(){
        $vars = get_object_vars($this);
        $vars = array_filter($vars, function($k, $v) {
            return Utils::endsWith($v, "_id");
        }, ARRAY_FILTER_USE_BOTH);
        // var_dump($vars);
        foreach($vars as $k=>$v){
            // var_dump($k);
            $length = strlen($k) - 3;
            $col = substr($k, 0, $length);
            // var_dump($col);
            $this->{$col} = is_null($this->{$k}) ? null : ucfirst($col)::selectById($this->{$k});
            // var_dump($this);
        }
        $vars = get_object_vars($this);
        $vars = array_filter($vars, function($k, $v) {
            return Utils::startsWith($v, "has_");
        }, ARRAY_FILTER_USE_BOTH);
        // var_dump($vars);
        foreach($vars as $k=>$v){
            // var_dump($k);
            $length = strlen($k);
            $col = substr($k, 4, $length);
            // var_dump($col);
            $this->{$col."_list"} = $this->{$k} == 0 ? null :
                ucfirst($col)::selectAll(get_class($this)."_id = ".$this->id);
            // var_dump($this);
        }
        //linked tables
        return $this;
    }

    /**
     * One or multiple
     */
    public static function insert($fields){
        if(Utils::is_assoc($fields)){
            $classe = get_called_class();
            $table = strtolower($classe);
            $columns = "";
            $values = "";
            $valuesArray = array(); 
            foreach($fields as $k=>$v){
                $columns .= $k.",";
                //$values .= "'".$v."',";
                $values .= "?,";
                array_push($valuesArray,$v);
            }
            $columns = trim($columns,',');
            $values = trim($values,',');
            $sql = "INSERT INTO $table ($columns) VALUES ($values)";
            $resp = self::query($sql, $valuesArray);
        }
        else if(is_array($fields)){
            $classe = get_called_class();
            $table = strtolower($classe);
            $columns = "";
            $values = "";
            $valuesArray = array();
            foreach($fields as $row){
                $values .= "(";
                foreach($row as $k=>$v){
                    if($fields[0] == $row){
                        $columns .= $k.",";
                    }
                    $values .= "?,";
                    array_push($valuesArray,$v);
                }
                $values = trim($values,',');
                $values .= "),";
            }
            $columns = trim($columns,',');
            $values = trim($values,',');
            $sql = "INSERT INTO $table ($columns) VALUES $values";
            $resp = self::query($sql, $valuesArray);
        }
        return $resp;//inserted obj ?
    }

    public function update($fields, $where = ""){
        $classe = get_called_class();
        $table = strtolower($classe);
        $set = "";
        $valuesArray = array();
        if($where == ""){
            $where = "id=$this->id";
        }
        foreach($fields as $k=>$v){
            $set .= $k."=?,";
            array_push($valuesArray,$v);
        }
        $set = trim($set,",");
        $sql = "UPDATE $table SET $set WHERE $where";
        //var_dump($sql, $valuesArray);
        $resp = self::query($sql, $valuesArray);
        return $resp;
    }



}