<?php /* Smarty version Smarty-3.1.11, created on 2015-06-01 00:51:52
         compiled from "../application/view/emails/email-reset.tpl" */ ?>
<?php /*%%SmartyHeaderCode:113328532155518611c3d8c6-62379631%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'eea1c4f848d2a94e3873e3f04b448bf0fde7fef8' => 
    array (
      0 => '../application/view/emails/email-reset.tpl',
      1 => 1430866767,
      2 => 'file',
    ),
    '5462dd5439ec0a3aea44dafba513cf80fa11b376' => 
    array (
      0 => '../application/view/emails/user-email-wrapper.tpl',
      1 => 1433145082,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '113328532155518611c3d8c6-62379631',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_55518611d0bb02_27041767',
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_55518611d0bb02_27041767')) {function content_55518611d0bb02_27041767($_smarty_tpl) {?>
<head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
    <title>Notify</title>

    <style type="text/css">

        div, p, a, li, td { -webkit-text-size-adjust:none; }

        *{
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        .ReadMsgBody
        {width: 100%; background-color: #ffffff;}
        .ExternalClass
        {width: 100%; background-color: #ffffff;}
        body{width: 100%; height: 100%; background-color: #ffffff; margin:0; padding:0; -webkit-font-smoothing: antialiased;}
        html{width: 100%; background-color: #ffffff;}

        @font-face {
            font-family: 'proxima_novalight';src: url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-light-webfont.eot');src: url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-light-webfont.eot?#iefix') format('embedded-opentype'),url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-light-webfont.woff') format('woff'),url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-light-webfont.ttf') format('truetype');font-weight: normal;font-style: normal;}

        @font-face {
            font-family: 'proxima_nova_rgregular'; src: url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-regular-webfont.eot');src: url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-regular-webfont.eot?#iefix') format('embedded-opentype'),url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-regular-webfont.woff') format('woff'),url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-regular-webfont.ttf') format('truetype');font-weight: normal;font-style: normal;}

        @font-face {
            font-family: 'proxima_novasemibold';src: url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-semibold-webfont.eot');src: url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-semibold-webfont.eot?#iefix') format('embedded-opentype'),url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-semibold-webfont.woff') format('woff'),url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-semibold-webfont.ttf') format('truetype');font-weight: normal;font-style: normal;}

        @font-face {
            font-family: 'proxima_nova_rgbold';src: url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-bold-webfont.eot');src: url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-bold-webfont.eot?#iefix') format('embedded-opentype'),url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-bold-webfont.woff') format('woff'),url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-bold-webfont.ttf') format('truetype');font-weight: normal;font-style: normal;}

        @font-face {
            font-family: 'proxima_novablack';src: url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-black-webfont.eot');src: url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-black-webfont.eot?#iefix') format('embedded-opentype'),url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-black-webfont.woff') format('woff'),url('http://rocketway.net/themebuilder/template/templates/titan/font/proximanova-black-webfont.ttf') format('truetype');font-weight: normal;font-style: normal;}

        @font-face {font-family: 'proxima_novathin';src: url('http://rocketway.net/themebuilder/template/templates/mason/font/proximanova-thin-webfont.eot');src: url('http://rocketway.net/themebuilder/template/templates/mason/font/proximanova-thin-webfont.eot?#iefix') format('embedded-opentype'),url('http://rocketway.net/themebuilder/template/templates/mason/font/proximanova-thin-webfont.woff') format('woff'),url('http://rocketway.net/themebuilder/template/templates/mason/font/proximanova-thin-webfont.ttf') format('truetype');font-weight: normal;font-style: normal;}

        p {padding: 0!important; margin-top: 0!important; margin-right: 0!important; margin-bottom: 0!important; margin-left: 0!important; }

        .hover:hover {opacity:0.85;filter:alpha(opacity=85);}

        .image77 img {width: 77px; height: auto;}
        .avatar125 img {width: 125px; height: auto;}
        .icon61 img {width: 61px; height: auto;}
        .logo img {width: 75px; height: auto;}
        .icon18 img {width: 18px; height: auto;}

    </style>

    <!-- @media only screen and (max-width: 640px)
		   {*/
		   -->
<style type="text/css"> @media only screen and (max-width: 640px){
		body{width:auto!important;}
		table[class=full2] {width: 100%!important; clear: both; }
		table[class=mobile2] {width: 100%!important; padding-left: 20px; padding-right: 20px; clear: both; }
		table[class=fullCenter2] {width: 100%!important; text-align: center!important; clear: both; }
		td[class=fullCenter2] {width: 100%!important; text-align: center!important; clear: both; }
		td[class=pad15] {width: 100%!important; padding-left: 15px; padding-right: 15px; clear: both;}

} </style>
<!--

