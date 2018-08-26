<?php
	
	/*
	 * Returns files in a directory specified by 'which' post param
	 */

	include 'config.php';
	
	$result = array( 'status' => 'Ready', 'ready' => true );
	$logFile = ROOT . '/log.txt';
	
	// if log exists
	if ( file_exists( $logFile ) ) {
		
		// check if process is finished
		$contents = file_get_contents( $logFile );
		if ( strpos( $contents, "Exit code" ) !== FALSE ) {
		
			$result[ 'finished' ] = true;
			$result[ 'status' ] = "Finished";
			
			// error?
			if ( stripos( $contents, "error" ) !== FALSE ) {
				
				$result[ 'status' ] = "Error detected";
				$result[ 'log' ] = $contents;
			
			}
			
		// no not finished yet
		} else {
			
			// still going
			$result[ 'ready' ] = false;
			
			// progress?
			$result[ 'status' ] = "Initializing...";
			
			// current iteration
			if ( preg_match_all( "/Iteration (\d+) \/ (\d+)/m", $contents, $matches, PREG_SET_ORDER ) &&
			     ($numMatches = count( $matches ) ) > 0 ) {
					
				$last = $matches[ $numMatches - 1 ];
				$result[ 'status' ] = "Iteration " . $last[ 1 ] . '/' . $last[ 2 ]. ' ('. floor( 100* $last[ 1 ] / $last[ 2 ] ) . '%)';
			}
			
		}
		
		// parse out settings
		if ( preg_match( "/<SETTINGS>([\s\S]+)<\\/SETTINGS>/m", $contents, $matches ) ) {
			$result[ 'settings' ] = $matches[ 1 ];
		}
		
	
	}
	
	// done
	header( 'Content-type: application/json' );
	echo json_encode( $result );