<?php

$uri = $_SERVER[ 'REQUEST_URI' ] ;  // адреса запиту 
if( $uri == 'index' ) {
	include 'index.php' ;  # 
}
else {
	echo 'acces manager - 404' ;
}

