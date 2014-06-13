<!-- JAVASCRIPTS -->
<script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/jquery-ui/jquery-ui-1.10.0.custom.min.js"></script>
<script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/jquery.jnotify/jquery.jnotify.min.js"></script>
<!-- Tooltips from: http://vadikom.com/tools/poshy-tip-jquery-plugin-for-stylish-tooltips -->
<script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/poshytip-1.1/jquery.poshytip.min.js"></script>
<script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/search.js"></script>

<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/jquery-ui-1.8.17.custom.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/jquery.jnotify.min.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_JS_URI}/poshytip-1.1/tip-green/tip-green.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/search.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/general.css" />
<link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/cwr-species-list.css" />
<!-- Header Title -->
<script type="text/javascript">
    document.title = "{$type}";
</script>

<div class="go-back"> 
    <a href="{$smarty.const.SMARTY_URL_CHECKLIST}">
        <img src="{$smarty.const.SMARTY_IMG_URI}/arrow_return.png">
        Back
    </a>
</div>

<p id="title">{$type}</p>

<div id="fullwidth" class="post">
    <div id="taxa-list-information">
        {if isset($data)}
            {if $type=="Priority Genus"}
                {assign var="values" value=$data}
                <ul>
                    {foreach from=$values item=item}
                        <li>{$item}</li>
                    {/foreach}
                </ul> 
            {else}
                <div class="wrapper">
                    <ul>
                        {foreach from=$taxa_left item=taxon}
                            <li>{$taxon}</li>
                        {/foreach}
                    </ul>
                </div>
                <div class="wrapper">
                    <ul>
                        {foreach from=$taxa_right item=taxon}
                            <li>{$taxon}</li>
                        {/foreach}
                    </ul>
                </div>
            {/if}
        {/if}
    </div>
    <div class="go-back"> 
        <a href="{$smarty.const.SMARTY_URL_CHECKLIST}">
            <img src="{$smarty.const.SMARTY_IMG_URI}/arrow_return.png">
            Back
        </a>
    </div>
</div><!-- #post -->
