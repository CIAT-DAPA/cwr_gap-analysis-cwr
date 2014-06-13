/*
 * International Center of Tropical Agriculture (ICTA)
 * @Author: Alex Gabriel Castaneda
 * @Email: a.g.castaneda@cgiar.org
 * 
 * Handle the user events from the map
 **/

jQuery(document).ready(function($) {
    $('#single-layers-div').css('overflowY', 'auto');
    $('#searchTypeWindow').reveal(); // Show a modal Window in the start
    $('#empty_value_error').hide('fast');
    $('#no_map_type_error').hide('fast');
    $('#conservation-section').hide('fast');
    $("#genepool-conservation-section").hide('fast');
    $("#tituloEscalaColores").hide("fast");
    $("#escalaColores").hide("fast");
    /*$("#help_window").hide("fast");*/
    $('.species').hide("fast");
    $('.genepool').hide("fast");
    $(".global").hide("fast");
    $('#no_data').hide('fast');
    $(".section > .content").hide("fast");
    $(".header").hide("fast");
    $(".minus").hide("fast");
    $("#minus").hide("fast");
    $("#single-layers-div").hide("fast");
    $(".single-layers").hide("hide");
    $(".typeMap > img").hide("fast");
    $("#accepted-species").hide("fast");
    $('#roadmap').addClass('selected');  // Mapa por defecto
    var rootURI = "../CWR-DistributionMap/pages/distribution-map/";
    var rootCWRInventoryURI = "../CWR-Checklist/pages/search";
    var cwrInventoryResults = "../checklist";
    var search_text = "Search by scientific name or common name";
    var search_text_crop = "Search by crop scientific name or common name";
    var typeMapEnabled = false;
    var searchType = null;
    var mapType = null;
    var last_search_value = null; // Almacenar el ultimo valor de la busqueda para saber si es necesario o no realizar una limpieza total del mapa
    var last_taxon = null;

    /* Calcular la fecha y desplegarla en la ventana inicial */
    var today = new Date();
    var dd = today.getDate();
    var mm = today.getMonth();
    var yyyy = today.getFullYear();

    var months = new Array(12);
    months[0] = "January";
    months[1] = "February";
    months[2] = "March";
    months[3] = "April";
    months[4] = "May";
    months[5] = "June";
    months[6] = "July";
    months[7] = "August";
    months[8] = "September";
    months[9] = "October";
    months[10] = "November";
    months[11] = "December";

    var date = months[mm] + "-" + dd + "-" + yyyy;
    
    /* Gestiona los colores en los selectores tipos de busqueda en el menu */
    $("#search_type > div").hover(function(){
        $(this).css("background-color","#61A260");
        $(this).css("border","1px solid #FFFFFF");
        $(this).css("color","#FFFFFF");
    }, function(){
        if(!$(this).hasClass("selected")){
            $(this).css("background-color","#FFFFFF");
            $(this).css("border","1px solid #000000");
            $(this).css("color","#000000");
        }
    });
    

    /* Area para rellenar el formulario con las species aceptadas */
    $("#validSpeciesButton").click(function() {
        $.ajax({
            url: rootURI + "specie-information.php?searchType=specieList&taxon="+$("#search-value").val(),
            dataType: "json",
            contentType: "application/x-www-form-urlencoded;charset=UTF-8",
            beforeSend: function() {
                $("#accepted-species").html("");
                $("#accepted-species").append("<div class='header'><div class='left'><a class='close-accepted-species'>&#215;</a></div><div class='right'><center><h4>Crop genepools and CWR analyzed.</h4></center>Click on the crop to display the CWR<br></div></div>");
            },
            success: function(data) {
                if (data == null || data.length == 0 || data.no_data == 1) {

                } else {
                    hide_modal_window();
                    $('#empty_value_error').hide('fast');
                    
                    if ($("#search-value").val() != search_text && $("#search-value").val() != search_text_crop) {
                        if (!data[0].No_Valid_Data) {  // En caso que la specie ingresada sea valida entonces mostrar.    
                            var parts = $("#search-value").val().split(" ");

                            if (parts.length <= 2) {
                                $("#no_data").html("<b><i>" + $("#search-value").val() + "</i></b> was not analyzed in the gap analysis.<br> Check the full list of crops and CWR taxa analyzed in the box appearing below.<br>");
                            } else {
                                var italic_text = "";
                                if ($("#search-value").val().split(/var./).length != 1 || $("#search-value").val().split(/var/).length != 1) {
                                    parts = $("#search-value").val().split(/var./);
                                    for (i = 0; i < parts.length; i++) {
                                        italic_text += "<i>";
                                        italic_text += parts[i];
                                        italic_text += "</i> " + "var.";
                                    }
                                    italic_text += "-";
                                    italic_text = italic_text.replace("var.-", "");
                                }

                                if ($("#search-value").val().split(/subsp./).length != 1 || $("#search-value").val().split(/subsp/).length != 1) {
                                    parts = $("#search-value").val().split(/subsp./);
                                    for (i = 0; i < parts.length; i++) {
                                        italic_text += "<i>";
                                        italic_text += parts[i];
                                        italic_text += "</i> " + "subsp.";
                                    }
                                    italic_text += "-";
                                    italic_text = italic_text.replace("subsp.-", "");
                                }

                                $("#no_data").html("<b>" + italic_text + "</b> was not analyzed in the gap analysis.<br> Check the full list of crops and CWR taxa analyzed in the box appearing below.<br>");
                            }

                            
                            $("#no_data").append("<center><div id='closeButton'>Close</div></center>");
                            $("#no_data").show("slide", {
                                direction: "right"
                            }, "slow");
                        }
                    }

                    var lastCropCode = null;
                    var new_content = false;
                    var speciesHTML = "";
                    
                    /*   Estas variables no entraban antes*/
                    var specie_name = "";
                    var temporal_text = "";
                    var x = null;
                    /*  ------------------*/
                    
                    $.each(data, function(index, item) {
                        if (lastCropCode != null && item.Crop_code == lastCropCode) {
                            if (new_content) {
                                speciesHTML += "<div class='content'>";
                                new_content = false;
                            }
                            
                            if (item.Taxon_ID == item.Valid_Taxon_ID) {
                                speciesHTML += "<b>" + item.Scientific_name + "</b><br>";
                            } else {
                                speciesHTML += item.Scientific_name + "<br>";
                            }
                            
                        } else {
                            if (lastCropCode != null) { // Cambio de titulo debe cerrar el contenedor anterior
                                speciesHTML += "</div></div>";
                                $("#accepted-species").append(speciesHTML);
                            }
                            speciesHTML = "<div id='" + item.Crop_code + "'><div class='title-species'><img class='plus' src='http://www.cwrdiversity.org/CWR-DistributionMap/themes/yamidoo-child/images/plus.png'><img class='minus' src='http://www.cwrdiversity.org/CWR-DistributionMap/themes/yamidoo-child/images/minus.png'>" + item.Crop_code + "</div>";
                            lastCropCode = item.Crop_code;
                            new_content = true;
                        }
                    });
                    $(".content").hide("fast");
                    
                    $("#accepted-species").show("slide", {
                        direction: "right"
                    }, "slow");
                }
            },
            complete: function() {
                $(".title-species > .minus").hide("fast");
            },
            error: function(jqXHR, textStatus, errorThrown) {

            }
        });
    });

    $("#accepted-species").hover(function() {
        $(this).animate({
            opacity: 1
        });
    }, function() {
        $(this).animate({
            opacity: 0.3
        }, 1000);
    });

    $(".title-species").live("click", function() {
        if ($(this).parent().find(".content").is(":visible")) {
            $(this).parent().find(".content").hide("slow");
            $(this).find(".minus").hide("fast");
            $(this).find(".plus").show("fast");
            $("#accepted-species").animate({
                width: "160px"
            }, 200);
        } else {
            $(this).parent().find(".content").show("slow");
            $(this).find(".plus").hide("fast");
            $(this).find(".minus").show("fast");
            $("#accepted-species").animate({
                width: "300px"
            }, 200);
        }
    });

    $(".close-accepted-species").live("click", function() {
        $("#accepted-species").hide("slide", {
            direction: "right"
        }, "slow");
    });

    $("#text").append("<b>CWR Global Atlas citation:</b><br><p style='font-size:11px;'>Crop Wild Relatives and Climate Change (2013). <br>Interactive map. Online resource. Accessed on " + date + " <br> www.cwrdiversity.org/distribution-map/</p>");

    $('#roadmap').addClass('selected'); // by Default

    $("#search-value").attr("disabled", "disabled");
    $("#search-button").attr("disabled", "disabled");
    $("#search-value").autocomplete({
        source: function(request, response) {
            var results = $.ui.autocomplete.filter(taxonomy, request.term);
            response(results.slice(0, 5));
        },
        minLength: 1,
        max: 25,
        select: function(event, ui) {
            $("#search-value").val(ui.item.value);
        }
    });

    /* Controles sobre las opciones de mapas del cuadro de busqueda */
    $(".typeMap").live('mouseover', function(e) {
        if (!$(this).find("img").is(":visible")) {
            e.preventDefault();
            $(this).animate({
                backgroundColor: '#E9F5BC'
            });
        }
    });

    $(".typeMap").live('mouseout', function(e) {
        e.preventDefault();
        if (!$(this).find("img").is(":visible")) {
            $(this).animate({
                backgroundColor: '#EDF9ED'
            });
        }
    });

    $(".typeMap").bind('event-typemap-click', function(e) {
        e.preventDefault();

        if ($(this).find("img").is(":visible")) {
            typeMapEnabled = false;
            $(this).find("img").hide("slow");
            $(this).css("background-color", "#EDF9ED");
            resetMapTypes();
        } else {
            typeMapEnabled = true;
            $(this).find("img").show("slow");
            $(this).css("background-color", "#EDF9ED");
        }
    });

    $("#w-genepool-search").click(function() {
        hide_modal_window();
        $('.genepool').show('fast');
        $("#genepool-search").css("background-color", "#61A260");
        $("#genepool-search").css("color", "#FFFFFF");
        $("#genepool-search").addClass("selected");
        $("#species-search").css("background-color", "#FFFFFF");
        $("#species-search").css("color", "#000000");
        $("#species-search").removeClass("selected");
        $("#global-summary").css("background-color", "#FFFFFF");
        $("#global-summary").css("color", "#000000");
        $("#global-summary").removeClass("selected");
        $("#search-value").attr("value", "Search by crop scientific name or common name");
        searchType = "genepool";
        $("#search-value").removeAttr("disabled");
        $("#search-button").removeAttr("disabled");
        $("#search-value").autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(cropcodes, request.term);
                response(results.slice(0, 25));
            },
            minLength: 1,
            select: function(event, ui) {
                $("#search-value").val(ui.item.value);
            }
        });
    });

    $("#w-species-search").click(function() {
        hide_modal_window();
        $('.species').show('fast');
        $("#species-search").css("background-color", "#61A260");
        $("#species-search").css("color", "#FFFFFF");
        $("#species-search").addClass("selected");
        $("#genepool-search").css("background-color", "#FFFFFF");
        $("#genepool-search").css("color", "#000000");
        $("#genepool-search").removeClass("selected");
        $("#global-summary").css("background-color", "#FFFFFF");
        $("#global-summary").css("color", "#000000");
        $("#global-summary").removeClass("selected");
        $("#search-value").attr("value", "Search by scientific name or common name");
        searchType = "species";
        $("#search-value").removeAttr("disabled");
        $("#search-button").removeAttr("disabled");
        $("#search-value").autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(taxonomy, request.term);
                response(results.slice(0, 25));
            },
            minLength: 1,
            select: function(event, ui) {
                $("#search-value").val(ui.item.value);
            }
        });
    });

    $("#w-global-summary").click(function() {
        hide_modal_window();
        $("#global-summary").css("background-color", "#61A260");
        $("#global-summary").css("color", "#FFFFFF");
        $("#global-summary").addClass("selected");
        $("#genepool-search").css("background-color", "#FFFFFF");
        $("#genepool-search").css("color", "#000000");
        $("#genepool-search").removeClass("selected");
        $("#species-search").css("background-color", "#FFFFFF");
        $("#species-search").css("color", "#000000");
        $("#species-search").removeClass("selected");
        searchType = "global";
        $("#search-value").removeAttr("disabled");
        $("#search-button").removeAttr("disabled");
        $("#search-value").autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(taxonomy, request.term);
                response(results.slice(0, 25));
            },
            minLength: 1,
            select: function(event, ui) {
                $("#search-value").val(ui.item.value);
            }
        });
        $("#search-value").attr("disabled", "disabled");
        $("#search-button").attr("disabled", "disabled");
        $("#global-summary").trigger("click");
    });

    /* Events for species -  gene pool - global search */
    $("#species-search").click(function() {
        $('.species').show('fast');
        $('.global').hide('fast');
        $('.genepool').hide('fast');
        $(".header").hide("fast");
        $(".header").html("");
        $(".typeMap > img").hide("fast");
        $(".typeMap").css("background-color", '#EDF9ED');
        $("#single-layers-div").hide("fast");
        $(".single-layers").hide("hide");
        $("#conservation-section").hide("fast");
        $("#genepool-conservation-section").hide("fast");
        $("#search-button").attr("enabled", true);
        $("#search-value").attr("enabled", true);
        $("#species-search").css("background-color", "#61A260");
        $("#species-search").css("color", "#FFFFFF");
        $("#species-search").addClass("selected");
        $("#genepool-search").css("background-color", "#FFFFFF");
        $("#genepool-search").css("color", "#000000");
        $("#genepool-search").removeClass("selected");
        $("#global-summary").css("background-color", "#FFFFFF");
        $("#global-summary").css("color", "#000000");
        $("#global-summary").removeClass("selected");
        $("#search-value").attr("value", "Search by scientific name or common name");
        $("#search-value").removeAttr("disabled");
        $("#search-button").removeAttr("disabled");
        $("#tituloEscalaColores").html("");
        $("#escalaColores").html("");
        $("#tituloEscalaColores").hide("fast");
        $("#escalaColores").hide("fast");
        $("#no_data").hide("fast");
        mapType = null;
        searchType = "species";
        destroyAutocomplete();
        if ($("#roadmap").hasClass('selected')) {
            clearMarkers();
            resetMap("map");
        } else if ($("#terrain").hasClass('selected')) {
            clearMarkers();
            resetMap("terrain");
        } else {
            clearMarkers();
            resetMap("sattelite");
        }
        $("#search-value").autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(taxonomy, request.term);
                response(results.slice(0, 25));
            },
            minLength: 1,
            select: function(event, ui) {
                $("#search-value").val(ui.item.value);
            }
        });
    });

    $("#genepool-search").click(function() {
        $('.species').hide('fast');
        $('.genepool').show('fast');
        $('.global').hide('fast');
        $(".header").hide("fast");
        $(".header").html("");
        $(".typeMap > img").hide("fast");
        $(".typeMap").css("background-color", '#EDF9ED');
        $("#single-layers-div").hide("fast");
        $(".single-layers").hide("hide");
        $("#conservation-section").hide("fast");
        $("#genepool-conservation-section").hide("fast");
        $("#search-button").attr("enabled", true);
        $("#search-value").attr("enabled", true);
        $("#genepool-search").css("background-color", "#61A260");
        $("#genepool-search").css("color", "#FFFFFF");
        $("#genepool-search").addClass("selected");
        $("#species-search").css("background-color", "#FFFFFF");
        $("#species-search").css("color", "#000000");
        $("#species-search").removeClass("selected");
        $("#global-summary").css("background-color", "#FFFFFF");
        $("#global-summary").css("color", "#000000");
        $("#global-summary").removeClass("selected");
        $("#search-value").attr("value", "Search by crop scientific name or common name");
        $("#search-value").removeAttr("disabled");
        $("#search-button").removeAttr("disabled");
        $("#tituloEscalaColores").html("");
        $("#escalaColores").html("");
        $("#tituloEscalaColores").hide("fast");
        $("#escalaColores").hide("fast");
        $("#no_data").hide("fast");
        mapType = null;
        searchType = "genepool";
        destroyAutocomplete();
        if ($("#roadmap").hasClass('selected')) {
            clearMarkers();
            resetMap("map");
        } else if ($("#terrain").hasClass('selected')) {
            clearMarkers();
            resetMap("terrain");
        } else {
            clearMarkers();
            resetMap("sattelite");
        }
        $("#search-value").autocomplete({
            source: function(request, response) {
                var results = $.ui.autocomplete.filter(cropcodes, request.term);
                response(results.slice(0, 25));
            },
            minLength: 1,
            select: function(event, ui) {
                $("#search-value").val(ui.item.value);
            }
        });
    });

    $("#global-summary").click(function() {
        $("#search-value").attr("disabled", "disabled");
        $("#search-button").attr("disabled", "disabled");
        $("#search-value").val("");
        $('.species').hide('fast');
        $('.genepool').hide('fast');
        $('.global').show('fast');
        $(".header").hide("fast");
        $(".header").html("");
        $("#single-layers-div").hide("fast");
        $(".single-layers").hide("hide");
        $("#conservation-section").hide("fast");
        $("#genepool-conservation-section").hide("fast");
        $("#global-summary").css("background-color", "#61A260");
        $("#global-summary").css("color", "#FFFFFF");
        $("#global-summary").addClass("selected");
        $("#genepool-search").css("background-color", "#FFFFFF");
        $("#genepool-search").css("color", "#000000");
        $("#genepool-search").removeClass("selected");
        $("#species-search").css("background-color", "#FFFFFF");
        $("#species-search").css("color", "#000000");
        $("#species-search").removeClass("selected");
        $("#tituloEscalaColores").html("");
        $("#escalaColores").html("");
        $("#tituloEscalaColores").hide("fast");
        $("#escalaColores").hide("fast");
        $("#no_data").hide("fast");
        searchType = "global";

        if ($("#roadmap").hasClass('selected')) {
            clearMarkers();
            resetMap("map");
        } else if ($("#terrain").hasClass('selected')) {
            clearMarkers();
            resetMap("terrain");
        } else {
            clearMarkers();
            resetMap("sattelite");
        }

        // Para el caso especifico de esta opcion de busqueda, el mapa por defecto debe cargarse inmediatamente despues de 
        // realizar click sobre la opcion.
        if (mapType != "global_gap_richness" && mapType != "global_species_richness") { // Cargando el mapa por defecto
            $("#global_species_richness > img").show("fast");
            $("#global_species_richness > img").css("background-color", '#EDF9ED');
            mapType = "global_species_richness";
        }

        $.ajax({
            url: rootURI + "show-information.php?map_type=" + mapType,
            dataType: "json",
            contentType: "application/x-www-form-urlencoded;charset=UTF-8",
            beforeSend: function() {
                clearMarkers(); // Limpiando marcadores para realizar varias busquedas
                $("#tituloEscalaColores").html("");
                $("#escalaColores").html("");
                $("#tituloEscalaColores").hide("fast");
                $("#escalaColores").hide("fast");
                $(".header").hide("fast");
                $(".header").html(""); //  Borrar el html del div, para evitar mezclar info. previa
            },
            success: function(data) {
                if (data == null || data.length == 0 || data.no_data == 1) {
                // Omitiendo el paso de busqueda global cuando se cambia de mapa
                } else { // Se ha encontrando informacion, se debe desplegar el tile correspondiente
                    if ($("#global_species_richness > img").is(":visible") || $("#global_gap_richness > img").is(":visible")) {  // Distribucion kml Gap
                        if ($("#roadmap").hasClass('selected')) {
                            initializeWithMapType("map");
                        } else if ($("#terrain").hasClass('selected')) {
                            initializeWithMapType("terrain");
                        } else {
                            initializeWithMapType("sattelite");
                        }
                        showTileImages(data.url, null);
                        if (mapType == "global_gap_richness") {
                            $("#tituloEscalaColores").html("Number of high priority taxa");
                        } else {
                            $("#tituloEscalaColores").html("Number of taxa");
                        }

                        $("#escalaColores").html("<img src='" + data.url + "/" + mapType + "scaleTestImage.png' />");
                        $("#tituloEscalaColores").show("fast");
                        $("#escalaColores").show("fast");
                    }
                }
            },
            complete: function() {

            },
            error: function(jqXHR, textStatus, errorThrown) {

            }
        });
    });

    /* Handle the events for the search field */
    $("#search-value").focus(function() {
        if ($("#search-value").val() == search_text || $("#search-value").val() == search_text_crop) {
            $("#search-value").val("");
        }
    });

    $("#search-value").focusout(function() {
        if ($("#genepool-search").css("background-color") == "rgb(97,162,96)") {
            if ($("#search-value").val() == "") {
                $("#search-value").val(search_text_crop);
            }
        } else {
            if ($("#search-value").val() == "") {
                $("#search-value").val(search_text);
            }
        }

    });

    /* Events for the map type to display */
    $('#satellite').click(function(ev) {
        if (!$(this).hasClass('selected')) {
            $('#roadmap').removeClass('selected');
            $('#terrain').removeClass('selected');
            $('#satellite').addClass('selected');
            map.setMapTypeId(google.maps.MapTypeId.SATELLITE);
        }
    });

    $("#terrain").click(function(ev) {
        if (!$(this).hasClass('selected')) {
            $('#roadmap').removeClass('selected');
            $('#satellite').removeClass('selected');
            $('#terrain').addClass('selected');

            map.setMapTypeId(google.maps.MapTypeId.TERRAIN);
        }
    });

    $('#roadmap').click(function(ev) {
        if (!$(this).hasClass('selected')) {
            $('#satellite').removeClass('selected');
            $('#terrain').removeClass('selected');
            $('#roadmap').addClass('selected');
            map.setMapTypeId(google.maps.MapTypeId.ROADMAP);
        }
    });


    $('.reveal-modal-bg').click(function() {
        if (!$('#genepool-search').is(":visible") && !$('#species-search').is(':visible')) {
            $('#species-search').show('fast');
        }
    });

    $("#okButton").click(function() {
        hide_modal_window();
    });

    /* Evento que debe comenzar el proceso de busqueda dada las preferencias del usuario */
    $("#search-button").click(function() {
        // Inicialmente se muestra la ventana de carga
        if (getMapType() == null) {
            if (searchType == "species") {
                mapType = "points"; // Por defecto sera seleccionado este tipo de mapa en caso de que el usuario no lo haga
                $("#points > img").show("fast");
            } else if (searchType == "genepool") {
                mapType = "genepool_species_richness";
                $("#genepool_species_richness > img").show("fast");
            } else if (searchType == "global") {
                mapType = "global_species_richness";
                $("#global_species_richness > img").show("fast");
            }
        }

        if (last_search_value != null && last_search_value != $("#search-value").val()) { // Valor de busqueda diferente al valor anterior
            clearMarkers();
            if ($("#roadmap").hasClass('selected')) {
                initializeWithMapType("map");
            } else if ($("#terrain").hasClass('selected')) {
                initializeWithMapType("terrain");
            } else {
                initializeWithMapType("sattelite");
            }

            if (searchType == "species") {
                $(".typeMap > img").hide("fast");
                $(".typeMap").css("background-color", '#EDF9ED');
                mapType = "points";
                $("#points > img").show("fast");
                $("#points").css("background-color", '#EDF9ED');
            } else if (searchType == "genepool") {
                $(".typeMap > img").hide("fast");
                $(".typeMap").css("background-color", '#EDF9ED');
                mapType = "genepool_species_richness";
                $("#genepool_species_richness > img").show("fast");
            } else if (searchType == "global") {
                $(".typeMap > img").hide("fast");
                $(".typeMap").css("background-color", '#EDF9ED');
                mapType = "global_species_richness";
                $("#global_species_richness > img").show("fast");
            }

            last_search_value = $("#search-value").val();
        }

        var FPCAT;
        $("#no_data").hide();
        if ($(".typeMap > img").is(":visible")) { // Si se ha seleccionado un tipo de mapa
            $('#loadingWindow').reveal({
                closeonbackgroundclick: false
            });
            $('#loadingWindow').show('fast');
            $('.reveal-modal-bg').show('fast');
            if ($("#search-value").val() != search_text && $("#search-value").val() != "" && $("#search-value").val() != search_text_crop) {
                if (searchType == "species") { // Busqueda por species
                    $.ajax({
                        url: rootURI + "show-information.php?specie=" + $("#search-value").val() + "&map_type=" + mapType,
                        dataType: "json",
                        contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                        beforeSend: function() {
                            if (mapType == "points") { // Unicamente limpiar en caso que sea una busqueda por puntos, ya que  de lo contrario se solaparian los puntos
                                clearMarkers(); // Limpiando marcadores para realizar varias busquedas
                            }
                            $("#tituloEscalaColores").html("");
                            $("#escalaColores").html("");
                            $("#tituloEscalaColores").hide("fast");
                            $("#escalaColores").hide("fast");
                            $("#conservation-section").hide("fast");
                            $(".header").hide("fast");
                            $(".header").html(""); //  Borrar el html del div, para evitar mezclar info. previa
                        },
                        success: function(data) {
                            if (data == null || data.length == 0 || data.no_data == 1) {
                                hide_modal_window();
                                $('#empty_value_error').hide('fast');
                                $("#no_data").html("Sorry, no data for  <b>" + $("#search-value").val() + "</b><br>");
                                $("#no_data").append("<center><div id='closeButton'>Close</div></center>");
                                $("#no_data").show("slide", {
                                    direction: "left"
                                }, "slow");
                            } else if (data.unknown_specie == 1) {
                                $("#validSpeciesButton").trigger("click");
                            } else { // Para cada resultado se deben extraer los puntos
                                var tile_points = [];
                                last_search_value = $("#search-value").val();
                                if (mapType == "points") {
                                    if (!$("#gap > img").is(":visible") && !$("#gap_spp > img").is(":visible")) { // Solo limpiar el mapa en caso tal que el tipo de mapa sea otro diferente a gap_spp o models
                                        if ($("#roadmap").hasClass('selected')) {
                                            initializeWithMapType("map");
                                        } else if ($("#terrain").hasClass('selected')) {
                                            initializeWithMapType("terrain");
                                        } else {
                                            initializeWithMapType("sattelite");
                                        }
                                    }
                                    var points = [];
                                    $.each(data, function(index, item) {
                                        points.push([item.latitude, item.longitude]);
                                    });
                                    showMarkers(points);
                                } else if (mapType == "gap") {
                                    // Distribucion kml Gap
                                    if (data != null) {
                                        $.each(data.pointList, function(index, item) {
                                            tile_points.push([item.latitude, item.longitude]);
                                        });
                                        if ($("#gap_spp > img").is(":visible")) {
                                            $("#gap_spp > img").hide("slow");
                                            if ($("#roadmap").hasClass('selected')) {
                                                initializeWithMapType("map");
                                            } else if ($("#terrain").hasClass('selected')) {
                                                initializeWithMapType("terrain");
                                            } else {
                                                initializeWithMapType("sattelite");
                                            }
                                        }
                                        showTileImages(data.url, tile_points);
                                    }
                                } else if (mapType == "gap_spp") {
                                    if (data != null) {
                                        $.each(data.pointList, function(index, item) {
                                            tile_points.push([item.latitude, item.longitude]);
                                        });
                                        if ($("#gap > img").is(":visible")) {
                                            $("#gap > img").hide("slow");
                                            if ($("#roadmap").hasClass('selected')) {
                                                initializeWithMapType("map");
                                            } else if ($("#terrain").hasClass('selected')) {
                                                initializeWithMapType("terrain");
                                            } else {
                                                initializeWithMapType("sattelite");
                                            }
                                        }
                                        showTileImages(data.url, tile_points);
                                    }
                                }
                            }
                        },
                        complete: function() {
                            hide_modal_window();
                            $("#search-button").attr('disabled', false);
                        },
                        error: function(jqXHR, textStatus, errorThrown) {

                        }
                    });


                    // Aqui se obtiene el enlace para la muestra de resultados por busqueda de cwr
                    $.ajax({
                        url: rootURI + "specie-information.php?specie=" + $("#search-value").val(),
                        dataType: "json",
                        contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                        beforeSend: function() {
                            $("#conservation-section").hide("fast");
                            $(".header").hide("fast");
                            $(".header").html(""); //  Borrar el html del div, para evitar mezclar info. previa
                        },
                        success: function(data) {
                            if (data == null || data.length == 0) {
                                
                            } else { // Hay que mostrar el enlace para los resultados
                                // Concatenando el enlace dentro del div para mostrar informacion
                                if (data.validName) {
                                    $(".header").append("<span><a href=http://www.cwrdiversity.org/checklist/cwr-details.php?specie_id=" + data.taxonID + " target=_BLANK><b>" + data.validName + "</b>");
                                    $(".header").append(" (" + data.specieName + ")</a></span>");
                                } else {
                                    if(data.specieName) {
                                        $(".header").append("<a href=http://www.cwrdiversity.org/checklist/cwr-details.php?specie_id=" + data.taxonID + " target=_BLANK><b>" + data.specieName + "</b></a>");
                                    }else{
                                        $(".header").append("<a href=http://www.cwrdiversity.org/checklist/cwr-details.php?specie_id=" + data.taxonID + " target=_BLANK><b>" + $("#search-value").val() + "</b></a>");  
                                    }
                                }
                                $(".header").append("<br>");

                                if (data.commonName) {
                                    $(".header").append("Common name: " + data.commonName + "</br>");
                                }
                                
                                if(data.mainCropList){
                                    $.each(data.mainCropList, function(index, item) {
                                        if(item.type != "Graftstock" && item.type.indexOf("[PT]") == -1){
                                            $(".header").append(item.type + " " + item.level + " relative of <br><a href=" + cwrInventoryResults + "/genepool-details.php?id[]=" + item.mainCropID + " target=_BLANK>" + item.mainCropName + "</a><br>");
                                        } else if(item.level == "Confirmed" && item.type != "graftstock") {
                                            $(".header").append(item.level + " use in breeding for <br><a href=" + cwrInventoryResults + "/genepool-details.php?id[]=" + item.mainCropID + " target=_BLANK>" + item.mainCropName + "</a><br>");
                                        } else if (item.level == "Potential" && item.type != "graftstock"){
                                            $(".header").append(item.level + " use in crop breeding for <br><a href=" + cwrInventoryResults + "/genepool-details.php?id[]=" + item.mainCropID + " target=_BLANK>" + item.mainCropName + "</a><br>");
                                        } else {
                                              $(".header").append(item.level + " use as " + item.type + " for <br><a href=" + cwrInventoryResults + "/genepool-details.php?id[]=" + item.mainCropID + " target=_BLANK>" + item.mainCropName + "</a><br>");
                                        }
                                    });
                                }
                                $(".header").show("fast");
                            }
                        },
                        error: function(request, status, error){
                            
                        }
                    });

                    $.ajax({
                        url: rootURI + "specie-information.php?searchType=getFPC&specie=" + $("#search-value").val(),
                        dataType: "json",
                        contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                        beforeSend: function() {
                            $("#conservation-section").hide("fast");
                            $("#HPS > div").css({
                                opacity: 0.2
                            });
                            $("#HPS > div").css({
                                '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"
                            });
                            $("#MPS > div").css({
                                opacity: 0.2
                            });
                            $("#MPS > div").css({
                                '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"
                            });
                            $("#LPS > div").css({
                                opacity: 0.2
                            });
                            $("#LPS > div").css({
                                '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"
                            });
                            $("#NFCR > div").css({
                                opacity: 0.2
                            });
                            $("#NFCR > div").css({
                                '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"
                            });
                        },
                        success: function(data) {
                            if (data == null || data.length == 0) {
                            } else { // Hay que mostrar el enlace para los resultados
                                FPCAT = data.FPCAT;
                                if (FPCAT == "HPS") {
                                    $("#HPS > div").css({
                                        opacity: 1.0
                                    });
                                    $("#HPS > div").css({
                                        '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"
                                    });
                                } else if (FPCAT == "MPS") {
                                    $("#MPS > div").css({
                                        opacity: 1.0
                                    });
                                    $("#MPS > div").css({
                                        '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"
                                    });
                                } else if (FPCAT == "LPS") {
                                    $("#LPS > div").css({
                                        opacity: 1.0
                                    });
                                    $("#LPS > div").css({
                                        '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"
                                    });
                                } else if (FPCAT == "NFCR") {
                                    $("#NFCR > div").css({
                                        opacity: 1.0
                                    });
                                    $("#NFCR > div").css({
                                        '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"
                                    });
                                }
                                $("#conservation-section").slideDown("slow");
                            }
                        },
                        error: function(request, status, error) {
                            
                        }
                    });

                } else { // Busqueda a realizar por medio de gene pool 

                    if (last_search_value != null && last_search_value != $("#search-value").val()) {
                        $(".typeMap > img").hide("slow");
                    }

                    $.ajax({
                        url: rootURI + "show-information.php?genepool=" + $("#search-value").val() + "&map_type=" + mapType,
                        dataType: "json",
                        contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                        beforeSend: function() {
                            clearMarkers(); // Limpiando marcadores para realizar varias busquedas
                            $("#conservation-section").hide("fast");
                            $(".header").hide("fast");
                            $(".header").html(""); //  Borrar el html del div, para evitar mezclar info. previa
                            $("#no_data").hide("fast");
                            $("#tituloEscalaColores").html("");
                            $("#escalaColores").html("");
                            $("#tituloEscalaColores").hide("fast");
                            $("#escalaColores").hide("fast");
                            last_search_value = $("#search-value").val();
                        },
                        success: function(data) {
                            if (data == null || data.length == 0 || data.no_data == 1) {
                                hide_modal_window();
                                $('#empty_value_error').hide('fast');
                                $("#no_data").html("Sorry, no data for <b>" + $("#search-value").val() + "</b><br>");
                                $("#no_data").append("<center><div id='closeButton'>Close</div></center>");
                                //$("#accepted-species").show('slow');
                                $("#no_data").show("slide", {
                                    direction: "left"
                                }, "slow");
                            } else { // Para cada resultado se deben extraer los puntos
                                if (data != null) {
                                    if ($("#roadmap").hasClass('selected')) {
                                        initializeWithMapType("map");
                                    } else if ($("#terrain").hasClass('selected')) {
                                        initializeWithMapType("terrain");
                                    } else {
                                        initializeWithMapType("sattelite");
                                    }
                                    showTileImages(data.url, null);
                                    if (mapType == "genepool_gap_richness") {
                                        $("#tituloEscalaColores").html("Number of high priority taxa");
                                    } else {
                                        $("#tituloEscalaColores").html("Number of taxa");
                                    }
                                    if (mapType == "genepool_species_richness") {
                                        $("#escalaColores").html("<img src='" + data.url + "/" + "species-richnessscaleTestImage.png' />");
                                    } else if (mapType == "genepool_gap_richness") {
                                        $("#escalaColores").html("<img src='" + data.url + "/" + "gap-richnessscaleTestImage.png' />");
                                    }
                                    $("#tituloEscalaColores").show("fast");
                                    $("#escalaColores").show("fast");
                                }
                            }
                        },
                        complete: function() {
                            $("#search-button").attr('disabled', false);
                            hide_modal_window();
                        },
                        error: function(jqXHR, textStatus, errorThrown) {  // Error de lectura en el archivo

                        }
                    });

                    // Obtener enlace para gene pool
                    $.ajax({
                        url: rootURI + "specie-information.php?searchType=genepool&genepool=" + $("#search-value").val(),
                        dataType: "json",
                        contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                        beforeSend: function() {
                            $("#single-layers-div").hide("fast");
                            $("#single-layers-div").html("");
                            $(".single-layers").hide("fast");
                        },
                        success: function(data) {
                            if (data == null || data.length == 0) {
                                hide_modal_window();
                            } else { // Hay que mostrar el enlace para los resultados
                                // Concatenando el enlace dentro del div para mostrar informacion
                                $(".header").append("<a href=" + cwrInventoryResults + "/genepool-details.php?id[]=" + data.id + " target=_BLANK>" + data.genepoolName + "</a>");

                                if (data.synonimName) {
                                    $(".header").append("<br>(" + data.synonimName + ")");
                                }

                                if (data.commonName) {
                                    $(".header").append("<br>Common name: " + data.commonName);
                                }
                                $(".header").show("fast");
                                
                                var htmlText = "";
                                $.each(data.genepoolConcepts, function(index, item) {
                                    htmlText += "<div class='single-layer-title'>" + item[0] + "</div>";
                                    htmlText += "<div class='specie-div'>";
                                    $.each(item[1], function(index2, taxonInformation) {
                                        htmlText += "<div class='individual-specie-container'>"; // Al parecer estaba tomando el padre y al obtener el titulo obtenia todos los nombres scientificos de todas las especies en la caja
                                        htmlText += "<div class='specie-title'><a href='" + cwrInventoryResults + "/cwr-details.php?specie_id=" + taxonInformation.ID + "' target=_BLANK>" + taxonInformation.scientificName + "</a></div>";
                                        htmlText += "<div class='specie-content'>";
                                        htmlText += "<div class='typeMap' id='gap-points'><img src='http://www.cwrdiversity.org/CWR-DistributionMap/themes/yamidoo-child/images/check.png'/>Occurrence data</div><div class='typeMap' id='gap-gap'><img src='http://www.cwrdiversity.org/CWR-DistributionMap/themes/yamidoo-child/images/check.png'/>Potential distribution map</div><div class='typeMap' id='gap-gap_spp'><img src='http://www.cwrdiversity.org/CWR-DistributionMap/themes/yamidoo-child/images/check.png'/>Collecting priorities map</div>";
                                        htmlText += "</div>";
                                        htmlText += "</div>";
                                    });
                                    htmlText += "</div>";
                                });
                                
                                $("#gap-points").find('img').hide('fast');
                                $("#single-layers-div").append(htmlText);
                                $("#single-layers-div").slideDown('slow');
                                $(".single-layers").show("fast");
                                $("#gap-points > img").hide("fast");
                                $("#gap-gap > img").hide("fast");
                                $("#gap-gap_spp > img").hide("fast");
                            }
                        },
                        complete: function() {

                        }
                    });
                }
            } else { // Error no se ha especificado ningun tipo de informacion para el genero / especie
                hide_modal_window();
                // Limpiando el cuadro de busquedas anterior
                mapType = null; // Valor por defecto del mapa
                $(".typeMap > img").hide("fast");
                $(".typeMap > img").css("background-color", '#EDF9ED');
                $("#search-value").focus();
                $("#search-value").tooltip({
                    tooltipClass: "tooltip"
                });
                $("#search-value").mouseenter();
            }
        }

    });

    /* Ocultar y mostrar contenido de busqueda para gap_spp y gap_riohness cuando se genere
         * un evento de muestra u ocultar */
    $("#gap-spp-div > div.title").click(function() {
        if ($("#gap-spp-div > div.content").is(":visible")) {
            $("#gap-spp-div > div.content").hide('fast');
            $(this).find("#minus").hide('fast');
            $(this).find("#plus").show('fast');
        } else {
            $("#gap-richness-div > div.content").hide('fast');
            $("#gap-spp-div > div.content").show('fast');
            $(this).find("#minus").show('fast');
            $(this).find("#plus").hide('fast');
        }
    });

    $("#gap-richness-div > div.title").click(function() {
        if ($("#gap-richness-div > div.content").is(":visible")) {
            $("#gap-richness-div > div.content").hide('fast');
            $(this).find("#minus").hide('fast');
            $(this).find("#plus").show('fast');
        } else {
            $("#gap-spp-div > div.content").hide('fast');
            $("#gap-richness-div > div.content").show('fast');
            $(this).find("#minus").show('fast');
            $(this).find("#plus").hide('fast');
        }
    });

    $("#taxonomic_information_slider").click(function() {
        if ($("#taxonomic_information").is(":visible")) {
            $("#taxonomic_information").hide("slide", {
                direction: 'right'
            });
        } else {
            $("#taxonomic_information").show("slide", {
                direction: 'right'
            });
        }
    });

    /* Estableciendo los controles para el tipo de mapa, Con esta implementacion se podra realizar la busqueda
         * sin necesidad de hacer clic en el boton Search */

    $("#gap-points").live('click', function(e) {
        e.preventDefault();

        var taxon = $(this).parent().parent().find(".specie-title").text(); // A diferencia de las busquedas normales de species, en este casos e busca por lo que se tenga en el titulo de la caja
        if (taxon != last_taxon && last_taxon != null) {
            $(".typeMap > img").hide("fast");
            clearMarkers();
        }

        if ($(this).find("img").is(":visible")) {
            $(this).find("img").hide("fast");
            clearMarkers();
        } else {
            $(this).find("img").show("fast");

            $('#loadingWindow').reveal({
                closeonbackgroundclick: false
            });
            $('#loadingWindow').show('fast');
            $('.reveal-modal-bg').show('fast');
            if (taxon != null && taxon != "") {
                last_taxon = taxon;
                $.ajax({
                    url: rootURI + "show-information.php?specie=" + taxon + "&map_type=" + "points",
                    dataType: "json",
                    contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                    beforeSend: function() {
                        //clearMarkers(); // Limpiando marcadores para realizar varias busquedas
                        $("#tituloEscalaColores").html("");
                        $("#escalaColores").html("");
                        $("#tituloEscalaColores").hide("fast");
                        $("#escalaColores").hide("fast");
                        $("#genepool-conservation-section").hide("fast");
                        $("#no_data").hide("fast");
                        $(".header").hide("fast");
                        $(".header").html(""); //  Borrar el html del div, para evitar mezclar info. previa
                    },
                    success: function(data) {
                        if (data == null || data.length == 0 || data.no_data == 1) {
                            hide_modal_window();
                            $('#empty_value_error').hide('fast');
                            $("#no_data").html("Sorry, no data for <br><b>" + taxon + "</b><br>");
                            $("#no_data").append("<center><div id='closeButton'>Close</div></center>");
                            $("#no_data").show("slide", {
                                direction: "left"
                            }, "slow");

                        } else { // Para cada resultado se deben extraer los puntos
                            clearMarkers();
                            if (!$(this).parent().find("#gap-gap_spp > img").is(":visible") && !$(this).parent().find("#gap-gap > img").is(":visible")) {
                                if ($("#roadmap").hasClass('selected')) {
                                    initializeWithMapType("map");
                                } else if ($("#terrain").hasClass('selected')) {
                                    initializeWithMapType("terrain");
                                } else {
                                    initializeWithMapType("sattelite");
                                }
                            }

                            var points = [];
                            $.each(data, function(index, item) {
                                points.push([item.latitude, item.longitude]);
                            });
                            showMarkers(points);
                        }
                    },
                    complete: function() {
                        hide_modal_window();
                        $("#search-button").attr('disabled', false);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {

                    }
                });

                // Aqui se obtiene el enlace para la muestra de resultados por busqueda de cwr
                $.ajax({
                    url: rootURI + "specie-information.php?specie=" + taxon,
                    dataType: "json",
                    contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                    beforeSend: function() {
                        $("#genepool-conservation-section").hide("fast");
                        $(".header").hide("fast");
                        $(".header").html(""); //  Borrar el html del div, para evitar mezclar info. previa
                    },
                    success: function(data) {
                        if (data == null || data.length == 0) {

                        } else { // Hay que mostrar el enlace para los resultados
                            // Concatenando el enlace dentro del div para mostrar informacion
                            if (data.validName) {
                                $(".header").append("<b>" + data.validName + "</b>");
                                $(".header").append(" (" + data.specieName + ")");
                            } else {
                                $(".header").append("<b>" + data.specieName + "</b>");
                            }
                            $(".header").append("<br>");

                            if (data.commonName) {
                                $(".header").append("Common name: " + data.commonName + "</br>");
                            }

                            $.each(data.mainCropList, function(index, item) {
                                $(".header").append(item.type + " " + item.level + " relative of <br><a href=" + cwrInventoryResults + "/genepool-details.php?id[]=" + item.mainCropID + " target=_BLANK>" + item.mainCropName + "</a><br>");
                            });
                            $(".header").show("fast");
                        }
                    }
                });

                $.ajax({
                    url: rootURI + "specie-information.php?searchType=getFPC&specie=" + taxon,
                    dataType: "json",
                    contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                    beforeSend: function() {
                        $("#genepool-conservation-section").hide("fast");
                        $("#HPS_Genepool > div").css({
                            opacity: 0.2
                        });
                        $("#HPS_Genepool > div").css({
                            '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"
                        });
                        $("#MPS_Genepool > div").css({
                            opacity: 0.2
                        });
                        $("#MPS_Genepool > div").css({
                            '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"
                        });
                        $("#LPS_Genepool > div").css({
                            opacity: 0.2
                        });
                        $("#LPS_Genepool > div").css({
                            '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"
                        });
                        $("#NFCR_Genepool > div").css({
                            opacity: 0.2
                        });
                        $("#NFCR_Genepool > div").css({
                            '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"
                        });
                    },
                    success: function(data) {
                        if (data == null || data.length == 0) {
                            hide_modal_window();
                            $('#empty_value_error').hide('fast');
                            $("#no_data").html("Sorry, no data for <br><b>" + taxon + "</b><br>");
                            $("#no_data").append("<center><div id='closeButton'>Close</div></center>");
                            $("#no_data").show("slide", {
                                direction: "left"
                            }, "slow");
                        } else { // Hay que mostrar el enlace para los resultados
                            FPCAT = data.FPCAT;
                            if (FPCAT == "HPS") {
                                $("#HPS_Genepool > div").css({
                                    opacity: 1.0
                                });
                                $("#HPS_Genepool > div").css({
                                    '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"
                                });
                            } else if (FPCAT == "MPS") {
                                $("#MPS_Genepool > div").css({
                                    opacity: 1.0
                                });
                                $("#MPS_Genepool > div").css({
                                    '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"
                                });
                            } else if (FPCAT == "LPS") {
                                $("#LPS_Genepool > div").css({
                                    opacity: 1.0
                                });
                                $("#LPS_Genepool > div").css({
                                    '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"
                                });
                            } else if (FPCAT == "NFCR") {
                                $("#NFCR_Genepool > div").css({
                                    opacity: 1.0
                                });
                                $("#NFCR_Genepool > div").css({
                                    '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"
                                });
                            }
                            $("#genepool-conservation-section").slideDown("slow");
                        }
                    },
                    error: function(request, status, error) {

                    }
                });

            }
        }
    });

    $("#gap-gap").live('click', function(e) {
        e.preventDefault();
        var taxon = $(this).parent().parent().find(".specie-title").text(); // A diferencia de las busquedas normales de species, en este casos e busca por lo que se tenga en el titulo de la caja
        if (taxon != last_taxon && last_taxon != null) {
            $(".typeMap > img").hide("fast");
            clearMarkers();
        }

        if ($(this).find("img").is(":visible")) {
            last_taxon = taxon;
            $(this).find("img").hide("slow");
            if ($("#roadmap").hasClass('selected')) {
                resetMap("map");
            } else if ($("#terrain").hasClass('selected')) {
                resetMap("terrain");
            } else {
                resetMap("sattelite");
            }
        } else {
            last_taxon = taxon;
            if ($(this).parent().find("#gap-gap_spp > img").is(":visible")) {
                $(this).parent().find("#gap-gap_spp > img").hide("slow");
                $(this).find("img").show("slow");
            } else {
                $(this).find("img").show("slow");
            }

            $('#loadingWindow').reveal({
                closeonbackgroundclick: false
            });
            $('#loadingWindow').show('fast');
            $('.reveal-modal-bg').show('fast');
            if (taxon != null && taxon != "") {
                $.ajax({
                    url: rootURI + "show-information.php?specie=" + taxon + "&map_type=" + "gap",
                    dataType: "json",
                    contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                    beforeSend: function() {
                        //clearMarkers(); // Limpiando marcadores para realizar varias busquedas
                        $("#tituloEscalaColores").html("");
                        $("#escalaColores").html("");
                        $("#tituloEscalaColores").hide("fast");
                        $("#escalaColores").hide("fast");
                        $("#conservation-section").hide("fast");
                        $(".header").hide("fast");
                        $(".header").html(""); //  Borrar el html del div, para evitar mezclar info. previa
                        $("#no_data").hide("fast");
                    },
                    success: function(data) {
                        if (data == null || data.length == 0 || data.no_data == 1) {
                            hide_modal_window();
                            $('#empty_value_error').hide('fast');
                            $("#no_data").html("Sorry, no data for <b>" + taxon + "</b><br>");
                            $("#no_data").append("<center><div id='closeButton'>Close</div></center>");
                            $("#no_data").show("slide", {
                                direction: "left"
                            }, "slow");
                        } else { // Para cada resultado se deben extraer los puntos
                            if ($("#roadmap").hasClass('selected')) {
                                initializeWithMapType("map");
                            } else if ($("#terrain").hasClass('selected')) {
                                initializeWithMapType("terrain");
                            } else {
                                initializeWithMapType("sattelite");
                            }
                            var tile_points = [];
                            $.each(data.pointList, function(index, item) {
                                tile_points.push([item.latitude, item.longitude]);
                            });
                            showTileImages(data.url, tile_points);
                        // No se muestran las escalas de los tiles cuando se hace de manera individual por specie
                        }
                    },
                    complete: function() {
                        hide_modal_window();
                        $("#search-button").attr('disabled', false);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {

                    }
                });

                // Aqui se obtiene el enlace para la muestra de resultados por busqueda de cwr
                $.ajax({
                    url: rootURI + "specie-information.php?specie=" + taxon,
                    dataType: "json",
                    contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                    beforeSend: function() {
                        $("#conservation-section").hide("fast");
                        $(".header").hide("fast");
                        $(".header").html(""); //  Borrar el html del div, para evitar mezclar info. previa
                    },
                    success: function(data) {
                        if (data == null || data.length == 0) {

                        } else { // Hay que mostrar el enlace para los resultados
                            // Concatenando el enlace dentro del div para mostrar informacion
                            if (data.validName) {
                                $(".header").append("<b>" + data.validName + "</b>");
                                $(".header").append(" (" + data.specieName + ")");
                            } else {
                                $(".header").append("<b>" + data.specieName + "</b>");
                            }
                            $(".header").append("<br>");

                            if (data.commonName) {
                                $(".header").append("Common name: " + data.commonName + "</br>");
                            }


                            $.each(data.mainCropList, function(index, item) {
                                $(".header").append(item.type + " " + item.level + " relative of <br><a href=" + cwrInventoryResults + "/genepool-details.php?id[]=" + item.mainCropID + " target=_BLANK>" + item.mainCropName + "</a><br>");
                            });
                            $(".header").show("fast");
                        }
                    }
                });

                $.ajax({
                    url: rootURI + "specie-information.php?searchType=getFPC&specie=" + taxon,
                    dataType: "json",
                    contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                    beforeSend: function() {
                        $("#conservation-section").hide("fast");
                        $("#HPS > div").css({
                            opacity: 0.2
                        });
                        $("#HPS > div").css({
                            '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"
                        });
                        $("#MPS > div").css({
                            opacity: 0.2
                        });
                        $("#MPS > div").css({
                            '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"
                        });
                        $("#LPS > div").css({
                            opacity: 0.2
                        });
                        $("#LPS > div").css({
                            '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"
                        });
                        $("#NFCR > div").css({
                            opacity: 0.2
                        });
                        $("#NFCR > div").css({
                            '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"
                        });
                    },
                    success: function(data) {
                        if (data == null || data.length == 0) {
                            hide_modal_window();
                            $('#empty_value_error').hide('fast');
                            $("#no_data").html("Sorry, no data to <br><b>" + $("#search-value").val() + "</b><br>");
                            $("#no_data").append("<center><div id='closeButton'>Close</div></center>");
                            $("#no_data").show("slide", {
                                direction: "left"
                            }, "slow");
                        } else { // Hay que mostrar el enlace para los resultados
                            FPCAT = data.FPCAT;
                            if (FPCAT == "HPS") {
                                $("#HPS > div").css({
                                    opacity: 1.0
                                });
                                $("#HPS > div").css({
                                    '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"
                                });
                            } else if (FPCAT == "MPS") {
                                $("#MPS > div").css({
                                    opacity: 1.0
                                });
                                $("#MPS > div").css({
                                    '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"
                                });
                            } else if (FPCAT == "LPS") {
                                $("#LPS > div").css({
                                    opacity: 1.0
                                });
                                $("#LPS > div").css({
                                    '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"
                                });
                            } else if (FPCAT == "NFCR") {
                                $("#NFCR > div").css({
                                    opacity: 1.0
                                });
                                $("#NFCR > div").css({
                                    '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"
                                });
                            }
                            $("#conservation-section").slideDown("slow");
                        }
                    },
                    error: function(request, status, error) {

                    }
                });

            }
        }

    });


    $("#gap-gap_spp").live('click', function(e) {
        e.preventDefault();
        var taxon = $(this).parent().parent().find(".specie-title").text(); // A diferencia de las busquedas normales de species, en este casos e busca por lo que se tenga en el titulo de la caja

        if (taxon != last_taxon && last_taxon != null) {
            $(".typeMap > img").hide("fast");
            clearMarkers();
        }

        if ($(this).find("img").is(":visible")) {
            last_taxon = taxon;
            $(this).find("img").hide("slow");
            if ($("#roadmap").hasClass('selected')) {
                resetMap("map");
            } else if ($("#terrain").hasClass('selected')) {
                resetMap("terrain");
            } else {
                resetMap("sattelite");
            }
        } else {
            last_taxon = taxon;
            if ($(this).parent().find("#gap-gap > img").is(":visible")) {
                $(this).parent().find("#gap-gap > img").hide("slow");
                $(this).find("img").show("slow");
            } else {
                $(this).find("img").show("slow");
            }

            $('#loadingWindow').reveal({
                closeonbackgroundclick: false
            });
            $('#loadingWindow').show('fast');
            $('.reveal-modal-bg').show('fast');
            if (taxon != null && taxon != "") {
                $.ajax({
                    url: rootURI + "show-information.php?specie=" + taxon + "&map_type=" + "gap_spp",
                    dataType: "json",
                    contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                    beforeSend: function() {
                        //clearMarkers(); // Limpiando marcadores para realizar varias busquedas
                        $("#tituloEscalaColores").html("");
                        $("#escalaColores").html("");
                        $("#tituloEscalaColores").hide("fast");
                        $("#escalaColores").hide("fast");
                        $("#conservation-section").hide("fast");
                        $(".header").hide("fast");
                        $(".header").html(""); //  Borrar el html del div, para evitar mezclar info. previa
                        $("#no_data").hide("fast");
                    },
                    success: function(data) {
                        if (data == null || data.length == 0 || data.no_data == 1) {
                            hide_modal_window();
                            $('#empty_value_error').hide('fast');
                            $("#no_data").html("Sorry, no data to   <b>" + $("#search-value").val() + "</b><br>");
                            $("#no_data").append("<center><div id='closeButton'>Close</div></center>");
                            $("#no_data").show("slide", {
                                direction: "left"
                            }, "slow");
                        } else { // Para cada resultado se deben extraer los puntos
                            if ($("#roadmap").hasClass('selected')) {
                                initializeWithMapType("map");
                            } else if ($("#terrain").hasClass('selected')) {
                                initializeWithMapType("terrain");
                            } else {
                                initializeWithMapType("sattelite");
                            }
                            var tile_points = [];
                            $.each(data.pointList, function(index, item) {
                                tile_points.push([item.latitude, item.longitude]);
                            });
                            showTileImages(data.url, tile_points);
                        }
                    },
                    complete: function() {
                        hide_modal_window();
                        $("#search-button").attr('disabled', false);
                    },
                    error: function(jqXHR, textStatus, errorThrown) {

                    }
                });

                // Aqui se obtiene el enlace para la muestra de resultados por busqueda de cwr
                $.ajax({
                    url: rootURI + "specie-information.php?specie=" + taxon,
                    dataType: "json",
                    contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                    beforeSend: function() {
                        $("#conservation-section").hide("fast");
                        $(".header").hide("fast");
                        $(".header").html(""); //  Borrar el html del div, para evitar mezclar info. previa
                    },
                    success: function(data) {

                        if (data == null || data.length == 0) {

                        } else { // Hay que mostrar el enlace para los resultados
                            // Concatenando el enlace dentro del div para mostrar informacion
                            if (data.validName) {
                                $(".header").append("<b>" + data.validName + "</b>");
                                $(".header").append(" (" + data.specieName + ")");
                            } else {
                                $(".header").append("<b>" + data.specieName + "</b>");
                            }
                            $(".header").append("<br>");

                            if (data.commonName) {
                                $(".header").append("Common name: " + data.commonName + "</br>");
                            }

                            $.each(data.mainCropList, function(index, item) {
                                $(".header").append(item.type + " " + item.level + " relative of <br><a href=" + cwrInventoryResults + "/genepool-details.php?id[]=" + item.mainCropID + " target=_BLANK>" + item.mainCropName + "</a><br>");
                            });
                            $(".header").show("fast");
                        }

                    }
                });

                $.ajax({
                    url: rootURI + "specie-information.php?searchType=getFPC&specie=" + taxon,
                    dataType: "json",
                    contentType: "application/x-www-form-urlencoded;charset=UTF-8",
                    beforeSend: function() {
                        $("#conservation-section").hide("fast");
                        $("#HPS > div").css({
                            opacity: 0.2
                        });
                        $("#HPS > div").css({
                            '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"
                        });
                        $("#MPS > div").css({
                            opacity: 0.2
                        });
                        $("#MPS > div").css({
                            '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"
                        });
                        $("#LPS > div").css({
                            opacity: 0.2
                        });
                        $("#LPS > div").css({
                            '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"
                        });
                        $("#NFCR > div").css({
                            opacity: 0.2
                        });
                        $("#NFCR > div").css({
                            '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=20)"
                        });
                    },
                    success: function(data) {
                        if (data == null || data.length == 0) {
                            hide_modal_window();
                            $('#empty_value_error').hide('fast');
                            $("#no_data").html("Sorry, no data to <br><b>" + $("#search-value").val() + "</b><br>");
                            $("#no_data").append("<center><div id='closeButton'>Close</div></center>");
                            $("#no_data").show("slide", {
                                direction: "left"
                            }, "slow");
                        } else { // Hay que mostrar el enlace para los resultados
                            FPCAT = data.FPCAT;

                            if (FPCAT == "HPS") {
                                $("#HPS > div").css({
                                    opacity: 1.0
                                });
                                $("#HPS > div").css({
                                    '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"
                                });
                            } else if (FPCAT == "MPS") {
                                $("#MPS > div").css({
                                    opacity: 1.0
                                });
                                $("#MPS > div").css({
                                    '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"
                                });
                            } else if (FPCAT == "LPS") {
                                $("#LPS > div").css({
                                    opacity: 1.0
                                });
                                $("#LPS > div").css({
                                    '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"
                                });
                            } else if (FPCAT == "NFCR") {
                                $("#NFCR > div").css({
                                    opacity: 1.0
                                });
                                $("#NFCR > div").css({
                                    '-ms-filter': "progid:DXImageTransform.Microsoft.Alpha(Opacity=100)"
                                });
                            }
                            $("#conservation-section").slideDown("slow");
                        }
                    },
                    error: function(request, status, error) {

                    }
                });

            }
        }
    });


    $("#helpButton").click(function() {
        $("#help_window").addClass('show-scrollbar');
        $("#help_window").removeClass('hide-scrollbar');
        $("#help_window").reveal({
            closeonbackgroundclick: true
        });
        $('#help_window').show('fast');
        $('.reveal-modal-bg').show('fast');
    });

    $("#points").click(function() { // Evento para puntos
        if ($("#points > img").is(":visible")) { // Si esta visible la busqueda por puntos
            clearMarkers();
        } else {
            mapType = "points";
        }

        $(this).trigger("event-typemap-click");
        if (typeMapEnabled) {
            $("#search-button").trigger("click");
        }
    });

    $("#gap").click(function() { // Evento para gap maps 

        if ($("#gap > img").is(":visible")) {
            if ($("#roadmap").hasClass('selected')) {
                resetMap("map");
            } else if ($("#satellite").hasClass('selected')) {
                resetMap("sattelite");
            } else if ($("#terrain").hasClass('selected')) {
                resetMap("terrain");
            }
        } else {
            mapType = "gap";
        }

        $(this).trigger("event-typemap-click");
        if (typeMapEnabled) {
            $("#search-button").trigger("click");
        }
    });


    $("#gap_spp").click(function() { // Evento para gap spp

        if ($("#gap_spp > img").is(":visible")) {
            if ($("#roadmap").hasClass('selected')) {
                resetMap("map");
            } else if ($("#satellite").hasClass('selected')) {
                resetMap("sattelite");
            } else if ($("#terrain").hasClass('selected')) {
                resetMap("terrain");
            }
        } else {
            mapType = "gap_spp";
        }

        $(this).trigger("event-typemap-click");
        if (typeMapEnabled) {
            $("#search-button").trigger("click");
        }
    });

    $("#genepool_species_richness").click(function() {
        if ($("#genepool_species_richness > img").is(":visible")) {
            if ($("#roadmap").hasClass('selected')) {
                resetMap("map");
            } else if ($("#satellite").hasClass('selected')) {
                resetMap("sattelite");
            } else if ($("#terrain").hasClass('selected')) {
                resetMap("terrain");
            }
        } else {
            $(".typeMap > img").hide("fast");
            $(".typeMap > img").css("background-color", '#EDF9ED');
            mapType = "genepool_species_richness";
        }

        $(this).trigger("event-typemap-click");
        if (typeMapEnabled) {
            $("#search-button").trigger("click");
        }
    });

    $("#genepool_gap_richness").click(function() {
        if ($("#genepool_gap_richness > img").is(":visible")) {
            if ($("#roadmap").hasClass('selected')) {
                resetMap("map");
            } else if ($("#satellite").hasClass('selected')) {
                resetMap("sattelite");
            } else if ($("#terrain").hasClass('selected')) {
                resetMap("terrain");
            }
        } else {
            $(".typeMap > img").hide("fast");
            $(".typeMap > img").css("background-color", '#EDF9ED');
            mapType = "genepool_gap_richness";
        }

        $(this).trigger("event-typemap-click");
        if (typeMapEnabled) {
            $("#search-button").trigger("click");
        }
    });

    $("#global_species_richness").click(function() {
        $(".typeMap > img").hide("slow");
        resetMapTypes();
        if ($("#roadmap").hasClass('selected')) {
            resetMap("map");
        } else if ($("#satellite").hasClass('selected')) {
            resetMap("sattelite");
        } else if ($("#terrain").hasClass('selected')) {
            resetMap("terrain");
        }
        mapType = "global_species_richness";
        $(this).trigger("event-typemap-click");
        if (typeMapEnabled) {
            $("#global-summary").trigger("click");
        }
    });

    $("#global_gap_richness").click(function() {
        $(".typeMap > img").hide("slow");
        $(".typeMap > img").css("background-color", '#EDF9ED');
        resetMapTypes();
        if ($("#roadmap").hasClass('selected')) {
            resetMap("map");
        } else if ($("#satellite").hasClass('selected')) {
            resetMap("sattelite");
        } else if ($("#terrain").hasClass('selected')) {
            resetMap("terrain");
        }
        mapType = "global_gap_richness";
        $(this).trigger("event-typemap-click");
        if (typeMapEnabled) {
            $("#global-summary").trigger("click");
        }
    });

    /* Establecer control de busquedas como arrastrable */
    $("#searchTypeControl").draggable({
        containment: "#map_canvas",
        handle: ".title, .header",
        scroll: false
    });

    $("#accepted-species").draggable({
        containment: "#map_canvas",
        /*handle: ".title",*/
        scroll: false
    });

    $("#search-value").keypress(function() {
        $("#search-value").tooltip('disable'); // Quitar el foco del mouse para desapareceer el tool tip
    });

    $("#control-plus").click(function() {
        increaseZoom();
    });

    $("#control-minus").click(function() {
        decreaseZoom();
    });

    // Hace la funcion del clic cuando se hace enter en la busqueda
    $("#search-value").keyup(function(event) {
        if (event.keyCode == 13) {
            $("#search-button").trigger("click");
        }
    });

    $("#closeButton").live("click", function() {
        $("#no_data").hide("slide", {
            direction: "left"
        }, "slow");
    });

    $(".section > .title").click(function() {
        $content = $(this).parent().find(".content");

        if ($content.is(":visible")) {
            $(this).find(".more").show("fast");
            $(this).find(".minus").hide("fast");
            $content.slideUp();
        } else {
            $(this).find(".minus").show("fast");
            $(this).find(".more").hide("fast");
            $content.slideDown();
        }
    });

    $("#help_window > span").click(function() {
        hide_modal_window();
        $('#video_tutorial').reveal({
            closeonbackgroundclick: true
        });
        $('#video_tutorial').show('fast');
        $('.reveal-modal-bg').show('fast');
        $('#video_tutorial > iframe').attr("src", "//www.youtube.com/embed/O6JHveohPrk");
        $("#video_tutorial > iframe").show('fast');
    });

});

