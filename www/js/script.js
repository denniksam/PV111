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
});
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
