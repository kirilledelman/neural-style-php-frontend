<?php

$ret = array( 'success' => false );

/* copies a file in output directory to saved dir */
if( isset( $_POST[ 'file' ] ) ) {
	
	$dir = $_SERVER[ 'DOCUMENT_ROOT' ] . '/output/';
	$dir2 = $_SERVER[ 'DOCUMENT_ROOT' ] . '/saved/';
	$prefix = empty( $_POST[ 'prefix' ] ) ? time() : $_POST[ 'prefix' ];
	if ( file_exists( $dir . $_POST[ 'file' ] ) ) {
		
		if ( file_exists( $dir2 . $prefix . '_' . $_POST[ 'file' ] ) ) {
			$_POST[ 'file' ] = time() . '_' . $_POST[ 'file' ];
		}
		$ret[ 'success' ] = copy( $dir . $_POST[ 'file' ], $dir2 . $prefix . '_' . $_POST[ 'file' ] );
		
	} else {
		$ret[ 'error' ] = "File not found";
	}
	
	
}

echo json_encode( $ret );