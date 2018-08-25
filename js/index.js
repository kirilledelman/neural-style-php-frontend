
// init
document.addEventListener("DOMContentLoaded", function() {

	$('#sourceFile').onchange = addFile;
	$('#styleFile').onchange = addFile;
	$('#style2File').onchange = addFile;
	$('#sources').onchange = sourceChanged;
	$('#styles').onchange = styleChanged;
	$('#styles2').onchange = style2Changed;
	$('#outputs').onchange = outputChanged;
	$('#saveds').onchange = savedChanged;
	$('#source').onload = imageLoaded;
	$('#style').onload = $('#style2').onload = $('#output').onload = imageLoaded;
	$('#saved').onload = imageLoaded;
	$('#style_count').onchange = styleCountChanged;
	$('#btnStart').onclick = start;
	$('#btnStop').onclick = stop;
	$('#btnDeleteFile').onclick = $('#btnDeleteFile1').onclick = $('#btnDeleteFile2').onclick = deleteFile;
	$('#btnClearOutput').onclick = clearOutput;
	$('#outputs').ondblclick = $('#saveds').ondblclick = openImage;
	$('#btnSave').onclick = saveImage;
	$('#btnDeleteSaved').onclick = deleteFile;
	$('#btnDeleteAllSaved').onclick = deleteAllSaved;
	$('#loadSettings').onclick = loadSettings;
	
	// defaults
	var inputs = $$('input,select');
	var defaults = document.paramsDefaults = {
		'style_count': 1,
		'style_blend_weight0': '1',
		'style_blend_weight1': '1',
		'num_iterations': 100,
		'save_iter': 10,
		'image_size': 512,
		'content_weight': 5,
		'style_weight': 100,
		'seed': 100,
		'tv_weight': 0.001,
		'normalize_gradients': 1,
		'style_scale': 1.0,
		'init': 'image', // 'random',
		'content_layers': 'relu4_2',
		'style_layers': 'relu1_1,relu2_1,relu3_1,relu4_1,relu5_1',
		'style_layer_weights': '1,1,1,1,1',
		'original_colors': 0,
		'pooling': 'max',
		'output_image': 'out.png',
		
	};
	for ( var i = 0; i < inputs.length; i++ ) {
		var fld = inputs[ i ];
		var v = localStorage.getItem( fld.id ) || "";
		
		if ( !v && defaults[ fld.id ] !== undefined ) {
			v = defaults[ fld.id ];
		}
		
		// delayed set
		if ( fld.options && fld.options.length == 0 ) {
			fld.setAttribute( 'initValue', v );
		} else {
			fld.value = v;
		}
		
		fld.addEventListener( 'change', rememberValue );
	}
	
	// refresh
	refreshStatus();
	styleCountChanged();
	repopulateFiles();
	
} );


function refreshStatus() {
	ajax( 'sys/status.php', {}, function ( r ) {
		r = JSON.parse( r );
		var btnStart = $('#btnStart');
		var btnStop = $('#btnStop');
		var status = $('#status');
		if ( r.ready ) {
			btnStop.hidden = true;
			btnStart.disabled = false;
			btnStart.hidden = false;
			validateImages();
			stopRefreshStatus();
			if ( r.log ) console.log( r.log );
		} else {
			btnStop.hidden = false;
			btnStart.hidden = true;
			if ( !document.statusRefreshInterval ) startRefreshStatus();
		}
		
		try {
			if ( r.settings ) {
				$('#loadSettings').hidden = false;
				$('#loadSettings').settings = JSON.parse( r.settings );
			} else {
				$('#loadSettings').hidden = true;
			}
		} catch( e ) { $('#loadSettings').hidden = true; }
		
		// refresh output dir if status changed
		if ( status.textContent != r.status ) {
			status.textContent = r.status;
			ajax( 'sys/get-files.php', { which: 'output' }, populateSelect( $( '#outputs' ), true ) );
		}
	} );
}

function startRefreshStatus() {
	stopRefreshStatus();
	document.statusRefreshInterval = setInterval( refreshStatus, 2000 );
}

function stopRefreshStatus(){
	if ( document.statusRefreshInterval ) {
		clearInterval( document.statusRefreshInterval );
	}
	document.statusRefreshInterval = 0;
}

function start() {

	var params = {
		content_image: $('#sources').value,
		style_image: $('#styles').value,
		num_iterations: $('#num_iterations').value,
		image_size: $('#image_size').value,
		content_weight: $('#content_weight').value,
		style_weight: $('#style_weight').value,
		tv_weight: $('#tv_weight').value,
		style_scale: $('#style_scale').value,
		init: $('#init').value,
		content_layers: $('#content_layers').value,
		style_layers: $('#style_layers').value,
		style_layer_weights: $('#style_layer_weights').value,
		original_colors: $('#original_colors').value,
		normalize_gradients: $('#normalize_gradients').value,
		pooling: $('#pooling').value,
		save_iter: $('#save_iter').value,
		output_image: $('#output_image').value,
	}
	if ( $('#style_count').value == 2 ) {
		params.style_blend_weights = $( '#style_blend_weight0' ).value + ',' + $( '#style_blend_weight1' ).value;
		params.style_image += ',' + $( '#styles2' ).value;
	}
	
	$('#btnStart').disabled = true;
	
	ajax( 'sys/start.php', params, function(r){
		startRefreshStatus();
	} );

}

