<?php

require_once('DbFactory.php');

class TrxnFactory {

	public static function addTrxn ($user, $desc, $amount, $category, $trxntype) {
        $sql = "INSERT INTO tb_trxn(
                    fk_pk_user_id
                    ,amount
                    ,description
                    ,fk_pk_category_id
                    ,fk_pk_trxn_type_id
                    ,datetime_created
                    ,datetime_updated
                )
                VALUES(
                    :user
                    ,:amount
                    ,:desc
                    ,:category
                    ,:trxntype
                    ,NOW()
                    ,NOW()
                )";
        
        $parameters = array('user'=>$user, 'desc'=>$desc, 'amount'=>$amount, 'category'=>$category, 'trxntype'=>$trxntype);
        $datatypes  = array('user'=>'i', 'desc'=>'s', 'amount'=>'d', 'category'=>'i', 'trxntype'=>'i');
        $result = DbFactory::queryDb($sql, $parameters, $datatypes);
        
        return $result;
    }
    
    public static function getTrxnSummary ($user) {
        $sql = "SELECT
                    t.description
                    ,t.amount
                    ,c.name category
                    ,tt.name type
                FROM tb_trxn t
                JOIN tb_category c
                    ON t.fk_pk_category_id = c.pk_category_id
                JOIN tb_trxn_type tt
                    ON t.fk_pk_trxn_type_id = tt.pk_trxn_type_id
                WHERE fk_pk_user_id = :user
                ORDER BY t.datetime_created";
        
        $parameters = array('user'=>$user);
        $datatypes  = array('user'=>'i');
        $result = DbFactory::queryDb($sql, $parameters, $datatypes);
        
        $summary = array();
        $summary['trxns'] = array();

        $sum = 0;
        foreach ($result as $r) {
            $summary['trxns'][] = $r;
            if ($r->type == 'spend') {
                $sum += $r->amount;
            } elseif ($r->type == 'earn') {
                $sum -= $r->amount;
            }
        }
        $summary['sum'] = $sum;
        
        return $summary;
    }

}

?>