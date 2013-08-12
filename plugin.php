<?php

/*
Plugin Name: GitHub Plugin Updater
Plugin URI:
Description: Allows automatic updating for plugins hosted on GitHub
Version: 0.2.6
Author: Zach Lanich
Author URI: https://www.ZachLanich.com
License: Undecided
*/

add_action( 'activated_plugin', 'es_github_plugin_updater_load_first' );
function es_github_plugin_updater_load_first()
{
	$path = basename(dirname(__FILE__)) .'/'. basename(__FILE__);

	if ( $plugins = get_option( 'active_plugins' ) ) {
		ob_start();
		var_dump($plugins);
		$stuff = ob_get_clean();
		error_log($stuff, 3, dirname(__FILE__). '/log.txt');
		if ( $key = array_search( $path, $plugins ) ) {
			//error_log($key, 3, dirname(__FILE__). '/log.txt');
			array_splice( $plugins, $key, 1 );
			array_unshift( $plugins, $path );
			update_option( 'active_plugins', $plugins );
			ob_start();
			var_dump(get_option( 'active_plugins' ));
			$stuff = ob_get_clean();
			error_log($stuff, 3, dirname(__FILE__). '/log.txt');
		}
	}
}

/**
 * Class WP_GitHub_Plugin_Updater
 *
 * Allows automatic updating for plugins hosted on GitHub
 */
class WP_GitHub_Plugin_Updater {

	public function __construct() {

		require_once 'lib/updater.php';

		if ( is_admin() ) {

			$config = array(
				// These top 2 might not work on older PHP installs - I've seen basename() behave strangely
				'slug' => basename(dirname(__FILE__)) .'/'. basename(__FILE__),
				'proper_folder_name' => basename(dirname(__FILE__)),
				'api_url' => 'https://api.github.com/repos/eclipseshadow/wordpress-github-plugin-updater',
				'raw_url' => 'https://raw.github.com/eclipseshadow/wordpress-github-plugin-updater/master',
				'github_url' => 'https://github.com/eclipseshadow/wordpress-github-plugin-updater',
				'zip_url' => 'https://github.com/eclipseshadow/wordpress-github-plugin-updater/archive/master.zip',
				'sslverify' => true,
				'requires' => '3.0',
				'tested' => '3.6',
				'readme' => 'README.md',
				'access_token' => '',
			);

			new WP_GitHub_Updater( $config );

		}

	}

}

add_action('init', create_function('', '$wp_github_plugin_updater = new WP_GitHub_Plugin_Updater();'));