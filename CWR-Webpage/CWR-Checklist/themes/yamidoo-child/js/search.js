/*   CENTRO INTERNACIONAL DE AGRICULTURA TROPICAL
 *   Autores:  Hector Fabio Tobon  
 *             Alex Gabriel Castaneda
 *  Maneja los eventos para la seccion de busqueda CWR Inventory
 **/

jQuery(document).ready(function($) {

    var rootURI = "../CWR-Checklist/pages/search";
    var searchCropText = "Enter a genus, taxon or crop name";    
    var searchCWRText = "Enter a genus or taxon";
    var searchLocation = "Enter a country name";
    var helpTextConceptLevel = "To make multiple selections hold down the Ctrl or Shift while you make your selection";
    var helpTextCountry = "To make multiple selections hold down the Ctrl or Shift while you make your selection";
    var helpTextRegion = "To make multiple selections hold down the Ctrl or Shift while you make your selection";
    
    // Populating text field help.
    $("#cwr-search #search-genepool").val(searchCropText);
    $("#cwr-search #search-cwr").val(searchCWRText);
    // Adding effect when page load  
    $("#advanced-search").hide("fast");
    $(".concept_level").hide("fast");
    $("#priority-genera").hide("fast");
    $("#cwr-search").show("slow");
    $("#classic-search-button").show("slow");
    $("#advanced-search-button").show("slow");
    $("#advanced-search-button").css({
        opacity: 0.5
    });
    
    // Poshytip to show a help message in advanced-search
    $(".concept_level").poshytip({
        className: 'tip-green',
        offsetX: -7,
        offsetY: 16,
        allowTipHover: true
    });
    
    // Mouse is on level element, so show tool tip
    $(".concept_level").mouseover(function(event){
        event.preventDefault();
        var $target = $(event.target);
        $target.poshytip('title',helpTextConceptLevel);
    });
    
    // Mouse is not on level element, so hide tool tip
    $(".concept_level").mouseout(function(event){
        event.preventDefault();
        var $target = $(event.target);
        $target.poshytip('hide');
    });
    
    $("#search-location-adv").poshytip({
        className: 'tip-green',
        offsetX: -7,
        offsetY: 16,
        allowTipHover: false
    });
    
    $("#search-location-adv").mouseover(function(event){
        event.preventDefault();
        var $target = $(event.target);
        $target.poshytip('title',helpTextCountry)
    });
    
    $("#search-location-adv").mouseout(function(event){
        event.preventDefault();
        var $target = $(event.target);
        $target.poshytip('hide');
    });
    
    $("#search-regions-adv").poshytip({
        className: 'tip-green',
        offsetX: -7,
        offsetY: 16,
        allowTipHover: true
    });
    
    $("#search-regions-adv").mouseover(function(event){
        event.preventDefault();
        var $target = $(event.target);
        $target.poshytip('title',helpTextRegion);
    });
    
    $("#search-regions-adv").mouseout(function(event){
        event.preventDefault();
        var $target = $(event.target);
        $target.poshytip('hide');
    });
    
    // Hide divs elements to the checkbox
    if($("input[name='parameter']:checked").val() == 'countries'){
        // $("#cwr-search #country-search").css("display", "block");
        $("#cwr-search #country-search").show("slow");
    }else{
        //$("#cwr-search #country-search").css("display", "none");
        $("#cwr-search #country-search").hide("slow");
    }
    
    if($("input[name='parameter']:checked").val() == 'regions'){
        //$("#cwr-search #region-search").css("display", "block");
        $("#cwr-search #region-search").show("slow");
    }else{
        //$("#cwr-search #region-search").css("display", "none");  
        $("#cwr-search #region-search").hide("slow");
    }
    
    // Delete or write text to the inputs
    $("#cwr-search #search-genepool").focus(function(event){
        var $target = $(event.target);
        if($target.val() == searchCropText) {
            $target.val("");
        }
    });

    $("#cwr-search #search-genepool").focusout(function(event){
        var $target = $(event.target);
        if($target.val() == "") {
            $target.val(searchCropText);
        }
    });

    $("#cwr-search #search-cwr").focus(function(event){
        var $target = $(event.target);
        if($target.val() == searchCWRText) {
            $target.val("");
        }
    });

    $("#cwr-search #search-cwr").focusout(function(event){
        var $target = $(event.target);
        if($target.val() == "") {
            $target.val(searchCWRText);
        }
    });

    $("#cwr-search #search-location").focus(function(event){
        var $target = $(event.target);
        if($target.val() == searchLocation) {
            $target.val("");
        }
    });

    $("#cwr-search #search-location").focusout(function(event){
        var $target = $(event.target);
        if($target.val() == "") {
            $target.val(searchLocation);
        }
    });
    
    $("#term").focus(function(event){
        var $target = $(event.target);
        if($target.val() == searchCropText) {
            $target.val("");
        }
    });
    
    $("#term").focusout(function(event){
        var $target = $(event.target);
        if($target.val() == "") {
            $target.val(searchCropText);
        }
    });
    
    // Close the table displayed on search by crop genepool
    $("#cwr-search #genepool-table #close-table").click(function(event){
        $("#cwr-search #genepool-table").hide("blind");
    });    

    // Autocomplete functionality to search by crop genepool
    $("#cwr-search #search-genepool").autocomplete({
        minLength: 1,
        source: rootURI+"/ajax-search-suggestion.php?search-type=genepool&limit=10",
        select: function(event, ui) {
            $("#cwr-search #search-genepool").val("<br>"+ui.item.value+"</br>");
            $("#cwr-search #submit-genepool").click();
        }
    });

    // Autocomplete functionality to search by crop wild relative.
    $("#cwr-search #search-cwr").autocomplete({
        minLength: 1,
        delay: 0,
        source: rootURI+"/ajax-search-suggestion.php?search-type=cwr&limit=10",
        select: function(event, ui) {
            $("#cwr-search #search-cwr").val(ui.item.value);
            $("#cwr-search #submit-cwr").click();						
        }
    });

    // Autocomplete functionality to search by country name.
    $("#cwr-search #search-location").autocomplete({
        minLength: 2,
        source: rootURI+"/ajax-search-suggestion.php?search-type=location",
        select: function(event, ui) {
            $("#cwr-search #search-location").val(ui.item.value);
            $("#cwr-search #submit-location").click();
        }
    });
    
    // Autocomplete functionality to advanced search
    $("#advanced-search #term").autocomplete({
        minLength: 1,
        delay: 0,
        source: rootURI+"/ajax-search-suggestion.php?search-type=advanced&limit=10",
        select: function(event, ui) {
            $("#advanced-search #term").val(ui.item.value);
            $("#advanced-search #term").click();						
        }
    });

    // Create a table of taxon depending on the search term
    $("#cwr-search #submit-genepool").click(function(event) {
        event.preventDefault();
        var $target = $(event.target);
        
        if($("#cwr-search #search-genepool").val() != searchCropText && $("#cwr-search #search-genepool").val() != "") {
            $.ajax({
                url: rootURI+"/ajax-search-suggestion.php?search-type=genepool&term="+$("#cwr-search #search-genepool").val()+"",
                dataType: "json",
                contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                beforeSend: function() {
                    // disable button until the search finish.
                    $target.attr('disabled', 'disabled');
                    // show loading icon
                    $target.siblings(".loader").show();
                    // remove the error message (in case it exists).
                    $("#form-genepool").next(".error").remove();
                    // hide table in case it is visible.
                    $("#cwr-search .two-column-table:visible").hide("blind");
                    $("#cwr-search #search-genepool").autocomplete("disable");  
                },
                success: function(data) {
                    // there are results?
                    if(data == null || data.length == 0) {
                        // insert a no data text below the text field.
                        $("#form-genepool").after("<p class='error'>No Data Found</p>");
                        $target.siblings(".loader").hide(); 
                    } else {
                        var have_genus = false;
                        
                        $.each(data, function(index, item) {
                            if(item.id == -9999) {
                                have_genus = true;
                            }
                        });
                      
                        if(!have_genus) {
                            var ids2 = "";
                            $.each(data, function(index, item) {
                                if(item.id != -9999) {
                                    // redirect using JQuery to the cwr detailed page 
                                    if(item.commonName.toString().toLowerCase() == $("#cwr-search #search-genepool").val().toLowerCase()){
                                        if(item.id != null){
                                            ids2 += "id[]="+item.id+"&";
                                            var url = ("genepool-details.php?id[]="+ids2).replace("id[]=id[]=","id[]=");
                                            $(window.location).attr('href',url);
                                        }
                                    }
                                }
                            }); 
                        } else {
                            if(data.length <= 2 ){
                                $.each(data, function(index, item) {
                                    if(item.id != -9999) {
                                        // redirect using JQuery to the cwr detailed page 
                                        $(window.location).attr('href', "genepool-details.php?id[]="+item.id);
                                    }
                                }); 
                            } else {
                                // create table content.
                                var ids = "";
                                $.each(data, function(index, item) {
                                    if(item.id != -9999) {
                                        ids += "id[]="+item.id+"&";
                                    }
                                });
                                $(window.location).attr('href', "genepool-details.php?"+ids);
                            }                      
                        }
                    }
                },
                complete: function() {
                    //enable the submit button and show the table.
                    $target.removeAttr('disabled');
                    $("#cwr-search #search-genepool").autocomplete("enable");
                }
            });
        } else {            
            $("#cwr-search #search-genepool").poshytip('show');
        }
    });
    
    /* Validate if the search throw more than one taxonomic item, in which case it will redirect to the species list page.
     * If the search throw only one taxonomic item, the function will redirect to the species detail page.
     * */
    $("#cwr-search #submit-cwr").click(function(event) {
        event.preventDefault();
        var $target = $(event.target);        
        if($("#cwr-search #search-cwr").val() != searchCWRText && $("#cwr-search #search-cwr").val() != "") {
            $.ajax({
                url: rootURI+"/ajax-search-suggestion.php?search-type=cwr&term="+$("#cwr-search #search-cwr").val(),
                dataType: "json",
                beforeSend: function() {
                    // remove the error message (in case it exists).
                    $("#form-cwr").next(".error").remove();                                
                    $(".ui-autocomplete").hide();
                    // show loading icon
                    $target.siblings(".loader").show();
                    // disable button until the search finish.
                    $target.attr('disabled', 'disabled');
                },
                success: function(data) {
                    if(data == null || data.length == 0) {
                        // insert a no data text below the text field.
                        $("#form-cwr").after("<p class='error'>No Data Found</p>");
                        $target.siblings(".loader").hide(); 
                    } else {
                        // Normally the first item could be the Genus.
                        if(data.length <= 2) {
                            $.each(data, function(index, item) {
                                if(item.id != -9999) {
                                    // redirect using JQuery to the cwr detailed page 
                                    // but first notify the user about this redirection.                                
                                    $(window.location).attr('href', "cwr-details.php?specie_id="+item.id);
                                }
                            });
                        } else {
                            // There are many items. It needs to redirect to List species page.
                            // but first notify the user about this redirection.                        
                            $(window.location).attr('href', "cwr-species-list.php?search-type=cwr&term="+$("#cwr-search #search-cwr").val());
                        }
                    }
                },
                complete: function() {
                    // hide loader gif and enable the submit buttom.
                    $target.removeAttr('disabled');
                    $("#cwr-search #search-cwr").autocomplete("enable");
                }
            });

            
        } else {
            $("#cwr-search #search-cwr").poshytip('show');
        }
    });
    
    /* Validate if the search throw more than one taxonomic item, in which case it will redirect to the species list page.
     * If the search doesn't return any value, a "no data found" will be showed.
     * */
    $("#cwr-search #submit-location").click(function(event) {
        event.preventDefault();
        var $target = $(event.target);
        $("#form-location").next(".error").remove(); 
        if($("#cwr-search #search-location").val() != "") {
            $.ajax({
                url: rootURI+"/ajax-search-suggestion.php?search-type=taxa-location&term="+$("#search-location").val(),
                dataType: "json",
                beforeSend: function() {
                    // remove the error message (in case it exists).
                    $("#form-location").next(".error").remove();                                
                    $(".ui-autocomplete").hide();
                
                    // hide table in case it is visible.
                    $("#cwr-search #location-table:visible").hide("blind");
                    $("#cwr-search #search-location").autocomplete("disable");
                    
                    // show loading icon
                    $target.siblings(".loader").show();
                    
                    // disable button until the search finish.
                    $target.attr('disabled', 'disabled');
                },
                success: function(data) {
                    if(data == null || data.length == 0) {                    
                        // insert a no data text below the text field.
                        $("#form-location").after("<p class='error'>No Data Found</p>");
                        $target.siblings(".loader").hide(); 
                    } else {                      
                        if(data.length == 1) {
                            // Validate that there are species in that coountry
                            if(data[0].taxaCount > 0) {                           
                                // There are many items. It needs to redirect to List species page.
                                $(window.location).attr('href', "cwr-species-list.php?search-type=location&term="+$("#search-location").val()/*data[0].countryName*/);    
                           } else {
                                // insert a no data text below the text field.
                                $("#form-location").after("<p class='error'>No Data Found</p>");
                                // hide loader gif and enable the submit buttom.
                                $target.siblings(".loader").hide();
                                $target.removeAttr('disabled');
                            }
                        } else {
                            // create table content.
                            $htmlOptions = "";
                            $.each(data, function(index, item) {
                                if(item.taxaCount > 0) {
                                    $htmlOptions += "<tr>";
                                    $htmlOptions +=  "<td>"                                
                                    $htmlOptions += "<a href='cwr-species-list.php?search-type=location&term="+escape(item.countryName)+"'>"+item.countryName+"</a></td>";
                                    $htmlOptions +=  "<td>"+item.taxaCount+"</td>";
                                    $htmlOptions += "</tr>";
                                }
                            });
                            $("#table-content-location").html($htmlOptions);
                            // show table with an animation.
                            $("#cwr-search #location-table").show("blind");
                            // hide loader gif and enable the submit buttom.
                            $target.siblings(".loader").hide();
                            $target.removeAttr('disabled');
                        }
                    }
                },
                complete: function() { 
                    $target.removeAttr('disabled');
                    $("#cwr-search #search-location").autocomplete("enable");
                }
            });
        } else {
            event.preventDefault();
            // insert a no data text below the text field.
            $("#form-location").after("<p class='error'>Select an item from the list</p>");
        }
    });
    
    $("#submit-location-adv").click(function(event) {
        event.preventDefault();
        var $target = $(event.target);
        $("#form-location").next(".error").remove(); 
        if($("#cwr-search #search-location").val() != "") {
            $.ajax({
                url: rootURI+"/ajax-search-suggestion.php?search-type=taxa-location&term="+$("#search-location").val(),
                dataType: "json",
                beforeSend: function() {
                    // remove the error message (in case it exists).
                    $("#form-location").next(".error").remove();                                
                    $(".ui-autocomplete").hide();
                
                    // hide table in case it is visible.
                    $("#cwr-search #location-table:visible").hide("blind");
                    $("#cwr-search #search-location").autocomplete("disable");
                    
                    // show loading icon
                    $target.siblings(".loader").show();
                    
                    // disable button until the search finish.
                    $target.attr('disabled', 'disabled');
                },
                success: function(data) {
                    if(data == null || data.length == 0) {                    
                        // insert a no data text below the text field.
                        $("#form-location").after("<p class='error'>No Data Found</p>");
                        $target.siblings(".loader").hide(); 
                    } else {                      
                        if(data.length == 1) {
                            // Validate that there are species in that coountry
                            if(data[0].taxaCount > 0) {                           
                                // There are many items. It needs to redirect to List species page.
                                $(window.location).attr('href', "cwr-species-list.php?search-type=location&term="+$("#search-location").val()/*data[0].countryName*/);    
                           } else {
                                // insert a no data text below the text field.
                                $("#form-location").after("<p class='error'>No Data Found</p>");
                                // hide loader gif and enable the submit buttom.
                                $target.siblings(".loader").hide();
                                $target.removeAttr('disabled');
                            }
                        } else {
                            // create table content.
                            $htmlOptions = "";
                            $.each(data, function(index, item) {
                                if(item.taxaCount > 0) {
                                    $htmlOptions += "<tr>";
                                    $htmlOptions +=  "<td>"                                
                                    $htmlOptions += "<a href='cwr-species-list.php?search-type=location&term="+escape(item.countryName)+"'>"+item.countryName+"</a></td>";
                                    $htmlOptions +=  "<td>"+item.taxaCount+"</td>";
                                    $htmlOptions += "</tr>";
                                }
                            });
                            $("#table-content-location").html($htmlOptions);
                            // show table with an animation.
                            $("#cwr-search #location-table").show("blind");
                            // hide loader gif and enable the submit buttom.
                            $target.siblings(".loader").hide();
                            $target.removeAttr('disabled');
                        }
                    }
                },
                complete: function() { 
                    $target.removeAttr('disabled');
                    $("#cwr-search #search-location").autocomplete("enable");
                }
            });
        } else {
            event.preventDefault();
            // insert a no data text below the text field.
            $("#form-location").after("<p class='error'>Select an item from the list</p>");
        }
    });
    
    $("#submit-regions-adv").click(function (event) {
        event.preventDefault();
        var $target = $(event.target);
        $("#form-location").next(".error").remove(); 
        if($("#cwr-search #search-regions").val() != "" ){
            $.ajax({
                url: rootURI+"/ajax-search-suggestion.php?search-type=taxa-regions&term="+encodeURIComponent($("#cwr-search #search-regions").val()),
                dataType: "json",
                beforeSend: function() {
                    // remove the error message (in case it exists).
                    $("#form-location").next(".error").remove();                                
                    $(".ui-autocomplete").hide();
                
                    // hide table in case it is visible.
                    $("#cwr-search #location-table:visible").hide("blind");
                    $("#cwr-search #search-location").autocomplete("disable");
                    
                    // show loading icon
                    $target.siblings(".loader").show();
                    
                    // disable button until the search finish.
                    $target.attr('disabled', 'disabled');
                },
                success: function(data) {
                    if(data == null || data.length == 0) {                    
                        // insert a no data text below the text field.
                        $("#form-location").after("<p class='error'>No Data Found</p>");
                        $target.siblings(".loader").hide(); 
                    } else { 
                        if(data.length == 1) {
                            // Validate that there are species in that coountry
                            if(data[0].taxaCount > 0) {                           
                                // There are many items. It needs to redirect to List species page.
                                $(window.location).attr('href', "cwr-species-list.php?search-type=region&term="+data[0].regionName);    
                            } else {
                                // insert a no data text below the text field.
                                $("#form-location").after("<p class='error'>No Data Found</p>");
                                // hide loader gif and enable the submit buttom.
                                $target.siblings(".loader").hide();
                                $target.removeAttr('disabled');
                            }
                        } else {
                            // create table content.
                            $htmlOptions = "";
                            $.each(data, function(index, item) {
                                if(item.taxaCount > 0) {
                                    $htmlOptions += "<tr>";
                                    $htmlOptions +=  "<td>"                                
                                    $htmlOptions += "<a href='cwr-species-list.php?search-type=location&term="+escape(item.countryName)+"'>"+item.countryName+"</a></td>";
                                    $htmlOptions +=  "<td>"+item.taxaCount+"</td>";
                                    $htmlOptions += "</tr>";
                                }
                            });
                            $("#table-content-location").html($htmlOptions);
                            // show table with an animation.
                            $("#cwr-search #location-table").show("blind");
                            // hide loader gif and enable the submit buttom.
                            $target.siblings(".loader").hide();
                            $target.removeAttr('disabled');
                        }
                    
                    }
                },
                complete: function() {    
                    $target.removeAttr('disabled');
                    $("#cwr-search #search-region").autocomplete("enable");
                }
            });
        }else{
            event.preventDefault();
            // insert a no data text below the text field.
            $("#form-location").after("<p class='error'>Select an item from the list</p>");
        }
    });
    
    $("#cwr-search #submit-regions").click(function (event) {
        event.preventDefault();
        var $target = $(event.target);
        $("#form-location").next(".error").remove(); 
        if($("#cwr-search #search-regions").val() != "" ){
            $.ajax({
                url: rootURI+"/ajax-search-suggestion.php?search-type=taxa-regions&term="+encodeURIComponent($("#cwr-search #search-regions").val()),
                dataType: "json",
                beforeSend: function() {
                    // remove the error message (in case it exists).
                    $("#form-location").next(".error").remove();                                
                    $(".ui-autocomplete").hide();
                
                    // hide table in case it is visible.
                    $("#cwr-search #location-table:visible").hide("blind");
                    $("#cwr-search #search-location").autocomplete("disable");
                    
                    // show loading icon
                    $target.siblings(".loader").show();
                    
                    // disable button until the search finish.
                    $target.attr('disabled', 'disabled');
                },
                success: function(data) {
                    if(data == null || data.length == 0) {                    
                        // insert a no data text below the text field.
                        $("#form-location").after("<p class='error'>No Data Found</p>");
                        $target.siblings(".loader").hide(); 
                    } else { 
                        if(data.length == 1) {
                            // Validate that there are species in that coountry
                            if(data[0].taxaCount > 0) {                           
                                // There are many items. It needs to redirect to List species page.
                                $(window.location).attr('href', "cwr-species-list.php?search-type=region&term="+data[0].regionName);    
                            } else {
                                // insert a no data text below the text field.
                                $("#form-location").after("<p class='error'>No Data Found</p>");
                                // hide loader gif and enable the submit buttom.
                                $target.siblings(".loader").hide();
                                $target.removeAttr('disabled');
                            }
                        } else {
                            // create table content.
                            $htmlOptions = "";
                            $.each(data, function(index, item) {
                                if(item.taxaCount > 0) {
                                    $htmlOptions += "<tr>";
                                    $htmlOptions +=  "<td>"                                
                                    $htmlOptions += "<a href='cwr-species-list.php?search-type=location&term="+escape(item.countryName)+"'>"+item.countryName+"</a></td>";
                                    $htmlOptions +=  "<td>"+item.taxaCount+"</td>";
                                    $htmlOptions += "</tr>";
                                }
                            });
                            $("#table-content-location").html($htmlOptions);
                            // show table with an animation.
                            $("#cwr-search #location-table").show("blind");
                            // hide loader gif and enable the submit buttom.
                            $target.siblings(".loader").hide();
                            $target.removeAttr('disabled');
                        }
                    
                    }
                },
                complete: function() {    
                    $target.removeAttr('disabled');
                    $("#cwr-search #search-region").autocomplete("enable");
                }
            });
        }else{
            event.preventDefault();
            // insert a no data text below the text field.
            $("#form-location").after("<p class='error'>Select an item from the list</p>");
        }
    });
   
    $("#cwr-search #submit-use").click(function(event) {        
        // remove the error message (in case it exists).
        $("#form-use").next(".error").remove(); 
        if($("#cwr-search #name-use").val() == "") {
            event.preventDefault();
            // insert a no data text below the text field.
            $("#form-use").after("<p class='error'>Select an item from the list</p>");
            $target.siblings(".loader").hide();
        }
        var $target = $(event.target);
        $target.siblings(".loader").show();
    });
    
    $("#search-genepool, #search-cwr, #search-location").poshytip({
        className: 'tip-green',
        //allowTipHover: true,
        timeOnScreen: 2000,
        alignTo: 'target',
        alignX: 'inner-right',
        alignY: 'bottom',
        offsetX: 0,
        offsetY: 5,
        showOn: 'none',
        content: "<b>Let's write something!</b>"
    });

    //Show and hide divs in search.tpl
    $("#cwr-search #check-countries").click(function(event) {//check box countries
        $("#cwr-search #country-search").show("slow");
        $("#cwr-search #region-search").hide("slow");
    });
    
    $("#cwr-search #check-regions").click(function(event) {//check box regions
        $("#cwr-search #region-search").show("slow");
        $("#cwr-search #country-search").hide("slow");
    });
    
    
    /* Nuevas funciones para incluir la busqueda avanzada */
    
    // Valores iniciales para las pestañas
    $("#cwr-search #busqueda-general").animate({
        backgroundColor: '#0B7802',
        color: '#FFFFFF',
        opacity: 1
    });
    
    $("#cwr-search #busqueda-avanzada").animate({
        opacity: 0.5
    });
    
    // Valores iniciales para los modulos para concept-type
    $("#taxongroup-concept-lvl").hide(); // Ocultar el concept-level taxon group
    $("#genepool-concept-lvl").hide(); // Ocultar el concept-level gene pool
    $(".advanced-search").hide(); //Solo esta activa al principio la busqueda general
    
    // Animacion para intercambiar los modulos para concept-type
    $("#concept-type").change(function(event) {
        var conceptTypeSelected = $("#concept-type").find(":selected").text();
        
        if(conceptTypeSelected == "Gene Pool"){
            $("#genepool-concept-lvl").show("slow"); 
            $("#taxongroup-concept-lvl").hide("slow"); 
        }else if(conceptTypeSelected == "Taxa Group"){ 
            $("#taxongroup-concept-lvl").show("slow"); 
            $("#genepool-concept-lvl").hide("slow"); 
        }
    }).trigger('change');
    
    /* Eventos para manejador de tipo de busqueda */
    $("#advanced-search-button").click(function(){
        $("#advanced-search").show("fast");
        $("#classic-search").hide("fast");
    });
    
    $("#classic-search-button").click(function(){
        $("#classic-search").show("fast");
        $("#advanced-search").hide("fast");
    });
    
    $("#search-type-selector div").mouseover(function(){
        $(this).css({
            opacity: 1.0
        });
    });
    
    $("#search-type-selector div").mouseleave(function(){
        if(!$(this).hasClass("select")){
            $(this).css({
                opacity: 0.5
            });
        }
    });
    
    $("#search-type-selector div").click(function(){
        $("#search-type-selector div").removeClass("select");
        $("#search-type-selector div").css({
            opacity: 0.5
        });
        $(this).css({
            opacity: 1.0
        });
        $(this).addClass("select");
    });
    
    // Limpiar todos los campos del formulario de busqueda avanzada
    $("#clear-advanced-search").click(function(){
        $("#term").val(searchCropText);
        $("#concept-type").val("empty");
        $(".concept_level").hide("slow");
        $("#priority-genera").hide("slow");
        $("#priority-genera-only").removeAttr("checked");
        $("#priority-croptaxa-only").removeAttr("checked");
    });
    
    // Evento que responde a la busqueda unicamente por generos prioritarios
    $("#priority-genera-only").click(function(){
        if($("#priority-genera-only:checked").val()){
            $("#priority-croptaxa-only").removeAttr("checked");// Deseleccionando la busqueda por crop taxa prioritario
            $("#priority-genera").show("slow");
            $("#term").attr("disabled","disabled");
            $(".term").hide("slow");
        }else{
            $("#priority-genera").hide("slow");
            $("#term").removeAttr("disabled");
            $(".term").show("slow");
        } 
    });
    
    $("#priority-croptaxa-only").click(function(){
        if($("#priority-croptaxa-only:checked").val()){
            $("#priority-genera-only").removeAttr("checked");// Deseleccionando la busqueda por genero prioritario
            $("#priority-genera").hide("slow");
            $("#term").attr("disabled","disabled");
            $(".term").hide("slow");
        }else{
            $("#term").removeAttr("disabled");
            $(".term").show("slow");
        }
    });

    $("#concept-type").change(function(){
        $(".concept_level").hide("slow");
        if(!$(this).val() == ""){
            $.ajax({
                url: rootURI+"/advanced-search.php?search-type=concept-level&term="+$(this).val(),
                dataType: "json",
                success: function(data) {
                    if(data == null || data.length == 0) {                    
                       
                    } else {  
                        $("#concept-level option").remove(); // Eliminando las opciones previas
                        $.each(data, function(index,item) {
                            $("#concept-level").append("<option>"+item+"</option>");
                        });
                        $(".concept_level").show("slow");
                    }
                }
            });
        }
    });
    
    $("#link-more-genus-information").click(function(){
        $(window.location).attr('href', "genus-taxa-information.php?type=genus");    
    });
    
    $("#link-more-taxa-information").click(function(){
        $(window.location).attr('href', "genus-taxa-information.php?type=taxa");    
    });
});
