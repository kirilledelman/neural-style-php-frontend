<?php

	if( isset( $_POST[ 'which' ] ) &&
	    ( $_POST[ 'which' ] == 'sources' || $_POST[ 'which' ] == 'styles' || $_POST[ 'which' ] == 'output' || $_POST[ 'which' ] == 'saved' ) ){
		
		$dir = $_SERVER[ 'DOCUMENT_ROOT' ]. '/'. $_POST[ 'which' ];
		
		// one file
		if ( isset($_POST[ 'name' ] ) && file_exists( $dir. '/' . $_POST[ 'name' ] ) ) {
		
			unlink( $dir. '/' . $_POST[ 'name' ] );
			
		// all in dir
		} else {
			
			$handle = opendir( $dir );
			$files = array();
			// read
			while ( false !== ($file = readdir( $handle )) ) {
				if ( $file == '.' || $file == '..' ) continue;
				$files[] = $file;
			}
			closedir( $handle );
			foreach ( $files as $f ) unlink( $dir. '/' . $f );
		}
		
	    echo '{"success":true}';
	} else {
		die( '{"success":false,"error":"Bad request"}' );
	}