<?php
/*
 * Plugin Name: Default Media
 * Plugin URI: http://wordpress.lowtone.nl/media-defaults
 * Description: Select default media for Posts.
 * Version: 1.0
 * Author: Lowtone <info@lowtone.nl>
 * Author URI: http://lowtone.nl
 * License: http://wordpress.lowtone.nl/license
 */
/**
 * @author Paul van der Meijs <code@lowtone.nl>
 * @copyright Copyright (c) 2011-2012, Paul van der Meijs
 * @license http://wordpress.lowtone.nl/license/
 * @version 1.0
 * @package wordpress\plugins\lowtone\media\defaults
 */

namespace lowtone\media\defaults {

	use lowtone\content\packages\Package,
		lowtone\ui\forms\Form,
		lowtone\ui\forms\Input;

	// Includes
	
	if (!include_once WP_PLUGIN_DIR . "/lowtone-content/lowtone-content.php") 
		return trigger_error("Lowtone Content plugin is required", E_USER_ERROR) && false;

	$__i = Package::init(array(
			Package::INIT_PACKAGES => array("lowtone"),
			Package::INIT_MERGED_PATH => __NAMESPACE__,
			Package::INIT_SUCCESS => function() {

				add_filter("get_post_metadata", function($value, $objectId, $metaKey, $single) {
					if (!$single)
						return $value;

					if ("_thumbnail_id" != $metaKey)
						return $value;

					return reset(get_post_meta($objectId, $metaKey, false)) ?: defaultMedia($objectId);
				}, 10, 4);

				add_action("admin_init", function() {

					register_setting("media", "lowtone_media_defaults");

				});

				add_action("load-options-media.php", function() {

					add_settings_section("lowtone_media_defaults", __("Default media", "lowtone_media_defaults"), function() {
						echo '<p>' . __('Select default media for posts.', "lowtone_media_defaults") . '</p>';
					}, "media");

					$form = new Form();

					$defaultMedia = defaultMedia();

					add_settings_field("lowtone_media_defaults_default", __("Default thumbnail ID", "lowtone_media_defaults"), function() use ($form, &$defaultMedia) {

						$form
							->createInput(Input::TYPE_TEXT, array(
								Input::PROPERTY_NAME => array("lowtone_media_defaults", "default"),
								Input::PROPERTY_VALUE => $defaultMedia["default"]
							))
							->addClass("setting")
							->out();

					}, "media", "lowtone_media_defaults");

				});

				// Register text domain
				
				add_action("plugins_loaded", function() {
					load_plugin_textdomain("lowtone_media_defaults", false, basename(__DIR__) . "/assets/languages");
				});

				return true;
			}
		));

	if (!$__i)
		return false;

	function defaultMedia($postId = NULL) {
		$defaultMedia = array_merge(array(
				"default" => NULL,
			), get_option("lowtone_media_defaults") ?: array());

		if (!isset($postId))
			return $defaultMedia;

		return $defaultMedia["default"];
	}

}