<?php
	
	/*
	 * Stops running neural-style process
	 */

	include 'config.php';
	
	$result = array( 'success' => false );

	// get list of processes under current user
	$ps = shell_exec( 'ps -o pid -o command -u ' . USER );
	$lines = explode( "\n", $ps );
	
	// scan the rest
	foreach ( $lines as $ps ) {
		
		// find pid of process with "luajit" in name
		if ( preg_match( "/^\\s*(\d+).+luajit.*$/", $ps, $matches ) ) {
			$pid = $matches[ 1 ];
			if ( $pid ) {
				$result[ 'success' ] = shell_exec( 'kill -9 ' . $pid );
				break;
			}
		}
	}
	
	// done
	header( 'Content-type: application/json' );
	echo json_encode( $result );