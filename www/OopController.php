<?php

include_once "ApiController.php" ;

class OopController extends ApiController {
	private $x ;  // при зверненні - $this->x 
	
	public function __construct( $x = 10 ) {  // конструктор створюється спеціальною назвою
		// Методи класів підтримують усі можливості функцій, у т.ч. параметри за замовчанням
		$this->x = $x ;  // доступ до елементів об'єкту здійнюється завжди через
		// $this-> після чого іде ім'я змінної, але без додаткового $
	}
	
	protected function do_get() {
		// $this->log_error( __METHOD__ . "#" . __LINE__ . " GET request detected" ) ;
		global $_CONTEXT ;
		$page =  'OopView.php' ;
		include '_layout.php' ;
	}
	
	protected function do_post() {
		echo 'POST requests' ;
	}
	
	protected function do_put() {
		echo 'PUT requests' ;
	}
	
	protected function do_link() {
		echo 'LINK requests' ;
	}
}
