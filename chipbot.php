<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

/*
Plugin Name: ChipBot
Plugin URI: https://getchipbot.com/?utm_source=wordpress&utm_medium=plugin-link
description: ChipBot engages customers with video, helps you sell with live chat, and provides customer support with an AI help desk.
Author: GetChipBot.com
Author URI: https://getchipbot.com/?utm_source=wordpress&utm_medium=author-link
Version: 2.0.3
*/

class ChipBotWordpressPlugin {
  public $slug = 'getchipbot-com';

  public function __construct() {
    add_action('admin_menu', array($this, 'create_plugin_settings_page'));
    add_action('admin_init', array($this, 'init'));
    add_action('wp_head', array($this, 'injectAccountIdVariable'));
    add_action('wp_head', array($this, 'script'));
    add_action('admin_enqueue_scripts', array($this, 'admin_styles'));
  }

  public function injectAccountIdVariable() {
    $aid = get_option('chipbot_account_id');
    $jsSnippet = get_option('chipbot_js_snippet');

    if (trim($aid) !== '' && (!isset($jsSnippet) || trim($jsSnippet) === '')) {
      echo '
                <script type="text/javascript">
                  var CHIPBOT_ID = "' . $aid . '";
                </script>
            ';
    }
  }

  public function script() {
    $aid = get_option('chipbot_account_id');
    $jsSnippet = get_option('chipbot_js_snippet');

    if (trim($aid) !== '' && (!isset($jsSnippet) || trim($jsSnippet) === '')) {
      echo '
            <script type="text/javascript">
              var CHIPBOT_ID = "' . $aid . '";
            </script>
            ';

      echo '<script src="https://static.getchipbot.com/edge/p/chipbot.js?id=' . get_option('chipbot_account_id') . '" async></script>';
    }

    if (isset($jsSnippet) || trim($jsSnippet) !== '') {
      echo $jsSnippet;
    }
  }

  public function init() {
    // legacy impl and deprecated
    register_setting('settings_group', 'chipbot_account_id');
    // new impl
    register_setting('settings_group', 'chipbot_js_snippet');
  }

  public function create_plugin_settings_page() {
    $pluginPath = plugins_url('chipbot');
    $page_title = 'ChipBot Settings';
    $menu_title = 'ChipBot';
    $capability = 'manage_options';
    $slug = $this->slug;
    $callback = array($this, 'plugin_settings_page_content');
    $icon = $pluginPath . '/cb-square-logo-dark-rounded-16px.svg';
    $position = 100;

    add_menu_page($page_title, $menu_title, $capability, $slug, $callback, $icon, $position);
  }

  public function plugin_settings_page_content() {
    include(plugin_dir_path(__FILE__) . '/settings.php');
  }

  public function admin_styles($hook) {
    // only load script on top level admin page
    if ($hook === ('toplevel_page_' . $this->slug)) {
      wp_enqueue_style('chipbot_admin_style', plugin_dir_url(__FILE__) . 'styles.css', 1);
    }
  }
}

new ChipBotWordpressPlugin();