@media only screen and (max-width: 479px)
		   {
		   -->
    <style type="text/css"> @media only screen and (max-width: 479px){
            body{width:auto!important;}
            table[class=full2] {width: 100%!important; clear: both; }
            table[class=mobile2] {width: 100%!important; padding-left: 20px; padding-right: 20px; clear: both; }
            table[class=fullCenter2] {width: 100%!important; text-align: center!important; clear: both; }
            td[class=fullCenter2] {width: 100%!important; text-align: center!important; clear: both; }
            table[class=full] {width: 100%!important; clear: both; }
            table[class=mobile] {width: 100%!important; padding-left: 20px; padding-right: 20px; clear: both; }
            table[class=fullCenter] {width: 100%!important; text-align: center!important; clear: both; }
            td[class=fullCenter] {width: 100%!important; text-align: center!important; clear: both; }
            td[class=pad15] {width: 100%!important; padding-left: 15px; padding-right: 15px; clear: both;}
		.erase {display: none;}

        }
        } </style>

</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" style="background-color: #ffffff;">


<!-- Notification 1  -->
<div style="display: none;" id="element_044963457551784813"></div><!-- End Notification 1 -->

<!-- Notification 2  -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="full" >
<tr>
<td bgcolor="#2a8799"style="background-color: #ffffff;">


<!-- Mobile Wrapper -->
<table width="100%" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile">
<tr>
<td width="100%" height="100" align="center">

<div class="sortable_inner ui-sortable">
<table width="352" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile" object="drag-module-small">
    <tr>
        <td align="center" width="352" valign="middle">

            <table width="265" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
                <tr>
                    <td width="100%" height="30">
                        <img src="http://join.30daysselflove.com/join/images/logos/preloader-logo.png"/>
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>
<table width="352" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile" object="drag-module-small">
    <tr>
        <td align="center" width="352" valign="middle">

            <table width="265" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
                <tr>
                    <td width="100%" height="50"></td>
                </tr>
            </table>

        </td>
    </tr>
</table>

<table width="352" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile" object="drag-module-small">
    <tr>
        <td align="center" width="352" valign="middle">

            <!-- Header Text -->
            <table width="300" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
                <tr>
                    <td valign="middle" width="100%" style="text-align: center; font-family: Helvetica, Arial, sans-serif; font-size: 22px; color: rgb(255, 255, 255); line-height: 32px; font-weight: 100;"class="fullCenter" >
                        <!--[if !mso]><!--><span style="font-family: 'proxima_nova_rgregular', Helvetica; font-weight: normal;"><!--<![endif]-->Hi <?php echo $_smarty_tpl->tpl_vars['user']->value->username;?>
! <!--[if !mso]><!--></span><!--<![endif]-->


                        <!--[if !mso]><!--><span style="font-family: 'proxima_nova_rgbold', Helvetica; font-weight: normal;"><!--<![endif]--><!--[if !mso]><!--></span><!--<![endif]-->
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table width="352" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile" object="drag-module-small">
    <tr>
        <td align="center" width="352" valign="middle">

            <table width="265" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
                <tr>
                    <td width="100%" height="50"></td>
                </tr>
            </table>

        </td>
    </tr>
</table>

