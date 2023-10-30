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
                    <img src="/img/<?= empty( $product['avatar'] ) ? 'no-image.jpg' : $product['avatar'] ?>"  
                        style="height:150px">
                    </div>
                    <div class="card-content">
                    <span class="card-title" 
                          title="<?= $product['title'] . "\n" . $product['description'] ?>"
                          style="font-size:1.2vw;height: 32px;overflow: hidden;white-space: nowrap;text-overflow: ellipsis;"
                    ><?= $product['title'] ?></span>

                    <p>Price:
                        <?php if( empty( $product['discount'] ) ) { ?>
                            <b><?= $product['price'] ?></b>
                        <?php } else { ?>
                            <s><?= $product['price'] ?></s>
                            <b title="<?= $product['action_title'] . "\n" . $product['action_description'] ?>"
                            ><?= round( $product['price'] * (1 - $product['discount'] / 100), 2 ) ?></b>
                        <?php } ?>
                    </p>
                    </div>
                    <div class="card-action right-align">
                        <?php if( $_CONTEXT[ 'admin_mode' ] ) : ?>
                            <a href="?admin-edit=<?=$product['id']?>"><i class="material-icons">edit_note</i></a>
                            <a onclick="adminDelete('<?=$product['id']?>')"><i class="material-icons">delete_forever</i></a>
                        <?php else : ?>
                            <i class="material-icons">visibility</i> 
                            <i style='display:inline-block;vertical-align:top;margin-right:20px'>123</i>
                            <a href="#"><i class="material-icons">shopping_cart</i></a>
                        <?php endif ?>
                        
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
        <?php if( $_CONTEXT[ 'admin_mode' ] ) :
            $is_edit = isset( $edit_product ) ; ?>
            <div class="card">
              <form id="add-form" method='post' enctype='multipart/form-data'>
                <div class="card-content">
                    <span class="card-title">
                        <?= empty( $edit_product ) ? 'Додавання товару' : 'Редагування товару' ?>
                    </span>
                    
                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">shopping_cart</i>
                            <input name="title" id="add-title" type="text" class="validate"
                                value="<?= $is_edit ? $edit_product['title'] : '' ?>">
                            <label for="add-title">Назва товару</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix">list_alt</i>
                            <select name="group">
                                <option value="" disabled selected>Оберіть товарну групу</option>
                                <?php foreach( $product_groups as $product_group ) : ?>
                                    <option value="<?=$product_group['id']?>"
                                         <?= $is_edit ? ($edit_product['id_group'] == $product_group['id'] ? 'selected' : '') : '' ?>><?= $product_group['title'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <label>Група товарів</label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">receipt_long</i>
                            <input name="description" id="add-description" type="text" class="validate"
                                    value="<?= $is_edit ? $edit_product['description'] : '' ?>">
                            <label for="add-description">Опис товару</label>
                        </div>
                        <div class="col s6">
                            <div style="display:flex;justify-content: space-evenly;align-items: center;">
                                <?php if( $is_edit ) : ?>
                                    <img src="/img/<?= empty( $edit_product['avatar'] ) ? 'no-image.jpg' : $edit_product['avatar'] ?>"  
                                        style="height:50px">
                                <?php endif ?>

                                <div class="file-field input-field" style="width:100%">
                                    <div class="btn orange">
                                        <span>File</span>
                                        <input name="avatar" type="file">
                                    </div>
                                    <div class="file-path-wrapper">
                                        <input class="file-path validate" type="text">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="input-field col s6">
                            <i class="material-icons prefix">money</i>
                            <input name="price" id="add-price" type="number" step="0.01" class="validate"
                                    value="<?= $is_edit ? $edit_product['price'] : '' ?>">
                            <label for="add-price">Ціна товару</label>
                        </div>
                        <div class="input-field col s6">
                            <i class="material-icons prefix">percent</i>
                            <select name="action">
                                <option value="" disabled selected>Виберіть акцію</option>
                                <?php foreach( $product_actions as $product_action ) : ?>
                                    <option value="<?=$product_action['id']?>"
                                        <?= $is_edit ? ( $edit_product['id_action'] != NULL && $edit_product['id_action'] == $product_action['id'] ? 'selected' : '' ) : '' ?> 
                                    ><?= $product_action['title'] ?> (<?= $product_action['discount'] ?>%)</option>
                                <?php endforeach ?>
                            </select>
                            <label>Участь в акції</label>
                        </div>
                    </div>
                    <?php if( $is_edit ) : ?>
                        <input type="hidden" name="edit-id" value="<?=$edit_product['id']?>"/>
                    <?php endif ?>
                </div>
              </form>

                <div class="card-action right-align">
                    <button id="add-product-button" class="btn orange"><?= $is_edit ? 'Зберігти' : 'Додати до БД' ?></button>
                </div>
            </div>
        <?php endif ?>
    </div> 
</div>
