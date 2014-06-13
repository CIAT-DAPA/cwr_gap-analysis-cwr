<!-- JAVASCRIPTS -->
<script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/jquery-ui/jquery-ui-1.10.0.custom.min.js"></script>
<script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/jquery.jnotify/jquery.jnotify.min.js"></script>
<!-- Tooltips from: http://vadikom.com/tools/poshy-tip-jquery-plugin-for-stylish-tooltips -->
<script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/poshytip-1.1/jquery.poshytip.min.js"></script>
<script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/advanced-search-details.js"></script>

<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/jquery-ui-1.8.17.custom.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/jquery.jnotify.min.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_JS_URI}/poshytip-1.1/tip-green/tip-green.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/advanced-search-details.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/general.css" />
<!-- Header Title -->
<script type="text/javascript">
    document.title =  "Advanced Search Results" ;
</script>

<div id="fullwidth" class="post">
    <div id="advanced-search-details">
        <div class="post">
            <p id="title">
                Advanced Search Results
            </p>
            <div class="go-back"> 
                <a href="{$smarty.const.SMARTY_URL_CHECKLIST}">
                    <img src="{$smarty.const.SMARTY_IMG_URI}/arrow_return.png">
                    Back
                </a>
            </div>
            <div id="count-box">        
                <p id="count-taxa" class="text-aling-right">
                    {if isset($countryCode)}
                        <img src="{$smarty.const.SMARTY_IMG_URI}/countries/{$countryCode}.png"/>
                    {/if}
                    Total {count($taxa_left)+count($taxa_right)} Results
                </p>
            </div>
            {if !isset($cwrName)}
                    <div class="text-aling-left">
                        <form action="download.php" method="post" class="right download">
                            <img src="{$smarty.const.SMARTY_IMG_URI}/save.png" class="left"/>
                            {foreach from=$taxa item="taxon"}
                                <input type="hidden" name="term[]" value={$taxon->getId()}>
                            {/foreach}
                            <input id="download-taxa" type="submit" value="Download" />
                        </form>
                    </div>
           <!-- <p id="count-taxa" class="text-aling-left">
                
            </p>-->
            {/if}
            <div id="taxa-list">
                <div class="wrapper more-top">
                    <ul>
                        {foreach from=$taxa_left item=taxon}
                            {if $taxon->getMainCrop() == 0}
                                <li><a href="cwr-details.php?specie_id={$taxon->getId()}"> {$taxon->generateScientificName(true, true)} </a></li>
                            {/if}
                            {if $taxon->getMainCrop() == 1}
                                <li><a href="genepool-details.php?id[]={$taxon->getId()}"> {$taxon->generateScientificName(true, true)} </a></li>
                            {/if}
                        {/foreach}
                    </ul>
                </div>
                <div class="wrapper less-top">
                    <ul>
                        {foreach from=$taxa_right item=taxon}
                            {if $taxon->getMainCrop() == 0}
                                <li><a href="cwr-details.php?specie_id={$taxon->getId()}"> {$taxon->generateScientificName(true, true)} </a></li>
                            {/if}
                            {if $taxon->getMainCrop() == 1}
                                <li><a href="genepool-details.php?id[]={$taxon->getId()}"> {$taxon->generateScientificName(true, true)} </a></li>
                            {/if}
                        {/foreach}
                    </ul>
                </div>
                {if count($taxa_left)==0 && count($taxa_right==0)}
                    <div>No data</div>
                {/if}
                <br />
                <div class="cwrchecklist-citation auto">
                    <h2 class="blue" >CWR Inventory Citation</h2>
                    Vincent, H. et al. (2013) <a href="http://linkinghub.elsevier.com/retrieve/pii/S0006320713002851" target="_blank"> A prioritized crop wild relative inventory to help underpin global food security.</a> <em>Biological Conservation</em> 167: 265–275. 
                </div>
            </div>
        </div>

    </div>
</div>