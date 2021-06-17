<?php
$user = $this->getVar("user");
$vt_user = new ca_users($user["id"]);
$image = $vt_user->getVar("_user_preferences")["user_profile_image"];
$current_user = $this->getVar("current_user");
?>
<h2><?php _p($user["name"]); ?></h2>

<div class="columns">
    <div class="column">
        <img src="<?= $image ?>">
        <p>Confiance : <?= $user["confiance"]; ?></p>
    <p>Date d'inscription : <?= $user["date"]; ?></p>
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
</style>