</main>

<footer class="site-footer">
    <div class="footer-content">
        <a href="<?php echo esc_url(home_url('/')); ?>">
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/logo.jpg'); ?>"
                 alt="<?php bloginfo('name'); ?>"
                 class="footer-logo">
        </a>

        <p class="footer-tagline">War Campaign Live - Your Premier Indie Comics Livestream & Podcast</p>

        <p class="footer-notice">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="currentColor" style="vertical-align: middle; margin-right: 5px;">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
            </svg>
            This site does not store old streams. Each broadcast is live only!
        </p>

        <p class="copyright">
            &copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. All rights reserved.
        </p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
