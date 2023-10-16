<?php

include_once "ApiController.php" ;

class ShopController extends ApiController {

	protected function do_get() {
		global $_CONTEXT ;
		$db = $this->get_db();
		$where = "" ;
		if( isset( $_GET['grp'] ) && $_GET['grp'] != "all" ) {
			$sql = "SELECT id FROM product_groups WHERE id = :grp OR url = :grp" ;
			try {
				$prep = $db->prepare( $sql ) ;
				$prep->bindParam( ':grp', $_GET['grp'] ) ;
				$prep->execute() ;
				$row = $prep->fetch() ;
				// var_dump( $row ) ; exit ;
			}
			catch( PDOException $ex ) {
				$this->log_error( __METHOD__ . "#" . __LINE__ . $ex->getMessage() . " {$sql}" ) ;
				$this->send_error( 500 ) ;
			}
			if( $row === false ) {
				$where = "WHERE NULL" ;
			}
			else {
				$where = "WHERE id_group = {$row['id']}" ;
			}
		}
		if( isset( $_GET['min-price'] ) && is_numeric( $_GET['min-price'] ) ) {
			$cond = " price >= " . $_GET['min-price'] ;
			if( $where == "" ) {
				$where = "WHERE {$cond}" ;
			}
			else {
				$where .= " AND {$cond}" ;
			}
		}
		if( isset( $_GET['max-price'] ) && is_numeric( $_GET['max-price'] ) ) {
			$cond = " price <= " . $_GET['max-price'] ;
			if( $where == "" ) {
				$where = "WHERE {$cond}" ;
			}
			else {
				$where .= " AND {$cond}" ;
			}
		}
		$sql = "SELECT * FROM products {$where}" ;
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
