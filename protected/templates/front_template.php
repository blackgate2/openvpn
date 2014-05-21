<!DOCTYPE Html PUBLIC "-//W3C//DTD Html 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <TITLE> <?= $content['name'] ?></TITLE>
        <META name="keywords" content="<?= $content['keywords'] ?>">
        <META name="description" content="<?= $content['description'] ?>">
        <META http-equiv="Content-Style-Type" content="text/css">
        <META http-equiv="Content-Type" content="text/html; charset=windows-1251">
        
        <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon" />
        <LINK rel="stylesheet" href="/css/style_front_container.css" type="text/css"/>
        <LINK rel="stylesheet" href="/css/style.css" type="text/css"/>
        <LINK rel="stylesheet" href="/css/menu.css" type="text/css"/>


        <script src="/js/jquery-1.7.2.min.js"></script>
        <script type="text/javascript" src="/js/menu.js"></script>

        <link rel="stylesheet" href="/css/panning-slideshow.css" type="text/css"/>
        <script src="/js/jquery.easing.1.3.js"></script>

        <script type="text/javascript" src="/js/image-rotator_<?= $nav->pre ?>.js"></script>

        <link rel="stylesheet" type="text/css" href="/css/skins/tango/skin.css" />
        <script type="text/javascript" src="/js/jquery.jcarousel.min.js"></script>


        <script>
            $(function(){
                $('#mycarousel').jcarousel();
                $(".jcarousel-skin-tango li img").mouseover(function() {
                    $(this).addClass("active");
                });
                $(".jcarousel-skin-tango li img").mouseout(function() {
                    $(this).removeClass("active");
                                        
                });
            });



        </script>
    </head>
    <body>
        <div id="maket">
            <div id="contener_top">
                <div id="lang">
                    <?= $str_lang ?>
                </div>
                <a href="/<?= $nav->lang ?>"><img src="/images/logo_<?= $nav->lang ?>.png" id="logo"></a>
                <div id="menu"><div>
                        <ul class="myMenu">
                            <?= $str_main_menu ?>
                        </ul>

                    </div></div>
                <img src="/images/<?= $nav->pre ?>tel.png" id="tel">

            </div>
            <div id="contener_middle">
                <div style="width: 366px; float: left;">
                    <img src="/images/slogan.png">
                    <div id="slogan_wrap"><div id="slogan">                         
                        </div>
                    </div>
                    <div id="nav">                      
                    </div>
                </div>
                <div style="width: 538px; float: left; position: relative;top:20px; left: 12px">

                    <div id="imgs">
                        <ul id="slideshow">
                            <li class="box1"><img src="/images/main_img.png"/></li>
                            <li class="box2"><img src="/images/main_img1.png"/></li>
                            <li class="box3"><img src="/images/main_img2.png"/></li>
                            <li class="box3"><img src="/images/main_img3.png"/></li>
                        </ul>
                    </div>
                    <div id="imgs_bg"></div>


                </div>

            </div>
        </div>
        <div id="bottom"><div id="bottom_elem">
                <div id="content_bottom">
                    <div id="news">
                        <?= $content['content_front_news'] ?>
                        </div>
                        <div id="partners">
                        <?= $content['content_front_partners'] ?>
                        </div>
                        <div id="bot_soc">
                            <div class="share42init"></div>
                            <script type="text/javascript" src="/js/share42.js"></script>
                            <script>share42('/images/', 110)</script>

                        </div>
                        <div id="bot_copy">
                        <?= ($nav->lang == 'en') ? '©2012 «RB INVEST» LTD' : '©2012 ООО «РБ ИНВЕСТ»' ?>
                    </div>
                </div>
            </div></div>
        
    </body>
</html>
