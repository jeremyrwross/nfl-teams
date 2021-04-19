<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://jereross.com
 * @since      1.0.0
 *
 * @package    Nfl_Teams
 * @subpackage Nfl_Teams/admin
 * @author     Jeremy Ross <jeremyrwross@gmail.com>
 */
class Nfl_Teams_Admin {

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
	 * @since    1.0.0
	 * @param    string    $plugin_name       The name of this plugin.
	 * @param    string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Displays a notification after activation.
	 *
	 * @since    1.0.0
	 */
	public function activation_notice( ) {

		if( 1 == get_option( 'nfl_teams_deferred_admin_notice' ) ) {

			update_option( 'nfl_teams_deferred_admin_notice', 0 );

			$class = 'notice notice-info';
			$message = __( '<strong>Thanks for activating the NFL Teams plugin.</strong> 👍', 'nfl-teams' );

			printf( '<div class="%1$s"><p>%2$s</p></div>',
				esc_attr( $class ),
				wp_kses( $message, wp_kses_allowed_html( 'post' ) )
			);

		}

	}


	/**
	 * Creates the settings page menu item.
	 */
	public function add_menu() {

		add_menu_page(
			__( 'NFL Teams Settings', 'nfl-teams' ),
			__( 'NFL Teams', 'nfl-teams' ),
			'manage_options',
			'nfl-teams',
			false,
			'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"><path fill="#A7AAAD" d="M30 2c-.49-.49-4.1-.737-8.477-.042l.977.542c2.485 1.381 5.619 4.515 7 7l.542.976C30.737 6.1 30.49 2.49 30 2zM18 4l-1.354-.903C13.479 4.097 10.347 5.653 8 8c-2.346 2.346-3.903 5.479-4.902 8.646L4 18c2.209 3.313 6.687 7.791 10 10l1.354.902C18.521 27.903 21.653 26.347 24 24c2.347-2.347 3.903-5.479 4.902-8.646L28 14c-2.209-3.313-6.687-7.791-10-10zm3.707 9.707a.997.997 0 0 1-1.414 0L20 13.414l-.586.586.293.293a.999.999 0 1 1-1.414 1.414L18 15.414l-.586.586.293.293a.999.999 0 1 1-1.414 1.414L16 17.414l-.586.586.293.293a.999.999 0 1 1-1.414 1.414L14 19.414l-.586.586.293.293a.999.999 0 1 1-1.414 1.414l-2-2a.999.999 0 1 1 1.414-1.414l.293.293.586-.586-.293-.293a.999.999 0 1 1 1.414-1.414l.293.293.586-.586-.293-.293a.999.999 0 1 1 1.414-1.414l.293.293.586-.586-.293-.293a.999.999 0 1 1 1.414-1.414l.293.293.586-.586-.293-.293a.999.999 0 1 1 1.414-1.414l2 2a.999.999 0 0 1 0 1.414zM2.5 22.5l-.542-.977C1.263 25.9 1.51 29.51 2 30c.49.49 4.1.737 8.477.042l-.976-.542c-2.486-1.381-5.62-4.515-7.001-7z"/></svg>'),
			100
		);

		add_submenu_page(
			'nfl-teams',
			__( 'Settings', 'nfl-teams' ),
			__( 'Settings', 'nfl-teams' ),
			'manage_options',
			'nfl-teams',
			array( $this, 'page_settings' )
		);

		add_submenu_page(
			'nfl-teams',
			__( 'Help', 'nfl-teams' ),
			__( 'Help', 'nfl-teams' ),
			'manage_options',
			'nfl-teams-help',
			array( $this, 'page_help' )
		);
	}

	/**
	 * Creates the help page
	 *
	 * @since    1.0.0
	 */
	public function page_help() {

		include( plugin_dir_path( __FILE__ ) . 'partials/nfl-teams-help.php' );

	}

	/**
	 * Creates the settings page
	 *
	 * @since    1.0.0
	 */
	public function page_settings() {

		include( plugin_dir_path( __FILE__ ) . 'partials/nfl-teams-settings.php' );

	}

	/**
	 * Registers plugin settings
	 *
	 * @since    1.0.0
	 */
	public function register_settings() {

		// register_setting( string $option_group, string $option_name, array $args = array() )

		$args = array(
            'type' => 'string',
            'sanitize_callback' => 'sanitize_text_field',
            'default' => NULL,
          );

		register_setting(
			$this->plugin_name . '-options',
			$this->plugin_name . '-api-key',
			$args
		);

	}

	/**
	 * Registers settings sections with WordPress
	 *
	 * @since    1.0.0
	 */
	public function register_sections() {

		// add_settings_section( $id, $title, $callback, $menu_slug );

		add_settings_section(
			$this->plugin_name . '-settings',
			__( 'API Settings', 'nfl-teams' ),
			array( $this, 'section_messages' ),
			$this->plugin_name
		);

	}

	/**
	 * Displays a message before each settings section
	 *
	 * @since    1.0.0
	 */
	public function section_messages() {

		// Blank.

	}

	/**
	 * Registers settings fields with WordPress
	 *
	 * @since    1.0.0
	 */
	public function register_fields() {

		// add_settings_field( string $id, string $title, callable $callback, string $page, string $section = 'default', array $args = array() )

		$key = $this->plugin_name . '-api-key';

		add_settings_field(
			$this->plugin_name . 'api-key',
			__( 'API Key', 'nfl-teams' ),
			array( $this, 'field_text' ),
			$this->plugin_name,
			$this->plugin_name . '-settings',
			[
				'label_for' => $key,
			]
		);

	}

	/**
	 * Displays form input
	 *
	 * @since    1.0.0
	 */
	public function field_text( ) {

		$key = $this->plugin_name . '-api-key';

		$atts['name']  = $key;
		$atts['value'] = get_option( $key );

		?>
		<input class="regular-text" id="<?php echo esc_attr( $atts['name'] ); ?>" name="<?php echo esc_attr( $atts['name'] ); ?>" type="text" required value="<?php echo esc_attr( $atts['value'] ); ?>">
		<?php

	}
}
