=== InfographicNinja WP ===
Author URI: https://outline.ninja/support/
Plugin URI: https://outline.ninja/
Get API key: https://rapidapi.com/outline-ninja-outline-ninja-default/api/ai-infographics-generator-api/
Contributors: NK
Tags: Infographics, AI, Generate
Requires at least: 6.4
Tested up to: 6.4
Requires PHP: 7.0
Stable tag: 1.0.3
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Insert Relevant Infographics into WP Post using AI

== Description ==

This plugin uses your WP Post title to generate an informational infographic on the same topic in just a few clicks!

All you need is an API key from https://rapidapi.com/outline-ninja-outline-ninja-default/api/ai-infographics-generator-api/ (A subscription is required)

3rd Party Services using by this plugin:
-We use https://rapidapi.com for tracking payments, API key, tracking your credits
-We use https://openai.com for the artificial intelligence text generation part.
-We use https://Outline.ninja as our website to host our proprietary code that is the the external generation of the image.

Service Terms and Privacy Policy links:
-https://rapidapi.com/terms/
-https://openai.com/policies
-https://outline.ninja/terms-eula-and-privacy-policy/


== Frequently Asked Questions ==

= How much does each Infographic Cost =

The API credits cost 15 cents each (USD).


== Installation ==

1. Go to `Plugins` in the Admin menu
2. Click on the button `Add new`
3. Search for `InfographicNinja WP` and click 'Install Now' or click on the `upload` link to upload `infographicninja.zip`
4. Click on `Activate plugin`
5. Go to https://rapidapi.com/outline-ninja-outline-ninja-default/api/ai-infographics-generator-api/
6. Create an account on RapidAPI and subscribe to a plan to obtain a key (X-RapidAPI-Key)
7. Enter your rapidapi credentials into the pluggin settings

== Changelog ==

= 1.0.0: January 21, 2024 =
* Creation of InfographicNinja WP plugin

= 1.0.1: January 22, 2024 =
* Added validation for incorrect API keys

= 1.0.2: January 24, 2024 =
* made functions more unique to avoid conflicts
* added version compatibility
* added sanitization for post id
* replaced curl with http api

= 1.0.3: March 5, 2024 =
* made all images stored in special folder for this plugin
* added nonce for ajax call security
* made use of current_user_can() in order to prevent users without the right permissions from accessing things