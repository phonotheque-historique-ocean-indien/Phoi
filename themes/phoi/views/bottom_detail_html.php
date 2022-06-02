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
			  <span class="tabitems is-active" data-tab="random"><?php _p('Au hasard'); ?></span>
			  <span class="tabitems" data-tab="mostDisplayed"><?php _p('Les plus vus'); ?></span>
			  <!-- <span class="tab"><?php _p('Voir tous'); ?></span> -->
			</div>
	    </div>
	  </header>
	  <div class="card-content tab-content" id="mostDisplayed" style="display:none;">
	    <div class="content">
			<?php while ($qr_mostDisplayed->nextRow()) {
    $o_id = $qr_mostDisplayed->get('object_id');
    $vt_object = new ca_objects($o_id);
	 ?>
	 					<p>
						<?php
							$preview170= $vt_object->get('ca_object_representations.media.preview170.url');
							$preview170=reset(explode(";", $preview170));
							if($preview170): ?>
								<a href="/index.php/Detail/objects/<?php echo $vt_object->get('ca_objects.object_id'); ?>"><img src="<?= $preview170 ?>"  onerror="this.style.display='none';" alt="Placeholder image"></a>
						  <?php endif; ?>
					        <a href=/index.php/Detail/objects/<?php echo $vt_object->get('ca_objects.object_id'); ?>>
							<?php 
								$label = $vt_object->get('ca_objects.parent.preferred_labels');
								if(!$label) $label = $vt_object->get('ca_objects.preferred_labels');
								echo  $label;
							?>
							</a></p>

			<?php
    ++$i;
} ?>
		    </div>

	    </div>
		<div class="card-content tab-content" id="random">
			<div class="content">
				<?php while ($qr_random->nextRow()) {
    $o_id = $qr_random->get('object_id');
    $vt_object = new ca_objects($o_id);
     ?>
							<a href="/index.php/Detail/objects/<?php echo $vt_object->get('ca_objects.object_id'); ?>">
							<p class="">
							<?php 
								$preview170= $vt_object->get('ca_object_representations.media.preview170.url');
								$preview170=reset(explode(";", $preview170));
								if($preview170): ?>
									<img src="<?= $preview170 ?>" alt="Placeholder image">
								<?php endif; ?>
									<?php 
									$label = $vt_object->get('ca_objects.parent.preferred_labels');
									if(!$label) $label = $vt_object->get('ca_objects.preferred_labels');
									echo  $label;
									?>
									</p>
							</a>
				<?php
    ++$i;
} ?>
			</div>

	    </div>
	  </div>	  
	</div>
</div>

<script>
	$(".tabitems").on("click", function() {
		$(".tabitems").removeClass("is-active");
		$(this).addClass("is-active");
		let tab = $(this).data("tab");
		$(".tab-content").hide();
		$("#"+tab).show();
	});
</script>
<style>
.item-accessoires .media img{
	width:80px;
}
.infosprincipales {
	max-height: fit-content !important;
}
</style>	