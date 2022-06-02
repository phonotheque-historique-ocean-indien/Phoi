<?php
$user = $this->getVar("user");
$user_id = $user["id"];
$vt_user = new ca_users($user["id"]);
?>

<script>
	window.parent.history.pushState('', "<?= $browser_tab_label ?>", 'https://www.phoi.io/index.php/Phoi/Users/Info/id/<?= $user_id ?>');
	window.parent.document.title = "PHOI - profil public utilisateur";
</script>

<?php
$image = $vt_user->getVar("_user_preferences")["user_profile_image"];
if(!is_file($image)) {
    $image = "/user_icon.png";
} else {
    $image = str_replace(__CA_BASE_DIR__, "", $image);
}

$current_user = $this->getVar("current_user");
?>
<h2><?php _p($user["name"]); ?></h2>

<div class="columns">
    <div class="column">
        <div class="avatar">
            <img src="<?= $image ?>" style="max-width:100%;height:auto">
        </div>
    <p><b>Type d'utilisateur</b> : </p>    
    <p><b>Date d'inscription</b> : <?= $user["date"]; ?></p>
    <p>
    <button onclick="goBack()" class="button is-light">Retour</button>

<script>
function goBack() {
  window.history.back();
}
</script>

<?php
    if($current_user->get("user_id") == $vt_user->get("user_id")) {
?>
    <a href="/index.php/Phoi/MonEspace/Index"><button class="button is-primary">Mon espace</button></a>
<?php } ?>
    </p>
    </div>
    <div class="column">
    <h3>Profil</h3>
    <div id="profileForm" style="min-width: 460px;">
        <p>Prénom : <?= $user["fname"]; ?></p>
        <p>Nom : <?= $user["lname"]; ?></p>
    </div>
    </div>
    <div class="column">
    <h3>Ses playlists</h3>
    </div>
</div>


<p><img src="" style="width:120px;height:auto;"></p>

<style>
.columns h3 {
    font-weight: bold;
}

.avatar {
    width: 225px;
    position: relative;
    margin-bottom: 15px;
}
</style>