<?php /* Smarty version Smarty-3.1.11, created on 2014-06-13 22:30:18
         compiled from "..\application\view\www\search.tpl" */ ?>
<?php /*%%SmartyHeaderCode:3968530e4d6ecb8ed9-28012537%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cb3f0f7ca2d32d97746e955edb22aa3d47c10449' => 
    array (
      0 => '..\\application\\view\\www\\search.tpl',
      1 => 1402723780,
      2 => 'file',
    ),
    '5c20365d03d8fac853cc67b3a73a107dabef6067' => 
    array (
      0 => '..\\application\\view\\www\\wrapper.tpl',
      1 => 1402723389,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '3968530e4d6ecb8ed9-28012537',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_530e4d6ed32063_64737660',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_530e4d6ed32063_64737660')) {function content_530e4d6ed32063_64737660($_smarty_tpl) {?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Font Spy</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- google webfonts -->
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" type="text/css" href="/www/css/main.css">


    
    <link rel="stylesheet" type="text/css" href="/www/css/search.css">


    <script src="/www/js/lib/jquery-1.10.2.min.js"></script>
    <script src="/www/js/lib/backbone/underscore.js"></script>
    <script src="/www/js/lib/backbone/backbone-min.js"></script>
    <script src="/www/js/lib/rivets/rivets.min.js"></script>
    <script src="/www/js/lib/moment.min.js"></script>


    
    <!-- Global variables -->
    <script type="text/javascript">
        var myApp = {};

        var SERVER_CONSTANTS = {
            user : <?php echo (($tmp = @$_smarty_tpl->tpl_vars['user']->value)===null||$tmp==='' ? '""' : $tmp);?>

        };
    </script>


<body>

    
    <div class="header clearfix">
        <div class="wrapper">
            <img src="/www/img/logo-small.png" />

            <div class="search clearfix">
                <input type="text" class="search-input" placeholder='Search for fonts' />
                <a class="search-submit">SEARCH</a>
            </div>

            <div class="user-nav"><a href="#" class="space-right">Log In</a> <a href="#">Sign Up</a></div>
        </div>
    </div>

    <div class="nav-bar">
        <div class="wrapper">
            <ul class="options">
                <li>
                    <a class="trigger-popover" href="#font-styles">Font Styles <span class="nib"></span></a>
                </li>
                <li>
                    <a class="trigger-popover" href="#font-categories">Categories <span class="nib"></span></a>
                </li>
                <li>
                    <a class="trigger-popover" href="#font-licenses">Licenses <span class="nib"></span></a>
                </li>
                <li>
                    <div class="sample-text">Sample Text <input type="text" class="render-input" placeholder='Text to use to render font' /></div>
                </li>
                <li class="last-item">
                    <img src="/www/img/small-font-icon.png" class="pull-left">
                    <div class="slider-box pull-left">
                        <input type="text" data-slider="true" data-slider-range="8,240" data-slider-step="1" />
                    </div>
                    <img src="/www/img/large-font-icon.png" class="pull-right">
                </li>

            </ul>
        </div>
    </div>

    <div class="content">
        <div class="wrapper">

            <!-- BEGIN FONT LISTINGS -->

            <div class="font-family" data-each-font="collections:collection" data-model-id="font:id">
                <div class="summary">
                    <div class="name">
                        <a class="font-name" href="">{ font:name }</a> by <a href="#">{ font:author }</a>
                    </div>
                    <div class="preview">
                        <img data-fontname="font:name" data-src="font|baseFontListRenderFormat 32">
                    </div>
                    <div class="count">
                        <span>{ font:fontCount} Fonts</span>
                    </div>
                    <div class="action">
                        <a class="download" href="#">Download</a>
                        <p>Free for personal use</p>
                    </div>
                </div>

                <div class="details" style="display:none;">
                    <div class="close">&times;</div>
                    <ul class="tab-nav clearfix">
                        <li class="selected">Font Information</li>
                        <li>Glyphs</li>
                        <li>Similar Fonts</li>
                    </ul>
                    <div class="tab font-information selected">
                        <p class="title">About { font:name }</p>
                        <p class="description">{ font:description }</p>
                        <p class="info"><b>Author: </b> <a href="#">{ font:author }</a></p>
                        <p class="info"><b>Classification: </b> <a href="#">{ font:class }</a></p>
                        <p class="info"><b>Categories: </b> <a href="#">{ font:categories|commaSpace }</a></p>
                        <p class="info"><b>Tags: </b> <a href="#">{ font:keywords|commaSpace }</a> | <a href="#">Display</a></p>
                        <div class="fonts"><b>Fonts </b> <ul></ul>
                        </div>
                    </div>

                    <div class="tab glyphs">Glyphs</div>

                    <div class="tab glyphs">Similar Fonts</div>
                </div>
                </div>
            </div>
            <!-- END FONT LISTINGS -->

        </div>
    </div>

    <!-- POPOVERS -->
    <div id="font-styles" class="popover font-styles">
        <ul>
            <li><label><input name="all-font-styles" type="checkbox"> <img src="/www/img/small-all-styles.png" title="All Font Styles" /></label></li>
            <li><label><input name="serif-fonts" type="checkbox"> <img src="/www/img/small-serif.png" title="Serif Fonts" /></label></li>
            <li><label><input name="sans-serif-fonts" type="checkbox"> <img src="/www/img/small-sans-serif.png" title="Sans Serif Fonts" /></label></li>
            <li><label><input name="script-fonts" type="checkbox"> <img src="/www/img/small-script.png" title="Script Fonts" /></label></li>
            <!-- <li><label><input type="checkbox"> <img src="/www/img/small-monospaced.png" title="Monospaced Fonts" /></label></li> -->
            <li><label><input name="dingbat-fonts" type="checkbox"> <img src="/www/img/small-dingbats.png" title="Dingbat Fonts" /></label></li>
        </ul>
    </div>

    <div id="font-categories" class="popover font-categories">

        <ul>
            <li><label><input name="all-categories" type="checkbox"> All Categories</label></li>
            <li><label><input type="checkbox"> Monospaced</label></li>
            <li><label><input type="checkbox"> Wedding</label></li>
            <li><label><input type="checkbox"> Western</label></li>
            <li><label><input type="checkbox"> Eroded</label></li>
            <li><label><input type="checkbox"> Techno</label></li>
        </ul>

        <ul>
            <li><label><input name="all-categories" type="checkbox"> All Categories</label></li>
            <li><label><input type="checkbox"> Monospaced</label></li>
            <li><label><input type="checkbox"> Wedding</label></li>
            <li><label><input type="checkbox"> Western</label></li>
            <li><label><input type="checkbox"> Eroded</label></li>
            <li><label><input type="checkbox"> Techno</label></li>
        </ul>

        <ul>
            <li><label><input name="all-categories" type="checkbox"> All Categories</label></li>
            <li><label><input type="checkbox"> Monospaced</label></li>
            <li><label><input type="checkbox"> Wedding</label></li>
            <li><label><input type="checkbox"> Western</label></li>
            <li><label><input type="checkbox"> Eroded</label></li>
            <li><label><input type="checkbox"> Techno</label></li>
        </ul>

    </div>

    <div id="font-licenses" class="popover font-licenses">
        <ul>
            <li><label><input name="license-free" type="checkbox"> Free <span>Fonts that are free to use for personal and commercial use. </span></label></li>
            <li><label><input name="license-free-personal" type="checkbox"> Free for Personal Use Only<span>Fonts that are free for personal use only. Author may provide a way to use the fonts commercially.</span></label></li>
            <li><label><input name="license-commercial" type="checkbox"> Commercial <span>Professional fonts which can be purchased for personal and commercial use.</span></label></li>
        </ul>
    </div>
    <!-- END POPOVERS -->

    <div class="content footer">
        <div class="wrapper">
            &copy 2014 FontSpy.com. Font Spy is a community managed font search engine. Fonts displayed on this site are the sole property of the authors. <br/>The license for each font differs, so be sure to verify the license information with the author before using any font referenced on this site.
        </div>
    </div>



</body>

<script src="/www/js/models/FontFamilyModel.js"></script>
<script src="/www/js/collections/FontFamiliesCollection.js"></script>

    <script src="/www/js/lib/jquery/simple-slider.min.js"></script>
    <script src="/www/js/lib/jquery/jquery-popover.min.js"></script>
    <script src="/www/js/lib/jquery/fontspy-search-tabs.js"></script>
    <script src="/www/js/models/FontModel.js"></script>
    <script src="/www/js/collections/FontsCollection.js"></script>
    <script src="/www/js/views/SearchView.js"></script>
    <script type="text/javascript">
        $(function()
        {
            searchView = new SearchView();
            searchView.render();
        });

    </script>

<script src="/www/js/app.js"></script>

</html><?php }} ?>