<?php



if ( isset( $_POST[ 'which' ] ) && isset( $_FILES[ 'file' ] ) ) {
	$type = $_FILES[ 'file' ][ 'type' ];
	if ( $type != 'image/jpeg' && $type != 'image/png' ) die( '{"success":false,"error":"Bad type"}' );
	$destination = $_SERVER[ 'DOCUMENT_ROOT' ]. '/'. $_POST[ 'which' ] . '/' . $_FILES[ 'file' ][ 'name' ];
	if ( file_exists( $destination ) ) {
		die( '{"success":false,"error":"File exists"}' );
	}
	if ( move_uploaded_file( $_FILES[ 'file' ][ 'tmp_name' ], $destination ) ) {
		chmod( $destination, 0666 );
		die( '{"success":true,"name":"' . '/'. addslashes( $_POST[ 'which' ] . '/' . $_FILES[ 'file' ][ 'name' ] ). '"}' );
	} else {
		die( '{"success":false,"error":"Upload failed"}' );
	}
}

die( '{"success":false,"error":"Bad request"}' );
