<?php

ini_set("display_errors", 1);
error_reporting(E_ERROR);
require_once(__CA_LIB_DIR__."/Search/ObjectSearch.php");

class LivresController extends ActionController
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

    public function Search() {
        $country = $this->request->getParameter("country", pString);
        $this->view->setVar("country", $country);
        $this->render('livres_search_html.php');
    }

    public function Results() {
        $country = $this->request->getParameter("country", pString);
        $keywords = $this->request->getParameter("keywords", pString);
        $tag = $this->request->getParameter("tag", pString);
        $display = $this->request->getParameter("display", pString);
        $pays = $this->request->getParameter("pays", pString);
        $date = $this->request->getParameter("date", pString);
        $date_fin = $this->request->getParameter("date_fin", pString);
        $producteur = $this->request->getParameter("producteur", pString);
        $groupes = $this->request->getParameter("groupes", pString);
        $labels = $this->request->getParameter("labels", pString);
        $titre = $this->request->getParameter("titre", pString);
        $num_catalogue = $this->request->getParameter("num_catalogue", pString);
        $album_avec_audio = $this->request->getParameter("album_avec_audio", pString);
        $album_avec_image = $this->request->getParameter("album_avec_image", pString);

        $order = $_GET["order"];
        if(!isset($_GET["order"][0])) {
	        $order="";
	    } else {
		    $order=reset($order);
	    }
        
        if($display != "tiles") $display = "list";
        $this->view->setVar("country", $country);
        $vs_search = 'ca_objects.type_id:"213" AND ca_objects.deleted:0';/* AND ca_objects.pays_facet:"'.$country.'"';*/
        if($pays && ($pays !="-")) $vs_search .= " AND ca_objects.pays_liste:".$pays;
        if($date && $date_fin) $vs_search .= " AND ca_objects.date:\"".str_replace("_","/", $date)." -\"";
        if($date && !$date_fin) $vs_search .= " AND ca_objects.date:\"".str_replace("_","/", $date)."\"";
        if($date_fin) $vs_search .= " AND ca_objects.date_fin:\" - ".str_replace("_","/", $date_fin)."\"";
        if($producteur) $vs_search .= " AND ca_entities.preferred_labels.displayname/producteur:".$producteur;
        if($groupes) $vs_search .= " AND (ca_entities.preferred_labels.displayname/interprete:\"".$groupes."\" OR ca_objects.indexation_interprete:\"".$groupes."\" )";
        if($labels) $vs_search .= " AND ca_entities.preferred_labels.displayname/label:".$labels;
        if($titre) $vs_search .= " AND ca_objects.preferred_labels:\"".$titre."\"";
        if($num_catalogue) $vs_search .= " AND ca_objects.num_edition:\"".$num_catalogue."\"";
        if($album_avec_audio) $vs_search .= " AND ca_objects.album_avec_audio:\"1\"";
        if($album_avec_image) $vs_search .= " AND ca_objects.album_avec_image:\"1\"";
        if($tag) $vs_search .= " AND ca_objects.tag:\"".$tag."\"";

        if($keywords) {
            $vs_search .= " AND ".$keywords;
        }       
        $options = ["sort"=>"ca_objects.preferred_labels", "sortDirection"=>$order["dir"]];
        //$options = [];
        $vt_search = new ObjectSearch();
        $vt_search_result = $vt_search->search($vs_search, $options);
        $nb_results = $vt_search_result->numHits();
        $this->view->setVar("nb_results", $nb_results);
        $this->view->setVar("page", $this->request->getParameter("page", pInteger));
        $this->view->setVar("results", $vt_search_result);

        $this->render('livres_search_results_'.$display.'_html.php');
    }
    
    public function ResultsJson() {
        $country = $this->request->getParameter("country", pString);
        $keywords = $this->request->getParameter("keywords", pString);
        $tag = $this->request->getParameter("tag", pString);
        $display = $this->request->getParameter("display", pString);
        $pays = $this->request->getParameter("pays", pString);
        $date = $this->request->getParameter("date", pString);
        $date_fin = $this->request->getParameter("date_fin", pString);
        $producteur = $this->request->getParameter("producteur", pString);
        $groupes = $this->request->getParameter("groupes", pString);
        $auteur = $this->request->getParameter("auteur", pString);
        $editeur = $this->request->getParameter("editeur", pString);
        $titre = $this->request->getParameter("titre", pString);
        $num_catalogue = $this->request->getParameter("num_catalogue", pString);
        $album_avec_audio = $this->request->getParameter("album_avec_audio", pString);
        $album_avec_image = $this->request->getParameter("album_avec_image", pString);

        $order = $_GET["order"];
        if(!isset($_GET["order"][0])) {
	        $order="";
	    } else {
		    $order=reset($order);
	    }
        
        if($display != "tiles") $display = "list";
        $this->view->setVar("country", $country);
        $vs_search = 'ca_objects.type_id:"903" AND ca_objects.deleted:0';/* AND ca_objects.pays_facet:"'.$country.'"';*/
        if($pays && ($pays !="-")) $vs_search .= " AND ca_objects.pays_liste:".$pays;
        if($date && $date_fin) $vs_search .= " AND ca_objects.date:\"".str_replace("_","/", $date)." -\"";
        if($date && !$date_fin) $vs_search .= " AND ca_objects.date:\"".str_replace("_","/", $date)."\"";
        if($date_fin) $vs_search .= " AND ca_objects.date_fin:\" - ".str_replace("_","/", $date_fin)."\"";
        if($producteur) $vs_search .= " AND ca_entities.preferred_labels.displayname/producteur:".$producteur;
        if($groupes) $vs_search .= " AND (ca_entities.preferred_labels.displayname/interprete:\"".$groupes."\" OR ca_objects.indexation_interprete:\"".$groupes."\" )";
        if($auteur) $vs_search .= " AND ca_entities.preferred_labels.displayname/auteur:".$auteur;
        if($editeur) $vs_search .= " AND ca_entities.preferred_labels.displayname/editeur:".$editeur;
        if($titre) $vs_search .= " AND ca_objects.preferred_labels:\"".$titre."\"";
        if($num_catalogue) $vs_search .= " AND ca_objects.num_edition:\"".$num_catalogue."\"";
        if($album_avec_audio) $vs_search .= " AND ca_objects.album_avec_audio:\"1\"";
        if($album_avec_image) $vs_search .= " AND ca_objects.album_avec_image:\"1\"";
        if($tag) $vs_search .= " AND ca_objects.tag:\"".$tag."\"";

        if($keywords) {
            $vs_search .= " AND ".$keywords;
        }        

        //print $vs_search; die();
        $options = [];
        if($order["column"] == 0) {
            $options = ["sort"=>"ca_objects.preferred_labels", "sortDirection"=>$order["dir"]];
        }
        if($order["column"] == 1) {
            $options = ["sort"=>"ca_objects.date", "sortDirection"=>$order["dir"]];
        }
        if($order["column"] == 2) {
            $options = ["sort"=>"ca_entities.preferred_labels.surname", "sortDirection"=>$order["dir"]];
        }
        if($order["column"] == 3) {
            $options = ["sort"=>"ca_entities.preferred_labels.surname", "sortDirection"=>$order["dir"]];
        }
        if($order["column"] == 4) {
            $options = ["sort"=>"ca_objects.pays_liste", "sortDirection"=>$order["dir"]];
        }
        $vt_search = new ObjectSearch();
        $vt_search_result = $vt_search->search($vs_search, $options);
        $nb_results = $vt_search_result->numHits();
        $this->view->setVar("nb_results", $nb_results);
        $this->view->setVar("results", $vt_search_result);
        
        print $this->render('livres_search_results_'.$display.'_json.php', false);
        die();
    }

}
?>
