<?php

include_once "ApiController.php" ;

class CartController extends ApiController {

    private function get_active_card( $id_user ) {
        $db = $this->get_db();
        $sql = "SELECT * FROM shop_cart_order WHERE `id_user` = {$id_user}
                AND `order_dt` IS NULL AND `delete_dt` IS NULL" ;
        try {
            return $db->query( $sql )->fetch() ;            
        }
        catch( PDOException $ex ) {
            $this->log_error( __METHOD__ . "#" . __LINE__ . $ex->getMessage() . " {$sql}" ) ;
            $this->send_error( 500 ) ;
        }
    }

	protected function do_get() {
		global $_CONTEXT ;
		if( isset( $_CONTEXT[ 'user' ] ) ) {
            $cart = $this->get_active_card( $_CONTEXT[ 'user' ][ 'id' ] ) ;            
            $_CONTEXT[ 'cart' ] = $cart;
            if( ! empty( $cart ) ) {
                // Одержуємо всі позиції по замовленню
                $sql = "SELECT i.*, p.`title`, p.`price`
                FROM shop_cart_item i JOIN products p ON i.`id_product` = p.`id`
                WHERE id_cart = {$cart['id']}" ;
                try {
                    $_CONTEXT[ 'orders' ] = $this->get_db()->query($sql)->fetchAll() ;
                }
                catch( PDOException $ex ) {
                    $this->log_error( __METHOD__ . "#" . __LINE__ . $ex->getMessage() . " {$sql}" ) ;
                    $this->send_error( 500 ) ;
                }
            }
        }	
		
		$page =  'CartView.php' ;
		include '_layout.php' ;
	}
    protected function do_put() {
		global $_CONTEXT ;
        if( empty( $_GET[ 'id-product' ] ) ) {
            $this->send_error( 400, "'id-product' parameter required" ) ;
        }
        $id_product = $_GET[ 'id-product' ] ;

        $db = $this->get_db();
        if( empty( $_CONTEXT[ 'user' ] ) ) {
            $this->send_error( 401 ) ;
        }
        $id_user = $_CONTEXT[ 'user' ][ 'id' ] ;

        $cart = $this->get_active_card( $id_user ) ;
        if( empty( $cart ) ) {
            $this->send_error( 409, "Cart is empty" ) ;   // Conflict
        }
        $sql = "SELECT SUM(`count`) FROM shop_cart_item WHERE `id_cart`={$cart['id']} AND `id_product`={$id_product}";
        try {
            $cnt = $db->query( $sql )->fetch( PDO::FETCH_NUM )[0] ;
        }
        catch( PDOException $ex ) {
            $this->log_error( __METHOD__ . "#" . __LINE__ . $ex->getMessage() ) ;
            $this->send_error( 500 ) ;
        }
        
        if( $cnt == 0 ) {
            $this->send_error( 409, "Product not in cart" ) ;   // Conflict
        }
        else if( $cnt > 1 ) {
            $sql = "UPDATE shop_cart_item SET `count` = `count` - 1 WHERE
            `id_cart` = {$cart['id']} AND `id_product` = {$id_product}" ;
        }
        else {
            $sql = "DELETE FROM shop_cart_item WHERE
            `id_cart` = {$cart['id']} AND `id_product` = {$id_product} ";
        }
        try {
            $cnt = $db->query( $sql ) ;
        }
        catch( PDOException $ex ) {
            $this->log_error( __METHOD__ . "#" . __LINE__ . $ex->getMessage() ) ;
            $this->send_error( 500 ) ;
        }
        http_response_code( 202 ) ;
    }

