<?php
require_once '../lib/config.php';

$start = microtime(true);

$conn = new XMPPHP_BOSH(
	'localhost', 
  5222, 
  'testuser1', 
  'admin', 
  'xmpphp', 
  'localhost', 
  $printlog = true, 
  $loglevel = XMPPHP_Log::LEVEL_DEBUG);

$conn->autoSubscribe();

try {
	if(isset($_SESSION['messages'])) {
		foreach($_SESSION['messages'] as $msg) {
			print $msg;
			flush();
		}
	}
	$conn->connect2('http://localhost/http-bind', 1, true);
	#while(true) {
			$payloads = $conn->processUntil(array('message', 'presence', 'end_stream', 'session_start'));
			foreach($payloads as $event) {
				$pl = $event[1];
				switch($event[0]) {
					case 'message': 
						if(!isset($_SESSION['messages'])) $_SESSION['message'] = Array();
						$msg = "---------------------------------------------------------------------------------\n{$pl['from']}: {$pl['body']}\n";
						print $msg;
						$_SESSION['messages'][] = $msg;
						flush();
						$conn->message($pl['from'], $body="Thanks for sending me \"{$pl['body']}\".", $type=$pl['type']);
						if($pl['body'] == 'quit') $conn->disconnect();
						if($pl['body'] == 'break') $conn->send("</end>");
					break;
					case 'presence':
						print "Presence: {$pl['from']} [{$pl['show']}] {$pl['status']}\n";
					break;
					case 'session_start':
						print "Session Start\n";
						$conn->getRoster();
						$conn->presence($status="Cheese!");
					break;
				}
		}
	#}
} catch(XMPPHP_Exception $e) {
    die($e->getMessage());
}
$conn->saveSession();



$end = microtime(true);