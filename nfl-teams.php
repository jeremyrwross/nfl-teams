<?php
/**
 * @link              https://jereross.com
 * @since             1.0.0
 * @package           Nfl_Teams
 *
 * @wordpress-plugin
 * Plugin Name:       NFL Teams
 * Plugin URI:        https://github.com/jeremyrwross/nfl-teams
 * Description:       A basic WordPress plugin built to show a list of NFL teams returned from an API.
 * Version:           1.0.0
 * Author:            Jeremy Ross
 * Author URI:        https://jereross.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       nfl-teams
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'NFL_TEAMS_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-nfl-teams-activator.php
 */
function activate_nfl_teams() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-nfl-teams-activator.php';
	Nfl_Teams_Activator::activate();
}

register_activation_hook( __FILE__, 'activate_nfl_teams' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-nfl-teams.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_nfl_teams() {

	$plugin = new Nfl_Teams();
	$plugin->run();

}
run_nfl_teams();
