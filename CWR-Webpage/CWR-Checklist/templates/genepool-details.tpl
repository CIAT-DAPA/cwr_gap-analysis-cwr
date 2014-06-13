<!-- JAVASCRIPTS -->
<script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/jquery-ui/jquery-ui-1.10.0.custom.min.js"></script>
<!-- Tooltips from: http://vadikom.com/tools/poshy-tip-jquery-plugin-for-stylish-tooltips -->
<script type='text/javascript' src='https://www.google.com/jsapi'></script> 
<script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/poshytip-1.1/jquery.poshytip.min.js"></script>
<script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/genepool-details.js"></script>

<!-- STYLESHEETS -->
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_JS_URI}/poshytip-1.1/tip-green/tip-green.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/jquery-ui-1.8.17.custom.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/cwrgen-details.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/genepool-details.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/general.css" />

<!-- start temporal text -->
<!--<p style="color: red; font-weight: bold; text-align: center; font-size: 16px; margin-bottom: 20px">This page is currently under development and will be released shortly</p>--!>
<!-- end temporal texto -->

<div id="fullwidth" class="post">

    <div id="cwr-genepool">
        <div class="go-back"> 
            <a href="{$smarty.const.SMARTY_URL_CHECKLIST}">
                <img src="{$smarty.const.SMARTY_IMG_URI}/arrow_return.png">
                Back
            </a>
        </div>

        <script type="text/javascript">
                document.title =  "Gene Pool";
        </script>

        {foreach from=$genePools item="genePool" key="key"}
            {if isset($taxa)}
                {if $key != 0}
                    <p id="title" class="more-margin-top full-size">
                        {$taxons[$key]->generateScientificName(true,true)}
                    </p>
                {else}
                    <p id="title" class="full-size">
                        {$taxons[$key]->generateScientificName(true,true)}
                    </p>
                {/if}
                <!-- Crop Taxa-->
                <div id="synonyms" class="box">
                    <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png" style="display: none" />
                    <img class="more-genepool" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png" />
                    {if count($cropTaxa[$key]) == 1}
                        <h6 class="box-header">Crop Taxon:</h6>
                    {else}
                        <h6 class="box-header">Crop Taxa:</h6>
                    {/if}
                    <div class="box-content">
                        {assign var="family" value=$taxa[$key]->getFamily()}
                        {if isset($family)}
                            <h4>Family </h4><span class="list-more-left">{$taxa[$key]->getFamily()}</span>
                        {/if}
                        <h4>Taxon </h4>
                        {foreach from=$cropTaxa[$key] item=cropTaxon}
                            <ul class="list-more-left">
                                <li><span id="taxon-name">{$cropTaxon->generateScientificName(true, true)}</span></li>
                            </ul>
                        {/foreach}
                        <!-- Common Name -->
                        {assign var="commonName" value=$taxons[$key]->getCommonName()}
                        {if isset($commonName)}
                            <h4>Common Name</h4><span class="list-more-left">{$taxons[$key]->getCommonName()}</span>
                        {/if}
                    </div> <!-- box content -->
                </div> <!-- box -->

                <!-- Synonyms -->
                {assign var="synonyms" value=$taxa[$key]->getSynonyms()}
                {if isset($synonyms)}
                    <div class="box">
                        <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png" style="display: none" />
                        <img class="more-genepool" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png" />
                        <h6 class="box-header">Main Synonyms:</h6>
                        <div class="box-content">
                            <ul class="list-more-left">
                                {foreach from=$taxa[$key]->getSynonyms() item=synonym}
                                    <li>{$synonym->generateScientificName(true, true)}</li>
                                {/foreach}
                            </ul>
                        </div>
                    </div> <!-- box -->
                {/if}

                <!-- Classification Refereces -->
                {assign var="classificationReferences" value=$taxa[$key]->getClassificationReferences()}
                {if count($classificationReferences) > 0}
                    <div class="box">
                        <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png" style="display: none" />
                        <img class="more-genepool" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png" />
                        <h6 class="box-header">Classification references:</h6>
                        <ul class="box-content">
                            {foreach from=$taxa[$key]->getClassificationReferences() item=references}
                                <li>{$references->getReference()}</li>
                            {/foreach}
                        </ul>
                    </div> <!-- box -->
                {/if}

                <!-- Taxon Group Concept -->
                {assign var="taxonGroupConcept" value=$genePool->getTaxaByConceptLevels()}
                {if count($taxonGroupConcept) > 0}
                    <div class="box">
                        <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png" style="display: none" />
                        <img class="more-genepool" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png" />
                        <h6 id="concept-type" class="box-header">{$genePool->getMainCrop()->getConceptType()}:</h6>
                        <div class="box-content">
                            <div id="download-box">
                                <p id="content-download-box">
                                    {if isset($genePoolsIDs)}
                                        <a href="download.php?term[]={$genePoolsIDs[$key]}">
                                            <img src="{$smarty.const.SMARTY_IMG_URI}/save.png"/>
                                            Download
                                        </a>
                                    {/if}
                                </p>
                            </div> 
                            <ul>
                                {foreach $taxonGroupConcept as $conceptLevel => $concepts}
                                    <li class="concept-level" >{$conceptLevel}</li>
                                    <ul class="list-more-right">
                                        {foreach from=$concepts item=taxonConcept}
                                            <li><a href="cwr-details.php?specie_id={$taxonConcept->getId()}">{$taxonConcept->getScientificName(true, true)}</a></li>
                                            <!--
                                            {if $taxonConcept->getMainCrop() == 0}
                                                <li><a href="cwr-details.php?specie_id={$taxonConcept->getId()}">{$taxonConcept->getScientificName(true, true)}</a></li>
                                            {else}
                                                <li><a href="genepool-details.php?id[]={$taxonConcept->getId()}">{$taxonConcept->getScientificName(true, true)}</a></li>
                                            {/if}-->
                                        {/foreach}
                                    </ul>
                                {/foreach}
                            </ul>
                            <!-- Concept References -->
                            {assign var="conceptReferences" value=$genePool->getMainCrop()->getConceptReferences()}
                            {if count($conceptReferences) > 0}
                                <h4>Concept References:</h4>
                                <ul class="list-more-left">
                                    {foreach from=$conceptReferences item=conceptReference}
                                        <li>{$conceptReference->getReference()}</li>
                                    {/foreach}
                                </ul>
                            {/if}
                        </div> <!-- box content -->
                    </div> <!-- box -->
                {/if}

                <!-- Use Breeding -->
                {assign var="cropBreedingUses" value=$genePool->getCropBreedingByUseType()}
                {if count($cropBreedingUses) > 0}
                    <div class="box">
                        <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png" style="display: none" />
                        <img class="more-genepool" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png" />
                        <h6 class="box-header">Taxa used in crop breeding:</h6>
                        <ul class="box-content">
                            {foreach $cropBreedingUses as $useType => $breedingUses}
                                <li class="use-type">{$useType}</li>
                                <ul class="list-more-right">
                                    {foreach from=$breedingUses item=breedingUse}
                                        <li>
                                            <p>
                                                {if $breedingUse->getTaxon()->getMainCrop() == 1}
                                                    <a href="?specie_id={$breedingUse->getTaxon()->getId()}">
                                                    {else}
                                                        <a href="cwr-details.php?specie_id={$breedingUse->getTaxon()->getId()}">
                                                        {/if}                                        
                                                        {$breedingUse->getTaxon()->getScientificName()}
                                                    </a>:                                   
                                                    {$breedingUse->getDescription()}
                                            </p>
                                            {$breedingUse->getReference()}
                                        </li>
                                    {/foreach}
                                </ul>
                            {/foreach}
                        </ul>
                    </div> <!-- box -->
                {/if}

                <!-- Herbaria Data -->
                {assign var="herbaria" value=$taxa[$key]->getHerbaria()}
                {if count($herbaria) > 0}
                    <div class="box">
                        <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png" style="display: none" />
                        <img class="more-genepool" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png" />
                        <h6 class="box-header">Herbaria:</h6>
                        <div id="herbaria" class="box-content">
                            <ul class="list-more-left">
                                {foreach from=$herbaria item=herbarium}
                                    <li><span class="herbariaToolTip" title="{$herbarium->getDetailsInHTML()}">{$herbarium->getInstitutionName()}</span></li>
                                {/foreach}
                            </ul>
                        </div> <!-- box-content -->
                    </div> <!-- box -->
                {/if}

            {else}
                <h3>NO DATA</h3>
            {/if}
        {/foreach}

        <div class="box">
            <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png" style="display: none" />
            <img class="more-genepool" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png" />
            <h6 class="box-header">CWR Checklist Citation:</h6>
            <div class="box-content">
                <p class="genepool-citation">
                    Vincent, H. et al. (2013) <a href="http://linkinghub.elsevier.com/retrieve/pii/S0006320713002851" target="_blank"> A prioritized crop wild relative inventory to help underpin global food security.</a> <em>Biological Conservation</em> 167: 265–275. 
                </p>
            </div>
        </div>

        <div class="go-back"> 
            <a href="{$smarty.const.SMARTY_URL_CHECKLIST}">
                <img src="{$smarty.const.SMARTY_IMG_URI}/arrow_return.png">
                Back
            </a>
        </div>
    </div>
</div><!-- #post -->
