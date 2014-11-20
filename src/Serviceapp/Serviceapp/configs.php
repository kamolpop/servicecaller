<?php
/**
 * [Configuration] : all of library config
 */
return array(

		/**
		 * HTTP is default 
		 */
		
		'protocal' 		=> Config::get('protocal'), 

		/**
		 * Connection default
		 */
		'default_host'	=> Config::get('default_host'),

		/**
		 * Domain name of service request
		 */
		
		'host' 			=>  Config::get('host'), 

		/**
		 * Client ID 
		 */

		'client_id' 	=> Config::get('client_id'), 

		/**
		 * Secret Key use for indentify application. (Keep secret)
		 */

		'public_key' 	=> Config::get('public_key'),

		/**
		 * Authentication server must take full path URL
		 */

		'authHost'		=> Config::get('authHost'),



		/**
		* 	 ----------------- Access log configuration --------------------
		*	|																|
		*	|	1 . mongoDB storage											|
		*	|	2 . send Alert with e-mail									|
		*	|	3 . Enable domain list										|
		*	|																|
		*	 ---------------------------------------------------------------
		*/

		/**
		* MongoDB config
		*/
		'LogDB'			=>	Config::get('LogDB'),

		/**
		 * Alert e-mail config
		 */

		'alert-mail' 	=> 	Config::get('alert-mail'),

		/**
		 * Enable log function domain list
		 */

		'EnableList'	=>	Config::get('EnableList'),

	);

?>