<?php
/**
 * InfographicNinja WP
 *
 * @package       INFOGRAPHI
 * @author        Outline Ninja
 * @license       gplv2
 * @version       1.0.3
 *
 * @wordpress-plugin
 * Plugin Name:   InfographicNinja WP
 * Plugin URI:    https://outline.ninja/
 * Description:   Insert Relevant Infographics into WP Post using AI
 * Version:       1.0.3
 * Author:        Outline Ninja
 * Author URI:    https://outline.ninja/support/
 * Text Domain:   infographicninja
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with InfographicNinja WP. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if (!defined('ABSPATH')) exit;

// Plugin name
define('INFOGRAPHI_NAME', 'InfographicNinja WP');

// Plugin version
define('INFOGRAPHI_VERSION', '1.0.3');

// Plugin Root File
define('INFOGRAPHI_PLUGIN_FILE', __FILE__);

// Plugin base
define('INFOGRAPHI_PLUGIN_BASE', plugin_basename(INFOGRAPHI_PLUGIN_FILE));

// Plugin Folder Path
define('INFOGRAPHI_PLUGIN_DIR', plugin_dir_path(INFOGRAPHI_PLUGIN_FILE));

// Plugin Folder URL
define('INFOGRAPHI_PLUGIN_URL', plugin_dir_url(INFOGRAPHI_PLUGIN_FILE));

/**
 * Load the main class for the core functionality
 */
require_once INFOGRAPHI_PLUGIN_DIR . 'core/class-infographicninja.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  Outline Ninja
 * @since   1.0.0
 * @return  object|Infographicninja
 */
function INFOGRAPHI() {
	return Infographicninja::instance();
}

INFOGRAPHI();

// Add a settings link on the plugin page
function infographicninja_settings_link($links) {
	$settings_link = '<a href="admin.php?page=infographicninja_settings">Settings</a>';
	array_unshift($links, $settings_link);
	return $links;
}

$plugin_basename = plugin_basename(__FILE__);
add_filter("plugin_action_links_$plugin_basename", 'infographicninja_settings_link');

// Initialize the settings page
function infographicninja_settings_init() {
	register_setting('infographicninja_settings_group', 'INFGJ_x_rapidapi_key');
	register_setting('infographicninja_settings_group', 'INFGJ_about_us');
	register_setting('infographicninja_settings_group', 'INFGJ_logo_url');
	register_setting('infographicninja_settings_group', 'INFGJ_company_name');

	add_settings_section(
		'infographicninja_settings_section',
		'Plugin Settings',
		'infographicninja_settings_section_callback',
		'infographicninja_settings'
	);

	add_settings_field(
		'INFGJ_x_rapidapi_key',
		'X-RapidAPI-Key',
		'infographicninja_x_rapidapi_key_callback',
		'infographicninja_settings',
		'infographicninja_settings_section'
	);

	add_settings_field(
		'INFGJ_about_us',
		'About Us',
		'infographicninja_about_us_callback',
		'infographicninja_settings',
		'infographicninja_settings_section'
	);

	add_settings_field(
		'INFGJ_logo_url',
		'Logo URL',
		'infographicninja_logo_url_callback',
		'infographicninja_settings',
		'infographicninja_settings_section'
	);

	add_settings_field(
		'INFGJ_company_name',
		'Company Name',
		'infographicninja_company_name_callback',
		'infographicninja_settings',
		'infographicninja_settings_section'
	);
}
add_action('admin_init', 'infographicninja_settings_init');

function INFGJ_get_plain_text_content($post_content) {
    // Remove HTML tags
    $stripped_content = strip_tags($post_content);

    // Decode HTML entities
    $plain_text_content = html_entity_decode($stripped_content, ENT_QUOTES | ENT_HTML5, 'UTF-8');

    // Remove extra whitespaces and trim
    $plain_text_content = preg_replace('/\s+/', ' ', $plain_text_content);
    $plain_text_content = trim($plain_text_content);

    return $plain_text_content;
}

// Callback functions for displaying settings fields
function infographicninja_settings_section_callback() {
	echo '<p>Enter your plugin settings below. You can get your API key <a target="_blank" href="https://rapidapi.com/outline-ninja-outline-ninja-default/api/ai-infographics-generator-api/">here</a>. The about us and company name will be placed in the footer of the Infographic. We suggest using a wide version of your logo.</p>';
}

