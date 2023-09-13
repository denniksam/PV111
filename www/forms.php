<?php
	$name_class = "validate" ;
	$reg_name = "" ;
	if( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) { 
		// оброблення даних форми
		// echo '<pre>' ; print_r( $_POST ) ; exit ;
		// етап 1 - валідація
		if( ! isset( $_POST[ 'reg-name' ] ) ) {  // наявність самих даних
			$name_message = "No reg-name field" ;
		}
		else {
			$reg_name = $_POST[ 'reg-name' ] ;
			if( strlen( $reg_name ) < 2 ) {
				$name_message = "Name too short" ;
			}
		}
		if( isset( $name_message ) ) {  // валідація імені не пройшла
			$name_class = "invalid" ;
		}
		else {  // успішна валідація
			$name_class = "valid" ;
		}
	}
	/* Задачі: 
	1. для input class може приймати одне з трьох значень:
	    "validate" - немає попередніх даних (заповнення форми вперше)
		"invalid" - є дані і вони некоректні
		"valid" - є коректні дані
	   TODO: скласти вираз для перемикання цих значень.
	2. якщо є передані дані то їх бажано відновити у полях форми   
	Д.З. Виконати ці задачі для всіх полів форми
	*/
?>
<div class="row">
<form class="col s12" method="post">
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
	  <input id="reg-lastname" name="reg-lastname" type="text" class="validate">
	  <label for="reg-lastname">Last Name</label>
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
  <div class="row center-align">
	<button class="waves-effect waves-light btn orange darken-3">
		<i class="material-icons right">how_to_reg</i>Register
	</button>
  </div>
</form>
</div>