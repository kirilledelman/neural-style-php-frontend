<?php

	/*
	 * Uploads a file in 'file' field to directory in 'which' field
	 */
	
	include 'config.php';
	
	$result = array( 'success' => false );
	
	// validate
	if ( isset( $_POST[ 'which' ] ) && isset( $_FILES[ 'file' ] ) &&
	     in_array( $_POST[ 'which' ], array( 'sources', 'styles', 'output', 'saved' ) )	) {
		
		// check type
		$file = $_FILES[ 'file' ];
		if ( $file[ 'type' ] == 'image/jpeg' || $file[ 'type' ] == 'image/png' ) {
			
			// check if exists
			$destination = ROOT . '/'. $_POST[ 'which' ] . '/' . $file[ 'name' ];
			if ( !file_exists( $destination ) ) {
				
				// move to dir
				if ( move_uploaded_file( $file[ 'tmp_name' ], $destination ) ) {
					
					// and set flags
					chmod( $destination, 0666 );
					$result[ 'success' ] = true;
					$result[ 'name' ] = $_POST[ 'which' ] . '/' . $file[ 'name' ];
					
				} else $result[ 'error' ] = "Upload failed";
				
			} else $result[ 'error' ] = "File exists";
			
		} else $result[ 'error' ] = "Bad type";
		
	}
	
	// done
	header( 'Content-type: application/json' );
	echo json_encode( $result );
