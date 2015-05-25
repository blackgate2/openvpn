<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of class
 *
 * @author Antosha
 */
class orders_sum {

    private $ids;
    private $period_ids;
    private $q;

    public function __construct($period_ids = array()) {
        $this->ids = $ids;
        $this->period_ids = $period_ids;
        $this->q = DB::Open();
    }

    private function get_order_params() {
        /* выбираем данные по заказу  */
        $keys = array_keys($this->period_ids);
        return $this->q->fetch_data_to_array('Select
                        o.id,
                         o.num_order as name,
                         o.price,
                         o.portable,
                         o.type_id,
                         o.period_id,
                         s.country_id,
                         o.protocol_id
                   From orders o
                    JOIN order_server_ids ids ON ids.orderID = o.id 
                    JOIN servers s On s.id = ids.serverID
                    Where o.id IN ('. implode(',', $keys).') Group by o.id');
    }

    private function get_prices() {
        $order_params = $this->get_order_params();
        $prices = array();
        $price_dis_id = vars_db::user_group_discount();
        foreach ($order_params as $d) {
            /* получаем цену для выбранного периода  */
            $this->q->qry('Select
                        IFNULL(p.name'.$price_dis_id.',p.name) as price,
                        IFNULL(p.portable_price'.$price_dis_id.',p.portable_price) as portable_price
                    From prices p               
                    ' . (($d['type_id'] == 1) ? 'Join price_country_ids i On i.priceID=p.id and i.countryID=' . $d['country_id'] : '') . '               
                    Where p.type_id=? and p.period_id= ? Limit 1 ', $d['type_id'], $this->period_ids[$d['id']]);
            $dd = $this->q->getrow();

            if ($d['portable'] && $dd['portable_price'] != '0.00') {
                $prices[] = $dd['portable_price'];
            } else {
                $prices[] = $dd['price'];
            }
        }
        return $prices;
    }

    public function sum_order() {
        return array_sum($this->get_prices());
    }

}
?>
