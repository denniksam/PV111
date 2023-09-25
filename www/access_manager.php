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

// Підключення до БД - потрібно на всіх сторінках, змінна $db буде доступна у всіх файлах
$db = new PDO(
		"mysql:host=localhost;dbname=pv111;charset=UTF8", 
		"pv111_user", 
		"pv111_pass"
	);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC) ;
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION) ;
$db->setAttribute(PDO::ATTR_PERSISTENT, true) ;

$uri_parts = explode( '?', $uri ) ;

if( isset( $router_layout[ $uri_parts[0] ] ) ) {
	$page =  // змінні локалізуються тільки у функціях, оголошена поза функцією змінна доступна скрізь, у т.ч. в іншому файлі
			$router_layout[ $uri_parts[0] ] ;  // у РНР оператор "+" діє тільки на числа, для рядків - оператор "."
	include '_layout.php' ;  // перехід до інструкцій в іншому файлі
}
else if( isset( $router_direct[ $uri_parts[0] ] ) ) {
	include $router_direct[ $uri_parts[0] ] ;  // без шаблону - на файл
}
else {
	echo 'access manager - 404' ;
}

/* "Білий" перелік - перелік дозволених ресурсів (маршрутів, файлів, тощо)
Позитив - безпека
Негатив - кожен файл треба зазначати, у т.ч. картинки, стилі, скрипти і т.д.
Варіант рішення - перевіряти, чи є запит файлом, та у разі вірної відповіді
передавати цей файл до клієнта. Небезпека - можливість інжекції файлів з
їх автоматичною видачею сервером.

*/
