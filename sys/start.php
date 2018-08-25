<?php

$user = 'kirilledelman';

// save settings
$logFile = $_SERVER[ 'DOCUMENT_ROOT' ] . '/log.txt';
if ( file_exists( $logFile ) ) unlink( $logFile );
file_put_contents( $logFile, "<SETTINGS>" . json_encode( $_POST ) . "</SETTINGS>" );


// for now original colors and
if ( $_POST[ 'original_colors' ] == '0' ) {
	unset( $_POST[ 'original_colors' ] );
	$lua_file = 'neural_style_layer_weights.lua';
} else {
	unset( $_POST[ 'style_layer_weights' ] );
	$lua_file = 'neural_style.lua';
}


// read params
$params = array();
$_POST[ 'output_image' ] = '/output/' . $_POST[ 'output_image' ];
foreach( $_POST as $k => $v ) {
	if ( preg_match( "/^[a-z_]+$/", $k ) ) {
		// prepend paths
		if ( $k == 'content_image' || $k == 'style_image' || $k == 'output_image' ) {
			$v = explode(',', $v);
			foreach( $v as $a=>$b) {
				$v[ $a ] = str_replace( ' ', "\\ ", $_SERVER[ 'DOCUMENT_ROOT' ] . $b );
			}
			$v = join(',', $v);
		}
		
		// flag
		if ( $k == 'normalize_gradients' ) {
			if ( $v == '1' ) $params[] = '-' . $k;
		// setting
		} else {
			$params[] = '-' . $k . ' ' . $v;
		}
	}
}

// start process
$cmd = '/Users/' . $user . '/torch/install/bin/th /Users/' . $user . '/neural-style/' . $lua_file .
       ' -gpu -1 -print_iter 1 ' . join(' ', $params );
$script = '('.
   "cd /Users/$user/neural-style/; $cmd >> $logFile 2>&1; ".
   'echo "Exit code $?" >> '.$logFile.
   ') > /dev/null 2>&1 & echo $!';
$obj = array( 'success' => true, 'cmd' => $script, 'exec' => shell_exec( $script ) );

// clear output dir
$dir = $_SERVER[ 'DOCUMENT_ROOT' ] . '/output/';
$handle = opendir( $dir );
$files = array();
while ( false !== ($file = readdir( $handle )) ) {
	if ( $file == '.' || $file == '..' ) continue;
	if ( preg_match( "/^__/", $file ) ) continue;
	$files[] = $file;
}
closedir( $handle );
foreach ( $files as $f ) unlink( $dir . $f );

// done
echo json_encode( $obj );

/* TODO

	replace hardcoded paths w variables
	

*/