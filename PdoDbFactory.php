<?php

class PdoDbFactory{
    public static function getDbConnection() {
        $dsn = "mysql:host=localhost;dbname=budget_db";
        $opt = array(
            // any occurring errors wil be thrown as PDOException
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            // an SQL command to execute when connecting
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"
        );
        try {
            $pdo = new PDO($dsn, 'root', '', $opt);
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }

        return $pdo;
    }

    public static function closeDbConnection($pdo) {
        $pdo = null;
    }

    public static function queryDb($sql, $parameters = array(), $datatypes = array()) {
        $dataObj = array();
        try {
            $pdo = self::getDbConnection();
            
            $ps = $pdo->prepare($sql);
            
            // Parameters and datatypes are bound by reference,
            // so do not delete the 2 arrays until execute has happened.
            foreach($parameters as $key=>$value) {
                $ps->bindParam(':'.$key, $parameters[$key], $datatypes[$key]);
            }

            $ps->execute();
            if ($ps->columnCount()) {
                $dataObj = $ps->fetchAll(PDO::FETCH_OBJ);
            } else {
                if ($pdo->lastInsertId() > 0) {
                    $dataObj['newid'] = $pdo->lastInsertId();
                }
            }
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
        
        return $dataObj;
    }

}

?>