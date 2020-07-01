<?php
$qr_results=$this->getVar("results");
$nb_results=$this->getVar("nb_results");
print $nb_results." résultats";
?>

<div id="search-results-tiles">
<?php
$template = '
<div class="tile is-parent">
  <div class="tile">
    <p>
    <img class="list-icon" src="^ca_object_representations.media.icon.url"> <l>^ca_objects.preferred_labels.name</l><br/>
    <unit restrictToRelationshipTypes="interprete" relativeTo="ca_entities">^ca_entities.preferred_labels.displayname</unit>, !!DATE!!
    </p>
  </div>
</div>
';
$i=0;
while($qr_results->nextHit()) {
    if($i % 3 ==0 ) {
        print '<div class="tile is-ancestor">';
    }
    $vt_object = new ca_objects($qr_results->get("ca_objects.object_id"));
    $record = $vt_object->getWithTemplate(
             $template, array("checkAccess"=>[0=>1])
    );
    if($vt_object->get("ca_objects.date")) {
        $date = $vt_object->get("ca_objects.date");
        if(strlen($date) > 8) {
            $date = "";
        }
    } else {
        $date = "";
    }
    $record = str_replace("!!DATE!!", $date, $record);
    print $record;
    if($i % 3 == 2 ) {
        print '</div>';
    }
    $i++;
}
?>
</div>
