<?php

class DbFactory{
    public static function getDbConnection() {
        $debug = true;
        $credentials = array();
        
        if ($debug) {
            $credentials['host'] = 'localhost';
            $credentials['username'] = 'root';
            $credentials['password'] = '';
            $credentials['database'] = 'budget_db';
        } else {
            $credentials['host'] = 'spendmeter.zymichost.com';
            $credentials['username'] = '788031_aproothi';
            $credentials['password'] = 'labyrinth1';
            $credentials['database'] = 'spendmeter_zymichost_budget';
        }
        
        $db = new mysqli($credentials['host'], $credentials['username'], $credentials['password'], $credentials['database']);

        if($db->connect_errno > 0) {
            die('Unable to connect to database [' . $db->connect_error . ']');
        }

        return $db;
    }

    public static function closeDbConnection($db) {
        $db->close();
    }

    public static function queryDb($sql, $parameters = array(), $datatypes = array()) {
        $db = self::getDbConnection();

        $params = array();
        $types = '';
        preg_match_all('/\:([A-z]+)/', $sql, $matches, PREG_SET_ORDER);

        foreach($matches as $prop) {
            if ($datatypes[$prop[1]] === 's'){
                $params[] = $db->escape_string($parameters[$prop[1]]);
            } else {
                $params[] = $parameters[$prop[1]];
            }
            $types .= $datatypes[$prop[1]];
        }

        $sql = preg_replace('/\:[A-z]+/', '?', $sql);

        $statement = $db->prepare($sql);

        if ($statement) {
            if (count($params) > 0 && $types !== ''){
                $bindingparams = "\$statement->bind_param('$types'";
                $i = 0;
                while ($i < count($params)){
                    $bindingparams .= ", \$params[".$i."]";
                    $i++;
                }
                $bindingparams .= ");";
                eval($bindingparams);
            }

            $statement->execute();
            
            $dataObj = array();

            $metaResults = $statement->result_metadata();
            if ($metaResults) {
                $fields = $metaResults->fetch_fields();

                $statementParams = '';
                foreach($fields as $field){
                    if(empty($statementParams)){
                     $statementParams.="\$row['".$field->name."']";
                    }else{
                     $statementParams.=", \$row['".$field->name."']";
                    }
                }
                $stmt="\$statement->bind_result($statementParams);";
                eval($stmt);

                while($statement->fetch()){
                    $dataObj[] = (object)$row;
                }
            } else {
                $newid = $db->insert_id;
                if ($newid > 0) {
                    $dataObj['newid'] = $newid;
                }
            }

            $statement->free_result();
            $statement->close();
            self::closeDbConnection($db);
            
            return $dataObj;
        } else {
            return null;
        }
    }
}

?>