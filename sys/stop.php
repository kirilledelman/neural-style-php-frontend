<?php
	
	$ps = shell_exec( 'ps -u _www' );
	$obj = array( 'success' => false );
	$lines = explode( "\n", $ps );
	foreach ( $lines as $ps ) {
		
		// find pid of precess with "luajit"
		if ( preg_match( "/^\s+(\d+)\s+(\d+).+luajit.+$/", $ps, $matches ) ) {
			$pid = $matches[ 2 ];
			if ( $pid ) {
				$obj[ 'pid' ] = $pid;
				$obj[ 'success' ] = shell_exec( 'kill -9 ' . $pid );
				break;
			}
		}
	}
	
	echo json_encode( $obj );