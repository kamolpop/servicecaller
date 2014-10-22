<?php
/**
 * [Configuration] : all of library config
 */
return array(

		/**
		 * HTTP is default 
		 */
		
		'protocal' 		=> Config::get('serviceapp.protocal'),

		/**
		 * Domain name of service request
		 */
		
		'host' 			=> Config::get('serviceapp.host'), 

		/**
		 * Client ID 
		 */

		'client_id' 	=> Config::get('serviceapp.client_id'), 

		/**
		 * Secret Key use for indentify application. (Keep secret)
		 */

		'public_key' 	=> Config::get('serviceapp.public_key'), 

		/**
		 * Authentication server must take full path URL
		 */

		'authHost'		=> Config::get('serviceapp.authHost'), 
	
		);

?>