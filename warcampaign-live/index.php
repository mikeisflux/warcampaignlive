<?php
/**
 * Main Template File
 *
 * @package WarCampaignLive
 */

get_header();

$stream_title = get_theme_mod('stream_title', 'Warcampaign Live');
$stream_subtitle = get_theme_mod('stream_subtitle', 'Your Source for Indie Comics');
$stream_hls_url = get_theme_mod('stream_hls_url', '');
$stream_embed_url = get_theme_mod('stream_embed_url', '');
$is_live = get_theme_mod('is_stream_live', false);

// Calculate yesterday's date for "last streamed"
$yesterday = new DateTime('yesterday');
$last_streamed = $yesterday->format('F j, Y');

// Determine which player to use
$use_hls = !empty($stream_hls_url);
$use_embed = !empty($stream_embed_url) && !$use_hls;
?>

<section class="stream-section">
    <div class="stream-header">
        <h1 class="stream-title"><?php echo esc_html($stream_title); ?></h1>
        <p class="stream-subtitle"><?php echo esc_html($stream_subtitle); ?></p>
        <span class="indie-comics-badge">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="vertical-align: middle; margin-right: 5px;">
                <path d="M21 5c-1.11-.35-2.33-.5-3.5-.5-1.95 0-4.05.4-5.5 1.5-1.45-1.1-3.55-1.5-5.5-1.5S2.45 4.9 1 6v14.65c0 .25.25.5.5.5.1 0 .15-.05.25-.05C3.1 20.45 5.05 20 6.5 20c1.95 0 4.05.4 5.5 1.5 1.35-.85 3.8-1.5 5.5-1.5 1.65 0 3.35.3 4.75 1.05.1.05.15.05.25.05.25 0 .5-.25.5-.5V6c-.6-.45-1.25-.75-2-1zm0 13.5c-1.1-.35-2.3-.5-3.5-.5-1.7 0-4.15.65-5.5 1.5V8c1.35-.85 3.8-1.5 5.5-1.5 1.2 0 2.4.15 3.5.5v11.5z"/>
            </svg>
            Indie Comics Livestream & Podcast
        </span>
    </div>

    <div class="player-container">
        <div class="player-wrapper">
            <?php if ($use_embed && $is_live) : ?>
                <!-- Embed Player (YouTube/Twitch fallback) -->
                <iframe
                    src="<?php echo esc_url($stream_embed_url); ?>"
                    frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                    allowfullscreen>
                </iframe>
            <?php else : ?>
                <!-- Video.js HLS Player -->
                <video
                    id="warcampaign-player"
                    class="video-js vjs-big-play-centered vjs-theme-warcampaign"
                    controls
                    preload="auto"
                    data-setup='{"fluid": true}'
                    poster="<?php echo esc_url(get_template_directory_uri() . '/assets/images/player-placeholder.png'); ?>"
                    <?php if ($use_hls && $is_live) : ?>
                    style="display: block;"
                    <?php else : ?>
                    style="display: none;"
                    <?php endif; ?>
                >
                    <?php if ($use_hls && $is_live) : ?>
                    <source src="<?php echo esc_url($stream_hls_url); ?>" type="application/x-mpegURL">
                    <?php endif; ?>
                    <p class="vjs-no-js">
                        To view this video please enable JavaScript, or consider upgrading to a
                        web browser that <a href="https://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
                    </p>
                </video>

                <!-- Placeholder Overlay (shown when offline or no stream configured) -->
                <div id="player-placeholder" class="player-placeholder" <?php if ($use_hls && $is_live) echo 'style="display: none;"'; ?>>
                    <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/player-placeholder.png'); ?>"
                         alt="Warcampaign Live Stream">
                    <div class="play-overlay">
                        <button id="play-stream-btn" class="play-button" aria-label="<?php echo $is_live ? 'Watch Live Stream' : 'Stream Offline'; ?>">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                                <path d="M8 5v14l11-7z"/>
                            </svg>
                        </button>
                        <span class="watch-now-text">
                            <?php if ($is_live) : ?>
                                <span class="live-badge-text">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="#ff4444" style="vertical-align: middle; margin-right: 5px; animation: pulse 1.5s infinite;">
                                        <circle cx="12" cy="12" r="6"/>
                                    </svg>
                                    Watch Now
                                </span>
                            <?php else : ?>
                                Stream Offline
                            <?php endif; ?>
                        </span>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="stream-info">
        <div class="info-card">
            <div class="info-card-label">Stream Status</div>
            <div class="info-card-value stream-status-value">
                <?php if ($is_live) : ?>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#ff4444" style="vertical-align: middle; margin-right: 5px;">
                        <circle cx="12" cy="12" r="8"/>
                    </svg>
                    LIVE
                <?php else : ?>
                    Offline
                <?php endif; ?>
            </div>
        </div>

        <div class="info-card">
            <div class="info-card-label">Last Streamed</div>
            <div class="info-card-value"><?php echo esc_html($last_streamed); ?></div>
        </div>

        <div class="info-card">
            <div class="info-card-label">Channel</div>
            <div class="info-card-value">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="vertical-align: middle; margin-right: 5px;">
                    <path d="M21 6h-7.59l3.29-3.29L16 2l-4 4-4-4-.71.71L10.59 6H3c-1.1 0-2 .89-2 2v12c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V8c0-1.11-.9-2-2-2zm0 14H3V8h18v12zM9 10v8l7-4z"/>
                </svg>
                Warcampaign TV
            </div>
        </div>
    </div>
