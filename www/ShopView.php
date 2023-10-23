<?php
    // сторінка має надходити із запиту, а загальна кількість (last_page) - передаватись з контроллера
   

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
        <!-- Paginator -->
        <?= $current_page ?> / <?= $last_page ?>
        <ul class="pagination">
            <li class="<?= $current_page == 1 ? "disabled" : "waves-effect" ?>"><a href="#1"><i class="material-icons">chevron_left</i></a></li>
            <?php for( $i = 1; $i <= $last_page; $i += 1 ) : ?>
                <li class="<?= $current_page == $i ? "active" : "waves-effect" ?>">
                    <a href="#<?= $i ?>"><?= $i ?></a>
                </li>
            <?php endfor ?>
            <li class="waves-effect"><a href="#<?= $last_page ?>"><i class="material-icons">chevron_right</i></a></li>
        </ul>
        <!-- Admin Panel --> 
        <?php if( isset( $_CONTEXT[ 'user' ] ) && 
                $_CONTEXT[ 'user' ][ 'login' ] == 'admin' ) : ?>
            <div class="card">
                
              <form id="add-form">
                <div class="card-content">
                    <span class="card-title">Додавання товару</span>
                    
                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">shopping_cart</i>
                            <input id="add-title" type="text" class="validate">
                            <label for="add-title">Назва товару</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix">list_alt</i>
                            <select>
                                <option value="" disabled selected>Оберіть товарну групу</option>
                                <?php foreach( $product_groups as $product_group ) : ?>
                                    <option value="<?=$product_group['id']?>"><?= $product_group['title'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <label>Група товарів</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">receipt_long</i>
                            <input id="add-description" type="text" class="validate">
                            <label for="add-description">Опис товару</label>
                        </div>
                        <div class="col s6">
                            <div class="file-field input-field">
                                <div class="btn orange">
                                    <span>File</span>
                                    <input type="file">
                                </div>
                                <div class="file-path-wrapper">
                                    <input class="file-path validate" type="text">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">money</i>
                            <input id="add-price" type="number" step="0.01" class="validate">
                            <label for="add-price">Ціна товару</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix">percent</i>
                            <select>
                                <option value="" disabled selected>Виберіть акцію</option>
                                <option value="1">Option 1</option>
                                <option value="2">Option 2</option>
                                <option value="3">Option 3</option>
                            </select>
                            <label>Участь в акції</label>
                        </div>
                    </div>
                </div>
              </form>

                <div class="card-action right-align">
                    <a id="add-button" href="#" class="btn orange">Додати до БД</a>
                </div>
            </div>
        <?php endif ?>
    </div> 
</div>
