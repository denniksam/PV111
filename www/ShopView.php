<?php
    // Визначаємо максимальну та мінімальну ціни з наявної вибірки
    if( is_array( $products ) && count( $products ) > 0 ) {
        $max_price = $products[0]['price'] ;
        $min_price = $max_price ;
        foreach( $products as $product ) {
            if( $product['price'] > $max_price ) {
                $max_price = $product['price'] ;
            }
            if( $product['price'] < $min_price ) {
                $min_price = $product['price'] ;
            }
        }
    }
    
?>
<div class="row">
    <div class="col s2">
        <h4>Групи товарів</h4>
        <div class="collection">
            <a href="?grp=all" class="collection-item">Усі</a>
            <?php foreach( $product_groups as $product_group ) : ?>
                <a href="?grp=<?=$product_group['url']?>" class="collection-item"><?= $product_group['title'] ?></a>
            <?php endforeach ?>
        </div>
        <h4>За ціною</h4>
        <span>від</span> <input type="number" value="<?= $min_price ?>" id="min-price-input" />
        <span>до</span>  <input type="number" value="<?= $max_price ?>" id="max-price-input" />
        <div class="row right-align">
            <button id="price-filter-button" title="Показати з встановленним обмеженням" class="waves-effect waves-light btn orange"><i class="material-icons">savings</i></button>
        </div>
    </div>

    <div class="col s10">
        <div class="row">
            <?php foreach( $products as $product ) : ?>
                <div class="col" style='width: 200px; height: 340px;'>
                <div class="card">
                    <div class="card-image">
                    <img src="/img/<?= $product['avatar'] ?>"  
                        style="height:150px">
                    </div>
                    <div class="card-content">
                    <span class="card-title" title="<?= $product['title'] ?>"
                    style="font-size:1.2vw;height: 32px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;"><?= $product['title'] ?></span>
                    <p><?= $product['description'] ?></p>
                    <p><b>Price: <?= $product['price'] ?></b></p>
                    </div>
                    <div class="card-action right-align">            
                        <i class="material-icons">visibility</i> 
                        <i style='display:inline-block;vertical-align:top;margin-right:20px'>123</i>
                    <a href="#"><i class="material-icons">shopping_cart</i></a>
                    </div>
                </div>
                </div>
            <?php endforeach ?>
        </div>
    </div> 
</div>
