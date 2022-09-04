<?php
/*
Plugin Name: Qiita2WP
Description: WordPress Plugin for Qiita Users. Automatically crawling your Qiita articles and place links (posts) on your WordPress site.
Author: Kai Sugahara
Author URI: https://fulfills.jp/
Domain Path: /languages/
Version: 1.0.0
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function qiita2wp_sanitize($obj, $is_textarea = 0) {
    if(is_array($obj)) {
        foreach($obj as $key => $val) $obj[$key] = qiita2wp_sanitize($val, $is_textarea);
        return $obj;
    }
    if($is_textarea) return sanitize_textarea_field($obj);
    return sanitize_text_field($obj);
}

// ADD SETTING PAGE
function qiita2wp_add_page() {
    include_once 'template/admin.php';
    add_submenu_page('options-general.php', __('Qiita2WP'), __('Qiita2WP'), 'publish_posts', 'qiita2wp_add_adminpage', 'qiita2wp_add_admin_page');
}
add_action('admin_menu', 'qiita2wp_add_page');

// INCLUDE
include_once 'lib/init.php';
include_once 'lib/update.php';
include_once 'lib/cron.php';