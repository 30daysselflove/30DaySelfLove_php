<?php /* Smarty version Smarty-3.1.11, created on 2013-12-22 20:59:58
         compiled from "..\application\view\httpstatus\default.tpl" */ ?>
<?php /*%%SmartyHeaderCode:319525167618aed0143-04787449%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9c123ea77ce4e39e5182e71a4c8b38a3aaa16737' => 
    array (
      0 => '..\\application\\view\\httpstatus\\default.tpl',
      1 => 1387767655,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '319525167618aed0143-04787449',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5167618b06c6e0_10521446',
  'variables' => 
  array (
    'statusCode' => 0,
    'statusMessage' => 0,
    'v' => 0,
    'k1' => 0,
    'v1' => 0,
    'k' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5167618b06c6e0_10521446')) {function content_5167618b06c6e0_10521446($_smarty_tpl) {?><!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<!--
 index.html
 NewApplication

 Created by You on February 22, 2011.
 Copyright 2011, Your Company All rights reserved.
-->
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7, chrome=1" />

        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />

        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-status-bar-style" content="black" />

        <link rel="apple-touch-icon" href="Resources/icon.png" />
        <link rel="apple-touch-startup-image" href="Resources/default.png" />
		
        <title>PhantomTweets.com - Woops! <?php echo $_smarty_tpl->tpl_vars['statusCode']->value;?>
 Error</title>
    
        <style type="text/css">

    *{margin:0;padding:0}
    html,code{font:15px/22px arial,sans-serif}
    html{background:#fff;color:#222;padding:15px}
    body{margin:40px auto 0 auto; max-width:750px;min-height:180px;padding:30px 0 15px}
    
    p{margin:11px 0 22px;overflow:hidden}
    ins{color:#777;text-decoration:none}
    a img{border:0}

    h1 {
        line-height: 1.1;
        font-size: 16px;
    }

    ul
    {
        padding-left: 20px;
    }

    #tech
    {
        margin-top:20px;
        display: block;
        padding: 45px;
        background-color: #F7F7F7;
        font-size: 12px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }

    a {
        color:#507786;
        text-decoration: none;
    }

        a:hover {
        text-decoration: underline;
    }



        </style>
    
    </head>

    <body>
    <img src="/www/img/phantom-logo.jpg" alt="Error"></a>
    <p><b><?php echo $_smarty_tpl->tpl_vars['statusCode']->value;?>
.</b> <ins><?php if ($_smarty_tpl->tpl_vars['statusCode']->value<500||$_smarty_tpl->tpl_vars['statusCode']->value>399){?>There was a problem processing your request.<?php }else{ ?>There was an error.<?php }?></ins></p>
    <p>Error : <?php echo $_smarty_tpl->tpl_vars['statusMessage']->value['message'];?>
.<br/><ins><?php if ($_smarty_tpl->tpl_vars['statusCode']->value<500||$_smarty_tpl->tpl_vars['statusCode']->value>399){?>Please alter the parameters of your request.<?php }else{ ?>We were notified of the error and are working on correcting it.<?php }?></ins>
    </p>

    <a href="#" id="showtech">Show technical details</a>
    <div style="display:none" id="tech">
        
    <h1><?php echo $_smarty_tpl->tpl_vars['statusCode']->value;?>
 - <?php echo $_smarty_tpl->tpl_vars['statusMessage']->value['message'];?>
</h1>
        <ul>
    	<?php  $_smarty_tpl->tpl_vars['v'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v']->_loop = false;
 $_smarty_tpl->tpl_vars['k'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['statusMessage']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v']->key => $_smarty_tpl->tpl_vars['v']->value){
$_smarty_tpl->tpl_vars['v']->_loop = true;
 $_smarty_tpl->tpl_vars['k']->value = $_smarty_tpl->tpl_vars['v']->key;
?>
    		<?php if (is_array($_smarty_tpl->tpl_vars['v']->value)){?>
    		
    		<?php  $_smarty_tpl->tpl_vars['v1'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['v1']->_loop = false;
 $_smarty_tpl->tpl_vars['k1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['v']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['v1']->key => $_smarty_tpl->tpl_vars['v1']->value){
$_smarty_tpl->tpl_vars['v1']->_loop = true;
 $_smarty_tpl->tpl_vars['k1']->value = $_smarty_tpl->tpl_vars['v1']->key;
?>
    		<li><?php echo $_smarty_tpl->tpl_vars['k1']->value;?>
: <?php echo $_smarty_tpl->tpl_vars['v1']->value;?>
</li>
    		<?php } ?>


    		<?php }else{ ?>
    		<li><?php echo $_smarty_tpl->tpl_vars['k']->value;?>
: <?php echo $_smarty_tpl->tpl_vars['v']->value;?>
</li>
    		<?php }?>
    	   
    	<?php } ?>
    	</ul>
    </div>
    
    </body>

</html>


<!--
{extends file='$smarty.const.SMARTY_COMMON_BASE_PATH}/wrapper.tpl'}


{block name=pagetitle}
    Blizzfull - Woops! {$statusCode} Error
{/block}

   

{block name=pagecontent}
    <h1>{$statusCode} - {$statusMessage['message']}</h1>
    <ul>
    {foreach from=$statusMessage key=k item=v}
        {if is_array($v)}
        
        {foreach from=$v key=k1 item=v1}
        <li>{$k1}: {$v1}</li>
        {/foreach}
    
        {else}
        <li>{$k}: {$v}</li>
        {/if}
       
    {/foreach}
    </ul>
{/block}
-->

<script type="text/javascript">
        document.getElementById('showtech').onclick = function(e){
          document.getElementById('tech').style.display = "block";
          e.preventDefault();
          return false;
        }
</script>






<?php }} ?>