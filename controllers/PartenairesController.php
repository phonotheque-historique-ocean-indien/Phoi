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
                $description = "...";
                $facet_id = 1016;
                break;
            case "maurice":
                $description = "…";
                $facet_id = 1064;
                break;
        }
        $this->view->setVar("pays", $pays);
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
