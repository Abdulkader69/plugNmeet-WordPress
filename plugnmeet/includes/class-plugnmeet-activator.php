<?php
/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @todo This should probably be in one class together with Deactivator Class.
 * @since      1.0.0
 * @package    Plugnmeet
 * @subpackage Plugnmeet/includes
 * @author     Jibon Costa <jibon@mynaparrot.com>
 */

if (!defined('PLUGNMEET_BASE_NAME')) {
    die;
}

class Plugnmeet_Activator {

    /**
     * The $_REQUEST during plugin activation.
     *
     * @since    1.0.0
     * @access   private
     * @var      array $request The $_REQUEST array during plugin activation.
     */
    private static $request = array();

    /**
     * The $_REQUEST['plugin'] during plugin activation.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin The $_REQUEST['plugin'] value during plugin activation.
     */
    private static $plugin = PLUGNMEET_BASE_NAME;

    /**
     * Activate the plugin.
     *
     * Checks if the plugin was (safely) activated.
     * Place to add any custom action during plugin activation.
     *
     * @since    1.0.0
     */
    public static function activate() {

        if (false === self::get_request()
            || false === self::validate_request(self::$plugin)
            || false === self::check_caps()
        ) {
            if (isset($_REQUEST['plugin'])) {
                if (!check_admin_referer('activate-plugin_' . self::$request['plugin'])) {
                    exit;
                }
            } elseif (isset($_REQUEST['checked'])) {
                if (!check_admin_referer('bulk-plugins')) {
                    exit;
                }
            }
        }

        flush_rewrite_rules();
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . "plugnmeet_rooms";

        $sql = "CREATE TABLE IF NOT EXISTS `{$table_name}` (
          `id` int(11) NOT NULL AUTO_INCREMENT,
          `room_id` varchar(36) NOT NULL,
          `room_title` varchar(255) NOT NULL,
          `description` text NOT NULL,
          `moderator_pass` varchar(255) NOT NULL,
          `attendee_pass` varchar(255) NOT NULL,
          `welcome_message` text NOT NULL,
          `max_participants` int(10) NOT NULL,
          `room_metadata` text NOT NULL,
          `published` int(1) NOT NULL,
          `created_by` int(10) NOT NULL,
          `modified_by` int(10) DEFAULT NULL,
          `created` datetime NOT NULL DEFAULT current_timestamp(),
          `modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE current_timestamp(),
          PRIMARY KEY (`id`),
          UNIQUE KEY `room_id` (`room_id`),
          KEY `published` (`published`)
        ) {$charset_collate};";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }

    /**
     * Get the request.
     *
     * Gets the $_REQUEST array and checks if necessary keys are set.
     * Populates self::request with necessary and sanitized values.
     *
     * @return bool|array false or self::$request array.
     * @since    1.0.0
     */
    private static function get_request() {

        if (!empty($_REQUEST)
            && isset($_REQUEST['_wpnonce'])
            && isset($_REQUEST['action'])
        ) {
            if (isset($_REQUEST['plugin'])) {
                if (false !== wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'activate-plugin_' . sanitize_text_field(wp_unslash($_REQUEST['plugin'])))) {

                    self::$request['plugin'] = sanitize_text_field(wp_unslash($_REQUEST['plugin']));
                    self::$request['action'] = sanitize_text_field(wp_unslash($_REQUEST['action']));

                    return self::$request;

                }
            } elseif (isset($_REQUEST['checked'])) {
                if (false !== wp_verify_nonce(sanitize_text_field(wp_unslash($_REQUEST['_wpnonce'])), 'bulk-plugins')) {

                    self::$request['action'] = sanitize_text_field(wp_unslash($_REQUEST['action']));
                    self::$request['plugins'] = array_map('sanitize_text_field', wp_unslash($_REQUEST['checked']));

                    return self::$request;

                }
            }
        }
        
        return false;
    }

    /**
     * Validate the Request data.
     *
     * Validates the $_REQUESTed data is matching this plugin and action.
     *
     * @param string $plugin The Plugin folder/name.php.
     * @return bool false if either plugin or action does not match, else true.
     * @since    1.0.0
     */
    private static function validate_request($plugin) {

        if (isset(self::$request['plugin'])
            && $plugin === self::$request['plugin']
            && 'activate' === self::$request['action']
        ) {

            return true;

        } elseif (isset(self::$request['plugins'])
            && 'activate-selected' === self::$request['action']
            && in_array($plugin, self::$request['plugins'])
        ) {
            return true;
        }

        return false;

    }

    /**
     * Check Capabilities.
     *
     * We want no one else but users with activate_plugins or above to be able to active this plugin.
     *
     * @return bool false if no caps, else true.
     * @since    1.0.0
     */
    private static function check_caps() {

        if (current_user_can('activate_plugins')) {
            return true;
        }

        return false;

    }

}

