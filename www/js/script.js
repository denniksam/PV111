document.addEventListener('DOMContentLoaded', function() {
	var elems = document.querySelectorAll('.modal');
	var instances = M.Modal.init(elems, {});
	var elems = document.querySelectorAll('select');
    var instances = M.FormSelect.init(elems, {});

	const authButton = document.getElementById("auth-button");
	if(authButton) authButton.addEventListener('click', authClick);
	else console.error("Element '#auth-button' not found");

    const priceFilterButton = document.getElementById("price-filter-button");
	if(priceFilterButton) priceFilterButton.addEventListener('click', priceFilterClick);
	// else console.error("Element '#auth-button' not found");

	window.addEventListener('hashchange', onHashChanged);

	// add product form (button)
	const addProductButton = document.getElementById("add-product-button");
	if(addProductButton) addProductButton.addEventListener('click', addProductClick);	

	activateCartButtons();
});
function activateCartButtons() {
	for( let button of document.querySelectorAll('[data-id-product]') ) {
		button.addEventListener('click', cartButtonClick);
	}
	// -1 (decrement) button
	for( let button of document.querySelectorAll('[data-cart-dec]') ) {
		button.addEventListener('click', decrementButtonClick);
	}
}
function decrementButtonClick(e) {
	const btn = e.target.closest('[data-cart-dec]');
	const idProduct = btn.getAttribute('data-cart-dec');
	fetch('/cart?id-product=' + idProduct, {
		method: 'PUT',
	}).then(r => {
		// r.text().then(console.log);
		if(r.status < 400) {
			window.location.reload();
		}
		else {
			M.toast({html: 'Помилка, спробуйте пізніше'}) ;
		}
	});
}

function cartButtonClick(e) {
	const btn = e.target.closest('[data-id-product]');
	const idProduct = btn.getAttribute('data-id-product');
	console.log(idProduct);
	fetch('/cart?id-product=' + idProduct, {
		method: 'POST',
	}).then(r => {
		switch(r.status) {
			case 201: M.toast({html: 'Товар додано до кошику'});
				break;
			case 202: M.toast({html: 'Кількість оновлена'}) ;
				break;
			case 500: M.toast({html: 'Помилка, спробуйте пізніше'}) ;
				break;
			default:  M.toast({html: 'Невідомий статус відповіді'}) ;
		}
	});
}

function adminDelete( productId ) {
	if( confirm('Підтвердіть видалення товару №' + productId ) ) {
		fetch(window.location.origin + window.location.pathname + `?id=${productId}`, {
			method: 'DELETE',
		}).then(r=>r.text()).then(console.log);

	}
}
function adminRestore( productId ) {
	if( confirm('Підтвердіть відновлення товару №' + productId ) ) {
		fetch(window.location.origin + window.location.pathname + `?id=${productId}`, {
			method: 'PURGE',
		}).then(r=>r.text()).then(console.log);

	}
}
function addProductClick() {
	const form = document.getElementById("add-form");
	if( ! form ) throw "#add-form not found" ;
	console.log(form);

	const title = form.querySelector('[name="title"]');
	if( ! title ) throw '[name="title"] not found' ;
	if( title.value.length < 3 ) {
		alert( 'Назва закоротка' ) ;
		return ;
	}

	// form.submit(); - з оновленням сторінки

	// а це - асинхронний варіант надсилання форми
	fetch(window.location.href, {
		method: 'POST',
		body: new FormData(form)
	}).then( r => {
		if( r.status === 201 ) {  // 201 - Created
			// Очищаємо форму (можна оновити сторінку)
		}
		else {
			// Проблеми з додаванням товару - виводимо повідомлення, одержане від сервера
			r.text().then(alert);
		}
	} ) ;
}

function authClick() {
	const authLogin = document.getElementById("auth-login");
	if(!authLogin) throw "Element '#auth-login' not found" ;
	const authPassword = document.getElementById("auth-password");
	if(!authPassword) throw "Element '#auth-password' not found" ;
	const login = authLogin.value ;
	const password = authPassword.value ;
	if( login.length == 0 ) {
		alert( 'Введіть логін' ) ;
		return ;
	}
	fetch( `/auth?login=${login}&password=${password}`, {
		method: 'POST',		
	}).then( r => {
		if( r.status != 200 ) {
			const msg = document.getElementById('auth-rejected-message');
			msg.style.visibility = 'visible';
		}
		else r.text().then( t => {
			console.log(t) ;
			if( t == 'OK' ) {
				// window.location.reload() ;
				window.location.href = window.location.pathname;
			}
		});
	} ); 
}
function priceFilterClick() {
    const minPriceInput = document.getElementById("min-price-input") ;
    if( ! minPriceInput ) throw "#min-price-input not found" ;
    const maxPriceInput = document.getElementById("max-price-input") ;
    if( ! maxPriceInput ) throw "#max-price-input not found" ;
    if( window.location.search.length > 0 ) {  // є параметри у запиті
        let params = [] ;
        for( let part of window.location.search.substring(1).split('&') ) {
            let kv = part.split('=') ;
            if( kv[0] == 'min-price' || kv[0] == 'max-price' ) {
                continue ;
            }
            params.push( kv ) ;
        }
        params.push( [ 'min-price', minPriceInput.value ] ) ;
        params.push( [ 'max-price', maxPriceInput.value ] ) ;
        var queryParts = params.map( elem => elem.join('=') ) ;
        var query = '?' + queryParts.join('&') ;
        // console.log( query ) ;
        window.location.href = window.location.pathname + query ;
    }
    else {
        window.location.href += '?' + `min-price=${minPriceInput.value}&max-price=${maxPriceInput.value}` ;
    }
}
function onHashChanged() {
	var hash = window.location.hash ;
	var page ;
	if( hash == '' ) {
		page = 1 ;
	}
	else {
		page = hash.substring(1);  // #2 -> 2
	}
	let params = [] ;
	for( let part of window.location.search.substring(1).split('&') ) {
		let kv = part.split('=') ;
		if( kv.length != 2 || kv[0] == 'page' ) {
			continue ;
		}
		params.push( kv ) ;
	}
	params.push( [ 'page', page ] ) ;
	console.log( params ) ;
	var queryParts = params.map( elem => elem.join('=') ) ;
	var query = '?' + queryParts.join('&') ;
	window.location.href = window.location.pathname + query ;
}
/*
Завдання: При зміні будь-якої умови фільтрації (за групою, за ціною тощо)
прибирати пагінацію, тобто фактично переводити на першу сторінку.

Д.З. Реалізувати визначення максимальної та мінімальної ціни за всією
групою вибірки (з урахуванням групи) але без урахування сторінки
(тобто максимум та мінімум серед усіх сторінок)
*/
