<?php

ini_set("display_errors", 1);
error_reporting(E_ERROR);
require_once(__CA_MODELS_DIR__.'/ca_site_pages.php');
require_once(__CA_LIB_DIR__.'/Search/ObjectSearch.php');
require_once(__CA_LIB_DIR__.'/Browse/EntityBrowse.php');
require_once(__CA_LIB_DIR__.'/Browse/ObjectRepresentationBrowse.php');

class UsersController extends ActionController
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

    public function List() {
        $o_data = new Db();
        $qr_result = $o_data->query("
            SELECT * 
            FROM ca_users 
        ");
        $users = [];
        while($qr_result->nextRow()) {
            $vt_user = new ca_users($qr_result->get('user_id'));
            $groups = implode(", ", array_map('extractUserGroups', $vt_user->getUserGroups()));
            $users[] = [
                "id" => $qr_result->get('user_id'),
                "name" => $qr_result->get('user_name'),
                "fname" => $qr_result->get('fname'),
                "lname" => $qr_result->get('lname'),
                "groups" => $groups,
                "confiance" => $vt_user->getPreference('user_profile_confiance'),
                "date" => $vt_user->getPreference('user_profile_date_creation')
                ];
        }
        $this->view->setVar("users", $users);
        $this->render('users_list_html.php');
        //exit();
    }

    public function Info() {
        $id = $this->getRequest()->getParameter("id", pInteger);
        $user = $this->getRequest()->getParameter("user", pString);
        $o_data = new Db();
        if($id) {
        $qr_result = $o_data->query("
            SELECT * 
            FROM ca_users 
            WHERE user_id = ".$id);
        }  else {
            $qr_result = $o_data->query("
            SELECT * 
            FROM ca_users 
            WHERE user_name = '".$user."'");
        }           
        $users = [];
        while($qr_result->nextRow()) {
            $vt_user = new ca_users($qr_result->get('user_id'));
            $groups = implode(", ", array_map('extractUserGroups', $vt_user->getUserGroups()));
            $users[] = [
                "id" => $qr_result->get('user_id'),
                "name" => $qr_result->get('user_name'),
                "fname" => $qr_result->get('fname'),
                "lname" => $qr_result->get('lname'),
                "groups" => $groups,
                "confiance" => $vt_user->getPreference('user_profile_confiance'),
                "date" => $vt_user->getPreference('user_profile_date_creation')
                ];
        }
        $user = reset($users);
        $this->view->setVar("user", $user);
        $this->view->setVar("vt_user", new ca_users($user["user_id"]));
        $current_user = $this->getRequest()->getUser();
        $this->view->setVar("current_user", $current_user);
        $this->render('user_info_html.php');
    }
}

function extractUserGroups($group) {
    return $group["name"];
}
?>
