<?php /* Smarty version Smarty-3.1.11, created on 2013-08-20 04:42:39
         compiled from "..\application\view\emails\email-new.tpl" */ ?>
<?php /*%%SmartyHeaderCode:99715199a355743a97-41330136%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'd259d3fcb70a1ffa0f2a936bada27d3213ba9c46' => 
    array (
      0 => '..\\application\\view\\emails\\email-new.tpl',
      1 => 1376996395,
      2 => 'file',
    ),
    '4f90c86092eda1171837c17691dc6faabbb02e79' => 
    array (
      0 => '..\\application\\view\\emails\\email-default.tpl',
      1 => 1376951597,
      2 => 'file',
    ),
    '3a98cca30223c2c0aa47734b56bd59e4be759fc9' => 
    array (
      0 => '..\\application\\view\\emails\\wrapper.tpl',
      1 => 1376951573,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '99715199a355743a97-41330136',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_5199a3558295b3_07267118',
  'variables' => 
  array (
    'baseDomain' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_5199a3558295b3_07267118')) {function content_5199a3558295b3_07267118($_smarty_tpl) {?><html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <!-- Facebook sharing information tags -->
        <meta property="og:title" content="An account has been created for you on Phantomtweets.com" />
        <title>Anaccount has been created for you on Phantomtweets.com</title>
    </head>
    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="width: 100% !important;-webkit-text-size-adjust: none;margin: 0;padding: 0; font-family: Arial">
        <center>
            <table border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="backgroundTable" style="height: 100% !important;margin: 0;padding: 0;width: 100% !important;font-family: Arial">
                <tr>
                    <td align="center" valign="top" style="border-collapse: collapse">
                        <!-- Begin Template Preheader -->
                        <!--  End Template Preheader  -->
                        <table border="0" cellpadding="0" cellspacing="0" width="650" id="templateContainer">
                            <tr>
                                <td align="center" valign="top" style="border-collapse: collapse">
                                    <!--  Begin Template Body  -->
                                    <table border="0" cellpadding="0" cellspacing="0" width="600" id="templateBody">
                                        <tr>
                                            <td valign="top" class="bodyContent" style="border-collapse: collapse;background-color: #FFF">
                                                
<table border="0" cellpadding="0" cellspacing="0" width="100%">

    <tr>
        <td valign="top" id="textTD" style=" padding:35px 20px 10px 20px;">
            <div mc:edit="std_content00">
                <img src="http://www.phantomtweets.com/www/img/phantom-logo.jpg">
                <div style="color:#202020;
                                display:block;
                                font-family:Arial, Helvetica, sans-serif;
                                font-size:20px;
                                font-weight:bold;
                                line-height:1.4em;
                                margin-top:0;
                                margin-right:0;
                                margin-bottom:10px;
                                margin-left:0;
                                text-align:left;" class="h4">
                    
                    
                </div>
                <p style="color:#202020; font-size:16px;font-weight: bold;">
                    An account has been created for you on Phantomtweets.com.
                </p>
                <div id="personalMessage" style="border-top:1px solid #cecece;
                                                    border-bottom:1px solid #cecece;
                                                    padding:20px 0;">
                    <p class="message" style="margin:0 0 4px 0;
                                                padding:0;">
                        <a href="<?php echo $_smarty_tpl->tpl_vars['link']->value;?>
">Click to set your password and get started right away</a>
                    </p>
                    <p class="signed" style="margin:0;">
                        
                    </p>
                </div>
                
                
            </div>
        </td>

        
    </tr>
</table>
            
                                            </td>
                                        </tr>
                                    </table>
                                    <!--  End Template Body  -->
                                </td>
                            </tr>
                        </table>
                        <div id="footer" style="font-size: 11px;padding-top: 10px;color: #7d7d7d;width: 650px;margin: 0 auto">
                            <div>
                                Copyright © 2013 <a style=" color:#7d7d7d;" href="<?php echo $_smarty_tpl->tpl_vars['baseDomain']->value;?>
">PhantomTweets.com</a>, All rights reserved.
                            </div>
                        </div>
                        <br />
                    </td>
                </tr>
            </table>
        </center>
    </body>
</html><?php }} ?>