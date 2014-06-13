<?php /* Smarty version Smarty-3.1.8, created on 2014-05-06 16:32:50
         compiled from "/home/cwruser/cwrdiversity.org/CWR-Checklist/templates/cwr-details.tpl" */ ?>
<?php /*%%SmartyHeaderCode:188663541551d71e2e53d386-65417356%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '8e4b7e525ef724bc9fb6740e316ec08895f9c31e' => 
    array (
      0 => '/home/cwruser/cwrdiversity.org/CWR-Checklist/templates/cwr-details.tpl',
      1 => 1399393761,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '188663541551d71e2e53d386-65417356',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51d71e2f237602_43642089',
  'variables' => 
  array (
    'taxon' => 0,
    'mainTaxon' => 0,
    'maintx' => 0,
    'family' => 0,
    'commonName' => 0,
    'synonyms' => 0,
    'synonym' => 0,
    'classificationReferences' => 0,
    'references' => 0,
    'countries' => 0,
    'country' => 0,
    'distributionType' => 0,
    'region' => 0,
    'details' => 0,
    'distributionReferences' => 0,
    'distributionReference' => 0,
    'utilizations' => 0,
    'utilization' => 0,
    'utilizationReferences' => 0,
    'utilizationReference' => 0,
    'cwr' => 0,
    'cropBreedingUses' => 0,
    'useType' => 0,
    'breedingUses' => 0,
    'breedingUse' => 0,
    'storageBehavior' => 0,
    'herbaria' => 0,
    'herbarium' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51d71e2f237602_43642089')) {function content_51d71e2f237602_43642089($_smarty_tpl) {?><!-- JAVASCRIPTS -->
<script type="text/javascript" src="<?php echo @SMARTY_JS_URI;?>
/jquery-ui/jquery-ui-1.10.0.custom.min.js"></script>
<script type='text/javascript' src='https://www.google.com/jsapi'></script> <!-- Google Charts -->
<!-- Tooltips from: http://vadikom.com/tools/poshy-tip-jquery-plugin-for-stylish-tooltips -->
<script type="text/javascript" src="<?php echo @SMARTY_JS_URI;?>
/poshytip-1.1/jquery.poshytip.min.js"></script>
<script type="text/javascript" src="<?php echo @SMARTY_JS_URI;?>
/cwrgen-details.js"></script>
<script type="text/javascript" src="<?php echo @SMARTY_JS_URI;?>
/cwr-details.js"></script>

<!-- STYLESHEETS --> 
<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_JS_URI;?>
/poshytip-1.1/tip-green/tip-green.css" />
<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_CSS_URI;?>
/jquery-ui-1.8.17.custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_CSS_URI;?>
/cwrgen-details.css" />
<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_CSS_URI;?>
/cwr-details.css" />
<link rel="stylesheet" type="text/css" href="<?php echo @SMARTY_CSS_URI;?>
/general.css" />
 

<!-- start temporal text -->
<!--<p style="color: red; font-weight: bold; text-align: center; font-size: 16px; margin-bottom: 20px">This page is currently under development and will be released shortly</p>--!>
<!-- end temporal texto -->


<div id="fullwidth" class="post">

    <div id="cwr-genepool">
        <?php if (isset($_smarty_tpl->tpl_vars['taxon']->value)){?>
            <!-- Header Title -->
            <script type="text/javascript">
                document.title =  "<?php echo $_smarty_tpl->tpl_vars['taxon']->value->generateScientificName(true,false);?>
";
            </script>

            <!-- Crop Taxa-->
            <p id="title"><?php echo $_smarty_tpl->tpl_vars['taxon']->value->generateScientificName(true,true);?>
</p>

            <?php  $_smarty_tpl->tpl_vars['maintx'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['maintx']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['mainTaxon']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['maintx']->key => $_smarty_tpl->tpl_vars['maintx']->value){
$_smarty_tpl->tpl_vars['maintx']->_loop = true;
?>
                <?php if ($_smarty_tpl->tpl_vars['maintx']->value->Concept_Type!="graftstock"&&!strstr($_smarty_tpl->tpl_vars['maintx']->value->Concept_Type,"[PT]")){?>
                    <p id="link-to-genepool"><a href="genepool-details.php?id[]=<?php echo $_smarty_tpl->tpl_vars['maintx']->value->taxon->getId();?>
"><span><?php echo $_smarty_tpl->tpl_vars['maintx']->value->Concept_Type;?>
 <?php echo $_smarty_tpl->tpl_vars['maintx']->value->Concept_Level;?>
 relative of </span><?php echo $_smarty_tpl->tpl_vars['maintx']->value->taxon->generateScientificName(true,false);?>
</a></p>
                <?php }elseif($_smarty_tpl->tpl_vars['maintx']->value->Concept_Level=="Confirmed"&&$_smarty_tpl->tpl_vars['maintx']->value->Concept_Type!="graftstock"){?>   
                    <p id="link-to-genepool"><a href="genepool-details.php?id[]=<?php echo $_smarty_tpl->tpl_vars['maintx']->value->taxon->getId();?>
"><span><?php echo $_smarty_tpl->tpl_vars['maintx']->value->Concept_Level;?>
 use in breeding for </span><?php echo $_smarty_tpl->tpl_vars['maintx']->value->taxon->generateScientificName(true,false);?>
</a></p>
                <?php }elseif($_smarty_tpl->tpl_vars['maintx']->value->Concept_Level=="Potential"&&$_smarty_tpl->tpl_vars['maintx']->value->Concept_Type!="graftstock"){?>
                    <p id="link-to-genepool"><a href="genepool-details.php?id[]=<?php echo $_smarty_tpl->tpl_vars['maintx']->value->taxon->getId();?>
"><span><?php echo $_smarty_tpl->tpl_vars['maintx']->value->Concept_Level;?>
 use in crop breeding for </span><?php echo $_smarty_tpl->tpl_vars['maintx']->value->taxon->generateScientificName(true,false);?>
</a></p>
                <?php }else{ ?>
                    <p id="link-to-genepool"><a href="genepool-details.php?id[]=<?php echo $_smarty_tpl->tpl_vars['maintx']->value->taxon->getId();?>
"><span><?php echo $_smarty_tpl->tpl_vars['maintx']->value->Concept_Level;?>
 use as <?php echo $_smarty_tpl->tpl_vars['maintx']->value->Concept_Type;?>
 for </span><?php echo $_smarty_tpl->tpl_vars['maintx']->value->taxon->generateScientificName(true,false);?>
</a></p>
                <?php }?>
            <?php } ?>


            <div class="go-back"> 
                <a href="<?php echo @SMARTY_URL_CHECKLIST;?>
">
                    <img src="<?php echo @SMARTY_IMG_URI;?>
/arrow_return.png">
                    Back
                </a>
            </div>
            <!-- Crop Taxa-->
            <div class="box">
                <img class="minus" src="<?php echo @SMARTY_IMG_URI;?>
/circle-minus.png" style="display: none" />
                <img class="more" src="<?php echo @SMARTY_IMG_URI;?>
/circle-plus.png" />
                <h6 class="box-header">Taxon:</h6>
                <div class="box-content">
                    
                    <?php $_smarty_tpl->tpl_vars["family"] = new Smarty_variable($_smarty_tpl->tpl_vars['taxon']->value->getFamily(), null, 0);?>
                    <?php if (isset($_smarty_tpl->tpl_vars['family']->value)){?>
                        <h3>Family </h3><span class="list-more-left"><?php echo $_smarty_tpl->tpl_vars['taxon']->value->getFamily();?>
</span>
                    <?php }?>
                    <h3>Taxon </h3>
                    <ul class="list-more-left">
                        <li><span id="taxon-name"><?php echo $_smarty_tpl->tpl_vars['taxon']->value->generateScientificName(true,true);?>
</span></li>
                    </ul>
                    <!-- Common Name -->
                    <?php $_smarty_tpl->tpl_vars["commonName"] = new Smarty_variable($_smarty_tpl->tpl_vars['taxon']->value->getCommonName(), null, 0);?>
                    <?php if (isset($_smarty_tpl->tpl_vars['commonName']->value)&&$_smarty_tpl->tpl_vars['commonName']->value!=''){?>
                        <h3>Common Name</h3><span class="list-more-left"><?php echo $_smarty_tpl->tpl_vars['taxon']->value->getCommonName();?>
</span>
                    <?php }?>
                </div> <!-- box content -->
            </div> <!-- box -->
            <!-- Synonyms -->
            <?php $_smarty_tpl->tpl_vars["synonyms"] = new Smarty_variable($_smarty_tpl->tpl_vars['taxon']->value->getSynonyms(), null, 0);?>
            <?php if (isset($_smarty_tpl->tpl_vars['synonyms']->value)){?>
                <div id="synonyms" class="box">
                    <img class="minus" src="<?php echo @SMARTY_IMG_URI;?>
/circle-minus.png" style="display: none" />
                    <img class="more" src="<?php echo @SMARTY_IMG_URI;?>
/circle-plus.png" />
                    <h6 class="box-header">Main Synonyms:</h6>
                    <div class="box-content">
                        <ul class="list-more-left">
                            <?php  $_smarty_tpl->tpl_vars['synonym'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['synonym']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['taxon']->value->getSynonyms(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['synonym']->key => $_smarty_tpl->tpl_vars['synonym']->value){
$_smarty_tpl->tpl_vars['synonym']->_loop = true;
?>
                                <li><?php echo $_smarty_tpl->tpl_vars['synonym']->value->generateScientificName(true,true);?>
</li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            <?php }?>
            <!-- Classification Refereces -->
            <?php $_smarty_tpl->tpl_vars["classificationReferences"] = new Smarty_variable($_smarty_tpl->tpl_vars['taxon']->value->getClassificationReferences(), null, 0);?>
            <?php if (count($_smarty_tpl->tpl_vars['classificationReferences']->value)>0){?>
                <div id="classification-ref" class="box">
                    <img class="minus" src="<?php echo @SMARTY_IMG_URI;?>
/circle-minus.png" />
                    <img class="more" src="<?php echo @SMARTY_IMG_URI;?>
/circle-plus.png" />
                    <h6 class="box-header">Classification references:</h6>
                    <div class="box-content">
                        <ul class="list-more-left">
                            <?php  $_smarty_tpl->tpl_vars['references'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['references']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['taxon']->value->getClassificationReferences(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['references']->key => $_smarty_tpl->tpl_vars['references']->value){
$_smarty_tpl->tpl_vars['references']->_loop = true;
?>
                                <li><?php echo $_smarty_tpl->tpl_vars['references']->value->getReference();?>
</li>
                            <?php } ?>
                        </ul>
                    </div>
                </div>
            <?php }?>

            <!-- Geographical Distributions -->
            <?php if (count($_smarty_tpl->tpl_vars['taxon']->value->getGeographicDistributions())>0){?>
                <div id="classification-ref" class="box">
                    <img class="minus" src="<?php echo @SMARTY_IMG_URI;?>
/circle-minus.png" />
                    <img class="more" src="<?php echo @SMARTY_IMG_URI;?>
/circle-plus.png" />
                    <h6 class="box-header">Geographical distribution:</h6>
                    <div class="box-content">
                        <div id="map">
                            <!-- Google Map for Geographic Distribution -->
                            <?php $_smarty_tpl->tpl_vars["countries"] = new Smarty_variable($_smarty_tpl->tpl_vars['taxon']->value->getCountries(), null, 0);?>
                            <?php if (count($_smarty_tpl->tpl_vars['countries']->value)>0){?>
                                <script type='text/javascript'>
                                    var countries = new Array();
                                    var isos = new Array();
                                    <?php  $_smarty_tpl->tpl_vars['country'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['country']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['countries']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['loopCountry']['index']=-1;
foreach ($_from as $_smarty_tpl->tpl_vars['country']->key => $_smarty_tpl->tpl_vars['country']->value){
$_smarty_tpl->tpl_vars['country']->_loop = true;
 $_smarty_tpl->tpl_vars['smarty']->value['foreach']['loopCountry']['index']++;
?>
                                        isos[<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['loopCountry']['index'];?>
] = '<?php echo $_smarty_tpl->tpl_vars['country']->value->getIso2();?>
';
                                        countries[<?php echo $_smarty_tpl->getVariable('smarty')->value['foreach']['loopCountry']['index'];?>
] = '<?php echo $_smarty_tpl->tpl_vars['country']->value->getName();?>
';
                                    <?php } ?>
                                </script>
                                
                                    <script type='text/javascript'>
                                        google.load('visualization', '1', {'packages': ['geochart']});
                                        google.setOnLoadCallback(drawRegionsMap);
                                            
                                        function drawRegionsMap() {
                                        var data = new google.visualization.DataTable();
                                        data.addRows(countries.length);
                                        data.addColumn('string', 'Country');
                                        data.addColumn('string', 'Name');
                                        for(i = 0; i < countries.length; i++) {
                                            data.setValue(i, 0, isos[i]);
                                            var country_name = "";
                                            var array = countries[i].split("-");
                                                
                                                for(j = 0;j < array.length;j++){
                                                    country_name += String.fromCharCode(array[j]);
                                                }
                                                
                                            data.setValue(i, 1, country_name);
                                        }
                                        var options = {
                                            backgroundColor: '#EDF9ED',
                                            datalessRegionColor: 'white',
                                            height: 440,
                                            width: 800
                                        };
      
                                        var chart = new google.visualization.GeoChart(document.getElementById('div-geographic'));
                                        // Redirect to the country distribution    
                                        google.visualization.events.addListener(chart, "regionClick" , function clickHandler(event) {
                                            //window.location.href = "cwr-species-list.php?search-type=location&term="; // relative link  www.cwrdiversity.org/checklist/
                                        });
                                        chart.draw(data, options);
                                    };
                                    </script>
                                
                                <div id="div-geographic"></div>
                            <?php }?>
                        </div> <!-- map -->
                        <div id="country-list" >

                            <?php  $_smarty_tpl->tpl_vars['distributionType'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['distributionType']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['taxon']->value->getGeographicDistributions(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['distributionType']->key => $_smarty_tpl->tpl_vars['distributionType']->value){
$_smarty_tpl->tpl_vars['distributionType']->_loop = true;
?>
                                <h5><?php echo $_smarty_tpl->tpl_vars['distributionType']->value->getName();?>
</h5>
                                <?php if (count($_smarty_tpl->tpl_vars['distributionType']->value->getRegions())>0){?>
                                    <!-- ul -->
                                    <?php  $_smarty_tpl->tpl_vars['region'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['region']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['distributionType']->value->getRegions(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['region']->key => $_smarty_tpl->tpl_vars['region']->value){
$_smarty_tpl->tpl_vars['region']->_loop = true;
?>
                                        <h4 class="mar-left"><?php echo $_smarty_tpl->tpl_vars['region']->value->getName();?>
</h4>
                                        <?php if (count($_smarty_tpl->tpl_vars['region']->value->getCountries())>0){?>
                                            <ul class="list-more-left">
                                                <?php  $_smarty_tpl->tpl_vars['country'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['country']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['region']->value->getCountries(); if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['country']->key => $_smarty_tpl->tpl_vars['country']->value){
$_smarty_tpl->tpl_vars['country']->_loop = true;
?>
                                                    <li><script>
                                                            var string = "<?php echo $_smarty_tpl->tpl_vars['country']->value->getName();?>
";
                                                            var array = string.split("-");
                                                            var temp = "";
                                                            for(i=0;i < array.length;i++){
                                                                temp += String.fromCharCode(array[i]);
                                                            }
                                                            document.write(temp);
                                                        </script>
                                                        <?php $_smarty_tpl->tpl_vars["details"] = new Smarty_variable($_smarty_tpl->tpl_vars['country']->value->getDetails(), null, 0);?>
                                                        <?php if (isset($_smarty_tpl->tpl_vars['details']->value)){?>
                                                            : <?php echo $_smarty_tpl->tpl_vars['details']->value;?>

                                                        <?php }?>
                                                    </li>
                                                <?php } ?>
                                            </ul>
                                        <?php }?>
                                    <?php } ?>
                                    <!-- /ul -->
                                <?php }?>
                            <?php } ?>

                        </div> <!-- country-list -->

                        <!-- Geographical Distributions References -->

                        <?php $_smarty_tpl->tpl_vars["distributionReferences"] = new Smarty_variable($_smarty_tpl->tpl_vars['taxon']->value->getGeographicDistributionReferences(), null, 0);?>
                        <?php if (count($_smarty_tpl->tpl_vars['distributionReferences']->value)>0){?>
                            <h4 class="mar-left" id="geographic-ref">References:</h4>
                            <ul class="list-more-left">
                                <?php  $_smarty_tpl->tpl_vars['distributionReference'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['distributionReference']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['distributionReferences']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['distributionReference']->key => $_smarty_tpl->tpl_vars['distributionReference']->value){
$_smarty_tpl->tpl_vars['distributionReference']->_loop = true;
?>
                                    <li><?php echo $_smarty_tpl->tpl_vars['distributionReference']->value->getReference();?>
</li>
                                <?php } ?>
                            </ul>
                        <?php }?>
                    </div> <!-- box-content -->
                </div> <!-- classification-ref -->
            <?php }?>

            <!-- Taxon Usage -->
            <?php $_smarty_tpl->tpl_vars["utilizations"] = new Smarty_variable($_smarty_tpl->tpl_vars['taxon']->value->getUtilizations(), null, 0);?>
            <?php if (count($_smarty_tpl->tpl_vars['utilizations']->value)>0){?>
                <div id="classification-ref" class="box">
                    <img class="minus" src="<?php echo @SMARTY_IMG_URI;?>
/circle-minus.png" />
                    <img class="more" src="<?php echo @SMARTY_IMG_URI;?>
/circle-plus.png" />
                    <h6 class="box-header">Taxon Usage:</h6>
                    <div id="taxon-usage" class="box-content">
                        <ul class="list-more-left">
                            <?php  $_smarty_tpl->tpl_vars['utilization'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['utilization']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['utilizations']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['utilization']->key => $_smarty_tpl->tpl_vars['utilization']->value){
$_smarty_tpl->tpl_vars['utilization']->_loop = true;
?>
                                <li>
                                    <?php echo $_smarty_tpl->tpl_vars['utilization']->value->getType();?>

                                    <?php if ($_smarty_tpl->tpl_vars['utilization']->value->getUse()!=null){?>
                                        (<?php echo ucfirst($_smarty_tpl->tpl_vars['utilization']->value->getUse());?>
)
                                    <?php }?>
                                </li>
                            <?php } ?>
                        </ul>
                        <!-- Taxon Usage References -->
                        <?php $_smarty_tpl->tpl_vars["utilizationReferences"] = new Smarty_variable($_smarty_tpl->tpl_vars['taxon']->value->getUtilizationReferences(), null, 0);?>
                        <?php if (count($_smarty_tpl->tpl_vars['utilizationReferences']->value)>0){?>
                            <h4 class="mar-left">References:</h4>
                            <ul class="list-more-left">
                                <?php  $_smarty_tpl->tpl_vars['utilizationReference'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['utilizationReference']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['utilizationReferences']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['utilizationReference']->key => $_smarty_tpl->tpl_vars['utilizationReference']->value){
$_smarty_tpl->tpl_vars['utilizationReference']->_loop = true;
?>
                                    <li><i><?php echo $_smarty_tpl->tpl_vars['utilizationReference']->value->getAuthor();?>
</i> - <?php echo $_smarty_tpl->tpl_vars['utilizationReference']->value->getName();?>
</li>
                                <?php } ?>
                            </ul>
                        <?php }?>
                    </div> <!-- taxon usage -->
                </div> <!-- box -->
            <?php }?>


            <!-- Use Breeding -->
            <?php $_smarty_tpl->tpl_vars["cropBreedingUses"] = new Smarty_variable($_smarty_tpl->tpl_vars['cwr']->value->getTaxonBreedingByUseType(), null, 0);?>
            <?php if (count($_smarty_tpl->tpl_vars['cropBreedingUses']->value)>0){?>
                <div id="classification-ref" class="box">
                    <img class="minus" src="<?php echo @SMARTY_IMG_URI;?>
/circle-minus.png" />
                    <img class="more" src="<?php echo @SMARTY_IMG_URI;?>
/circle-plus.png" />
                    <h6 class="box-header">Breeding uses:</h6>
                    <div class="box-content">
                        <ul>
                            <?php  $_smarty_tpl->tpl_vars['breedingUses'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['breedingUses']->_loop = false;
 $_smarty_tpl->tpl_vars['useType'] = new Smarty_Variable;
 $_from = $_smarty_tpl->tpl_vars['cropBreedingUses']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['breedingUses']->key => $_smarty_tpl->tpl_vars['breedingUses']->value){
$_smarty_tpl->tpl_vars['breedingUses']->_loop = true;
 $_smarty_tpl->tpl_vars['useType']->value = $_smarty_tpl->tpl_vars['breedingUses']->key;
?>
                                <li class="use-type"><?php echo $_smarty_tpl->tpl_vars['useType']->value;?>
</li>
                                <ul class="list-more-left">
                                    <?php  $_smarty_tpl->tpl_vars['breedingUse'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['breedingUse']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['breedingUses']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['breedingUse']->key => $_smarty_tpl->tpl_vars['breedingUse']->value){
$_smarty_tpl->tpl_vars['breedingUse']->_loop = true;
?>
                                        <li>
                                            <p>
                                                <?php echo $_smarty_tpl->tpl_vars['breedingUse']->value->getTaxon()->generateScientificName(true,true);?>
:
                                                <?php echo $_smarty_tpl->tpl_vars['breedingUse']->value->getDescription();?>

                                            </p>
                                            <?php echo $_smarty_tpl->tpl_vars['breedingUse']->value->getReference();?>

                                        </li>
                                    <?php } ?>
                                </ul>
                            <?php } ?>
                        </ul>
                    </div>
                </div> <!-- box -->
            <?php }?>

            <!-- Storage Behavior -->
            <?php $_smarty_tpl->tpl_vars["storageBehavior"] = new Smarty_variable($_smarty_tpl->tpl_vars['cwr']->value->getStorageBehavior(), null, 0);?>
            <?php if (!empty($_smarty_tpl->tpl_vars['storageBehavior']->value)){?>
                <div class="box">
                    <img class="minus" src="<?php echo @SMARTY_IMG_URI;?>
/circle-minus.png" />
                    <img class="more" src="<?php echo @SMARTY_IMG_URI;?>
/circle-plus.png" />
                    <h6 class="box-header">Storage behavior for the genus:</h6>
                    <div class="box-content">
                        <script type='text/javascript'>
                            var storages = new Array();
                            storages[0] = <?php echo $_smarty_tpl->tpl_vars['storageBehavior']->value->getOrthodox();?>

                            storages[1] = <?php echo $_smarty_tpl->tpl_vars['storageBehavior']->value->getIntermeduate();?>

                            storages[2] = <?php echo $_smarty_tpl->tpl_vars['storageBehavior']->value->getRecalcitrant();?>

                            storages[3] = <?php echo $_smarty_tpl->tpl_vars['storageBehavior']->value->getUnknown();?>

                        </script>
                        
                            <script type="text/javascript">
                                google.load("visualization", "1", {packages:["corechart"]});
                                google.setOnLoadCallback(drawChart);
                                function drawChart() {
                                var data = new google.visualization.DataTable();
                                data.addColumn('string', 'Task');
                                data.addColumn('number', 'Hours per Day');
                                data.addRows([
                                ['orthodox', storages[0]],
                                ['intermediate', storages[1]],
                                ['recalcitrant', storages[2]],
                                ['unknown', storages[3]]
                                ]);

                                var options = {
                                    backgroundColor: '#EDF9ED',
                                    is3D: true,
                                        reverseCategories: true
                                };

                                var chart = new google.visualization.PieChart(document.getElementById('storage_pie'));
                                chart.draw(data, options);
                            }
                            </script>

                        
                        <div id="pie-container" class="left">
                            <div id="storage_pie"></div>
                        </div>
                        <span>For storage behavior and other seed information for this species search <a href="http://data.kew.org/sid/sidsearch.html">here</a></span>
                        <h4>Reference:</h4>
                        <span><?php echo $_smarty_tpl->tpl_vars['storageBehavior']->value->getReference();?>
</span>
                    </div> <!-- box-content -->
                </div> <!-- box -->

            <?php }?>

            <!-- Herbaria Data -->
            <?php $_smarty_tpl->tpl_vars["herbaria"] = new Smarty_variable($_smarty_tpl->tpl_vars['taxon']->value->getHerbaria(), null, 0);?>
            <?php if (count($_smarty_tpl->tpl_vars['herbaria']->value)>0){?>
                <div class="box">
                    <img class="minus" src="<?php echo @SMARTY_IMG_URI;?>
/circle-minus.png" />
                    <img class="more" src="<?php echo @SMARTY_IMG_URI;?>
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
                    </div>
                </div> <!-- box -->
            <?php }?>

        <?php }else{ ?>
            <h3>NO DATA</h3>
            <script type="text/javascript">
                document.title =  "NO DATA" ;
            </script>
        <?php }?>

        <div class="box">
            <img class="minus" src="<?php echo @SMARTY_IMG_URI;?>
/circle-minus.png" style="display: none" />
            <img class="more" src="<?php echo @SMARTY_IMG_URI;?>
/circle-plus.png" />
            <h6 class="box-header">CWR Checklist Citation:</h6>
            <div class="box-content">
                <p class="genepool-citation">
                    Vincent, H. et al. (2013) <a href="http://linkinghub.elsevier.com/retrieve/pii/S0006320713002851" target="_blank"> A prioritized crop wild relative inventory to help underpin global food security.</a> <em>Biological Conservation</em> 167: 265–275. 
                </p>
            </div>
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

</div><!-- #post --><?php }} ?>