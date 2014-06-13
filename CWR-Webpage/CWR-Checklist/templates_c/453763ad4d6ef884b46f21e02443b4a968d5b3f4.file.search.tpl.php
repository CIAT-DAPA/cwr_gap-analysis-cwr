<?php /* Smarty version Smarty-3.1.8, created on 2014-06-11 14:13:21
         compiled from "/home/cwruser/cwrdiversity.org/CWR-Checklist/templates/search.tpl" */ ?>
<?php /*%%SmartyHeaderCode:63660038351d71b61c0f6c9-35642329%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_valid = $_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '453763ad4d6ef884b46f21e02443b4a968d5b3f4' => 
    array (
      0 => '/home/cwruser/cwrdiversity.org/CWR-Checklist/templates/search.tpl',
      1 => 1402495979,
      2 => 'file',
    ),
  ),
  'nocache_hash' => '63660038351d71b61c0f6c9-35642329',
  'function' => 
  array (
  ),
  'version' => 'Smarty-3.1.8',
  'unifunc' => 'content_51d71b6200fb43_92444331',
  'variables' => 
  array (
    'countries' => 0,
    'country' => 0,
    'regions' => 0,
    'region' => 0,
    'concept_types' => 0,
    'type' => 0,
    'concept_levels' => 0,
    'level' => 0,
    'priority_genera' => 0,
    'genus' => 0,
    'breedingUses' => 0,
    'use' => 0,
  ),
  'has_nocache_code' => false,
),false); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_valid && !is_callable('content_51d71b6200fb43_92444331')) {function content_51d71b6200fb43_92444331($_smarty_tpl) {?><!-- JAVASCRIPTS -->
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
<!-- Header Title -->
<script type="text/javascript">
    document.title =  "Crop Wild Relatives Inventory" ;
</script>

<!-- start temporal text -->
<!--<p style="color: red; font-weight: bold; text-align: center; font-size: 16px; margin-bottom: 20px">This page is currently under development and will be released shortly</p>-->
<!-- end temporal texto -->


<p id="title">The Harlan and de Wet Crop Wild Relative Inventory</p>

<div id="fullwidth" class="post">

    <div id="search-type-selector">
        <div id="classic-search-button" class="left select">
            <span class="button-text">
                Classic Search
            </span>
        </div>
        <div id="advanced-search-button" class="left">
            <span class="button-text">
                Advanced Search
            </span>
        </div>
    </div>
    <div class="clear"></div>
    <div id="cwr-search">
        <div id="rightDiv" >
            <div id="text">
                <p>Crop wild relative species may be prioritised on the basis of the economic importance of the associated crop, the level of threat to CWR populations, or perhaps most importantly, the potential for CWR utilisation. Utilisation potential is determined by the ease of trait transfer between the CWR and the crop, and CWR may be assigned to different gene pools based upon these characteristics. </p>
                <p>The Harlan and de Wet (1971) gene pool concept proposes that members of crop gene pool GP1b (primary) and 2 (secondary) are most likely to be crossable with the crop and so these become the obvious conservation priorities. However, gene pool concepts have not yet been established for all crops, and where they are not available the taxon group concept (Maxted et al. 2006), which uses taxonomic classifications of the crop genus as a proxy for relative crossability, can be applied. Taxon group TG1b (same species as crop), TG2 (same series or section as crop) and TG3 (same subgenus as crop) are given priority. Other CWR that are also given priority are species that have previously been successfully used in breeding, regardless of relative close relation to the crop. As such the Inventory presents a priority list of CWR species (members of GP1b/GP2 or TG1b-3, or previously used in breeding). The Inventory contains over 1400 taxa divided between 36 families and 92 genera, and is annotated with key ancillary data, including their regional and national occurrence, seed storage behaviour and herbaria housing major collections of the CWR.</p>
                <p>Harlan, J.R. and de Wet, J.M.J., (1971). Towards a rational classification of cultivated plants. <i>Taxon</i>., 20, 509–517</p>
                <p>Maxted, N., Ford-Lloyd, B.V., Jury, S.L., Kell, S.P. and Scholten, M.A., (2006). Towards a definition of a crop wild relative. <i>Biodiversity and Conservation</i>, 15(8): 2673–2685</p>
            </div>
            <div id="logos">
                <a href="http://www.croptrust.org" target="_blank">
                    <img id="gcdt-logo" src="http://www.cwrdiversity.org/wp-content/uploads/2012/02/GCDTlogo.png" alt="" style="height: 80px; padding-left: 30px;" />
                </a>
                <a href="http://www.birmingham.ac.uk/" target="_blank">
                    <img id="ubhami-logo" src="http://www.cwrdiversity.org/wp-content/uploads/2012/01/ubham.jpg" alt="" style="height: 40px; padding-left: 30px;"/>
                </a>
            </div>
        </div>  

        <div id="advanced-search">
            <fieldset class="search-box">
                <legend class="legend">Advanced Search</legend>
                <form id="form-advanced-search"  action="advanced-search-details.php" method="post">
                    <p class="term">Enter a genus, taxon or crop name.</p>
                    <input class="input-text term" name="term" id="term" value="Enter a genus, taxon or crop name" />
                    <!-- Search by native distribution -->
                    <legend class="legend">Search by native distribution</legend>
                    <input id="check-countries" type="radio" name="parameter" value="countries" style="margin-left: 50px;" checked/><span>Search by country</span>
                    <input id="check-regions" type="radio" name="parameter" value="regions" style="margin-left: 50px;" /><span>Search by region</span>

                    <div id="country-search">
                        <p>Select one or more countries from the list</p>
                        <select id="search-location-adv" multiple="multiple" size="10"  class="input-text" name="country[]" >
                            <option></option>
                            <?php  $_smarty_tpl->tpl_vars['country'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['country']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['countries']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['country']->key => $_smarty_tpl->tpl_vars['country']->value){
$_smarty_tpl->tpl_vars['country']->_loop = true;
?>
                                <option><?php echo $_smarty_tpl->tpl_vars['country']->value;?>
</option>
                            <?php } ?>
                        </select> 
                    </div>

                    <div id="region-search">
                        <p>Select one or more regions from the list</p>
                        <select id="search-regions-adv" multiple="multiple" size="10"  class="input-text" name="region[]" >
                            <option></option>
                            <?php  $_smarty_tpl->tpl_vars['region'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['region']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['regions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['region']->key => $_smarty_tpl->tpl_vars['region']->value){
$_smarty_tpl->tpl_vars['region']->_loop = true;
?>
                                <option><?php echo $_smarty_tpl->tpl_vars['region']->value;?>
</option>
                            <?php } ?>
                        </select>
                    </div> 

                    <div class="clear"></div>
                    <!-- Busqueda por valores para la tabla concept -->
                    <p>Select the concept type.</p>
                    <select id="concept-type" class="input-text" name="concept-type" >
                        <option value="empty"></option>
                        <?php  $_smarty_tpl->tpl_vars['type'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['type']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['concept_types']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['type']->key => $_smarty_tpl->tpl_vars['type']->value){
$_smarty_tpl->tpl_vars['type']->_loop = true;
?>
                            <option><?php echo $_smarty_tpl->tpl_vars['type']->value;?>
</option>
                        <?php } ?>
                    </select>
                    <div class="clear"></div>
                    <p class="concept_level">Select the concept level.</p>
                    <select id="concept-level" multiple="multiple" class="input-text concept_level" name="concept-level[]" size="5">
                        <option></option>
                        <?php  $_smarty_tpl->tpl_vars['level'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['level']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['concept_levels']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['level']->key => $_smarty_tpl->tpl_vars['level']->value){
$_smarty_tpl->tpl_vars['level']->_loop = true;
?>
                            <option><?php echo $_smarty_tpl->tpl_vars['level']->value;?>
</option>
                        <?php } ?>
                    </select>
                    <div class="clear"></div>
                    <input class="more-margin-top" type="checkbox" name="priority-genera-only" id="priority-genera-only" value="Only show 29 Priority Genera results"/>Display results for the 29 genera prioritized in the global CWR Project "Adapting agriculture to climate change: collecting, protecting and preparing CWR"
                    <a id="link-more-genus-information" class="green" target="_BLANK">Show Priority Genera</a>
                    <div class="clear"></div>
                    <select id="priority-genera" multiple="multiple" class="input-text" name="priority-genera[]" size="10">
                        <?php  $_smarty_tpl->tpl_vars['genus'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['genus']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['priority_genera']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['genus']->key => $_smarty_tpl->tpl_vars['genus']->value){
$_smarty_tpl->tpl_vars['genus']->_loop = true;
?>
                            <option selected><?php echo $_smarty_tpl->tpl_vars['genus']->value;?>
</option>
                        <?php } ?>
                    </select>
                    <div class="clear"></div>
                    <input class="more-margin-top" type="checkbox" name="priority-croptaxa-only" id="priority-croptaxa-only" value="Only show Priority Taxa results"/>Display results for the 485 CWR taxa  prioritized in the global CWR Project "Adapting agriculture to climate change: collecting, protecting and preparing CWR" 
                    <a id="link-more-taxa-information" class="green" target="_BLANK">Show Priority Taxa</a>
                    <div class="clear"></div>   
                    <input id="submit-advanced-search" class="submit-button more-margin-top" type="submit" value="Search" />
                    <input id="clear-advanced-search" class="submit-button more-margin-top" type="button" value="Clear Fields" />        
                </form>
            </fieldset>
        </div>

        <div id="classic-search">
            <fieldset class="search-box">
                <!-- Search by Crop Genepool -->
                <legend class="legend">Search by crop genepool</legend>
                <form id="form-genepool" name="form-genepool">
                    <p>Enter a genus (eg. <i>Zea</i>), taxon (eg. <i>Zea mays</i>) or crop name (eg. maize).</p>
                    <input id="search-genepool" class="input-text" type="text" name="search-genepool" />
                    <input id="submit-genepool" class="submit-button" type="submit" value="Search" />
                    <img class="loader" src="<?php echo @SMARTY_IMG_URI;?>
/h-loading.gif" />
                </form>
                <!-- Result Table for Crop Genepool -->
                <table id="genepool-table" class="two-column-table">
                    <td id="close-table"><img src="<?php echo @SMARTY_IMG_URI;?>
/close-green.png"></td>
                    <thead class="fixed-header">
                        <tr>
                            <th>Taxa</th>
                            <th>Common Name</th>
                        </tr>
                    </thead>
                    <tbody id="table-content-genepool" class="scroll-content">
                    </tbody>
                </table>
            </fieldset>

            <fieldset class="search-box">
                <legend class="legend">Search by crop wild relative</legend>

                <!-- Search by crop wild relative -->
                <form id="form-cwr" name="form-cwr">
                    <p>Enter a genus (eg. <i>Zea</i>) or taxon (eg. <i>Zea diploperennis</i>).</p>
                    <input id="search-cwr" class="input-text" type="text" name="search-cwr" />
                    <input id="submit-cwr" class="submit-button" type="submit" value="Search" />
                    <img class="loader" src="<?php echo @SMARTY_IMG_URI;?>
/h-loading.gif" />                
                </form>
                <!-- Result Table for Crop Wild Relative -->

                <br>

                <!-- Search by native distribution -->
                <legend class="legend">Search by native distribution</legend>
                <form id="form-location" name="form-location" action="cwr-species-list.php" method="get">
                    <p>Select your search parameter</p>
                    <input id="check-countries" type="radio" name="parameter" value="countries" style="margin-left: 50px;" checked/><span>Search by country</span>
                    <input id="check-regions" type="radio" name="parameter" value="regions" style="margin-left: 50px;" /><span>Search by region</span>

                    <div id="country-search">
                        <p>Select a country from the list.</p>
                        <select id="search-location" class="input-text" name="term" >
                            <option></option>
                            <?php  $_smarty_tpl->tpl_vars['country'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['country']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['countries']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['country']->key => $_smarty_tpl->tpl_vars['country']->value){
$_smarty_tpl->tpl_vars['country']->_loop = true;
?>
                                <option><?php echo $_smarty_tpl->tpl_vars['country']->value;?>
</option>
                            <?php } ?>
                        </select>
                        <!-- <input id="search-location" class="input-text" type="text" name="name-search" /> -->
                        <input id="submit-location" class="submit-button" type="submit" value="Search" />
                        <img class="loader" src="<?php echo @SMARTY_IMG_URI;?>
/h-loading.gif" />
                    </div>

                    <div id="region-search">
                        <p>Select a region from the list.</p>
                        <select id="search-regions" class="input-text" name="term" >
                            <option></option>
                            <?php  $_smarty_tpl->tpl_vars['region'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['region']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['regions']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['region']->key => $_smarty_tpl->tpl_vars['region']->value){
$_smarty_tpl->tpl_vars['region']->_loop = true;
?>
                                <option><?php echo $_smarty_tpl->tpl_vars['region']->value;?>
</option>
                            <?php } ?>
                        </select>
                        <!-- <input id="search-location" class="input-text" type="text" name="name-search" /> -->
                        <input id="submit-regions" class="submit-button" type="submit" value="Search" />
                        <img class="loader" src="<?php echo @SMARTY_IMG_URI;?>
/h-loading.gif" />
                    </div> 

                </form>
                <!-- Result Table for country list -->
                <table id="location-table" class="two-column-table">
                    <thead class="fixed-header">
                        <tr>
                            <th>Country Name</th>
                            <th>Species</th>
                        </tr>
                    </thead>
                    <tbody id="table-content-location" class="scroll-content">
                    </tbody>
                </table>
                <br>

                <!-- Search by use -->
                <legend class="legend">Search by breeding use</legend>
                <form id="form-use" name="form-use" action="cwr-species-list.php" method="get">
                    <p>Select a use from the list.</p>
                    <input type="hidden" name="search-type" value="breeding-use"/>
                    <select id="name-use" class="input-text" name="term">
                        <option></option>
                        <?php  $_smarty_tpl->tpl_vars['use'] = new Smarty_Variable; $_smarty_tpl->tpl_vars['use']->_loop = false;
 $_from = $_smarty_tpl->tpl_vars['breedingUses']->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
foreach ($_from as $_smarty_tpl->tpl_vars['use']->key => $_smarty_tpl->tpl_vars['use']->value){
$_smarty_tpl->tpl_vars['use']->_loop = true;
?>
                            <option><?php echo $_smarty_tpl->tpl_vars['use']->value;?>
</option>
                        <?php } ?>
                    </select>                
                    <input id="submit-use" class="submit-button" type="submit" value="Search" />
                    <img class="loader" src="<?php echo @SMARTY_IMG_URI;?>
/h-loading.gif" />
                </form>
            </fieldset>

        </div>

        <div class="cwrchecklist-citation strench">
            <h2>CWR Checklist Citation</h2>
            <p>
                Vincent, H. et al. (2013) <a href="http://linkinghub.elsevier.com/retrieve/pii/S0006320713002851" target="_blank"> A prioritized crop wild relative inventory to help underpin global food security.</a> <em>Biological Conservation</em> 167: 265–275. 
            </p>
        </div>

    </div><!-- #cwr-search -->
</div><!-- #post -->
<?php }} ?>