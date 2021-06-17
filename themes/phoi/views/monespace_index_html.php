<?php
//$user = new ca_users(2000);
    $user = $this->getVar("user");
?>
<h2>Mon espace</h2>
<div class="columns">
    <div class="column">
        <img src="<?php print $user->getVar("_user_preferences")["user_profile_image"]; //("picture"); //getVars("ca_users.picture"); ?>" />
        <p>Date d'inscription :</p>
        <p>Score :</p>
        <p><a href="/index.php/Phoi/Users/Info/id/<?php print $user->get("user_id"); ?>"><button class="button is-primary">Voir la version publique</button></a></p>
    </div>
    <div class="column">
    <h3>Mon profil</h3>
    <div id="profileForm"></div>
    </div>
    <div class="column">
    <h3>Mes playlists</h3>
    <a class="tag is-primary" href="<?php print __CA_URL_ROOT__; ?>/index.php/Lightbox/setForm">Ajouter une playlist</a>
    
    </div>
</div>

<script>
$(document).ready(function() {
    $.get("<?php print __CA_URL_ROOT__; ?>/index.php/LoginReg/profileForm", function( data ) {
        $("#profileForm" ).html( data );
    });
});
</script>
<style>
h3 {
    font-weight:bold;
}
a.tag {

}
a.tag:hover {
    text-decoration: none;
}

</style>