/* Functiones para manejar el zoom del control del mapa */
function increaseZoom() {
    map.setZoom(map.getZoom() + 1);
}

function decreaseZoom() {
    if (map.getZoom() > 0) {
        map.setZoom(map.getZoom() - 1);
    }
}

/* Devuelve el tipo de mapa seleccionado de acuerdo a lo establecido por el usuario */
function getMapType() {
    if ($("#points > img").is(":visible")) {
        return "points";
    }
    if ($("#gap > img").is(":visible")) {
        return "gap";
    }
    if ($("#gap_spp > img").is(":visible")) {
        return "gap_spp";
    }
    if ($("#genepool_species_richness > img").is(":visible")) {
        return "genepool_species_richness";
    }
    if ($("#genepool_gap_richness > img").is(":visible")) {
        return "genepool_gap_richness";
    }
    if ($("#global_species_richness > img").is(":visible")) {
        return "global_species_richness";
    }
    if ($("#global_gap_richness > img").is(":visible")) {
        return "gap_species_richness";
    }
    return null;
}

/* Limpia los colores previos y las imagenes visualizadas anteriormente en las opciones de mapa */
function resetMapTypes() {
    $("#tituloEscalaColores").hide("fast");
    $("#escalaColores").hide("fast");
    $("#escalaColores").html("");
    $("#typeMapForm").css("background-color","#EDF9ED");
}

/* Close the modal window when the user clicked on any button over itself */
function hide_modal_window() {
    $('.reveal-modal').hide('slow');
    $('.reveal-modal-bg').hide('slow');
}

/* Drop autocomplete from input text */
function destroyAutocomplete() {
    jQuery("#search-value").autocomplete("destroy");
    jQuery("#search-value").removeData('autocomplete');
}