</section>

<section class="about-section">
    <h2>About Warcampaign Live</h2>
    <p>
        Welcome to <strong>Warcampaign Live</strong> - the ultimate destination for indie comics enthusiasts!
        Join us for live discussions, creator interviews, comic reviews, and deep dives into the world of
        independent comics and graphic novels.
    </p>
    <p>
        Our livestreams bring together passionate fans and talented creators from the indie comics community.
        Don't miss out on the action - streams are live only and not archived!
    </p>

    <div class="about-features">
        <div class="feature-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M21 5c-1.11-.35-2.33-.5-3.5-.5-1.95 0-4.05.4-5.5 1.5-1.45-1.1-3.55-1.5-5.5-1.5S2.45 4.9 1 6v14.65c0 .25.25.5.5.5.1 0 .15-.05.25-.05C3.1 20.45 5.05 20 6.5 20c1.95 0 4.05.4 5.5 1.5 1.35-.85 3.8-1.5 5.5-1.5 1.65 0 3.35.3 4.75 1.05.1.05.15.05.25.05.25 0 .5-.25.5-.5V6c-.6-.45-1.25-.75-2-1zm0 13.5c-1.1-.35-2.3-.5-3.5-.5-1.7 0-4.15.65-5.5 1.5V8c1.35-.85 3.8-1.5 5.5-1.5 1.2 0 2.4.15 3.5.5v11.5z"/>
            </svg>
            Indie Comics
        </div>
        <div class="feature-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M12 14c1.66 0 2.99-1.34 2.99-3L15 5c0-1.66-1.34-3-3-3S9 3.34 9 5v6c0 1.66 1.34 3 3 3zm5.3-3c0 3-2.54 5.1-5.3 5.1S6.7 14 6.7 11H5c0 3.41 2.72 6.23 6 6.72V21h2v-3.28c3.28-.48 6-3.3 6-6.72h-1.7z"/>
            </svg>
            Live Podcast
        </div>
        <div class="feature-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"/>
            </svg>
            Creator Interviews
        </div>
        <div class="feature-item">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M21 6h-7.59l3.29-3.29L16 2l-4 4-4-4-.71.71L10.59 6H3c-1.1 0-2 .89-2 2v12c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V8c0-1.11-.9-2-2-2zm0 14H3V8h18v12zM9 10v8l7-4z"/>
            </svg>
            Live Only Streams
        </div>
    </div>
</section>

<?php get_footer(); ?>
