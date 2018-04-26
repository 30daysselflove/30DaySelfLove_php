<?php /* Smarty version Smarty-3.1.11, created on 2014-06-13 22:25:31
         compiled from "..\application\view\www\prime.tpl" */ ?>
<?php /*%%SmartyHeaderCode:21841530e4d685f8ec0-06527242%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'f0e021eea38843602b902857452db3f04053881c' => 
    array (
      0 => '..\\application\\view\\www\\prime.tpl',
      1 => 1402723514,
      2 => 'file',
    ),
    '5c20365d03d8fac853cc67b3a73a107dabef6067' => 
    array (
      0 => '..\\application\\view\\www\\wrapper.tpl',
      1 => 1402723389,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '21841530e4d685f8ec0-06527242',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_530e4d687be130_90129403',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_530e4d687be130_90129403')) {function content_530e4d687be130_90129403($_smarty_tpl) {?>
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


    
    <link rel="stylesheet" type="text/css" href="/www/css/home.css">


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

    
    <!--
    <div class="header clearfix">
        <div class="pull-left"><a href="#">About FontSpy</a></div>
        <div class="pull-right"><a href="#" class="space-right">Log In</a> <a href="#">Sign Up</a></div>
    </div>
    -->

    <div class="search clearfix">
        <img src="/www/img/logo.png" />

        <div class="clearfix">
            <form id="home-search" action="/search" method="GET">
                <input name="query" type="text" class="search-input" placeholder='Search for fonts' />
                <input name="render" type="text" class="render-input" placeholder='Text to use to render font' />
                <input type="submit" class="search-submit" value="SEARCH">
            </form>
        </div>

    </div>



    <div class="center-menu">
        <a href="/search">BROWSE ALL FONTS</a>
    </div>

    <div class="copyright">
        &copy; 2013 FontSpy.com. All Rights Reserved.
    </div>


</body>

<script src="/www/js/models/FontFamilyModel.js"></script>
<script src="/www/js/collections/FontFamiliesCollection.js"></script>

    <script src="/www/js/views/MainView.js"></script>

<script src="/www/js/app.js"></script>

</html><?php }} ?>