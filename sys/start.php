<?php

	/*
	 * Starts neural-style computation. All command line params come as $_POST vars
	 */

	include 'config.php';
	
	$result = array( 'success' => false );
	$logFile = ROOT . '/log.txt';

	// just in case, check for currently running neural-style
	$ps = shell_exec( 'ps -u ' . USER );
	$lines = explode( "\n", $ps );
	foreach ( $lines as $ps ) {
		// find pid of process with "luajit" in name
		if ( preg_match( "/^\s+(\d+)\s+(\d+).+luajit.+$/", $ps, $matches ) ) {
			// kill it, if found
			$pid = $matches[ 2 ];
			if ( $pid ) {
				shell_exec( 'kill -9 ' . $pid );
				break;
			}
		}
	}
	
	// clear output dir
	$dir = ROOT . '/output/';
	$handle = opendir( $dir );
	$files = array();
	while ( false !== ($file = readdir( $handle )) ) {
		if ( $file == '.' || $file == '..' ) continue;
		$files[] = $file;
	}
	closedir( $handle );
	foreach ( $files as $f ) unlink( $dir . $f );
	
	// if using original_colors flag, we'll use original neural_style.lua located in jsjohnson/neural-style
	// otherwise use one that allows tuning style layers weights (https://github.com/htoyryla/neural-style), from /sys
	if ( $_POST[ 'original_colors' ] == '1' ) {
		unset( $_POST[ 'style_layer_weights' ] );
		$lua_file = NEURAL_STYLE_PATH . 'neural_style.lua';
	} else {
		unset( $_POST[ 'original_colors' ] );
		$lua_file = ROOT . '/sys/' . 'neural_style_layer_weights.lua';
	}

	// read and tweak params from $_POST
	$params = array();
	$_POST[ 'output_image' ] = '/output/' . $_POST[ 'output_image' ];
	foreach( $_POST as $k => $v ) {
		
		// no funky business with $_POST
		if ( preg_match( "/^[a-z_]+$/", $k ) ) {
			
			// prepend paths
			if ( $k == 'content_image' || $k == 'style_image' || $k == 'output_image' ) {
				$v = explode(',', $v);
				foreach( $v as $a=>$b) {
					$v[ $a ] = str_replace( ' ', "\\ ", ROOT . '/' . $b );
				}
				$v = join(',', $v);
			}
			
			// flag '--normalize_gradients' doesn't need value
			if ( $k == 'normalize_gradients' ) {
				if ( $v == '1' ) $params[] = '-' . $k;
				
			// normal "--param value"
			} else {
				$params[] = '-' . $k . ' ' . $v;
			}
		}
	}
	
	// build command line command for torch + neural-style
	$cmd = TORCH_PATH . ' ' . $lua_file .
	       ' -gpu -1 -print_iter 1 ' . join( ' ', $params );
	
	// build command line command to run in background and print to log
	$script = '('.
	   "cd " . NEURAL_STYLE_PATH . "; $cmd >> $logFile 2>&1; ".
	   'echo "Exit code $?" >> '.$logFile.
	   ') > /dev/null 2>&1 & echo $!';
	
	// dump current settings into log
	if ( file_exists( $logFile ) ) unlink( $logFile );
	file_put_contents( $logFile,
       "<COMMAND>$script</COMMAND>\n\n".
       "<SETTINGS>" . json_encode( $_POST ) . "</SETTINGS>\n\n" );

	// launch it
	$result[ 'exec' ] = shell_exec( $script );
	$result[ 'success' ] = true;
	$result[ 'cmd' ] = $script;
	
	// done
	header( 'Content-type: application/json' );
	echo json_encode( $result );
	
	