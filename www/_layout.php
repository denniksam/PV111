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
			<li><a href="sass.html">Sass</a></li>
			<li><a href="badges.html">Components</a></li>
			<li><a href="collapsible.html">JavaScript</a></li>
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