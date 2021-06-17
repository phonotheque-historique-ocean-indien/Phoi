<?php
$t_object=new ca_objects($this->getVar("id"));
$vt_user=$this->getVar("user");
$repr = [];
$vb_isadmin = $vt_user->hasGroupRole("admin");
?>

<?php
$representations = $t_object->getRepresentations(['preview170', 'large', 'pagewatermark'], null, ["checkAccess"=>1]);
//print $representation["tags"]["preview170"];
foreach ($representations as $key => $representation) {
    if(!$representation["access"] && !$vb_isadmin) continue;
    //if(!$representation['urls']['pagewatermark']) continue;
    $repr[] = ["name"=>basename($representation['urls']['pagewatermark']), "path"=>dirname($representation['urls']['pagewatermark']), "size"=>filesize($representation['paths']['pagewatermark']), "label"=>$representation["label"], "primary"=>$representation["is_primary"]];
    //"<img style=\"height:140px\" style=\"cursor: zoom-in\"  src='".$representation['urls']['pagewatermark']."' >";
}

print json_encode($repr);
?>

