<?php

include_once "ApiController.php" ;

class SignupController extends ApiController {
	
	protected function do_get() {
		global $_CONTEXT ;
		$page = "forms.php" ; 
		include '_layout.php' ; 
	}
	
	protected function do_post() {
		
	}
	
	private function check_session() {
		if( isset( $_SESSION[ 'form_data' ] ) ) {
			// Загальна успішність:
			if( $_SESSION[ 'reg_db' ] !== true ) {
				$db_message = $_SESSION[ 'reg_db' ] ;
			}
			else {
				$db_message = "INSERT OK" ;
			}
			// є передача даних, перевіряємо повідомлення
			if( isset( $_SESSION[ 'name_message' ] ) ) { 
				$name_message = $_SESSION[ 'name_message' ] ;
				unset( $_SESSION[ 'name_message' ] ) ;
			}
			if( isset( $_SESSION[ 'lastname_message' ] ) ) { 
				$lastname_message = $_SESSION[ 'lastname_message' ] ;
				unset( $_SESSION[ 'lastname_message' ] ) ;
			}
			$reg_name = $_SESSION[ 'reg_name'  ] ;
			$reg_lastname = $_SESSION[ 'reg_lastname'  ] ;
			
			// видаляємо з сесії повідомленя про дані
			unset( $_SESSION[ 'form_data' ] ) ;
			
			if( isset( $name_message ) ) {  // валідація імені не пройшла
				$name_class = "invalid" ;
			}
			else {  // успішна валідація
				$name_class = "valid" ;
			}
			if( isset( $lastname_message ) ) {  // валідація імені не пройшла
				$lastname_class = "invalid" ;
			}
			else {  // успішна валідація
				$lastname_class = "valid" ;
			}
		}
		
	}
}
