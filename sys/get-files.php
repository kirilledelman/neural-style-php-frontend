<?php

	/*
	 * Returns files in a directory specified by 'which' post param
	 */

	include 'config.php';
	
	$result = array( 'success' => false );
	
	// validate
	if( isset( $_POST[ 'which' ] ) &&
	    in_array( $_POST[ 'which' ], array( 'sources', 'styles', 'output', 'saved' ) ) ) {
	
		// open dir
		$dir = ROOT . '/'. $_POST[ 'which' ];
		$handle = opendir( $dir );
		if ( $handle ) {
		
			// read files
			$result[ 'files' ] = array();
			$result[ 'path' ] = $dir;
		    while ( ( $file = readdir( $handle ) ) !== FALSE ) {
		        if ( $file == '.' || $file == '..' ) continue;
		        $result[ 'files' ][] = array (
		            'name' => ( $_POST[ 'which' ] . '/' . $file ),
			        'time' => filectime( $dir . '/' . $file ) );
		    }
		    closedir( $handle );
		    $result[ 'success' ] = true;
		    
	    } else $result[ 'error' ] = "Unable to open directory $dir";
	}
	
	// done
	header( 'Content-type: application/json' );
	echo json_encode( $result );