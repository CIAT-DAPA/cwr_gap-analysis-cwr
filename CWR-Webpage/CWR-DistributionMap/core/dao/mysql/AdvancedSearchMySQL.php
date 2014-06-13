<?php

require_once WORKSPACE_DIR . 'config/db.php';
require_once WORKSPACE_DIR . 'core/dao/AdvancedSearchDAO.php';
require_once WORKSPACE_DIR . 'core/model/Taxon.php';

class AdvancedSearchMySQL implements AdvancedSearchDAO {

    private static $instance;

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new AdvancedSearchMySQL();
        }
        return self::$instance;
    }

    private function __construct() {
        
    }

    public function getResultsAdvancedSearch($term, $search_type, $countries, $regions, $concept_type, $concept_levels, $priority_genera) {
        global $db;
        $priority_taxa = "'Aegilops bicornis'
        ,'Aegilops bicornis var. anathera'
        ,'Aegilops bicornis var. bicornis'
        ,'Aegilops biuncialis'
        ,'Aegilops columnaris'
        ,'Aegilops comosa'
        ,'Aegilops comosa var. comosa'
        ,'Aegilops comosa var. subventricosa'
        ,'Aegilops crassa'
        ,'Aegilops cylindrica'
        ,'Aegilops geniculata'
        ,'Aegilops juvenalis'
        ,'Aegilops kotschyi'
        ,'Aegilops longissima'
        ,'Aegilops markgrafii'
        ,'Aegilops neglecta'
        ,'Aegilops peregrina'
        ,'Aegilops peregrina var. brachyathera'
        ,'Aegilops peregrina var. peregrina'
        ,'Aegilops searsii'
        ,'Aegilops sharonensis'
        ,'Aegilops speltoides'
        ,'Aegilops speltoides var. ligustica'
        ,'Aegilops speltoides var. speltoides'
        ,'Aegilops tauschii'
        ,'Aegilops triuncialis'
        ,'Aegilops triuncialis var. persica'
        ,'Aegilops triuncialis var. triuncialis'
        ,'Aegilops umbellulata'
        ,'Aegilops uniaristata'
        ,'Aegilops vavilovii'
        ,'Aegilops ventricosa'
        ,'Amblyopyrum muticum'
        ,'Amblyopyrum muticum var. loliaceum'
        ,'Amblyopyrum muticum var. muticum'
        ,'Avena abyssinica'
        ,'Avena atherantha'
        ,'Avena byzantina'
        ,'Avena fatua'
        ,'Avena hybrida'
        ,'Avena insularis'
        ,'Avena macrostachya'
        ,'Avena maroccana'
        ,'Avena murphyi'
        ,'Avena occidentalis'
        ,'Avena pilosa'
        ,'Avena prostrata'
        ,'Avena sterilis'
        ,'Avena strigosa'
        ,'Avena trichophylla'
        ,'Cajanus acutifolius'
        ,'Cajanus albicans'
        ,'Cajanus cajanifolius'
        ,'Cajanus cinereus'
        ,'Cajanus confertiflorus'
        ,'Cajanus crassus'
        ,'Cajanus lanceolatus'
        ,'Cajanus latisepalus'
        ,'Cajanus lineatus'
        ,'Cajanus mollis'
        ,'Cajanus platycarpus'
        ,'Cajanus reticulatus'
        ,'Cajanus scarabaeoides'
        ,'Cajanus sericeus'
        ,'Cajanus trinervius'
        ,'Cajanus volubis'
        ,'Cicer bijugum'
        ,'Cicer echinospermum'
        ,'Cicer judaicum'
        ,'Cicer pinnatifidum'
        ,'Cicer reticulatum'
        ,'Daucus capillifolius'
        ,'Daucus carota subsp. azoricus'
        ,'Daucus carota subsp. cantabricus'
        ,'Daucus carota subsp. carota'
        ,'Daucus carota subsp. commutatus'
        ,'Daucus carota subsp. drepanensis'
        ,'Daucus carota subsp. fontanesii'
        ,'Daucus carota subsp. gadecaei'
        ,'Daucus carota subsp. gummifer'
        ,'Daucus carota subsp. halophilus'
        ,'Daucus carota subsp. hispanicus'
        ,'Daucus carota subsp. major'
        ,'Daucus carota subsp. majoricus'
        ,'Daucus carota subsp. maritimus'
        ,'Daucus carota subsp. maximus'
        ,'Daucus carota subsp. parviflorus'
        ,'Daucus carota subsp. rupestris'
        ,'Daucus syrticus'
        ,'Eleusine africana'
        ,'Eleusine floccifolia'
        ,'Eleusine indica'
        ,'Eleusine intermedia'
        ,'Eleusine kigeziensis'
        ,'Eleusine tristachya'
        ,'Helianthus annuus'
        ,'Helianthus anomalus'
        ,'Helianthus argophyllus'
        ,'Helianthus arizonensis'
        ,'Helianthus atrorubens'
        ,'Helianthus bolanderi'
        ,'Helianthus debilis'
        ,'Helianthus debilis subsp. cucumerifolius'
        ,'Helianthus debilis subsp. debilis'
        ,'Helianthus debilis subsp. silvestris'
        ,'Helianthus debilis subsp. tardiflorus'
        ,'Helianthus debilis subsp. vestitus'
        ,'Helianthus deserticola'
        ,'Helianthus divaricatus'
        ,'Helianthus exilis'
        ,'Helianthus giganteus'
        ,'Helianthus grosseserratus'
        ,'Helianthus hirsutus'
        ,'Helianthus maximilianii'
        ,'Helianthus neglectus'
        ,'Helianthus niveus'
        ,'Helianthus niveus subsp. canescens'
        ,'Helianthus niveus subsp. niveus'
        ,'Helianthus niveus subsp. tephrodes'
        ,'Helianthus paradoxus'
        ,'Helianthus pauciflorus'
        ,'Helianthus petiolaris'
        ,'Helianthus petiolaris subsp. fallax'
        ,'Helianthus petiolaris subsp. petiolaris'
        ,'Helianthus praecox'
        ,'Helianthus praecox subsp. hirtus'
        ,'Helianthus praecox subsp. praecox'
        ,'Helianthus praecox subsp. runyonii'
        ,'Helianthus resinosus'
        ,'Helianthus salicifolius'
        ,'Helianthus silphioides'
        ,'Helianthus strumosus'
        ,'Helianthus tuberosus'
        ,'Hordeum brevisubulatum'
        ,'Hordeum bulbosum'
        ,'Hordeum chilense'
        ,'Hordeum vulgare subsp. spontaneum'
        ,'Ipomoea batatas var. apiculata'
        ,'Ipomoea cordatotriloba'
        ,'Ipomoea cynanchifolia'
        ,'Ipomoea grandifolia'
        ,'Ipomoea lacunosa'
        ,'Ipomoea leucantha'
        ,'Ipomoea littoralis'
        ,'Ipomoea ramosissima'
        ,'Ipomoea tabascana'
        ,'Ipomoea tenuissima'
        ,'Ipomoea tiliacea'
        ,'Ipomoea trifida'
        ,'Ipomoea triloba'
        ,'Ipomoea umbraticola'
        ,'Lathyrus chrysanthus'
        ,'Lathyrus gorgoni'
        ,'Lathyrus marmoratus'
        ,'Lathyrus pseudocicera'
        ,'Lathyrus sativus'
        ,'Lens culinaris subsp. odemensis'
        ,'Lens culinaris subsp. orientalis'
        ,'Lens culinaris subsp. tomentosus'
        ,'Lens ervoides'
        ,'Lens nigricans'
        ,'Malus baccata'
        ,'Malus baccata var. baccata'
        ,'Malus baccata var. daochengensis'
        ,'Malus baccata var. jinxianensis'
        ,'Malus baccata var. xiaojinensis'
        ,'Malus chitralensis'
        ,'Malus crescimannoi'
        ,'Malus doumeri'
        ,'Malus floribunda'
        ,'Malus fusca'
        ,'Malus halliana'
        ,'Malus honanensis'
        ,'Malus hupehensis'
        ,'Malus kansuensis'
        ,'Malus komarovii'
        ,'Malus mandshurica'
        ,'Malus muliensis'
        ,'Malus ombrophila'
        ,'Malus orientalis'
        ,'Malus prattii'
        ,'Malus prunifolia'
        ,'Malus pumila'
        ,'Malus sargentii'
        ,'Malus sieversii'
        ,'Malus sikkimensis'
        ,'Malus spectabilis'
        ,'Malus sylvestris'
        ,'Malus toringo'
        ,'Malus toringoides'
        ,'Malus transitoria'
        ,'Malus tschonoskii'
        ,'Malus yunnanensis'
        ,'Malus zumi'
        ,'Medicago papillosa'
        ,'Medicago papillosa subsp. macrocarpa'
        ,'Medicago papillosa subsp. papillosa'
        ,'Medicago prostrata'
        ,'Medicago sativa subsp. caerulea'
        ,'Medicago sativa subsp. falcata var. falcata'
        ,'Medicago sativa subsp. falcata var. viscosa'
        ,'Medicago sativa subsp. glomerata'
        ,'Medicago sativa subsp. hemicycla'
        ,'Medicago sativa subsp. sativa'
        ,'Medicago sativa subsp. tunetana'
        ,'Medicago sativa subsp. varia'
        ,'Medicago saxatilis'
        ,'Musa acuminata subsp. acuminata var. acuminata'
        ,'Musa acuminata subsp. banksii'
        ,'Musa acuminata subsp. burmannica'
        ,'Musa acuminata subsp. burmannicoides'
        ,'Musa acuminata subsp. errans'
        ,'Musa acuminata subsp. halabanensis'
        ,'Musa acuminata subsp. malaccensis'
        ,'Musa acuminata subsp. malaccensis var. minor'
        ,'Musa acuminata subsp. siamea'
        ,'Musa acuminata subsp. truncata'
        ,'Musa acuminata var. alasensis'
        ,'Musa acuminata var. bantamensis'
        ,'Musa acuminata var. breviformis'
        ,'Musa acuminata var. cerifera'
        ,'Musa acuminata var. chinensis'
        ,'Musa acuminata var. flava'
        ,'Musa acuminata var. longipetiolata'
        ,'Musa acuminata var. microcarpa'
        ,'Musa acuminata var. nakaii'
        ,'Musa acuminata var. rutilipes'
        ,'Musa acuminata var. sumatrana'
        ,'Musa acuminata var. tomentosa'
        ,'Musa acuminata var. zebrina'
        ,'Musa balbisiana var. andamica'
        ,'Musa balbisiana var. bakeri'
        ,'Musa balbisiana var. balbisiana'
        ,'Musa balbisiana var. brachycarpa'
        ,'Musa balbisiana var. dechangensis'
        ,'Musa balbisiana var. liukiuensis'
        ,'Musa flaviflora'
        ,'Musa schizocarpa'
        ,'Musa textilis'
        ,'Musa yunnanensis'
        ,'Musa yunnanensis var. caii'
        ,'Musa yunnanensis var. jingdongensis'
        ,'Musa yunnanensis var. yongpingensis'
        ,'Oryza alta'
        ,'Oryza australiensis'
        ,'Oryza barthii'
        ,'Oryza brachyantha'
        ,'Oryza eichingeri'
        ,'Oryza glaberrima'
        ,'Oryza glumipatula'
        ,'Oryza grandiglumis'
        ,'Oryza latifolia'
        ,'Oryza longistaminata'
        ,'Oryza malampuzhaensis'
        ,'Oryza meridionalis'
        ,'Oryza minuta'
        ,'Oryza nivara'
        ,'Oryza officinalis'
        ,'Oryza perennis var. cubensis'
        ,'Oryza punctata'
        ,'Oryza rhizomatis'
        ,'Oryza ridleyi'
        ,'Oryza rufipogon'
        ,'Oryza schweinfurthiana'
        ,'Pennisetum glaucum subsp. monodii'
        ,'Pennisetum orientale'
        ,'Pennisetum purpureum'
        ,'Pennisetum squamulatum'
        ,'Pennisetum stenostachyum'
        ,'Phaseolus albescens'
        ,'Phaseolus augusti'
        ,'Phaseolus coccineus'
        ,'Phaseolus costaricensis'
        ,'Phaseolus dumosus'
        ,'Phaseolus longiplacentifer'
        ,'Phaseolus lunatus'
        ,'Phaseolus mollis'
        ,'Phaseolus pachyrrhizoides'
        ,'Phaseolus persistentus'
        ,'Phaseolus vulgaris var. aborigineus'
        ,'Pisum abyssinicum'
        ,'Pisum fulvum'
        ,'Pisum sativum subsp. elatius var. brevipedunculatum'
        ,'Pisum sativum subsp. elatius var. elatius'
        ,'Pisum sativum subsp. elatius var. pumilo'
        ,'Secale cereale subsp. afghanicum'
        ,'Secale cereale subsp. ancestrale'
        ,'Secale cereale subsp. dighoricum'
        ,'Secale cereale subsp. segetale'
        ,'Solanum acaule'
        ,'Solanum acroglossum'
        ,'Solanum acroscopicum'
        ,'Solanum aculeatissimum'
        ,'Solanum adoense'
        ,'Solanum aethiopicum'
        ,'Solanum agnewiorum'
        ,'Solanum agrimonifolium'
        ,'Solanum albicans'
        ,'Solanum albornozii'
        ,'Solanum aldabrense'
        ,'Solanum andreanum'
        ,'Solanum anguivi'
        ,'Solanum anomalum'
        ,'Solanum asperolanatum'
        ,'Solanum aureitomentosum'
        ,'Solanum ayacuchense'
        ,'Solanum berthaultii'
        ,'Solanum boliviense'
        ,'Solanum bombycinum'
        ,'Solanum brevicaule'
        ,'Solanum buesii'
        ,'Solanum bulbocastanum'
        ,'Solanum burchellii'
        ,'Solanum burkartii'
        ,'Solanum cajamarquense'
        ,'Solanum campylacanthum'
        ,'Solanum candolleanum'
        ,'Solanum cantense'
        ,'Solanum capense'
        ,'Solanum catombelense'
        ,'Solanum cerasiferum'
        ,'Solanum chacoense'
        ,'Solanum chilliasense'
        ,'Solanum chiquidenum'
        ,'Solanum chomatophilum'
        ,'Solanum clarum'
        ,'Solanum coelestipetalum'
        ,'Solanum colombianum'
        ,'Solanum commersonii'
        ,'Solanum contumazaense'
        ,'Solanum cumingii'
        ,'Solanum cyaneopurpureum'
        ,'Solanum dasyphyllum'
        ,'Solanum deflexicarpum'
        ,'Solanum demissum'
        ,'Solanum flahaultii'
        ,'Solanum flavoviridens'
        ,'Solanum gandarillasii'
        ,'Solanum garcia-barrigae'
        ,'Solanum glabratum'
        ,'Solanum gracilifrons'
        ,'Solanum grandiflorum'
        ,'Solanum guerreroense'
        ,'Solanum hastifolium'
        ,'Solanum hastiforme'
        ,'Solanum hintonii'
        ,'Solanum hjertingii'
        ,'Solanum hougasii'
        ,'Solanum hovei'
        ,'Solanum huancabambense'
        ,'Solanum humile'
        ,'Solanum inaequiradians'
        ,'Solanum incanum'
        ,'Solanum incasicum'
        ,'Solanum infundibuliforme'
        ,'Solanum insanum'
        ,'Solanum iopetalum'
        ,'Solanum kurtzianum'
        ,'Solanum lamprocarpum'
        ,'Solanum laxissimum'
        ,'Solanum lesteri'
        ,'Solanum lichtensteinii'
        ,'Solanum lidii'
        ,'Solanum limbaniense'
        ,'Solanum linnaeanum'
        ,'Solanum litoraneum'
        ,'Solanum lobbianum'
        ,'Solanum longiconicum'
        ,'Solanum macracanthum'
        ,'Solanum macrocarpon'
        ,'Solanum maglia'
        ,'Solanum malindiense'
        ,'Solanum marginatum'
        ,'Solanum marinasense'
        ,'Solanum mauense'
        ,'Solanum medians'
        ,'Solanum microdontum'
        ,'Solanum morelliforme'
        ,'Solanum multiflorum'
        ,'Solanum multiinterruptum'
        ,'Solanum neocardenasii'
        ,'Solanum neorossii'
        ,'Solanum neovavilovii'
        ,'Solanum nigriviolaceum'
        ,'Solanum nubicola'
        ,'Solanum okadae'
        ,'Solanum olmosense'
        ,'Solanum ortegae'
        ,'Solanum oxycarpum'
        ,'Solanum palustre'
        ,'Solanum paucissectum'
        ,'Solanum pillahuatense'
        ,'Solanum piurae'
        ,'Solanum platacanthum'
        ,'Solanum polhillii'
        ,'Solanum polyadenium'
        ,'Solanum raphanifolium'
        ,'Solanum rhomboideilanceolatum'
        ,'Solanum richardii'
        ,'Solanum rubetorum'
        ,'Solanum ruvu'
        ,'Solanum salasianum'
        ,'Solanum schenckii'
        ,'Solanum setaceum'
        ,'Solanum sisymbriifolium'
        ,'Solanum sodomeodes'
        ,'Solanum sogarandinum'
        ,'Solanum stipitatostellatum'
        ,'Solanum stoloniferum'
        ,'Solanum supinum'
        ,'Solanum taitense'
        ,'Solanum tarnii'
        ,'Solanum taulisense'
        ,'Solanum tomentosum'
        ,'Solanum torreanum'
        ,'Solanum torvum'
        ,'Solanum umtuma'
        ,'Solanum usambarense'
        ,'Solanum usaramense'
        ,'Solanum venturii'
        ,'Solanum vernei'
        ,'Solanum verrucosum'
        ,'Solanum vespertilio'
        ,'Solanum viarum'
        ,'Solanum vicinum'
        ,'Solanum violaceimarmoratum'
        ,'Solanum violaceum'
        ,'Solanum zanzibarense'
        ,'Sorghum angustum'
        ,'Sorghum bicolor subsp. drummondii'
        ,'Sorghum bicolor subsp. verticilliflorum'
        ,'Sorghum ecarinatum'
        ,'Sorghum exstans'
        ,'Sorghum halepense'
        ,'Sorghum interjectum'
        ,'Sorghum intrans'
        ,'Sorghum laxiflorum'
        ,'Sorghum macrospermum'
        ,'Sorghum matarankense'
        ,'Sorghum nitidum'
        ,'Sorghum propinquum'
        ,'Sorghum purpureosericeum'
        ,'Sorghum stipoideum'
        ,'Sorghum timorense'
        ,'Sorghum versicolor'
        ,'Tornabenea annua'
        ,'Tornabenea insularis'
        ,'Tornabenea tenuissima'
        ,'Triticum aestivum subsp. tibeticum'
        ,'Triticum aestivum subsp. yunnanense'
        ,'Triticum boeoticum'
        ,'Triticum monococcum'
        ,'Triticum monococcum subsp. aegilopoides'
        ,'Triticum timopheevii'
        ,'Triticum timopheevii subsp. armeniacum'
        ,'Triticum turgidum subsp. dicoccoides'
        ,'Triticum turgidum subsp. paleocolchicum'
        ,'Triticum urartu'
        ,'Vicia barbazitae'
        ,'Vicia faba subsp. faba'
        ,'Vicia faba subsp. paucijuga'
        ,'Vicia grandiflora'
        ,'Vicia pyrenaica'
        ,'Vicia qatmensis'
        ,'Vicia sativa subsp. amphicarpa'
        ,'Vicia sativa subsp. devia'
        ,'Vicia sativa subsp. incisa'
        ,'Vicia sativa subsp. macrocarpa'
        ,'Vicia sativa subsp. nigra'
        ,'Vigna hosei'
        ,'Vigna keraudrenii'
        ,'Vigna monantha'
        ,'Vigna schlecteri'
        ,'Vigna subterranea var. spontanea'
        ,'Vigna unguiculata subsp. aduensis'
        ,'Vigna unguiculata subsp. alba'
        ,'Vigna unguiculata subsp. baoulensis'
        ,'Vigna unguiculata subsp. burundiensis'
        ,'Vigna unguiculata subsp. dekindtiana'
        ,'Vigna unguiculata subsp. letouzeyi'
        ,'Vigna unguiculata subsp. pawekiae'
        ,'Vigna unguiculata subsp. pubescens'
        ,'Vigna unguiculata subsp. stenophylla'
        ,'Vigna unguiculata subsp. tenuis'
        ,'Vigna unguiculata subsp. unguiculata var. spontanea'";

        switch ($search_type) {
            case 0: //Busqueda teniendo en cuenta $term, no se van a evaluar entonces lo que haya en los genus prioritarios ni taxa prioritarios
                $query = "SELECT  s . *
                            FROM species AS s
                            JOIN distribution AS d ON s.Taxon_ID = d.Taxon_ID
                            JOIN countries AS c ON d.Country = c.Code
                            WHERE d.Taxon_ID
                            IN 
                            (SELECT s.Taxon_ID FROM species AS s";
                if ($concept_type != "" && $concept_type != "empty") {
                    $query .= " INNER JOIN concepts AS co ON s.Taxon_ID = co.Taxon_ID";
                }
                $query .= " WHERE ";
                if ($term != "") {
                    $query .= "s.Scientific_Name like '" . $term . "%'";
                }
                if ($concept_type != "" && $concept_type != "empty") {
                    if ($term != "") {
                        $query .= " and";
                    }
                    $query .= " co.Concept_Type = '$concept_type'";
                    if ($concept_levels) {
                        $query .= " and co.Concept_Level in (";
                        foreach ($concept_levels as $level) {
                            $query .= "'$level',";
                        }
                        $query .= ")";
                        $query = str_replace(",)", ")", $query);
                    }
                }
                if ($term != "") {
                    /* La misma busqueda en el campo Common_Name */
                    $query .= " or s.Common_Name like '" . $term . "%'";
                    if ($concept_type != "" && $concept_type != "empty") {
                        $query .= " and co.Concept_Type = '$concept_type'";
                        if ($concept_levels) {
                            $query .= " and co.Concept_Level in (";
                            foreach ($concept_levels as $level) {
                                $query .= "'$level',";
                            }
                            $query .= ")";
                            $query = str_replace(",)", ")", $query);
                        }
                    }
                }
                if ($term != "") {
                    $query .= " or s.Family like '" . $term . "%'";
                    if ($concept_type != "" && $concept_type != "empty") {
                        $query .= " and co.Concept_Type = '$concept_type'";
                        if ($concept_levels) {
                            $query .= " and co.Concept_Level in (";
                            foreach ($concept_levels as $level) {
                                $query .= "'$level',";
                            }
                            $query .= ")";
                            $query = str_replace(",)", ")", $query);
                        }
                    }
                }
                $query .= ")";
                $query = str_replace("WHERE )", " )", $query);

                if ($countries) { // Si hay paises por los cuales restringir la busqueda
                    $query .= " and c.Name in (";
                    foreach ($countries as $country) {
                        $query .= "'$country',";
                    }
                    $query .= ")";
                    $query = str_replace(",)", ")", $query);
                }
                if ($regions) {
                    $query .= " and c.Region in (";
                    foreach ($regions as $region) {
                        $query .= "'$region',";
                    }
                    $query .= ")";
                    $query = str_replace(",)", ")", $query);
                }
                $query .= " GROUP BY s.Genus, s.Species, s.Species_Author, s.Subsp, s.Subsp_Author, s.Var, s.Var_Author";
                break;
            case 1:$query = "SELECT  s . *
                            FROM species AS s
                            JOIN distribution AS d ON s.Taxon_ID = d.Taxon_ID ";
                //if ($countries) {
                $query .= "JOIN countries AS c ON d.Country = c.Code";
                //}
                $query .= " WHERE d.Taxon_ID
                            IN 
                            (SELECT s.Taxon_ID FROM species AS s";
                if ($concept_type != "" && $concept_type != "empty") {
                    $query .= " INNER JOIN concepts AS co ON s.Taxon_ID = co.Taxon_ID";
                }
                $query .= " WHERE Genus in (";
                foreach ($priority_genera as $genus) {
                    $query .= "'$genus',";
                }
                $query .= ")";
                $query = str_replace(",)", ")", $query);
                if ($concept_type != "" && $concept_type != "empty") {
                    /*if ($term != "") {
                        $query .= " and";
                    }**/
                    $query .= " and co.Concept_Type = '$concept_type'";
                    if ($concept_levels) {
                        $query .= " and co.Concept_Level in (";
                        foreach ($concept_levels as $level) {
                            $query .= "'$level',";
                        }
                        $query .= ")";
                        $query = str_replace(",)", ")", $query);
                    }
                }
                $query .= ")";
                if ($countries) { // Si hay paises por los cuales restringir la busqueda
                    $query .= " and c.Name in (";
                    foreach ($countries as $country) {
                        $query .= "'$country',";
                    }
                    $query .= ")";
                    $query = str_replace(",)", ")", $query);
                }
                if ($regions) {
                    $query .= " and c.Region in (";
                    foreach ($regions as $region) {
                        $query .= "'$region',";
                    }
                    $query .= ")";
                    $query = str_replace(",)", ")", $query);
                }
                $query .= " GROUP BY s.Genus, s.Species, s.Species_Author, s.Subsp, s.Subsp_Author, s.Var, s.Var_Author";
                break;
            case 2:$query = "SELECT s . *
                            FROM species AS s
                            JOIN distribution AS d ON s.Taxon_ID = d.Taxon_ID ";
                //if ($countries) {
                $query .= "JOIN countries AS c ON d.Country = c.Code";
                //}
                $query .= " WHERE d.Taxon_ID
                            IN 
                            (SELECT s.Taxon_ID FROM species AS s";
                if ($concept_type != "" && $concept_type != "empty") {
                    $query .= " INNER JOIN concepts AS co ON s.Taxon_ID = co.Taxon_ID";
                }
                $query .= " WHERE Scientific_Name in ($priority_taxa)";
                $query = str_replace(",)", ")", $query);
                if ($concept_type != "" && $concept_type != "empty") {
                    /*if ($term != "") {
                        $query .= " and";
                    }*/
                    $query .= " and co.Concept_Type = '$concept_type'";
                    if ($concept_levels) {
                        $query .= " and co.Concept_Level in (";
                        foreach ($concept_levels as $level) {
                            $query .= "'$level',";
                        }
                        $query .= ")";
                        $query = str_replace(",)", ")", $query);
                    }
                }
                $query .= ")";
                if ($countries) { // Si hay paises por los cuales restringir la busqueda
                    $query .= " and c.Name in (";
                    foreach ($countries as $country) {
                        $query .= "'$country',";
                    }
                    $query .= ")";
                    $query = str_replace(",)", ")", $query);
                }
                if ($regions) {
                    $query .= " and c.Region in (";
                    foreach ($regions as $region) {
                        $query .= "'$region',";
                    }
                    $query .= ")";
                    $query = str_replace(",)", ")", $query);
                }
                $query .= " GROUP BY s.Genus, s.Species, s.Species_Author, s.Subsp, s.Subsp_Author, s.Var, s.Var_Author";
                break;
            case 3:$query = "SELECT s.* FROM species AS s";
                if ($concept_type != "" && $concept_type != "empty") {
                    $query .= " INNER JOIN concepts AS co ON s.Taxon_ID = co.Taxon_ID";
                }
                $query .= " WHERE s.Scientific_Name like '" . $term . "%'";
                if ($concept_type != "" && $concept_type != "empty") {
                    if ($term != "") {
                        $query .= " and";
                    }
                    $query .= " and co.Concept_Type = '$concept_type'";
                    if ($concept_levels) {
                        $query .= " and co.Concept_Level in (";
                        foreach ($concept_levels as $level) {
                            $query .= "'$level', ";
                        }
                        $query .= ")";
                        $query = str_replace(", )", ")", $query);
                    }
                }
                if ($term != "") {
                    /* La misma busqueda en el campo Common_Name */
                    $query .= " or s.Common_Name like '" . $term . "%'";
                    if ($concept_type != "" && $concept_type != "empty") {
                        $query .= " and co.Concept_Type = '$concept_type'";
                        if ($concept_levels) {
                            $query .= " and co.Concept_Level in (";
                            foreach ($concept_levels as $level) {
                                $query .= "'$level', ";
                            }
                            $query .= ")";
                            $query = str_replace(", )", ")", $query);
                        }
                    }
                }

                if ($term != "") {
                    $query .= " or s.Family like '" . $term . "%'";
                    if ($concept_type != "" && $concept_type != "empty") {
                        $query .= " and co.Concept_Type = '$concept_type'";
                        if ($concept_levels) {
                            $query .= " and co.Concept_Level in (";
                            foreach ($concept_levels as $level) {
                                $query .= "'$level',";
                            }
                            $query .= ")";
                            $query = str_replace(",)", ")", $query);
                        }
                    }
                }
                $query .= " GROUP BY s.Genus, s.Species, s.Species_Author, s.Subsp, s.Subsp_Author, s.Var, s.Var_Author";
                break;
        }
        $query .= " order by s.Genus, s.Species, s.Species_Author, s.Subsp, s.Subsp_Author, s.Var, s.Var_Author";
        //print($query);
        $result = $db->getAll($query);
        $taxa = array();
        foreach ($result as $r) {
            $taxon = new Taxon(trim($r['Taxon_ID']));
            $taxon->setCommonName(trim($r["Common_Name"]));
            $taxon->setGenus(trim($r["Genus"]));
            $taxon->setSpecie(trim($r["Species"]));
            $taxon->setSpecieAuthor(trim($r["Species_Author"]));
            $taxon->setSubspecie(trim($r["Subsp"]));
            $taxon->setSubspecieAuthor($r["Subsp_Author"]);
            $taxon->setVariety(trim($r["Var"]));
            $taxon->setVarietyAuthor(trim($r["Var_Author"]));
            $taxon->setForm(trim($r["Form"]));
            $taxon->setFormAuthor(trim($r["Form_Author"]));
            $taxon->setMainCrop($r["Main_Crop"]);
            // insert reference to the array.
            array_push($taxa, $taxon);
        }
        return $taxa;
    }

}

?>
