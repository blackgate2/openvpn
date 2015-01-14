<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<head>
    <TITLE><?= $content['name'] ?> - Openvpn.ru </TITLE>
    <META name="keywords" content="<?= $content['keywords'] ?>">
    <META name="description" content="<?= $content['description'] ?>">
    <META http-equiv="Content-Style-Type" content="text/css">

    <link rel="icon" href="/images/favicon.ico" type="image/x-icon">
    <link type="text/css" href="/css/style.css" rel="stylesheet" />
    <link type="text/css" href="/css/menu.css" rel="stylesheet"/>
    

    <!-- Load jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
    <link type="text/css" href="/css/ui-lightness/jquery-ui-1.8rc3.custom.css" rel="stylesheet" />
    <script type="text/javascript" src="/js/launch.js" language="JavaScript"></script>
    
    
    <!-- Load Lightbox  -->
    <script src="/js/jquery.lightbox-0.5.pack.js" type="text/javascript"></script>
    <link media="screen" href="/css/jquery.lightbox-0.5.css" type="text/css" rel="stylesheet">
    
    <script src="/js/menu.js"></script>
    <script src="/js/safe-standard.js"></script>
    <script type="text/javascript" src="/js/misc.js"></script>
    <script type="text/javascript" src="/js/login.js.php"></script>

    <?= $content['js'] ?>
    <?= $content['css'] ?>
<? if (!$is_right){?>
  <style>  
    div#left{
        width:750px;
    }
 </style>

<? }?>
</head>

<body>
     <div id="header">
        <div id="top" class="plashka"> 
                <div id="content_top">
                    <div id="lang">
                        <?= $nav->str_lang?>
                    </div>
                    <div id="auth">
                        <?= $str_login ?>
                    </div>

                    <div id="logo"><a href="/"> <img src="/images/<?=$nav->pre?>logo.png"></a></div>

                    <div id="menu" <?= ($is_login)?'':'' ?>>
                        <ul class="myMenu">
                             <?= $str_main_menu ?>
                        </ul>

                    </div>
                </div>
        </div>

        <div id="<?=(!$nav->page)?'middle_bg':'middle_bg_20'?>">

            <? if (!$nav->page){?>
            <div id="middle">
            <div id="contact_top" class="plashka opa">
                    <h3><?=($nav->lang=='ru')?'Контакты':'Contacts' ?></h3>
                    <table>
                    <tr><td><img src="http://status.icq.com/online.gif?icq=244436&img=5" class="icq_shadow"></td><td>244-436</td></tr>
                    <tr><td><img src="http://status.icq.com/online.gif?icq=586534216&img=5"  class="icq_shadow"></td><td>586-534-216</td></tr>
                    <tr><td><img alt="jabber" src="/images/jabber.png"></td><td> openvpn@jabber.ru</td></tr>
                    <tr><td><img alt="jabber" src="/images/email.png"></td><td> <a href="mailto:support@openvpn.ru">support@openvpn.ru</a></td></tr>
                    </table>
            </div>
            </div>
            <? }?>
        </div>
    </div>
    
    <div id="content">
        
        <? if ($is_right){?>
        <div id="right">
            <div  class="block2">
                        <?= $content['content_front_news'] ?>
            </div>
            <br>
            <div class="block1  plashka">
               
               <h3><?=($nav->lang=='ru')?'Мы атестованы':'We accept' ?></h3>
               <div>
                   <a href="https://passport.webmoney.ru/asp/certview.asp?wmid=933039972653" mce_href="https://passport.webmoney.ru/asp/certview.asp?wmid=933039972653" target="_blank">
                       <img src="http://www.webmoney.ru/img/icons/88x31_wm_v_blue_on_white_ru.png" mce_src="http://www.webmoney.ru/img/icons/88x31_wm_v_blue_on_white_ru.png" title="Здесь находится аттестат нашего WM идентификатора 933039972653" border="0">
                   </a> 
                   </div>
                   <div>
                   <img src="http://www.webmoney.ru/img/icons/88x31_wm_blue_on_transparent_en.png" mce_src="http://www.webmoney.ru/img/icons/88x31_wm_blue_on_transparent_en.png" border="0">&nbsp; <a href="https://passport.webmoney.ru/asp/certview.asp?wmid=933039972653" mce_href="https://passport.webmoney.ru/asp/certview.asp?wmid=933039972653" target="_blank"><br><span style="font-size: xx-small;" mce_style="font-size: xx-small;">Check the certificate</span></a>
                   </div>
                <h3>Online support</h3>
                <div>
                    <a href="#" onclick="psf1zsow(); return false;"><img name="psf1zsimage" src="http://www.providesupport.com/resource/cp6x59/default/company/image/chat-icon/1/chat-icon-1-online-en.gif" border="0"></a>
                 </div>
            </div>
            <div class="block2">            
            <?= $content['content_front_faq'] ?>
            </div>
            
        </div>
        <? }?>
        <div id="left">
                <?=($content['name'])? '<h1>'.$content['name'].'</h1>':'' ?>
                <?= $content['content_page'] ?>
        </div>


    </div>
    <div id="bottom">
        <div id="bottom_content">
            <div class="bot_col1">
                <h3><?=($nav->lang=='ru')?'Контакты':'Contacts' ?></h3>
                <table style="margin:0 0 0 -3px;">
                <tr><td><img src="http://status.icq.com/online.gif?icq=244436&amp;img=5" class="icq_shadow"></td><td>244-436</td></tr>
                <tr><td><img src="http://status.icq.com/online.gif?icq=586534216&amp;img=5"  class="icq_shadow"></td><td>586-534-216</td></tr>
                <tr><td><img alt="jabber" src="/images/jabber.png"></td><td> openvpn@jabber.ru</td></tr>
                <tr><td><img alt="jabber" src="/images/email.png"></td><td> <a href="mailto:support@openvpn.ru">support@openvpn.ru</a></td></tr>
                </table>
            </div>
            <div class="bot_col2">
                <h3><?=($nav->lang=='ru')?'Мы принимаем':'We accept' ?></h3>
                <p><img src="/images/visa_master.png">
                <br><br><img src="/images/buttons_paymant.png">
                </p>
            </div>
            <div class="bot_col3">
                <h3><?=($nav->lang=='ru')?'Меню':'Menu' ?></h3> 
                <ul><?=$str_bottom_menu?></ul>
            </div>
           <div class="bot_copy"><span>© 2009, OpenVPN.RU</span></div>
        </div> 
    </div>



</body>
</html>
