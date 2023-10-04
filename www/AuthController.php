<?php

include_once "ApiController.php" ;

class AuthController extends ApiController {
	
	protected function do_get() {
		if( isset( $_GET[ 'logout' ] ) ) {
			unset( $_SESSION[ 'auth-user-id' ] ) ;
			header( 'Location: /' ) ;
			exit ;
		}
	}
	
	protected function do_post() {
		$db = $this->get_db() ;
		if( empty( $db ) ) {
			$this->log_error( __METHOD__ . "#" . __LINE__ . " Empty DB reference" ) ;
			$this->send_error( 500 ) ;
		}
		if( empty( $_GET[ 'login' ] ) ) {
			$this->send_error( 400, "Parameter 'login' required" ) ;
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
			$this->log_error( __METHOD__ . "#" . __LINE__ . $ex->getMessage() . " {$sql}" ) ;
			$this->send_error( 500 ) ;
		}
		if( $row === false ) {
			$this->send_error( 403 ) ;
		}
		$dk = sha1( $row[ 'salt' ] . md5( $password ) ) ;
		if( $row[ 'pass_dk' ] == $dk ) {
			// session_start() ;
			$_SESSION[ 'auth-user-id' ] = $row[ 'id' ] ;
			echo 'OK' ;
		}
		else {
			$this->send_error( 403, "Forbidden2" ) ;
		}
	}

}
