<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/cwr-species-list.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/general.css" />
<script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/cwr-species-list.js"></script>

<!-- Header Title -->
<script type="text/javascript">
    document.title =  "Results for: \"{$smarty.get.term|replace:"\\'":"'"}\"";
</script>

<!-- start temporal text -->
<!--<p style="color: red; font-weight: bold; text-align: center; font-size: 16px; margin-bottom: 20px">This page is currently under development and will be released shortly</p>-->
<!-- end temporal texto -->

<div class="post">
    <p id="title">
        Search results for "{$smarty.get.term|replace:"\\'":"'"}"
    </p>
    <div class="go-back"> 
        <a href="{$smarty.const.SMARTY_URL_CHECKLIST}">
            <img src="{$smarty.const.SMARTY_IMG_URI}/arrow_return.png">
            Back
        </a>
    </div>
    <div id=count-box>        
        <p id="count-taxa" class="text-aling-right">
            {if isset($countryCode)}
                <img src="{$smarty.const.SMARTY_IMG_URI}/countries/{$countryCode}.png"/>
            {/if}
            Total {count($taxa_left)+count($taxa_right)} taxa
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
    {/if}
    <div id="taxa-list">
        <div class="wrapper more-top">
            <ul>
                {foreach from=$taxa_left item=taxon}
                    <li><a href="cwr-details.php?specie_id={$taxon->getId()}"> {$taxon->generateScientificName(true, true)} </a></li>
                {/foreach}
            </ul>
        </div>
        <div class="wrapper">
            <ul>
                {foreach from=$taxa_right item=taxon}
                    <li><a href="cwr-details.php?specie_id={$taxon->getId()}"> {$taxon->generateScientificName(true, true)} </a></li>
                {/foreach}
            </ul>
        </div>
        <br />
        <div class="cwrchecklist-citation auto">
            <h2 class="blue" >CWR Checklist Citation</h2>
            Vincent, H. et al. (2013) <a href="http://linkinghub.elsevier.com/retrieve/pii/S0006320713002851" target="_blank"> A prioritized crop wild relative inventory to help underpin global food security.</a> <em>Biological Conservation</em> 167: 265–275. 
        </div>
    </div>
</div>

