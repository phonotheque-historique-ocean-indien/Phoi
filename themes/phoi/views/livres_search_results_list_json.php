<?php

$qr_results = $this->getVar('results');
$nb_results = $this->getVar('nb_results');
//print $nb_results." résultats";
$length = filter_var($_GET['length'], FILTER_VALIDATE_INT);
$force_start=0;
if($_GET['start'] == "NaN") {
    $force_start = $nb_results- ($nb_results % $length);
    if($force_start == $nb_results) $force_start = $nb_results - $length;
}
$start = filter_var($_GET['start'], FILTER_VALIDATE_INT);
$draw = filter_var($_GET['draw'], FILTER_VALIDATE_INT);
if (!$start) {
    $start = 0;
}
if($force_start) $start=$force_start;

$template1 = '<unit restrictToRelationshipTypes="auteur" relativeTo="ca_entities">^ca_entities.preferred_labels.displayname</unit>';
$template2 = '<l>^ca_objects.preferred_labels.name</l>';
//$template1 = '^ca_objects.preferred_labels.name';
$template3 = '^ca_objects.date';
$template4 = '^ca_objects.lieux <unit restrictToRelationshipTypes="editeur" relativeTo="ca_entities">: ^ca_entities.preferred_labels.displayname</unit>';
$template5 = '^ca_objects.pays_liste';
$i = 0;
$json_data = [];
while ($qr_results->nextHit()) {
    ++$i;
    if ($i < $start + 1) {
        continue;
    }
    if ($i > ($start + $length)) {
        break;
    }
    $vt_object = new ca_objects($qr_results->get('ca_objects.object_id'), ['checkAccess' => [0 => 1]]);
    // Ignore blank titles on display


    $rep_id = $vt_object->getPrimaryRepresentationID();
    if (!$rep_id) {
        $rep_ids = array_keys($vt_object->getRepresentationIDs());
        $rep_id = reset($rep_ids);
    }
    $vt_rep = new ca_object_representations($rep_id);
    $vt_rep_url = $vt_object->getWithTemplate('^ca_object_representations.media.preview170.url');

    $record1 = $vt_object->getWithTemplate(
        $template1,
        ['checkAccess' => [0 => 1]]
    );
    $record2 = $vt_object->getWithTemplate(
        $template2,
        ['checkAccess' => [0 => 1]]
    );

    $record3 = $vt_object->getWithTemplate(
        $template3,
        ['checkAccess' => [0 => 1]]
    );
    $record3 = explode('|', $record3);
    $record3 = reset($record3);
    $record4 = $vt_object->getWithTemplate(
        $template4,
        ['checkAccess' => [0 => 1]]
    );
    $record5 = $vt_object->getWithTemplate(
        $template5,
        ['checkAccess' => [0 => 1]]
    );

    $record1 = str_replace('!!ICONURL!!', $vt_rep_url, $record1);
    $record2 = str_replace('!!ICONURL!!', $vt_rep_url, $record2);
    $record3 = str_replace('!!ICONURL!!', $vt_rep_url, $record3);
    $record4 = str_replace('!!ICONURL!!', $vt_rep_url, $record4);
    $record5 = str_replace('!!ICONURL!!', $vt_rep_url, $record5);

    if ($vt_object->get('ca_objects.date')) {
        $date = $vt_object->get('ca_objects.date');
        if (strlen($date) > 8) {
            $date = '';
        }
    } else {
        $date = '';
    }
    $record2 = str_replace('!!DATE!!', $date, $record2);
    $json_data[] = ['Auteur' => $record1, 'Titre' => $record2, 'Année' => $record3, "Edition" => $record4, 'Type' => $vt_object->get('ca_objects.type_id'), 'Pays (thématique)' => $record5];
}

header('Content-Type: application/json');

echo '{ 
    "draw": '.$draw.',
    "recordsTotal": '.$nb_results.',
    "recordsFiltered": '.$nb_results.',
    "data": '.json_encode($json_data, JSON_PRETTY_PRINT).'}';
