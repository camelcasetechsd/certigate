<?php /* Smarty version Smarty-3.1.16, created on 2016-03-23 09:00:53
         compiled from "/var/www/html/public/forum/sites/default/plugins/sso/admin/sso.admin.tpl" */ ?>
<?php /*%%SmartyHeaderCode:33981726556f25b451cf2d2-00602678%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6d7b6a59d3e537a9bfad184d91932faf1a742a05' => 
    array (
      0 => '/var/www/html/public/forum/sites/default/plugins/sso/admin/sso.admin.tpl',
      1 => 1438625715,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '33981726556f25b451cf2d2-00602678',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.16',
  'unifunc' => 'content_56f25b4521d9f7_08128734',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_56f25b4521d9f7_08128734')) {function content_56f25b4521d9f7_08128734($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_get_opt')) include '/var/www/html/public/forum/sys/CODOF/Smarty/plugins/modifier.get_opt.php';
?><div class="col-md-6">
<form  action="index.php?page=ploader&plugin=sso" role="form" method="post" enctype="multipart/form-data">


SSO Name:
<input type="text" class="form-control" name="sso_name" value="<?php echo smarty_modifier_get_opt("sso_name");?>
" /><br/>
 
SSO Client ID:
<input type="text" class="form-control" name="sso_client_id" value="<?php echo smarty_modifier_get_opt("sso_client_id");?>
" /><br/>

SSO Secret:
<input type="text" class="form-control" name="sso_secret" value="<?php echo smarty_modifier_get_opt("sso_secret");?>
" /><br/>

SSO Get User Path:
<input type="text" class="form-control" name="sso_get_user_path" value="<?php echo smarty_modifier_get_opt("sso_get_user_path");?>
" /><br/>

SSO Login User Path:
<input type="text" class="form-control" name="sso_login_user_path" value="<?php echo smarty_modifier_get_opt("sso_login_user_path");?>
" /><br/>

SSO Logout User Path:
<input type="text" class="form-control" name="sso_logout_user_path" value="<?php echo smarty_modifier_get_opt("sso_logout_user_path");?>
" /><br/>

SSO Register User Path:
<input type="text" class="form-control" name="sso_register_user_path" value="<?php echo smarty_modifier_get_opt("sso_register_user_path");?>
" /><br/>

<input type="submit" value="Save" class="btn btn-primary"/>
</form>
<br/>
<br/>
</div><?php }} ?>
