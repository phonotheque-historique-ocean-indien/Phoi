<?php
//error_reporting(E_ALL);
//ini_set("display_errors", true);
//require_once('setup.php');
require_once 'phonex.cls.php';
require_once(__CA_MODELS_DIR__."/ca_objects.php");
require_once(__CA_MODELS_DIR__."/ca_entities.php");

// Adelaïde 0.62589470911465000000
// adelaïde 0.62448599386296
// adelaide 0.62448749495566
// adelaide 0.62589470911465000000
// 0.62448599386296
// adelaide 0.62448599386296
function sortByLevel($a, $b) {
    return $b['level'] - $a['level'];
}

function phoneticEntitiesSearch($table_num = 25, $vs_search, $type_id=null) {

    $oPhonex = new phonex;
    //var_dump($sPhonex);
    
    $results = [];
    
    $o_data = new Db();
    $words = explode(" ", $vs_search);
    foreach($words as $word) {
        $sPhonex = $oPhonex -> build ($word);
        //print $word." ".$sPhonex."\n";
        //die();
        $query = "SELECT table_num, table_row, word
        FROM phoi_phonex_words LEFT JOIN phoi_phonex_word_index ON phoi_phonex_word_index.word_id=phoi_phonex_words.id 
        WHERE ROUND(phonex, 8) = ROUND(".$sPhonex.",8) AND table_num = ".$table_num;
        if($type_id) $query .= " AND type_id=".$type_id;
        //var_dump($query);
        //die();
        $qr_result = $o_data->query($query);
    
        while($qr_result->nextRow()) {
            // On ne calcule le score que si présent
            $level = levenshtein (strtolower ($word), strtolower ($qr_result->get("word")) );
            //print $qr_result->get("table_row")." ".$qr_result->get("word")." ".$level."\n";
            $entity_id = $qr_result->get("table_row");
            $vt_entity = new ca_entities($entity_id);
            if(!isset($results[$entity_id])) {
                $results[$entity_id] = [
                    "entity_id"=>$entity_id, 
                    "words" => $qr_result->get("word"), 
                    "level"=>(100-$level), 
                    "entity_label"=>$vt_entity->get("ca_entities.preferred_labels"),
                    "Nom"=>$vt_entity->getWithTemplate("<l>^ca_entities.preferred_labels.surname</l>"),
                    "Prénom"=>$vt_entity->getWithTemplate("<l>^ca_entities.preferred_labels.forename</l>"),
                    "Date de début"=>$vt_entity->getWithTemplate("^ca_entities.date_debut"),
                    "Date de fin"=>$vt_entity->getWithTemplate("^ca_entities.date_fin"),
                    "Décès"=>"",
                    "Naissance"=>"",
                    "Pays"=>$vt_entity->getWithTemplate("^ca_entities.pays_liste")
                ];
            } else {
                $results[$entity_id]["words"] .= " ".$qr_result->get("word");
                $results[$entity_id]["level"] += (100 - $level);
            }
        }
    
    }
    usort($results, 'sortByLevel');

    $return = [];
    foreach($results as $key=>$result) {
        $return [] =  [
            "Nom"=>$result["Nom"],
            "Prénom"=>$result["Prénom"],
            "Naissance"=>"",
            "Décès"=>"",
            "Date de début"=>$result["Date de début"],
            "Date de fin"=>$result["Date de fin"],
            "Pays"=>$result["Pays"],
            "level"=>$result["level"]
        ];
    }
    return '{ 
        "recordsTotal": '.sizeof($results).',
        "recordsFiltered": '.sizeof($results).',
        "data": '.json_encode($return, JSON_PRETTY_PRINT).'}';
}



/*
{ 
    "draw": 6,
    "recordsTotal": 35,
    "recordsFiltered": 35,
    "data": [
    {
        "Nom": "<a href=\"\/index.php\/Detail\/entities\/40853\">Marie Jos\u00e9<\/a>",
        "Pr\u00e9nom": "",
        "Naissance": "",
        "D\u00e9c\u00e8s": "",
        "Pays": ""
    },
    {
        "Nom": "<a href=\"\/index.php\/Detail\/entities\/40863\">Roger Clency et Marie-Jos\u00e9e<\/a>",
        "Pr\u00e9nom": "",
        "Naissance": "",
        "D\u00e9c\u00e8s": "",
        "Pays": ""
    },
    {
        "Nom": "<a href=\"\/index.php\/Detail\/entities\/40886\">Marie-Jos\u00e9e Clency<\/a>",
        "Pr\u00e9nom": "",
        "Naissance": "",
        "D\u00e9c\u00e8s": "",
        "Pays": ""
    },
    {
        "Nom": "<a href=\"\/index.php\/Detail\/entities\/40999\">Hubert Rostaing<\/a>",
        "Pr\u00e9nom": "",
        "Naissance": "",
        "D\u00e9c\u00e8s": "",
        "Pays": ""
    },
    {
        "Nom": "<a href=\"\/index.php\/Detail\/entities\/41057\">Marie-H\u00e9l\u00e8ne Dormeuil<\/a>",
        "Pr\u00e9nom": "",
        "Naissance": "",
        "D\u00e9c\u00e8s": "",
        "Pays": ""
    },
    {
        "Nom": "<a href=\"\/index.php\/Detail\/entities\/41062\">Marie. jo<\/a>",
        "Pr\u00e9nom": "",
        "Naissance": "",
        "D\u00e9c\u00e8s": "",
        "Pays": ""
    },
    {
        "Nom": "<a href=\"\/index.php\/Detail\/entities\/41070\">Marie Armande Moutou<\/a>",
        "Pr\u00e9nom": "",
        "Naissance": "",
        "D\u00e9c\u00e8s": "",
        "Pays": ""
    },
    {
        "Nom": "<a href=\"\/index.php\/Detail\/entities\/41076\">Marie Jos\u00e9e<\/a>",
        "Pr\u00e9nom": "",
        "Naissance": "",
        "D\u00e9c\u00e8s": "",
        "Pays": ""
    },
    {
        "Nom": "<a href=\"\/index.php\/Detail\/entities\/41177\">Georges HUBERT<\/a>",
        "Pr\u00e9nom": "",
        "Naissance": "",
        "D\u00e9c\u00e8s": "",
        "Pays": ""
    },
    {
        "Nom": "<a href=\"\/index.php\/Detail\/entities\/41205\">Jos\u00e9 Charles<\/a>",
        "Pr\u00e9nom": "",
        "Naissance": "",
        "D\u00e9c\u00e8s": "",
        "Pays": ""
    }
]}