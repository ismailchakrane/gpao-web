<?php

class Database
{
    private static $db = null;
    
    public static function getDatabase()
    {
        if (!self::$db) {
            self::$db = new PDO('mysql:dbname=gpao;host=localhost', 'root', '');
        }
    }

    public static function select($table, $key, $value){
        self::getDatabase();
        $req = self::$db->prepare("select * from $table where $key =:value");
        $req->bindParam(":value", $value);
        $req->execute();
        return $req->fetchAll();
    }

    public static function insert($table, array $keys, array $values)
    {
        $query = "insert into $table (";
        $i = 0;
        foreach ($keys as $k) {
            if($i == count($keys) - 1){
                $query .= "$k) ";
                break;
            }
            $query .= "$k,";
            $i++;
        }
        $query .= "values (";
        $i = 0;
        foreach ($keys as $k) {
            if($i == count($keys) - 1){
                $query .= ":$i) ";
                break;
            }
            $query .= ":$i,";
            $i++;
        }

        self::getDatabase();
        $req = self::$db->prepare($query);

        for ($i = 0; $i < count($values); $i++) {
            $req->bindParam(":$i", $values[$i]);
        }        
        $req->execute();
        return self::$db->lastInsertId();
    }

    public static function selectAll($table)
    {
        self::getDatabase();
        $req = self::$db->prepare("select * from " . $table);
        $req->execute();
        return $req->fetchAll();

    }

    public static function selectAllByOrder($table, $orderField = "dateE", $methode = "DESC")
    {
        self::getDatabase();
        $req = self::$db->prepare("select * from " . $table . " order by " . $orderField . " " . $methode);
        $req->execute();
        return $req->fetchAll();

    }

    public static function delete($table, $key, $value){
        self::getDatabase();
        $req = self::$db->prepare("delete from " . $table . " where " . $key . " = '" . $value ."'");
        $req->execute();
        return true;
    }

    public static function update($table,$pKeys,$pValues ,$keys,$values){
        $query = "update  $table set  ";
        $i = 0;
        foreach ($keys as $k) {
            if($i == 0){
                $query .= " `$k` = :$i ";
            }
            else {
                $query .= " and `$k` = :$i ";
            }
            $i++;
        }
        $query .= " where ";

        $i = 0;
        foreach ($pKeys as $pk) {
            if($i == 0){
                $query .= " `$pk` = :$pk ";
            }
            else {
                $query .= " and `$pk` = :$pk ";
            }
            $i++;
        }

        echo $query;

        self::getDatabase();
        $req = self::$db->prepare($query);

        for ($i = 0; $i < count($values); $i++) {
            $req->bindParam(":$i", $values[$i]);
        }    
        
        for ($i = 0; $i < count($pKeys); $i++) {
            $req->bindParam(':' . $pKeys[$i] , $pValues[$i]);
        }


        $req->execute();
        return true;
    }


}