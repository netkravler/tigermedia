<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also inc all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.netkravler.com
 * @since             1.0.0
 * @package           Gestus_nord
 *
 * @wordpress-plugin
 * Plugin Name:       gestus_nord
 * Plugin URI:        https://www.netkravler.com
 * Description:       Plugin indeholdende de nødvendigheder der er for at gestus nordsiden fungerer.
 * Version:           1.0.0
 * Author:            netkravler
 * Author URI:        https://www.netkravler.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       gestus_nord
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if( !defined('ABSPATH')){

	die;
	
 }

 if ( ! function_exists( 'get_home_path' ) ) {
	include_once ABSPATH . '/wp-admin/includes/file.php';
}



if ( file_exists( dirname ( __FILE__ ) . '/vendor/autoload.php')){

require_once dirname( __FILE__) . '/vendor/autoload.php';

}



use Inc\Base\Activate;
use Inc\Base\Deactivate;


function activate(){

	Activate::activate();
}

register_activation_hook( __FILE__ ,  'activate');



function deactivate(){

	Deactivate::deactivate();
}

register_deactivation_hook( __FILE__ ,  'deactivate');


if ( class_exists( 'Inc\\Init')){

	Inc\Init::register_services();
}


function mytranslate($query){

	$query = ($query == false) ? 'Nej' : 'Ja' ;

	return $query;

}