function stop() {
	if ( confirm( "Abort?" ) ) {
		ajax( 'sys/stop.php', {}, function ( r ) {
			refreshStatus();
		});
	}
}

function loadSettings( e ) {
	var settings = $('#loadSettings').settings;
	for( var s in settings ) {
		var fld = $('#' + s);
		if ( fld ) fld.value = settings[ s ];
	}
	if ( settings.style_blend_weights ){
		var p = settings.style_blend_weights.split( ',' );
		$('#style_blend_weight0').value = p[ 0 ];
		$('#style_blend_weight1').value = p[ 1 ] || 1;
	}
	if ( settings.content_image ) {
		$('#sources').value = settings.content_image;
		$('#sources').onchange();
	}
	if ( settings.style_image ) {
		var p = settings.style_image.split( ',' );
		$('#styles').value = p[ 0 ];
		$('#styles').onchange();
		if ( p.length == 2 ) {
			$( '#styles2' ).value = p[ 1 ];
			$('#styles2').onchange();
			$('#style_count').value = 2;
			$('#style_count').onchange();
		} else {
			$('#style_count').value = 1;
			$('#style_count').onchange();
		}
	}
}

function deleteFile( e ) {
	var b = e.target;
	var from = $('#' + b.getAttribute( 'from' ) );
	if ( from.value && confirm( "Delete " + from.value ) ) {
		var parts = from.value.split( '/' );
		var params = {
			which: parts[ 0 ],
			name: parts[ 1 ]
		};
		ajax( 'sys/delete.php', params, function(){
			repopulateFiles();
		} );
	}
}

function deleteAllSaved() {
	if ( confirm( "Clear saved list?" ) ){
		ajax( 'sys/delete.php', { which: 'saved'}, function(){
			ajax( 'sys/get-files.php', { which: 'saved' }, populateSelect( $('#saveds'), false, $('#savedPanel' ), true ) );
		} );
	}
}

function saveImage() {
	var file = $('#outputs').value;
	if ( !file ) return;
	file = file.substr( file.lastIndexOf( '/' ) + 1 );
	var prefix = prompt( "Enter file name prefix");
	ajax( 'sys/save-file.php', { file, prefix }, function(){
		ajax( 'sys/get-files.php', { which: 'saved' }, populateSelect( $('#saveds'), true, $('#savedPanel' ), true ) );
	} );
}

function clearOutput() {
	if ( confirm( "Clear output?" ) ){
		ajax( 'sys/delete.php', { which: 'output' }, function(){
			ajax( 'sys/get-files.php', { which: 'output' }, populateSelect( $( '#outputs' ), true ) );
		} );
	}
}

// field changed
function rememberValue( e ) {
	localStorage.setItem( e.target.id, e.target.value );
	
	// fix
	if ( e.target.id == 'original_colors' ) {
		$('#style_layer_weights').disabled = ( e.target.value == 1 );
	}
}

// reset all
function resetParams() {
	var inputs = $$('#params input,#params select, #style_count');
	for ( var i = 0; i < inputs.length; i++ ) {
		var fld = inputs[ i ];
		if ( document.paramsDefaults[ fld.id ] !== undefined ) {
			fld.value = document.paramsDefaults[ fld.id ];
			localStorage.setItem( fld.id, fld.value );
		}
	}
	styleCountChanged();
}

// one / two image
function styleCountChanged( e ) {
	var fld = $('#style_count');
	if( fld.value == 1 ) {
		$('#styleImage2').setAttribute( 'hidden', 'hidden' );
		document.body.classList.remove( 'multiple-images' );
	} else if ( fld.value == 2 ) {
		$('#styleImage2').removeAttribute( 'hidden' );
		document.body.classList.add( 'multiple-images' );
	}
}

// select source changed
function sourceChanged ( e ) {
	var fld = $('#sources');
	var img = $('#source' );
	var info = $('#sourceInfo');
	img.src = fld.value;
	img.classList.remove( 'empty' );
	
	if ( e ) rememberValue( e );
	validateImages();
	
}

// select style changed
function styleChanged ( e ) {
	var fld = $('#styles');
	var img = $('#style' );
	img.src = fld.value;
	img.classList.remove( 'empty' );
	
	if ( e ) rememberValue( e );
	validateImages();
}

// select style2 changed
function style2Changed ( e ) {
	var fld = $('#styles2');
	var img = $('#style2' );
	img.src = fld.value;
	img.classList.remove( 'empty' );

	if ( e ) rememberValue( e );
}


function outputChanged ( e ) {
	var fld = $('#outputs');
	var img = $('#output' );
	img.src = fld.value ? (fld.value + '?r=' + Math.random()) : '';
	img.classList.toggle( 'empty', !fld.value );
}

function savedChanged ( e ) {
	var fld = $('#saveds');
	var img = $('#saved' );
	img.src = fld.value ? (fld.value + '?r=' + Math.random()) : '';
	img.classList.toggle( 'empty', !fld.value );
}

