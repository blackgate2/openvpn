<?php

// регистрационная информация (логин, пароль #1)
// registration info (login, password #1)

//$url = 'http://test.robokassa.ru/Index.aspx';
//$mrh_login = "riflemag";
//$mrh_pass1 = "rockcity12";
$url = 'https://auth.robokassa.ru/Merchant/Index.aspx';
$url_post = 'https://auth.robokassa.ru/Merchant/Index.aspx';
$mrh_login = "openvpn-sale";
$mrh_pass1 = "Lvbnhbq10291";

// номер заказа
// number of order
$inv_id = 0;

// описание заказа
// order description
$inv_desc = $msg['basket_oder_descr'];
$inv_desc2 = $msg['basket_oder_descr_prolong'];
// сумма заказа
// sum of order
$out_summ = "10.96";

// тип товара
// code of goods
$shp_item = 1; /*если 1 новый заказ если 2 значит продление*/

// предлагаемая валюта платежа
// default payment e-currency
$in_curr = "";

// язык
// language
$culture = $_SESSION['lang'];

// кодировка
// encoding
$encoding = "utf-8";

