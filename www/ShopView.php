<div class="row">
    <div class="col s2">
        <h4>Групи товарів</h4>
        <div class="collection">
            <a href="?grp=all" class="collection-item">Усі</a>
            <?php foreach( $product_groups as $product_group ) : ?>
                <a href="?grp=<?=$product_group['id']?>" class="collection-item"><?= $product_group['title'] ?></a>
            <?php endforeach ?>
        </div>
    </div>
    <div class="col s10">
        <div class="row">
            <?php foreach( $products as $product ) : ?>
                <div class="col s6 m3">
                <div class="card">
                    <div class="card-image">
                    <img src="/img/<?= $product['avatar'] ?>"  style="max-height:150px">
                    </div>
                    <div class="card-content">
                    <span class="card-title" style="font-size:1.2vw"><?= $product['title'] ?></span>
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
