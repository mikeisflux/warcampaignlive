<?php
/**
 * War Campaign Live Theme Functions
 *
 * @package WarCampaignLive
 * @version 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Theme Setup
 */
function warcampaign_live_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('custom-logo', array(
        'height'      => 100,
        'width'       => 400,
        'flex-height' => true,
        'flex-width'  => true,
    ));
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));

    // Register nav menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'warcampaign-live'),
    ));
}
add_action('after_setup_theme', 'warcampaign_live_setup');

/**
 * Enqueue styles and scripts
 */
function warcampaign_live_scripts() {
    // Main stylesheet
    wp_enqueue_style(
        'warcampaign-live-style',
        get_stylesheet_uri(),
        array(),
        wp_get_theme()->get('Version')
    );

    // Google Fonts (optional, using system fonts by default)
    wp_enqueue_style(
        'warcampaign-live-fonts',
        'https://fonts.googleapis.com/css2?family=Oswald:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap',
        array(),
        null
    );
}
add_action('wp_enqueue_scripts', 'warcampaign_live_scripts');

/**
 * Get the last stream date (yesterday)
 */
function warcampaign_get_last_stream_date() {
    $yesterday = strtotime('-1 day');
    return date_i18n(get_option('date_format'), $yesterday);
}

/**
 * Check if stream is currently live
 * This can be customized to check an external API
 */
function warcampaign_is_stream_live() {
    // Default to false - can be modified to check live status
    return apply_filters('warcampaign_stream_live_status', false);
}

/**
 * Get stream embed URL
 * Can be customized in theme options
 */
function warcampaign_get_stream_url() {
    return apply_filters('warcampaign_stream_url', '');
}

/**
 * Customizer settings
 */
function warcampaign_live_customize_register($wp_customize) {
    // Stream Settings Section
    $wp_customize->add_section('warcampaign_stream_settings', array(
        'title'    => __('Stream Settings', 'warcampaign-live'),
        'priority' => 30,
    ));

    // Stream Embed URL
    $wp_customize->add_setting('stream_embed_url', array(
        'default'           => '',
        'sanitize_callback' => 'esc_url_raw',
    ));

    $wp_customize->add_control('stream_embed_url', array(
        'label'       => __('Stream Embed URL', 'warcampaign-live'),
        'description' => __('Enter your livestream embed URL (YouTube, Twitch, etc.)', 'warcampaign-live'),
        'section'     => 'warcampaign_stream_settings',
        'type'        => 'url',
    ));

    // Stream Title
    $wp_customize->add_setting('stream_title', array(
        'default'           => 'War Campaign Live',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('stream_title', array(
        'label'   => __('Stream Title', 'warcampaign-live'),
        'section' => 'warcampaign_stream_settings',
        'type'    => 'text',
    ));

    // Stream Subtitle
    $wp_customize->add_setting('stream_subtitle', array(
        'default'           => 'Your Source for Indie Comics',
        'sanitize_callback' => 'sanitize_text_field',
    ));

    $wp_customize->add_control('stream_subtitle', array(
        'label'   => __('Stream Subtitle', 'warcampaign-live'),
        'section' => 'warcampaign_stream_settings',
        'type'    => 'text',
    ));

    // Is Live Setting
    $wp_customize->add_setting('is_stream_live', array(
        'default'           => false,
        'sanitize_callback' => 'warcampaign_sanitize_checkbox',
    ));

    $wp_customize->add_control('is_stream_live', array(
        'label'   => __('Stream is Live', 'warcampaign-live'),
        'section' => 'warcampaign_stream_settings',
        'type'    => 'checkbox',
    ));
}
add_action('customize_register', 'warcampaign_live_customize_register');

/**
 * Sanitize checkbox
 */
function warcampaign_sanitize_checkbox($checked) {
    return ((isset($checked) && true == $checked) ? true : false);
}

/**
 * Add body classes
 */
function warcampaign_body_classes($classes) {
    if (get_theme_mod('is_stream_live', false)) {
        $classes[] = 'stream-live';
    }
    return $classes;
}
add_filter('body_class', 'warcampaign_body_classes');
