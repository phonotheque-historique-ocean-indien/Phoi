<?php
//error_reporting(E_ALL);
//ini_set("display_errors", true);
//require_once('setup.php');
require_once 'phonex.cls.php';
require_once(__CA_MODELS_DIR__."/ca_objects.php");
require_once(__CA_MODELS_DIR__."/ca_entities.php");


function sortByLevel($a, $b) {
    return $b['level'] - $a['level'];
}

function phoneticSearch($table_num = 50, $vs_search, $type_id=null) {
    $oPhonex = new phonex;
    //var_dump($sPhonex);
    
    $results = [];
    
    $o_data = new Db();
    $words = explode(" ", $vs_search);
    foreach($words as $word) {
        $sPhonex = $oPhonex -> build ($word);
        //print $word." ".$sPhonex."\n";
        $query = "SELECT table_num, table_row, word
        FROM phoi_phonex_words LEFT JOIN phoi_phonex_word_index ON phoi_phonex_word_index.word_id=phoi_phonex_words.id 
        WHERE ROUND(phonex, 8) = ROUND(".$sPhonex.",8) AND table_num = ".$table_num;
        if($type_id) $query .= " AND type_id=".$type_id;
        $qr_result = $o_data->query($query);
    
        while($qr_result->nextRow()) {
            // On ne calcule le score que si présent
            $level = levenshtein (strtolower ($word), strtolower ($qr_result->get("word")) );
            //print $qr_result->get("table_row")." ".$qr_result->get("word")." ".$level."\n";
            $o_id = $qr_result->get("table_row");
            $vt_object = new ca_objects($o_id);
            $date = $vt_object->getWithTemplate('<unit relativeTo="ca_objects">^ca_objects.format</unit>');
            $test = preg_match_all('/[0-9][0-9][0-9][0-9]/', $date, $matches);
            if ($test) {
                $date = $matches[0][0];
            }
            $titre =$vt_object->getWithTemplate('<img class="list-icon" src="!!ICONURL!!"/> <l><ifdef code="ca_objects.preferred_labels.name">^ca_objects.preferred_labels.name</ifdef><ifnotdef code="ca_objects.preferred_labels.name">[sans titre]</ifnotdef></l>');
            $vt_rep_url = $vt_object->getWithTemplate('^ca_object_representations.media.preview170.url');
            $titre = str_replace("!!ICONURL!!", $vt_rep_url, $titre);

            $interprete = $vt_object->getWithTemplate('<unit relativeTo="ca_objects"><unit relativeTo="ca_objects.children" delimiter="|"><unit restrictToRelationshipTypes="interprete" relativeTo="ca_entities">^ca_entities.preferred_labels.displayname</unit></unit></unit>');
            $interprete = explode('|', $interprete);
            $interprete = reset($interprete);
            if(!isset($results[$o_id])) {
                $results[$o_id] = [
                    "object_id"=>$o_id, 
                    "words" => $qr_result->get("word"), 
                    "level"=>(100-$level), 
                    "label"=>$vt_object->get("ca_objects.preferred_labels"),
                    "Titre"=>$titre,
                    "Type de support"=>$vt_object->getWithTemplate('<unit relativeTo="ca_objects">^ca_objects.format</unit>'),
                    "Date"=>$date,
                    "Année"=>date("Y",strtotime($date)),
                    "Interprète" => $interprete,
                    "Label"=>$vt_object->getWithTemplate('<unit relativeTo="ca_objects"><unit restrictToRelationshipTypes="label" relativeTo="ca_entities">^ca_entities.preferred_labels.displayname</unit></unit>'),
                    "Producteur"=>$vt_object->getWithTemplate('<unit relativeTo="ca_objects"><unit restrictToRelationshipTypes="producteur" relativeTo="ca_entities">^ca_entities.preferred_labels.displayname</unit></unit>'),
                    "Auteur"=> $vt_object->getWithTemplate('<unit relativeTo="ca_objects"><unit restrictToRelationshipTypes="auteur,auteur texte" relativeTo="ca_entities">^ca_entities.preferred_labels.displayname</unit></unit>'),
                    "Compositeur"=> $vt_object->getWithTemplate('<unit relativeTo="ca_objects"><unit restrictToRelationshipTypes="compositeur" relativeTo="ca_entities">^ca_entities.preferred_labels.displayname</unit></unit>'),
                    "Éditeur"=> $vt_object->getWithTemplate('<unit relativeTo="ca_objects"><unit restrictToRelationshipTypes="editeur" relativeTo="ca_entities">^ca_entities.preferred_labels.displayname</unit></unit>'),
                    "Partition"=>$vt_object->getWithTemplate('<unit relativeTo="ca_objects.related" restrictToTypes="Partition"><l>^ca_objects.preferred_labels</l></unit>'),
                    "Type"=>$vt_object->get('ca_objects.type_id'),
                    "Pays"=>$vt_object->getWithTemplate("^ca_objects.pays_liste"),
                    "Edition"=>$vt_object->getWithTemplate("^ca_objects.lieux")
                ];
            } else {
                $results[$o_id]["words"] .= " ".$qr_result->get("word");
                $results[$o_id]["level"] += (100 - $level);
            }
        }
    
    }
    usort($results, 'sortByLevel');
    $return = [];
    foreach($results as $key=>$result) {
        $return [] =  [
            "Titre"=>$result["Titre"],
            "Type de support"=>$result["Type de support"],
            "Date"=>$result["Date"],
            "Interprète"=>$result["Interprète"],
            "Label"=>$result["Label"],
            "Producteur"=>$result["Producteur"],
            "Auteur"=> $result["Auteur"],
            "Compositeur"=> $result["Compositeur"],
            "Éditeur"=> $result["Éditeur"],
            "Partition"=>$result["Partition"],
            "Type"=>$result["Type"],
            "Pays"=>$result["Pays"],
            "Année"=>$result["Année"],
            "Edition"=>$result["Edition"],
        ];
    }
    return '{ 
        "recordsTotal": '.sizeof($results).',
        "recordsFiltered": '.sizeof($results).',
        "data": '.json_encode($return, JSON_PRETTY_PRINT).'}';
}


