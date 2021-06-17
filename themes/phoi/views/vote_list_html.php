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
	<?php if($is_admin) print '<span class="tag is-light">Administrateur</span>'; ?>
		<?php if($is_moderator) print '<span class="tag is-light">Modérateur</span>'; ?>
</p>
<div id="suggestions_list">

</div>

<div id="modifications_list">

</div>

<link href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet" />
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready( function () {
        $('#log_list').DataTable({
            "language": {"url": "/datatables_french.json"},
            "info": false
        });
        $.get("/index.php/Phoi/Vote/Json", function(data) {
            $("#modifications_list").html(data);
        })

    } );
</script>
<div style="height:80px;"></div>
