<?php

include_once "ApiController.php" ;

class ShopController extends ApiController {

	protected function do_get() {
		global $_CONTEXT ;
		$per_page = 4 ;   // кількість даних на одній сторінці

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
		// Особливість пагінації - запит на вибірку не містить даних про загальний
		// розмір даних. Для їх одержання потрібен додатковий запит, але з тими ж
		// умовами (фільтрами), що й основний запит
		$sql = "SELECT COUNT(*) FROM products {$where}" ;
		try {
			$cnt = $db->query( $sql )->fetch( PDO::FETCH_NUM )[ 0 ] ;
		}
		catch( PDOException $ex ) {
			$this->log_error( __METHOD__ . "#" . __LINE__ . $ex->getMessage() . " {$sql}" ) ;
			$this->send_error( 500 ) ;
		}
		// $cnt - загальна кількість по вибірці, розраховуємо кількість сторінок
		// 8(2) 9(3) 10(3) 11(3) 12(3) 13(4)
		$last_page = $cnt == 0 ? 1 : ceil( $cnt / $per_page ) ;
		// визначаємо поточну сторінку
		$current_page = isset($_GET['page']) ? intval( $_GET['page'] ) : 1 ;
		if( $current_page <= 0 ) $current_page = 1 ;
		if( $current_page > $last_page ) $current_page = $last_page ;
		// визначаємо $skip - кількість пропусків у вибірці для даної сторінки
		// 1(0x4), 2(1x4), 3(2x4)
		$skip = ($current_page - 1) * $per_page ;

		$sql = "SELECT * FROM products {$where} LIMIT {$skip}, {$per_page}" ;
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

		$sql = "SELECT * FROM product_actions" ;
		try {
			$ans = $db->query( $sql ) ;
			$product_actions = $ans->fetchAll() ;
		}
		catch( PDOException $ex ) {
			$this->log_error( __METHOD__ . "#" . __LINE__ . $ex->getMessage() . " {$sql}" ) ;
			$this->send_error( 500 ) ;
		}

		$page =  'ShopView.php' ;
		include '_layout.php' ;
	}

	protected function do_post() {
		// echo '<pre>' ; print_r( $_POST ) ; print_r( $_FILES ) ; exit ;
		if( $_FILES['avatar']['error'] == 0 && $_FILES['avatar']['size'] != 0 ) {
			$ext = pathinfo( $_FILES['avatar']['name'], PATHINFO_EXTENSION ) ;
			// TODO: перевірити на допустимість розширення (типу файла)
			$avatar = uniqid() . ".$ext" ;
			move_uploaded_file(
				$_FILES['avatar']['tmp_name'],
				"./img/$avatar"
			);
		}
		else {
			$avatar = null ;
		}
		if( ! empty( $_POST['action'] ) ) {
			$action_id = $_POST['action'] ;
		}
		else {
			$action_id = null ;
		}
		$db = $this->get_db() ;
		$sql = "INSERT INTO products (id,title,`description`,id_group,avatar,price,`id_action`)
		VALUES( UUID_SHORT(), ?, ?, ?, ?, ?, ? )" ;
		try {
			$prep = $db->prepare( $sql ) ;
			$prep->execute( [ 
				$_POST['title'], 
				$_POST['description'],  
				$_POST['group'],
				$avatar,
				$_POST['price'],
				$action_id,
			] ) ;
			http_response_code( 201 ) ;  // Created
			echo 'ADD OK' ;
		}
		catch( PDOException $ex ) {
			http_response_code( 500 ) ;
			echo $ex->getMessage() ;
			$this->log_error( __METHOD__ . "#" . __LINE__ . $ex->getMessage() . " {$sql}" ) ;
			// $this->send_error( 500 ) ;
		}
	}
}
/*
Д.З. Реалізувати валідацію форми додавання нового товару ДО надсилання
(заповнення необхідних полів у т.ч. файлового).
Забезпечити очищення даних полів форми у випадку успішного додавання товару. 
* Помічати поля, що проходять/не проходять валідацію, відповідним стилем
*/
