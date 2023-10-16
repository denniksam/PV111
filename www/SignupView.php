<?php if( $view_data['db-message'] ) : ?>
	<span> <?= $view_data['db-message'] ?> </span>
<?php endif ?>

<div class="row">
<form class="col s12" method="post" enctype="multipart/form-data">
  <div class="row">
	<div class="input-field col s6">
	  <i class="material-icons prefix">account_circle</i>
	  <input id="reg-name" name="reg-name" type="text" 
		class='<?= $view_data['reg-name']['class'] ?>' 
		value='<?= $view_data['reg-name']['value'] ?>' />
	  <label for="reg-name">Name</label>
	  <?php if( $view_data['reg-name']['error'] ) : ?>
		<span class="helper-text" 
			  data-error="<?= $view_data['reg-name']['error'] ?>"
		></span>
	  <?php endif ?>	
	</div>

	<div class="input-field col s6">
	  <i class="material-icons prefix">badge</i>
	  <input id="reg-lastname" name="reg-lastname" type="text" 
		class='<?= $view_data['reg-lastname']['class'] ?>' 
		value='<?= $view_data['reg-lastname']['value'] ?>'>
	  <label for="reg-lastname">Login</label>
	  <?php if( $view_data['reg-lastname']['error'] ) : ?>
		<span class="helper-text" 
			  data-error="<?= $view_data['reg-lastname']['error'] ?>"
		></span>
	  <?php endif ?>	
	</div>
  </div>
  <div class="row">
	<div class="input-field col s6">
	  <i class="material-icons prefix">mark_email_unread</i>
	  <input id="reg-email" name="reg-email" type="email" class="validate">
	  <label for="reg-email">Email</label>
	</div>
	<div class="input-field col s6">
	  <i class="material-icons prefix">pin</i>
	  <input id="reg-phone" name="reg-phone" type="password" class="validate">
	  <label for="reg-phone">Password</label>
	</div>
  </div>
  <div class="row">
    <div class="file-field input-field  col s6">
      <div class="btn orange darken-1">
        <span>File</span>
        <input type="file" name="reg-avatar" />
      </div>
      <div class="file-path-wrapper">
        <input class="file-path validate" type="text" placeholder="Виберіть аватарку">
      </div>
    </div>
  </div>
  <div class="row center-align">
	<button class="waves-effect waves-light btn orange darken-3">
		<i class="material-icons right">how_to_reg</i>Register
	</button>
  </div>
</form>
</div>
<p>
Особливості роботи з формами полягають у тому, що оновлення
сторінки можи привести до повторної передачі даних. У разі
POST запиту про це видається попередження, у разі GET - повтор
автоматичний. Рекомендовано роботу з формами розділяти на 
два етапи: 1) прийом і оброблення даних та 2) відображення.
Між цими етапами сервер передає браузеру редирект і зберігає
дані у сесії. При повторному запиті дані відновлюються і 
відображаються.
</p>
