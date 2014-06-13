<!-- Header Title -->
<html>
    <header>
        <script type="text/javascript">
            document.title =  "CWR Distribution Map" ;
            var taxonomy = [
            {foreach from=$taxonomyList item=taxonItem name=taxonloop}
                {if not $smarty.foreach.taxonloop.last}
                    "{$taxonItem->getTaxonomy()}",
                {else}
                    "{$taxonItem->getTaxonomy()}"
                {/if}
            {/foreach}
            ];
            var cropcodes = [
            {foreach from=$cropcodeList item=taxonItem name=taxonloop}
                {if not $smarty.foreach.taxonloop.last}
                    "{$taxonItem->getCropCode()}",
                {else}
                    "{$taxonItem->getCropCode()}"
                {/if}
            {/foreach}
            ];
            window.onload = function(){
                initialize();
            }
        </script>
        <!-- Seccion para los Archivos de estilos -->
        <link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/jquery-ui-1.8.17.custom.css" />
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" />
        <link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/map.css" />
        <link rel="stylesheet" type="text/css" href="{$smarty.const.SMARTY_CSS_URI}/reveal.css" />
        <link rel="shortcut icon" href="{$smarty.const.SMARTY_IMG_URI}/logo-favicon.ico">

        <!-- Seccion para los Scripts  -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js" type="text/javascript"></script>
        <script src="http://code.jquery.com/ui/1.10.2/jquery-ui.js"></script>
        <script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/cwr-map.js"></script>
        <script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/map-search-manager.js"></script>
        <!-- Scripts para la carga del mapa de distribucion -->
        <script src="http://maps.google.com/maps/api/js?sensor=true" type="text/javascript"></script>
        <script type="text/javascript" src="http://www.google.com/jsapi"></script>
        <!-- Script para despliegue de ventana modal -->
        <script type="text/javascript" src="{$smarty.const.SMARTY_JS_URI}/jquery.reveal.js"></script>
        <!--[if IE]>
        <link rel="stylesheet" type="text/css" href="http://www.cwrdiversity.org/CWR-DistributionMap/themes/yamidoo-child/css/ie.css" />
        <![endif]-->
    </header>
    <!-- Encargado de desplegar el control del mapa -->
    <body>
        <div id="searchTypeControl">
            <div class="species">
                <div class="header">
                </div>

                <div class="title">
                    Available Layers for CWR Taxon
                </div>
                <div id="typeMapForm">
                    <div class="typeMap" id="points">
                        <img src="{$smarty.const.SMARTY_IMG_URI}/check.png"/>
                        Occurrence data
                    </div>
                    <div class="typeMap" id="gap">
                        <img src="{$smarty.const.SMARTY_IMG_URI}/check.png"/>
                        Potential distribution map
                    </div>
                    <div class="typeMap" id="gap_spp">
                        <img src="{$smarty.const.SMARTY_IMG_URI}/check.png"/>
                        Collecting priorities map
                    </div>
                </div> 

                <div id="conservation-section">
                    <div class="title">
                        <i>Ex situ</i> Conservation Status
                    </div>
                    <div id="conservation-status">
                        <div id="HPS" class="left">
                            <div></div>
                            HPS
                        </div>
                        <div id="MPS" class="left">
                            <div></div>
                            MPS
                        </div>
                        <div id="LPS" class="left">
                            <div></div>
                            LPS
                        </div>
                        <div id="NFCR" class="left">
                            <div></div>
                            NFCR
                        </div>
                    </div>
                </div>
            </div>
            <div class="genepool">
                <div class="header">
                </div>

                <div class="title">
                    Summary for Crop Gene Pool
                </div>
                <div id="typeMapForm">
                    <div class="typeMap" id="genepool_species_richness">
                        <img src="{$smarty.const.SMARTY_IMG_URI}/check.png"/>
                        Taxon richness
                    </div>
                    <div class="typeMap" id="genepool_gap_richness">
                        <img src="{$smarty.const.SMARTY_IMG_URI}/check.png"/>
                        Collecting hotspots
                    </div>
                </div>

                <div class="title single-layers">
                    Individual CWR taxa
                </div>
                <div  id="single-layers-div">

                </div>
                <div id="genepool-conservation-section">
                    <div class="title">
                        <i>Ex situ</i> Conservation Status
                    </div>
                    <div id="conservation-status">
                        <div id="HPS_Genepool" class="left">
                            <div></div>
                            HPS
                        </div>
                        <div id="MPS_Genepool" class="left">
                            <div></div>
                            MPS
                        </div>
                        <div id="LPS_Genepool" class="left">
                            <div></div>
                            LPS
                        </div>
                        <div id="NFCR_Genepool" class="left">
                            <div></div>
                            NFCR
                        </div>
                    </div>
                </div>
            </div>
            <div class="global">
                <div class="title">
                    Summary of all Crop Gene Pools assessed
                </div>
                <div id="typeMapForm">
                    <div class="typeMap" id="global_species_richness">
                        <img src="{$smarty.const.SMARTY_IMG_URI}/check.png"/>
                        Combined taxon richness
                    </div>
                    <div class="typeMap" id="global_gap_richness">
                        <img src="{$smarty.const.SMARTY_IMG_URI}/check.png"/>
                        Combined collecting priorities
                    </div>
                </div>
            </div>
        </div>     
        <!-- Control para el zoom del mapa  -->
        <div id="zoomControl">
            <img id="control-plus" src="{$smarty.const.SMARTY_IMG_URI}/plus.png"/><br>
            <img id="control-minus" src="{$smarty.const.SMARTY_IMG_URI}/minus.png"/>
        </div
        <!-- Control del tipo de mapa -->
        <div id="main_page_buttons">
            <div id="roadmap" title="Map"><img src="{$smarty.const.SMARTY_IMG_URI}/map_ico_T_B3.png"/></div>
            <div id="terrain" title="Terrain"><img src="{$smarty.const.SMARTY_IMG_URI}/terrain_ico_T_B2.png"/></div>
            <div id="satellite" title="Satellite"><img src="{$smarty.const.SMARTY_IMG_URI}/satellite_ico_T_B2.png"/></div>
        </div> 
        <!-- Seccion para despliegue de Escalas de colores en las especies -->
        <div id="tituloEscalaColores" class="position">

        </div>
        <div id="escalaColores">
            <!--<img id="control-plus" src="{$smarty.const.SMARTY_IMG_URI}/sp-rich_rclassscaleTestImage.png"/>-->
        </div>
        <!-- Logo cwr bioversity, redirige a la pagina principal del proyecto -->
        <div id="cwr_diversity_logo">
            <a href="http://cwrdiversity.org" target="_BLANK">
                <img src="{$smarty.const.SMARTY_IMG_URI}/CWR logo HD.png">
            </a>
        </div>  
        <!-- Ventana modal mientras realiza proceso de carga -->
        <!--[if IE]>     
            <div id="loadingWindow" class="reveal-modal">
            </div>
        <![endif]-->
        <!--[if !IE]><!-->
        <div id="loadingWindow" class="reveal-modal">
            <img src="{$smarty.const.SMARTY_IMG_URI}/loading.gif"/>
        </div>
        <!--<![endif]-->
        <div id="no_data">

        </div>  

        <div id="video_tutorial" class="reveal-modal">
            <iframe width="500" height="480" src="//www.youtube.com/embed/O6JHveohPrk" frameborder="0" allowfullscreen></iframe>
            <a class="close-reveal-modal">&#215;</a> 
        </div>

        <div id="help_window" class="reveal-modal">
            <h1>How to use the Crop Wild Relatives Global Atlas</h1>
            There are three distinct ways to visualize results:
            <ol>
                <li>CWR Taxon- view occurrence data, distribution maps, and conservation gaps maps for individual species.</li>
                <li>Crop Gene Pool- view a summary of species distributions as well as conservation concerns for all assessed CWR in a particular crop gene pool.</li>
                <li>Global Summary- view a summary of species distributions as well as conservation concerns for all assessed CWR in all crop gene pools combined. </li>
            </ol>
            <span>
                Watch our video tutorial.
                <img class="more" src="{$smarty.const.SMARTY_IMG_URI}/television_green.png"/>
            </span>
            <br><br>
            Please click on the boxes below for instructions.
            <br>
            <div class="section">

                <div class="title">
                    <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png"/>
                    <img class="more" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png"/>
                    CWR Taxon- view individual CWR taxa
                </div>
                <div class="content">
                    Available Layers:
                    <ol>
                        <li>Occurrence data: Points from georeferenced herbarium specimens and genebank accessions used to create the potential distribution model and perform the gap analysis.</li>
                        <li>Potential distribution map: Calculated using maximum entropy (Maxent) model, with a set of bioclimatic variables and species occurrence data as inputs. In the case that a robust Maxent model was not possible, a buffer of 50 km radius was placed around occurrence points as an estimation of potential distribution. Please see the "<a href="http://www.cwrdiversity.org/conservation-gaps/" target="_BLANK">Conservation Gaps</a>" page for details.</li>
                        <li>Collecting priorities map: Derived from comparing the potential distribution map against areas that have previously been collected, to reveal uncollected geographic areas.</li>
                    </ol>
                    To explore individual species, click the "CWR Taxon" button and enter the species name in the search box. The results will first show available occurrence data points. Click on "Potential distribution map" to display the distribution model derived from occurrence data. Finally, click on "Collecting priorities map" to show areas identified as in need of further collecting. The conservation status of the species is also provided, along with a link to the associated crop gene pool.
                </div>
            </div>
            <div class="section">
                <div class="title">
                    <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png"/>
                    <img class="more" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png"/>
                    Crop Gene Pool- view summary per crop gene pool
                </div>
                <div class="content"> <!-- class="content" -->
                    Available Layers:
                    <ol>
                        <li>Taxon richness: Richness of all assessed species in the crop gene pool.</li>
                        <li>Collecting hotspots: Richness of areas where the high priority species (HPS) as determined in the analysis have not yet been collected.</li>
                    </ol>
                    <p>
                        To explore the results on the crop gene pool level, click on "Crop Gene Pool" and enter a cultivated species or its common name in the search box. The results will first display a richness map portraying the concentration of distributions of all assessed CWR species related to the crop. Clicking on "Collecting hotspots" will generate a second richness map providing concentration of distributions where species of high priority for collecting are thought to occur but have not yet been collected and conserved.
                    </p>
                    CWR of the following crops have thus far been assessed in the gap analyses. To view the CWR related to each crop, follow the link.  Only "closely related" CWR (i.e. in the primary and secondary gene pool levels or with published uses in breeding) were included in the analyses.
                    <br><br>
                    <table>
                        <tr>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=12">African rice (Oryza glaberrima)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=19">Eggplant/Aubergine (Solanum melongena)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=34">Plantain (Musa balbisiana)</a></td>
                        </tr>
                        <tr>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=29&id%5b%5d=290&id%5b%5d=288&">Alfalfa (Medicago sativa)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=24&id%5b%5d=442&id%5b%5d=441&id%5b%5d=440&">Faba bean (Vicia faba)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=20&id%5b%5d=677&id%5b%5d=678&id%5b%5d=681&">Potato (Solanum tuberosum)</a></td>
                        </tr>
                        <tr>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=11">Apple (Malus domestica)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=5">Finger Millet (Eleusine coracana)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=13">Rice (Oryza sativa)</a></td>
                        </tr>
                        <tr>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=32&id%5b%5d=149&">Bambara groundnut (Vigna subterranea)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=36">Grasspea (Lathyrus sativus)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=18">Rye (Secale cereale)</a></td>
                        </tr>
                        <tr>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=33">Banana (Musa acuminata)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=9&id%5b%5d=182&">Lentil (Lens culinaris)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=21">Sorghum (Sorghum bicolor)</a></td>
                        </tr>
                        <tr>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=7&id%5b%5d=116&">Barley (Hordeum vulgare)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=15">Lima bean (Phaseolus lunatus)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=6">Sunflower (Helianthus annuus)</a></td>
                        </tr>
                        <tr>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=4&id%5b%5d=4878&">Carrot (Daucus carota)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=1">Oat (Avena sativa)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=8&id%5b%5d=4322&">Sweet Potato (Ipomoea batatas)</a></td>
                        </tr>
                        <tr>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=3">Chickpea (Cicer arietinum)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=17&id%5b%5d=108&">Pea (Pisum sativum)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=44&id%5b%5d=467&">Vetch (Vicia sativa)</a></td>
                        </tr>
                        <tr>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=16&id%5b%5d=383&">Common bean (Phaseolus vulgaris)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=14">Pearl Millet (Pennisetum glaucum)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=22&id%5b%5d=4184&id%5b%5d=578&">Wheat (Triticum aestivum)</a></td>
                        </tr>
                        <tr>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=25&id%5b%5d=163&">Cowpea (Vigna unguiculata)</a></td>
                            <td><a target="_BLANK" href="http://www.cwrdiversity.org/checklist/genepool-details.php?id%5b%5d=2">Pigeonpea (Cajanus cajan)</a></td>
                            <td></td>
                        </tr>
                    </table>
                </div>
                <a class="close-reveal-modal">&#215;</a> 
            </div>
            <div class="section">
                <div class="title">
                    <img class="minus" src="{$smarty.const.SMARTY_IMG_URI}/circle-minus.png"/>
                    <img class="more" src="{$smarty.const.SMARTY_IMG_URI}/circle-plus.png"/>
                    Global Summary- view summary for all assessed CWR in all crop gene pools combined
                </div>
                <div class="content">
                    Available Layers:
                    <ol>
                        <li>Combined taxon richness: Richness of all assessed CWR species in all gene pools</li>
                        <li>Combined collecting priorities: Richness of areas where the high priority species (HPS) as determined in the analyses of all crop gene pools combined have not yet been collected.</li>
                    </ol>
                    <p>
                        The global summary area displays richness maps of CWR species distributions, inclusive of all CWR in all crop gene pools assessed thus far. The corollary collecting priorities map reveals those geographic areas around the world with the greatest concentration of species considered of high priority for collecting.
                    </p>
                    For an overview of the gap analysis methodology, summary of results, and further resources, see the "<a href="http://www.cwrdiversity.org/conservation-gaps/" target="_BLANK">Conservation Gaps</a>" page. 
                </div>
            </div>
        </div>


        <!-- Tabla con los generos que se utilizaron en el estudio -->
        <div id="accepted-species">

        </div>

        <!-- Muestra ventana de inicio tipo modal -->
        <div id="searchTypeWindow" class="reveal-modal">
            <h1>Crop Wild Relatives Global Atlas</h1>
            <div id="text">
                This Atlas provides information on distributions and collecting priorities for the wild relatives of important crops. 
                The application presents occurrence data points, potential distribution maps, and maps displaying areas identified as of
                priority for collecting for conservation and use.<br><br>
                The maps can be explored per CWR taxon, as a summary per crop gene pool, or as a summary of all CWR in all assessed crop gene pools.
                <br><br>
                <b>Search by:</b>
                <br>
                <div class="container">
                    <div id="w-species-search" class="left">CWR Taxon</div>
                    <div id="w-global-summary" class="right">Global Summary</div>
                    <div id="w-genepool-search" class="right">Crop Gene Pool</div>
                </div>
                <br>
                <img src="{$smarty.const.SMARTY_IMG_URI}/ciat.png" class="right" />
            </div>
            <a class="close-reveal-modal">&#215;</a> 
        </div>  

        <!-- Muestra el area del mapa y el menu  -->

        <div id="search-menu" class="opacity">
            <input id="search-value" type="text" class="tooltip" title="Please enter a species name in the search box." value=""></input>
            <input id="search-button" type="button" value="Search"></input>
            <div id="search_type" name="search_type">
                <div id="species-search">CWR Taxon</div>
                <div id="genepool-search">Crop Gene Pool</div>
                <div id="global-summary">Global Summary</div>
            </div>
            <!--
                <div id="validSpeciesButton">
                    CWR analyzed
                </div>
            -->
            <div id="helpButton">
                <img src="{$smarty.const.SMARTY_IMG_URI}/help.png"/>
            </div>
        </div> 
        <div id="map_canvas">
        </div>   
    </body>
</html> 
