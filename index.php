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
	<link href="css/custom.css" rel="stylesheet">

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
		  <h5>Sorted by:</h5>
		  <ul class="nav nav-pills">
			<li class="active sort title"><a href="#">Title</a></li>
			<li class="sort rating" action="rating"><a href="#">Rating</a></li>
			<li class="sort mtime" action="mtime"><a href="#">Modified Time</a></li>
		  </ul>
		</div>
	  </div>
	  <div class="row top10">
		<div class="col-lg-12">
		  <div class="list-group">
			<a href="#" id="list-title-a" class="list-group-item active">
			  <div id="list-title">
				<?php echo $panel_title; ?>
			  </div>
			  <div id="progress" class="progress progress-striped active">
				<div class="progress-bar" style="width: 100%"></div>
			  </div>
			</a>
			<div id="sort-container">
			 <?php include_once('sort.php'); ?>
			</div>
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
			$(this).find(".name-label").text($(this).attr('short_name'));
		  });
		} else {
		  $(".movie_listing").each(function(idx, val) {
			$(this).find(".name-label").text($(this).attr('title'));
		  });
		}
	  }
	  $(window).resize(function() {
		checkWidth();
	  });
	  
	  $("div").on('click', "[class*='movie_listing']", function(event) {
		console.log($(this));
		var modalOpen = $(".modal[movie='"+$(this).attr('title')+"']").modal();
		// reset the window / modal logic to default 
		modalOpen.on('hidden.bs.modal', function (e) {
		  modalOpen.find(".action").show();
		  modalOpen.find(".rating").hide();
		});
	  });
	  
	  $("div").on('click', '.play', function() {
		window.location.href = $(this).attr('action');
	  });
	  
	  $("div").on('click', '.rate', function() {
		$(this).parents(".button-actions").find(".action").hide();
		$(this).siblings(".rating").show();
	  });
	  
	  $("div").on('click', 'button.go-back', function() {
		$(this).parents(".button-actions").find(".action").show();
		$(this).parents(".rating").hide();
	  });
	  
	  $(".remove").on('click', function() {
		window.location.href = $(this).attr('action');
	  });
	  
	  $(".sort").on('click', function() {
		$("ul.nav li").removeClass("active");
		console.log($(this));
		console.log($(this).parents("li"));
		$(this).addClass("active");
		$("#list-title-a").removeClass("active")
		$("#list-title").hide();
		$("#progress").show();
		$.ajax({
		  type: "GET",
		  url: "sort.php",
		  data: { sort: $(this).attr('action') }
		})
		  .done(function( content ) {
			//alert( "Data Saved: " + msg );
			$("#list-title-a").addClass("active")
			$("#list-title").show();
			$("#progress").hide();
			$("#sort-container").html(content);
		  });
		
	  });
	  
	  $("div").on('click', '.rating button', function(e) {
		var selector = $(this);
		$.ajax({
		  type: "GET",
		  url: "rating.php",
		  data: { rating: $(this).attr('value'), movie: $(this).attr('movie') }
		})
		  .done(function( content ) {
			//alert( "Data Saved: " + msg );
			selector.addClass("active");
			selector.parentsUntil(".rating").after(content);
		  });
	  });
	  
	</script>  
  </body>
</html>
