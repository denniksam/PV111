<h1>Кошик - ваші замовлення</h1>
<?php if( empty( $_CONTEXT[ 'user' ] ) ) : ?>
    <p>
        Кошик доступний тільки авторизованим користувачам. Увійдіть до системи.
    </p>
<?php else : 
    if( empty( $_CONTEXT[ 'cart' ] ) ) : ?>
    <p>
        Кошик порожній. Додавайте товари зі 
        <a href="/shop">сторінки магазину</a>
    </p>
    <?php else : ?>
        Тут буде кошик 
        Д.З. Реалізувати відображення даних, одержаних
        по "кошику" у вигляді HTML таблиці.
        <table>
            <thead>
            <tr>
                <th>№</th>
                <th>Назва</th>
                <th>Ціна за 1 шт</th>
                <th>Кількість</th>
                <th>Ціна за позицію</th>
            </tr>
        </thead>
        <tbody>
            <?php $cnt = 0; $sum = 0;
            foreach ($_CONTEXT['orders'] as $index => $item): 
                $cnt += $item['count']; 
                $sum += $item['price'] * $item['count']; ?>
                <tr>
                    <td><?= $index + 1     ?></td>
                    <td><?= $item['title'] ?></td>
                    <td><?= $item['price'] ?></td>
                    <td><?= $item['count'] ?></td>
                    <td><?= $item['price'] * $item['count'] ?></td>
                    <td>
                        <button data-cart-dec='<?= $item['id_product'] ?>'><i class="material-icons">do_not_disturb_on</i></button>
                        <button data-cart-inc='<?= $item['id_product'] ?>'><i class="material-icons">add_circle</i></button>
                        <button data-cart-del='<?= $item['id_product'] ?>'><i class="material-icons">delete_forever</i></button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan=3>Разом</th>
                <th><?= $cnt ?></th>
                <th><?= $sum ?></th>
            </tr>
        </tfoot>
        </table>
    <?php endif ?>
<?php endif ?>