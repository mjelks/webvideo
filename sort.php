<?php
require_once 'src/VideoLibrary.php';
$library = new VideoLibrary();

$sort = (array_key_exists('sort', $_REQUEST)) ? $_REQUEST['sort'] : 'title';
$library->sortLibrary($sort);

?>
<?php
  foreach ($library->getLibrary() as $meta):
	$ignore_class = $meta['ignore'] == true ? 'ignore' : '';
?>
	<div class="movie-row"
		 movie="<?php print $meta['name']; ?>" 
		 mtime="<?php print $meta['mtime']; ?>"
		 rating="<?php print $meta['rating']; ?>"
	>
	  <div class="modal modal-dialog-center <?php print $ignore_class; ?>" movie="<?php print $meta['name']; ?>">
		<div class="modal-dialog">
		  <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			  <h4 class="modal-title">Rate the movie: <?php echo $library->getFriendlyName($meta['name']); ?></h4>
			</div>
			<div class="modal-body text-center">
			  <div class="button-actions">
				<button type="button" class="btn btn-primary play action" action="<?php echo $library->getMediaPath() . '/' . $meta['name'] ?>">Play</button>
				<button type="button" class="btn btn-success rate action">Rate</button>
				<button type="button" class="btn btn-danger remove action">Remove</button>
				<div class="rating">
				  <div class="btn-group">
					<?php for ($i=1;$i<=5;$i++): ?>
					<?php $class = ($meta['rating'] && $meta['rating'] == $i) ? "btn btn-lg btn-info" : "btn btn-lg btn-default"; ?>
					  <button type="button" class="<?php echo $class; ?>" value="<?php echo $i; ?>" movie="<?php echo $meta['name']; ?>"><?php echo $i; ?></button>
					<?php endfor; ?>
				  </div>
				  <br/>
				  <div class="btn-group go-back text-center">
					<button type="button" class="btn btn-info go-back">Go Back</button>
				  </div>
				</div>
			  </div>
			</div>

			<div class="modal-footer">
			  <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
			</div>
		  </div>
		</div>
	  </div>
	  <a 
		 href="#" 
		 class="list-group-item movie_listing"
		 title="<?php echo $meta['name']; ?>"
		 short_name="<?php echo $meta['short_name']; ?>"
	  >
		<?php if ($meta['rating'] > 0): ?>
		  <span class="badge"><?php echo $meta['rating']?></span>
		<?php endif; ?>
		<span class="name-label">
		  <?php echo $meta['name']; ?>
		</span>
	  </a>
	</div>
<?php  endforeach; ?>

