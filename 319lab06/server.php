<?php
require_once('objects/library.php');
require_once('objects/user.php');

set_time_limit(0);
$master = WebSocket("localhost",8080);
$sockets = array($master);
ob_implicit_flush();

//handles sockets
while(true){
	$changed = $sockets;
	socket_select($changed,$write=NULL,$except=NULL,NULL);
	foreach($changed as $socket){
    if($socket==$master){
    	$client=socket_accept($master);
    	if($client<0){ 
    		console("socket_accept() failed");
    		continue; 
    	}else{ 
    		socket_write($client,Library::showLib());
    	}
    }
  }
}

/*****************************************************************************/
/* funtions to help handle sockets                                           */
/*****************************************************************************/


function WebSocket($address,$port){
 	$master=socket_create(AF_INET, SOCK_STREAM, SOL_TCP)     or die("socket_create() failed");
 	socket_set_option($master, SOL_SOCKET, SO_REUSEADDR, 1)  or die("socket_option() failed");
  	socket_bind($master, $address, $port)                  or die("socket_bind() failed");
  	socket_listen($master,20)                              or die("socket_listen() failed");
  	return $master;
}

?>