<?php
require_once __CA_MODELS_DIR__.'/ca_objects.php';
$qr_mostDisplayed = $this->getVar('mostDisplayed');
$qr_random = $this->getVar('random');
?>
<div class="item-accessoires">
	<div class="card infosprincipales">
	  <header class="card-header">
	    <div class="card-header-title">
		    <div class="tags are-medium">
			  <span class="tab is-active" data-tab="mostDisplayed"><?php _p('12 items les plus vus'); ?></span>
			  <span class="tab" data-tab="random"><?php _p('12 items au hasard'); ?></span>
			  <!-- <span class="tab"><?php _p('Voir tous'); ?></span> -->
			</div>
	    </div>
	  </header>
	  <div class="card-content tab-content" id="mostDisplayed">
	    <div class="content">
			<?php while ($qr_mostDisplayed->nextRow()) {
    $o_id = $qr_mostDisplayed->get('object_id');
    $vt_object = new ca_objects($o_id);
    if (0 == $i % 4) {
        echo "<div class='columns'>";
    } ?>
				<div class="column is-one-quarter">
				    <div class="card">
					  <div class="card-content">
					    <div class="media">
						<?php
							$preview170= $vt_object->get('ca_object_representations.media.preview170.url');
							$preview170=reset(explode(";", $preview170));
							if($preview170): ?>
							<div class="media-left">
								<figure class="image is-48x48">
								<a href="/index.php/Detail/objects/<?php echo $vt_object->get('ca_objects.object_id'); ?>"><img src="<?= $preview170 ?>"  onerror="this.style.display='none';" alt="Placeholder image"></a>
								</figure>
							</div>
						  <?php endif; ?>
					      <div class="media-content">
					        <p class="title is-4"><a href=/index.php/Detail/objects/<?php echo $vt_object->get('ca_objects.object_id'); ?>><?php echo $vt_object->get('ca_objects.preferred_labels'); ?></a></p>
					        <!-- <p class="subtitle is-6">Auteur</p> -->
					      </div>
					    </div>

					    <!--<div class="content">
					      Début de description
					    </div>-->
					  </div>
					</div>
				</div>
			<?php
    if (3 == $i % 4) {
        echo '</div>';
    }
    ++$i;
} ?>
		    </div>

	    </div>
		<div class="card-content tab-content" id="random" style="display:none;">
			<div class="content">
				<?php while ($qr_random->nextRow()) {
    $o_id = $qr_random->get('object_id');
    $vt_object = new ca_objects($o_id);
    if (0 == $i % 4) {
        echo "<div class='columns'>";
    } ?>
					<div class="column is-one-quarter">
						<div class="card">
						<div class="card-content">
							<div class="media">
							<?php 
								$preview170= $vt_object->get('ca_object_representations.media.preview170.url');
								$preview170=reset(explode(";", $preview170));
								if($preview170): ?>
							<div class="media-left">
								<figure class="image is-48x48">
								<a href="/index.php/Detail/objects/<?php echo $vt_object->get('ca_objects.object_id'); ?>"><img src="<?= $preview170 ?>" alt="Placeholder image"></a>
								</figure>
							</div>
							<?php endif; ?>
							<div class="media-content">
								<p class="title is-4"><a href="/index.php/Detail/objects/<?php echo $vt_object->get('ca_objects.object_id'); ?>"><?php echo $vt_object->get('ca_objects.preferred_labels'); ?></a></p>
							</div>
							</div>

							<!--<div class="content">
							Début de description
							</div>-->
						</div>
						</div>
					</div>
				<?php
        if (3 == $i % 4) {
            echo '</div>';
        }
    ++$i;
} ?>
				</div>

	    </div>
	  </div>	  
	</div>
</div>

<script>
	$(".tab").on("click", function() {
		$(".tab").removeClass("is-active");
		$(this).addClass("is-active");
		let tab = $(this).data("tab");
		$(".tab-content").hide();
		$("#"+tab).show();
	});
</script>
