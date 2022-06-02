<?php
$users = $this->getVar("users");
//var_dump($users);die();
// sanitize page name for browse tab
$browser_tab_label = "PHOI - Utilisateurs";
?>
<script>
	window.parent.history.pushState('', "<?= $browser_tab_label ?>", "/index.php/Phoi/Users/List");
	window.parent.document.title = "<?= $browser_tab_label ?>";
</script>
<h2><?php _p("Utilisateurs"); ?></h2>
<table id="userslist">
        <thead>
            <tr>
                <th></th>
                <th><?php _p("Login"); ?></th>
                <th><?php _p("Prénom"); ?></th>
                <th><?php _p("Nom"); ?></th>
                <th><?php _p("Droits"); ?></th>
                <th><?php _p("Confiance"); ?></th>
                <th><?php _p("Inscription"); ?></th>
            </tr>
        </thead>
<?php
foreach($users as $user):
    $user_slug = str_replace(["@","/","."],"_", $user["name"]);
?>
    <tr>
        <td><i class="mdi mdi-information is-large tooltip" data-tooltip-content="#tooltip_content_<?= $user_slug ?>"></i></td>
        <td><?= $user["name"]; ?></td>
        <td><?= $user["fname"]; ?></td>
        <td><?= $user["lname"]; ?></td>
        <td><?= $user["groups"]; ?></td>
        <td><?= $user["confiance"]; ?></td>
        <td><?= $user["date"]; ?></td>
    </tr>
<?php endforeach; ?>
</table>

<div class="tooltip_templates" style="display: none">
<?php
foreach($users as $user):
    $user_slug = str_replace(["@","/","."],"_", $user["name"]);
?>
    <span id="tooltip_content_<?= $user_slug ?>">
        <?php
        $vt_user = new ca_users($user["id"]);
        $picture = $vt_user->getPreference("user_profile_image");
        $picture = str_replace(__CA_BASE_DIR__, "", $picture);

        if(!$picture) $picture="/user_icon.png";
        print "<p style='text-align:center'><img class='profile-image' src='".$picture."'></p>";
        ?><br/>

        <p style="text-align: center;font-size:24px;"><b><?= $user["fname"]; ?> <?= $user["lname"]; ?></b><br/>
        <i><?= $user["name"]; ?></i></p>
        <hr/>
        Droits : <?php print ($user["groups"] ? $user["groups"] : "<i>Utilisateur</i>"); ?><br/>
        Membre depuis : <?= $user["date"]; ?><br/>
    </span>
<?php endforeach; ?>
</div>
<link href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet" />
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<div style="height:80px;"></div>

<link href="https://swisnl.github.io/jQuery-contextMenu/dist/jquery.contextMenu.css" rel="stylesheet" type="text/css" />
<script src="https://swisnl.github.io/jQuery-contextMenu/dist/jquery.contextMenu.js" type="text/javascript"></script>
<script>
        $(function() {
        $.contextMenu({
            selector: '.context-menu-one',
            trigger: 'left',
            callback: function(key, options) {
                var m = "clicked: " + key;
                window.console && console.log(m) || alert(m);
            },
            items: {
                "edit": {name: "Edit", icon: "edit"},
                "cut": {name: "Cut", icon: "cut"},
               copy: {name: "Copy", icon: "copy"},
                "paste": {name: "Paste", icon: "paste"},
                "delete": {name: "Delete", icon: "delete"},
                "sep1": "---------",
                "quit": {name: "Quit", icon: function(){
                    return 'context-menu-icon context-menu-icon-quit';
                }}
            }
        });

        $('.context-menu-one').on('click', function(e){
            console.log('clicked', this);
        })
    });
</script>

