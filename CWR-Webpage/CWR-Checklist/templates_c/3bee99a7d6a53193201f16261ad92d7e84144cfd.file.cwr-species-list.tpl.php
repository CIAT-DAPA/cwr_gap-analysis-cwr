<?php /* Smarty version Smarty-3.1.8, created on 2013-10-02 16:40:20
         compiled from "/home/cwruser/cwrdiversity.org/CWR-Checklist/templates/cwr-species-list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:55503221751dada8e6ffeb9-39363819%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3bee99a7d6a53193201f16261ad92d7e84144cfd' => 
    array (
      0 => '/home/cwruser/cwrdiversity.org/CWR-Checklist/templates/cwr-species-list.tpl',
      1 => 1380731859,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '55503221751dada8e6ffeb9-39363819',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51dada8eb3ed55_07898537',
  'variables' => 
  array (
    'countryCode' => 0,
    'taxa_left' => 0,
    'taxa_right' => 0,
    'cwrName' => 0,
    'taxa' => 0,
    'taxon' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51dada8eb3ed55_07898537')) {function content_51dada8eb3ed55_07898537($_smarty_tpl) {?><?php if (!is_callable('smarty_modifier_replace')) include '/home/cwruser/cwrdiversity.org/libs/Smarty-3.1.8/plugins/modifier.replace.php';
?><link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_CSS_URI;?>
/cwr-species-list.css" />
<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_CSS_URI;?>
/general.css" />
<script type="text/javascript" src="<?php echo @SMARTY_JS_URI;?>
/cwr-species-list.js"></script>

<!-- Header Title -->
<script type="text/javascript">
    document.title =  "Results for: \"<?php echo smarty_modifier_replace($_GET['term'],"\\'","'");?>
\"";
</script>

<!-- start temporal text -->
<!--<p style="color: red; font-weight: bold; text-align: center; font-size: 16px; margin-bottom: 20px">This page is currently under development and will be released shortly</p>-->
<!-- end temporal texto -->

<div class="post">
    <p id="title">
        Search results for "<?php echo smarty_modifier_replace($_GET['term'],"\\'","'");?>
"
    </p>
    <div class="go-back"> 
        <a href="<?php echo @SMARTY_URL_CHECKLIST;?>
">
            <img src="<?php echo @SMARTY_IMG_URI;?>
/arrow_return.png">
            Back
        </a>
    </div>
    <div id=count-box>        
        <p id="count-taxa" class="text-aling-right">
            <?php if (isset($_smarty_tpl->tpl_vars['countryCode']->value)){?>
                <img src="<?php echo @SMARTY_IMG_URI;?>
/countries/<?php echo $_smarty_tpl->tpl_vars['countryCode']->value;?>
.png"/>
            <?php }?>
            Total <?php echo count($_smarty_tpl->tpl_vars['taxa_left']->value)+count($_smarty_tpl->tpl_vars['taxa_right']->value);?>
 taxa
        </p>
    </div>
    <?php if (!isset($_smarty_tpl->tpl_vars['cwrName']->value)){?>
        <div class="text-aling-left">
            <form action="download.php" method="post" class="right download">
                <img src="<?php echo @SMARTY_IMG_URI;?>
/save.png" class="left"/>
                <?php  $_smarty_tpl->tpl_vars["taxon"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["taxon"]->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['taxa']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["taxon"]->key => $_smarty_tpl->tpl_vars["taxon"]->value){
$_smarty_tpl->tpl_vars["taxon"]->_loop = true;
?>
                    <input type="hidden" name="term[]" value=<?php echo $_smarty_tpl->tpl_vars['taxon']->value->getId();?>
>
                <?php } ?>
                <input id="download-taxa" type="submit" value="Download" />
            </form>
        </div>
    <?php }?>
    <div id="taxa-list">
        <div class="wrapper more-top">
            <ul>
                <?php  $_smarty_tpl->tpl_vars['taxon'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['taxon']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['taxa_left']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['taxon']->key => $_smarty_tpl->tpl_vars['taxon']->value){
$_smarty_tpl->tpl_vars['taxon']->_loop = true;
?>
                    <li><a href="cwr-details.php?specie_id=<?php echo $_smarty_tpl->tpl_vars['taxon']->value->getId();?>
"> <?php echo $_smarty_tpl->tpl_vars['taxon']->value->generateScientificName(true,true);?>
 </a></li>
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
                    <li><a href="cwr-details.php?specie_id=<?php echo $_smarty_tpl->tpl_vars['taxon']->value->getId();?>
"> <?php echo $_smarty_tpl->tpl_vars['taxon']->value->generateScientificName(true,true);?>
 </a></li>
                <?php } ?>
            </ul>
        </div>
        <br />
        <div class="cwrchecklist-citation auto">
            <h2 class="blue" >CWR Checklist Citation</h2>
            Vincent, H. et al. (2013) <a href="http://linkinghub.elsevier.com/retrieve/pii/S0006320713002851" target="_blank"> A prioritized crop wild relative inventory to help underpin global food security.</a> <em>Biological Conservation</em> 167: 265–275. 
        </div>
    </div>
</div>

<?php }} ?>