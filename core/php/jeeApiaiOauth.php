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
 
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';


log::add('apiai', 'debug', 'Début oAuth');

if (isset($_GET['response_type'])) {

	log::add('apiai', 'debug', 'Demande autorisation de Google '. print_r($_GET, true));

	if ($_GET['client_id'] == config::byKey("OAuthClientID", "apiai") && $_GET['response_type'] == "code") {
		
		log::add('apiai', 'debug', 'Methode code');
		
		$authorization_code = bin2hex(random_bytes(8));
		config::save("OAuthAuthorizationCode", $authorization_code, "apiai");
		
		// https://oauth-redirect.googleusercontent.com/r/YOUR_PROJECT_ID?code=AUTHORIZATION_CODE&state=STATE_STRING
		$cible_url = $_GET['redirect_uri'] . "?code=" . $authorization_code . "&state=" . $_GET['state'];
		
	    header('Location: ' . $cible_url);
	    die('Redirect');
	    
	} elseif ($_GET['client_id'] == config::byKey("OAuthClientID", "apiai") && $_GET['response_type'] == "token") {
	
		log::add('apiai', 'debug', 'Methode token');
	
		$access_token = bin2hex(random_bytes(16));
		config::save("OAuthAccessToken", $access_token, "apiai");
	
		// https://oauth-redirect.googleusercontent.com/r/YOUR_PROJECT_ID#access_token=ACCESS_TOKEN&token_type=bearer&state=STATE_STRING
		$cible_url = $_GET['redirect_uri'] . "?access_token=" . $access_token . "&token_type=bearer&state=" . $_GET['state'];
		
	    header('Location: ' . $cible_url);
	    die('Redirect');
	    
	} else {
		die();
	}
	    
} else {
	
	log::add('apiai', 'debug', 'Response Type est vide. $_POST = ' . print_r($_POST, true));
	
	// vérifivation des tokens
	if ($_POST['client_id'] == config::byKey("OAuthClientID", "apiai") && $_POST['client_secret'] == config::byKey("OAuthClientSecret", "apiai")) {
		
		
		log::add('apiai', 'debug', 'Oauth client ID and Secret ID OK');
		header('Content-type: application/json');
		header('HTTP/1.1 200 OK');
		header("'Access-Control-Allow-Origin': *");
	    header("'Access-Control-Allow-Headers': 'Content-Type, Authorization'");

		// client_id=GOOGLE_CLIENT_ID&client_secret=GOOGLE_CLIENT_SECRET&grant_type=authorization_code&code=AUTHORIZATION_CODE
		// client_id=GOOGLE_CLIENT_ID&client_secret=GOOGLE_CLIENT_SECRET&grant_type=refresh_token&refresh_token=REFRESH_TOKEN


		if($_POST['grant_type'] == "authorization_code" && $_POST['code'] == config::byKey("OAuthAuthorizationCode", "apiai")) {
			
			log::add('apiai', 'debug', 'Method Authorization Code');
			
			$access_token = bin2hex(random_bytes(16));
			config::save("OAuthAccessToken", $access_token, "apiai");
			
			$refresh_token = bin2hex(random_bytes(16));
			config::save("OAuthRefreshToken", $refresh_token, "apiai");
			
			$response = array( 
			"token_type" => "bearer",
			"access_token" => $access_token,
			"refresh_token" => $refresh_token,
			"expires_in" =>  604800
			);
			
			log::add('apiai', 'debug', 'Envoi de la réponse');

			
			echo json_encode($response);
			
		} elseif($_POST['grant_type'] == "refresh_token" &&  $_POST['refresh_token'] == config::byKey("OAuthRefreshToken", "apiai")) {
			
			log::add('apiai', 'debug', 'Method Refresh Token');
			
			$access_token = bin2hex(random_bytes(16));
			config::save("OAuthAccessToken", $access_token, "apiai");
			
			$response = array( 
			"token_type" => "bearer",
			"access_token" => $access_token,
			"expires_in" =>  604800
			);
			
			log::add('apiai', 'debug', 'Envoi de la réponse');
			
			echo json_encode($response);
			
			
		} else die();
		
	} else {
		log::add('apiai', 'debug', 'Client ID and Secret ID KO');
		
		die();
	}
    
}

log::add('apiai', 'debug', 'Fin oAuth');

?>