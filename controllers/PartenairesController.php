<?php

ini_set("display_errors", 1);
error_reporting(E_ERROR);
require_once(__CA_MODELS_DIR__.'/ca_site_pages.php');
require_once(__CA_LIB_DIR__.'/Search/ObjectSearch.php');
require_once(__CA_LIB_DIR__.'/Browse/EntityBrowse.php');
require_once(__CA_LIB_DIR__.'/Browse/ObjectRepresentationBrowse.php');

class PartenairesController extends ActionController
{
    # -------------------------------------------------------
    protected $opo_config;        // plugin configuration file
    protected $opa_list_of_lists; // list of lists
    protected $opa_listIdsFromIdno; // list of lists
    protected $opa_locale; // locale id
    private $opo_list;
    private $plugin_path;

    # -------------------------------------------------------
    # Constructor
    # -------------------------------------------------------

    public function __construct(&$po_request, &$po_response, $pa_view_paths = null)
    {
        parent::__construct($po_request, $po_response, $pa_view_paths);
		$this->plugin_path = __CA_APP_DIR__ . '/plugins/Phoi';

        $this->opo_config = Configuration::load(__CA_APP_DIR__ . '/plugins/Phoi/conf/phoi.conf');

		// Extracting theme name to properly handle views in distinct theme dirs
        $vs_theme_dir = explode("/", $po_request->getThemeDirectoryPath());
        $vs_theme = end($vs_theme_dir);
        $this->opa_view_paths[] = $this->plugin_path."/themes/".$vs_theme."/views";
    }

    # -------------------------------------------------------
    # Functions to render views
    # -------------------------------------------------------

    public function Carte() {
        $this->render('map_html.php');
    }

