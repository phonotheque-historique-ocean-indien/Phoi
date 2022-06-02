<?php
//$user = new ca_users(2000);
$user = $this->getVar("user");
$sets = $this->getVar("sets");

$groups = array_keys($user->getUserGroups());
$isContributor = false;
$getReject = false;
$i = sizeof($groups);
while ($i >= 0) {
    $contributor_id = [9, 7, 5, 8, 4, 10];
    if (in_array($groups[$i], $contributor_id)) {
        $isContributor = true;
    }
    $i--;
}
if (!$isContributor && $user->getVar("_user_preferences")["user_profile_refus_contri"]) {
    $getReject = true;
}

$image = $user->getVar("_user_preferences")["user_profile_image"]; //("picture"); //getVars("ca_users.picture"); 
if (!$image) {
    $image = "/user_icon.png";
}
$image = str_replace(__CA_BASE_DIR__, "", $image);

?>
<h2>Mon espace</h2>
<div class="columns">
    <div class="column is-one-quarter">
        <div class="avatar">
            <span class="icon js-upload-file-btn">
                <i class="mdi mdi-pencil is-large"></i>
            </span>
            <img src="<?= $image ?>" />

            <!-- Modal Backdrop for all -->
            <div class="modal-backdrop" aria-hidden="true"></div>

            <!--Modal Create Folder -->
            <div class="modal js-upload-file">
                <div class="modal-dialog" role="dialog" aria-hidden="true">
                    <div class="modal-content">
                        <div class="modal-card-head">

                            <h3 class="modal-card-title">Envoyer une image de profil</h3>
                            <a aria-label="Close" class="close">
                                <i class="fa fa-times-circle-o" aria-hidden="true"></i>
                            </a>
                        </div>
                        <div class="modal-card-body">

                            <form id="dropzone" action="/" class="dropzone" method="post" enctype="multipart/form-data">
                                <div class="fallback">
                                    <input name="file" type="file" multiple />
                                </div>
                            </form>

                        </div>
                        <div class="modal-card-foot">
                            <button class="button is-sucess" onclick="saveAvatar()" type="button">Enregistrer</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <p>Date d'inscription :</p>
        <?php if ($isContributor) {
        ?>
            <p>Score : <?= (!$user->getVar("_user_preferences")["user_profile_confiance"])  ? 0  :  $user->getVar("_user_preferences")["user_profile_confiance"]; ?></p>
        <?php
        }
        ?>
    </div>
    <div class="column is-one-quarter">
        <h3>Mon profil</h3>
        <div id="profileForm">
            <span class="icon" onclick="editProfile()">
                <i class="mdi mdi-pencil is-large"></i>
            </span>
        </div>
    </div>
    <div class="column">
        <div style="display: flex; align-items:center;width: 100%;justify-content: space-evenly;">
            <h3>Mes playlists</h3>
            <div class="control has-icons-left">
                <div class="select">
                    <select>
                        <?php foreach ($sets as $set_id => $set_name) {
                            print "<option value='" . $set_id . "'>" . $set_name . "</option>";
                        } ?>
                    </select>
                </div>
                <div class="icon is-small is-left">
                    <i class="fas fa-music"></i>
                </div>

            </div>
            <a class="tag is-primary" href="<?php print __CA_URL_ROOT__; ?>/index.php/Lightbox/setForm">Ajouter une playlist</a>
        </div>
    </div>
</div>
<div style="padding-bottom:120px">
    <p>
        <a href="/index.php/Phoi/Users/Info/id/<?php print $user->get("user_id"); ?>"><button class="button is-primary">Voir la version publique</button></a>

        <?php if (!$isContributor) {
            if (!$getReject) {
                print '<a href="/index.php/Phoi/Users/becameContributor"><button class="button is-warning">Devenir contributeur</button></a>';
            }
        } ?>

    </p>
