<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author Oleg
 */
class order_post {

    private $_typeIDs;
    private $_countryIDs;
    private $_periods;
    private $_amounts;
    private $_portables;
    private $_os;
    private $_protocolIDs;
    private $_prices;
    private $_biletik;
    private $_userID;
    public $msg_error;
    private $q;
    private $msg;
    public function __construct($msg) {
        $this->q = DB::Open();
        $this->_typeIDs = $_SESSION['basket']['type'];
        $this->_countryIDs = $_SESSION['basket']['country'];
        $this->_periods = $_SESSION['basket']['period'];
        $this->_amounts = $_SESSION['basket']['amount'];
        $this->_portables = $_SESSION['basket']['portable'];
        $this->_os = $_SESSION['basket']['os'];
        $this->_protocolIDs = $_SESSION['basket']['protocol'];
        $this->_biletik = $_SESSION['basket']['biletik'];
        $this->_userID = $_SESSION['auth_user_id'];
        $this->msg=$msg;
        $this->setPrices();
        
    }

    private function isPortablePrice($d, $typeID) {
        if ($this->_portables[$typeID] && $d['portable_price'] != '0.00') {
            return $d['portable_price'];
        } else {
            return $d['price'];
        }
    }

    private function setPrices() {
        $price_dis_id = vars_db::user_group_discount();
        foreach ($this->_typeIDs as $typeID) {
            $this->q->query('
                    Select
                        IFNULL(p.name'.$price_dis_id.',p.name) as price,
                        IFNULL(p.portable_price'.$price_dis_id.',p.portable_price) as portable_price
                    From prices p

                    ' . (($typeID == 1) ? 'Join price_country_ids i On i.priceID=p.id and i.countryID=' . $this->_countryIDs[$typeID] : '') . '

                    Where p.type_id=' . $typeID . ' and p.period_id=' . $this->_periods[$typeID] . '
                    Limit 1');

            if ($this->q->numrows()) {
                $d = $this->q->getrow();
                $this->_prices[$typeID] = $this->isPortablePrice($d, $typeID);
            } else {
                throw new Exception;
            }
        }
    }

    public function checkSpam() {
        // ------------------------------ проверка на Spam Bot ------------------------------ //
        $this->q->qry('Select bilet From bilets_reg_users Where bilet = "?" and status="1"',$this->_biletik);
        if (!$this->q->numrows()) {
            $this->msg_error .= '<span class="error">' . $this->msg['reg_error_spam_bot'] . '</span>';
            return false;
        } else {
            $this->q->qry('Update bilets_reg_users Set status="" Where bilet="?"',$this->_biletik);
            return true;
        }
    }

    private function date_exp($tid) {
        $this->q->query('Select for_sql From periods Where id = ' . $this->_periods[$tid]);
        if ($this->q->numrows()) {
            $d = $this->q->getrow();
            return 'DATE_ADD("' . date("Y-m-d H:i:s") . '", INTERVAL ' . $d['for_sql'] . ' )';
        } else {
            throw new Exception;
        }
    }

    private function getNumOrder() {
        $this->q->query('Select nextNumOrder() as n');
        if ($this->q->numrows()) {
            $d = $this->q->getrow();
            return $d['n'];
        } else {
            throw new Exception;
        }
    }

    private function getSQL() {
        $arrSql = array();
    }

    public function inserOrder() {

        
           
            $num_order=$this->getNumOrder();
            if (is_numeric($num_order)) {
                $this->q->begin();
                foreach ($this->_typeIDs as $tid) {
                    

                    for ($i = 0; $i < $this->_amounts[$tid]; $i++) {
                        $sql = 'Insert Into orders  (type_id,   protocol_id,  datetime_expire, num_order, user_id, price, portable,os,period_id,action_id,datetime_edit) 
                        Values (' . $tid . ',' . $this->_protocolIDs[$tid] . ',
                        ' . $this->date_exp($tid) . ',  
                        ' . $num_order . ',
                        ' . $this->_userID . ',
                        ' . $this->_prices[$tid] . ',
                        "'. $this->_portables[$tid] . '",
                        "'. $this->_os[$tid] . '",
                        ' . $this->_periods[$tid] . ',
                        1,
                        CURRENT_TIMESTAMP
                            )';

                        if ($this->q->query($sql)) {
                            $orderID = $this->q->lastID();

                            if ($orderID || !$this->q->result) {
                                if ($tid == 1 || $tid == 2) {
                                    $this->q->query('Select getRandomServerByCountryID(' . $this->_countryIDs[$tid] . ') as serverID');
                                    $d = $this->q->getrow();
                                    $this->q->query('Select getRandomServerByCountryID(' . $this->_countryIDs[$tid] . ') as serverID');
                                    $sql = 'CALL insertUpdateOrderServersIds(' . $orderID . ',"' . $d['serverID'] . '")';
                                } elseif ($tid == 3) { // тип мульти
                                    //  сервера для мульти 
                                    $sql = 'CALL insertUpdateOrderMultiServersIds(' . $orderID . ', true, false)';
                                } elseif ($tid == 4) { // тип мультидабл
                                    //  сервера для мультидабл 
                                    $sql = 'CALL insertUpdateOrderMultiServersIds(' . $orderID . ', false, true)';
                                } else {
                                    $sql = 'CALL insertUpdateOrderMultiServersIds(' . $orderID . ', false, true)';
                                }
                                $this->q->query($sql);
                            } else {
                                $this->q->rollback();
                                $this->msg_error .= '<span class="error">'.$msg['error_try_agen'].'</span>';
                            }
                        } else {
                            $this->msg_error .= '<span class="error">'.$msg['error_try_agen'].'</span>';
                        }
                        
                    }
                }
                $this->q->commit();
                
            } else {
                throw new Exception;
            }
            return $num_order;

    }

}

