<?php

include_once "ApiController.php" ;

class ShopController extends ApiController {

	protected function do_get() {
		global $_CONTEXT ;
		$db = $this->get_db();
		// if($_GET['grp'])....
		$sql = "SELECT * FROM products" ;
		try {
			$ans = $db->query( $sql ) ;
			$products = $ans->fetchAll() ;
		}
		catch( PDOException $ex ) {
			$this->log_error( __METHOD__ . "#" . __LINE__ . $ex->getMessage() . " {$sql}" ) ;
			$this->send_error( 500 ) ;
		}

		$sql = "SELECT * FROM product_groups" ;
		try {
			$ans = $db->query( $sql ) ;
			$product_groups = $ans->fetchAll() ;
		}
		catch( PDOException $ex ) {
			$this->log_error( __METHOD__ . "#" . __LINE__ . $ex->getMessage() . " {$sql}" ) ;
			$this->send_error( 500 ) ;
		}


		$page =  'ShopView.php' ;
		include '_layout.php' ;
	}
}
/*
Д.З. Перевести у режим роботи з контролерами сторінки index та about
Зверстати картку для відображення товару, зазначивши необхідні дані
(назву, ціну, за наявності - знижку, картинку, ....)
*/
