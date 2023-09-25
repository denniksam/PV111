<?php
$name_class = "validate" ;
$reg_name = "" ;
$lastname_class = "validate" ;
$reg_lastname = "" ;
if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) { 
	// оброблення даних форми
	// echo '<pre>' ; print_r( $_POST ) ; exit ;
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
	
	
	
	
	session_start() ;   // включення сесії
	// після включення сесії стає доступним $_SESSION
	$_SESSION[ 'form_data' ] = true ;
	$_SESSION[ 'reg_name'  ] = $reg_name ;
	$_SESSION[ 'reg_lastname'  ] = $reg_lastname ;
	if( isset( $name_message ) ) { 
		$_SESSION[ 'name_message' ] = $name_message ;
	}
	if( isset( $lastname_message ) ) { 
		$_SESSION[ 'lastname_message' ] = $lastname_message ;
	}
	
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
// змінна $db встановлюється у диспетчері доступу і доступна тут
if( empty( $db ) ) {
	echo 'Server error' ;
	exit ;
}
$salt = substr( md5( uniqid() ), 0, 16 ) ;
$dk = sha1( $salt . md5( $_POST[ 'reg-phone' ] ) ) ;
$email = empty( $_POST[ 'reg-email' ] ) 
	? "NULL" 
	: "'{$_POST['reg-email']}'" ;
	
$avatar = empty( $uniqueFileName ) 
	? "NULL" 
	: "'{$uniqueFileName}'" ;
	
$sql = <<<TXT
INSERT INTO users(`id`,`login`,`salt`,`pass_dk`,`name`,`email`,`avatar`)
VALUES( UUID_SHORT(), '{$reg_lastname}', '{$salt}', '{$dk}', 
		'{$reg_name}', {$email}, {$avatar} )	
TXT;
try {
	$db->query( $sql ) ;
	$_SESSION[ 'reg_db'  ] = true ;
}
catch( PDOException $ex ) {
	$_SESSION[ 'reg_db'  ] = $ex->getMessage() ;
}
// echo $sql ; exit;	
	// echo '<pre>' ; print_r( $_FILES ) ; exit ;
	/*
	Array
	(
		[reg-avatar] => Array
			(
				[name] => php.png
				[type] => image/png
				[tmp_name] => C:\xampp\tmp\php1ED6.tmp
				[error] => 0
				[size] => 201607
			)
	)
	*/
	header( 'Location: ' . $_SERVER[ 'REQUEST_URI' ] ) ;
	exit ;
}
else {  // запит методом GET
	// перевіряємо, чи є дані у сесії
	session_start() ;   // включення сесії
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
	$page = "forms.php" ; 
	include '_layout.php' ;   // return View
}
/* Задачі: 
1. для input class може приймати одне з трьох значень:
	"validate" - немає попередніх даних (заповнення форми вперше)
	"invalid" - є дані і вони некоректні
	"valid" - є коректні дані
   TODO: скласти вираз для перемикання цих значень.
2. якщо є передані дані то їх бажано відновити у полях форми   
Д.З. Виконати ці задачі для всіх полів форми
*/