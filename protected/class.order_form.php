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
class order_form {

    
    private $_types; // типы vpn
    private $_protocols; // протоколы 
    private $_periods; // периоды
    private $_countries; // страны
    private $q;
    private $msg;
    public $_post_script_name;
    
    public function __construct($msg) {
        $this->post_script_name='';
        $this->_periods = array();
        $this->_types = array();
        $this->_protocols = array();
        $this->_countries = array();
        $this->q= DB::Open();
        $this->getData();
        $this->msg=$msg;
    }

    private function getData() {
        
        $this->q->query('Select
                p.id,
                pg.id as typeID,
                pg.name as pgname,
                c.id as countryID,
                c.name as country,
                ps.id as protocolID,
                ps.name as protocol,
                pi.name as period,
                pi.id as periodID
           From prices p
           Join price_country_ids i On i.priceID=p.id
           Join countries c On i.countryId=c.id
           Join types pg
                ON pg.id=p.type_id
           Join type_protocol_ids ipp
                On ipp.typeID = pg.id
           Join protocols ps
                On ps.id= ipp.protocolID
           Join periods pi
                On pi.id= p.period_id
           Where p.status<>"1"
           Order by p.id ');

        while ($d = $this->q->getrow()) {

            if (!in_array($d['pgname'], $this->_types)) {
                $this->_types[$d['typeID']] = $d['pgname'];
            }
            if (!is_array($this->_protocols[$d['typeID']])) {
                $this->_protocols[$d['typeID']] = array();
            }

            if (!in_array($d['protocol'], $this->_protocols[$d['typeID']])) {
                $this->_protocols[$d['typeID']][$d['protocolID']] = $d['protocol'];
            }

            if (!is_array($this->_countries[$d['typeID']])) 
                $this->_countries[$d['typeID']] = array();            
            if (!in_array($d['country'], $this->_countries[$d['typeID']]))
                $this->_countries[$d['typeID']][$d['countryID']] = $d['country'];

            if (!is_array($this->_periods[$d['typeID']])) 
                $this->_periods[$d['typeID']] = array();            
            if (!in_array($d['period'], $this->_periods[$d['typeID']]))
                $this->_periods[$d['typeID']][$d['periodID']] = $d['period'];
        }
    }
    
    private  function getHiddenForSPAMvalidation(){
        $this->q->query('Select bilet From bilets_reg_users Where status="1"  Limit 1');
        $d = $this->q->getrow();
        return '<input type="hidden" name="biletik" value="'.$d['bilet'].'" id="biletik">';
    }
    public function order_form_HTML() {
        $str_html='
            <script>
            $(document).ready(function() {
                $("#dialog_alert").dialog({
                    modal: false,
                    autoOpen: false,
                    height: 200,
                    width: 350,
                    buttons: {
                        "'.$this->msg['close'].'": function() {
                            $(this).dialog("close");
                        }
                    }

                });
                
                $("a.list_countries").click(function() {

                        $("#dialog_alert").dialog({
                            title: "'.$this->msg['order_countries'].'"
                        });
                        $("#dialog_alert").html($(this).attr("title"));
                        $("#dialog_alert").dialog("open");

                    return false;
                });
                $("#button_submit").click(function() {
                    if ($(".type").is(":checked")) {
                        $("#theform").submit();
                    } else {

                        $("#dialog_alert").html("<p>'.$this->msg['order_select_vpn_type'].'</p>");
                        $("#dialog_alert").dialog("open");
                    }
                });
            });
            </script>
            <div id="dialog_alert"></div>
            <form action="'.$this->post_script_name.'" name="theform" id="theform" method="POST" enctype="multipart/form-data" target="_self">
                    <table align="center" cellpadding="5" cellspacing="0" id="order_tbl">
                    <th>'.implode('</th><th>', $this->msg['order_fields']) .'</th>'."\n";
        foreach ($this->_types as $typeID => $type_name) {
            $str_html.='<tr id="tr' . $typeID . '"><td class="td_type" id="td' . $typeID . '"><input type="checkbox" name="type[' . $typeID . ']" id="type' . $typeID . '" value="' . $typeID . '" class="type ui-widget-content ui-corner-all">&nbsp;' . $type_name . '</td>';
            asort($this->_countries[$typeID]);

            if ($typeID == 1 || $typeID == 2) {
                $str_html.='    <td><select  id="country' . $typeID . '" name="country[' . $typeID . ']" class="country ui-widget-content ui-corner-all" typeID=' . $typeID . ' disabled>';
                foreach ($this->_countries[$typeID] as $id => $cname) {
                    $str_html.='        <option value="' . $id . '">' . $cname . '</option>';
                }
                $str_html.='    </select></td>';
            } else {
                $ttt = '';
                foreach ($this->_countries[$typeID] as $id => $cname) {
                    $ttt.=unesc($cname ). '<br>';
                }
                //$ttt= str_replace('<br>',"\n",$ttt);
                $str_html.='<td><a href="javascript:" title="'.trim($ttt, '<br>').'" class="list_countries ui-state-default ui-corner-all">'.$this->msg['order_list_countries'].'</a>   </td>';
            }
            $str_html.='    <td><select  id="period' . $typeID . '" name="period[' . $typeID . ']" class="period ui-widget-content ui-corner-all" typeID=' . $typeID . ' disabled>';
            foreach ($this->_periods[$typeID] as $id=>$pval){
                 $str_html.='        <option value="' . $id . '">' . $pval . '</option>';
            }
            $str_html.='    </select></td>';

            $str_html.='<td><input type="checkbox"  id="portable' . $typeID . '" name="portable[' . $typeID . ']" value="1"  class="portable ui-widget-content ui-corner-all" typeID=' . $typeID . ' disabled></td>';
            $str_html.='<td><select  id="os' . $typeID . '" name="os[' . $typeID . ']"  disabled  class="os ui-widget-content ui-corner-all">';
                $str_html.='        <option value="win">Win</option><option value="mac">Mac</option>';
            $str_html.='    </select></td>';
            $str_html.='    <td><select  id="protocol' . $typeID . '" name="protocol[' . $typeID . ']"  disabled  class="protocol ui-widget-content ui-corner-all">';
            foreach ($this->_protocols[$typeID] as $id => $pval) {
                $str_html.='        <option value="' . $id . '">' . $pval . '</option>';
            }
            $str_html.='    </select></td>';
            $str_html.='<td><input type="input" id="amount' . $typeID . '"  name="amount[' . $typeID . ']"  maxlength="2" value="1" class="amount ui-widget-content ui-corner-all" typeID=' . $typeID . ' disabled></td>';
            $str_html.='<td><input type="input" id="price' . $typeID . '" value="0" disabled class="price ui-widget-content ui-corner-all"></td>';
            $str_html.='</tr>';
        }
        $str_html.='
            <tr><td colspan="7" align="right">'.$this->msg['order_total'].':</td><td><input type="input" id="total" value="" disabled class="total ui-widget-content ui-corner-all"></td></tr>
            <tr><td colspan="8" align="right"><a href="javascript:"  id="button_submit" class="ui-state-default ui-corner-all">'.$this->msg['order_submit'].'</a></td></tr>

        </table>
        '.$this->getHiddenForSPAMvalidation().'
        </form>';
        return $str_html;
    }

}

?>
