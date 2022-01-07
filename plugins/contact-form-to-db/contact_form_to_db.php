<?php
/*
Plugin Name: Contact Form to DB by BestWebSoft
Plugin URI: https://bestwebsoft.com/products/wordpress/plugins/contact-form-to-db/
Description: Save and manage contact form messages. Never lose important data.
Author: BestWebSoft
Text Domain: contact-form-to-db
Domain Path: /languages
Version: 1.6.8
Author URI: https://bestwebsoft.com/
License: GPLv2 or later
*/
/*  @ Copyright 2021  BestWebSoft  ( https://support.bestwebsoft.com )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/
 
/*
* Function for adding menu and submenu
*/

if ( ! function_exists( 'cntctfrmtdb_admin_menu' ) ) {
	function cntctfrmtdb_admin_menu() {
		global $submenu, $wp_version, $cntctfrmtdb_plugin_info;

		$hook = add_menu_page( 'CF to DB', 'CF to DB', 'edit_posts', 'cntctfrmtdb_manager', 'cntctfrmtdb_manager_page', 'none', '56.1' );
		add_submenu_page( 'cntctfrmtdb_manager', __( 'Contact Form 7 to DB Pro', 'contact-form-to-db' ), 'CF7 to DB', 'manage_options', 'cntctfrmtdb_manager_cf7', 'cntctfrmtdb_manager_pro' );
        add_submenu_page( 'cntctfrmtdb_manager', __( 'Pojo Form to DB Pro', 'contact-form-to-db' ), 'POJO to DB', 'manage_options', 'cntctfrmtdb_manager_pojo', 'cntctfrmtdb_manager_pro' );

		if ( isset( $submenu['wpcf7'] ) ) {
			$submenu['wpcf7'][] = array(
				'CF7 to DB',
				'manage_options',
				admin_url( 'admin.php?page=cntctfrmtdb_manager_cf7' )
			);
		}

        if ( isset( $submenu['edit.php?post_type=pojo_forms'] ) ) {
            $submenu['edit.php?post_type=pojo_forms'][] = array(
                'POJO to DB',
                'manage_options',
                admin_url( 'admin.php?page=cntctfrmtdb_manager_pojo' )
            );
        }
		$settings = add_submenu_page(
			'cntctfrmtdb_manager',
			__( 'Contact Form to DB Settings', 'contact-form-to-db' ),
			__( 'Settings', 'contact-form-to-db' ),
			'manage_options',
			'contact_form_to_db.php',
			'cntctfrmtdb_settings_page'
		);
		add_submenu_page(
			'cntctfrmtdb_manager',
			'BWS Panel',
			'BWS Panel',
			'manage_options',
			'cntctfrmtdb-bws-panel',
			'bws_add_menu_render'
		);

		if ( isset( $submenu['cntctfrmtdb_manager'] ) ) {
			$submenu['cntctfrmtdb_manager'][] = array(
				'<span style="color:#d86463"> ' . __('Upgrade to Pro', 'contact-form-to-db' ) . '</span>',
				'manage_options',
				'https://bestwebsoft.com/products/wordpress/plugins/contact-form-to-db/?k=5906020043c50e2eab1528d63b126791&pn=91&v=' . $cntctfrmtdb_plugin_info["Version"] . '&wp_v=' . $wp_version );
		}

		add_action( 'load-' . $hook, 'cntctfrmtdb_add_options_manager' );
        add_action( 'load-' . $settings, 'cntctfrmtdb_add_tabs' );
	}
}

