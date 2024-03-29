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

$template1 = '<l>^ca_entities.preferred_labels.surname</l>';
$template2 = '^ca_entities.preferred_labels.forename';
$template3 = '';
$template4 = '';

//$template1 = '^ca_objects.preferred_labels.name';
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
    $vt_item = new ca_entities($qr_results->get('ca_entities.entity_id'), ['checkAccess' => [0 => 1]]);

    $record1 = 
    // $qr_results->get("object_id").
    $vt_item->getWithTemplate(
        $template1,
        ['checkAccess' => [0 => 1]]
    );
    $record2 = $vt_item->getWithTemplate(
        $template2,
        ['checkAccess' => [0 => 1]]
    );
    $record3 = $vt_item->getWithTemplate(
        $template3,
        ['checkAccess' => [0 => 1]]
    );
    $test = preg_match_all('/[0-9][0-9][0-9][0-9]/', $record3, $matches);
    if ($test) {
        $record3 = $matches[0][0];
    }
    $record4 = $vt_item->getWithTemplate(
        $template4,
        ['checkAccess' => [0 => 1]]
    );
    $test = preg_match_all('/[0-9][0-9][0-9][0-9]/', $record4, $matches);
    if ($test) {
        $record4 = $matches[0][0];
    }
    // Ignore blank titles on display
    $json_data[] = ['Nom' => $record1, "Prénom" => $record2, "Naissance" => "", "Décès" =>"", 'Pays' => $vt_item->getWithTemplate('^ca_entities.pays_liste')];
}

header('Content-Type: application/json');

echo '{ 
    "draw": '.$draw.',
    "recordsTotal": '.$nb_results.',
    "recordsFiltered": '.$nb_results.',
    "data": '.json_encode($json_data, JSON_PRETTY_PRINT).'}';