    public function GetLinks() {
        $pays = $this->request->getParameter("pays", pString);
        switch ($pays) {
            case "reunion":
                $description = "La Réunion, l’île intense est une terre de contrastes et de diversité. Ce joyau de l’océan Indien s’affiche sur ses 2.512 km² comme l’un des territoires, le plus unique, des départements et régions d’outre-mer. Située à 9.345 km, soit à 11h de vol de Paris, en plein hémisphère Sud, La Réunion allie l’exotisme d’une destination lointaine à la sécurité d’une région européenne.
Terre de métissage ethnique remarquable, La Réunion est composée d’une population originaire des quatre coins du monde (Afrique, Asie, Europe, Inde, Madagascar) qui invite dans la douce chaleur du soleil à vivre des aventures sans modération à travers ses multiples expériences passionnantes et insolites.
La Réunion est envoûtante, multi-ethnique, authentique, sa culture se conjugue au pluriel avec la découverte de ses cases créoles colorées, ses jardins aux mille essences, ses temples hindous, ses églises, ses pagodes chinoises, ses mosquées, ses demeures coloniales, ses monuments historiques, son architecture créole, ses musées, ses festivals de musique et ses fêtes traditionnelles…";
                $facet_id = 1286;
                break;
            case "seychelles":
                $description = "Les Seychelles forment un archipel de 115 îles (dont une artificielle), situé dans l'ouest de l'océan Indien et rattaché au continent africain. Toutes ces îles sont regroupées en un État dont la capitale est la ville de Victoria sur l'île principale de Mahé.";
                $facet_id = 1016;
                break;
            case "maurice":
                $description = "Maurice (en anglais : Mauritius), en forme longue la république de Maurice (en anglais : Republic of Mauritius), est un État insulaire de l'océan Indien à 868 kilomètres à l'est de Madagascar et 172 kilomètres à l'est-nord-est de La Réunion. Le pays inclut l'île principale de Maurice, mais aussi l'île Rodrigues à 560 kilomètres à l'est de l'île principale. Les îles plus lointaines d'Agaléga et de Saint-Brandon font partie du territoire national. Les îles Maurice et Rodrigues font partie de l'archipel des Mascareignes, avec l'île de La Réunion qui est elle un département d'outre-mer français. La capitale et plus grande ville est Port-Louis.";
                $facet_id = 1064;
                break;
            case "rodrigues":
                $description = "Rodrigues est la plus petite des îles principales de l’archipel des Mascareignes. Elle est surnommée la Cendrillon des Mascareignes. D'origine volcanique, l'île se situe à 583 km à l’est de Maurice, presque isolée au centre de l’océan Indien. D’une superficie de 109 km2, elle mesure 18 km de long sur 8 de large et présente la particularité d’avoir un lagon d’une surface deux fois supérieure à celle des terres émergées. Elle fait partie de la république de Maurice et jouit d'un statut d'autonomie depuis le 12 octobre 2002.";
                $facet_id = 1064;
                break;
            case "mayotte":
                $description = "Mayotte (en mahorais : Maoré), officiellement nommée département de Mayotte, est à la fois une région insulaire française et un département de France d'outre-mer qui sont administrés dans le cadre d’une collectivité territoriale unique4 dirigée par le conseil départemental de Mayotte. Sur le plan géographique, il s’agit d’un ensemble d’îles situé dans le canal du Mozambique et dans l’océan Indien. Mayotte est constituée de deux îles principales, Grande-Terre et Petite-Terre, et de plusieurs autres petites îles dont Mtsamboro, Mbouzi et Bandrélé. Son code départemental officiel est « 976 »5. L’ancien chef-lieu Dzaoudzi est situé en Petite-Terre. Le chef-lieu actuel de jure est Mamoudzou6 sur Grand-Terre, le siège du Conseil départemental et les services administratifs de la préfecture sont tous deux sur Grande-Terre à Mamoudzou, ville la plus peuplée de Mayotte. Du fait de son statut de région française, Mayotte est également une région ultrapériphérique de l’Union européenne.";
                $facet_id = 1064;
                break;
            case "comores":
                $description = "Les Comores, en forme longue l'union des Comores (en comorien : Komori et Udzima wa Komori, en arabe : جزر القمر (Djuzur al qamar) et الاتّحاد القُمُريّ (al-Ittiḥād al-Qumuriyy)), est une république fédérale d'Afrique australe située dans le nord du canal du Mozambique, un espace maritime de l'océan Indien. Le pays a pour capitale Moroni, pour langues officielles le comorien (shikomor), parlé par 96,9 % de la population5, le français et l'arabe6,7 et pour monnaie le franc comorien. L'Union des Comores est membre de la Ligue arabe, de l'Organisation de la coopération islamique, de l'Organisation internationale de la francophonie et de l'Assemblée parlementaire de la francophonie. Colonie française à partir de 1892, les Comores obtiennent leur indépendance le 6 juillet 1975.";
                $facet_id = 1064;
                break;
            case "zanzibar":
                $description = "Zanzibar, en swahili Funguvisiwa ya Zanzibar, est un archipel de l'océan Indien situé en face des côtes tanzaniennes, formé de trois îles principales (Unguja, Pemba et Mafia) et de plusieurs autres petites îles. Les îles d'Unguja et de Pemba forment depuis plusieurs siècles une entité tour à tour indépendante (sultanat de Zanzibar et État de Zanzibar), colonisée par le Royaume-Uni (protectorat de Zanzibar) ou incorporée à la Tanzanie (Gouvernement révolutionnaire de Zanzibar). L'île de Mafia a quant à elle toujours été intégrée à la Tanzanie continentale (Afrique orientale allemande, territoire sous mandat de Tanganyika et État de Tanganyika).";
                $facet_id = 1064;
                break;
            case "madagascar":
                $description = "Madagascar, en forme longue la République de Madagascar (en malgache : Madagasikara, ou Repoblikan'i Madagasikara), est un État insulaire situé dans l'océan Indien et géographiquement rattaché au continent africain, dont il est séparé par le canal du Mozambique. C’est la cinquième plus grande île du monde après l'Australie, le Groenland, la Nouvelle-Guinée et Bornéo. Longue de 1 580 km et large de 580 km, Madagascar couvre une superficie de 587 000 km2. Sa capitale est Antananarivo6 et le pays a pour monnaie l'ariary. Ses habitants, les Malgaches, sont un peuple austronésien parlant une langue malayo-polynésienne : le malgache. Le pays est entouré par d'autres îles et archipels dont l'île Maurice, les Seychelles, Mayotte, les Comores et La Réunion.";
                $facet_id = 1064;
                break;

        }
        $this->view->setVar("pays", $pays);
        $this->view->setVar("country_code", $pays);
        $this->view->setVar("description", $description);

        $o_search = new ObjectSearch();
        $o_results = $o_search->search('ca_objects.grands_types:"collectages" AND ca_objects.pays_facet:"'.$pays.'"');
        $nb_objects = $o_results->numHits();
        $this->view->setVar("nb_collectages", $nb_objects);

        $o_search = new ObjectSearch();
        $o_results = $o_search->search('ca_objects.grands_types:"autres" AND ca_objects.pays_facet:"'.$pays.'"');
        $nb_autres = $o_results->numHits();
        $this->view->setVar("nb_autres", $nb_autres);

        $o_browse = new ObjectSearch();
        $o_results = $o_search->search('ca_objects.grands_types:"oeuvres" AND ca_objects.pays_facet:"'.$pays.'"');
        $nb_oeuvres = $o_results->numHits();
        $this->view->setVar("nb_oeuvres", $nb_oeuvres);

        $o_browse = new ObjectSearch();
        $o_results = $o_search->search('ca_objects.grands_types:"phonogrammes" AND ca_objects.pays_facet:"'.$pays.'"');
        $nb_phonogrammes = $o_results->numHits();
        $this->view->setVar("nb_phonogrammes", $nb_phonogrammes);

        $e_browse = new EntityBrowse();
        $this->view->setVar("pays", $pays);
        //$e_browse->addCriteria("pays_facet", $facet_id);
        $e_browse->addCriteria("type_facet", 89);
        $e_browse->execute();
        $nb_entities = $e_browse->numResults();
        $this->view->setVar("nb_inds", $nb_entities);

        $e_browse = new EntityBrowse();
        $this->view->setVar("pays", $pays);
        //$e_browse->addCriteria("pays_facet", $facet_id);
        $e_browse->addCriteria("type_facet", 220);
        $e_browse->execute();
        $nb_groups = $e_browse->numResults();
        $this->view->setVar("nb_groups", $nb_groups);

        $or_browse = new ObjectRepresentationBrowse();
        $this->view->setVar("pays", $pays);
        //$or_browse->addCriteria("pays_facet", $facet_id);
        $or_browse->execute();
        $nb_representations = $or_browse->numResults();
        $this->view->setVar("nb_representations", $nb_representations);

        $content = $this->render('map_subview_html.php');
        print $content;
        exit();
    }

}
?>
