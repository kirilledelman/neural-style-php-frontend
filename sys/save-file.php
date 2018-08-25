<?php

	/*
	 * Copies a file named by 'file' param in /output directory to /saved dir
	 * prepends 'prefix' param to new file name, otherwise timestamp
	 */

	include 'config.php';
	
	$result = array( 'success' => false );

	// validate
	if( isset( $_POST[ 'file' ] ) ) {
		
		// check if file exists
		$dir = ROOT . '/output/';
		$dir2 = ROOT . '/saved/';
		$prefix = empty( $_POST[ 'prefix' ] ) ? time() : $_POST[ 'prefix' ];
		if ( file_exists( $dir . $_POST[ 'file' ] ) ) {
			
			// if destination exists, add timestamp
			if ( file_exists( $dir2 . $prefix . '_' . $_POST[ 'file' ] ) ) {
				$_POST[ 'file' ] = time() . '_' . $_POST[ 'file' ];
			}
			
			// copy
			$result[ 'success' ] = copy( $dir . $_POST[ 'file' ], $dir2 . $prefix . '_' . $_POST[ 'file' ] );
			
		}
		
	}
	
	// done
	header( 'Content-type: application/json' );
	echo json_encode( $result );