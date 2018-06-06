<?php

/*
 * This file is part of the NextDom software (https://github.com/NextDom or http://nextdom.github.io).
 * Copyright (c) 2018 NextDom.
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, version 2.
 *
 * This program is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

	require_once dirname(__FILE__) . "/../../../../core/php/core.inc.php";
	require_once dirname(__FILE__) . '/../../../gsh/core/class/gsh.class.php';

	function SYNC_devices() {
		
		return gsh::sync();
	}
	
	function QUERY_devices($devices) {
		
		return gsh::query($devices);
		
	}	
	
	function EXECUTE_commands($commands) {
		
		return gsh::exec(array('data' => $commands));
		
	}	
	
	function traiteInput($input){
		switch ($input['intent']) {
			case "action.devices.SYNC" : 
				log::add('apiai', 'debug', 'Demande de Sync');
				$payload = array();
				$payload['agentUserId'] = 'jeedom-apiaiplugin-' . jeedom::getApiKey('apiai');
				$payload['devices'] = SYNC_devices();
				log::add('apiai', 'debug', 'Fin Sync');
				return $payload;
				break;
				
			case "action.devices.QUERY" : 
				log::add('apiai', 'debug', 'Demande de QUERY');
				$payload = QUERY_devices($input['payload']);
				return $payload;
				break;
				
			case "action.devices.EXECUTE" : 
				log::add('apiai', 'debug', 'Demande de EXECUTE');
				log::add('apiai', 'debug', print_r(json_encode($input['payload']), true) .'...');

				$payload = EXECUTE_commands($input['payload']);
				return $payload;
				break;
				
		}
	}
	
	$entityBody = file_get_contents('php://input');
	$body = json_decode($entityBody, true);
	
	
	$token = null;
	$headers = apache_request_headers();
	if (isset($headers['Authorization'])) {
		$matches = array();
		preg_match('/Bearer (.*)/', $headers['Authorization'], $matches);
		if (isset($matches[1])) {
		  $token = $matches[1];
		}
	} else {
		header("HTTP/1.1 401 Unauthorized");
		echo json_encode(array());
    	exit;
	}
	

	if (isset($token) && $token == config::byKey("OAuthAccessToken", "apiai")) {
	
		header('Content-type: application/json');
		header('HTTP/1.1 200 OK');

		$reply['requestId'] = $body['requestId'];
		foreach ($body['inputs'] as $input) {
			$reply['payload'] = traiteInput($input);
		}
		
		$response = $reply;
		
		echo json_encode($response);
		
	} else {
		header("HTTP/1.1 401 Unauthorized");
		echo json_encode(array());
    	exit;
	}
?>
