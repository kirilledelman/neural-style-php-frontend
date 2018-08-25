<?php

	/*
	 * Deletes a file in 'which' + 'name' param or clears directory specified by 'which'
	 */
	
	include 'config.php';

	$result = array( 'success' => false );
	
	// validate
	if( isset( $_POST[ 'which' ] ) &&
	    in_array( $_POST[ 'which' ], array( 'sources', 'styles', 'output', 'saved' ) ) ) {
		
		$dir = ROOT . '/'. $_POST[ 'which' ];
		
		// one file
		if ( isset( $_POST[ 'name' ] ) && file_exists( $dir. '/' . $_POST[ 'name' ] ) ) {
		
			unlink( $dir. '/' . $_POST[ 'name' ] );
			
		// all in dir
		} else {
			
			// read directory
			$handle = opendir( $dir );
			$files = array();
			while ( false !== ($file = readdir( $handle )) ) {
				if ( $file == '.' || $file == '..' ) continue;
				$files[] = $file;
			}
			closedir( $handle );
			
			// delete each file
			foreach ( $files as $f ) unlink( $dir. '/' . $f );
		}
		
	    $result[ 'success' ] = true;
		
	}
	
	// done
	header( 'Content-type: application/json' );
	echo json_encode( $result );
	