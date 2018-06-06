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

require_once dirname(__FILE__) . '/../../../core/php/core.inc.php';
include_file('core', 'authentification', 'php');
if (!isConnect()) {
    include_file('desktop', '404', 'php');
    die();
}
?>


<form class="form-horizontal">
    <fieldset>
    	<div class="form-group">
		    <label class="col-lg-4 control-label">{{Google Cloud App/Project ID}}</label>
		    <div class="col-lg-2">
		        <input class="configKey form-control" data-l1key="GAppID" />
		    </div>
		</div>    	    	
    	<div class="form-group">
		    <label class="col-lg-4 control-label">{{OAuth Client ID}}</label>
		    <div class="col-lg-2">
		        <input class="configKey form-control" data-l1key="OAuthClientID" />
		    </div>
		</div>       	
    	<div class="form-group">
		    <label class="col-lg-4 control-label">{{OAuth Client Secret}}</label>
		    <div class="col-lg-2">
		        <input class="configKey form-control" data-l1key="OAuthClientSecret" />
		    </div>
		</div>
		<div class="form-group">
		  <label class="col-lg-4 control-label">{{OAuth URL de retour}}</label>
		  <div class="col-lg-2">
		    	<span><?php echo network::getNetworkAccess('external') . '/plugins/apiai/core/php/jeeApiaiOauth.php';?></span>
			</div>		
		</div>
		<div class="form-group">
		  <label class="col-lg-4 control-label">{{OAuth Scope}}</label>
		  <div class="col-lg-2">
		    	<span>code</span>
			</div>		
		</div>
    	<div class="form-group">
		    <label class="col-lg-4 control-label">{{OAuth Authorization Code}}</label>
		    <div class="col-lg-2">
		        <input class="configKey form-control" data-l1key="OAuthAuthorizationCode" readonly/>
		    </div>
		</div>		
    	<div class="form-group">
		    <label class="col-lg-4 control-label">{{OAuth Access Token}}</label>
		    <div class="col-lg-4">
		        <input class="configKey form-control" data-l1key="OAuthAccessToken" readonly/>
		    </div>
		</div>       	
    	<div class="form-group">
		    <label class="col-lg-4 control-label">{{OAuth Refresh Token}}</label>
		    <div class="col-lg-4">
		        <input class="configKey form-control" data-l1key="OAuthRefreshToken" readonly/>
		    </div>
		</div>		
    	<div class="form-group">
		    <label class="col-lg-4 control-label">{{HomeGraph API Key}}</label>
		    <div class="col-lg-4">
		        <input class="configKey form-control" data-l1key="homeGraphAPIKey" />
		    </div>
		</div>
      <div class="form-group">
        <label class="col-lg-4 control-label">{{Resynchroniser les équipements}}</label>
        <div class="col-lg-2">
        <a class="btn btn-default" id="bt_syncWithGoogle"><i class='fa fa-refresh'></i> {{Resynchroniser les équipements avec Google}}</a>
        </div>
    </div>        
  </fieldset>
</form>


<script>
    $('#bt_syncWithGoogle').on('click', function () {
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "plugins/apiai/core/ajax/apiai.ajax.php", // url du fichier php
            data: {
                action: "syncWithGoogle",
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function (data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alert').showAlert({message: data.result, level: 'danger'});
                    return;
                }
                $('#div_alert').showAlert({message: '{{Synchronisation réussie}}', level: 'success'});
            }
        });
    });
</script>

