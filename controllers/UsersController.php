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
    
    function ValidateEmail() {
        $token = $this->request->getParameter("token", pString);
        $user_id = $this->request->getParameter("user_id", pString);

        $vt_user = new ca_users($user_id);
        if($token == $vt_user->get("confirmation_key")) {
            $vt_user->setMode(ACCESS_WRITE);
            $vt_user->set("active", 1);
            $vt_user->update();
            $this->notification->addNotification(_t("Votre compte est désormais activé, vous pouvez vous connecter."), __NOTIFICATION_TYPE_INFO__);
            $this->redirect("/index.php/LoginReg/loginForm");
        } else {
            $this->redirect("/");
        }
    }

    function editPassword() {
        $current_user = $this->getRequest()->getUser();
        $this->view->setVar("current_user", $current_user);
        $this->view->setVar("user_id", $current_user->getPrimaryKey());
        $this->render('edit_password_html.php');
    }

    function savePassword() {
        $password = $this->request->getParameter("password", pString);
        $confirmation = $this->request->getParameter("confirmation", pString);
        if($password != $confirmation) {
            $this->notification->addNotification("Password & confirmation don't match.", 0);
            $this->redirect("/index.php/Phoi/Users/editPassword");
        } else {
            $current_user = $this->getRequest()->getUser();
            $current_user->setMode(ACCESS_WRITE);
            $current_user->set('password', $password);
            $current_user->update();
            if($current_user->numErrors()) {
                var_dump($current_user->getErrors());
                die();
            }
            $this->notification->addNotification("Password has been updated.", 2);
            $this->redirect("/index.php/Phoi/MonEspace/Index");
        }
       
    }
    
    public function becameContributor(){
        $current_user = $this->getRequest()->getUser();
        $this->view->setVar("user", $current_user);
        $this->render("become_contributor_html.php");
    }

    public function sendContributorProposal(){
        $current_user = $this->getRequest()->getUser();
        $motivation = $this->getRequest()->getParameter("motivation", pString);

        $message = "<div style='font-family: \"Roboto\", sans-serif; color:black;'><h1 style='font-weight: 100;font-size: 2.2em;padding-top: 1em; padding-bottom: 0.2em;'>Candidature</h1> <p>L'utilisateur ".$current_user->getName()." demande à devenir un contributeur.</p> <h2 style='font-weight: 100;font-size: 1.2em;padding-top: 1em; padding-bottom: 0.2em;'> Motivation </h2> <p>" .$motivation."</p>
        <a style='cursor: pointer;color: #fff;
        background-color: #198754;
        border-color: #198754;display: inline-block;
        font-weight: 400;
        line-height: 1.5;
        text-align: center;
        text-decoration: none;
        vertical-align: middle;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        border-radius: 0.25rem;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;' href='https://www.phoi.io/index.php/Phoi/Users/AcceptContributor/id/".$current_user->getUserID()."'> Valider la candidature </a> <a style='cursor: pointer;color: #fff;
        background-color: #dc3545;
        border-color: #dc3545;display: inline-block;
        font-weight: 400;
        line-height: 1.5;
        text-align: center;
        text-decoration: none;
        vertical-align: middle;
        cursor: pointer;
        -webkit-user-select: none;
        -moz-user-select: none;
        user-select: none;
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        border-radius: 0.25rem;
        transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;' href='https://www.phoi.io/index.php/Phoi/Users/DenyContributor/id/".$current_user->getUserID()."'>Refuser la demande</a>
        </div>";


        caSendmail("md@ideesculture.com", "gm@ideesculture.com", "Demande pour devenir Contributeur", "", $message);
        $this->redirect("/index.php/Phoi/MonEspace/Index");

    }

    public function AcceptContributor(){
        $user_id = $this->getRequest()->getParameter("id", pString);
        $user = new ca_users($user_id);

        $user->addToGroups("Nouveau contributeur");

        $message = "<div style='font-family: \"Roboto\", sans-serif;'><h1 style='font-weight: 100;font-size: 2.2em;padding-top: 1em; padding-bottom: 0.2em;'>Félicitation !</h1> <p>Votre demande pour devenir contributeur a été accepté ! Vous pouvez maintenant proposer des modifications sur les fiches en cliquant sur le crayon au survol de la valeur à modifier.</p></div>";
        caSendmail($user->get("email"), "noreply@phoi.io", "Demande pour devenir Contributeur", "", $message);

        $this->view->setVar("accepted", true);
        $this->render("contributor_confirmation_html.php");


        
    }

    public function DenyContributor(){
        $user_id = $this->getRequest()->getParameter("id", pString);
        $user = new ca_users($user_id);
        $user->setMode(ACCESS_WRITE);
        $temp = $user->getVar("_user_preferences");
        $temp["user_profile_refus_contri"] = date("d/m/Y");
        $user->setVar("_user_preferences",$temp);
        $user->update();

        $message = "<div style='font-family: \"Roboto\", sans-serif;'><h1 style='font-weight: 100;font-size: 2.2em;padding-top: 1em; padding-bottom: 0.2em;'>Désolé</h1> <p>Votre demande pour devenir contributeur a été refusé !</p></div>";
        caSendmail($user->get("email"), "noreply@phoi.io", "Demande pour devenir Contributeur", "", $message);

        $this->view->setVar("accepted", false);
        $this->render("contributor_confirmation_html.php");


    }

    public function changeAvatar()
    {
        if (!empty($_FILES)) {
            $user = $this->getRequest()->getUser();
            $temp = $user->getVar("_user_preferences");
            $folder = __CA_BASE_DIR__ . "/upload/user_image/" . $user->getName();
            @mkdir($folder, 0777, true);
            $ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
            $targetDir = $folder . "/user_image." . $ext;

            if (move_uploaded_file($_FILES['file']['tmp_name'], $targetDir)) {
                $temp["user_profile_image"] = $targetDir;
                $user->setVar("_user_preferences", $temp);
                $user->update();
            }
            //$img = file_get_contents($_FILES['file']['tmp_name']);
            //$data = base64_encode($img);

        }
    }
}

function extractUserGroups($group) {
    return $group["name"];
}


?>
