<?php
error_reporting( E_ALL );
ini_set( 'display_errors', 1 );
ini_set( 'max_execution_time', 2000 );
ini_set( 'memory_limit', '200M' );

require_once( '../config.inc.php' );

header( 'Content-type: application/json' );
//header('Content-type: text/plain'); // FOR TESTING

function getRequest( $key , $default = '' ) {
	if ( isset ( $_REQUEST[$key] ) ) return str_replace( "\'" , "'" , $_REQUEST[$key] );
	return $default;
}

$wikigrokdb = new mysqli( $wikigrokdb['host'], $wikigrokdb['user'], $wikigrokdb['pass'], $wikigrokdb['dbname'] );
$action = getRequest( 'action' , '' ) ;
$item = intval( getRequest( 'item' , 0 ) ) ;

$out = array( 'status' => 'OK' ) ;

if ( $action == 'get_potential_occupations' ) {

        if ( $item ) {
				$item = str_replace( 'Q' , '' , $item );
                $sql = "SELECT occupation FROM potential_occupation WHERE status IS NULL AND item = $item LIMIT 1";
                $result = $wikigrokdb->query( $sql );
                if ( !$result ) die( 'There was an error running the query [' . $wikigrokdb->error . '] '.$sql );
                $x = $result->fetch_array();
                if ( $x ) {
                        $out['occupations'] = "$x[0]";
                } else {
                        $out['occupations'] = false;
                }
        } else {
                $out['status'] = 'Invalid input';
        }

} else {
	$out['status'] = "Unknown action $action" ;
}

print json_encode ( $out ) ;
