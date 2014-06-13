
jQuery(document).ready(function($) {
   
    /* -----------------------------GENEPOOL DEFINITIONS--------------------------------- */
    var genepool = "gene pool:";
    var prim_level = "primary";
    var sec_level = "secondary";
    var ter_level = "tertiary";
    var gp_1a = "1a";
    var gp_1b = "1b";
    var gp_2 = "2";
    var gp_3 = "3";
    /* ------------------------------GENEPOOL CONTENTS------------------------------------*/
    var prim_level_content = "The wild or weedy forms of the crop";
    var sec_level_content = "The coenospecies (less closely related species) from which gene transfer to the crop is possible but difficult using conventional breeding techniques";
    var ter_level_content = "Includes the species from which gene transfer to the crop is impossible, or if possible requires sophisticated techniques, such as embryo rescue, somatic fusion or genetic engineering";
    var gp_1a_content = "The cultivated forms of the crop";
    var gp_1b_content = "The wild or weedy forms of the crop";
    var gp_2_content = "The coenospecies (less closely related species) from which gene transfer to the crop is possible but difficult using conventional breeding techniques";
    var gp_3_content = "Includes the species from which gene transfer to the crop is impossible, or if possible requires sophisticated techniques, such as embryo rescue, somatic fusion or genetic engineering";
    
    /* -------------------------------TAXGROUP DEFINITIONS--------------------------------- */
    var taxgroup = "taxon group:";
    var tax_1a = "1a";
    var tax_1b = "1b";
    var tax_2 = "2";
    var tax_3 = "3";
    var tax_4 = "4";
    var tax_5 = "5";
    
    /* -------------------------------TAXGROUP DEFINITIONS--------------------------------- */
    var tax_1a_content = "Crop";
    var tax_1b_content = "Same species as crop"; 
    var tax_2_content = "Same series or section as crop"; 
    var tax_3_content = "Same subgenus as crop"; 
    var tax_4_content = "Same genus"; 
    var tax_5_content = "Same tribe but different genus to crop"; 
    
    
     
    $(".concept-level").poshytip({
        className: 'tip-green',
        offsetX: -7,
        offsetY: 16,
        allowTipHover: true
    });
    
    // Mouse is on level element, so show tool tip
    $(".concept-level").mouseover(function(event){
        event.preventDefault();
        var $target = $(event.target);
        // Target text is the content for <li> element on over mouse
        if($("#concept-type").text().toLowerCase() == genepool){
            switch($target.text().toLowerCase()){
                case prim_level :
                    $target.poshytip('update',prim_level_content);
                    break;
                case sec_level :
                    $target.poshytip('update',sec_level_content);
                    break;
                case ter_level :
                    $target.poshytip('update',ter_level_content);
                    break;
                case gp_1a :
                    $target.poshytip('update',gp_1a_content);
                    break;
                case gp_1b :
                    $target.poshytip('update',gp_1b_content);
                    break;
                case gp_2 :
                    $target.poshytip('update',gp_2_content);
                    break;
                case gp_3 :
                    $target.poshytip('update',gp_3_content);
                    break;
            }
        }else if($("#concept-type").text().toLowerCase() == taxgroup){
            switch($target.text().toLowerCase()){
                case tax_1a :
                    $target.poshytip('update',tax_1a_content);
                    break;
                case tax_1b :
                    $target.poshytip('update',tax_1b_content);
                    break;
                case tax_2 :
                    $target.poshytip('update',tax_2_content);
                    break;
                case tax_3 :
                    $target.poshytip('update',tax_3_content);
                    break;
                case tax_4 :
                    $target.poshytip('update',tax_4_content);
                    break;
                case tax_5 :
                    $target.poshytip('update',tax_5_content);
                    break;
            }
        }

    });
    
    // Mouse is not on level element, so hide tool tip
    $(".concept-level").mouseout(function(event){
        event.preventDefault();
        var $target = $(event.target);
        $target.poshytip('hide');
    });
    
    $(".more-genepool, .minus").click(function(){
        var $box = $(this).parent();
        var $next = $box.find(".box-content");
        var $minus = $box.find(".minus");
        var $more_genepool = $box.find(".more-genepool");
        // if more is showing
        if($more_genepool.is(":visible")) {
            $next.slideDown("fast");
            $more_genepool.hide();
            $minus.show();
        } else {
            $next.slideUp("fast");
            $minus.hide();
            $more_genepool.show();
        }
       
    });
    
    // This method should be at the end of this file.
    $(".more-genepool").each(function(index, element) {
        var $box = $(element).parent();
        var $next = $box.find(".box-content");
        var $minus = $box.find(".minus");
        var $more_genepool = $box.find(".more-genepool");
        $next.slideDown("fast");
        $more_genepool.hide();
        $minus.show();
    });
});

