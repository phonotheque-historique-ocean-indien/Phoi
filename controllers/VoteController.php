<?php

ini_set("display_errors", 1);
error_reporting(E_ERROR);
require_once(__CA_MODELS_DIR__.'/ca_site_pages.php');
require_once(__CA_LIB_DIR__.'/Search/ObjectSearch.php');
require_once(__CA_LIB_DIR__.'/Browse/EntityBrowse.php');
require_once(__CA_LIB_DIR__.'/Browse/ObjectRepresentationBrowse.php');

class VoteController extends ActionController
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
        $this->render('vote_list_html.php');
        //exit();
    }

    public function Json() {
        // Exiting if anonymous contributions are not allowed
        if(!$this->request->getUserID() && ($this->opo_config->get("allow_anonymous_contributions", pInteger) == 0)) {
            $this->render('anonymous_index_html.php');
            return false;
        }
        foreach($this->getRequest()->getUser()->getUserGroups() as $group) {
            if($group["code"] == $this->opo_config->get("moderator_user_groups")) {
                $this->view->setVar("is_moderator", true);
            } else {
                $this->view->setVar("is_moderator", false);
            }
        }
        $id= $this->request->getParameter("id", pInteger);
        $this->view->setVar("template", $this->opo_config->get("template"));
        $this->view->setVar("mappings", $this->opo_config->get("mappings"));

        $contribution_filenames = scandir(__CA_BASE_DIR__."/app/plugins/Contribuer/temp/contributions");
        $contribution_filenames = array_diff($contribution_filenames, array('..', '.'));
        $contributions = [];
        foreach($contribution_filenames as $filename) {
            $contrib_file_content = file_get_contents(__CA_BASE_DIR__."/app/plugins/Contribuer/temp/contributions/".$filename);
            if(trim($contrib_file_content) == "[]") continue;
            $contrib = json_decode($contrib_file_content, TRUE);

            $user_id = $contrib["_user_id"];
            $timecode = $contrib["_timecode"];
            $vt_user = new ca_users($user_id);
            $user_name = $vt_user->get("ca_users.user_name");
            $contributions[] = ["type_id" => $contrib["type_id"], "_type" => $contrib["_type"], "title"=> "<b>".$contrib["title"]."</b><br/>".date('d/m/Y H:i', $timecode)." : ".$user_name, "filename"=>$filename];
        }
        $this->view->setVar("contributions", $contributions);

        $modifications_filenames = scandir(__CA_BASE_DIR__."/app/plugins/Contribuer/temp/modifications");
        $modifications_filenames = array_diff($modifications_filenames, array('..', '.'));
        $modifications = [];
        foreach($modifications_filenames as $filename) {
            $contrib_file_content = file_get_contents(__CA_BASE_DIR__."/app/plugins/Contribuer/temp/modifications/".$filename);
            if(trim($contrib_file_content) == "[]") continue;
            $contrib = json_decode($contrib_file_content, TRUE);
            if(!$contrib["_user_id"]) $contrib["_user_id"] = 1;
            $contrib["filename"] = $filename;
            $timecode = $filename;
            $vt_user = new ca_users($user_id);
            $modifications[] = $contrib;
        }
        $this->view->setVar("modifications", $modifications);
        

        $medias_filenames = scandir(__CA_BASE_DIR__."/app/plugins/Contribuer/temp/medias");
        $medias_filenames = array_diff($medias_filenames, array('..', '.'));
        $medias = [];
        foreach($medias_filenames as $filename) {
            // Ignore non JSON files
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if($ext != "json") continue;

            $contrib_file_content = file_get_contents(__CA_BASE_DIR__."/app/plugins/Contribuer/temp/medias/".$filename);
            if(trim($contrib_file_content) == "[]") continue;
            $contrib = json_decode($contrib_file_content, TRUE);
            //var_dump($contrib);die();
            $user_id = ($contrib["_user_id"] ? $contrib["_user_id"] : 1);
            $timecode = $filename;
            $vt_user = new ca_users($user_id);
            $user_name = $vt_user->get("ca_users.user_name");
            $medias[] = ["id" => $contrib["id"], "image" => $contrib["image"], "filename"=>$filename];
        }
        $this->view->setVar("medias", $medias);

        $this->render('vote_json.php');
    }

    function Contribution() {
        if(!$this->request->getUserID()) {
            $this->render('jevote_anonymous_html.php');
        } else {
            $filename = $this->request->getParameter("id", pString);
            $this->view->setVar("contribution_id", $filename);
            $contrib_file_content = file_get_contents(__CA_BASE_DIR__."/app/plugins/Contribuer/temp/modifications/".$filename);
            //if(trim($contrib_file_content) == "[]") continue;
            $contrib = json_decode($contrib_file_content, TRUE);
            $this->view->setVar("contribution", $contrib);

            $this->notification->addNotification(_t("Notification."), __NOTIFICATION_TYPE_INFO__);
            $this->render('jevote_html.php');
        }
    }
    function VoterPour() {
        $contribution_id = $this->request->getParameter("id", pString);
        $this->view->setVar("contribution_id", $this->request->getParameter("id", pString));
        // Get amount of vote
        $amount=0;
        foreach($this->getRequest()->getUser()->getUserGroups() as $group) {
            // Ignore non numeric group
            $amount += $group["code"];
        }

        // Register the vote
        // Check if vote can be accepted
        $o_data = new Db();
        $result = ["result"=>"ok", "message"=>"Your vote has been accepted, thanks.", "refresh"=>true];
        try {
            $qr_result = $o_data->query("insert into phoi_votes (user_id, contribution_id, vote) values (".$this->request->getUserID().",\"".$contribution_id."\", ".$amount.");");
        } catch (Exception $e) {
            $result = ["result"=>"ko", "message"=>"Votre vote a déjà été enregistré pour cette contribution.", "refresh"=>false];
        }
        print json_encode($result);
        exit();
    }
    function VoterContre() {
        $contribution_id = $this->request->getParameter("id", pString);
        $this->view->setVar("contribution_id", $this->request->getParameter("id", pString));
        // Get amount of vote
        $amount=0;
        foreach($this->getRequest()->getUser()->getUserGroups() as $group) {
            // Ignore non numeric group
            $amount += $group["code"];
        }

        // Register the vote
        // Check if vote can be accepted
        $o_data = new Db();
        $result = ["result"=>"ok", "message"=>"Your vote has been accepted, thanks.", "refresh"=>true];
        try {
            $qr_result = $o_data->query("insert into phoi_votes (user_id, contribution_id, vote) values (".$this->request->getUserID().",\"".$contribution_id."\", ".$amount.");");
        } catch (Exception $e) {
            $result = ["result"=>"ko", "message"=>"Votre vote a déjà été enregistré pour cette contribution.", "refresh"=>false];
        }
        print json_encode($result);
        exit();
    }
}

?>
