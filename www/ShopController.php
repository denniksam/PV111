<?php

include_once "ApiController.php" ;

class ShopController extends ApiController {

	protected function do_get() {
		global $_CONTEXT ;
		$page =  'ShopView.php' ;
		include '_layout.php' ;
	}
}
/*
Д.З. Перевести у режим роботи з контролерами сторінки index та about
Зверстати картку для відображення товару, зазначивши необхідні дані
(назву, ціну, за наявності - знижку, картинку, ....)
*/
