<?php

/**
 * This file is used to markup the settings aspects of the plugin.
 *
 * @link       https://jereross.com/
 * @since      1.0.0
 *
 * @package    Nfl_Teams
 * @subpackage Nfl_Teams/admin/partials
 */
?>

<div class="wrap">

    <h1>NFL Teams Settings</h1>

    <form method="post" action="options.php">
		<?php

		settings_errors();

		settings_fields( $this->plugin_name . '-options' );
		do_settings_sections( $this->plugin_name );
		submit_button();

		?>
	</form>

</div>