<table width="352" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile" bgcolor="#e85140" style="border-top-right-radius: 5px; border-top-left-radius: 5px; background-color: #1f3c55;"object="drag-module-small">
    <tr>
        <td align="center" width="352" valign="middle">

            <table width="300" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
                <tr>
                    <td width="100%" height="50"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table width="352" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile" bgcolor="#e85140"object="drag-module-small" style="background-color: #1f3c55;">
    <tr>
        <td align="center" width="352" valign="middle">

            <table width="265" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">

                <tr>
                    <td width="100%" height="50"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table width="352" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile" bgcolor="#e85140"object="drag-module-small" style="background-color: #1f3c55;">
    <tr>
        <td align="center" width="352" valign="middle">

            <table width="265" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">

                <tr>
                    <td valign="middle" width="100%" style="text-align: center; font-family: Helvetica, Arial, sans-serif; font-size: 48px; color: rgb(255, 255, 255); line-height: 44px; font-weight: bold;"class="fullCenter" >
                        <!--[if !mso]><!--><span style="font-family: 'proxima_nova_rgbold', Helvetica; font-weight: normal;"><!--<![endif]-->Reset Password<!--[if !mso]><!--></span><!--<![endif]-->
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table width="352" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile" bgcolor="#e85140"object="drag-module-small" style="background-color: #1f3c55;">
    <tr>
        <td align="center" width="352" valign="middle">

            <table width="265" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">

                <tr>
                    <td width="100%" height="30"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table width="352" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile" bgcolor="#e85140"object="drag-module-small" style="background-color: #1f3c55;">
    <tr>
        <td align="center" width="352" valign="middle">

            <table width="265" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
                <tr>
                    <td valign="middle" width="100%" style="text-align: center; font-family: Helvetica, Arial, sans-serif; font-size: 15px; color: rgb(255, 255, 255); line-height: 24px;"class="fullCenter" >
                        <!--[if !mso]><!--><span style="font-family: 'proxima_nova_rgregular', Helvetica; font-weight: normal;"><!--<![endif]--><!--[if !mso]><!-->Click the button below to reset your password.</span><!--<![endif]-->
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table width="352" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile" bgcolor="#e85140"object="drag-module-small" style="background-color: #1f3c55;">
    <tr>
        <td align="center" width="352" valign="middle">

            <table width="265" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
                <tr>
                    <td width="100%" height="40"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table width="352" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile" bgcolor="#e85140"object="drag-module-small" style="background-color: #1f3c55;">
    <tr>
        <td align="center" width="352" valign="middle">

            
    <table width="265" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
        <!----------------- Button Center ----------------->
        <tr>
            <td align="center">
                <table border="0" cellpadding="0" cellspacing="0" align="center">
                    <tr>
                        <td align="center" height="45"bgcolor="#ffffff" style="border-top-left-radius: 5px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border-bottom-left-radius: 5px; padding-left: 30px; padding-right: 30px; font-weight: bold; font-family: Helvetica, Arial, sans-serif; color: rgb(42, 42, 42); text-transform: uppercase; background-color: rgb(255, 255, 255);">
                            <!--[if !mso]><!--><span style="font-family: 'proxima_nova_rgbold', Helvetica; font-weight: normal;"><!--<![endif]-->
																<a href="<?php echo $_smarty_tpl->tpl_vars['link']->value;?>
" style="color: rgb(42, 42, 42); font-size: 15px; text-decoration: none; line-height: 34px; width: 100%;">Reset Password</a>
															<!--[if !mso]><!--></span><!--<![endif]-->
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <!----------------- End Button Center ----------------->
    </table>


        </td>
    </tr>
</table>

<table width="352" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile" bgcolor="#e85140"object="drag-module-small" style="background-color: #1f3c55;">
    <tr>
        <td align="center" width="352" valign="middle">

            <table width="265" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
                <tr>
                    <td width="100%" height="35"></td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<table width="352" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile" bgcolor="#e85140"style="border-bottom-left-radius: 5px; border-bottom-right-radius: 5px; background-color: #1f3c55;" object="drag-module-small">
    <tr>
        <td align="center" width="352" valign="middle">

            <table width="265" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
                <tr>
                    <td width="100%" height="50"></td>
                </tr>
            </table>

        </td>
    </tr>
</table>

<table width="352" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile" object="drag-module-small">
    <tr>
        <td align="center" width="352" valign="middle">

            <table width="265" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
                <tr>
                    <td width="100%" style="text-align: center; font-family: Helvetica, Arial, sans-serif; font-size: 14px; color: rgb(232, 81, 64); line-height: 24px;"class="fullCenter" >
                        <!--[if !mso]><!--><span style="font-family: 'proxima_nova_rgregular', Helvetica;"><!--<![endif]-->
                            <!--subscribe--><!--unsub--><!--[if !mso]><!-->
                        </span><!--<![endif]-->
                    </td>
                </tr>
            </table>

        </td>
    </tr>
</table>

<table width="352" border="0" cellpadding="0" cellspacing="0" align="center" class="mobile" object="drag-module-small">
    <tr>
        <td align="center" width="352" valign="middle">

            <table width="265" border="0" cellpadding="0" cellspacing="0" align="center" style="text-align: center; border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt;" class="fullCenter">
                <tr>
                    <td width="100%" height="50"></td>
                </tr>
                <tr>
                    <td width="100%" height="1"></td>
                </tr>
            </table>

        </td>
    </tr>
</table>
</div>

</td>
</tr>
</table>

</div>
</td>
</tr>
</table><!-- End Notification 2 -->

<!-- Notification 3 -->
<div style="display: none;" id="element_09760125917382538"></div><!-- End Notification 3 -->

<!-- Notification 4  -->
<div style="display: none;" id="element_035286722611635923"></div><!-- End Notification 4 -->

<!-- Notification 5 -->
<div style="display: none;" id="element_08013805721420795"></div><!-- End Notification 5 -->

<!-- Notification 6 -->
<div style="display: none" id="element_020839663897641003"></div><!-- End Notification 6 -->
</div>
</body>	<style>body{ background: none !important; } </style><?php }} ?>