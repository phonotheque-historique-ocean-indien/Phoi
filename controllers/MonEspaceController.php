<?php

ini_set("display_errors", 1);
error_reporting(E_ERROR);
require_once(__CA_MODELS_DIR__.'/ca_site_pages.php');
require_once(__CA_MODELS_DIR__.'/ca_sets.php');


require_once(__CA_LIB_DIR__.'/Search/ObjectSearch.php');
require_once(__CA_LIB_DIR__.'/Browse/EntityBrowse.php');
require_once(__CA_LIB_DIR__.'/Browse/ObjectRepresentationBrowse.php');

class MonEspaceController extends ActionController
{
    # -------------------------------------------------------
    protected $opo_config;        // plugin configuration file
    protected $opa_list_of_lists; // list of lists
    protected $opa_listIdsFromIdno; // list of lists
    protected $opa_locale; // locale id
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

    public function Index() {
        $user = $this->getRequest()->getUser();

        $t_sets = new ca_sets();
        $sets = $t_sets->getSetsForUser(array("table" => "ca_objects", "user_id" => $user->getUserID(), "parents_only" => true));
        foreach ($sets as $set){
            $t_set = new ca_sets($set["set_id"]);
            $allSets[$set["set_id"]] = $t_set->getWithTemplate("^ca_sets.preferred_labels");
        }
        $this->view->setVar("sets", $allSets);
        $this->view->setVar("user", $user);
        $this->render('monespace_index_html.php');
    }
}