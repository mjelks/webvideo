<?php
  //ini_set('display_errors',0);
  require_once 'src/VideoLibrary.php';
  $library = new VideoLibrary();
  $counter = 1;
  $title = "MGJ Theater";
  $panel_title = "Da Movies";
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php print $title; ?></title>

    <!-- Bootstrap -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="js/html5shiv.min.js"></script>
      <script src="js/respond.min.js"></script>
    <![endif]-->
  </head>
  <body>
	<div class="container">
	  <div class="page-header" id="banner">
        <div class="row">
          <div class="col-lg-12">
            <h1><?php print $title; ?></h1>
            <p class="lead">Simple listing of movies in the <strong><?php echo $library->getMediaPath(); ?></strong> folder</p>
          </div>
        </div>
      </div>
	  <div class="row">
		<div class="col-lg-12">
		  <div class="list-group">
			<a href="#" class="list-group-item active">
			  <?php echo $panel_title; ?>
			</a>
			 <?php 
			  foreach ($library->getLibrary() as $movie => $meta):
			  ?>
				<a href="<?php echo $library->getMediaPath() . '/' . $movie ?>" 
				   class="list-group-item movie_listing"
				   title="<?php echo $movie; ?>"
				   short_name="<?php echo $meta['short_name']; ?>"
			    >
				  <?php echo $movie; ?>
                </a>
			<?php  endforeach; ?>
		  </div>	
		</div>
	  </div>
	</div>
    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="js/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="js/bootstrap.min.js"></script>
	<script>
	  checkWidth();
	  function checkWidth() {
		//console.log($(this).width());
		if ($(this).width() < 600) {
		  // truncate the text
		  $(".movie_listing").each(function(val) {
			$(this).text($(this).attr('short_name'));
		  });
		} else {
		  $(".movie_listing").each(function(idx, val) {
			$(this).text($(this).attr('title'));
		  });
		}
	  }
	  $(window).resize(function() {
		checkWidth();
	  });
	</script>  
  </body>
</html>