function infographicninja_x_rapidapi_key_callback() {
	$value = get_option('INFGJ_x_rapidapi_key');
	echo '<input type="text" name="INFGJ_x_rapidapi_key" value="' . esc_attr($value) . '" />';
}

function infographicninja_about_us_callback() {
	$value = get_option('INFGJ_about_us');
	echo '<textarea name="INFGJ_about_us">' . esc_textarea($value) . '</textarea>';
}

function infographicninja_logo_url_callback() {
	$value = get_option('INFGJ_logo_url');
	echo '<input type="text" name="INFGJ_logo_url" value="' . esc_attr($value) . '" />';
}

function infographicninja_company_name_callback() {
	$value = get_option('INFGJ_company_name');
	echo '<input type="text" name="INFGJ_company_name" value="' . esc_attr($value) . '" />';
}

// Settings page content
function infographicninja_settings_page() {
	?>
	<div class="wrap">
		<h1>Infographic Ninja Settings</h1>
		<form method="post" action="options.php">
			<?php
			settings_fields('infographicninja_settings_group');
			do_settings_sections('infographicninja_settings');
			submit_button();
			?>
		</form>
	</div>
	<?php
}
// Hook to modify the admin menu
function infographicninja_modify_admin_menu() {
    // Check if the user can edit the plugin settings
    if (!current_user_can('edit_infographicninja_settings')) {
        // Remove the plugin settings page from the menu
        remove_menu_page('infographicninja_settings');
    }
}
add_action('admin_menu', 'infographicninja_modify_admin_menu');

register_activation_hook(__FILE__, 'infographicninja_plugin_activation');
function infographicninja_plugin_activation() {
    // Define the custom capability
    $role = get_role('administrator'); // Or any other role
    $role->add_cap('edit_infographicninja_settings');
}
// Hook to add the settings page
function infographicninja_add_settings_page() {
	add_menu_page(
		'Infographic Ninja Settings',
		'Infographic Ninja',
		'manage_options',
		'infographicninja_settings',
		'infographicninja_settings_page'
	);
}
add_action('admin_menu', 'infographicninja_add_settings_page');

