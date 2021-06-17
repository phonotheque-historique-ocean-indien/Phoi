<?php
$qr_results = $this->getVar('results');
$nb_results = $this->getVar('nb_results');
$page = (int) $this->getVar('page');
//echo $nb_results.' résultats';
$num_pages = floor($nb_results/24);
?>

<div id="search-results-tiles">
<?php
$template = "
<div class=\"tile is-parent\">
  <div class=\"tile\">
    <l>
    <p style=\"display:block !important;\"><img class=\"list-icon\" src=\"^ca_object_representations.media.large.url\" style=\"display:block\"></p>
    <p class=\"nom_objet\">^ca_objects.preferred_labels.name</p>
    </l>
  </div>
</div>
";
$start = $page * 24;
$end = $start+23;
$i=-1;
while ($qr_results->nextHit() && ($i<$end)) {
  ++$i;
  //print $i."<br/>";
  if($i<$start) continue;
    if (0 == $i % 6) {
        echo '<div class="tile is-ancestor">';
    }
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
    if (5 == $i % 6) {
        echo '</div>';
    }
}
/*for ($j = ($i % 6); $j < 6; ++$j) {
    echo '<div class="tile is-parent">
    <div class="tile blank">
      <p style="display:block !important;"></p>
    </div>
  </div>';
}*/
print "<span style='float:right'>";
for($i=1;$i<=$num_pages;$i++) {
  print "<span onClick=\"getResultsList('tiles', ".$i.");\">".$i."</span> ";
}
print "<span>";
?>
</div>

<style>
	#search-results-tiles > .tile > .tile > .tile{
		box-shadow: rgba(0,0,0,0.3) 0px 0px 4px;
		background-color: white;
	}
    #search-results-tiles > .tile > .tile > .tile.blank {
		box-shadow: none;
		background-color: transparent;
	}
	#search-results-tiles .list-icon {
		height:auto;
		float:none;
	}
</style>