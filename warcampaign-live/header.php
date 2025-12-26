<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="War Campaign Live - Your Premier Indie Comics Livestream & Podcast">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<header class="site-header">
    <div class="header-container">
        <div class="header-left">
            <?php if (has_custom_logo()) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url(home_url('/')); ?>">
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.jpg'); ?>"
                         alt="<?php bloginfo('name'); ?>"
                         class="site-logo">
                </a>
            <?php endif; ?>
        </div>

        <div class="header-right">
            <span class="site-tagline">Indie Comics Podcast</span>

            <?php
            $is_live = get_theme_mod('is_stream_live', false);
            $live_class = $is_live ? '' : 'offline';
            ?>
            <div class="live-indicator <?php echo esc_attr($live_class); ?>">
                <span class="live-dot"></span>
                <span class="live-text"><?php echo $is_live ? 'LIVE NOW' : 'OFFLINE'; ?></span>
            </div>
        </div>
    </div>
</header>

<main class="site-main">