/*

{ 
    "draw": 4,
    "recordsTotal": 3,
    "recordsFiltered": 3,
    "data": [
    {
        "Titre": "<img class=\"list-icon\" src=\"https:\/\/www.phoi.io\/media\/collectiveaccess\/images\/1\/0\/6\/72706_ca_object_representations_media_10626_preview170.jpg\" \/> <a href=\"\/index.php\/Detail\/objects\/6405\">Alain Peters, parabol\u00e8r<\/a>",
        "Type de support": "",
        "Date": "",
        "Interpr\u00e8te": "",
        "Label": "",
        "Producteur": "",
        "Type": "878",
        "Pays": ""
    },
    {
        "Titre": "<img class=\"list-icon\" src=\"https:\/\/www.phoi.io\/media\/collectiveaccess\/images\/1\/0\/6\/38209_ca_object_representations_media_10639_preview170.jpg\" \/> <a href=\"\/index.php\/Detail\/objects\/6418\">Alain Peters, vavangu\u00e8r<\/a>",
        "Type de support": "",
        "Date": "",
        "Interpr\u00e8te": "",
        "Label": "",
        "Producteur": "",
        "Type": "878",
        "Pays": ""
    },
    {
        "Titre": "<img class=\"list-icon\" src=\"https:\/\/www.phoi.io\/media\/collectiveaccess\/images\/2\/1\/1152_ca_object_representations_media_2113_preview170.jpg\" \/> <a href=\"\/index.php\/Detail\/objects\/1523\">\"Chante Albany !\" La R\u00e9union\n\nPar Jean-Michel SALMACIS\nHerv\u00e9 IMARE - Pierre VIDOT\nAlain Peters\nAccompagn\u00e9s par Ren\u00e9 Lacaille,\nBernard Brancard, etc.<\/a>",
        "Type de support": "Cassette",
        "Date": "1978",
        "Interpr\u00e8te": "",
        "Label": "ADER",
        "Producteur": "",
        "Type": "878",
        "Pays": "La R\u00e9union"
    }
]}