<?php

$msg = array(
    /* ------- таблица    ------- */
    'back' => 'back',
    'forv' => 'forward',
    'num_pages' => 'Pages',
    'num_rec' => 'Rows',
    'total_pages' => 'Total Pages',
    'total_rec' => 'Total records',
    'checkAll' => '',
    'rows_deleted' => 'Запиcь удалена',
    'row_saved' => 'Запиcь сохранена',
    'row_added' => 'Запись добавлена',
    'row_status_changed' => 'Статус изменен',
    'confirm_del' => 'Вы действительно хотите удалить запись ?',
    /* ------- кнопки   ------- */
    'add' => 'add',
    'edit' => 'edit',
    'copy' => 'copy',
    'dell' => 'dell',
    'open' => 'Open',
    'close' => 'Close',
    'status_on' => 'on',
    'status_off' => 'off',
    'submit_button' => 'Submit',
    'button_cancel' => 'Cancel',
    'select_title_default' => 'All',
    /* ------- картинка   ------- */
    'img_param' => 'Image parameters',
    'img_param_size' => 'before',
    'img_param_res' => 'Resolution not more than',
    /* ------- форма   ------- */
    'valid_form_login' => 'Usernames must consist of the characters a-z, 0-9 and the underscore character and start with the character.',
    'valid_form_login_len' => 'Usernames must not less than 3 and not more than 20 characters.',
    'valid_form_email' => 'Wrong email.',
    'valid_form_pass' => 'The password must be between 6 and 16 characters.',
    'valid_form_pass_re' => 'Пароли несовпадают',
    'valid_form_name' => 'Length of the text field "Name" must be at least 1 and no more than 255 characters.',
    'valid_form_text' => 'Length of the text field  " + n + " must be at least "+min+" and no more than "+max+" characters.',
    'valid_form_radio' => 'In the \"" + f + "\" must be set at least one value.',
    'valid_form_checkbox' => 'In the \"" + f + "\" must be selected.',
    'valid_form_select' => 'In the \"" + f + "\" must be set at least one value.',
    'valid_form_date' => 'Fill out the field  \"" + n + "\" ',
    'valid_form_oneOrMore' => 'At least one form to be filled',
    /* ------- Date_time_form ------- */
    'timeOnlyTitle' => 'Select the time',
    'timeText' => 'Time',
    'hourText' => 'Hours',
    'minuteText' => 'Minutes',
    'secondText' => 'Seconds',
    'currentText' => 'Now',
    'closeText' => 'Close',
    /* ------- окна  ------- */
    'dialog_title' => 'Title',
    'dialog_modal_title' => '',
    'dialog_confirm_title' => '',
    'dialog_alert_title' => '',
    /* ------- авторизация ------- */
    'login_ok' => 'You have successfully logged in',
    'login_err' => 'Wrong login or password',
    //'logig_ok'=> 'You have logged in successfully!',
//'login_err'=> 'You have logged in successfully!',
    'login_field' => 'Login',
    'passwd_field' => 'Password',
    'button_ucreate' => 'Registration',
    'button_usendpass' => 'Send me password',
    'log_error' => 'You specified an incorrect login or password!',
    'reg_error_spam_bot' => 'The system you are perceived as spam bot!',
    'reg_error_login_exist' => 'The Login has already been registered, please try another',
    'reg_error_email_exist' => 'This email has already been registered, please try another',
    'reg_forgot_email' => 'This email is not registered with us, please try another',
    /* ------- модули ------- */
    'not_found' => 'Not found',
    'page_under_construction' => 'This section is under construction',
    'page_not_found' => 'Page not found',
    'user_active' => 'User activation',
    'user_active_success' => 'Activation was successful.',
    'user_active_fail' => 'Failed, apparently something went wrong: (',
    /* --------  sendpass -------------- */
    'user_sendpass' => 'Forgot your password?',
    'user_sendpass_submit' => 'Send me password',
    'user_sendpass_form_field' => 'Enter your email',
    /* --------  Регистрация -------------- */
    'user_create' => 'Registration',
    'user_create_submit' => 'Sign up',
    'user_login' => 'Login',
    'user_passwd' => 'Password',
    'user_passwd_re' => 'Repeat password',
    'user_name' => 'Your name',
    'user_email' => 'E-mail',
    'user_jabber' => 'Jabber',
    'user_icq' => 'ICQ',
    /* --------  user menu -------------- */
    'logout' => 'Logout',
    'your_orders' => 'Your orders',
    /* --------  заказы пользователя -------------- */
    'user_orders_tab' => array('Account/Zip','Links to settings', 'Num order', 'Start Date', 'End date', 'Type of VPN', ' Countries ', ' Price($)', 'Сhoose period', ''),
    'user_orders' => 'Your orders',
    'user_no_orders' => 'No Orders',
    'user_all_counries' => 'All countries for multi-account',
    'user_prolong' => 'Extend',
    'user_prolong_all' => 'Extend all orders',
    'button_config' => 'Config for ',
    'button_config_hint' => 'Download configuration file',
    'button_settings' => 'Settings',
    'button_settings_hint' => 'How to set up',
    /* --------  Новый заказ -------------- */
    'order_countries' => 'Countries',
    'order_fields' => array('Type VPN', 'Country', 'Period(mon)', 'Portable', 'OS', 'Protocol', 'Amount', 'Price($)'),
    'order_list_countries' => 'List of countries',
    'order_submit' => 'Submit order',
    'order_select_vpn_type' => 'Please specify the type of VPN',
    'order_total' => 'Total',
    /* ------- корзинка ИЛИ оплата заказа --------- */
    'basket_name' => 'Confirm order and payment',
    'basket_oder_descr' => 'Payment order openvpn.ru',
    'basket_oder_descr_prolong' => 'Extending order openvpn.ru',
    'basket_submit_button' => 'Pay order',
    'basket_order_not_exists' => 'Order is not exists',
    /* ------- ошибка оплаты заказа --------- */
    'order_fail' => 'You have refused payment. Order# ' . $_REQUEST["InvId"], //"You have refused payment. Order# $inv_id\n";
    /* ------- оплата заказа успешна --------- */
    'order_bad_sign' => 'Invalid Digital Signature',
    'order_payment_ok' => 'Your order has been paid. This service will be included in a minute. Thank you for choosing us!.',
    'order_ext_payment_ok' => 'Your order is extended. VPN account will be included in a minute.',
    'error_try_agen' => 'Error: It seems that something went wrong. Try again',
    /* ------- письмо при регистрации --------- */
    'reg_email_suject' => 'Успешная регистрация. openvpn.ru',
    'reg_email_body' => "
                           <p>Hello, <name> </p>
                             <p>You have successfully signed up for <a href=\"openvpn.ru\">openvpn.ru</a>\r\n
                             To activate the account click on the link <alink>.\r\n
                             If the link do not opened, please copy the address bar of the browser\r\n</p>
                             <p>This is an automated message, reply to that is not required.\r\n</p>
                             <p>Thank you</p>
                             Openvpn.ru",
    'send_pass_suject' => 'openvpn.ru Access',
    'send_pass_body' => "<p> Hello, <name> </p>
                             <p>Username: <login></p>
                             <p>Your new password: <passwd></p>
                             <p>This is an automated message, reply to that is not required.</p>
                             <p>Thank you</p>
                             Openvpn.ru",
);
