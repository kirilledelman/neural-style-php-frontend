<?php

	if( isset( $_POST[ 'which' ] ) &&
	    ( $_POST[ 'which' ] == 'sources' || $_POST[ 'which' ] == 'styles' || $_POST[ 'which' ] == 'output' || $_POST[ 'which' ] == 'saved' ) ){
		
		$dir = $_SERVER[ 'DOCUMENT_ROOT' ]. '/'. $_POST[ 'which' ];
		$handle = opendir( $dir );
		$files = array();
		// read
	    while ( false !== ( $file = readdir( $handle ) ) ) {
	        if ( $file == '.' || $file == '..' ) continue;
	        $files[] = '{"name":"' .
	                   addslashes( '/' . $_POST[ 'which' ] . '/' . $file ) .
	                   '","time":' . filectime( $dir . '/' . $file ).'}';
	    }
	    closedir( $handle );
		
	    echo '{"success":true, "files":[' . join( ',', $files ) . ']}';
	} else {
		die( '{"success":false,"error":"Bad request"}' );
	}