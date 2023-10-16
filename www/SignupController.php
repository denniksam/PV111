<?php

include_once "ApiController.php" ;

class SignupController extends ApiController {
	
	protected function do_get() {
		global $_CONTEXT ;
		$view_data = [
			'db-message' => false,
			'reg-name' => [
				'class' => 'validate',
				'value' => '',
				'error' => false
			],
			'reg-lastname' => [
				'class' => 'validate',
				'value' => '',
				'error' => false
			],
		] ;
		$this->check_session( $view_data ) ;
		$page = "SignupView.php" ; 
		include '_layout.php' ; 
	}
	
	protected function do_post() {
		// етап 1 - валідація
		if( ! isset( $_POST[ 'reg-name' ] ) ) {  // наявність самих даних
			$name_message = "No reg-name field" ;
		}
		else {
			$reg_name = $_POST[ 'reg-name' ] ;
			if( strlen( $reg_name ) < 2 ) {
				$name_message = "Name too short" ;
			}
		}
	
		if( ! isset( $_POST[ 'reg-lastname' ] ) ) {  // наявність самих даних
			$lastname_message = "No reg-lastname field" ;
		}
		else {
			$reg_lastname = $_POST[ 'reg-lastname' ] ;
			if( strlen( $reg_name ) < 2 ) {
				$lastname_message = "Login too short" ;
			}
		}
		$_SESSION[ 'form_data' ] = true ;
		$_SESSION[ 'reg_name'  ] = $reg_name ;
		$_SESSION[ 'reg_lastname'  ] = $reg_lastname ;
		
		// перевіряємо чи є передані файли
		if( isset( $_FILES[ 'reg-avatar' ] ) ) {
			if( $_FILES[ 'reg-avatar' ][ 'error' ] == 0 
			&& $_FILES[ 'reg-avatar' ][ 'size' ] > 0 ) {
				
				$uploadDir = "C:/Projects/Step/PHP/PV111/www/avatars" ;
				$extension = pathinfo( 
					$_FILES['reg-avatar']['name'], 
					PATHINFO_EXTENSION 
				) ;
				
				do {
					$uniqueFileName = uniqid() . '.' . $extension ;
					$uploadPath = "{$uploadDir}/{$uniqueFileName}";
				} while( file_exists( $uploadPath ) ) ;
				
				move_uploaded_file(
					$_FILES[ 'reg-avatar' ][ 'tmp_name' ],
					$uploadPath
				) ;
			}
		}

		$db = $this->get_db() ;
		// TODO: перевірити унікальність логіну
		$sql = "SELECT COUNT(*) FROM users u WHERE u.`login` = ?" ;
		try {
			$prep = $db->prepare( $sql ) ;
			$prep->execute( [ $reg_lastname ] ) ;
			$cnt = $prep->fetch( PDO::FETCH_NUM )[0] ;
			if( $cnt != 0 ) {
				$lastname_message = 'Login in use' ;
			}
		}
		catch( PDOException $ex ) {
			// TODO: log ex and return false
			$_SESSION[ 'reg_db'  ] = $ex->getMessage() ;
		}
		// Визначаємо загальний стан - пройдена валідація чи ні
		$is_valid = true ;
		if( isset( $name_message ) ) { 
			$_SESSION[ 'name_message' ] = $name_message ;
			$is_valid = false ;
		}
		if( isset( $lastname_message ) ) { 
			$_SESSION[ 'lastname_message' ] = $lastname_message ;
			$is_valid = false ;
		}
		if( $is_valid === false ) {
			header( 'Location: ' . $_SERVER[ 'REQUEST_URI' ] ) ;
			exit ;
		}
		// Валідація пройдена - додаємо користувача до БД
		$salt = substr( md5( uniqid() ), 0, 16 ) ;
		$dk = sha1( $salt . md5( $_POST[ 'reg-phone' ] ) ) ;
		$email = empty( $_POST[ 'reg-email' ] ) 
			? "NULL" 
			: "'{$_POST['reg-email']}'" ;
			
		$avatar = empty( $uniqueFileName ) 
			? "NULL" 
			: "'{$uniqueFileName}'" ;
	
		$sql = "
			INSERT INTO users(`id`,`login`,`salt`,`pass_dk`,`name`,`email`,`avatar`)
			VALUES( UUID_SHORT(), '{$reg_lastname}', '{$salt}', '{$dk}', 
			'{$reg_name}', {$email}, {$avatar} )" ;
		try {
			$db->query( $sql ) ;
			$_SESSION[ 'reg_db' ] = true ;
		}
		catch( PDOException $ex ) {
			// TODO: log ex and return false
			$_SESSION[ 'reg_db'  ] = $ex->getMessage() ;
		}
		header( 'Location: ' . $_SERVER[ 'REQUEST_URI' ] ) ;
		exit ;
	}
	
	private function check_session( &$view_data ) {
		if( isset( $_SESSION[ 'form_data' ] ) ) {
			// Загальна успішність:
			$view_data['db-message'] = 
				$_SESSION[ 'reg_db' ] !== true
				? $_SESSION[ 'reg_db' ] 
				: "INSERT OK" ;
			// є передача даних, перевіряємо повідомлення
			if( isset( $_SESSION[ 'name_message' ] ) ) { 
				$view_data['reg-name']['error'] = $_SESSION[ 'name_message' ] ;
				unset( $_SESSION[ 'name_message' ] ) ;
			}
			if( isset( $_SESSION[ 'lastname_message' ] ) ) { 
				$view_data['reg-lastname']['error'] = $_SESSION[ 'lastname_message' ] ;
				unset( $_SESSION[ 'lastname_message' ] ) ;
			}
			$view_data['reg-name']['value'] = $_SESSION[ 'reg_name'  ] ;
			$view_data['reg-lastname']['value'] = $_SESSION[ 'reg_lastname'  ] ;
			
			// видаляємо з сесії повідомленя про дані
			unset( $_SESSION[ 'form_data' ] ) ;

			$view_data['reg-name']['class'] = 
				$view_data['reg-name']['error'] === false
				? "valid"
				: "invalid" ;
			$view_data['reg-lastname']['class'] = 
				$view_data['reg-lastname']['error'] === false
				? "valid"
				: "invalid" ;				
		}
		
	}
}
