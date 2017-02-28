<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://jasonackerman.com
 * @since             1.0.0
 * @package           Ot_Latest_Tweets
 *
 * @wordpress-plugin
 * Plugin Name:       OT Latest Tweets - Simplified
 * Plugin URI:        http://otmediallc.com
 * Description:       A basic way to load latest tweets from any user. Simple, clean markup ready for custom styling.
 * Version:           1.0.0
 * Author:            Jason Ackerman / Overtime Media LLC
 * Author URI:        http://jasonackerman.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       ot-latest-tweets
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


include_once('ot-latest-tweets-admin.php');
include_once('ot-latest-tweets-shortcode.php');