<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Class Infographicninja_Run
 *
 * Thats where we bring the plugin to life
 *
 * @package		INFOGRAPHI
 * @subpackage	Classes/Infographicninja_Run
 * @author		Outline Ninja
 * @since		1.0.0
 */
class Infographicninja_Run{

	/**
	 * Our Infographicninja_Run constructor 
	 * to run the plugin logic.
	 *
	 * @since 1.0.0
	 */
	function __construct(){
		$this->add_hooks();
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOKS
	 * ###
	 * ######################
	 */

	/**
	 * Registers all WordPress and plugin related hooks
	 *
	 * @access	private
	 * @since	1.0.0
	 * @return	void
	 */
	private function add_hooks(){
	
		add_action( 'plugin_action_links_' . INFOGRAPHI_PLUGIN_BASE, array( $this, 'add_plugin_action_link' ), 20 );
	
	}

	/**
	 * ######################
	 * ###
	 * #### WORDPRESS HOOK CALLBACKS
	 * ###
	 * ######################
	 */

	/**
	* Adds action links to the plugin list table
	*
	* @access	public
	* @since	1.0.0
	*
	* @param	array	$links An array of plugin action links.
	*
	* @return	array	An array of plugin action links.
	*/
	public function add_plugin_action_link( $links ) {

		$links['our_shop'] = sprintf( '<a target="_blank" href="%s" title="Get API Key" style="font-weight:700;">%s</a>', 'https://rapidapi.com/outline-ninja-outline-ninja-default/api/ai-infographics-generator-api/', __( 'Get API Key', 'infographicninja' ) );

		return $links;
	}

}
