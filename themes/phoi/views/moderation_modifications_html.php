<?php
$modifications = $this->getVar("modifications");
$contributions = $this->getVar("contributions");
$medias = $this->getVar("medias");

?>
<h2><?php _p("Modifications en attente de validation"); ?></h2>
<table id="log_list">
        <thead>
            <tr>
                <th><?php _p("Concerne"); ?></th>
                <th><?php _p("Date"); ?></th>
                <th><?php _p("Proposée par"); ?></th>
                <th><?php _p("Type"); ?></th>
                <th><?php _p("Statut"); ?></th>
            </tr>
        </thead>
<?php
foreach($modifications as $modification):
?>
    <tr>
        <td><?php
	        $type = ( $modification["_table"] == "ca_entities" ? "entities" : "objects");
            print "<a href='/index.php/Detail/$type/".$modification["_id"]."'>".$modification["_type"]." : ".$modification["title"]."</a>"
            ?></td>

        <td><?= date('d/m/Y H:i:s', $modification["_timecode"]); ?></td>
        <td><?php
            $vt_user = new ca_users($modification["_user_id"]);
            print "<a href='/index.php/Phoi/Users/Info/id/".$modification["_user_id"]."'>".$vt_user->get("fname")." ".$vt_user->get("lname")."</a>";

        ?></td>
        <td><?php _p("Modification"); ?></td>
        <td><?php _p("Non démarré"); ?></td>
    </tr>
<?php endforeach; ?>
</table>

<link href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet" />
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready( function () {
        $('#log_list').DataTable({
            "language": {"url": "/datatables_french.json"},
            "info": false
        });
    } );
</script>
<div style="height:80px;"></div>
