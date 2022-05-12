<?php
/**
 * Create the Sharing Club settings page
 *
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Start Class
if ( ! class_exists( 'SCWP_Theme_Options' ) ) {

	class SCWP_Theme_Options {

		/**
		 * Start things up
		 *
		 * @since 1.0.0
		 */
		public function __construct() {

			// We only need to register the admin panel on the back-end
			if ( is_admin() ) {
				add_action( 'admin_menu', array( 'SCWP_Theme_Options', 'add_admin_menu' ) );
				add_action( 'admin_init', array( 'SCWP_Theme_Options', 'register_settings' ) );
			}

		}

		/**
		 * Returns all theme options
		 *
		 * @since 1.0.0
		 */
		public static function get_scwp_options() {
			return get_option( 'scwp_options' );
		}

		/**
		 * Returns single theme option
		 *
		 * @since 1.0.0
		 */
		public static function get_scwp_option( $id ) {
			$options = self::get_scwp_options();
			if ( isset( $options[$id] ) ) {
				return $options[$id];
			}
		}

		/**
		 * Add sub menu page
		 *
		 * @since 1.0.0
		 */
		public static function add_admin_menu() {
			add_options_page(
				esc_html__( 'Sharing Club', 'sharing-club' ),
				esc_html__( 'Sharing Club', 'sharing-club' ),
				'manage_options',
				'theme-settings',
				array( 'SCWP_Theme_Options', 'create_admin_page' )
			);

		}

		/**
		 * Register a setting and its sanitization callback.
		 *
		 * We are only registering 1 setting so we can store all options in a single option as
		 * an array. You could, however, register a new setting for each option
		 *
		 * @since 1.0.0
		 */
		public static function register_settings() {
			register_setting( 'scwp_options', 'scwp_options', array( 'SCWP_Theme_Options', 'sanitize' ) );
		}

		/**
		 * Sanitization callback
		 *
		 * @since 1.0.0
		 */
		public static function sanitize( $options ) {

			// If we have options lets sanitize them
			if ( $options ) {

				// Checkbox
				if ( ! empty( $options['public'] ) ) {
					$options['public'] = 'on';
				} else {
					unset( $options['public'] ); // Remove from options if not checked
				}

                if ( ! empty( $options['hide_comments'] ) ) {
					$options['hide_comments'] = 'on';
				} else {
					unset( $options['hide_comments'] ); // Remove from options if not checked
				}
                /*

				// Input
				if ( ! empty( $options['input_example'] ) ) {
					$options['input_example'] = sanitize_text_field( $options['input_example'] );
				} else {
					unset( $options['input_example'] ); // Remove from options if empty
				}

				// Select
				if ( ! empty( $options['select_example'] ) ) {
					$options['select_example'] = sanitize_text_field( $options['select_example'] );
				}*/

			}

			// Return sanitized options
			return $options;

		}

		/**
		 * Settings page output
		 *
		 * @since 1.0.0
		 */
		public static function create_admin_page() { ?>

			<div class="wrap">

				<h1><?php esc_html_e( 'Sharing Club', 'sharing-club' ); ?></h1>

				<form method="post" action="options.php">

					<?php settings_fields( 'scwp_options' ); ?>

					<table class="form-table wpex-custom-admin-login-table">

						<?php // Checkbox example ?>
						<tr valign="top">
							<th scope="row"><?php esc_html_e( 'Make Sharing Club public (visible without login)', 'sharing-club' ); ?></th>
							<td>
								<?php $value = self::get_scwp_option( 'public' ); ?>
								<input type="checkbox" name="scwp_options[public]" <?php checked( $value, 'on' ); ?>>
							</td>
						</tr>
                        <tr valign="top">
							<th scope="row"><?php esc_html_e( 'Hide the comment form', 'sharing-club' ); ?></th>
							<td>
								<?php $value = self::get_scwp_option( 'hide_comments' ); ?>
								<input type="checkbox" name="scwp_options[hide_comments]" <?php checked( $value, 'on' ); ?>>
							</td>
						</tr>
<!--
						<?php // Text input example ?>
						<tr valign="top">
							<th scope="row"></th>
							<td>
								<?php //$value = self::get_scwp_option( 'input_example' ); ?>
								<input type="text" name="scwp_options[input_example]" value="<?php echo esc_attr( $value ); ?>">
							</td>
						</tr>

						<?php // Select example ?>
						<tr valign="top" class="wpex-custom-admin-screen-background-section">
							<th scope="row"></th>
							<td>
								<?php //$value = self::get_scwp_option( 'select_example' ); ?>
								<select name="scwp_options[select_example]">
                                    <option value=""></option>
								</select>
							</td>
						</tr>
-->
					</table>

					<?php submit_button(); ?>

				</form>

			</div><!-- .wrap -->
		<?php }

	}
}
new SCWP_Theme_Options();

// Helper function to use in your theme to return a theme option value
function scwp_get_option( $id = '' ) {
	return SCWP_Theme_Options::get_scwp_option( $id );
}
