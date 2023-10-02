<?php

class OopController {
	private $x ;  // при зверненні - $this->x 
	
	public function __construct( $x = 10 ) {  // конструктор створюється спеціальною назвою
		// Методи класів підтримують усі можливості функцій, у т.ч. параметри за замовчанням
		$this->x = $x ;  // доступ до елементів об'єкту здійнюється завжди через
		// $this-> після чого іде ім'я змінної, але без додаткового $
	}
	
	public function serve() {
		$method = strtolower( $_SERVER[ 'REQUEST_METHOD' ] ) ;
		$handler = "do_{$method}" ;
		if( method_exists( $this, $handler ) ) {
			$this->$handler() ;
		}
		else {
			http_response_code( 405 ) ;
			echo "HTTP method not allowed by the server" ;
			exit ;
		}
		
		// switch( $method ) {
		// 	case 'GET'  : $this->do_get()  ; break ;
		// 	case 'POST' : $this->do_post() ; break ;
		// }
		// if( $method == 'GET' ) $this->do_get() ;
	}
	
	private function do_get() {
		$this->log_error( __METHOD__ . "#" . __LINE__ . " GET request detected" ) ;
		$page =  'OopView.php' ;
		include '_layout.php' ;
	}
	
	private function do_post() {
		echo 'POST requests' ;
	}
	
	private function do_put() {
		echo 'PUT requests' ;
	}
	
	private function do_link() {
		echo 'LINK requests' ;
	}
	
	protected function log_error( $message ) {
		$log_name = "logs/" . __CLASS__ . ".log" ;
		$log_file = fopen( $log_name, "a" ) ;
		fwrite( $log_file, date( "y-m-d h:i:s" ) . " " . $message . "\r\n" ) ;
		fclose( $log_file ) ;
	}
	
	protected function get_db() {
		global $db ;
		return $db ;
	}
}