if ( ! function_exists( 'cntctfrmtdb_plugins_loaded' ) ) {
	function cntctfrmtdb_plugins_loaded() {
		/* Internationalization, first(!) */
		load_plugin_textdomain( 'contact-form-to-db', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

/*
* Function initialisation plugin
*/
if ( ! function_exists( 'cntctfrmtdb_init' ) ) {
	function cntctfrmtdb_init() {
		global $cntctfrmtdb_plugin_info, $cntctfrmtdb_pages;

		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );

		if ( empty( $cntctfrmtdb_plugin_info ) ) {
			if ( ! function_exists( 'get_plugin_data' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$cntctfrmtdb_plugin_info = get_plugin_data( __FILE__ );
		}

		/* Function check if plugin is compatible with current WP version  */
		bws_wp_min_version_check( plugin_basename( __FILE__ ), $cntctfrmtdb_plugin_info, '4.5' );

		/* Call register settings function */
		$cntctfrmtdb_pages = array(
			'cntctfrmtdb_manager',
			'contact_form_to_db.php'
		);
		if ( isset( $_REQUEST['page'] ) && 'contact_form_to_db.php' == $_REQUEST['page'] )
		    cntctfrmtdb_settings();
	}
}

if ( ! function_exists( 'cntctfrmtdb_admin_init' ) ) {
	function cntctfrmtdb_admin_init() {
		global $bws_plugin_info, $cntctfrmtdb_plugin_info, $pagenow, $cntctfrmtdb_options;

		/* Add variable for bws_menu */
		if ( empty( $bws_plugin_info ) )
			$bws_plugin_info = array( 'id' => '91', 'version' => $cntctfrmtdb_plugin_info["Version"] );

		if ( isset( $_REQUEST['page'] ) && 'cntctfrmtdb_manager' == $_REQUEST['page'] )
			cntctfrmtdb_action_links();

		if ( 'plugins.php' == $pagenow ) {
			/* Install the option defaults */
			if ( function_exists( 'bws_plugin_banner_go_pro' ) ) {
				cntctfrmtdb_settings();
				bws_plugin_banner_go_pro( $cntctfrmtdb_options, $cntctfrmtdb_plugin_info, 'cntctfrmtdb', 'contact-form-to-db', 'a0297729ff05dc9a4dee809c8b8e94bf', '91', 'contact-form-to-db' );
			}
		}
	}
}

if ( ! function_exists( 'cntctfrmtdb_get_options_default' ) ) {
	function cntctfrmtdb_get_options_default() {
		global $cntctfrmtdb_plugin_info;

		$default_options = array(
			'plugin_option_version'     => $cntctfrmtdb_plugin_info["Version"],
			'save_messages_to_db'   	=> 1,
			'format_save_messages'  	=> 'xml',
			'csv_separator'         	=> ",",
			'csv_enclosure'         	=> "\"",
			'mail_address'          	=> 1,
			'delete_messages'       	=> 1,
			'delete_messages_after' 	=> 'daily',
			'first_install'             => strtotime( "now" ),
			'display_settings_notice'	=> 1,
			'suggest_feature_banner'	=> 1,
		);

		return $default_options;
	}
}

/*
* Function to register default settings of plugin
*/
if ( ! function_exists( 'cntctfrmtdb_settings' ) ) {
	function cntctfrmtdb_settings() {
		global $cntctfrmtdb_options, $cntctfrmtdb_plugin_info, $wpdb;
		$cntctfrmtdb_db_version = '1.4';

		/* add options to database */
		if ( ! get_option( 'cntctfrmtdb_options' ) )
			add_option( 'cntctfrmtdb_options', cntctfrmtdb_get_options_default() );

		/* get options from database to operate with them */
		$cntctfrmtdb_options = get_option( 'cntctfrmtdb_options' );

		/* create or update db table */
		if ( ! isset( $cntctfrmtdb_options['plugin_db_version'] ) || $cntctfrmtdb_options['plugin_db_version'] != $cntctfrmtdb_db_version ) {
			cntctfrmtdb_create_table();
			$cntctfrmtdb_options['plugin_db_version'] = $cntctfrmtdb_db_version;

			/**
			 * @deprecated since 1.6.8
			 * @todo remove after 28.03.2022
			 */
			if ( isset( $cntctfrmtdb_options['plugin_option_version'] ) && version_compare( $cntctfrmtdb_options['plugin_option_version'] , '1.6.8', '<' ) ) {

				$prefix = $wpdb->prefix . 'cntctfrmtdb_';
                $wpdb->query( "ALTER TABLE `" . $prefix . "to_email` MODIFY COLUMN `email` CHAR(255)" );
			}
			/* end deprecated */

			$update_option = true;
		}

		/* Array merge in case this version has added new options */
		if ( ! isset( $cntctfrmtdb_options['plugin_option_version'] ) || $cntctfrmtdb_options['plugin_option_version'] != $cntctfrmtdb_plugin_info["Version"] ) {

			if ( is_multisite() ) {
				switch_to_blog( 1 );
				register_uninstall_hook( __FILE__, 'cntctfrmtdb_delete_options' );
				restore_current_blog();
			} else {
				register_uninstall_hook( __FILE__, 'cntctfrmtdb_delete_options' );
			}

			$cntctfrmtdb_options = array_merge( cntctfrmtdb_get_options_default(), $cntctfrmtdb_options );
			$cntctfrmtdb_options['plugin_option_version'] = $cntctfrmtdb_plugin_info["Version"];
			/* show pro features */
			$cntctfrmtdb_options['hide_premium_options'] = array();
			$update_option = true;
		}

		if ( isset( $update_option ) )
			update_option( 'cntctfrmtdb_options', $cntctfrmtdb_options );
	}
}


/*
* Function to create a new tables in database
*/
if ( ! function_exists( 'cntctfrmtdb_create_table' ) ) {
	function cntctfrmtdb_create_table() {
		global $wpdb;
		$prefix = $wpdb->prefix . 'cntctfrmtdb_';
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "message_status` (
			`id` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
			`name` CHAR(30) NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "blogname` (
			`id` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
			`blogname` CHAR(100) NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "to_email` (
			`id` SMALLINT UNSIGNED NOT NULL AUTO_INCREMENT,
			`email` CHAR(255) NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "hosted_site` (
			`id` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
			`site` CHAR(50) NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "refer` (
			`id` TINYINT(2) UNSIGNED NOT NULL AUTO_INCREMENT,
			`refer` CHAR(50) NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "message` (
			`id` INT(6) UNSIGNED NOT NULL AUTO_INCREMENT,
			`from_user` CHAR(50) NOT NULL,
			`user_email` CHAR(50) NOT NULL,
			`send_date` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
			`subject` TINYTEXT NOT NULL,
			`message_text` TEXT NOT NULL,
			`custom_fields` TEXT NOT NULL,
			`was_read` TINYINT(1) NOT NULL,
			`sent` TINYINT(1) NOT NULL,
			`dispatch_counter` SMALLINT UNSIGNED NOT NULL,
			`status_id` TINYINT(2) UNSIGNED NOT NULL,
			`to_id` SMALLINT UNSIGNED NOT NULL,
			`blogname_id` TINYINT UNSIGNED NOT NULL,
			`hosted_site_id` TINYINT(2) UNSIGNED NOT NULL,
			`refer_id` TINYINT(2) UNSIGNED NOT NULL,
			`attachment_status` INT(1) UNSIGNED NOT NULL,
			PRIMARY KEY (`id`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );
		$sql = "CREATE TABLE IF NOT EXISTS `" . $prefix . "field_selection` (
			`cntctfrm_field_id` INT NOT NULL,
			`message_id` MEDIUMINT(6) UNSIGNED NOT NULL,
			`field_value` CHAR(50) NOT NULL
			) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
		dbDelta( $sql );

		$status = array( 'normal',
			'spam',
			'trash'
		);
		foreach ( $status as $key => $value ) {
			$db_row = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM `" . $prefix . "message_status` WHERE `name` = %s", $value ), ARRAY_A );
			if ( ! isset( $db_row ) || empty( $db_row ) ) {
				$wpdb->insert( $prefix . "message_status", array( 'name' => $value ), array( '%s' ) );
			}
		}
	}
}

/*
 * Write plugin settings and create neccessary tables for plugin in database
 */
if ( ! function_exists ( 'cntctfrmtdb_activation' ) ) {
	function cntctfrmtdb_activation( $networkwide ) {
		global $wpdb;
		if ( function_exists( 'is_multisite' ) && is_multisite() && $networkwide ) {
			$cntctfrm_blog_id = $wpdb->blogid;
			$cntctfrmtdb_get_blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
			foreach ( $cntctfrmtdb_get_blogids as $blog_id ) {
				switch_to_blog( $blog_id );
				cntctfrmtdb_settings();
				cntctfrmtdb_create_table();
			}
			switch_to_blog( $cntctfrm_blog_id );

			switch_to_blog( 1 );
			register_uninstall_hook( __FILE__, 'cntctfrmtdb_delete_options' );

			return;
		} else {
			cntctfrmtdb_settings();
			cntctfrmtdb_create_table();

			register_uninstall_hook( __FILE__, 'cntctfrmtdb_delete_options' );
		}
	}
}

/*
* Function to add stylesheets and scripts for admin bar
*/
if ( ! function_exists ( 'cntctfrmtdb_admin_head' ) ) {
	function cntctfrmtdb_admin_head() {
		global $cntctfrmtdb_pages;

		wp_enqueue_style( 'cntctfrmtdb_icon_stylesheet', plugins_url( 'css/icon.css', __FILE__ ) );

		if ( isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], $cntctfrmtdb_pages ) ) {
			wp_enqueue_style( 'cntctfrmtdb_stylesheet', plugins_url( 'css/style.css', __FILE__ ) );
			$script_vars = array(
				'letter'           => __( 'Letter' , 'contact-form-to-db' ),
				'spam'             => __( 'Spam!' , 'contact-form-to-db' ),
				'trash'            => __( 'in Trash' , 'contact-form-to-db' ),
				'statusNotChanged' => __( 'Status was not changed' , 'contact-form-to-db' ),
				'preloaderSrc'     => plugins_url( 'images/preloader.gif', __FILE__ )
			);
			wp_enqueue_script( 'cntctfrmtdb_script', plugins_url( 'js/script.js', __FILE__ ) );
			wp_localize_script( 'cntctfrmtdb_script', 'cntctfrmtdb', $script_vars );
			bws_enqueue_settings_scripts();
		}
	}
}

/*
* Function for displaying settings page of plugin
*/
if ( ! function_exists( 'cntctfrmtdb_settings_page' ) ) {
	function cntctfrmtdb_settings_page() {
		if ( ! class_exists( 'Bws_Settings_Tabs' ) )
			require_once( dirname( __FILE__ ) . '/bws_menu/class-bws-settings.php' );
		require_once( dirname( __FILE__ ) . '/includes/class-cntctfrmtdb-settings.php' );
		$page = new Cntctfrmtdb_Settings_Tabs( plugin_basename( __FILE__ ) );
        if ( method_exists( $page, 'add_request_feature' ) )
            $page->add_request_feature();
        ?>
        <div class="wrap">
            <h1><?php _e( 'Contact Form to DB Settings', 'contact-form-to-db' ); ?></h1>
            <noscript><div class="error below-h2"><p><strong><?php _e( "Please enable JavaScript in your browser.", 'contact-form-to-db' ); ?></strong></p></div></noscript>
			<?php $page->display_content(); ?>
        </div>
    <?php }
}

if ( ! function_exists( 'cntctfrm_options_for_this_plugin' ) ) {
	function cntctfrm_options_for_this_plugin() {
		global $cntctfrm_options_for_this_plugin;
		include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		$cntctfrm_options_for_this_plugin = get_option( 'cntctfrm_options' );
	}
}

if ( ! function_exists( 'cntctfrmtdb_is_duplicate_message' ) ) {
	function cntctfrmtdb_is_duplicate_message( $user, $messgae ) {
		global $wpdb;

		$prefix = $wpdb->prefix . 'cntctfrmtdb_';
		$previous_message_data = $wpdb->get_row( "SELECT `id`, `from_user`, `message_text`, `dispatch_counter` FROM `" . $prefix . "message` ORDER BY `id` DESC", ARRAY_A );
		if (
			! empty( $previous_message_data ) &&
			$user == $previous_message_data['from_user'] &&
			$messgae == $previous_message_data['message_text']
		) {
			$counter = intval( $previous_message_data['dispatch_counter'] );
			$wpdb->update( $prefix . 'message',
				array( 'dispatch_counter' => ++$counter ),
				array( 'id' => $previous_message_data['id'] )
			);
			return true;
		} else {
			return false;
		}
	}
}

if ( ! function_exists( 'cntctfrmtdb_get_attachment_data' ) ) {
	function cntctfrmtdb_get_attachment_data( $path_of_uploaded_file ) {
		global $path_of_uploaded_file_cf;

		$path_of_uploaded_file_cf[] = $path_of_uploaded_file;
	}
}

/* Function for CF save new message in database */
if ( ! function_exists( 'cntctfrmtdb_get_mail_data' ) ) {
	function cntctfrmtdb_get_mail_data( $to ) {
		global $wpdb, $cntctfrmtdb_options, $path_of_uploaded_file_cf, $message_id, $cntctfrm_options_for_this_plugin;

		if ( empty( $cntctfrmtdb_options ) )
			cntctfrmtdb_settings();

		$prefix = $wpdb->prefix . 'cntctfrmtdb_';

		$from_user = isset( $_POST['cntctfrm_contact_name'] ) ? sanitize_text_field( $_POST['cntctfrm_contact_name'] ) : '';
		$message_text = isset( $_POST['cntctfrm_contact_message'] ) ? sanitize_text_field( $_POST['cntctfrm_contact_message'] ) : '';

		if ( ! cntctfrmtdb_is_duplicate_message( $from_user, $message_text ) ) {
			$user_email = isset( $_POST['cntctfrm_contact_email'] ) ? sanitize_email( $_POST['cntctfrm_contact_email'] ) : '';
			$subject = isset( $_POST['cntctfrm_contact_subject'] ) ? sanitize_text_field( $_POST['cntctfrm_contact_subject'] ) : '';

			/* Insert data about who was adressed to email */
			$to_email_id = $wpdb->get_var( "SELECT `id` FROM `" . $prefix . "to_email` WHERE `email`='" . $to['sendto'] . "'" );
			if ( ! isset( $to_email_id ) ) {
				$wpdb->insert( $prefix . 'to_email', array( 'email' => $to['sendto'] ) );
				$to_email_id = $wpdb->insert_id;
			}

			/* Insert data about blogname */
			$blogname_id = $wpdb->get_var( "SELECT `id` FROM `" . $prefix . "blogname` WHERE `blogname`='" . get_bloginfo( 'name' ) . "'" );
			if ( ! isset( $blogname_id ) ) {
				$wpdb->insert( $prefix . 'blogname', array( 'blogname' => get_bloginfo( 'name' ) ) );
				$blogname_id = $wpdb->insert_id;
			}

			/* Insert URL of hosted site */
			$blogurl_id = $wpdb->get_var( "SELECT `id` FROM `" . $prefix . "hosted_site` WHERE `site`='" . get_bloginfo( "url" ) . "'" );
			if ( ! isset( $blogurl_id ) ) {
				$wpdb->insert( $prefix . 'hosted_site', array( 'site' => get_bloginfo( "url" ) ) );
				$blogurl_id = $wpdb->insert_id;
			}

			/* Insert data about refer */
			$refer_id = $wpdb->get_var( "SELECT `id` FROM `" . $prefix . "refer` WHERE `refer`='" . $to['refer'] . "'" );
			if ( ! isset( $refer_id ) ) {
				$wpdb->insert( $prefix . 'refer', array( 'refer' => $to['refer'] ) );
				$refer_id = $wpdb->insert_id;
			}

			/* Get an array with custom fields from CF Pro */
			$custom_fields_cf = array();
			if ( class_exists( 'CustomField' ) ) {
				$cf_id = ( isset( $_POST['cntctfrmmlt_shortcode_id'] ) ? stripslashes( esc_html( $_POST['cntctfrmmlt_shortcode_id'] ) ) : ( cntctfrm_check_cf_multi_active() ? cntctfrm_get_first_form_id() : '1' ) );
                $cf_class      = new CustomField( $args = array(
                    'prefix' => 'cntctfrm',
                    'domain' => 'contact-form-pro'
                ) );
                $custom_fields = $cf_class->cstmfld_get_these_custom_fields( false, $cf_id );
                foreach ( $custom_fields as $single_field ) {
                    if ( $single_field['is_used'] ) {
                        $custom_field_title = ( ! empty( $single_field['title'] ) ) ? $single_field['title'] : __( "Custom Field", 'contact-form-to-db-pro' );
                        $value              = isset( $_POST[ 'cntctfrm_contact_custom_field_' . $single_field['id'] ] ) ? sanitize_text_field( $_POST[ 'cntctfrm_contact_custom_field_' . $single_field['id'] ] ) : "";
                        $custom_fields_cf[ $custom_field_title ] = $value;
                    }
                }
			}

			$wpdb->insert(
				$prefix . 'message',
				array(
					'from_user'         => $from_user,
					'user_email'        => $user_email,
					'send_date'			=> current_time( 'mysql' ),
					'subject'           => $subject,
					'message_text'      => $message_text,
					'custom_fields'     => serialize( $custom_fields_cf ),
					'was_read'          => 0,
					'sent'              => 0,
					'dispatch_counter'  => 0,
					'status_id'         => 1,
					'to_id'          	=> $to_email_id,
					'blogname_id'    	=> $blogname_id,
					'hosted_site_id' 	=> $blogurl_id,
					'refer_id'       	=> $refer_id,
					'attachment_status' => 0,
				)
			);

			$message_id = $wpdb->insert_id;

			if ( isset( $_POST['cntctfrm_department'] ) ) {
				if ( function_exists( 'cntctfrm_check_cf_multi_active' ) && cntctfrm_check_cf_multi_active() ) {
					if ( isset( $_POST['cntctfrmmlt_shortcode_id'] ) ) {
						$cntctfrm_options_for_multi = get_option( 'cntctfrmmlt_options_' . intval( $_POST['cntctfrmmlt_shortcode_id'] ) );
					} else {
						$cntctfrm_options_for_multi = get_option( 'cntctfrmmlt_options_1' );
					}
					$value = $cntctfrm_options_for_multi['departments']['name'][ $_POST['cntctfrm_department'] ];
				} else {
					if ( empty( $cntctfrm_options_for_this_plugin ) )
						cntctfrm_options_for_this_plugin();

					$value = $cntctfrm_options_for_this_plugin['departments']['name'][ $_POST['cntctfrm_department'] ];
				}

				$field_id = $wpdb->get_var( 'SELECT `id` FROM `' . $wpdb->prefix . "cntctfrm_field` WHERE `name`='department_selectbox'");
				$wpdb->insert( $prefix . 'field_selection',
					array(
						'cntctfrm_field_id' => $field_id,
						'message_id'        => $message_id,
						'field_value'       => $value
					)
				);
			}

			if ( ! empty( $path_of_uploaded_file_cf ) ) {
				$wpdb->update( $prefix . 'message',
					array( 'attachment_status' => 1 ),
					array( 'id' => $message_id )
				);
			}
		}
	}
}

/* Function to check was sent message or not */
if ( ! function_exists( 'cntctfrmtdb_check_dispatch' ) ) {
	function cntctfrmtdb_check_dispatch( $cntctfrm_result ) {
		global $wpdb, $message_id;

		if ( ! empty( $message_id ) && $cntctfrm_result ) {
			$wpdb->update( $wpdb->prefix . 'cntctfrmtdb_message',
				array(
                    'sent'              => 1,
                    'dispatch_counter'  => 1
                ),
				array( 'id' => $message_id )
			);
        }
	}
}

/*
* Function to handle action links
*/
if ( ! function_exists( 'cntctfrmtdb_action_links' ) ) {
	function cntctfrmtdb_action_links() {
		global $wpdb, $cntctfrm_options_for_this_plugin, $cntctfrmtdb_done_message, $cntctfrmtdb_error_message, $cntctfrmtdb_options;

		if ( ! empty( $_REQUEST['_wp_http_referer'] ) ) {
			wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
			exit;
		}

		if ( ( isset( $_REQUEST['action'] ) || isset( $_REQUEST['action2'] ) ) && check_admin_referer( plugin_basename( __FILE__ ), 'cntctfrmtdb_manager_nonce_name' ) ) {

			if ( empty( $cntctfrmtdb_options ) )
				cntctfrmtdb_settings();

			/* get option from Contact form or Contact form PRO */
			if ( ! $cntctfrm_options_for_this_plugin )
				cntctfrm_options_for_this_plugin();

			$random_number = rand( 100, 999 ); /* prefix to the names of files to be saved */

			/* We get path to 'attachments' folder */
			if ( defined( 'UPLOADS' ) ) {
				if ( ! is_dir( ABSPATH . UPLOADS ) )
					wp_mkdir_p( ABSPATH . UPLOADS );
				$save_file_path = trailingslashit( ABSPATH . UPLOADS ) . 'attachments';
			} elseif ( defined( 'BLOGUPLOADDIR' ) ) {
				if ( ! is_dir( ABSPATH . BLOGUPLOADDIR ) )
					wp_mkdir_p( ABSPATH . BLOGUPLOADDIR );
				$save_file_path = trailingslashit( ABSPATH . BLOGUPLOADDIR ) . 'attachments';
			} else {
				$upload_path = wp_upload_dir();
				$save_file_path = $upload_path['basedir'] . '/attachments';
			}
			if ( ! is_dir( $save_file_path ) ) {
				wp_mkdir_p( $save_file_path );
			}

			$prefix = $wpdb->prefix . 'cntctfrmtdb_';
			$action = ( isset( $_REQUEST['action'] ) && '-1' != $_REQUEST['action'] ) ? $_REQUEST['action'] : $_REQUEST['action2'];

			if ( ! empty( $_REQUEST['message_id'] ) ) {
				$message_id = (array) $_REQUEST['message_id'];

				$i = $error_counter = $counter = $have_not_attachment = $can_not_create_zip = $file_created = $can_not_create_file = $can_not_create_xml = 0;
				/* Create ZIP-archive if:
				create zip-archives is possible and one embodiment of the:
				1) need to save several messages in "csv"-format
				2) need to save several messages in "eml"-format */
				if ( class_exists( 'ZipArchive' ) && 'download_messages' == $action && ( 'csv' == $cntctfrmtdb_options['format_save_messages'] || 'eml' == $cntctfrmtdb_options['format_save_messages'] ) ) {
					/* create new zip-archive */
					$zip = new ZipArchive();
					$zip_name = $save_file_path . '/' .time() . ".zip";
					if ( ! $zip->open( $zip_name, ZIPARCHIVE::CREATE ) )
						$can_not_create_zip = 1;
				}
				/* we create a new "xml"-file */
				if ( in_array( $action, array( 'download_message', 'download_messages' ) ) && 'xml' == $cntctfrmtdb_options['format_save_messages'] ) {
					$xml = new DOMDocument( '1.0','utf-8' );
					$xml->formatOutput = true;
					/* create main element <messages></messages> */
					$messages = $xml->appendChild( $xml->createElement( 'cnttfrmtdb_messages' ) );
				}
				$messageArray = array();
				foreach ( $message_id as $id ) {
					if ( '' != $id ) {
						switch ( $action ) {
							case 'download_message':
							case 'download_messages':
								/* we get message content */
								$message_text = '';
								$message_data = $wpdb->get_results(
									"SELECT `from_user`, `user_email`, `send_date`, `subject`, `message_text`, `blogname`, `site`, `refer`, `email`, `custom_fields`
									FROM `" . $prefix . "message`
									LEFT JOIN `" . $prefix . "blogname` ON " . $prefix . "message.blogname_id=" . $prefix . "blogname.id
									LEFT JOIN `" . $prefix . "hosted_site` ON " . $prefix . "message.hosted_site_id=" . $prefix . "hosted_site.id
									LEFT JOIN `" . $prefix . "refer` ON " . $prefix . "message.refer_id=" . $prefix . "refer.id
									LEFT JOIN `" . $prefix . "to_email` ON " . $prefix . "message.to_id=" . $prefix . "to_email.id
									WHERE " . $prefix . "message.id=" . $id
								);
								$additional_fields = $wpdb->get_results(
									"SELECT `field_value`, `name`
									FROM `" . $prefix . "field_selection`
									LEFT JOIN " . $wpdb->prefix . "cntctfrm_field ON " . $wpdb->prefix . "cntctfrm_field.id=" . $prefix . "field_selection.cntctfrm_field_id
									WHERE " . $prefix . "field_selection.message_id=" . $id
								);
								/* forming file in "XML" format */
								if ( 'xml' == $cntctfrmtdb_options['format_save_messages'] ) {
									foreach ( $message_data as $data ) {
										foreach ( $additional_fields as $field ) {
											if ( 'address' == $field->name )
												$data_address = $field->field_value;
											elseif ( 'phone' == $field->name )
												$data_phone = $field->field_value;
											elseif ( 'user_agent' == $field->name )
												$data_user_agent = $field->field_value;
										}

										$message	= $messages->appendChild( $xml->createElement( 'cnttfrmtdb_message' ) ); /* creation main element for single message <message></message> */
										$from		= $message->appendChild( $xml->createElement( 'cnttfrmtdb_from' ) ); /* insert <from></from> in to <message></messsage> */
										$from_text	= $from->appendChild( $xml->createTextNode( $data->blogname . '&lt;' . $data->user_email . '&gt;' ) ); /* insert text in to <from></from> */
										$to			= $message->appendChild( $xml->createElement( 'cnttfrmtdb_to' ) ); /* insert <to></to> in to <message></messsage> */
										$to_text	= $to->appendChild( $xml->createTextNode( $data->email ) ); /* insert text in to <to></to> */
										if ( '' != $data->subject ) {
											$subject		= $message->appendChild( $xml->createElement( 'cnttfrmtdb_subject' ) ); /* insert <subject></subject> in to <message></messsage> */
											$subject_text	= $subject->appendChild( $xml->createTextNode( $data->subject ) ); /* insert text in to <subject></subject> */
										}
										$send_date	= $message->appendChild( $xml->createElement( 'cnttfrmtdb_send_date' ) ); /* insert <send_date></send_date> in to <message></messsage> */
										$data_text	= $send_date->appendChild( $xml->createTextNode( $data->send_date ) ); /* insert text in to <send_date></send_date> */
										$content	= $message->appendChild( $xml->createElement( 'cnttfrmtdb_content' ) ); /* insert <content></content> in to <message></messsage> */
										if ( '' != $data->subject ) {
											$name				= $content->appendChild( $xml->createElement( 'cnttfrmtdb_name' ) ); /* insert <name></name> in to <content></content> */
											$name_text	= $name->appendChild( $xml->createTextNode( $data->from_user ) ); /* insert text in to <name></name> */
										}
										if ( isset( $data_address ) && '' != $data_address ) {
											$address		= $content->appendChild( $xml->createElement( 'cnttfrmtdb_address' ) ); /* insert <address></address> in to <content></content> */
											$address_text	= $address->appendChild( $xml->createTextNode( $data_address ) ); /* insert text in to <address></address> */
										}
										if ( '' != $data->user_email ) {
											$from_email			= $content->appendChild( $xml->createElement( 'cnttfrmtdb_from_email' ) ); /* insert <from_email></from_email> in to <content></content> */
											$from_email_text	= $from_email->appendChild( $xml->createTextNode( $data->user_email ) ); /* insert text in to <from_email></from_email> */
										}
										if ( isset( $data_phone ) && '' != $data_phone ) {
											$phone			= $content->appendChild( $xml->createElement( 'cnttfrmtdb_phone' ) ); /* insert <phone></phone> in to <content></content> */
											$phone_text		= $phone->appendChild( $xml->createTextNode( $data_phone ) ); /* insert text in to <phone></phone> */
										}
										if ( '' != $data->message_text ) {
											$text			= $content->appendChild( $xml->createElement( 'cnttfrmtdb_text' ) ); /* insert <text></text> in to <content></content> */
											$message_text	= $text->appendChild( $xml->createTextNode( $data->message_text ) ); /*insert message text in to <text></text> */
										}
										if ( ! empty( $data->custom_fields ) ) {
											$custom_fields_element = $content->appendChild( $xml->createElement( 'cnttfrmtdb_custom_fields' ) );
											$custom_fields = unserialize( $data->custom_fields );
											foreach ( $custom_fields as $key => $custom_field ) {
												$text = $custom_fields_element->appendChild( $xml->createElement( 'cnttfrmtdb_custom_field' ) );
												$text->appendChild( $xml->createTextNode( $key . ': ' . $custom_field ) );
											}
										}
										$hosted_site		= $content->appendChild( $xml->createElement( 'cnttfrmtdb_hosted_site' ) ); /* insert <hosted_site></hosted_site> in to <content></content> */
										$hosted_site_text	= $hosted_site->appendChild( $xml->createTextNode( $data->site ) ); /* insert text in to <hosted_site></hosted_site> */
										$sent_from_refer	= $content->appendChild( $xml->createElement( 'cnttfrmtdb_sent_from_refer' ) ); /* insert <sent_from_refer></sent_from_refer> in to <content></content> */
										$refer_text			= $sent_from_refer->appendChild( $xml->createTextNode( $data->refer ) ); /* insert text in to <sent_from_refer></sent_from_refer> */
										if ( isset( $data_user_agent ) && '' != $data_user_agent ) {
											$user_agent			= $content->appendChild( $xml->createElement( 'cnttfrmtdb_user_agent' ) ); /* insert <user_agent></user_agent> in to <content></content> */
											$user_agent_text	= $user_agent->appendChild( $xml->createTextNode( $data_user_agent ) ); /* insert text in to <user_agent></user_agent> */
										}
									}

								/* forming file in "EML" format */
								} elseif ( 'eml' == $cntctfrmtdb_options['format_save_messages'] ) {
									foreach ( $message_data as $data ) {
										foreach ( $additional_fields as $field ) {
											if ( 'address' == $field->name )
												$data_address = $field->field_value;
											elseif ( 'phone' == $field->name )
												$data_phone = $field->field_value;
											elseif ( 'user_agent' == $field->name )
												$data_user_agent = $field->field_value;
										}

										$message_text .=
											'<html>
												<head>
													<title>'. __( "Contact from to DB", 'contact_form' );
										if ( '' != $data->blogname ) {
											$message_text .= $data->blogname;
										} else {
											$message_text .= get_bloginfo( 'name' );
										}
										$message_text .=
											'</title>
												</head>
													<body>
														<p>' . __( 'This message was re-sent from ', 'contact-form-to-db' ) . home_url() . '</p>
														<table>
															<tr>
																<td width="160">'. __( "Name", 'contact-form-to-db' ) . '</td><td>' . $data->from_user . '</td>
															</tr>';
										if ( isset( $data_address ) && '' != $data_address ) {
											$message_text .=
											'<tr>
												<td>'. __( "Address", 'contact-form-to-db' ) . '</td><td>'. $data_address .'</td>
											</tr>';
										}
										$message_text .=
											'<tr>
												<td>'. __( "Email", 'contact-form-to-db' ) .'</td><td>'. $data->user_email .'</td>
											</tr>';
										if ( isset( $data_address ) && '' != $data_phone ) {
											$message_text .=
											'<tr>
												<td>'. __( "Phone", 'contact-form-to-db' ) . '</td><td>'. $data_phone .'</td>
											</tr>';
										}
										$message_text .=
											'<tr>
												<td>' . __( "Subject", 'contact-form-to-db' ) . '</td><td>'. $data->subject .'</td>
											</tr>
											<tr>
												<td>' . __( "Message", 'contact-form-to-db' ) . '</td><td>'. $data->message_text .'</td>
											</tr>
											<tr>
												<td>' . __( 'Site', 'contact-form-to-db' ) . '</td><td>'. $data->site .'</td>
											</tr>';
										if ( ! empty( $data->custom_fields ) ) {
											$message_text .= '<tr><td>' . __( "Custom Fields", 'contact-form-to-db' ) . '</td><td>';
											$custom_fields = unserialize( $data->custom_fields );
											foreach ( $custom_fields as $key => $custom_field ) {
												$message_text .= $key . ': ' . $custom_field . '<br />';
											}
											$message_text .= '</td></tr>';
										}
										if ( 1 == $cntctfrm_options_for_this_plugin['display_sent_from'] ) {
											$ip = '';
											if ( isset( $_SERVER ) ) {
												$sever_vars = array( 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' );
												foreach ( $sever_vars as $var ) {
													if ( isset( $_SERVER[ $var ] ) && ! empty( $_SERVER[ $var ] ) ) {
														if ( filter_var( $_SERVER[ $var ], FILTER_VALIDATE_IP ) ) {
															$ip = $_SERVER[ $var ];
															break;
														} else { /* if proxy */
															$ip_array = explode( ',', $_SERVER[ $var ] );
															if ( is_array( $ip_array ) && ! empty( $ip_array ) && filter_var( $ip_array[0], FILTER_VALIDATE_IP ) ) {
																$ip = $ip_array[0];
																break;
															}
														}
													}
												}
											}

											$message_text .=
											'<tr>
												<td>' . __( 'Sent from (ip address)', 'contact-form-to-db' ) . ':</td><td>' . $ip . " ( " . @gethostbyaddr( $ip ) ." )".'</td>
											</tr>';
										}
										$message_text .=
											'<tr>
												<td>' . __( 'Date/Time', 'contact-form-to-db' ) . ':</td><td>' . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $data->send_date ) ) . '</td>
											</tr>';
										if ( '' !=  $data->refer ) {
											$message_text .=
											'<tr>
												<td>' . __( 'Sent from (referer)', 'contact-form-to-db' ) . ':</td><td>' . $data->refer . '</td>
											</tr>';
										}
										if ( isset( $data_user_agent ) && '' != $data_user_agent ) {
											$message_text .=
											'<tr>
												<td>' .__( 'Sent from (referer)', 'contact_form' ) . ':</td><td>' . $data_user_agent . '</td>
											</tr>';
										}
										$message_text .=
													'</table>
												</body>
											</html>';
									}
									/* get headers */
									$headers = '';
									$headers .= 'MIME-Version: 1.0' . "\n";
									$headers .= 'Content-type: text/html; charset=utf-8' . "\n";
									if ( 'custom' == $cntctfrm_options_for_this_plugin['from_email'] )
										$headers .= __( 'From: ', 'contact-form-to-db' ) . stripslashes( $cntctfrm_options_for_this_plugin['from_field'] ) . ' <' . stripslashes( $cntctfrm_options_for_this_plugin['custom_from_email'] ) . '>' . "\n";
									else
										$headers .= __( 'From: ', 'contact-form-to-db' ) . stripslashes( $cntctfrm_options_for_this_plugin['from_field'] ) . ' <' . $data->user_email . '>' . "\n";
									$headers .= __( 'To: ', 'contact-form-to-db' ) . $data->email . "\n";
									$headers .= __( 'Subject: ', 'contact-form-to-db' ) . $data->subject . "\n";
									$headers .= __( 'Date/Time: ', 'contact-form-to-db' ) . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( current_time( 'mysql' ) ) ) . "\n";

									$message = $headers . $message_text;
									/* generate a file name */
									$random_prefix = $random_number + $i; /* add numeric prefix to file name */
									$i ++; /* to names have been streamlined */
									$file_name = 'message_' . 'ID_' . $id . '_' . $random_prefix . '.eml';
									if ( 'download_messages' == $action ) {
										/* add message to zip-archive if need save a several messages */
										if ( class_exists( 'ZipArchive' ) ) {
											$zip->addFromString( $file_name, $message ); /* add file content to zip - archive */
											$counter ++;
										}
									} else {
										/* save message to local computer if need save a single message */
										if ( file_exists( $save_file_path . '/' . $file_name ) )
											$file_name = time() . '_' . $file_name;
										$fp = fopen( $save_file_path . '/' . $file_name, 'w');
										fwrite( $fp, $message );
										$file_created = fclose( $fp );
										if ( '0' != $file_created ) {
											header( 'Content-Description: File Transfer' );
											header( 'Content-Type: application/force-download' );
											header( 'Content-Disposition: attachment; filename=' . $file_name );
											header( 'Content-Transfer-Encoding: binary' );
											header( 'Expires: 0' );
											header( 'Cache-Control: must-revalidate');
											header( 'Pragma: public' );
											header( 'Content-Length: ' . filesize( $save_file_path . '/' . $file_name ) );
											flush();
											$file_downloaded = readfile( $save_file_path . '/' . $file_name );
											if ( $file_downloaded )
												unlink( $save_file_path . '/' . $file_name );
										} else {
											$error_counter ++;
										}
									}
								/* forming files in to "CSV" format */
								} elseif ( 'csv' == $cntctfrmtdb_options['format_save_messages'] ) {
									$count_messages = count( $message_id ); /* number of messages which was chosen for downloading */
									/* we get enclosure anf separator from option */
									$enclosure = stripslashes( $cntctfrmtdb_options['csv_enclosure'] );
									if ( 't' == $cntctfrmtdb_options['csv_separator'] )
										$separator = "\\" . stripslashes( $cntctfrmtdb_options['csv_separator'] );
									else
										$separator = stripslashes( $cntctfrmtdb_options['csv_separator'] );
									/* forming file content */
									foreach ( $message_data as $data ) {
										$arrayRow = array();
										$data_address = $data_phone = $data_user_agent = $data_location = '';
										foreach ( $additional_fields as $field ) {
											if ( 'address' == $field->name )
												$data_address = $field->field_value;
											elseif ( 'phone' == $field->name )
												$data_phone = $field->field_value;
											elseif ( 'user_agent' == $field->name )
												$data_user_agent = $field->field_value;
										}

										if ( ! isset( $message ) )
											$message = '';
										if ( 'custom' == $cntctfrm_options_for_this_plugin['from_email'] )
											$arrayRow['from'] = $enclosure . stripslashes( $cntctfrm_options_for_this_plugin['from_field'] ) . ' <' . stripslashes( $cntctfrm_options_for_this_plugin['custom_from_email'] ) . '>' . $enclosure . $separator ;
										else
											$arrayRow['from'] = $enclosure . stripslashes( $cntctfrm_options_for_this_plugin['from_field'] ) . ' <' . $data->user_email . '>' . $enclosure . $separator ;
										$arrayRow['email_from'] = $enclosure . $data->email . $enclosure . $separator;
										if ( '' != $data->subject )
											$arrayRow['subject'] = $enclosure . $data->subject . $enclosure . $separator;
										if ( '' != $data->message_text )
											$arrayRow['message_text'] = $enclosure . $data->message_text . $enclosure . $separator;
										if ( ! empty( $data->custom_fields ) ) {
											$custom_fields = unserialize( $data->custom_fields );
											$arrayRow['custom_fields'] = $enclosure;
											foreach ( $custom_fields as $key => $custom_field ) {
												$arrayRow['custom_fields'] .= $key . ': ' . $custom_field . '; ';
											}
											$arrayRow['custom_fields'] .= $enclosure . $separator;
										}

										$arrayRow['send_date'] = $enclosure . date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $data->send_date ) ) . $enclosure . $separator;
										$arrayRow['from_user'] = $enclosure . $data->from_user . $enclosure . $separator;

										if ( isset( $data_address ) && '' != $data_address )
											$arrayRow['data_address'] = $enclosure . $data_address . $enclosure . $separator;
										if ( '' != $data->user_email )
											$arrayRow['user_email'] = $enclosure . $data->user_email . $enclosure . $separator;
										if ( isset( $data_phone ) && '' != $data_phone )
											$arrayRow['phone'] = $enclosure . $data_phone . $enclosure . $separator;
										$arrayRow['site'] = $enclosure . $data->site . $enclosure . $separator;
										if ( 1 == $cntctfrm_options_for_this_plugin['display_sent_from'] ) {
											$ip = '';
											if ( isset( $_SERVER ) ) {
												$sever_vars = array( 'HTTP_X_REAL_IP', 'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR' );
												foreach ( $sever_vars as $var ) {
													if ( isset( $_SERVER[ $var ] ) && ! empty( $_SERVER[ $var ] ) ) {
														if ( filter_var( $_SERVER[ $var ], FILTER_VALIDATE_IP ) ) {
															$ip = $_SERVER[ $var ];
															break;
														} else { /* if proxy */
															$ip_array = explode( ',', $_SERVER[ $var ] );
															if ( is_array( $ip_array ) && ! empty( $ip_array ) && filter_var( $ip_array[0], FILTER_VALIDATE_IP ) ) {
																$ip = $ip_array[0];
																break;
															}
														}
													}
												}
											}
											$arrayRow['sent_from_ip'] = $enclosure . $ip . " ( " . @gethostbyaddr( $ip ) ." )" . $enclosure . $separator;
										}

										if ( '' != $data->refer ) {
											$arrayRow['refer'] = $enclosure . $data->refer . $enclosure . $separator;
										}
										if ( isset( $data_user_agent ) && '' != $data_user_agent ) {
											$arrayRow['user_agent'] = $enclosure . $data_user_agent . $enclosure . $separator;
										}
										/* if was chosen only one message */
										if ( 1 == $count_messages ) {
											/* saving file to local computer */
											$file_name = 'message_' . 'ID_' . $id . '_' . $random_number . '.csv';
											$columns_row = array_keys( $arrayRow );
											$message = implode( ',' , $columns_row ) . "\n";
											$message .= implode( '' , $arrayRow );
											if ( file_exists( $save_file_path . '/' . $file_name ) )
												$file_name = time() . '_' . $file_name;
											$fp = fopen( $save_file_path . '/' . $file_name, 'w');
											fwrite( $fp, $message );
											$file_created = fclose( $fp );
											if ( '0' != $file_created ) {
												header( 'Content-Description: File Transfer' );
												header( 'Content-Type: application/force-download' );
												header( 'Content-Disposition: attachment; filename=' . $file_name );
												header( 'Content-Transfer-Encoding: binary' );
												header( 'Expires: 0' );
												header( 'Cache-Control: must-revalidate');
												header( 'Pragma: public' );
												header( 'Content-Length: ' . filesize( $save_file_path . '/' . $file_name ) );
												flush();
												$file_downloaded = readfile( $save_file_path . '/' . $file_name );
												if ( $file_downloaded )
													unlink( $save_file_path . '/' . $file_name );
											} else {
												$error_counter ++;
											}
										/* if was chosen more then one message */
										} elseif ( 1 < $count_messages ) {
											$messageArray[] = $arrayRow;
										}
									}
								} else {
									$error_counter ++;
									$unknown_format = 1;
								}
								if ( 0 != $can_not_create_xml ) {
									$cntctfrmtdb_error_message = __( 'Can not create XML-files.', 'contact-form-to-db' );
								}
								if ( 0 != $can_not_create_zip ) {
									if ( '' == $cntctfrmtdb_error_message ) {
										$cntctfrmtdb_error_message = __( 'Can not create ZIP-archive.', 'contact-form-to-db' );
									}
								}
								if ( isset( $unknown_format ) )
									$cntctfrmtdb_error_message = __( 'Unknown format.', 'contact-form-to-db' );

								break;
							case 'download_attachment':
							case 'download_attachments':
								break;
							case 'delete_message':
							case 'delete_messages':
								/* delete all records about choosen message from database */
								$error = 0;
								$wpdb->query( "DELETE FROM `" . $prefix . "message` WHERE " . $prefix . "message.id=" . $id );
								$error += $wpdb->last_error ? 1 : 0;
								$wpdb->query( "DELETE FROM `" . $prefix . "field_selection` WHERE `message_id`=" . $id );
								$error += $wpdb->last_error ? 1 : 0;
								if ( 0 == $error ) {
									$counter++;
								} else {
									$error_counter++;
								}
								if ( 0 == $error_counter ) {
									$cntctfrmtdb_done_message = sprintf( _nx( __( 'One message was successfully deleted.', 'contact-form-to-db' ), '%s&nbsp;' . __( 'messages were successfully deleted.', 'contact-form-to-db' ), $counter, 'contact-form-to-db' ), number_format_i18n( $counter ) );
								} else {
									$cntctfrmtdb_error_message = __( 'There are some problems while deleting message.', 'contact-form-to-db' );
								}
								break;
							/* marking messages as Spam */
							case 'spam':
								$wpdb->update( $prefix . 'message', array( 'status_id' => 2 ), array( 'id' => $id ) );
								if ( ! 0 == $wpdb->last_error )
									$error_counter ++;
								else
									$counter ++;
								$ids = '';
								if ( 0 == $error_counter ) {
									if ( 1 < count( $message_id ) ) {
										/* get ID`s of message to string in format "1,2,3,4,5" to add in action link */
										foreach( $message_id as $value )
											$ids .= $value . ',';
									} else {
										$ids = $message_id['0'];
									}
									$cntctfrmtdb_done_message = sprintf( _nx( __( 'One message was marked as Spam.', 'contact-form-to-db' ), '%s&nbsp;' . __( 'messages were marked as Spam.', 'contact-form-to-db' ), $counter, 'contact-form-to-db' ), number_format_i18n( $counter ) );
									$cntctfrmtdb_done_message .= ' <a href="' . wp_nonce_url( '?page=cntctfrmtdb_manager&action=undo&message_id[]=' . $ids, plugin_basename( __FILE__ ), 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Undo', 'contact-form-to-db' ) . '</a>';
								} else {
									$cntctfrmtdb_error_message = __( 'Problems while marking messages as Spam.', 'contact-form-to-db' );
								}
								break;
							/* marking messages as Trash */
							case 'trash':
								$wpdb->update( $prefix . 'message', array( 'status_id' => 3 ), array( 'id' => $id ) );
								if ( ! 0 == $wpdb->last_error )
									$error_counter ++;
								else
									$counter ++;
								$ids = '';
								if ( 0 == $error_counter ) {
									if ( 1 < count( $message_id ) ) {
										/* get ID`s of message to string in format "1,2,3,4,5" to add in action link */
										foreach( $message_id as $value )
											$ids .= $value . ',';
									} else {
										$ids = $message_id['0'];
									}
									$cntctfrmtdb_done_message = sprintf( _nx( __( 'One message was moved to Trash.', 'contact-form-to-db' ), '%s&nbsp;' . __( 'messages were moved to Trash.', 'contact-form-to-db' ), $counter, 'contact-form-to-db' ), number_format_i18n( $counter ) );
									$cntctfrmtdb_done_message .= ' <a href="' . wp_nonce_url( '?page=cntctfrmtdb_manager&action=undo&message_id[]=' . $ids, plugin_basename( __FILE__ ), 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Undo', 'contact-form-to-db' ) . '</a>';
								} else {
									$cntctfrmtdb_error_message .= __( "Problems while moving messages to Trash.", "contact-form-to-db" ) . ' ' . __( "Please, try it later.", "contact-form-to-db" );
								}
								break;
							case 'unspam':
							case 'restore':
								if ( isset( $_REQUEST['old_status'] ) && '' != $_REQUEST['old_status'] ) {
									$wpdb->update( $prefix . 'message', array( 'status_id' => $_REQUEST['old_status'] ), array( 'id' => $id ) );
								} else {
									$wpdb->update( $prefix . 'message', array( 'status_id' => 1 ), array( 'id' => $id ) );
								}
								if ( ! 0 == $wpdb->last_error )
									$error_counter ++;
								else
									$counter ++;

								if ( 0 == $error_counter ) {
									$cntctfrmtdb_done_message = sprintf( _nx( __( 'One message was restored.', 'contact-form-to-db' ), '%s&nbsp;' . __( 'messages were restored.', 'contact-form-to-db' ), $counter, 'contact-form-to-db' ), number_format_i18n( $counter ) );
								} else {
									$cntctfrmtdb_error_message = __( 'Problems during the messages restoration.', 'contact-form-to-db' );
								}
								break;
							case 'undo':
								if ( isset( $_REQUEST['old_status'] ) && '' != $_REQUEST['old_status'] ) {
									$wpdb->update( $prefix . 'message', array( 'status_id' => $_REQUEST['old_status'] ), array( 'id' => $id ) );
								} else {
									$wpdb->update( $prefix . 'message', array( 'status_id' => 1 ), array( 'id' => $id ) );
								}
								if ( ! 0 == $wpdb->last_error )
									$error_counter ++;
								else
									$counter ++;
								if ( 0 == $error_counter ) {
									$cntctfrmtdb_done_message = sprintf( _nx( __( 'One message was restored.', 'contact-form-to-db' ), '%s&nbsp;' . __( 'messages were restored.', 'contact-form-to-db' ), $counter, 'contact-form-to-db' ), number_format_i18n( $counter ) );
								} else {
									$cntctfrmtdb_error_message = __( 'Problems during the messages restoration.', 'contact-form-to-db' );
								}
								break;
							case 'change_status':
								$new_status = $_REQUEST['status'] + 1;
								if ( 3 < $new_status || 1 > $new_status )
									$new_status = 1;
								$wpdb->update( $prefix . 'message', array( 'status_id' => $new_status ), array( 'id' => $id ) );
								break;
								if ( ! 0 == $wpdb->last_error )
									$error_counter ++;
								if ( 0 == $error_counter ) {
									switch ( $new_status ) {
										case 1:
											$cntctfrmtdb_done_message = __( 'One message was marked as Normal.', 'contact-form-to-db' );
											 break;
										case 2:
											$cntctfrmtdb_done_message = __( 'One message was marked as Spam.', 'contact-form-to-db' ) . ' <a href="' . wp_nonce_url( '?page=cntctfrmtdb_manager&action=undo&message_id[]=' .  $id . '&old_status=' . $_REQUEST['status'], plugin_basename( __FILE__ ), 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Undo', 'contact-form-to-db' ) . '</a>';
											break;
										case 3:
											$cntctfrmtdb_done_message = __( 'One message was marked as Trash.', 'contact-form-to-db' ) . ' <a href="' . wp_nonce_url( '?page=cntctfrmtdb_manager&action=undo&message_id[]=' .  $id . '&old_status=' . $_REQUEST['status'], plugin_basename( __FILE__ ), 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Undo', 'contact-form-to-db' ) . '</a>';
											break;
										default:
											$cntctfrmtdb_error_message = __( 'Unknown result.', 'contact-form-to-db' );
											break;
									}
								} else {
									$cntctfrmtdb_error_message = __( 'Problems while changing the message status.', 'contact-form-to-db' );
								}
								break;
							case 'change_read_status':
								$wpdb->update( $prefix . 'message', array( 'was_read' => 1 ), array( 'id' => $id ) );
								if ( ! 0 == $wpdb->last_error )
									$error_counter ++;
								break;
							default:
								$cntctfrmtdb_error_message = __( 'Unknown action.', 'contact-form-to-db' );
								break;
						}
					}
				}
				/* Create columns in csv document*/
				if ( 'csv' == $cntctfrmtdb_options['format_save_messages'] ) {
					$all_columns  = array();
					if ( 't' == $cntctfrmtdb_options['csv_separator'] ){
						$separator = "\\" . stripslashes( $cntctfrmtdb_options['csv_separator'] );
                    } else {
						$separator = stripslashes( $cntctfrmtdb_options['csv_separator'] );
					}
					foreach ( $messageArray as $single_message ) {
						$all_columns  = array_merge( $all_columns , $single_message );
					}
					$all_columns = array_keys( $all_columns );
					$result_string = implode( $separator , $all_columns ) . "\n";

					/* Create row with data */
					foreach ( $messageArray as $single_message ) {
						foreach ( $all_columns as $col_key ) {
							if ( isset( $single_message[$col_key] ) ) {
								$result_string .= $single_message[$col_key];
							} else {
								$result_string .= $separator;
							}
						}
						$result_string .= "\n";
					}
					$message = $result_string;
				}
				/* create zip-archives is possible and one embodiment of the:
				1) need to save several messages in "csv"-format
				2) need to save several messages in "eml"-format */
				if ( 'download_messages' == $action && ( 'csv' == $cntctfrmtdb_options['format_save_messages'] || 'eml' == $cntctfrmtdb_options['format_save_messages'] ) ) {
					if ( class_exists( 'ZipArchive' ) ) {
						if ( 1 < count( $message_id ) && 'csv' == $cntctfrmtdb_options['format_save_messages'] ) {
							$file_name = 'messages.csv';
							$zip->addFromString( $file_name, $message ); /* add file content to zip - archive */
						}
						$zip->close();
						if ( file_exists( $zip_name ) ) {
							/* saving file to local computer */
							header( 'Content-Description: File Transfer' );
							header( 'Content-Type: application/x-zip-compressed' );
							header( 'Content-Disposition: attachment; filename=' . time() . '.zip' );
							header( 'Content-Transfer-Encoding: binary' );
							header( 'Expires: 0' );
							header( 'Cache-Control: must-revalidate' );
							header( 'Pragma: public' );
							header( 'Content-Length: ' . filesize( $zip_name ) );
							flush();
							$file_downloaded = readfile( $zip_name );
							if ( $file_downloaded )
								unlink( $zip_name );
						}
					} else {
						$can_not_create_zip = 1;
					}
				}
				if ( 'download_messages' == $action && 1 < count( $message_id ) && 'csv' == $cntctfrmtdb_options['format_save_messages'] ) {
					/* saving single chosen "csv"-file to local computer if content of attachment was include in csv */
					$file_name = 'messages.csv';
					if ( file_exists( $save_file_path . '/' . $file_name ) )
						$file_name = time() . '_' . $file_name;
					$fp = fopen( $save_file_path . '/' . $file_name, 'w');
					fwrite( $fp, $message );
					$file_created = fclose( $fp );
					if ( '0' != $file_created ) {
						header( 'Content-Description: File Transfer' );
						header( 'Content-Type: application/force-download' );
						header( 'Content-Disposition: attachment; filename=' . $file_name );
						header( 'Content-Transfer-Encoding: binary' );
						header( 'Expires: 0' );
						header( 'Cache-Control: must-revalidate');
						header( 'Pragma: public' );
						header( 'Content-Length: ' . filesize( $save_file_path . '/' . $file_name ) );
						flush();
						$file_downloaded = readfile( $save_file_path . '/' . $file_name );
						if ( $file_downloaded )
							unlink( $save_file_path . '/' . $file_name );
					} else {
						$error_counter ++;
					}
				}
				/* saving "xml"-file to local computer */
				if ( in_array( $action, array( 'download_message', 'download_messages' ) ) && 'xml' == $cntctfrmtdb_options['format_save_messages'] ) {
					if ( 'download_message' == $action ) {
						$random_prefix = $random_number; /* name prefix */
						$file_name = 'message_' . 'ID_' . $id . '_' . $random_prefix . '.xml';
					} else {
						$file_name = 'messages_' . time() . '.xml';
					}
					$file_xml = $xml->saveXML(); /* create string with file content */
					if ( '' != $file_xml ) {
						if ( file_exists( $save_file_path . '/' . $file_name ) )
							$file_name = time() . '_' . $file_name;
						$fp = fopen( $save_file_path . '/' . $file_name, 'w');
						fwrite( $fp, $file_xml );
						$file_created = fclose( $fp );
						if ( '0' != $file_created ) {
							header( 'Content-Description: File Transfer' );
							header( 'Content-Type: application/force-download' );
							header( 'Content-Disposition: attachment; filename=' . $file_name );
							header( 'Content-Transfer-Encoding: binary' );
							header( 'Expires: 0' );
							header( 'Cache-Control: must-revalidate');
							header( 'Pragma: public' );
							header( 'Content-Length: ' . filesize( $save_file_path . '/' . $file_name ) );
							flush();
							$file_downloaded = readfile( $save_file_path . '/' . $file_name );
							if ( $file_downloaded )
								unlink( $save_file_path . '/' . $file_name );
						} else {
							$error_counter ++;
						}
					} else {
						$can_not_create_xml = 1;
					}
				}
			} else {
				if ( ! ( in_array( $_REQUEST['action'], array( 'cntctfrmtdb_show_attachment', 'cntctfrmtdb_read_message', 'cntctfrmtdb_change_staus' ) ) || isset( $_REQUEST['s'] ) ) ) {
					$cntctfrmtdb_error_message = __( 'Can not handle request. May be you need choose some messages to handle them.', 'contact-form-to-db' );
				}
			}
		}
	}
}


/*
 * Function to get number of messages
 */
if ( ! function_exists( 'cntctfrmtdb_number_of_messages' ) ) {
	function cntctfrmtdb_number_of_messages() {
		global $wpdb;
		$prefix = $wpdb->prefix . 'cntctfrmtdb_';
		$sql_query = "SELECT COUNT(`id`) FROM " . $prefix . "message ";
		if ( isset( $_REQUEST['s'] ) && $_REQUEST['s'] ) {
			$search = stripslashes( esc_html( trim( $_REQUEST['s'] ) ) );
			$sql_query .= "WHERE `from_user` LIKE '%" . $search . "%' OR `user_email` LIKE '%" . $search . "%' OR `subject` LIKE '%" . $search . "%' OR `message_text` LIKE '%" . $search . "%'";
		} elseif ( isset( $_REQUEST['message_status'] ) ) { /* depending on request display different list of messages */
			if ( 'sent' == $_REQUEST['message_status'] ) {
				$sql_query .= "WHERE " . $prefix . "message.sent='1' AND " . $prefix . "message.status_id NOT IN (2,3)";
			} elseif ( 'not_sent' == $_REQUEST['message_status'] ) {
				$sql_query .= "WHERE " . $prefix . "message.sent='0' AND " . $prefix . "message.status_id NOT IN (2,3)";
			} elseif ( 'read_messages' == $_REQUEST['message_status'] ) {
				$sql_query .= "WHERE " . $prefix . "message.was_read='1' AND " . $prefix . "message.status_id NOT IN (2,3)";
			} elseif ( 'not_read_messages' == $_REQUEST['message_status'] ) {
				$sql_query .= "WHERE " . $prefix . "message.was_read='0' AND " . $prefix . "message.status_id NOT IN (2,3)";
			} elseif ( 'has_attachment' == $_REQUEST['message_status'] ) {
				$sql_query .= "WHERE " . $prefix . "message.attachment_status<>'0' AND " . $prefix . "message.status_id NOT IN (2,3)";
			} elseif ( 'all' == $_REQUEST['message_status'] ) {
				$sql_query .= "WHERE " . $prefix . "message.status_id='1'";
			} elseif ( 'spam' == $_REQUEST['message_status'] ) {
				$sql_query .= "WHERE " . $prefix . "message.status_id='2'";
			} elseif ( 'trash' == $_REQUEST['message_status'] ) {
				$sql_query .= "WHERE " . $prefix . "message.status_id='3'";
			}
		} else {
			$sql_query .= "WHERE " . $prefix . "message.status_id='1'";
		}
		$number_of_messages = $wpdb->get_var( $sql_query );
		return $number_of_messages;
	}
}

/*
* create class Cntctfrmtdb_Manager to display list of messages
*/
if ( ! class_exists( 'Cntctfrmtdb_Manager' ) ) {
	if ( ! class_exists( 'WP_List_Table' ) )
		require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

	class Cntctfrmtdb_Manager extends WP_List_Table {
		var $message_status;
		var $is_cf_pro_activated;
		/*
		* Constructor of class
		*/
		function __construct() {
			global $status, $page;
			parent::__construct( array(
				'singular'  => __( 'message', 'contact-form-to-db' ),
				'plural'    => __( 'messages', 'contact-form-to-db' ),
				'ajax'      => true
				)
			);
			if ( ! function_exists( 'is_plugin_active' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
			$this->is_cf_pro_activated = is_plugin_active( 'contact-form-pro/contact_form_pro.php' );
			$this->message_status = isset( $_REQUEST['message_status'] ) ? $_REQUEST['message_status'] : 'all';
		}

		/*
		* Function to prepare data before display
		*/
		function prepare_items() {
			global $cntctfrmtdb_options;

			$columns               = $this->get_columns();
			$hidden                = get_hidden_columns( $this->screen );
			$sortable              = $this->get_sortable_columns();
			$primary               = 'message';
			$this->_column_headers = array( $columns, $hidden, $sortable, $primary );

			if ( ! in_array( $this->message_status, array( 'all', 'sent', 'not_sent', 'read_messages', 'not_read_messages', 'has_attachment', 'spam', 'trash' ) ) )
				$this->message_status = 'all';
			$this->items = $this->get_message_list();
			$this->set_pagination_args( array(
				'total_items' => intval( cntctfrmtdb_number_of_messages() ),
				'per_page'    => $this->get_items_per_page( 'cntctfrmtdb_letters_per_page', 30 ),
				)
			);
		}

		/*
		* Function to show message if no data found
		*/
		function no_items() {
			if ( 'sent' == $this->message_status ) {
				echo '<i>- ' . __( 'No sent messages found.', 'contact-form-to-db' ) . ' -<i>';
			} elseif ( 'not_sent' == $this->message_status ) {
				echo '<i>- ' . __( 'There are no unsent messages.', 'contact-form-to-db' ) . '-<i>';
			} elseif ( 'read_messages' == $this->message_status ) {
				echo '<i>- ' . __( 'There are no read messages.', 'contact-form-to-db' ) . ' -<i>';
			} elseif ( 'not_read_messages' == $this->message_status ) {
				echo '<i>- ' . __( 'There are no unread messages.', 'contact-form-to-db' ) . ' -<i>';
			} elseif ( 'has_attachment' == $this->message_status ) {
				echo '<i>- ' . __( 'There are no messages with attachments.', 'contact-form-to-db' ) . ' -<i>';
			} elseif ( 'spam' == $this->message_status ) {
				echo '<i>- ' . __( 'No messages that was marked as Spam.', 'contact-form-to-db' ) . ' -<i>';
			} elseif ( 'trash' == $this->message_status ) {
				echo '<i>- ' . __( 'No messages that was marked as Trash.', 'contact-form-to-db' ) . ' -<i>';
			} else {
				echo '<i>- ' . __( 'No messages found.', 'contact-form-to-db' ) . ' -<i>';
			}
		}

		/*
		* Function to add column names
		*/
		function column_default( $item, $column_name ) {
			switch( $column_name ) {
				case 'from':
				case 'message':
				case 'attachment':
				case 'custom_fields':
				case 'department':
				case 'sent':
				case 'date':
					return $item[ $column_name ];
				default:
					return print_r( $item, true ) ;
			}
		}

		/*
		* Function to add column titles
		*/
		function get_columns() {
			$columns = array(
				'cb'            => '<input type="checkbox" />',
				'from'          => __( 'From', 'contact-form-to-db' ),
				'message'       => __( 'Message', 'contact-form-to-db' ),
				'attachment'    => '<span class="hidden">' . __( 'Attachment', 'contact-form-to-db' ) . '</span><div class="cntctfrmtdb-attachment-column-title"></div>',
				'custom_fields' => __( 'Custom Fields', 'contact-form-to-db-pro' ),
				'sent'          => __( 'Send Counter', 'contact-form-to-db' ),
				'date'          => __( 'Date', 'contact-form-to-db' )
			);
			/* insert column 'department' after column 'message' */
			if ( $this->is_cf_pro_activated )
				$columns = array_slice( $columns, 0, 5, true ) + array( 'department' => __( 'Department', 'contact-form-to-db' ) ) + array_slice( $columns, 4, count( $columns ), true );
			return $columns;
		}

		/**
		 * Get a list of sortable columns.
		 * @return array list of sortable columns
		 */
		function get_sortable_columns() {
			$sortable_columns = array(
				'from'       => array( 'from', false ),
				'date'       => array( 'date', false )
			);
			return $sortable_columns;
		}

		/**
		 * Add necessary classes for tag <table>
		 */
		function get_table_classes() {
			return array( 'widefat' );
		}

		/**
		* Function to add action links before and after list of messages
		*/
		function get_views() {
			global $wpdb;
			$status_links = array();
			$prefix = $wpdb->prefix . 'cntctfrmtdb_';

			$status = array(
				'all'               => __( 'All', 'contact-form-to-db' ),
				'sent'              => __( 'Sent', 'contact-form-to-db' ),
				'not_sent'          => __( 'Not sent', 'contact-form-to-db' ),
				'read_messages'     => __( 'Read', 'contact-form-to-db' ),
				'not_read_messages' => __( 'Unread', 'contact-form-to-db' ),
				'has_attachment'    => __( 'Has attachments', 'contact-form-to-db' ),
				'spam'              => __( 'Spam', 'contact-form-to-db' ),
				'trash'             => __( 'Trash', 'contact-form-to-db' )
			);

			$filters_count = $wpdb->get_results(
				"SELECT COUNT(`id`) AS `all`,
					( SELECT COUNT(`id`) FROM " . $prefix . "message WHERE " . $prefix . "message.sent=1 AND " . $prefix . "message.status_id NOT IN (2,3) ) AS `sent`,
					( SELECT COUNT(`id`) FROM " . $prefix . "message WHERE " . $prefix . "message.sent=0 AND " . $prefix . "message.status_id NOT IN (2,3) ) AS `not_sent`,
					( SELECT COUNT(`id`) FROM " . $prefix . "message WHERE " . $prefix . "message.was_read=1 AND " . $prefix . "message.status_id NOT IN (2,3) ) AS `was_read`,
					( SELECT COUNT(`id`) FROM " . $prefix . "message WHERE " . $prefix . "message.was_read=0 AND " . $prefix . "message.status_id NOT IN (2,3) ) AS `was_not_read`,
					( SELECT COUNT(`id`) FROM " . $prefix . "message WHERE " . $prefix . "message.attachment_status<>0 AND " . $prefix . "message.status_id NOT IN (2,3) ) AS `has_attachment`,
					( SELECT COUNT(`id`) FROM " . $prefix . "message WHERE " . $prefix . "message.status_id=2 ) AS `spam`,
					( SELECT COUNT(`id`) FROM " . $prefix . "message WHERE " . $prefix . "message.status_id=3 ) AS `trash`
				FROM " . $prefix . "message WHERE " . $prefix . "message.status_id NOT IN (2,3)"
			);
			foreach ( $filters_count as $value ) {
				$all_count					= $value->all;
				$sent_count					= $value->sent;
				$not_sent_count				= $value->not_sent;
				$read_messages_count		= $value->was_read;
				$not_read_messages_count	= $value->was_not_read;
				$has_attachment_count		= $value->has_attachment;
				$spam_count					= $value->spam;
				$trash_count				= $value->trash;
			}
			foreach ( $status as $key => $value ) {
				$class = ( $key == $this->message_status ) ? ' class="current"' : '';
				$status_links[ $key ] = '<a href="?page=cntctfrmtdb_manager&message_status=' . $key . '" ' . $class . '">' . $value . ' <span class="count">(<span class="' . str_replace( '_', '-', $key ) . '-count">' . ${ $key . '_count'} . '</span>)</span></a>';
			}
			return $status_links;
		}

		/*
		* Function to add filters before and after list of messages
		*/
		function extra_tablenav( $which ) {
			if ( 'top' !== $which )
				return;

			global $wpdb, $cntctfrmtdb_department;
			if ( $this->is_cf_pro_activated ) {
				$departments = $wpdb->get_results( "SELECT DISTINCT `field_value` FROM `" . $wpdb->prefix . "cntctfrmtdb_field_selection`, `" . $wpdb->prefix . "cntctfrm_field` WHERE `cntctfrm_field_id`=`id` AND `name`='department_selectbox'", ARRAY_A );
				if ( ! empty( $departments ) ) { ?>
					<div class="alignleft actions">
						<label class="screen-reader-text" for="filter-by-department"><?php _e( 'Filter by department', 'contact-form-to-db' ); ?></label>
						<select id="filter-by-department" name="cntctfrmtdb_department">
							<option value=""><?php _e( 'All departments', 'contact-form-to-db' ); ?></option>
							<?php foreach ( $departments as $department ) { ?>
								<option value="<?php echo $department['field_value']; ?>"<?php selected( $cntctfrmtdb_department, $department['field_value'], true ); ?>><?php echo $department['field_value']; ?></option>
							<?php } ?>
						</select>
						<?php submit_button( __( 'Filter', 'contact-form-to-db' ), 'button', 'filter_action', false, array( 'id' => 'post-query-submit' ) ); ?>
					</div>
				<?php }
			}
		}

		/*
		* Function to add action links to drop down menu before and after table depending on status page
		*/
		function get_bulk_actions() {
			$actions = array();
			if ( in_array( $this->message_status, array( 'all', 'sent', 'not_sent', 'read_messages', 'not_read_messages', 'has_attachment' ) ) ) {
				$actions['download_messages']		= __( 'Download messages', 'contact-form-to-db' );
				$actions['spam']					= __( 'Mark as Spam', 'contact-form-to-db' );
			}
			if ( 'spam' == $this->message_status )
				$actions['unspam'] = __( 'Not Spam', 'contact-form-to-db' );
			if ( 'trash' == $this->message_status )
				$actions['restore'] = __( 'Restore', 'contact-form-to-db' );
			if ( in_array( $this->message_status, array( 'spam', 'trash' ) ) )
				$actions['delete_messages'] = __( 'Delete Permanently', 'contact-form-to-db' );
			else
				$actions['trash'] = __( 'Mark as Trash', 'contact-form-to-db' );
			if ( in_array( $this->message_status, array( 'all', 'sent', 'not_sent', 'read_messages', 'not_read_messages', 'has_attachment' ) ) ) {
				$actions['re_send_messages']		= __( 'Re-send messages', 'contact-form-to-db' );
				$actions['download_attachments']	= __( 'Download attachments', 'contact-form-to-db' );
			}
			return $actions;
		}

		/*
		* Function to add action links to message column depenting on status page
		*/
		function column_message( $item ) {
			global $cntctfrmtdb_options;
			$actions = array();
			$plugin_basename = plugin_basename( __FILE__ );

			if ( in_array( $this->message_status, array( 'all', 'sent', 'not_sent', 'read_messages', 'not_read_messages', 'has_attachment' ) ) ) {
				$bws_hide_premium_options_check = bws_hide_premium_options_check( $cntctfrmtdb_options );
				if ( ! $bws_hide_premium_options_check )
					$actions['re_send_message'] = sprintf( '<a style="cursor: default;" class="bws_plugin_menu_pro_version" title="' . __( "This option is available in Pro version", "contact-form-to-db" ) . '" >' . __( 'Re-send', 'contact-form-to-db' ) . '</a>', $item['id'] );

				$actions['download_message'] = '<a href="' . wp_nonce_url( sprintf( '?page=cntctfrmtdb_manager&action=download_message&message_id[]=%s', $item['id'] ), $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Download', 'contact-form-to-db' ) . '</a>';
				$actions['spam'] = '<a href="' . wp_nonce_url( sprintf( '?page=cntctfrmtdb_manager&action=spam&message_id[]=%s', $item['id'] ), $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Spam', 'contact-form-to-db' ) . '</a>';
				$actions['trash'] = '<a href="' . wp_nonce_url( sprintf( '?page=cntctfrmtdb_manager&action=trash&message_id[]=%s', $item['id'] ), $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Trash', 'contact-form-to-db' ) . '</a>';
			}
			if ( 'spam' == $this->message_status )
				$actions['unspam'] = '<a style="color:#006505" href="' . wp_nonce_url( sprintf( '?page=cntctfrmtdb_manager&action=unspam&message_id[]=%s', $item['id'] ), $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Not spam', 'contact-form-to-db' ) . '</a>';
			if ( 'trash' == $this->message_status )
				$actions['untrash'] = '<a style="color:#006505" href="' . wp_nonce_url( sprintf( '?page=cntctfrmtdb_manager&action=restore&message_id[]=%s', $item['id'] ), $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Restore', 'contact-form-to-db' ) . '</a>';
			if ( in_array( $this->message_status, array( 'spam', 'trash' ) ) )
				$actions['delete_message'] = '<a style="color:#bc0b0b" href="' . wp_nonce_url( sprintf( '?page=cntctfrmtdb_manager&action=delete_message&message_id[]=%s', $item['id'] ), $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Delete Permanently', 'contact-form-to-db' ) . '</a>';
			else
				$actions['trash'] = '<a href="' . wp_nonce_url( sprintf( '?page=cntctfrmtdb_manager&action=trash&message_id[]=%s', $item['id'] ), $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Trash', 'contact-form-to-db' ) . '</a>';
			return sprintf( '%1$s %2$s', $item['message'], $this->row_actions( $actions ) );
		}
		/*
		* Function to add column of checboxes
		*/
		function column_cb( $item ) {
			return sprintf( '<input id="cb_%1s" type="checkbox" name="message_id[]" value="%2s" />', $item['id'], $item['id'] );
		}

		/*
		* Function to get data in message list
		*/
		function get_message_list() {
			global $wpdb, $cntctfrmtdb_options, $cntctfrmtdb_department;
			$prefix   = $wpdb->prefix . 'cntctfrmtdb_';
			$per_page = $this->get_items_per_page( 'cntctfrmtdb_letters_per_page', 30 );
			$start_row = ( isset( $_REQUEST['paged'] ) && 1 < intval( $_REQUEST['paged'] ) ) ? $per_page * ( absint( intval( $_REQUEST['paged'] ) - 1 ) ) : 0;

			$sql_query = $this->is_cf_pro_activated
				?
					"SELECT *, `field_value` AS `department` FROM `" . $prefix . "message` LEFT JOIN `" . $prefix . "field_selection` ON `" . $prefix . "message`.id=`" . $prefix . "field_selection`.message_id AND `" . $prefix . "field_selection`.cntctfrm_field_id=( SELECT `id` FROM `" . $wpdb->prefix . "cntctfrm_field` WHERE `name`='department_selectbox' ) "
				:
					"SELECT * FROM " . $prefix . "message ";

			$bws_hide_premium_options_check = bws_hide_premium_options_check( $cntctfrmtdb_options );

			if ( isset( $_REQUEST['s'] ) && $_REQUEST['s'] ) {
				$search = stripslashes( esc_html( trim( $_REQUEST['s'] ) ) );
				$sql_query .= "WHERE `from_user` LIKE '%" . $search . "%' OR `user_email` LIKE '%" . $search . "%' OR `subject` LIKE '%" . $search . "%' OR  `message_text` LIKE '%" . $search . "%'";
			} elseif ( isset( $_REQUEST['message_status'] ) ) {
				/* depending on request display different list of messages */
				if ( 'sent' == $_REQUEST['message_status'] ) {
					$sql_query .= "WHERE " . $prefix . "message.sent=1 AND " . $prefix . "message.status_id NOT IN (2,3)";
				} elseif ( 'not_sent' == $_REQUEST['message_status'] ) {
					$sql_query .= "WHERE " . $prefix . "message.sent=0 AND " . $prefix . "message.status_id NOT IN (2,3)";
				} elseif ( 'read_messages' == $_REQUEST['message_status'] ) {
					$sql_query .= "WHERE " . $prefix . "message.was_read=1 AND " . $prefix . "message.status_id NOT IN (2,3)";
				} elseif ( 'not_read_messages' == $_REQUEST['message_status'] ) {
					$sql_query .= "WHERE " . $prefix . "message.was_read=0 AND " . $prefix . "message.status_id NOT IN (2,3)";
				} elseif ( 'has_attachment' == $_REQUEST['message_status'] ) {
					$sql_query .= "WHERE " . $prefix . "message.attachment_status<>0 AND " . $prefix . "message.status_id NOT IN (2,3)";
				} elseif ( 'all' == $_REQUEST['message_status'] ) {
					$sql_query .= "WHERE " . $prefix . "message.status_id=1";
				} elseif ( 'spam' == $_REQUEST['message_status'] ) {
					$sql_query .= "WHERE " . $prefix . "message.status_id=2";
				} elseif ( 'trash' == $_REQUEST['message_status'] ) {
					$sql_query .= "WHERE " . $prefix . "message.status_id=3";
				}
			} else {
				$sql_query .= "WHERE " . $prefix . "message.status_id=1";
			}

			$cntctfrmtdb_department = !empty( $_REQUEST['cntctfrmtdb_department'] ) ? $_REQUEST['cntctfrmtdb_department'] : '';
			if ( ! empty( $cntctfrmtdb_department ) )
				$sql_query .= " AND `field_value`='" . $cntctfrmtdb_department . "'";

			if ( isset( $_REQUEST['orderby'] ) ) {
				switch ( $_REQUEST['orderby'] ) {
					case 'from':
						$order_by = 'from_user';
						break;
					case 'date':
					default:
						$order_by = 'send_date';
						break;
				}
			} else {
				$order_by = 'send_date';
			}
			$order = isset( $_REQUEST['order'] ) ? $_REQUEST['order'] : 'DESC';
			$sql_query .= " ORDER BY " . $order_by . " " . $order . " LIMIT " . $per_page . " OFFSET " . $start_row;
			$messages = $wpdb->get_results( $sql_query );
			$i = 0;
			$attachments_icon = '';
			$list_of_messages = array();
			$plugin_basename = plugin_basename( __FILE__ );

			foreach ( $messages as $value ) {
				$from_data = '<a class="from-name';

				if ( '1' != $value->was_read )
					$from_data .= ' not-read-message" href="' . wp_nonce_url( '?page=cntctfrmtdb_manager&action=change_read_status&message_id[]=' . $value->id, $plugin_basename, 'cntctfrmtdb_manager_nonce_name' ) . '">';
				else
					$from_data .= '" href="javascript:void(0);">';

				$from_data .= ( '' != $value->from_user ) ? $value->from_user : '<i>- ' . __( 'Unknown name', 'contact-form-to-db' ) . ' -</i>';
				$from_data .= '</a>';
				/* fill "from" column */
				$add_from_data = '';
				if ( '' != $value->user_email )
					$add_from_data .= '<strong>email: </strong>' . $value->user_email . '</br>';
				$additional_filelds = $wpdb->get_results( "SELECT `cntctfrm_field_id`, `field_value`, `name` FROM `" . $prefix . "field_selection` INNER JOIN `" . $wpdb->prefix . "cntctfrm_field` ON `cntctfrm_field_id`=`id` WHERE `message_id`='" . $value->id . "' AND `cntctfrm_field_id` <> ( SELECT `id` FROM `" . $wpdb->prefix . "cntctfrm_field` WHERE `name`='department_selectbox' )" );
				if ( '' != $additional_filelds ) {
					foreach ( $additional_filelds as $field ) {
						$field_name = $wpdb->get_var( "SELECT `name` FROM `" . $wpdb->prefix . "cntctfrm_field` WHERE `id`='" . $field->cntctfrm_field_id . "'");
						if ( 'user_agent' != $field->name )
							$add_from_data .= '<strong>' . $field->name . ':</strong> ' . $field->field_value . '</br>';
					}
				}
				$to_email = $wpdb->get_var( "SELECT `email` FROM `" . $prefix . "to_email` WHERE `id`='" . $value->to_id . "'" );
				$add_from_data .= '<strong>' . __( 'to', 'contact-form-to-db' ) . ': </strong>' . $to_email;
				if ( '' !=  $add_from_data ) {
					$from_data .= '<div class="from-info">' . $add_from_data . '</div>';
				}
				/* fill "message" column and "attachment" column */
				$message_content = '<div class="message-container">
					<div class="cntctfrmtdb-message-text"><strong>' . $value->subject . '</strong> - ';
					if ( '' != $value->message_text )
						$message_content .= $value->message_text . '</div>';
					else
						$message_content .= '<i> - ' . __( 'No text in this message', 'contact-form-to-db' ) . ' - </i></div>';

				if ( $value->attachment_status != 0 && ! $bws_hide_premium_options_check ) {
					/* display thumbnail */
					$message_content .= '<table class="cntctfrmtdb-attachments-preview">
							<tbody>
								<tr class="cntctfrmtdb-attachment-img bws_pro_version" align="center">
									<td class="cntctfrmtdb-attachment-info" valign="middle">
										<span>' . __( 'Attachment name', 'contact-form-to-db' ) . '</span></br>
										<span>' . __( 'Attachment size', 'contact-form-to-db' ) . '</span></br>
										<span><a class="cntctfrmtdb-download-attachment bws_plugin_menu_pro_version" title="' . __( "This option is available in Pro version", "contact-form-to-db" ) . '" href="#">' . __( 'Download', 'contact-form-to-db' ) . '</a></span></br>
										<span><a class="bws_plugin_menu_pro_version" title="' . __( "This option is available in Pro version", "contact-form-to-db" ) . '" href="#">' . __( 'View', 'contact-form-to-db' ) . '</a></span>
									</td>
								</tr>
							</tbody>
						</table>';

					$attachments_icon = '<div class="cntctfrmtdb-has-attachment" title="' . __( "This option is available in Pro version", "contact-form-to-db" ) . '"></div>';
				} else {
					$attachments_icon = '';
				}

				$message_content .= '</div>';
				/* display counter */
				$counter_sent_status = '<span class="counter" title="' . __( 'The number of dispatches', 'contact-form-to-db' ) . '">' . $value->dispatch_counter . '</span>';
				if ( '0' == $value->sent )
					$counter_sent_status .= '<span class="warning" title="' . __( 'This message was not sent', 'contact-form-to-db' ) . '"></span>';
				/* display date */
				$send_date = strtotime( $value->send_date );
				$send_date = date( 'd.m.Y H:i', $send_date );

				/* display custom fields */
				$custom_fields_content = '';
				if ( ! empty( $value->custom_fields ) ) {
					$custom_fields = unserialize( $value->custom_fields );
					$custom_fields_content .= '<div class="custom-fields-container">';
					foreach ( $custom_fields as $key => $custom_field ) {
						$custom_fields_content .= '<div class="cntctfrmtdb-custom-field-text"><strong>' . $key . ':</strong> ';
						$custom_fields_content .= $custom_field . '</div>';
					}
					$custom_fields_content .= '</div>';
				}

				/* forming massiv of messages */
				$list_of_messages[ $i ] = array(
					'id'         => $value->id,
					'from'       => $from_data,
					'message'    => $message_content,
					'attachment' => $attachments_icon,
					'custom_fields' => $custom_fields_content,
					'sent'       => $counter_sent_status,
					'date'       => $send_date
				);
				if ( $this->is_cf_pro_activated )
					$list_of_messages[ $i ]['department'] = $value->department;
				$i++;
			}
			return $list_of_messages;
		}
	}
}
/* End of class */

/*
* Function to save pagination options to data base
* and create new instance of the class cntctfrmtdb_manager
*/
if ( ! function_exists( 'cntctfrmtdb_add_options_manager' ) ) {
	function cntctfrmtdb_add_options_manager() {
		global $cntctfrmtdb_manager;
		cntctfrmtdb_add_tabs();
		$args = array(
			'label'   => __( 'Letters per page', 'contact-form-to-db' ),
			'default' => 30,
			'option'  => 'cntctfrmtdb_letters_per_page'
		);
		add_screen_option( 'per_page', $args );
		$cntctfrmtdb_manager = new cntctfrmtdb_Manager();
	}
}
if ( ! function_exists( 'cntctfrmtdb_set_screen_option' ) ) {
	function cntctfrmtdb_set_screen_option( $status, $option, $value ) {
		if ( 'cntctfrmtdb_letters_per_page' == $option )
			return $value;
	}
}
if ( ! function_exists( 'cntctfrmtdb_manager_pro' ) ) {
    function cntctfrmtdb_manager_pro() {
        global $cntctfrmtdb_plugin_info, $wp_version;

		$cntctfrmtdb_options = get_option('cntctfrmtdb_options');
		$bws_hide_premium_options_check = bws_hide_premium_options_check( $cntctfrmtdb_options ); ?>
        <div class="wrap">
            <h1><span><?php echo get_admin_page_title(); ?></span></h1>
            <?php if ( ! $bws_hide_premium_options_check ) { ?>
                <div class="bws_pro_version_bloc">
                    <div class="bws_pro_version_table_bloc">
                        <div class="bws_table_bg"></div>
                        <div class="bws_pro_version">
                            <ul class='subsubsub'>
                                <li class='all'><a class="current" href="#"><?php _e( 'All', 'contact-form-to-db' ); ?><span> ( 3 )</span></a> |</li>
                                <li class='spam'><a href="#"><?php _e( 'Spam', 'contact-form-to-db' ); ?><span> ( 0 )</span></a></li>
                                <li class='trash'><a href="#"><?php _e( 'Trash', 'contact-form-to-db' ); ?><span> ( 0 )</span></a></li>
                            </ul>
                            <div class="tablenav top">
                                <div class="alignleft actions bulkactions">
                                    <select disabled>
                                        <option value='-1' selected='selected'><?php _e( 'Bulk Actions', 'contact-form-to-db' ); ?></option>
                                    </select>
                                    <input disabled type="submit" class="button action" value="<?php _e( 'Apply', 'contact-form-to-db' ); ?>"  />
                                </div>
                                <div class='tablenav-pages one-page'><span class="displaying-num">3 <?php _e( 'items', 'contact-form-to-db' ); ?></span></div>
                                <br class="clear" />
                            </div>
                            <table class="wp-list-table widefat fixed striped letters">
                                <thead>
                                <tr>
                                    <td id="cb" class='manage-column column-cb check-column'>
                                        <label class="screen-reader-text"><?php _e( 'Select All', 'contact-form-to-db' ); ?></label>
                                        <input disabled type="checkbox" />
                                    </td>
                                    <th scope='col' id="from" class='manage-column column-from column sortable desc'>
                                        <a href="#"><span><?php _e( 'From', 'contact-form-to-db' ); ?></span><span class="sorting-indicator"></span></a>
                                    </th>
                                    <th scope='col' id="message" class='manage-column column-message sortable desc'>
                                        <a href="#"><span><?php _e( 'Message', 'contact-form-to-db' ); ?></span><span class="sorting-indicator"></span></a>
                                    </th>
                                    <th scope='col' id="date" class='manage-column column-date sortable desc'>
                                        <a href="#"><span><?php _e( 'Date', 'contact-form-to-db' ); ?></span><span class="sorting-indicator"></span></a>
                                    </th>
                                </tr>
                                </thead>
                                <tbody id="the-list" data-wp-lists='list:cntctfrmtdb_cf7'>
                                <tr class="alternate">
                                    <th scope="row" class="check-column">
                                        <input disabled type="checkbox"/>
                                    </th>
                                    <td class="message column-message column has-row-actions column-primary" data-colname="From">
                                        <strong><a href="#">lorem.impus@lorem.impus</a></strong>
                                        <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
                                    </td>
                                    <td class="from column-from column has-row-actions column-primary" data-colname="From">
                                        <strong><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></strong>
                                        <div class="row-actions" style="position: static;">
                                            <span class='re_send'><a href="#"><?php _e( 'Re-send', 'contact-form-to-db' ); ?></a> | </span>
                                            <span class='download'><a href="#"><?php _e( 'Download', 'contact-form-to-db' ); ?></a>|</span>
                                            <span class='spam'><a href="#"><?php _e( 'Spam', 'contact-form-to-db' ); ?></a> | </span>
                                            <span class='trash'><a href="#"><?php _e( 'Trash', 'contact-form-to-db' ); ?></a></span>
                                        </div>
                                    </td>
                                    <td class='date column-date' data-colname="Date">2018-11-27 14:26:47</td>
                                </tr>
                                <tr class="alternate">
                                    <th scope="row" class="check-column">
                                        <input disabled type="checkbox"/>
                                    </th>
                                    <td class="from column-from column has-row-actions column-primary" data-colname="From">
                                        <strong><a href="#">lorem.impus@lorem.impus</a></strong>
                                        <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
                                    </td>
                                    <td class="message column-message column has-row-actions column-primary" data-colname="From">
                                        <strong><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></strong>
                                        <div class="row-actions" style="position: static;">
                                            <span class='re_send'><a href="#"><?php _e( 'Re-send', 'contact-form-to-db' ); ?></a> | </span>
                                            <span class='download'><a href="#"><?php _e( 'Download', 'contact-form-to-db' ); ?></a>|</span>
                                            <span class='spam'><a href="#"><?php _e( 'Spam', 'contact-form-to-db' ); ?></a> | </span>
                                            <span class='trash'><a href="#"><?php _e( 'Trash', 'contact-form-to-db' ); ?></a></span>
                                        </div>
                                    </td>
                                    <td class='date column-date' data-colname="Date">2018-11-27 14:26:47</td>
                                </tr>
                                <tr class="alternate">
                                    <th scope="row" class="check-column">
                                        <input disabled type="checkbox"/>
                                    </th>
                                    <td class="from column-from column has-row-actions column-primary" data-colname="From">
                                        <strong><a href="#">lorem.impus@lorem.impus</a></strong>
                                        <button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button>
                                    </td>
                                    <td class="from column-from column has-row-actions column-primary" data-colname="From">
                                        <strong><a href="#">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</a></strong>
                                        <div class="row-actions" style="position: static;">
                                            <span class='re_send'><a href="#"><?php _e( 'Re-send', 'contact-form-to-db' ); ?></a> | </span>
                                            <span class='download'><a href="#"><?php _e( 'Download', 'contact-form-to-db' ); ?></a>|</span>
                                            <span class='spam'><a href="#"><?php _e( 'Spam', 'contact-form-to-db' ); ?></a> | </span>
                                            <span class='trash'><a href="#"><?php _e( 'Trash', 'contact-form-to-db' ); ?></a></span>
                                        </div>
                                    </td>
                                    <td class='date column-date' data-colname="Date">2018-11-27 14:26:47</td>
                                </tr>
                                </tbody>
                                <tfoot>
                                <tr>
                                    <td class='manage-column column-cb check-column'>
                                        <label class="screen-reader-text"><?php _e( 'Select All', 'sender' ); ?></label>
                                        <input disabled type="checkbox" />
                                    </td>
                                    <th scope='col' id="from" class='manage-column column-from column sortable desc'>
                                        <a href="#"><span><?php _e( 'From', 'contact-form-to-db' ); ?></span><span class="sorting-indicator"></span></a>
                                    </th>
                                    <th scope='col' id="message" class='manage-column column-message sortable desc'>
                                        <a href="#"><span><?php _e( 'Message', 'contact-form-to-db' ); ?></span><span class="sorting-indicator"></span></a>
                                    </th>
                                    <th scope='col' id="date" class='manage-column column-date sortable desc'>
                                        <a href="#"><span><?php _e( 'Date', 'contact-form-to-db' ); ?></span><span class="sorting-indicator"></span></a>
                                    </th>
                                </tr>
                                </tfoot>
                            </table>
                            <div class="tablenav bottom">
                                <div class="alignleft actions bulkactions">
                                    <select disabled name='action2'>
                                        <option value='-1' selected='selected'><?php _e( 'Bulk Actions', 'contact-form-to-db' ); ?></option>
                                        <option value='trash_letters'><?php _e( 'Trash', 'sender' ); ?></option>
                                    </select>
                                    <input disabled type="submit" name="" id="doaction2" class="button action" value="<?php _e( 'Apply', 'contact-form-to-db' ); ?>"  />
                                </div>
                                <div class='tablenav-pages one-page'><span class="displaying-num">3 <?php _e( 'items', 'contact-form-to-db' ); ?></span></div>
                                <br class="clear" />
                            </div>
                        </div>
                    </div>
                    <div class="bws_pro_version_tooltip">
                        <a class="bws_button" href="https://bestwebsoft.com/products/wordpress/plugins/contact-form-to-db/?k=5906020043c50e2eab1528d63b126791&pn=91&v=<?php echo $cntctfrmtdb_plugin_info["Version"]; ?>&wp_v=<?php echo $wp_version; ?>" target="_blank" title="Contact Form to DB Pro"><?php _e( 'Upgrade to Pro', 'contact-form-to-db' ); ?></a>
                        <div class="clear"></div>
                    </div>
                </div>
            <?php } else { ?>
                <p>
		            <?php _e( 'This tab contains Pro options only.', 'contact-form-to-db' );
		            echo ' ' . sprintf(
				            __( '%sChange the settings%s to view the Pro options.', 'contact-form-to-db' ),
				            '<a href="admin.php?page=contact_form_to_db.php&bws_active_tab=misc">',
				            '</a>'
			            ); ?>
                </p>
            <?php } ?>
        </div><!-- .wrap -->
    <?php }
}

/*
* Function to display plugin page
*/
if ( ! function_exists( 'cntctfrmtdb_manager_page' ) ) {
	function cntctfrmtdb_manager_page() {
 		global $cntctfrmtdb_done_message, $cntctfrmtdb_error_message, $cntctfrmtdb_manager;
 		$cntctfrmtdb_manager->prepare_items(); ?>
		<div class="wrap cntctfrmtdb">
			<h1>Contact Form to DB</h1>
			<noscript>
				<div class="error below-h2">
					<p><strong><?php _e( 'WARNING:', 'contact-form-to-db' ); ?></strong> <?php _e( 'Please enable JavaScript in your browser for correct plugin work.', 'contact-form-to-db' ); ?></p>
				</div>
			</noscript>
			<div class="updated below-h2" <?php if ( '' == $cntctfrmtdb_done_message ) echo 'style="display: none;"'?>><p><?php echo $cntctfrmtdb_done_message ?></p></div>
			<div class="error below-h2" <?php if ( '' == $cntctfrmtdb_error_message ) echo 'style="display: none;"'?>><p><strong><?php _e( 'WARNING:', 'contact-form-to-db' ); ?></strong> <?php echo $cntctfrmtdb_error_message . ' ' . __( 'Please, try it later.', 'contact-form-to-db' ); ?></p></div>
			<?php if ( isset( $_REQUEST['s'] ) && $_REQUEST['s'] )
					printf( '<span class="subtitle">' . sprintf( __( 'Search results for &#8220;%s&#8221;', 'contact-form-to-db' ), wp_html_excerpt( esc_html( stripslashes( $_REQUEST['s'] ) ), 50 ) ) . '</span>' );
			$cntctfrmtdb_manager->views(); ?>
			<form id="posts-filter" method="get">
				<input type="hidden" name="page" value="cntctfrmtdb_manager" />
				<input type="hidden" name="message_status" class="message_status_page" value="<?php echo !empty($_REQUEST['message_status']) ? esc_attr($_REQUEST['message_status']) : 'all'; ?>" />
				<?php $cntctfrmtdb_manager->search_box( __( 'Search emails', 'contact-form-to-db' ), 'search_id' );
				$cntctfrmtdb_manager->display();
				wp_nonce_field( plugin_basename( __FILE__ ), 'cntctfrmtdb_manager_nonce_name' ); ?>
			</form>
		</div>
	<?php }
}
/*
*
* AJAX functions
*
* Function to change read/not-read message status
*/
if ( ! function_exists( 'cntctfrmtdb_read_message' ) ) {
	function cntctfrmtdb_read_message() {
		global $wpdb;
		$prefix = $wpdb->prefix . 'cntctfrmtdb_';
		check_ajax_referer( plugin_basename( __FILE__ ), 'cntctfrmtdb_ajax_nonce_field' );
		$wpdb->update( $prefix . 'message', array( 'was_read' => $_POST['cntctfrmtdb_ajax_read_status'] ), array( 'id' => $_POST['cntctfrmtdb_ajax_message_id'] ) );
		die();
	}
}

/*
* Function to show attachment of message
*/
if ( ! function_exists( 'cntctfrmtdb_show_attachment' ) ) {
	function cntctfrmtdb_show_attachment() {
		if ( isset( $_POST['action'] ) && 'cntctfrmtdb_show_attachment' == $_POST['action'] ) {
			global $wp_version, $cntctfrmtdb_plugin_info;
			echo '<td valign="middle" class="cntctfrmtdb-thumbnail">
				<a href="https://bestwebsoft.com/products/wordpress/plugins/contact-form-to-db/?k=5906020043c50e2eab1528d63b126791&pn=91&v=' . $cntctfrmtdb_plugin_info["Version"] . '&wp_v=' . $wp_version . '" title="' . __( 'This option is available in Pro version', 'contact-form-to-db' ) . '" target="_blank">
					<img src="' . plugins_url( 'images/no-image.jpg', __FILE__ ) . '" title="' . __( 'This option is available in Pro version', 'contact-form-to-db' ) . '" alt="' . __( 'Can not display thumbnail','contact_form_to_db_plugin' ) . '" />
				</a>
			</td>';
			die();
		}
	}
}

/*
* Function to change message status
*/
if ( ! function_exists( 'cntctfrmtdb_change_status' ) ) {
	function cntctfrmtdb_change_status() {
		global $wpdb;
		$prefix = $wpdb->prefix . 'cntctfrmtdb_';
		check_ajax_referer( plugin_basename( __FILE__ ), 'cntctfrmtdb_ajax_nonce_field' );
		$wpdb->update( $prefix . 'message', array( 'status_id' => $_POST['cntctfrmtdb_ajax_message_status'] ), array( 'id' => $_POST['cntctfrmtdb_ajax_message_id'] ) );
		if ( ! $wpdb->last_error ) {
			switch ( $_POST['cntctfrmtdb_ajax_message_status'] ) {
				case 1:
					$result = '<div class="updated below-h2"><p>' . __( 'One message was marked as Normal.', 'contact-form-to-db' ) . '</a></p></div>';
					break;
				case 2:
					$result = '<div class="updated below-h2"><p>' . __( 'One message was marked as Spam.', 'contact-form-to-db' ) . ' <a href="' . wp_nonce_url( '?page=cntctfrmtdb_manager&action=undo&message_id[]=' . $_POST['cntctfrmtdb_ajax_message_id'] . '&old_status=' . $_POST['cntctfrmtdb_ajax_old_status'], plugin_basename( __FILE__ ), 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Undo', 'contact-form-to-db' ) . '</a></p></div>';
					break;
				case 3:
					$result = '<div class="updated below-h2"><p>' . __( 'One message was marked as Trash.', 'contact-form-to-db' ) . ' <a href="' . wp_nonce_url( '?page=cntctfrmtdb_manager&action=undo&message_id[]=' . $_POST['cntctfrmtdb_ajax_message_id'] . '&old_status=' . $_POST['cntctfrmtdb_ajax_old_status'], plugin_basename( __FILE__ ), 'cntctfrmtdb_manager_nonce_name' ) . '">' . __( 'Undo', 'contact-form-to-db' ) . '</a></p></div>';
					break;
				default:
					$result = '<div class="error below-h2"><p><strong>' . __( 'WARNING:', 'contact-form-to-db' ) . '</strong> ' . __( 'Unknown result.', 'contact-form-to-db' ) . '</p></div>';
					break;
			}
		} else {
			$result = '<div class="error below-h2"><p><strong>' . __( 'WARNING:', 'contact-form-to-db' ) . '</strong> ' . __( 'Problems while changing the message status. Please, try it later.', 'contact-form-to-db' ) . '</p></div>';
		}
		echo $result;
		die();
	}
}

/*
* Function to add actions link to block with plugins name on "Plugins" page
*/
if ( ! function_exists( 'cntctfrmtdb_plugin_action_links' ) ) {
	function cntctfrmtdb_plugin_action_links( $links, $file ) {
		if ( ! is_network_admin() ) {
			static $this_plugin;
			if ( ! $this_plugin )
				$this_plugin = plugin_basename( __FILE__ );
			if ( $file == $this_plugin ) {
				$settings_link = '<a href="admin.php?page=contact_form_to_db.php">' . __( 'Settings', 'contact-form-to-db' ) . '</a>';
				array_unshift( $links, $settings_link );
			}
		}
		return $links;
	}
}

/*
* Function to add links to description block on "Plugins" page
*/
if ( ! function_exists( 'cntctfrmtdb_register_plugin_links' ) ) {
	function cntctfrmtdb_register_plugin_links( $links, $file ) {
		$base = plugin_basename( __FILE__ );
		if ( $file == $base ) {
			if ( ! is_network_admin() )
				$links[] = '<a href="admin.php?page=contact_form_to_db.php">' . __( 'Settings','contact-form-to-db' ) . '</a>';
			$links[] = '<a href="https://support.bestwebsoft.com/hc/en-us/sections/200538679" target="_blank">' . __( 'FAQ','contact-form-to-db' ) . '</a>';
			$links[] = '<a href="https://support.bestwebsoft.com">' . __( 'Support','contact-form-to-db' ) . '</a>';
		}
		return $links;
	}
}

/*
* Add notises on plugins page if Contact Form plugin is not installed or not active
*/
if ( ! function_exists( 'cntctfrmtdb_show_notices' ) ) {
	function cntctfrmtdb_show_notices() {
		global $hook_suffix, $cntctfrmtdb_options, $bstwbsftwppdtplgns_cookie_add, $cntctfrmtdb_plugin_info, $cntctfrmtdb_pages;

		if ( $hook_suffix == 'plugins.php' || ( isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], $cntctfrmtdb_pages ) ) ) {

			if ( ! function_exists( 'is_plugin_active' ) )
				require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

			$all_plugins = get_plugins();

			if ( ! ( array_key_exists( 'contact-form-plugin/contact_form.php', $all_plugins ) || array_key_exists( 'contact-form-pro/contact_form_pro.php', $all_plugins ) ) ) {
				$contact_form_notice = __( 'Contact Form plugin is not found.</br>You need to install and activate this plugin for correct work with Contact Form to DB plugin.</br>You can download Contact Form plugin from ', 'contact-form-to-db' ) . '<a href="' . esc_url( 'https://bestwebsoft.com/products/wordpress/plugins/contact-form/' ) . '" title="' . __( 'Developers website', 'contact-form-to-db' ). '"target="_blank">' . __( 'website of plugin Authors ', 'contact-form-to-db' ) . '</a>&nbsp;' . __( 'or', 'contact-form-to-db' ) . '&nbsp;<a href="' . esc_url( 'http://wordpress.org/plugins/contact-form-plugin/' ) .'" title="Wordpress" target="_blank">'. __( 'WordPress.', 'contact-form-to-db' ) . '</a>';
			} else {
				$contact_form_notice = '';
				if ( ! ( is_plugin_active( 'contact-form-plugin/contact_form.php' ) || is_plugin_active( 'contact-form-pro/contact_form_pro.php' ) ) ) {
					$contact_form_notice .= __( 'Contact Form plugin is not active.</br>You need to activate this plugin for correct work with Contact Form to DB plugin.', 'contact-form-to-db' );
					if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array( 'cntctfrmtdb_manager', 'contact_form_to_db.php' ) ) ) {
						$contact_form_notice .= '<br/><a href="plugins.php">' . __('Activate plugin', 'contact-form-to-db') . '</a>';
					}
				}
				/* old version */
				if ( ( is_plugin_active( 'contact-form-plugin/contact_form.php' ) && isset( $all_plugins['contact-form-plugin/contact_form.php']['Version'] ) && $all_plugins['contact-form-plugin/contact_form.php']['Version'] < '3.60' ) ||
					( is_plugin_active( 'contact-form-pro/contact_form_pro.php' ) && isset( $all_plugins['contact-form-pro/contact_form_pro.php']['Version'] ) && $all_plugins['contact-form-pro/contact_form_pro.php']['Version'] < '1.12' ) ) {
					$contact_form_notice .= __( 'Contact Form plugin has old version.</br>You need to update this plugin for correct work with Contact Form to DB plugin.', 'contact-form-to-db' );
					if ( isset( $_GET['page'] ) && in_array( $_GET['page'], array( 'cntctfrmtdb_manager', 'contact_form_to_db.php' ) ) ) {
						$contact_form_notice .= '<br/><a href="plugins.php">' . __('Update plugin', 'contact-form-to-db') . '</a>';
					}
				}
			}
			if ( ! empty( $contact_form_notice ) ) { ?>
				<div class="error notice">
					<p><strong><?php _e( 'WARNING:', 'contact-form-to-db'); ?></strong> <?php echo $contact_form_notice; ?></p>
				</div>
			<?php }
		}

		/* chech plugin settings and add notice */
		if ( isset( $_REQUEST['page'] ) && 'cntctfrmtdb_manager' == $_REQUEST['page'] ) {

		    if ( empty( $cntctfrmtdb_options ) )
				cntctfrmtdb_settings();

			if ( isset( $cntctfrmtdb_options['save_messages_to_db'] ) && 0 == $cntctfrmtdb_options['save_messages_to_db'] ) {
				if ( ! isset( $bstwbsftwppdtplgns_cookie_add ) ) {
					wp_enqueue_script( 'bstwbsftwppdtplgns_cookie_add', plugins_url( 'bws_menu/js/c_o_o_k_i_e.js', __FILE__ ) );
					$bstwbsftwppdtplgns_cookie_add = true;
				}
				$script = "(function($) {
                    $(document).ready( function() {
                        var hide_message = $.cookie( 'cntctfrmtdb_save_messages_to_db' );
                        if ( hide_message == 'true' ) {
                            $( 'cntctfrmtdb_save_messages_to_db' ).css( 'display', 'none' );
                        } else {
                            $( 'cntctfrmtdb_save_messages_to_db' ).css( 'display', 'block' );
                        };
                        $( 'cntctfrmtdb_close_icon' ).click( function() {
                            $( 'cntctfrmtdb_save_messages_to_db' ).css( 'display', 'none' );
                            $.cookie( 'cntctfrmtdb_save_messages_to_db', 'true', { expires: 7 } );
                        });
                    });
                })(jQuery);";

				wp_register_script( 'cntctfrmtdb_hide_banner_on_plugin_page', '//' );
				wp_enqueue_script( 'cntctfrmtdb_hide_banner_on_plugin_page' );
				wp_add_inline_script( 'cntctfrmtdb_hide_banner_on_plugin_page', sprintf( $script ) ); ?>
				<div class="updated fade cntctfrmtdb_save_messages_to_db" style="display: none;">
					<img style="float: right;cursor: pointer;" class="cntctfrmtdb_close_icon" title="" src="<?php echo plugins_url( '/bws_menu/images/close_banner.png', __FILE__ ); ?>" alt=""/>
					<div style="float: left;margin: 5px;"><strong><?php _e( 'Notice:', 'contact-form-to-db'); ?></strong> <?php _e( 'Option "Save messages to database" was disabled on the plugin settings page.', 'contact-form-to-db'); ?> <a href="admin.php?page=contact_form_to_db.php"><?php _e( 'Enable it for saving messages from Contact Form', 'contact-form-to-db'); ?></a></div>
					<div style="clear:both;float: none;margin: 0;"></div>
				</div>
			<?php }
		}
		if ( $hook_suffix == 'plugins.php' ) {
			bws_plugin_banner_to_settings( $cntctfrmtdb_plugin_info, 'cntctfrmtdb_options', 'contact-form-to-db', 'admin.php?page=contact_form_to_db.php' );
		}

		if ( isset( $_REQUEST['page'] ) && 'contact_form_to_db.php' == $_REQUEST['page'] ) {
			bws_plugin_suggest_feature_banner( $cntctfrmtdb_plugin_info, 'cntctfrmtdb_options', 'contact-form-to-db' );
		}
	}
}

/* add help tab */
if ( ! function_exists( 'cntctfrmtdb_add_tabs' ) ) {
	function cntctfrmtdb_add_tabs() {
		global $cntctfrmtdb_pages;
		$screen = get_current_screen();
		if ( isset( $_REQUEST['page'] ) && in_array( $_REQUEST['page'], $cntctfrmtdb_pages ) ) {
			$args = array(
				'id' 			=> 'cntctfrmtdb',
				'section' 		=> '200538679'
			);
			bws_help_tab( $screen, $args );
		}
	}
}

/*
* Function for delete options and tables
*/
if ( ! function_exists ( 'cntctfrmtdb_delete_options' ) ) {
	function cntctfrmtdb_delete_options() {
		global $wpdb;
		$all_plugins = get_plugins();

		if ( ! array_key_exists( 'contact-form-to-db-pro/contact_form_to_db_pro.php', $all_plugins ) ) {
			if ( is_multisite() ) {
				/* Get all blog ids */
				$blogids = $wpdb->get_col( "SELECT `blog_id` FROM $wpdb->blogs" );
				$old_blog = $wpdb->blogid;
				foreach ( $blogids as $blog_id ) {
					switch_to_blog( $blog_id );
					$prefix = 1 == $blog_id ? $wpdb->base_prefix . 'cntctfrmtdb_' : $wpdb->base_prefix . $blog_id . '_cntctfrmtdb_';
					$wpdb->query( "DROP TABLE `" . $prefix . "message_status`,`" . $prefix . "blogname`,`" . $prefix . "to_email`,`" . $prefix . "hosted_site`, `" . $prefix . "refer`, `" . $prefix . "message`,`" . $prefix . "field_selection`;" );

					delete_option( "cntctfrmtdb_options" );
				}
				switch_to_blog( $old_blog );
			} else {
				$prefix = $wpdb->prefix . 'cntctfrmtdb_';
				$wpdb->query( "DROP TABLE `" . $prefix . "message_status`,`" . $prefix . "blogname`,`" . $prefix . "to_email`,`" . $prefix . "hosted_site`, `" . $prefix . "refer`, `" . $prefix . "message`,`" . $prefix . "field_selection`;" );
				delete_option( "cntctfrmtdb_options" );
			}

			/* delete images */
			if ( is_multisite() ) {
				switch_to_blog( 1 );
				$upload_dir = wp_upload_dir();
				restore_current_blog();
			} else {
				$upload_dir = wp_upload_dir();
			}
			$images_dir = $upload_dir['basedir'] . '/attachments';
			array_map( 'unlink', glob( $images_dir . "/" . "*.*" ) );
			rmdir( $images_dir );
		}

		require_once( dirname( __FILE__ ) . '/bws_menu/bws_include.php' );
		bws_include_init( plugin_basename( __FILE__ ) );
		bws_delete_plugin( plugin_basename( __FILE__ ) );
	}
}

/*
* Add all hooks
*/
/* Activate plugin */
register_activation_hook( __FILE__, 'cntctfrmtdb_activation' );
add_action( 'plugins_loaded', 'cntctfrmtdb_plugins_loaded' );
/* add menu items in to dashboard menu */
add_action( 'admin_menu', 'cntctfrmtdb_admin_menu' );
/* init hooks */
add_action( 'init', 'cntctfrmtdb_init' );
add_action( 'admin_init', 'cntctfrmtdb_admin_init' );
/* add pligin scripts and stylesheets */
add_action( 'admin_enqueue_scripts', 'cntctfrmtdb_admin_head' );
/* add action link of plugin on "Plugins" page */
add_filter( 'plugin_action_links', 'cntctfrmtdb_plugin_action_links', 10, 2 );
add_filter( 'plugin_row_meta', 'cntctfrmtdb_register_plugin_links', 10, 2 );
/* hooks for get mail data */
add_action( 'cntctfrm_get_mail_data', 'cntctfrmtdb_get_mail_data', 10, 11 );
add_action( 'cntctfrm_get_attachment_data', 'cntctfrmtdb_get_attachment_data' );
add_action( 'cntctfrm_check_dispatch', 'cntctfrmtdb_check_dispatch', 10, 1 );
add_filter( 'set-screen-option', 'cntctfrmtdb_set_screen_option', 10, 3 );
/*hooks for ajax */
add_action( 'wp_ajax_cntctfrmtdb_read_message', 'cntctfrmtdb_read_message' );
add_action( 'wp_ajax_cntctfrmtdb_show_attachment', 'cntctfrmtdb_show_attachment' );
add_action( 'wp_ajax_cntctfrmtdb_change_staus', 'cntctfrmtdb_change_status' );
/* check for installed and activated Contact Form plugin ; add banner on the plugins page */
add_action( 'admin_notices', 'cntctfrmtdb_show_notices' );