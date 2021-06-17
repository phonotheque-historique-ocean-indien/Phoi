<?php

ini_set('display_errors', 1);
error_reporting(E_ERROR);

require_once __CA_LIB_DIR__.'/Search/ObjectSearch.php';

class BottomDetailController extends ActionController
{
    // -------------------------------------------------------
    protected $opo_config;        // plugin configuration file
    protected $opa_list_of_lists; // list of lists
    protected $opa_listIdsFromIdno; // list of lists
    protected $opa_locale; // locale id
    private $opo_list;
    private $plugin_path;

    // -------------------------------------------------------
    // Constructor
    // -------------------------------------------------------

    public function __construct(&$po_request, &$po_response, $pa_view_paths = null)
    {
        parent::__construct($po_request, $po_response, $pa_view_paths);
        $this->plugin_path = __CA_APP_DIR__.'/plugins/Phoi';

        $this->opo_config = Configuration::load(__CA_APP_DIR__.'/plugins/Phoi/conf/phoi.conf');

        // Extracting theme name to properly handle views in distinct theme dirs
        $vs_theme_dir = explode('/', $po_request->getThemeDirectoryPath());
        $vs_theme = end($vs_theme_dir);
        $this->opa_view_paths[] = $this->plugin_path.'/themes/'.$vs_theme.'/views';
    }

    // -------------------------------------------------------
    // Functions to render views
    // -------------------------------------------------------

    public function Index()
    {
        $o_data = new Db();
        $qr_mostDisplayed = $o_data->query('
            SELECT ca_objects.object_id 
            FROM ca_objects LEFT JOIN ca_objects_x_object_representations ON ca_objects_x_object_representations.object_id=ca_objects.object_id
            WHERE deleted=0 AND access=1 AND type_id IN (26, 27, 849, 251) AND representation_id is not null GROUP BY ca_objects.object_id  ORDER BY view_count, rand() DESC LIMIT 12
        ');
        $this->view->setVar('mostDisplayed', $qr_mostDisplayed);
        $qr_random = $o_data->query('
            SELECT ca_objects.object_id 
            FROM ca_objects LEFT JOIN ca_objects_x_object_representations ON ca_objects_x_object_representations.object_id=ca_objects.object_id
            WHERE deleted=0 AND access=1 AND type_id IN (26, 27, 849, 251) AND representation_id is not null GROUP BY ca_objects.object_id  ORDER BY rand() DESC LIMIT 12
        ');
        $this->view->setVar('random', $qr_random);
        echo $this->render('bottom_detail_html.php', true);

        exit();
    }
}