</div>
<script>
    $(document).ready(function() {
        $.get("<?php print __CA_URL_ROOT__; ?>/index.php/LoginReg/profileForm", function(data) {
            $("#profileForm").append(data);
            $("#ProfileForm").find("input").css("border", "inherit");
            $("#ProfileForm").find("input").attr("disabled", "true");
            $("#ProfileForm").find("input").css("padding", "0.375rem 0");
            $("#ProfileForm").find("button").hide();

            $("#ProfileForm").find("input").each(function() {
                if (!$(this).val() && $(this).attr('type') != "hidden") {
                    $(this).parent().parent().hide();
                }
            })
            $('<a href="/index.php/Phoi/Users/editPassword"><button type="button" class="button is-info">Modifier mon mot de passe</button></a>').insertAfter($("#ProfileForm").find("button"));

        });
    });

    function editProfile() {
        $("#ProfileForm").find("input").css("border", "1px solid #ced4da");
        $("#ProfileForm").find("input").removeAttr("disabled");
        $("#ProfileForm").find("input").css("padding", "0.375rem 0.75rem");
        $("#ProfileForm").find(".form-group").show();

        $("#ProfileForm").find("button").show();


    }

    $('.js-upload-file-btn').on('click', function() {
        $(".js-upload-file, .modal-backdrop").addClass("open");
    });

    // close all modal
    $(document).on('click', '.modal .close', function() {
        $(".modal, .modal-backdrop").removeClass("open");

    });


    Dropzone.autoDiscover = false;
    try {
        var myDropzone = new Dropzone("#dropzone", {
            paramName: "file", // The name that will be used to transfer the file
            maxFilesize: 5, // MB
            maxFiles: 1,
            url: "/index.php/Phoi/Users/changeAvatar",
            addRemoveLinks: true,
            dictDefaultMessage: '<span class="bigger-150 bolder"><i class=" fa fa-caret-right red"></i> Glisser ici un fichier image </span> \
                    <span class="smaller-80 grey">(ou cliquer)</span> <br /> \
                    <i class="upload-icon fa fa-cloud-upload blue fa-3x"></i>',
            dictResponseError: "Erreur lors de l'envoie du fichier",

            //change the previewTemplate to use Bootstrap progress bars

        });
    } catch (e) {
        //  alert('Dropzone.js does not support older browsers!');
    }

    function saveAvatar() {
        if (!$(".modal-card-body #dropzone .dz-success").html()) {
            alert("Vous devez avoir envoyer une image !");
            return false;
        } else {
            window.location.href = "/index.php/Phoi/MonEspace/Index";
        }
    }
</script>
<style>
    h3 {
        font-weight: bold;
    }

    a.tag {}

    a.tag:hover {
        text-decoration: none;
    }

    .avatar {
        width: 225px;
        position: relative;
        margin-bottom: 15px;
    }

    .avatar:hover .icon {
        display: block;
    }

    .avatar .icon {
        position: absolute;
        right: 0;
        width: 30px;
        height: 30px;
        text-align: center;
        background-color: white;
        padding: 2px;
        display: none;
    }

    #profileForm {
        margin-top: 15px;
        position: relative;
    }

    #profileForm .icon {
        position: absolute;
        right: 0;
        display: none;
    }

    #profileForm:hover .icon {
        display: block;
    }

    .modal,
    .modal-backdrop {
        display: none;
    }

    .modal.open,
    .modal-backdrop.open {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }

    .modal {
        display: none;
        /* Hidden by default */
        padding-top: 100px;
        /* Location of the box */
        background-color: rgb(0, 0, 0);
        /* Fallback color */
        background-color: rgba(0, 0, 0, 0.4);
        /* Black w/ opacity */
    }

    .modal-dialog {
        margin: auto;
    }

    .modal-content {
        position: relative;
        margin: auto;
        animation-name: animatetop;
        animation-duration: 0.4s
    }

    @keyframes animatetop {
        from {
            top: -300px;
            opacity: 0
        }

        to {
            top: 0;
            opacity: 1
        }
    }

    .select{
        font-size: 14px;
    }

    .control.has-icons-left .icon.is-left {
        left: 10px;
        top: 10px;
        width: 14px;
        height: 14px;
    }

    .select:not(.is-multiple):not(.is-loading)::after {
        border-color: #dbdbdb !important;

    }
</style>