<?php

$obj = array( 'status' => 'Ready', 'ready' => true );
$logFile = $_SERVER[ 'DOCUMENT_ROOT' ] . '/log.txt';

if ( file_exists( $logFile ) ) {
	
	// check if finished
	$contents = file_get_contents( $logFile );
	if ( strpos( $contents, "Exit code" ) !== FALSE ) {
	
		$obj[ 'finished' ] = true;
		$obj[ 'status' ] = "Finished";
		
		// error?
		if ( stripos( $contents, "error" ) !== FALSE ) {
			
			$obj[ 'status' ] = "Error detected";
			$obj[ 'log' ] = $contents;
		
		}
		
	} else {
		
		// still going
		$obj[ 'ready' ] = false;
		
		// progress?
		$obj[ 'status' ] = "Initializing...";
		
		// current iteration
		if ( preg_match_all( "/Iteration (\d+) \/ (\d+)/m", $contents, $matches, PREG_SET_ORDER ) &&
		     ($numMatches = count( $matches ) ) > 0 ) {
				
			$last = $matches[ $numMatches - 1 ];
			$obj[ 'status' ] = "Iteration " . $last[ 1 ] . '/' . $last[ 2 ]. ' ('. floor( 100* $last[ 1 ] / $last[ 2 ] ) . '%)';
		}
		
	}
	
	// parse out settings
	if ( preg_match( "/<SETTINGS>([\s\S]+)<\\/SETTINGS>/m", $contents, $matches ) ) {
		$obj[ 'settings' ] = $matches[ 1 ];
	}
	

}
header( 'Content-type: application/json' );
echo json_encode( $obj );