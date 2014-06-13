<?php /* Smarty version Smarty-3.1.8, created on 2013-07-31 13:43:31
         compiled from "/home/cwruser/cwrdiversity.org/CWR-Checklist/templates/genus-taxa-information.tpl" */ ?>
<?php /*%%SmartyHeaderCode:124826356751f914836a6649-27560976%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b08bdf44cb826ea7ce2e9f9d530bc8b8321a0fcf' => 
    array (
      0 => '/home/cwruser/cwrdiversity.org/CWR-Checklist/templates/genus-taxa-information.tpl',
      1 => 1373050590,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '124826356751f914836a6649-27560976',
  'function' => 
  array (
  ),
  'variables' => 
  array (
    'type' => 0,
    'data' => 0,
    'values' => 0,
    'item' => 0,
    'taxa_left' => 0,
    'taxon' => 0,
    'taxa_right' => 0,
  ),
  'has_nocache_code' => false,
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51f91483a2e905_08850980',
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51f91483a2e905_08850980')) {function content_51f91483a2e905_08850980($_smarty_tpl) {?><!-- JAVASCRIPTS -->
<script type="text/javascript" src="<?php echo @SMARTY_JS_URI;?>
/jquery-ui/jquery-ui-1.10.0.custom.min.js"></script>
<script type="text/javascript" src="<?php echo @SMARTY_JS_URI;?>
/jquery.jnotify/jquery.jnotify.min.js"></script>
<!-- Tooltips from: http://vadikom.com/tools/poshy-tip-jquery-plugin-for-stylish-tooltips -->
<script type="text/javascript" src="<?php echo @SMARTY_JS_URI;?>
/poshytip-1.1/jquery.poshytip.min.js"></script>
<script type="text/javascript" src="<?php echo @SMARTY_JS_URI;?>
/search.js"></script>

<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_CSS_URI;?>
/jquery-ui-1.8.17.custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_CSS_URI;?>
/jquery.jnotify.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_JS_URI;?>
/poshytip-1.1/tip-green/tip-green.css" />
<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_CSS_URI;?>
/search.css" />
<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_CSS_URI;?>
/general.css" />
<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_CSS_URI;?>
/cwr-species-list.css" />
<!-- Header Title -->
<script type="text/javascript">
    document.title = "<?php echo $_smarty_tpl->tpl_vars['type']->value;?>
";
</script>

<div class="go-back"> 
    <a href="<?php echo @SMARTY_URL_CHECKLIST;?>
">
        <img src="<?php echo @SMARTY_IMG_URI;?>
/arrow_return.png">
        Back
    </a>
</div>

<p id="title"><?php echo $_smarty_tpl->tpl_vars['type']->value;?>
</p>

<div id="fullwidth" class="post">
    <div id="taxa-list-information">
        <?php if (isset($_smarty_tpl->tpl_vars['data']->value)){?>
            <?php if ($_smarty_tpl->tpl_vars['type']->value=="Priority Genus"){?>
                <?php $_smarty_tpl->tpl_vars["values"] = new Smarty_variable($_smarty_tpl->tpl_vars['data']->value, null, 0);?>
                <ul>
                    <?php  $_smarty_tpl->tpl_vars['item'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['item']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['values']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['item']->key => $_smarty_tpl->tpl_vars['item']->value){
$_smarty_tpl->tpl_vars['item']->_loop = true;
?>
                        <li><?php echo $_smarty_tpl->tpl_vars['item']->value;?>
</li>
                    <?php } ?>
                </ul> 
            <?php }else{ ?>
                <div class="wrapper">
                    <ul>
                        <?php  $_smarty_tpl->tpl_vars['taxon'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['taxon']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['taxa_left']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['taxon']->key => $_smarty_tpl->tpl_vars['taxon']->value){
$_smarty_tpl->tpl_vars['taxon']->_loop = true;
?>
                            <li><?php echo $_smarty_tpl->tpl_vars['taxon']->value;?>
</li>
                        <?php } ?>
                    </ul>
                </div>
                <div class="wrapper">
                    <ul>
                        <?php  $_smarty_tpl->tpl_vars['taxon'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['taxon']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['taxa_right']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['taxon']->key => $_smarty_tpl->tpl_vars['taxon']->value){
$_smarty_tpl->tpl_vars['taxon']->_loop = true;
?>
                            <li><?php echo $_smarty_tpl->tpl_vars['taxon']->value;?>
</li>
                        <?php } ?>
                    </ul>
                </div>
            <?php }?>
        <?php }?>
    </div>
    <div class="go-back"> 
        <a href="<?php echo @SMARTY_URL_CHECKLIST;?>
">
            <img src="<?php echo @SMARTY_IMG_URI;?>
/arrow_return.png">
            Back
        </a>
    </div>
</div><!-- #post -->
<?php }} ?>