<?php 
if( empty( $db ) ) {
	http_response_code( 500 ) ;
	echo "Server error - empty DB" ;
	exit ;
}
if( empty( $_GET[ 'login' ] ) ) {
	http_response_code( 400 ) ;
	echo "Parameter 'login' required" ;
	exit ;
}
$login = $_GET[ 'login' ] ;
$password = empty( $_GET[ 'password' ] ) 
	? '' 
	: $_GET[ 'password' ] ;
	
$sql = "SELECT * FROM users u WHERE u.`login` = ?" ;
try {
	$prep = $db->prepare( $sql ) ;
	$prep->execute( [ $login ] ) ;
	$row = $prep->fetch() ;
}
catch( PDOException $ex ) {
	http_response_code( 500 ) ;
	echo "Server error - " . $ex->getMessage() ;
	exit ;
}
if( $row === false ) {
	http_response_code( 403 ) ;
	echo "Forbidden" ;
	exit ;
}
$dk = sha1( $row[ 'salt' ] . md5( $password ) ) ;
if( $row[ 'pass_dk' ] == $dk ) {
	// session_start() ;
	$_SESSION[ 'auth-user-id' ] = $row[ 'id' ] ;
	echo 'OK' ;
}
else {
	http_response_code( 403 ) ;
	echo "Forbidden2" ;
	exit ;
}

