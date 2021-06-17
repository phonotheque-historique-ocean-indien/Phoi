<?php
$logs = $this->getVar("logs");
//$vt_user = new ca_users(1);
$vt_user = $this->request->getUser();
$roles = $vt_user->getUserGroups();

$is_admin = false;
$is_moderator = false;
foreach($roles as $role) {
	if($role["code"]=="moderator") {
		$is_moderator = true;}
	if($role["code"]=="admin") {
		$is_moderator = true;
		$is_admin = true; }
}
?>
<p style="text-align: right;padding-top:8px;">
    <span class="tag is-warning"><a href="/index.php/Phoi/Vote/List" style="color:black;font-weight:bold;">Voter</a></span>
	<?php if($is_admin) print '<span class="tag is-light">Administrateur</span>'; ?>
		<?php if($is_moderator) print '<span class="tag is-light">Modérateur</span>'; ?>
</p>
<div id="suggestions_list">

</div>

<div id="modifications_list">

</div>

<h2><?php _p("Modifications validées"); ?></h2>
<table id="log_list">
        <thead>
            <tr>
                <th><?php _p("Concerne"); ?></th>
                <th><?php _p("Date"); ?></th>
                <th><?php _p("Proposée par"); ?></th>
                <th><?php _p("Type"); ?></th>
            </tr>
        </thead>
<?php
foreach($logs as $log):
    $class = $log["class"];
    $vt_item = new $class($log["row_id"]);
    $type = ( $class == "ca_entities" ? "entities" : "objects");
	if(!$class.$vt_item->get($class.".preferred_labels")) {
		//continue;
	}
?>
    <tr>
        <td><?php
            print "<a href='/index.php/Detail/$type/".$log["row_id"]."'>".$vt_item->getTypeName()." : ".$vt_item->get($class.".preferred_labels")."</a>";
            ?></td>

        <td data-sort="<?= date('Y/m/d H:i:s', $log["log_datetime"]); ?>"><?= date('d/m/Y H:i:s', $log["log_datetime"]); ?></td>
        <td>
        <?php
            $vt_user = new ca_users($log["user_id"]);
        ?>
        <a href="https://dev.phoi.io/index.php/Phoi/Users/Info/id/<?= $vt_user->get("user_id") ?>"><?php
            print $vt_user->get("fname")." ".$vt_user->get("lname");
        ?></a></td>
        <td><?php
            switch($log["changetype"]) {
                case "I":
                    print "Ajout";
                    break;
                case "U":
                    print "Modification";
                    break;
                case "D":
                    print "Suppression";
                    break;
                default:
                    print $log["changetype"];
            }

            ?></td>
    </tr>
<?php endforeach; ?>
</table>

<link href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet" />
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready( function () {
        $('#log_list').DataTable({
            "language": {"url": "/datatables_french.json"},
            "info": false,
            "order": [[ 1, "desc" ]]
        });
        <?php if($is_moderator): ?>
        $.get("/index.php/Phoi/Moderation/Modifications", function(data) {
            $("#modifications_list").html(data);
        })
        $.get("/index.php/Contribuer/Do/Index", function(data) {
            $("#suggestions_list").html(data);
        })
        <?php endif; ?>

    } );
</script>
<div style="height:80px;"></div>
