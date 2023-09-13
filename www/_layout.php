<?php
	if( ! isset( $page ) ) :  # формалізм, схожий на Python
		echo 'Invalid access' ;
		endif;
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8" />
	<title>PV-111</title>
	<!-- Compiled and minified CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
	<!--Import Google Icon Font-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
</head>
<body>
	<nav>
		<div class="nav-wrapper orange">
		  <a href="/" class="brand-logo">PV-111</a>
		  <ul id="nav-mobile" class="right hide-on-med-and-down">
			<li <?php if($page=='about.php') echo 'class="active"'; ?> >
				<a href="/about">About</a>
			</li>
			<li <?php if($page=='forms.php') echo 'class="active"'; ?> >
				<a href="/forms">Forms</a>
			</li>
			<li <?php if($page=='db.php'   ) echo 'class="active"'; ?> >
				<a href="/db">DB</a>
			</li>
		  </ul>
		</div>
	</nav>
	<div class="container">
		<?php include $page ; ?>
	</div>
<!-- Compiled and minified JavaScript -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>