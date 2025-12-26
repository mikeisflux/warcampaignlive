/**
 * Warcampaign Live - Video Player Initialization
 * Uses Video.js with HLS support for livestreaming
 */

(function() {
    'use strict';

    // Wait for DOM to be ready
    document.addEventListener('DOMContentLoaded', function() {
        initPlayer();
    });

    function initPlayer() {
        var videoElement = document.getElementById('warcampaign-player');
        var placeholderElement = document.getElementById('player-placeholder');
        var playButton = document.getElementById('play-stream-btn');

        // Check if we have a video element
        if (!videoElement) {
            console.log('Warcampaign: No video element found');
            return;
        }

        // Get stream settings from WordPress
        var streamSettings = window.warcampaignStream || {};
        var hlsUrl = streamSettings.hlsUrl || '';
        var isLive = streamSettings.isLive || false;
        var autoplay = streamSettings.autoplay || false;

        // Initialize Video.js player
        var player = videojs('warcampaign-player', {
            controls: true,
            autoplay: false,
            preload: 'auto',
            fluid: true,
            responsive: true,
            playbackRates: [0.5, 1, 1.5, 2],
            html5: {
                vhs: {
                    overrideNative: true,
                    enableLowInitialPlaylist: true,
                    smoothQualityChange: true,
                    fastQualityChange: true
                },
                nativeAudioTracks: false,
                nativeVideoTracks: false
            },
            liveui: true,
            liveTracker: {
                trackingThreshold: 0,
                liveTolerance: 15
            }
        });

        // Custom error handling
        player.on('error', function() {
            var error = player.error();
            console.error('Warcampaign Player Error:', error);

            // Show placeholder on error
            showPlaceholder();

            // Update status text
            updateStreamStatus('Stream Unavailable');
        });

        // Handle stream end
        player.on('ended', function() {
            console.log('Warcampaign: Stream ended');
            showPlaceholder();
            updateStreamStatus('Stream Ended');
        });

        // Handle successful play
        player.on('playing', function() {
            console.log('Warcampaign: Stream playing');
            hidePlaceholder();
            updateStreamStatus('LIVE');
        });

        // Handle waiting/buffering
        player.on('waiting', function() {
            console.log('Warcampaign: Buffering...');
        });

        // If we have an HLS URL and stream is live, load it
        if (hlsUrl && isLive) {
            loadStream(hlsUrl);

            if (autoplay) {
                // Autoplay with muted (required by browsers)
                player.muted(true);
                player.play().catch(function(error) {
                    console.log('Warcampaign: Autoplay prevented:', error);
                });
            }
        }

        // Play button click handler
        if (playButton) {
            playButton.addEventListener('click', function(e) {
                e.preventDefault();

                if (hlsUrl && isLive) {
                    loadStream(hlsUrl);
                    player.play().catch(function(error) {
                        console.log('Warcampaign: Play prevented:', error);
                    });
                } else {
                    // Show offline message
                    alert('Stream is currently offline. Please check back later!');
                }
            });
        }

        // Function to load HLS stream
        function loadStream(url) {
            console.log('Warcampaign: Loading stream:', url);

            player.src({
                src: url,
                type: 'application/x-mpegURL'
            });

            hidePlaceholder();
        }

        // Function to show placeholder
        function showPlaceholder() {
            if (placeholderElement) {
                placeholderElement.style.display = 'flex';
            }
            videoElement.style.display = 'none';
        }

        // Function to hide placeholder
        function hidePlaceholder() {
            if (placeholderElement) {
                placeholderElement.style.display = 'none';
            }
            videoElement.style.display = 'block';
        }

        // Function to update stream status display
        function updateStreamStatus(status) {
            var statusElement = document.querySelector('.stream-status-value');
            if (statusElement) {
                statusElement.textContent = status;
            }
        }

        // Expose player globally for debugging
        window.warcampaignPlayer = player;

        // Cleanup on page unload
        window.addEventListener('beforeunload', function() {
            if (player) {
                player.dispose();
            }
        });
    }

    // Utility: Check if HLS is supported
    function isHlsSupported() {
        var video = document.createElement('video');
        return video.canPlayType('application/vnd.apple.mpegurl') ||
               video.canPlayType('application/x-mpegURL') ||
               typeof window.MediaSource !== 'undefined';
    }

    // Log HLS support status
    console.log('Warcampaign: HLS Support:', isHlsSupported() ? 'Yes' : 'No (using Video.js VHS)');

})();