    protected function do_post() {
		global $_CONTEXT ;
        if( empty( $_GET[ 'id-product' ] ) ) {
            $this->send_error( 400, "'id-product' parameter required" ) ;
        }
        $id_product = $_GET[ 'id-product' ] ;

        $db = $this->get_db();
        if( empty( $_CONTEXT[ 'user' ] ) ) {
            $this->send_error( 401 ) ;
        }
        $id_user = $_CONTEXT[ 'user' ][ 'id' ] ;

        $cart = $this->get_active_card( $id_user ) ;
        if( empty( $cart ) ) {  // немає кошику - створюємо його 
            /* Задача: створити order, одержати його id 
                і з цим id та id-product сформувати item.
               Проблема: як дізнатись id створеного order
               Рішення: id формується у коді (а не у БД)
               Нова пробема: як згенерувати id, якого гарантовано немає у БД?
               Рішення: запитати його у самої БД
               Підсумок: 
                - запитуємо id у БД
                - створюємо з ним order
                - з ним же + id-product створюємо item  */
            try {
                $id_order = $this->get_db_identity() ;
                $db->query( "INSERT INTO shop_cart_order(`id`, `id_user`)
                    VALUES( {$id_order}, {$id_user} ) " ) ;
                $db->query( "INSERT INTO shop_cart_item(`id_cart`, `id_product`)
                    VALUES( {$id_order}, {$id_product} ) " ) ;
                $status = 201 ;  // Created
            }
            catch( PDOException $ex ) {
                $this->log_error( __METHOD__ . "#" . __LINE__ . $ex->getMessage() ) ;
                $this->send_error( 500 ) ;
            }
        }
        else {  // є кошик
            // перевіряємо чи є такий товар у кошику
            $sql = "SELECT COUNT(*) FROM shop_cart_item WHERE `id_cart`={$cart['id']} AND `id_product`={$id_product}";
            try {
                $cnt = $db->query( $sql )->fetch( PDO::FETCH_NUM )[0] ;
            }
            catch( PDOException $ex ) {
                $this->log_error( __METHOD__ . "#" . __LINE__ . $ex->getMessage() ) ;
                $this->send_error( 500 ) ;
            }
            if( $cnt == 0 ) {  // немає такого товару -- додаємо
                $sql = "INSERT INTO shop_cart_item(`id_cart`, `id_product`)
                    VALUES( {$cart['id']}, {$id_product} ) " ;
                $status = 201 ;  // Created
            }
            else {  // є такий товар -- оновлюємо кількість
                $sql = "UPDATE shop_cart_item SET `count` = `count` + 1 WHERE
                `id_cart` = {$cart['id']} AND `id_product` = {$id_product}" ;
                $status = 202 ;  // Accepted
            }
            try {
                $cnt = $db->query( $sql ) ;
            }
            catch( PDOException $ex ) {
                $this->log_error( __METHOD__ . "#" . __LINE__ . $ex->getMessage() ) ;
                $this->send_error( 500 ) ;
            }
            http_response_code( $status ) ;
        }
    }
    // Повертає id, що гарантовано є вільним
    private function get_db_identity() {
        return $this
            ->get_db()
            ->query("SELECT UUID_SHORT()")
            ->fetch( PDO::FETCH_NUM )
            [0] ;
    }

}
/*
Кошик (замовлення товарів) -- Cart
1. Нова сторінка чи частина іншої? -- Нова, занадто багато відмінностей
2. Створюємо та реєструємо новий контролер
3. Структура даних
4. Головне представлення (View)
 - кошик активується зі сторінки товарів. Якщо є активний кошик, 
    то товар додається до нього, якщо ні - створюється новий та
    стає активним. Сторіка "кошик" при відсутності активного кошику
    порожня (повідомляє про відсутність)
5. Зв'язок зі сторінки "магазин" - клік по іконці кошику додає товар 
 - метод do_post у CartController
 - обробник натиску в ShopView

CREATE TABLE shop_cart_order (
    `id`          BIGINT PRIMARY KEY  DEFAULT UUID_SHORT(),
    `id_user`     BIGINT NOT NULL,
    `create_dt`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `order_dt`    DATETIME NULL,
    `delete_dt`   DATETIME NULL

) ENGINE = InnoDB, DEFAULT CHARSET = UTF8;

CREATE TABLE shop_cart_item (
    `id`          BIGINT PRIMARY KEY  DEFAULT UUID_SHORT(),
    `id_cart`     BIGINT NOT NULL,
    `id_product`  BIGINT NOT NULL,
    `count`       INT    NOT NULL DEFAULT 1
) ENGINE = InnoDB, DEFAULT CHARSET = UTF8;

*/
