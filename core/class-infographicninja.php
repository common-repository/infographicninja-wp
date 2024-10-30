<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
if ( ! class_exists( 'Infographicninja' ) ) :

	/**
	 * Main Infographicninja Class.
	 *
	 * @package		INFOGRAPHI
	 * @subpackage	Classes/Infographicninja
	 * @since		1.0.0
	 * @author		Outline Ninja
	 */
	final class Infographicninja {

		/**
		 * The real instance
		 *
		 * @access	private
		 * @since	1.0.0
		 * @var		object|Infographicninja
		 */
		private static $instance;

		/**
		 * INFOGRAPHI helpers object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Infographicninja_Helpers
		 */
		public $helpers;

		/**
		 * INFOGRAPHI settings object.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @var		object|Infographicninja_Settings
		 */
		public $settings;

		/**
		 * Throw error on object clone.
		 *
		 * Cloning instances of the class is forbidden.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __clone() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to clone this class.', 'infographicninja' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class.
		 *
		 * @access	public
		 * @since	1.0.0
		 * @return	void
		 */
		public function __wakeup() {
			_doing_it_wrong( __FUNCTION__, __( 'You are not allowed to unserialize this class.', 'infographicninja' ), '1.0.0' );
		}

		/**
		 * Main Infographicninja Instance.
		 *
		 * Insures that only one instance of Infographicninja exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @access		public
		 * @since		1.0.0
		 * @static
		 * @return		object|Infographicninja	The one true Infographicninja
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Infographicninja ) ) {
				self::$instance					= new Infographicninja;
				self::$instance->base_hooks();
				self::$instance->includes();
				self::$instance->helpers		= new Infographicninja_Helpers();
				self::$instance->settings		= new Infographicninja_Settings();

				//Fire the plugin logic
				new Infographicninja_Run();

				/**
				 * Fire a custom action to allow dependencies
				 * after the successful plugin setup
				 */
				do_action( 'INFOGRAPHI/plugin_loaded' );
			}

			return self::$instance;
		}

		/**
		 * Include required files.
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function includes() {
			require_once INFOGRAPHI_PLUGIN_DIR . 'core/includes/classes/class-infographicninja-helpers.php';
			require_once INFOGRAPHI_PLUGIN_DIR . 'core/includes/classes/class-infographicninja-settings.php';

			require_once INFOGRAPHI_PLUGIN_DIR . 'core/includes/classes/class-infographicninja-run.php';
		}

		/**
		 * Add base hooks for the core functionality
		 *
		 * @access  private
		 * @since   1.0.0
		 * @return  void
		 */
		private function base_hooks() {
			add_action( 'plugins_loaded', array( self::$instance, 'load_textdomain' ) );
		}

		/**
		 * Loads the plugin language files.
		 *
		 * @access  public
		 * @since   1.0.0
		 * @return  void
		 */
		public function load_textdomain() {
			load_plugin_textdomain( 'infographicninja', FALSE, dirname( plugin_basename( INFOGRAPHI_PLUGIN_FILE ) ) . '/languages/' );
		}

	}

endif; // End if class_exists check.