// Add a custom meta box to the post editing screen
function infographicninja_add_meta_box() {
    add_meta_box(
        'infographicninja_meta_box',
        'Infographics',
        'infographicninja_meta_box_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'infographicninja_add_meta_box');

// Meta box callback function
function infographicninja_meta_box_callback($post) {
    wp_nonce_field(basename(__FILE__), 'infographicninja_nonce');

    echo '<button class="button" id="generate_infographic_button">Generate Infographic</button>';
}

// Enqueue script for the post editing screen
function infographicninja_enqueue_script($hook) {
    global $post;

    if ($hook == 'post-new.php' || $hook == 'post.php') {
        wp_enqueue_script('infographicninja-script', INFOGRAPHI_PLUGIN_URL . 'js/infographicninja-script.js', array('jquery'), INFOGRAPHI_VERSION, true);
        wp_localize_script('infographicninja-script', 'infographicninja_ajax_object', array('ajax_url' => admin_url('admin-ajax.php'), 'post_id' => $post->ID,'nonce' => wp_create_nonce('infographicninja_nonce')));
		
		 wp_enqueue_style('infographicninja-styles', INFOGRAPHI_PLUGIN_URL . 'css/infographicninja-styles.css', array(), INFOGRAPHI_VERSION);
    }
}
add_action('admin_enqueue_scripts', 'infographicninja_enqueue_script');

// Ajax callback function to handle the Generate Infographic button click
function infographicninja_generate_infographic() {
	//wp_send_json_error('line 224.');
    check_ajax_referer('infographicninja_nonce', 'security');	//prevents submit by others and POST[] abuse.
	//wp_send_json_error('line 226.');
    $post_id = sanitize_text_field($_POST['post_id']);
	
	if (!current_user_can('edit_posts')) {
        wp_send_json_error('You do not have permission to perform this action.');
    }
	
    //$plugin_settings = get_option('infographicninja_settings_group');
	$INFGJ_x_rapidapi_key = get_option('INFGJ_x_rapidapi_key');
	$INFGJ_about_us = get_option('INFGJ_about_us');
	$INFGJ_logo_url = get_option('INFGJ_logo_url');
	$INFGJ_company_name = get_option('INFGJ_company_name');
	
//wp_send_json_error("post $post_id.");
    // Check if all required settings are present
    if ($INFGJ_x_rapidapi_key==false ||$INFGJ_about_us==false || $INFGJ_logo_url==false || $INFGJ_company_name==false) {
        wp_send_json_error('Plugin settings are incomplete. Please fill in all settings before using this plugin.'.$plugin_settings['INFGJ_x_rapidapi_key']);
    }

    // Check if post title and text meet the criteria
    $post_title = get_the_title($post_id);
    $post_content = get_post_field('post_content', $post_id);

    if (empty($post_title) || strlen($post_content) < 500) {
        wp_send_json_error('Post title must not be empty, and post text must be at least 500 characters long.');
    }

    // Proceed with API call
   // Prepare data for POST request
		$post_data = array(
			'title' => $post_title,
			'text' => INFGJ_get_plain_text_content($post_content),
			'aboutus' => $INFGJ_about_us,
			'logourl' => $INFGJ_logo_url,
			'companyname' => $INFGJ_company_name,
		);

		// Set headers
		$headers = array(
			'X-RapidAPI-Host' => 'ai-infographics-generator-api.p.rapidapi.com',
			'X-RapidAPI-Key' => $INFGJ_x_rapidapi_key,
			'Content-Type' => 'application/x-www-form-urlencoded',
		);

		// Make POST request using wp_remote_post
		$response = wp_remote_post(
			'https://ai-infographics-generator-api.p.rapidapi.com/save-data-API.php',
			array(
				'body' => $post_data,
				'headers' => $headers,
				'sslverify' => false, // Set to true if needed
				'timeout' => 50,
			)
		);

	// Check for errors
	if (is_wp_error($response))
	{
		wp_send_json_error("HTTP Error: " . $response->get_error_message());
	}
	else
	{
		// Process the response
		$body = wp_remote_retrieve_body($response);
		if ($body == '{"message":"You are not subscribed to this API."}') {
			wp_send_json_error('Please check your API key with rapidAPI.com.');
		} 
		elseif (stripos($body, 'error')!==false) {
			wp_send_json_error('Failed to create infographic. Please try again and make sure you are not using any banned topics.');
		}
		else
		{
		   // Save image to media library
			$upload_dir = wp_upload_dir();
				// Create directory if it doesn't exist: infographic_ninja_images will be used by this plugin
				$directory = $upload_dir['path'] . '/infographic_ninja_images/';
				if (!file_exists($directory)) {
					wp_mkdir_p($directory);
				}
			$filename = sanitize_file_name($post_title) . '.png';

			// Check if the file already exists
			$i = 1;
			while (file_exists($upload_dir['path'] . '/infographic_ninja_images/' . $filename)) {
				$filename = sanitize_file_name($post_title) . '_' . $i . '.png';
				$i++;
			}

			$image_path = $upload_dir['path'] . '/infographic_ninja_images/' . $filename;
			
			
			
			file_put_contents($image_path, $body);

			// Insert image into the post
			$attachment = array(
				'post_title' => 'Infographic: '.$post_title,
				'post_excerpt' => 'Infographic: '.$post_title,
				'post_content' => 'Infographic - '.$post_title,
				'post_meta' => 'Infographic - '.$post_title,
				'post_status' => 'inherit',
				'post_mime_type' => 'image/png',
			);

			$attach_id = wp_insert_attachment($attachment, $image_path, $post_id);
			update_post_meta( $attach_id, '_wp_attachment_image_alt', 'Infographic - '.$post_title);
			
			$attach_data = wp_generate_attachment_metadata($attach_id, $image_path);
			wp_update_attachment_metadata($attach_id, $attach_data);

			// Return the attachment ID
			  wp_send_json_success($attach_id);
			//wp_send_json_success("Success! Infographic generated and inserted into media library.");
		}
	}
}
add_action('wp_ajax_infographicninja_generate_infographic', 'infographicninja_generate_infographic');
