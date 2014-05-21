<?php

if ($action=='copy' || $action=='add'){
    $d=$q->get_fetch_data('SELECT nextNumOrder() as n');
    $o->set_val_by_name('num_order',$d['n']);
    $o->set_val_by_name('account_id',null);
}
