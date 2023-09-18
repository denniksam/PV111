<?php

$uri = $_SERVER[ 'REQUEST_URI' ] ;  // адреса запиту
$router_layout = [  // масив у РНР створюється [] або array()
	'/index' => 'index.php',   // масиви - асоціативні (схожі на об'єкти JS)
	'/'      => 'index.php',
	'/about' => 'about.php',	
] ;
$router_direct = [  // контролери - самі визначають відображення
	'/forms' => 'forms_controller.php',
] ;
$router_layout[ '/db' ] = 'db.php' ;  // доповнення масиву новим елементом

if( isset( $router_layout[$uri] ) ) {
	$page =  // змінні локалізуються тільки у функціях, оголошена поза функцією змінна доступна скрізь, у т.ч. в іншому файлі
			$router_layout[$uri] ;  // у РНР оператор "+" діє тільки на числа, для рядків - оператор "."
	include '_layout.php' ;  // перехід до інструкцій в іншому файлі
}
else if( isset( $router_direct[$uri] ) ) {
	include $router_direct[$uri] ;  // без шаблону - на файл
}
else {
	echo 'access manager - 404' ;
}