// update info
function imageLoaded( e ) {
	var img = e.target;
	var info = $('#' + img.id + 'Info' );
	info.textContent = img.naturalWidth + ' x ' + img.naturalHeight;
}

function validateImages() {
	var btnStart = $('#btnStart');
	btnStart.disabled = !$('#sources').value || !$('#styles').value;
}


// file field file selected
function addFile( e ) {
	var fld = e.target;
	var which = fld.getAttribute( 'which' );
	var file = fld.files.length ? fld.files[ 0 ] : null;
	if ( file && file.name.match( /(\.png|\.jpg|\.jpeg)$/i ) ) {
		upload( 'sys/add-file.php', file, { which }, function( r ) {
			r = JSON.parse( r );
			if ( r.success ) {
				// add to list and select
				var option = document.createElement( 'option' );
				option.value = r.name;
				var fname = r.name.substr( r.name.lastIndexOf( '/' ) + 1 );
				var shortName = ( fname.length > 20  ? ( fname.substr( 0, 9 ) + '...' + fname.substr( -10 ) ) : fname );
				option.text = shortName;
				var selectField = $( '#' + which );
				selectField.insertBefore( option, selectField.options[ 0 ] );
				selectField.selectedIndex = 0;
				selectField.disabled = false;
				selectField.onchange();
			}
		});
	}
	
	// reset
	fld.value = '';
}

function openImage( e ) {
	
	window.open( window.location + e.target.value );
	
}

 function populateSelect( selectField, selectFirst, hideIfEmpty ) {
	return function ( r ) {
		r = JSON.parse( r );
		r.files.sort( ( a, b )=>( a.time > b.time ? -1 : 1 ) );
		
		var prevSelected = selectField.options.length ? selectField.value : false;
		var initVal = selectField.getAttribute( 'initValue' );
		if ( initVal ) {
			prevSelected = initVal;
			selectField.removeAttribute( 'initValue' );
		}
		
		selectField.innerHTML = '';
		var prevFound = false;
		for ( var i = 0; i < r.files.length; i++ ) {
			var option = document.createElement( 'option' );
			var fname = r.files[ i ].name;
			option.value = fname;
			fname = fname.substr( fname.lastIndexOf( '/' ) + 1 )
			var shortName = ( fname.length > 20  ? ( fname.substr( 0, 9 ) + '...' + fname.substr( -10 ) ) : fname );
			option.text = shortName;
			selectField.appendChild( option );
			if ( prevSelected == option.value ) prevFound = true;
		}
		selectField.disabled = (selectField.options.length == 0);
		if ( selectFirst && document.activeElement != selectField ) selectField.selectedIndex = 0;
		else if ( prevSelected && prevFound ) selectField.value = prevSelected;
		else selectField.selectedIndex = 0;
		selectField.onchange();
		
		// hide element if empty
		if ( hideIfEmpty ) {
			hideIfEmpty.hidden = (selectField.options.length == 0);
		}
	}
}

// reload source and
function repopulateFiles() {
	ajax( 'sys/get-files.php', { which: 'sources' }, populateSelect( $('#sources') ) );
	ajax( 'sys/get-files.php', { which: 'styles' }, populateSelect( $('#styles') ) );
	ajax( 'sys/get-files.php', { which: 'styles' }, populateSelect( $('#styles2') ) );
	ajax( 'sys/get-files.php', { which: 'saved' }, populateSelect( $('#saveds'), true, $('#savedPanel' ) ) );
}

// ================================================================================

// submit an ajax request
function ajax( url, params, success, failure ) {

    var xhr = new XMLHttpRequest();
    xhr.open('POST', url );
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onload = function() {
        if ( xhr.status === 200 ) {
            if ( success ) success( xhr.response );
        } else if ( xhr.status !== 200 ) {
            if ( failure ) failure( xhr.error );
        }
    };
    // encode params
    var data = '';
    for ( var prop in params ) {
        if ( params.hasOwnProperty( prop ) ) {
            data += ( data.length ? '&' : '' ) + encodeURI( prop + '=' + params[ prop ] );
        }
    }
    xhr.send( data );
    return xhr;

}

function upload(url, file, params, success, failure ){
    var xhr = new XMLHttpRequest();
    var fd = new FormData();
    xhr.open( "POST", url, true );
    
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
            if ( success ) success( xhr.responseText );
        }
    };
    xhr.onerror = function ( e ) { if( failure ) failure( e.error ); }
    // file
    fd.append( "file", file );
    // encode params
    for ( var prop in params ) {
        if ( params.hasOwnProperty( prop ) ) {
        	fd.append( prop, params[ prop ] );
        }
    }
    xhr.send( fd );
}

function $$( a ) {
	return Array.prototype.slice.apply( document.querySelectorAll( a ) );
}

function $( a ) {
	return document.querySelector( a );
}

HTMLElement.prototype.$ = function ( a ) {
	return this.querySelector( a );
}

HTMLElement.prototype.$$ = function ( a ) {
	return Array.prototype.slice.apply( this.querySelectorAll( a ) );
}