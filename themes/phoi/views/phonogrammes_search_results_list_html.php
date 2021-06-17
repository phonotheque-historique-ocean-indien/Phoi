<?php
    $qr_results = $this->getVar('results');
    $nb_results = $this->getVar('nb_results');
?>

<table class="table is-fullwidth" id="search-results-list">
<thead>
  <tr>
    <th>Titre</th>
    <th>Date</th>
    <th>Groupe</th>
    <th>Producteur</th>
  </tr>
</thead>
<tbody>
<?php
$template = '
<tr>
    <td><img class="list-icon" src="^ca_object_representations.media.icon.url"> <l>^ca_objects.preferred_labels.name</l></td>
    <td>!!DATE!!</td>
    <td><unit restrictToRelationshipTypes="interprete" relativeTo="ca_entities">^ca_entities.preferred_labels.displayname</unit></td>
    <td><unit restrictToRelationshipTypes="producteur" relativeTo="ca_entities">^ca_entities.preferred_labels.displayname</unit></td>
</tr>
';
$i = 0;
while ($qr_results->nextHit()) {
    $vt_object = new ca_objects($qr_results->get('ca_objects.object_id'));
    $record = $vt_object->getWithTemplate(
        $template,
        []
    );
    if ($vt_object->get('ca_objects.date')) {
        $date = $vt_object->get('ca_objects.date');
        if (strlen($date) > 8) {
            $date = '';
        }
    } else {
        $date = '';
    }
    $record = str_replace('!!DATE!!', $date, $record);
    echo $record;
    ++$i;
}
?>
</tbody>
</table>
