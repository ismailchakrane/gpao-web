<?php

class Database
{
    private static $db = null;
    
    public static function getDatabase()
    {
        if (!self::$db) {
            self::$db = new PDO('mysql:dbname=gpao;host=localhost', 'root', '');
        }
        return self::$db;
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
        
        echo $query;

        // self::getDatabase();
        // $req = self::$db->prepare($query);
        //$i = 0;
        // foreach ($values as $v) {
        //     $req->bindParam(":$i", $v);
        //     $i++;
        // }        
        // $req->execute();
        // return $req->fetchAll();
    }

    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();

    }

}