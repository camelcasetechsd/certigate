<?php /* Smarty version Smarty-3.1.16, created on 2016-03-23 09:00:27
         compiled from "/var/www/html/public/forum/admin/layout/templates/dashboard.tpl" */ ?>
<?php /*%%SmartyHeaderCode:172253510256f25b2b54bac8-23778125%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '9132c6f0be5e4efc98390adfaad0753d4d368f88' => 
    array (
      0 => '/var/www/html/public/forum/admin/layout/templates/dashboard.tpl',
      1 => 1438625753,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '172253510256f25b2b54bac8-23778125',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'no_posts' => 0,
    'no_users' => 0,
    'no_topics' => 0,
    'no_views' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_56f25b2b5d70b3_00902738',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56f25b2b5d70b3_00902738')) {function content_56f25b2b5d70b3_00902738($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_abbrev_no')) include '/var/www/html/public/forum/sys/CODOF/Smarty/plugins/modifier.abbrev_no.php';
?>


<section class="content-header" id="breadcrumb_forthistemplate_hack">
    <h1>
        Dashboard
        <small>It all starts here.</small>
    </h1>

</section>



<div class="row">
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3>
                    <?php echo smarty_modifier_abbrev_no($_smarty_tpl->tpl_vars['no_posts']->value);?>

                </h3>
                <p>
                    Posts Made
                </p>
            </div>
            <div class="icon">
                <i class="fa fa-comments"></i>
            </div>
            <a href="../" target="_blank" class="small-box-footer">
                View All <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>    


    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>
                    <?php echo smarty_modifier_abbrev_no($_smarty_tpl->tpl_vars['no_users']->value);?>

                </h3>
                <p>
                    User Registrations
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-person-add"></i>
            </div>
            <a href="index.php?page=users" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    <?php echo smarty_modifier_abbrev_no($_smarty_tpl->tpl_vars['no_topics']->value);?>

                </h3>
                <p>
                    Topics Created
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-stats-bars"></i>
            </div>
            <a href="../" target="_blank" class="small-box-footer">
                View All <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div> 
    <div class="col-lg-3 col-xs-6">
        <!-- small box -->
        <div class="small-box bg-red">
            <div class="inner">
                <h3>
                    &nbsp; <?php echo smarty_modifier_abbrev_no($_smarty_tpl->tpl_vars['no_views']->value);?>

                </h3>
                <p>
                    Total Views
                </p>
            </div>
            <div class="icon">
                <i class="ion ion-pie-graph"></i>
            </div>
            <a href="../" target="_blank" class="small-box-footer">
                Visit Forum <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>                

</div><!-- /.row -->

<div class="row" style="">
    <div class="col-lg-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-bar-chart-o"></i> Latest News & Alerts</h3>
            </div>
            <div class="panel-body">

                <iframe style="width:100%;height:400px" src="http://codoforum.com/news/3.4.php">
                </iframe>

            </div>
        </div>
    </div>
</div><!-- /.row -->
<?php }} ?>
