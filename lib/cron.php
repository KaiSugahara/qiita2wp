<?php

/*
    Register update-function to WP_CRON
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'qiita2wp_update_cron', 'qiita2wp_update' );

$options = get_option( 'qiita2wp_settings', [] );

if( $cron_interval = $options['cron_interval'] ) {
    if ( !wp_next_scheduled( 'qiita2wp_update_cron' ) ) {
        date_default_timezone_set('Asia/Tokyo');
        wp_schedule_event( strtotime('2022-09-04 00:00:00'), $cron_interval, 'qiita2wp_update_cron' );
    }   
}