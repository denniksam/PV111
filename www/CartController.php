<?php

include_once "ApiController.php" ;

class CartController extends ApiController {

	protected function do_get() {
		global $_CONTEXT ;
		if( isset( $_CONTEXT[ 'user' ] ) ) {
            $id_user = $_CONTEXT[ 'user' ][ 'id' ] ;
            $db = $this->get_db();
            $sql = "SELECT * FROM shop_cart_order WHERE `id_user` = {$id_user}
                    AND `order_dt` IS NULL AND `delete_dt` IS NULL" ;
            try {
                $res = $db->query( $sql )->fetch() ;
                $_CONTEXT[ 'cart' ] = $res ;
            }
            catch( PDOException $ex ) {
                $this->log_error( __METHOD__ . "#" . __LINE__ . $ex->getMessage() . " {$sql}" ) ;
                $this->send_error( 500 ) ;
            }
        }	
		
		$page =  'CartView.php' ;
		include '_layout.php' ;
	}

    protected function do_post() {
		global $_CONTEXT ;
        $db = $this->get_db();
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
