<?php
$modifications = $this->getVar("modifications");
$modifications_filenames = $this->getVar("modifications_filenames");
$contributions = $this->getVar("contributions");
$medias = $this->getVar("medias");

?>
<h2><?php _p("Voter pour les contributions"); ?></h2>
<table id="log_list">
        <thead>
            <tr>
                <th><?php _p("Concerne"); ?></th>
                <th><?php _p("Date"); ?></th>
                <th><?php _p("Proposée par"); ?></th>
                <th><?php _p("Type"); ?></th>
                <th><?php _p("Score"); ?></th>
                <th></th>
            </tr>
        </thead>
<?php
foreach($modifications as $key=>$modification):
    //var_dump($modification);die();
    //https://dev.phoi.io/index.php/Contribuer/Do/Moderate/contribution/1600082182.json
?>
    <tr>
        <td><?php
	        $type = ( $modification["_table"] == "ca_entities" ? "entities" : "objects");
            print "<a href='/index.php/Phoi/Vote/Contribution/id/".$modification["filename"]."'>".$modification["_type"]." : ".$modification["title"]."</a>"
            ?></td>

        <td><?= date('d/m/Y H:i:s', $modification["_timecode"]); ?></td>
        <td><?php
            $vt_user = new ca_users($modification["_user_id"]);
            print $vt_user->get("fname")." ".$vt_user->get("lname");

        ?></td>
        <td><?php _p("Modification"); ?></td>
        <td><?php print round(rand(0,100)); ?>%</td>
        <td>
        <?php print "<a href='/index.php/Phoi/Vote/Contribution/id/".$modification["filename"]."'>"; ?>
            <span class="tag is-primary">je vote</span></span>
        <?php "</a>"; ?>
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
<style>
    #log_list .tags:not(:last-child){
        margin-bottom:0;
    }
    #log_list .tags.has-addons .tag {
        width:30%;
    }
    </style>