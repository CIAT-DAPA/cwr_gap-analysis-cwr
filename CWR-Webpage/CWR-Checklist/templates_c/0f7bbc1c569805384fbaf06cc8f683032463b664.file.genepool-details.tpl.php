<?php /* Smarty version Smarty-3.1.8, created on 2013-10-02 16:38:27
         compiled from "/home/cwruser/cwrdiversity.org/CWR-Checklist/templates/genepool-details.tpl" */ ?>
<?php /*%%SmartyHeaderCode:89448165851d71e1ccd1662-08479026%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '0f7bbc1c569805384fbaf06cc8f683032463b664' => 
    array (
      0 => '/home/cwruser/cwrdiversity.org/CWR-Checklist/templates/genepool-details.tpl',
      1 => 1380731889,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '89448165851d71e1ccd1662-08479026',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51d71e1d9064f8_69990188',
  'variables' => 
  array (
    'genePools' => 0,
    'taxa' => 0,
    'key' => 0,
    'taxons' => 0,
    'cropTaxa' => 0,
    'family' => 0,
    'cropTaxon' => 0,
    'commonName' => 0,
    'synonyms' => 0,
    'synonym' => 0,
    'classificationReferences' => 0,
    'references' => 0,
    'genePool' => 0,
    'taxonGroupConcept' => 0,
    'genePoolsIDs' => 0,
    'conceptLevel' => 0,
    'concepts' => 0,
    'taxonConcept' => 0,
    'conceptReferences' => 0,
    'conceptReference' => 0,
    'cropBreedingUses' => 0,
    'useType' => 0,
    'breedingUses' => 0,
    'breedingUse' => 0,
    'herbaria' => 0,
    'herbarium' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51d71e1d9064f8_69990188')) {function content_51d71e1d9064f8_69990188($_smarty_tpl) {?><!-- JAVASCRIPTS -->
<script type="text/javascript" src="<?php echo @SMARTY_JS_URI;?>
/jquery-ui/jquery-ui-1.10.0.custom.min.js"></script>
<!-- Tooltips from: http://vadikom.com/tools/poshy-tip-jquery-plugin-for-stylish-tooltips -->
<script type='text/javascript' src='https://www.google.com/jsapi'></script> 
<script type="text/javascript" src="<?php echo @SMARTY_JS_URI;?>
/poshytip-1.1/jquery.poshytip.min.js"></script>
<script type="text/javascript" src="<?php echo @SMARTY_JS_URI;?>
/genepool-details.js"></script>

<!-- STYLESHEETS -->
<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_JS_URI;?>
/poshytip-1.1/tip-green/tip-green.css" />
<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_CSS_URI;?>
/jquery-ui-1.8.17.custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_CSS_URI;?>
/cwrgen-details.css" />
<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_CSS_URI;?>
/genepool-details.css" />
<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_CSS_URI;?>
/general.css" />

<!-- start temporal text -->
<!--<p style="color: red; font-weight: bold; text-align: center; font-size: 16px; margin-bottom: 20px">This page is currently under development and will be released shortly</p>--!>
<!-- end temporal texto -->

<div id="fullwidth" class="post">

    <div id="cwr-genepool">
        <div class="go-back"> 
            <a href="<?php echo @SMARTY_URL_CHECKLIST;?>
">
                <img src="<?php echo @SMARTY_IMG_URI;?>
/arrow_return.png">
                Back
            </a>
        </div>

        <script type="text/javascript">
                document.title =  "Gene Pool";
        </script>

        <?php  $_smarty_tpl->tpl_vars["genePool"] = new Smarty_Variable; $_smarty_tpl->tpl_vars["genePool"]->_loop = false;
 $_smarty_tpl->tpl_vars["key"] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['genePools']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars["genePool"]->key => $_smarty_tpl->tpl_vars["genePool"]->value){
$_smarty_tpl->tpl_vars["genePool"]->_loop = true;
 $_smarty_tpl->tpl_vars["key"]->value = $_smarty_tpl->tpl_vars["genePool"]->key;
?>
            <?php if (isset($_smarty_tpl->tpl_vars['taxa']->value)){?>
                <?php if ($_smarty_tpl->tpl_vars['key']->value!=0){?>
                    <p id="title" class="more-margin-top full-size">
                        <?php echo $_smarty_tpl->tpl_vars['taxons']->value[$_smarty_tpl->tpl_vars['key']->value]->generateScientificName(true,true);?>

                    </p>
                <?php }else{ ?>
                    <p id="title" class="full-size">
                        <?php echo $_smarty_tpl->tpl_vars['taxons']->value[$_smarty_tpl->tpl_vars['key']->value]->generateScientificName(true,true);?>

                    </p>
                <?php }?>
                <!-- Crop Taxa-->
                <div id="synonyms" class="box">
                    <img class="minus" src="<?php echo @SMARTY_IMG_URI;?>
/circle-minus.png" style="display: none" />
                    <img class="more-genepool" src="<?php echo @SMARTY_IMG_URI;?>
/circle-plus.png" />
                    <?php if (count($_smarty_tpl->tpl_vars['cropTaxa']->value[$_smarty_tpl->tpl_vars['key']->value])==1){?>
                        <h6 class="box-header">Crop Taxon:</h6>
                    <?php }else{ ?>
                        <h6 class="box-header">Crop Taxa:</h6>
                    <?php }?>
                    <div class="box-content">
                        <?php $_smarty_tpl->tpl_vars["family"] = new Smarty_variable($_smarty_tpl->tpl_vars['taxa']->value[$_smarty_tpl->tpl_vars['key']->value]->getFamily(), null, 0);?>
                        <?php if (isset($_smarty_tpl->tpl_vars['family']->value)){?>
                            <h4>Family </h4><span class="list-more-left"><?php echo $_smarty_tpl->tpl_vars['taxa']->value[$_smarty_tpl->tpl_vars['key']->value]->getFamily();?>
</span>
                        <?php }?>
                        <h4>Taxon </h4>
                        <?php  $_smarty_tpl->tpl_vars['cropTaxon'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['cropTaxon']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['cropTaxa']->value[$_smarty_tpl->tpl_vars['key']->value]; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['cropTaxon']->key => $_smarty_tpl->tpl_vars['cropTaxon']->value){
$_smarty_tpl->tpl_vars['cropTaxon']->_loop = true;
?>
                            <ul class="list-more-left">
                                <li><span id="taxon-name"><?php echo $_smarty_tpl->tpl_vars['cropTaxon']->value->generateScientificName(true,true);?>
</span></li>
                            </ul>
                        <?php } ?>
                        <!-- Common Name -->
                        <?php $_smarty_tpl->tpl_vars["commonName"] = new Smarty_variable($_smarty_tpl->tpl_vars['taxons']->value[$_smarty_tpl->tpl_vars['key']->value]->getCommonName(), null, 0);?>
                        <?php if (isset($_smarty_tpl->tpl_vars['commonName']->value)){?>
                            <h4>Common Name</h4><span class="list-more-left"><?php echo $_smarty_tpl->tpl_vars['taxons']->value[$_smarty_tpl->tpl_vars['key']->value]->getCommonName();?>
</span>
                        <?php }?>
                    </div> <!-- box content -->
                </div> <!-- box -->

                <!-- Synonyms -->
                <?php $_smarty_tpl->tpl_vars["synonyms"] = new Smarty_variable($_smarty_tpl->tpl_vars['taxa']->value[$_smarty_tpl->tpl_vars['key']->value]->getSynonyms(), null, 0);?>
                <?php if (isset($_smarty_tpl->tpl_vars['synonyms']->value)){?>
                    <div class="box">
                        <img class="minus" src="<?php echo @SMARTY_IMG_URI;?>
/circle-minus.png" style="display: none" />
                        <img class="more-genepool" src="<?php echo @SMARTY_IMG_URI;?>
/circle-plus.png" />
                        <h6 class="box-header">Main Synonyms:</h6>
                        <div class="box-content">
                            <ul class="list-more-left">
                                <?php  $_smarty_tpl->tpl_vars['synonym'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['synonym']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['taxa']->value[$_smarty_tpl->tpl_vars['key']->value]->getSynonyms(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['synonym']->key => $_smarty_tpl->tpl_vars['synonym']->value){
$_smarty_tpl->tpl_vars['synonym']->_loop = true;
?>
                                    <li><?php echo $_smarty_tpl->tpl_vars['synonym']->value->generateScientificName(true,true);?>
</li>
                                <?php } ?>
                            </ul>
                        </div>
                    </div> <!-- box -->
                <?php }?>

                <!-- Classification Refereces -->
                <?php $_smarty_tpl->tpl_vars["classificationReferences"] = new Smarty_variable($_smarty_tpl->tpl_vars['taxa']->value[$_smarty_tpl->tpl_vars['key']->value]->getClassificationReferences(), null, 0);?>
                <?php if (count($_smarty_tpl->tpl_vars['classificationReferences']->value)>0){?>
                    <div class="box">
                        <img class="minus" src="<?php echo @SMARTY_IMG_URI;?>
/circle-minus.png" style="display: none" />
                        <img class="more-genepool" src="<?php echo @SMARTY_IMG_URI;?>
/circle-plus.png" />
                        <h6 class="box-header">Classification references:</h6>
                        <ul class="box-content">
                            <?php  $_smarty_tpl->tpl_vars['references'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['references']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['taxa']->value[$_smarty_tpl->tpl_vars['key']->value]->getClassificationReferences(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['references']->key => $_smarty_tpl->tpl_vars['references']->value){
$_smarty_tpl->tpl_vars['references']->_loop = true;
?>
                                <li><?php echo $_smarty_tpl->tpl_vars['references']->value->getReference();?>
</li>
                            <?php } ?>
                        </ul>
                    </div> <!-- box -->
                <?php }?>

                <!-- Taxon Group Concept -->
                <?php $_smarty_tpl->tpl_vars["taxonGroupConcept"] = new Smarty_variable($_smarty_tpl->tpl_vars['genePool']->value->getTaxaByConceptLevels(), null, 0);?>
                <?php if (count($_smarty_tpl->tpl_vars['taxonGroupConcept']->value)>0){?>
                    <div class="box">
                        <img class="minus" src="<?php echo @SMARTY_IMG_URI;?>
/circle-minus.png" style="display: none" />
                        <img class="more-genepool" src="<?php echo @SMARTY_IMG_URI;?>
/circle-plus.png" />
                        <h6 id="concept-type" class="box-header"><?php echo $_smarty_tpl->tpl_vars['genePool']->value->getMainCrop()->getConceptType();?>
:</h6>
                        <div class="box-content">
                            <div id="download-box">
                                <p id="content-download-box">
                                    <?php if (isset($_smarty_tpl->tpl_vars['genePoolsIDs']->value)){?>
                                        <a href="download.php?term[]=<?php echo $_smarty_tpl->tpl_vars['genePoolsIDs']->value[$_smarty_tpl->tpl_vars['key']->value];?>
">
                                            <img src="<?php echo @SMARTY_IMG_URI;?>
/save.png"/>
                                            Download
                                        </a>
                                    <?php }?>
                                </p>
                            </div> 
                            <ul>
                                <?php  $_smarty_tpl->tpl_vars['concepts'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['concepts']->_loop = false;
 $_smarty_tpl->tpl_vars['conceptLevel'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['taxonGroupConcept']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['concepts']->key => $_smarty_tpl->tpl_vars['concepts']->value){
$_smarty_tpl->tpl_vars['concepts']->_loop = true;
 $_smarty_tpl->tpl_vars['conceptLevel']->value = $_smarty_tpl->tpl_vars['concepts']->key;
?>
                                    <li class="concept-level" ><?php echo $_smarty_tpl->tpl_vars['conceptLevel']->value;?>
</li>
                                    <ul class="list-more-right">
                                        <?php  $_smarty_tpl->tpl_vars['taxonConcept'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['taxonConcept']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['concepts']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['taxonConcept']->key => $_smarty_tpl->tpl_vars['taxonConcept']->value){
$_smarty_tpl->tpl_vars['taxonConcept']->_loop = true;
?>
                                            <li><a href="cwr-details.php?specie_id=<?php echo $_smarty_tpl->tpl_vars['taxonConcept']->value->getId();?>
"><?php echo $_smarty_tpl->tpl_vars['taxonConcept']->value->getScientificName(true,true);?>
</a></li>
                                            <!--
                                            <?php if ($_smarty_tpl->tpl_vars['taxonConcept']->value->getMainCrop()==0){?>
                                                <li><a href="cwr-details.php?specie_id=<?php echo $_smarty_tpl->tpl_vars['taxonConcept']->value->getId();?>
"><?php echo $_smarty_tpl->tpl_vars['taxonConcept']->value->getScientificName(true,true);?>
</a></li>
                                            <?php }else{ ?>
                                                <li><a href="genepool-details.php?id[]=<?php echo $_smarty_tpl->tpl_vars['taxonConcept']->value->getId();?>
"><?php echo $_smarty_tpl->tpl_vars['taxonConcept']->value->getScientificName(true,true);?>
</a></li>
                                            <?php }?>-->
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </ul>
                            <!-- Concept References -->
                            <?php $_smarty_tpl->tpl_vars["conceptReferences"] = new Smarty_variable($_smarty_tpl->tpl_vars['genePool']->value->getMainCrop()->getConceptReferences(), null, 0);?>
                            <?php if (count($_smarty_tpl->tpl_vars['conceptReferences']->value)>0){?>
                                <h4>Concept References:</h4>
                                <ul class="list-more-left">
                                    <?php  $_smarty_tpl->tpl_vars['conceptReference'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['conceptReference']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['conceptReferences']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['conceptReference']->key => $_smarty_tpl->tpl_vars['conceptReference']->value){
$_smarty_tpl->tpl_vars['conceptReference']->_loop = true;
?>
                                        <li><?php echo $_smarty_tpl->tpl_vars['conceptReference']->value->getReference();?>
</li>
                                    <?php } ?>
                                </ul>
                            <?php }?>
                        </div> <!-- box content -->
                    </div> <!-- box -->
                <?php }?>

                <!-- Use Breeding -->
                <?php $_smarty_tpl->tpl_vars["cropBreedingUses"] = new Smarty_variable($_smarty_tpl->tpl_vars['genePool']->value->getCropBreedingByUseType(), null, 0);?>
                <?php if (count($_smarty_tpl->tpl_vars['cropBreedingUses']->value)>0){?>
                    <div class="box">
                        <img class="minus" src="<?php echo @SMARTY_IMG_URI;?>
/circle-minus.png" style="display: none" />
                        <img class="more-genepool" src="<?php echo @SMARTY_IMG_URI;?>
/circle-plus.png" />
                        <h6 class="box-header">Taxa used in crop breeding:</h6>
                        <ul class="box-content">
                            <?php  $_smarty_tpl->tpl_vars['breedingUses'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['breedingUses']->_loop = false;
 $_smarty_tpl->tpl_vars['useType'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['cropBreedingUses']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['breedingUses']->key => $_smarty_tpl->tpl_vars['breedingUses']->value){
$_smarty_tpl->tpl_vars['breedingUses']->_loop = true;
 $_smarty_tpl->tpl_vars['useType']->value = $_smarty_tpl->tpl_vars['breedingUses']->key;
?>
                                <li class="use-type"><?php echo $_smarty_tpl->tpl_vars['useType']->value;?>
</li>
                                <ul class="list-more-right">
                                    <?php  $_smarty_tpl->tpl_vars['breedingUse'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['breedingUse']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['breedingUses']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['breedingUse']->key => $_smarty_tpl->tpl_vars['breedingUse']->value){
$_smarty_tpl->tpl_vars['breedingUse']->_loop = true;
?>
                                        <li>
                                            <p>
                                                <?php if ($_smarty_tpl->tpl_vars['breedingUse']->value->getTaxon()->getMainCrop()==1){?>
                                                    <a href="?specie_id=<?php echo $_smarty_tpl->tpl_vars['breedingUse']->value->getTaxon()->getId();?>
">
                                                    <?php }else{ ?>
                                                        <a href="cwr-details.php?specie_id=<?php echo $_smarty_tpl->tpl_vars['breedingUse']->value->getTaxon()->getId();?>
">
                                                        <?php }?>                                        
                                                        <?php echo $_smarty_tpl->tpl_vars['breedingUse']->value->getTaxon()->getScientificName();?>

                                                    </a>:                                   
                                                    <?php echo $_smarty_tpl->tpl_vars['breedingUse']->value->getDescription();?>

                                            </p>
                                            <?php echo $_smarty_tpl->tpl_vars['breedingUse']->value->getReference();?>

                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </ul>
                    </div> <!-- box -->
                <?php }?>

                <!-- Herbaria Data -->
                <?php $_smarty_tpl->tpl_vars["herbaria"] = new Smarty_variable($_smarty_tpl->tpl_vars['taxa']->value[$_smarty_tpl->tpl_vars['key']->value]->getHerbaria(), null, 0);?>
                <?php if (count($_smarty_tpl->tpl_vars['herbaria']->value)>0){?>
                    <div class="box">
                        <img class="minus" src="<?php echo @SMARTY_IMG_URI;?>
/circle-minus.png" style="display: none" />
                        <img class="more-genepool" src="<?php echo @SMARTY_IMG_URI;?>
/circle-plus.png" />
                        <h6 class="box-header">Herbaria:</h6>
                        <div id="herbaria" class="box-content">
                            <ul class="list-more-left">
                                <?php  $_smarty_tpl->tpl_vars['herbarium'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['herbarium']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['herbaria']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['herbarium']->key => $_smarty_tpl->tpl_vars['herbarium']->value){
$_smarty_tpl->tpl_vars['herbarium']->_loop = true;
?>
                                    <li><span class="herbariaToolTip" title="<?php echo $_smarty_tpl->tpl_vars['herbarium']->value->getDetailsInHTML();?>
"><?php echo $_smarty_tpl->tpl_vars['herbarium']->value->getInstitutionName();?>
</span></li>
                                <?php } ?>
                            </ul>
                        </div> <!-- box-content -->
                    </div> <!-- box -->
                <?php }?>

            <?php }else{ ?>
                <h3>NO DATA</h3>
            <?php }?>
        <?php } ?>

        <div class="box">
            <img class="minus" src="<?php echo @SMARTY_IMG_URI;?>
/circle-minus.png" style="display: none" />
            <img class="more-genepool" src="<?php echo @SMARTY_IMG_URI;?>
/circle-plus.png" />
            <h6 class="box-header">CWR Checklist Citation:</h6>
            <div class="box-content">
                <p class="genepool-citation">
                    Vincent, H. et al. (2013) <a href="http://linkinghub.elsevier.com/retrieve/pii/S0006320713002851" target="_blank"> A prioritized crop wild relative inventory to help underpin global food security.</a> <em>Biological Conservation</em> 167: 265–275. 
                </p>
            </div>
        </div>

        <div class="go-back"> 
            <a href="<?php echo @SMARTY_URL_CHECKLIST;?>
">
                <img src="<?php echo @SMARTY_IMG_URI;?>
/arrow_return.png">
                Back
            </a>
        </div>
    </div>
</div><!-- #post -->
<?php }} ?>