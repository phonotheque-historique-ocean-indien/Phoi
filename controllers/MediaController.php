<?php

ini_set("display_errors", 1);
error_reporting(E_ERROR);
require_once(__CA_MODELS_DIR__.'/ca_site_pages.php');
require_once(__CA_LIB_DIR__.'/Search/ObjectSearch.php');
require_once(__CA_LIB_DIR__.'/Browse/EntityBrowse.php');
require_once(__CA_LIB_DIR__.'/Browse/ObjectRepresentationBrowse.php');

class MediaController extends ActionController
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

    public function UploadJson() {
        $id = $this->getRequest()->getParameter("id", pInteger);
        $t_object = new ca_objects($id);
        $this->view->setVar("id", $id);
        $this->view->setVar("user", $this->getRequest()->getUser());
        print $this->render('media_index_html.php');
        exit();
    }


    public function UploadPost() {
        $id = $this->getRequest()->getParameter("id", pInteger);
        $t_object = new ca_objects($id);
        $this->view->setVar("id", $id);
        $this->view->setVar("user", $this->getRequest()->getUser());
        $file = reset($_FILES);
        $t_object->setMode(ACCESS_WRITE);
        $id = $t_object->addRepresentation($file["tmp_name"], 140, 6, 1, 1, 1, null, array('preferred_labels'=>$file["name"], 'original_filename' => $file["name"]));
        $t_object->update();
        var_dump($id);

        //print $this->render('media_index_html.php');
        exit();
    }

    public function DetailInfos() {
        $id = $this->getRequest()->getParameter("id", pInteger);
        $name = $this->getRequest()->getParameter("name", pString);
        $t_repr = new ca_object_representations($id);
        $this->view->setVar("id", $id);
        $media = $t_repr->getRepresentations(["original"]);
        $media = reset($media);
        $dimensions = $media["dimensions"]["original"];
        $this->view->setVar("dimensions", $dimensions);
        $this->view->setVar("filename", $name);
        print $this->render('media_detailinfos_html.php');
        exit();
    }
    
    // Source : https://stackoverflow.com/a/30008824
    private function table_cell($data) {
        $return = "<table border='1'>";
        foreach ($data as $key => $value) {
            $return .= "<tr><td>$key</td><td>";
            if (is_array($value)) {
                $return .= $this->table_cell($value);
            } else {
                $return .= $value;
            }
            $return .= "</td><tr>";
        }
        $return .= "</tr></table>";
        return($return);
    }

    public function DetailAudioInfos() {
        $id = $this->getRequest()->getParameter("id", pInteger);
        $name = $this->getRequest()->getParameter("name", pString);
        $t_repr = new ca_object_representations($id);
        $this->view->setVar("id", $id);
        $media = $t_repr->getRepresentations(["original"]);
        //$media = reset($media);
        $data = $media[0]["info"]["original"]["PROPERTIES"]["audio"];
        unset($data["streams"]);
        $table = $this->table_cell($data);
        echo $table;
        die();
        $dimensions = $media["dimensions"]["original"];
        $this->view->setVar("dimensions", $dimensions);
        $this->view->setVar("filename", $name);
        print $this->render('media_detailinfos_html.php');
        exit();
    }    

    public function DownloadOriginal() {
        $id = $this->getRequest()->getParameter("representation_id", pInteger);
        $name = $this->getRequest()->getParameter("name", pString);
        $vt_rep = new ca_object_representations($id);
        $representations = $vt_rep->getRepresentations(["original"]);
        $this->view->setVar("url", $representations[0]["paths"]["original"]);
        $this->view->setVar("name", $name);
        print $this->render('media_downloadoriginal.php');
        exit();
    }

    public function SetPrimary() {
        error_reporting(E_ERROR);
        ini_set("display_errors",true);
        $object_id = $this->getRequest()->getParameter("object_id", pInteger);
        $pn_representation_id = $this->getRequest()->getParameter("representation_id", pInteger);

        $vt_object = new ca_objects($object_id);
        $vt_object->editRepresentation($pn_representation_id, null, null, null, $pn_access=null, $pb_is_primary=1);

        $this->redirect(caNavUrl($this->request, '', 'Detail', "objects/".$object_id));
    }

    public function SetAccessible() {
        $object_id = $this->getRequest()->getParameter("object_id", pInteger);
        $pn_representation_id = $this->getRequest()->getParameter("representation_id", pInteger);
        $vt_repr = new ca_object_representations($pn_representation_id);
        $vt_repr->setMode(ACCESS_WRITE);
        $vt_repr->set("access", ($vt_repr->get("access") > 0 ? 0 : 1));
        $vt_repr->update();
        $this->redirect(caNavUrl($this->request, '', 'Detail', "objects/".$object_id));
    }

}