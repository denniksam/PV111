
<div class="row">
<form class="col s12" method="post" enctype="multipart/form-data">
  <div class="row">
	<div class="input-field col s6">
	  <i class="material-icons prefix">account_circle</i>
	  <input id="reg-name" name="reg-name" type="text" 
		class='<?= $name_class ?>' value='<?= $reg_name ?>'>
	  <label for="reg-name">First Name</label>
	  <?php if( isset( $name_message ) ) : ?>
		<span class="helper-text" data-error="<?= $name_message ?>"></span>
	  <?php endif ?>	
	</div>
	<div class="input-field col s6">
	  <i class="material-icons prefix">badge</i>
	  <input id="reg-lastname" name="reg-lastname" type="text" 
		class='<?= $lastname_class ?>' value='<?= $reg_lastname ?>'>
	  <label for="reg-lastname">Last Name</label>
	  <?php if( isset( $lastname_message ) ) : ?>
		<span class="helper-text" data-error="<?= $lastname_message ?>"></span>
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
	  <i class="material-icons prefix">phone</i>
	  <input id="reg-phone" name="reg-phone" type="tel" class="validate">
	  <label for="reg-phone">Telephone</label>
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
