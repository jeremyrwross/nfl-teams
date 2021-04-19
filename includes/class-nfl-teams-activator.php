<?php

/**
 * Fired during plugin activation
 *
 * @link       http://jereross.com
 * @since      1.0.0
 *
 * @package    Nfl_Teams
 * @subpackage Nfl_Teams/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Nfl_Teams
 * @subpackage Nfl_Teams/includes
 * @author     Jeremy Ross <jeremyrwross@gmail.com>
 */
class Nfl_Teams_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		update_option( 'nfl_teams_deferred_admin_notice', 1 );
	}

}
