<?php 
$contribution_id = $this->getVar("contribution_id");
$contribution = $this->getVar("contribution");
$o_data = new Db();
$qr_result = $o_data->query("
   SELECT SUM(vote) as total_vote 
   FROM  phoi_votes
   WHERE contribution_id = '".$contribution_id."';");
$total_vote = $qr_result->getAllRows()[0]["total_vote"];
$qr_result = $o_data->query("
   SELECT *
   FROM  phoi_votes
   WHERE contribution_id = '".$contribution_id."';");
$votes = $qr_result->getAllRows();

?>
<style>
.notification {display:none;}
#jevotepour,
#jevotecontre {
    cursor:pointer;
}
</style>

<h2>Je vote</h2>
<div class="columns">
    <div class="column is-half" >
    <div class="card">
    <header class="card-header">
        <p class="card-header-title">
        Proposition de modification
        </p>
    </header>
        <div class="card-content">
            <div class="media">
            <div class="media-left">
                <figure class="image is-48x48">
                <img src="https://bulma.io/images/placeholders/96x96.png" alt="Placeholder image">
                </figure>
            </div>
            <?php 
            $vt_object = new ca_objects($contribution["_id"]); ?>
            <div class="media-content">
                <p class="title is-4"><?php print $vt_object->get("ca_objects.preferred_labels"); ?></p>
                <p class="subtitle is-6"><?php print date( 'd/m/Y', $contribution["_timecode"]);?></p>
            </div>
            </div>

            <div class="content">
            <?php 
            //var_dump($contribution);            
            foreach($contribution as $field=>$value){
                if($field[0] == "_") continue;
                print "<b>".$field."</b> : ".$value."<br/>";
            } ?>
            </div>
        </div>
    </div>
  </div>
  <div class="column" >
    <div class="tile is-ancestor">
        <div class="tile is-4 is-vertical is-parent">
            <div class="tile is-child box">
            <p style="text-align:center;"><span class="tag is-light">Pour être accepté</span><br/><span id='quorumplus'>&nbsp;</span></p>
            </div>
            <div class="tile is-child box">
            <p style="text-align:center;"><span class="tag is-light">Pour être rejeté</span><br/>-<span id='quorumminus'>&nbsp;</span></p>
            </div>
        </div>
        <div class="tile is-parent">
            <div class="tile is-child box">
            <p>Score actuel</p>
            <p style="font-size:40px;text-align:center;margin-top:20px;"><?= $total_vote; ?></p>
            </div>
        </div>
    </div>

    <div class="columns">
  <div class="column" >
    <div class="tags has-addons are-large" id="jevotepour">
            <span class="tag is-dark">je vote</span><span class="tag is-success">POUR</span>
        </div>    
  </div>
  <div class="column">
    <div class="tags has-addons are-large" id="jevotecontre">
            <span class="tag is-dark">je vote</span><span class="tag is-danger">CONTRE</span>
        </div>    
  </div>
</div>

  </div>
</div>





<div style="height:40px;"></div>


<div style="height:80px;"></div>
<table id="votes" style="">
    <thead>
        <tr><th>Pseudo</th><th>Statut</th><th>Date</th><th>Vote</th></tr>
    </thead>
    <tbody>
    <?php  foreach($votes as $vote):
        $user = new ca_users($vote["user_id"]);
        $group = array_filter($user->getUserGroups(), function($key){
            if ($key["code"] == "moderator" || $key["code"] == "admin"){
                return false;
            }
            return true;
        });
        print "<tr><td>".$user->get("ca_users.user_name")."</td>
        <td>".reset($group)["name"]."</td>
        <td>".$vote["timestamp"]."</td>
        <td>".$vote["vote"]."</td></tr>";
    endforeach;?>
       
    </tbody>
</table>
<div style="height:80px;"></div>
<link href="//cdn.datatables.net/1.10.20/css/jquery.dataTables.min.css" rel="stylesheet" />
<script src="//cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready( function () {
        $('#votes').DataTable({
            "language": {"url": "/datatables_french.json"},
            "info": false
        });
        $.get("/quorum.txt", function(data){
            $("#quorumplus").text(data);
            $("#quorumminus").text(data);
        });

        $("#jevotepour").click(function() {
            $.getJSON("/index.php/Phoi/Vote/VoterPour/id/1593758677.json", function(data) {
                console.log(data);
                let closebutton = $(".notification button").clone();
                $(".notification button").parent().text(data.message);
                if(data.result != "ok") {
                    $(".notification").removeClass('is-primary').addClass('is-warning');
                }
                $(".notification").show();
                closebutton.appendTo(".notification");
                if(data.refresh) {
                    setTimeout(function(){location.reload();}, 1000);
                }
            });
        });
        $("#jevotecontre").click(function() {
            $.getJSON("/index.php/Phoi/Vote/VoterContre/id/1593758677.json", function(data) {
                console.log(data);
                let closebutton = $(".notification button").clone();
                $(".notification button").parent().text(data.message);
                if(data.result != "ok") {
                    $(".notification").removeClass('is-primary').addClass('is-warning');
                }
                $(".notification").show();
                closebutton.appendTo(".notification");
                if(data.refresh) {
                    setTimeout(function(){location.reload();}, 1000);
                }
            });
        })
        $("body").on("click", ".notification", function() {

            console.log("notification delete");
            $(".notification").hide();
        })
    } );
</script>