<script type="text/javascript" src="/tooltipster.bundle.min.js"></script>
<style>
    #userslist td .mdi {
        font-size:20px;
    }
    /* TooltipSter */
    .tooltipster-fall,.tooltipster-grow.tooltipster-show{-webkit-transition-timing-function:cubic-bezier(.175,.885,.32,1);-moz-transition-timing-function:cubic-bezier(.175,.885,.32,1.15);-ms-transition-timing-function:cubic-bezier(.175,.885,.32,1.15);-o-transition-timing-function:cubic-bezier(.175,.885,.32,1.15)}.tooltipster-base{display:flex;pointer-events:none;position:absolute}.tooltipster-box{flex:1 1 auto}.tooltipster-content{box-sizing:border-box;max-height:100%;max-width:100%;overflow:auto}.tooltipster-ruler{bottom:0;left:0;overflow:hidden;position:fixed;right:0;top:0;visibility:hidden}.tooltipster-fade{opacity:0;-webkit-transition-property:opacity;-moz-transition-property:opacity;-o-transition-property:opacity;-ms-transition-property:opacity;transition-property:opacity}.tooltipster-fade.tooltipster-show{opacity:1}.tooltipster-grow{-webkit-transform:scale(0,0);-moz-transform:scale(0,0);-o-transform:scale(0,0);-ms-transform:scale(0,0);transform:scale(0,0);-webkit-transition-property:-webkit-transform;-moz-transition-property:-moz-transform;-o-transition-property:-o-transform;-ms-transition-property:-ms-transform;transition-property:transform;-webkit-backface-visibility:hidden}.tooltipster-grow.tooltipster-show{-webkit-transform:scale(1,1);-moz-transform:scale(1,1);-o-transform:scale(1,1);-ms-transform:scale(1,1);transform:scale(1,1);-webkit-transition-timing-function:cubic-bezier(.175,.885,.32,1.15);transition-timing-function:cubic-bezier(.175,.885,.32,1.15)}.tooltipster-swing{opacity:0;-webkit-transform:rotateZ(4deg);-moz-transform:rotateZ(4deg);-o-transform:rotateZ(4deg);-ms-transform:rotateZ(4deg);transform:rotateZ(4deg);-webkit-transition-property:-webkit-transform,opacity;-moz-transition-property:-moz-transform;-o-transition-property:-o-transform;-ms-transition-property:-ms-transform;transition-property:transform}.tooltipster-swing.tooltipster-show{opacity:1;-webkit-transform:rotateZ(0);-moz-transform:rotateZ(0);-o-transform:rotateZ(0);-ms-transform:rotateZ(0);transform:rotateZ(0);-webkit-transition-timing-function:cubic-bezier(.23,.635,.495,1);-webkit-transition-timing-function:cubic-bezier(.23,.635,.495,2.4);-moz-transition-timing-function:cubic-bezier(.23,.635,.495,2.4);-ms-transition-timing-function:cubic-bezier(.23,.635,.495,2.4);-o-transition-timing-function:cubic-bezier(.23,.635,.495,2.4);transition-timing-function:cubic-bezier(.23,.635,.495,2.4)}.tooltipster-fall{-webkit-transition-property:top;-moz-transition-property:top;-o-transition-property:top;-ms-transition-property:top;transition-property:top;-webkit-transition-timing-function:cubic-bezier(.175,.885,.32,1.15);transition-timing-function:cubic-bezier(.175,.885,.32,1.15)}.tooltipster-fall.tooltipster-initial{top:0!important}.tooltipster-fall.tooltipster-dying{-webkit-transition-property:all;-moz-transition-property:all;-o-transition-property:all;-ms-transition-property:all;transition-property:all;top:0!important;opacity:0}.tooltipster-slide{-webkit-transition-property:left;-moz-transition-property:left;-o-transition-property:left;-ms-transition-property:left;transition-property:left;-webkit-transition-timing-function:cubic-bezier(.175,.885,.32,1);-webkit-transition-timing-function:cubic-bezier(.175,.885,.32,1.15);-moz-transition-timing-function:cubic-bezier(.175,.885,.32,1.15);-ms-transition-timing-function:cubic-bezier(.175,.885,.32,1.15);-o-transition-timing-function:cubic-bezier(.175,.885,.32,1.15);transition-timing-function:cubic-bezier(.175,.885,.32,1.15)}.tooltipster-slide.tooltipster-initial{left:-40px!important}.tooltipster-slide.tooltipster-dying{-webkit-transition-property:all;-moz-transition-property:all;-o-transition-property:all;-ms-transition-property:all;transition-property:all;left:0!important;opacity:0}@keyframes tooltipster-fading{0%{opacity:0}100%{opacity:1}}.tooltipster-update-fade{animation:tooltipster-fading .4s}@keyframes tooltipster-rotating{25%{transform:rotate(-2deg)}75%{transform:rotate(2deg)}100%{transform:rotate(0)}}.tooltipster-update-rotate{animation:tooltipster-rotating .6s}@keyframes tooltipster-scaling{50%{transform:scale(1.1)}100%{transform:scale(1)}}.tooltipster-update-scale{animation:tooltipster-scaling .6s}.tooltipster-sidetip .tooltipster-box{background:#565656;border:2px solid #000;border-radius:4px}.tooltipster-sidetip.tooltipster-bottom .tooltipster-box{margin-top:8px}.tooltipster-sidetip.tooltipster-left .tooltipster-box{margin-right:8px}.tooltipster-sidetip.tooltipster-right .tooltipster-box{margin-left:8px}.tooltipster-sidetip.tooltipster-top .tooltipster-box{margin-bottom:8px}.tooltipster-sidetip .tooltipster-content{color:#fff;line-height:18px;padding:6px 14px}.tooltipster-sidetip .tooltipster-arrow{overflow:hidden;position:absolute}.tooltipster-sidetip.tooltipster-bottom .tooltipster-arrow{height:10px;margin-left:-10px;top:0;width:20px}.tooltipster-sidetip.tooltipster-left .tooltipster-arrow{height:20px;margin-top:-10px;right:0;top:0;width:10px}.tooltipster-sidetip.tooltipster-right .tooltipster-arrow{height:20px;margin-top:-10px;left:0;top:0;width:10px}.tooltipster-sidetip.tooltipster-top .tooltipster-arrow{bottom:0;height:10px;margin-left:-10px;width:20px}.tooltipster-sidetip .tooltipster-arrow-background,.tooltipster-sidetip .tooltipster-arrow-border{height:0;position:absolute;width:0}.tooltipster-sidetip .tooltipster-arrow-background{border:10px solid transparent}.tooltipster-sidetip.tooltipster-bottom .tooltipster-arrow-background{border-bottom-color:#565656;left:0;top:3px}.tooltipster-sidetip.tooltipster-left .tooltipster-arrow-background{border-left-color:#565656;left:-3px;top:0}.tooltipster-sidetip.tooltipster-right .tooltipster-arrow-background{border-right-color:#565656;left:3px;top:0}.tooltipster-sidetip.tooltipster-top .tooltipster-arrow-background{border-top-color:#565656;left:0;top:-3px}.tooltipster-sidetip .tooltipster-arrow-border{border:10px solid transparent;left:0;top:0}.tooltipster-sidetip.tooltipster-bottom .tooltipster-arrow-border{border-bottom-color:#000}.tooltipster-sidetip.tooltipster-left .tooltipster-arrow-border{border-left-color:#000}.tooltipster-sidetip.tooltipster-right .tooltipster-arrow-border{border-right-color:#000}.tooltipster-sidetip.tooltipster-top .tooltipster-arrow-border{border-top-color:#000}.tooltipster-sidetip .tooltipster-arrow-uncropped{position:relative}.tooltipster-sidetip.tooltipster-bottom .tooltipster-arrow-uncropped{top:-10px}.tooltipster-sidetip.tooltipster-right .tooltipster-arrow-uncropped{left:-10px}
    .tooltipster-sidetip .tooltipster-box {
        background-color: #EEEEEE;
    }
    .tooltipster-sidetip .tooltipster-content {
        color:#333;
    }
    .profile-image {
        max-height: 120px;
        display:inline-block;
    }
</style>


<script>
    $(document).ready( function () {
        $('#userslist').DataTable({
            "language": {"url": "/datatables_french.json"},
            "info": false,
            "drawCallback": function( settings ) {
                $('.tooltip').tooltipster({
                    trigger: 'click',
                    maxWidth: 400,
                    minWidth: 300,
                    side: 'right',
                    functionPosition: function(instance, helper, position){
                        position.size.height += 30;
                        position.size.width += 60;
                        return position;
                    }
                });
            }
        });
    } );
</script>