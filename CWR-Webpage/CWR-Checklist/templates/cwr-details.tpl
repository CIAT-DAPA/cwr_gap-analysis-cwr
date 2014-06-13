<!-- JAVASCRIPTS -->
<script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/jquery-ui/jquery-ui-1.10.0.custom.min.js"></script>
<script type='text/javascript' src='https://www.google.com/jsapi'></script> <!-- Google Charts -->
<!-- Tooltips from: http://vadikom.com/tools/poshy-tip-jquery-plugin-for-stylish-tooltips -->
<script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/poshytip-1.1/jquery.poshytip.min.js"></script>
<script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/cwrgen-details.js"></script>
<script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/cwr-details.js"></script>

<!-- STYLESHEETS --> 
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_JS_URI}/poshytip-1.1/tip-green/tip-green.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/jquery-ui-1.8.17.custom.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/cwrgen-details.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/cwr-details.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/general.css" />
 

<!-- start temporal text -->
<!--<p style="color: red; font-weight: bold; text-align: center; font-size: 16px; margin-bottom: 20px">This page is currently under development and will be released shortly</p>--!>
<!-- end temporal texto -->


<div id="fullwidth" class="post">

    <div id="cwr-genepool">
        {if isset($taxon)}
            <!-- Header Title -->
            <script type="text/javascript">
                document.title =  "{$taxon->generateScientificName(true, false)}";
            </script>

            <!-- Crop Taxa-->
            <p id="title">{$taxon->generateScientificName(true, true)}</p>

            {foreach from=$mainTaxon item=maintx}
                {if $maintx->Concept_Type != "graftstock" && !$maintx->Concept_Type|strstr:"[PT]"}
                    <p id="link-to-genepool"><a href="genepool-details.php?id[]={$maintx->taxon->getId()}"><span>{$maintx->Concept_Type} {$maintx->Concept_Level} relative of </span>{$maintx->taxon->generateScientificName(true,false)}</a></p>
                {elseif $maintx->Concept_Level == "Confirmed" && $maintx->Concept_Type != "graftstock"}   
                    <p id="link-to-genepool"><a href="genepool-details.php?id[]={$maintx->taxon->getId()}"><span>{$maintx->Concept_Level} use in breeding for </span>{$maintx->taxon->generateScientificName(true,false)}</a></p>
                {elseif $maintx->Concept_Level == "Potential" && $maintx->Concept_Type != "graftstock"}
                    <p id="link-to-genepool"><a href="genepool-details.php?id[]={$maintx->taxon->getId()}"><span>{$maintx->Concept_Level} use in crop breeding for </span>{$maintx->taxon->generateScientificName(true,false)}</a></p>
                {else}
                    <p id="link-to-genepool"><a href="genepool-details.php?id[]={$maintx->taxon->getId()}"><span>{$maintx->Concept_Level} use as {$maintx->Concept_Type} for </span>{$maintx->taxon->generateScientificName(true,false)}</a></p>
                {/if}
            {/foreach}


            <div class="go-back"> 
                <a href="{$smarty.const.SMARTY_URL_CHECKLIST}">
                    <img src="{$smarty.const.SMARTY_IMG_URI}/arrow_return.png">
                    Back
                </a>
            </div>
            <!-- Crop Taxa-->
            <div class="box">
                <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png" style="display: none" />
                <img class="more" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png" />
                <h6 class="box-header">Taxon:</h6>
                <div class="box-content">
                    
                    {assign var="family" value=$taxon->getFamily()}
                    {if isset($family)}
                        <h3>Family </h3><span class="list-more-left">{$taxon->getFamily()}</span>
                    {/if}
                    <h3>Taxon </h3>
                    <ul class="list-more-left">
                        <li><span id="taxon-name">{$taxon->generateScientificName(true, true)}</span></li>
                    </ul>
                    <!-- Common Name -->
                    {assign var="commonName" value=$taxon->getCommonName()}
                    {if isset($commonName) && $commonName != ""}
                        <h3>Common Name</h3><span class="list-more-left">{$taxon->getCommonName()}</span>
                    {/if}
                </div> <!-- box content -->
            </div> <!-- box -->
            <!-- Synonyms -->
            {assign var="synonyms" value=$taxon->getSynonyms()}
            {if isset($synonyms)}
                <div id="synonyms" class="box">
                    <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png" style="display: none" />
                    <img class="more" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png" />
                    <h6 class="box-header">Main Synonyms:</h6>
                    <div class="box-content">
                        <ul class="list-more-left">
                            {foreach from=$taxon->getSynonyms() item=synonym}
                                <li>{$synonym->generateScientificName(true, true)}</li>
                            {/foreach}
                        </ul>
                    </div>
                </div>
            {/if}
            <!-- Classification Refereces -->
            {assign var="classificationReferences" value=$taxon->getClassificationReferences()}
            {if count($classificationReferences) > 0}
                <div id="classification-ref" class="box">
                    <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png" />
                    <img class="more" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png" />
                    <h6 class="box-header">Classification references:</h6>
                    <div class="box-content">
                        <ul class="list-more-left">
                            {foreach from=$taxon->getClassificationReferences() item=references}
                                <li>{$references->getReference()}</li>
                            {/foreach}
                        </ul>
                    </div>
                </div>
            {/if}

            <!-- Geographical Distributions -->
            {if count($taxon->getGeographicDistributions()) > 0}
                <div id="classification-ref" class="box">
                    <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png" />
                    <img class="more" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png" />
                    <h6 class="box-header">Geographical distribution:</h6>
                    <div class="box-content">
                        <div id="map">
                            <!-- Google Map for Geographic Distribution -->
                            {assign var="countries" value=$taxon->getCountries()}
                            {if count($countries) > 0}
                                <script type='text/javascript'>
                                    var countries = new Array();
                                    var isos = new Array();
                                    {foreach from=$countries item=country name=loopCountry}
                                        isos[{$smarty.foreach.loopCountry.index}] = '{$country->getIso2()}';
                                        countries[{$smarty.foreach.loopCountry.index}] = '{$country->getName()}';
                                    {/foreach}
                                </script>
                                {literal}
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
                                {/literal}
                                <div id="div-geographic"></div>
                            {/if}
                        </div> <!-- map -->
                        <div id="country-list" >

                            {foreach from=$taxon->getGeographicDistributions() item=distributionType}
                                <h5>{$distributionType->getName()}</h5>
                                {if count($distributionType->getRegions()) > 0}
                                    <!-- ul -->
                                    {foreach from=$distributionType->getRegions() item=region}
                                        <h4 class="mar-left">{$region->getName()}</h4>
                                        {if count($region->getCountries()) > 0}
                                            <ul class="list-more-left">
                                                {foreach from=$region->getCountries() item=country}
                                                    <li><script>
                                                            var string = "{$country->getName()}";
                                                            var array = string.split("-");
                                                            var temp = "";
                                                            for(i=0;i < array.length;i++){
                                                                temp += String.fromCharCode(array[i]);
                                                            }
                                                            document.write(temp);
                                                        </script>
                                                        {assign var="details" value=$country->getDetails()}
                                                        {if isset($details)}
                                                            : {$details}
                                                        {/if}
                                                    </li>
                                                {/foreach}
                                            </ul>
                                        {/if}
                                    {/foreach}
                                    <!-- /ul -->
                                {/if}
                            {/foreach}

                        </div> <!-- country-list -->

                        <!-- Geographical Distributions References -->

                        {assign var="distributionReferences" value=$taxon->getGeographicDistributionReferences()}
                        {if count($distributionReferences) > 0}
                            <h4 class="mar-left" id="geographic-ref">References:</h4>
                            <ul class="list-more-left">
                                {foreach from=$distributionReferences item=distributionReference}
                                    <li>{$distributionReference->getReference()}</li>
                                {/foreach}
                            </ul>
                        {/if}
                    </div> <!-- box-content -->
                </div> <!-- classification-ref -->
            {/if}

            <!-- Taxon Usage -->
            {assign var="utilizations" value=$taxon->getUtilizations()}
            {if count($utilizations) > 0}
                <div id="classification-ref" class="box">
                    <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png" />
                    <img class="more" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png" />
                    <h6 class="box-header">Taxon Usage:</h6>
                    <div id="taxon-usage" class="box-content">
                        <ul class="list-more-left">
                            {foreach from=$utilizations item=utilization}
                                <li>
                                    {$utilization->getType()}
                                    {if $utilization->getUse() != null}
                                        ({ucfirst($utilization->getUse())})
                                    {/if}
                                </li>
                            {/foreach}
                        </ul>
                        <!-- Taxon Usage References -->
                        {assign var="utilizationReferences" value=$taxon->getUtilizationReferences()}
                        {if count($utilizationReferences) > 0}
                            <h4 class="mar-left">References:</h4>
                            <ul class="list-more-left">
                                {foreach from=$utilizationReferences item=utilizationReference}
                                    <li><i>{$utilizationReference->getAuthor()}</i> - {$utilizationReference->getName()}</li>
                                {/foreach}
                            </ul>
                        {/if}
                    </div> <!-- taxon usage -->
                </div> <!-- box -->
            {/if}


            <!-- Use Breeding -->
            {assign var="cropBreedingUses" value=$cwr->getTaxonBreedingByUseType()}
            {if count($cropBreedingUses) > 0}
                <div id="classification-ref" class="box">
                    <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png" />
                    <img class="more" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png" />
                    <h6 class="box-header">Breeding uses:</h6>
                    <div class="box-content">
                        <ul>
                            {foreach $cropBreedingUses as $useType => $breedingUses}
                                <li class="use-type">{$useType}</li>
                                <ul class="list-more-left">
                                    {foreach from=$breedingUses item=breedingUse}
                                        <li>
                                            <p>
                                                {$breedingUse->getTaxon()->generateScientificName(true, true)}:
                                                {$breedingUse->getDescription()}
                                            </p>
                                            {$breedingUse->getReference()}
                                        </li>
                                    {/foreach}
                                </ul>
                            {/foreach}
                        </ul>
                    </div>
                </div> <!-- box -->
            {/if}

            <!-- Storage Behavior -->
            {assign var="storageBehavior" value=$cwr->getStorageBehavior()}
            {if !empty($storageBehavior)}
                <div class="box">
                    <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png" />
                    <img class="more" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png" />
                    <h6 class="box-header">Storage behavior for the genus:</h6>
                    <div class="box-content">
                        <script type='text/javascript'>
                            var storages = new Array();
                            storages[0] = {$storageBehavior->getOrthodox()}
                            storages[1] = {$storageBehavior->getIntermeduate()}
                            storages[2] = {$storageBehavior->getRecalcitrant()}
                            storages[3] = {$storageBehavior->getUnknown()}
                        </script>
                        {literal}
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

                        {/literal}
                        <div id="pie-container" class="left">
                            <div id="storage_pie"></div>
                        </div>
                        <span>For storage behavior and other seed information for this species search <a href="http://data.kew.org/sid/sidsearch.html">here</a></span>
                        <h4>Reference:</h4>
                        <span>{$storageBehavior->getReference()}</span>
                    </div> <!-- box-content -->
                </div> <!-- box -->

            {/if}

            <!-- Herbaria Data -->
            {assign var="herbaria" value=$taxon->getHerbaria()}
            {if count($herbaria) > 0}
                <div class="box">
                    <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png" />
                    <img class="more" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png" />
                    <h6 class="box-header">Herbaria:</h6>
                    <div id="herbaria" class="box-content">
                        <ul class="list-more-left">
                            {foreach from=$herbaria item=herbarium}
                                <li><span class="herbariaToolTip" title="{$herbarium->getDetailsInHTML()}">{$herbarium->getInstitutionName()}</span></li>
                            {/foreach}
                        </ul>
                    </div>
                </div> <!-- box -->
            {/if}

        {else}
            <h3>NO DATA</h3>
            <script type="text/javascript">
                document.title =  "NO DATA" ;
            </script>
        {/if}

        <div class="box">
            <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png" style="display: none" />
            <img class="more" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png" />
            <h6 class="box-header">CWR Checklist Citation:</h6>
            <div class="box-content">
                <p class="genepool-citation">
                    Vincent, H. et al. (2013) <a href="http://linkinghub.elsevier.com/retrieve/pii/S0006320713002851" target="_blank"> A prioritized crop wild relative inventory to help underpin global food security.</a> <em>Biological Conservation</em> 167: 265–275. 
                </p>
            </div>
        </div>
    </div>

    <div class="go-back"> 
        <a href="{$smarty.const.SMARTY_URL_CHECKLIST}">
            <img src="{$smarty.const.SMARTY_IMG_URI}/arrow_return.png">
            Back
        </a>
    </div>

</div><!-- #post -->