<?php

$uri = $_SERVER[ 'REQUEST_URI' ] ;  // адреса запиту

// перевіряємо, чи запит є файлом (запит на файл)
$path = ".$uri" ;
if( $uri != '/' && is_readable( $path ) ) {
	// такий файл існує 
	// з деякими файлами можуть проблеми, якщо не зазначити Content-Type
	// зокрема, з CSS-файлами (стилями). Визначаємо тип (розширення) файлу
	$extension = pathinfo( $path, PATHINFO_EXTENSION ) ;
	// та з нього - Content-Type
	unset( $content_type ) ;
	switch( $extension ) {
		case 'css' : 
			$content_type = 'text/css' ; 
			break ;
		case 'jpg' : $extension = 'jpeg' ;
		case 'jpeg': 
		case 'png' : 
			$content_type = "image/{$extension}" ; 
			break ; 
		case 'js'  : 
			$content_type = 'text/javascript' ; 
			break ;
	}
	if( isset( $content_type ) ) {
		header( "Content-Type: $content_type" ) ;
		readfile( $path ) ;  // передаємо файл у відповідь
	}
	else {
		http_response_code( 403 ) ;  // Forbidden - не дозволено
	}
	exit ;
}

$router_layout = [  // масив у РНР створюється [] або array()
	'/index' => 'index.php',   // масиви - асоціативні (схожі на об'єкти JS)
	'/'      => 'index.php',
	'/about' => 'about.php',	
] ;
$router_direct = [  // контролери - самі визначають відображення
	'/forms' => 'forms_controller.php',
	'/auth'  => 'auth_controller.php',
] ;
$router_layout[ '/db' ] = 'db.php' ;  // доповнення масиву новим елементом

$uri_parts = explode( '?', $uri ) ;
unset( $included_file ) ;
if( isset( $router_layout[ $uri_parts[0] ] ) ) {
	$page =  // змінні локалізуються тільки у функціях, оголошена поза функцією змінна доступна скрізь, у т.ч. в іншому файлі
			$router_layout[ $uri_parts[0] ] ;  // у РНР оператор "+" діє тільки на числа, для рядків - оператор "."
	$included_file = '_layout.php' ;  // перехід до інструкцій в іншому файлі
}
else if( isset( $router_direct[ $uri_parts[0] ] ) ) {
	$included_file = $router_direct[ $uri_parts[0] ] ;  // без шаблону - на файл
}
else {
	echo 'access manager - 404' ;
	exit ;
}

// Підключення до БД - потрібно на всіх сторінках, змінна $db буде доступна у всіх файлах
$db = new PDO(
		"mysql:host=localhost;dbname=pv111;charset=UTF8", 
		"pv111_user", 
		"pv111_pass"
	);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC) ;
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION) ;
$db->setAttribute(PDO::ATTR_PERSISTENT, true) ;

$_CONTEXT = [   // наш масив, який буде слугувати для "глобальних" змінних та служб
	'db' => $db
] ;

session_start() ;
if( isset( $_SESSION[ 'auth-user-id' ] ) ) {  // є дані авторизації
	// вилучаємо з БД відомості про авторизованого користувача
	$sql = "SELECT u.* FROM users u WHERE u.id = ?" ;
	try {
		$prep = $db->prepare( $sql ) ;
		$prep->execute( [ $_SESSION[ 'auth-user-id' ] ] ) ;
		$row = $prep->fetch() ;
	}
	catch( PDOException $ex ) {
		http_response_code( 500 ) ;
		echo "Server error - " . $ex->getMessage() ;
		exit ;
	}
	if( $row === false ) {  // у сесії неправильні дані
		unset( $_SESSION[ 'auth-user-id' ] ) ;
	}
	else {  // вкладаємо дані у контекст для доступності у подальшому коді
		$_CONTEXT[ 'user' ] = $row ;
	}
}
include $included_file ;


/* "Білий" перелік - перелік дозволених ресурсів (маршрутів, файлів, тощо)
Позитив - безпека
Негатив - кожен файл треба зазначати, у т.ч. картинки, стилі, скрипти і т.д.
Варіант рішення - перевіряти, чи є запит файлом, та у разі вірної відповіді
передавати цей файл до клієнта. Небезпека - можливість інжекції файлів з
їх автоматичною видачею сервером.

*/
