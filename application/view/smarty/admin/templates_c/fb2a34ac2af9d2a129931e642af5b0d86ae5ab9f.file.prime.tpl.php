<?php /* Smarty version Smarty-3.1.11, created on 2014-01-14 17:47:21
         compiled from "..\application\view\admin\prime.tpl" */ ?>
<?php /*%%SmartyHeaderCode:622352c8d443234981-53572672%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fb2a34ac2af9d2a129931e642af5b0d86ae5ab9f' => 
    array (
      0 => '..\\application\\view\\admin\\prime.tpl',
      1 => 1389750403,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '622352c8d443234981-53572672',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.11',
  'unifunc' => 'content_52c8d4432a5e21_97829630',
  'variables' => 
  array (
    'pathRoot' => 0,
    'user' => 0,
    'hard_tags' => 0,
    'font_categories' => 0,
    'font_classifications' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_52c8d4432a5e21_97829630')) {function content_52c8d4432a5e21_97829630($_smarty_tpl) {?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>FontSpy Admin</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <!-- google webfonts -->
    <link href='http://fonts.googleapis.com/css?family=Ropa+Sans' rel='stylesheet' type='text/css'>

    <!-- require -->
    <script data-main="/admin/js/main" src="<?php echo $_smarty_tpl->tpl_vars['pathRoot']->value;?>
/js/libs/require/require.js"></script>

    <!-- Global variables -->
    <script type="text/javascript">
        var PhantomTweets = new Object;

        var SERVER_CONSTANTS = {
            user : <?php echo $_smarty_tpl->tpl_vars['user']->value;?>
,
            hard_tags : <?php echo $_smarty_tpl->tpl_vars['hard_tags']->value;?>
,
            font_categories : <?php echo $_smarty_tpl->tpl_vars['font_categories']->value;?>
,
            font_classifications : <?php echo $_smarty_tpl->tpl_vars['font_classifications']->value;?>

        };

        var myApp = new Object;
    </script>

    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['pathRoot']->value;?>
/css/bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['pathRoot']->value;?>
/css/bootstrap-modal.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['pathRoot']->value;?>
/css/admin.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['pathRoot']->value;?>
/css/toastr.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['pathRoot']->value;?>
/css/toastr-responsive.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['pathRoot']->value;?>
/css/datepicker.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['pathRoot']->value;?>
/css/token-input.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['pathRoot']->value;?>
/css/datetimepicker.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['pathRoot']->value;?>
/css/select2.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['pathRoot']->value;?>
/css/jquery.fileupload-ui.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['pathRoot']->value;?>
/css/jquery.fs.stepper.css">
    <link rel="stylesheet" type="text/css" href="<?php echo $_smarty_tpl->tpl_vars['pathRoot']->value;?>
/css/jquery.slider.min.css">

<body>


<div id="wrap">
    <nav class="navbar navbar-default" role="navigation">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <span class="navbar-brand">FontSpy</span>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav">
                <li class="dropdown" data-section="fonts">
                    <a data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-bold"></span> Fonts</a>
                    <ul class="dropdown-menu" role="menu" aria-labelledby="dLabel">
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/new-fonts">New Fonts</a></li>
                        <li role="presentation"><a role="menuitem" tabindex="-1" href="/all-fonts">All Fonts</a></li>
                        </ul>
                </li>
                <li data-section="categories"><a class="router" href="/categories"><span class="glyphicon glyphicon-list"></span> Categories</a></li>
                <li data-section="users"><a class="router" href="/users"><span class="glyphicon glyphicon-user"></span> Users</a></li>
                <li data-section="settings"><a class="router" href="/settings"><span class="glyphicon glyphicon-wrench"></span> Settings</a></li>

            </ul>
        </div><!-- /.navbar-collapse -->
    </nav>
    <div class="container" id="mainPanel"></div>

</div>

    <!-- DIALOGS -->
    <div id="globalDialogView">
        <div id="confirmDialog" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                <h3 class="dialogLabel"></h3>
            </div>
            <div class="modal-body">
                <p class="desc"></p>
            </div>
            <div class="modal-footer">
                <button class="btn closeButton" data-dismiss="modal" aria-hidden="true">Cancel</button>
                <button class="btn btn-danger confirmButton">Confirm</button>
            </div>
        </div>

    </div>
    <!-- END DIALOGS -->

</body>
</html><?php }} ?>