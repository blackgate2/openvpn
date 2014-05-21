<?php

        $forms = new forms($msg);
        $forms->fields = $tables[$table.'_filter'];
        $forms->nameform = 'form_filter';
        $forms->hiddens = array('action' => 'show', 
                                'm' => $m, 
                                'table' => $tables[$table.'_show']['table'], 
                                'order' => $tables[$table.'_show']['order'], 
                                'order_dir' => $tables[$table.'_show']['order_dir'], 
                                'p' => $p, 
                                'maxRow' => $maxRow);
        $forms->is_form_In_dialog = 0;
        //$forms->set_submit_button('Применить фильтр');
        $forms->print_form_filter(true);
        $str_filters = $forms->str;
?>
