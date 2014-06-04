<?php

$admin_menu = array(
    array(
        fold => false,
        title => 'Меню',
        href => 'index.php',
        m => 'main',
        action => 'show',
        table => 'menu'
    ),
    array(
        fold => false,
        title => 'Странички',
        href => 'index.php',
        m => 'main',
        action => 'show',
        table => 'pages'
    ),
    array(
        fold => true, title => 'Вопросы',
        childs => array(
            array(
                fold => false,
                title => 'Вопросы',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'faq'
            ),
            array(
                fold => false,
                title => 'Группы',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'faq_group', 
             ), 
         )  
    ),

    array(
        fold => false,
        title => 'Новости',
        href => 'index.php',
        m => 'main',
        action => 'show',
        table => 'news'
    ),
    array(
        fold => false,
        title => 'Заказы',
        href => 'index.php',
        m => 'main',
        action => 'show',
        table => 'orders',
    ),

    array(
        fold => true, title => 'Пользователи',
        childs => array(
            array(
                fold => false,
                title => 'Пользователи',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'users'
            ),
            array(
                fold => false,
                title => 'Группы',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'user_groups'
            ),
          ),
    ),
    array(
        fold => true, title => 'Логи',
        childs => array(
            array(
                fold => false,
                title => 'Аккаунты',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'accounts'
            ),
            array(
                fold => false,
                title => 'Лог рассылки',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'log_mess', 
             ), 
            array(
                fold => false,
                title => 'Лог команд',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'log_lounch_script', 
             ), 
            array(
                fold => false,
                title => 'Лог пользователей',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'activitylog', 
             ), 
         )  
    ),  
    
    array(
        fold => true, title => 'Справочники',
        childs => array(
            array(
                fold => false,
                title => 'Правила создания Zip',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'config_rules', 
             ),
            array(
                fold => false,
                title => 'Сообщения',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'user_messages', 
             ),
            array(
                fold => false,
                title => 'Команды',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'commads'
            ),
            array(
                fold => false,
                title => 'Страны',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'countries'
            ),
            array(
                fold => false,
                title => 'Цены',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'prices',
            ),
            array(
                fold => false,
                title => 'Типы VPN',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'types',
            ),
            array(
                fold => false,
                title => 'Протоколы',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'protocols',
            ),
            array(
                fold => false,
                title => 'Периоды',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'periods',
            ),
            array(
                fold => false,
                title => 'Сервера',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'servers',
            ),
           array(
                fold => false,
                title => 'Список DNS',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'dns',
            ),
            array(
                fold => false,
                title => 'Статусы заказа',
                href => 'index.php',
                m => 'main',
                action => 'show',
                table => 'actions',
            ),
        )
    ),
//    array(
//        fold => true, title => 'Специалисты',
//        childs => array(
//            array(
//                fold => false,
//                title => 'Специалисты',
//                href => 'index.php',
//                m => 'main',
//                action => 'show',
//                table => 'spec'
//            ),
////            array(
////                fold => false,
////                title => 'Сертификаты',
////                href => 'index.php',
////                m => 'main',
////                action => 'show',
////                table => 'sert'
////            ),
//            array(
//                fold => false,
//                title => 'Группы специалистов',
//                href => 'index.php',
//                m => 'main',
//                action => 'show',
//                table => 'spec_group',
//            ),
//        )
//    ),
//
//    array(
//        fold => true, title => 'Видео',
//        childs => array(
//            array(
//                fold => false,
//                title => 'Видеоальбом',
//                href => 'index.php',
//                m => 'main',
//                action => 'show',
//                table => 'video'
//            ),
//            array(
//                fold => false,
//                title => 'Группы',
//                href => 'index.php',
//                m => 'main',
//                action => 'show',
//                table => 'video_group',
//            ),
//        )
//    ),
//
//    array(
//        fold => true, title => 'Записи',
//        childs => array(
//            array(
//                fold => false,
//                title => 'Записи',
//                href => 'index.php',
//                m => 'main',
//                action => 'show',
//                table => 'writing_on'
//            ),
//            array(
//                fold => false,
//                title => 'Группы',
//                href => 'index.php',
//                m => 'main',
//                action => 'show',
//                table => 'writing_on_group',
//            ),
//        )
//    ),
//
//
);
?>