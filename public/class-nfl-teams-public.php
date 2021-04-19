<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://jereross.com
 * @since      1.0.0
 *
 * @package    Nfl_Teams
 * @subpackage Nfl_Teams/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Nfl_Teams
 * @subpackage Nfl_Teams/public
 * @author     Jeremy Ross <jeremyrwross@gmail.com>
 */
class Nfl_Teams_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 * @param string $plugin_name       The name of the plugin.
	 * @param string $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/nfl-teams-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/nfl-teams-public.js', '', $this->version, false );

	}

	/**
	 * Register shortcode
	 *
	 * @since    1.0.0
	 */
	public function register_shortcodes() {

		add_shortcode( 'nfl_teams', array( $this, 'nfl_teams_list' ) );

	}

	/**
	 * Outputs shortcode
	 *
	 * @since    1.0.0
	 */
	public function nfl_teams_list() {

		$response      = '';
		$output        = '';
		$api_url       = 'http://delivery.chalk247.com/team_list/NFL.JSON?api_key=';
		$api_key 	   = get_option( $this->plugin_name . '-api-key' );
		$api_url       = $api_url . $api_key;
		$transient_key = $this->plugin_name . '-transient-key';

		if ( ! $api_key && is_user_logged_in() ) {
			return '<p>API Key not set. <a href="' . admin_url( 'admin.php?page=nfl-teams' ) . '">Set the API Key</a>.</p>';
		}

		// Check if transient exists.
		if ( false === ( $response = get_transient( $transient_key ) ) ) {

			$response = wp_remote_get( $api_url );

		}

		if ( is_array( $response ) && 200 === $response['response']['code'] ) {

			// Store API data for 1 hour.
			set_transient( $transient_key, $response, HOUR_IN_SECONDS );

			$results = json_decode( $response['body'], true );

			// Simplify the array to contain only team data.
			$teams = $results['results']['data']['team'];

			// Get key parts of the data to built the template.
			$conferences = $this->get_conferences( $teams );
			$divisions = $this->get_divisions( $teams );

			$output .= '<div id="nfl-teams">';

			// Create tab navigation.
			$output .= '<nav class="mb-3">';
			$output .= '<div class="nav nav-tabs flex-sm-nowrap" id="nav-tab" role="tablist">';

			foreach( $conferences as $key => $conference ) {

				$conference_id = sanitize_title( $conference['conference'] );
				$class = 'nav-link';
				$selected = 'false';

				if( $key === $this->array_key_first( $conferences ) ) {
					$class .= ' active';
					$selected = 'true';
				}

				$output .= '<button class="' . $class . '" id="nav-' . esc_attr( $conference_id ) . '" data-bs-toggle="tab" data-bs-target="#' . esc_attr( $conference_id ) . '" type="button" role="tab" aria-controls="' . esc_attr( $conference_id ) . '" aria-selected="' . esc_attr( $selected ) . '">' . $conference['conference'] . '</button>';
			}

			$output .= '</div><!-- /#nav-tab -->';
			$output .= '</nav>';

			// Create tab content wrapper.
			$output .= '<div class="tab-content" id="tabContent">';

			foreach( $conferences as $key => $conference ) {

				$conference_id = esc_attr( sanitize_title( $conference['conference'] ) );
				$divisions = wp_list_sort( $divisions, 'division' );
				$class = 'py-3 tab-pane fade';

				if( $key === $this->array_key_first( $conferences ) ) {
					$class .= ' show active';
				}

				$output .= '<div class="' . $class . '" id="' . $conference_id . '" role="tabpanel" aria-labelledby="nav-' . $conference_id . '">';

				$output .= '<div class="row g-5">';

				foreach( $divisions as $division ) {

					// Search for all teams in the current conference.
					$teams_conference = $this->search_multidim_array( $teams, 'conference', $conference['conference'] );

					// Using the teams from the conference search above, find teams in the current division.
					$teams_division   = $this->search_multidim_array( $teams_conference, 'division', $division['division'] );

					if( ! empty( $teams_division ) ) {

						$output .= '<div class="division col-12 col-md-6">';
						$output .= '<h2>' . $division['division'] . '</h2>';
						$output .= '<ul class="list-group list-group-flush m-0 p-0">';

						foreach( $teams_division as $team ) {

							$name = $this->remove_duplicate_words( $team['name'] . ' ' . $team['nickname'] );

							$output .= '<li class="list-group-item">' . $name . '</li>';

						}

						$output .= '</ul>';
						$output .= '</div><!-- /.division -->';

					}
				}

				$output .= '</div><!-- /.row -->';
				$output .= '</div><!-- /.tab-pane -->';

			}

			$output .= '</div><!-- /.tab-content -->';
			$output .= '</div><!-- /#nfl-teams -->';

		}

		return $output;

	}

	/**
	 * Remove repeated words in a string.
	 * https://stackoverflow.com/a/43252449/12788474
	 *
	 * @since    1.0.0
	 */
	private function remove_duplicate_words( $string ) {

        return implode(' ', array_unique( explode( ' ', $string ) ) );

    }

	/**
	 * Gets the first key of an array.
	 * https://www.php.net/manual/en/function.array-key-first.php
	 *
	 * @since    1.0.0
	 */
	private function array_key_first( array $array ) {

		if( version_compare( PHP_VERSION, '7.3.0' ) >= 0 ) {
			return array_key_first( $array );
		}

        foreach( $array as $key => $unused ) {
            return $key;
        }

        return NULL;
    }

	/**
	 * Returns unique divisions from the API data.
	 *
	 * @since    1.0.0
	 */
	private function get_divisions( $data ) {
		$divisions = $this->unique_multidim_array( $data, 'division' );
		return $divisions;
	}

	/**
	 * Returns unique conferences from the API data.
	 *
	 * @since    1.0.0
	 */
	private function get_conferences( $data ) {
		$conferences = $this->unique_multidim_array( $data, 'conference' );
		return $conferences;
	}

	/**
	 * Return unique elements of multidimensional array.
	 * http://php.net/manual/en/function.array-unique.php#116302
	 *
	 * @since    1.0.0
	 */
	private function unique_multidim_array($array, $key) {
		$count      = 0;
		$temp_array = array();
		$key_array  = array();

		foreach($array as $val) {
			if (!in_array($val[$key], $key_array)) {
				$key_array[$count] = $val[$key];
				$temp_array[$count] = $val;
			}
			$count++;
		}
		return $temp_array;
	}

	/**
	 * multidimensional array search.
	 *
	 * @since    1.0.0
	 */
	private function search_multidim_array( $array, $key, $needle ) {

		$result = array();

		$results = array_keys( array_combine( array_keys( $array ), array_column( $array, $key ) ), $needle );

		foreach($results as $v ) {
			$result[] = $array[ $v ];
		}

		return $result;

	}
}
