<?php /* Smarty version Smarty-3.1.11, created on 2014-01-17 00:16:54
         compiled from "..\application\view\admin\login.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2906152c8d09742d727-55630511%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '5ae06c1eb69f71e1c98ee3fd1fe1288262cd61c6' => 
    array (
      0 => '..\\application\\view\\admin\\login.tpl',
      1 => 1389946605,
      2 => 'file',
    ),
    '08f8bf00ba24f0a12ea3b245f4989a06f598fecd' => 
    array (
      0 => '..\\application\\view\\admin\\wrapper.tpl',
      1 => 1389946605,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '2906152c8d09742d727-55630511',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_52c8d097594d82_67639313',
  'variables' => 
  array (
    'pathRoot' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52c8d097594d82_67639313')) {function content_52c8d097594d82_67639313($_smarty_tpl) {?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Fopnt Spy Administration</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">



    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- google webfonts -->
    <link href='http://fonts.googleapis.com/css?family=Ropa+Sans' rel='stylesheet' type='text/css'>

    <link rel="stylesheet" type="text/css" href="/www/css/bootstrap.min.css">

    <script src="<?php echo $_smarty_tpl->tpl_vars['pathRoot']->value;?>
/js/libs/jquery/jquery.min.js"></script>
    <script src="<?php echo $_smarty_tpl->tpl_vars['pathRoot']->value;?>
/js/login.js"></script>

    <script src="<?php echo $_smarty_tpl->tpl_vars['pathRoot']->value;?>
/js/libs/validation/jquery.validate.min.js"></script>


<body>


<style type="text/css">

    #errorText, #loggingInText{
        display:none;
        margin-top:20px;
    }

    #login_button{
        margin-left: 70px;
        width:90px;
        margin-top: 20px;
    }

    .center
    {
        text-align: center;
    }

    h1{
        font-size: 17px;
        font-weight: normal;
        color: #90AEC6;
        margin-top: 0;
    }


    body{
        color: white;
        font-family: arial, helvetica, sans-serif;
        width:300px;
        height:500px;
        position:absolute;
        left:50%;
        top:50%;
        margin:-200px 0 0 -150px;
        background-color:#213240;
    }

    #loginSection
    {

        color: black;
        background-color: #fff;
        -webkit-border-radius: 6px;
        -moz-border-radius: 6px;
        border-radius: 6px;
        padding:35px;
        text-align: left;
    }

    #loginSection input[type=text], #loginSection input[type=password]{
        border-radius: 3px 3px 3px 3px;
        -moz-border-radius: 3px 3px 3px 3px;
        -webkit-border-radius: 3px 3px 3px 3px;
        background-color: #ffffff;
        margin: 0;
        display: block;
        border: none;
        height: 30px;
        padding: 0 10px 0 10px;
        width: 210px;
        font-size: 15px;
        border:1px solid #d8d8d8;
        margin-bottom: 20px;
        margin-top: 3px;
    }

    #loginSection input[type=submit]{
        display: block;
        border-radius: 3px 3px 3px 3px;
        -moz-border-radius: 3px 3px 3px 3px;
        -webkit-border-radius: 3px 3px 3px 3px;
        background-color: #ec1559;
        margin: 0;
        width: 90px;
        height: 30px;
        line-height: 30px;
        font-size: 14px;
        color: #ffffff;
        cursor: pointer;
        -webkit-transition: background-color 500ms linear;
        -moz-transition: background-color 500ms linear;
        -o-transition: background-color 500ms linear;
        -ms-transition: background-color 500ms linear;
        transition: background-color 500ms linear;
        text-align: center;
        border: none;
        margin-left: auto;
        margin-right: auto;
        margin-top: 20px;
    }

    #subSection{
        margin-top: 20px;
    }



</style>


<div>

    <div  class="center">
        <div><img src="/admin/img/logo.png" /></div>
        <h1>Administrative Backend</h1> </div>

    <div id="loginSection" style="margin-top: 35px">

        <form id="login">
            <label for="username">Username</label>
            <input class="input-large form-control" id="username" type="text">

            <label for="password">Password</label>
            <input class="input-large form-control" id="password" type="password">

            <div class="alert alert-error alert-warning" id="errorText">
                Error Logging in
            </div>

            <div class="alert alert-info" id="loggingInText">
                Logging in...
            </div>

            <input class="loginButton btn form-control btn-default" id="login_button" type="submit" value="Submit">
        </form>
    </div>


    <div id="subSection" class="center">
    </div>


    <link rel="stylesheet" type="text/css" href="/www/css/login.css">

</div> <!-- /container -->






</div>

</body>
</html><?php }} ?>