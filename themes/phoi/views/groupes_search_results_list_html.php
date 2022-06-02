<?php
    $qr_results = $this->getVar('results');
    $nb_results = $this->getVar('nb_results');
?>

<table class="table is-fullwidth" id="search-results-list">
<thead>
  <tr>
    <th>Nom</th>
    <th>Pays</th>
  </tr>
</thead>
<tbody>
<?php
$template = '
<tr>
    <td><l>^ca_entities.preferred_labels.displayname</l></td>
    <td>^ca_entities.pays_liste</td>
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
