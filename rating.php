<?php
require_once 'src/VideoLibrary.php';
$library = new VideoLibrary();

$library->updateRating($_REQUEST['movie'], $_REQUEST['rating']);

?>
<div class="alert alert-dismissable alert-success">
  <button type="button" class="close" data-dismiss="alert">×</button>
  <strong>We have updated the rating to : </strong> <?php print $_REQUEST['rating']; ?>.
</div>