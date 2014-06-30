<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
require('class.accounts.php');
/**
 * Description of class
 *
 * @author Oleg
 */
class accountsInv {

    protected $q; /* class для работы с db */
    private $hostname;

 




    public function startAction() {
        
        foreach ($this->get_data_inv() as $v) {
            
            $this->sys_line_inv($v);
            
        }
    }





}