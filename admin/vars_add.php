<?

$tables['users_add'] = array(
   
        array(form => 'text', caption => 'Логин ', status => 'X', name => 'login', value => ''),
        array(form => 'text', caption => 'Пароль ', status => 'X', name => 'passwd', value => ''),
        array(form => 'text', caption => 'Имя', status => 'X', name => 'name', value => ''),
        array(form => 'text', caption => 'E-mail', status => 'X', name => 'email', value => ''),
        array(form => 'text', caption => 'ICQ', status => '0', name => 'icq', value => ''),
        array(form => 'text', caption => 'Jabber', status => '0', name => 'jabber', value => ''),
        array(form => 'text', caption => 'Skype', status => '0', name => 'skype', value => ''),
        array(form => 'checkbox', caption => 'Статус', status => '0', name => 'status', value => 1, checked => '1'),
        //array(form => 'date_form', caption => 'Дата регистрации', status => '0', name => 'date_reg', value => ''),
        array(form => 'select_simple', caption => 'Тип пользователя', status => '0', name => 'tip_user',  values => array('user'=>'user', 'operator'=>'operator', 'admin'=>'admin'), value => $_POST['tip_user']),
        array(form => 'select_simple', caption => 'Способ рассылки сообщений', status => '0', name => 'note_method',values => array('email'=>'email', 'jabber'=>'jabber', 'icq'=>'icq'), value => $_POST['tip_user']),

        );
?>