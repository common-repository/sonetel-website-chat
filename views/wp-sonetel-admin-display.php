<?php

if ( ! current_user_can( 'manage_options' ) ) {
	return;
}

function windowSonetelLogin() {

	return sprintf( 'function openWindowLogin() {
        tb_show("Login window", "%s/?destinationURL=%sTB_iframe=true&width=400&height=600&modal=true");
        }', WP_SONETEL__URL_SONETEL_LOGIN, admin_url( 'admin.php?page=wp_sonetel/accountID' ) );
}

function windowLoginOnload() {
	add_thickbox();
	printf( '<script type="text/javascript"> window.onload = %s </script>', windowSonetelLogin() );
}

function windowLoginReload() {
	printf( '<script type="text/javascript"> window.parent.location.reload(); </script>' );
}

if ( ( get_option( 'wp_sonetel_activated' ) == '1' ) && ( ! get_option( 'wp_sonetel_accountId' ) ) ) {
	windowLoginOnload();
	delete_option( 'wp_sonetel_activated' );
}

if ( get_option( 'wp_sonetel_window_close' ) ) {
	printf( '<div class="wp_sonetel_modal">
            <div class="wp_sonetel_modal_content">
                <div class="wp_sonetel_spinner">%s</div>
            </div>
        </div>', file_get_contents( WP_SONETEL__PLUGIN_DIR . '_inc/icons/spinner.svg' ) );

	delete_option( 'wp_sonetel_window_close' );
	add_option( 'wp_sonetel_window_reload', 1 );
	windowLoginReload();
}

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	delete_option( 'wp_sonetel_accountId' );
}


if ( get_option( 'wp_sonetel_window_reload' ) == '1' ) {
	delete_option( 'wp_sonetel_window_reload' );
	add_option( 'wp_sonetel_bar_activated', 1 );
} else {
	?>

    <div class="wrap">
        <form method="post">

            <div class="wp_sonetel_admin_main">
                <div class="wp_sonetel_admin_box">
					<?php

					if ( get_option( 'wp_sonetel_bar_activated' ) == '1' ) {
						?>
                        <div class="wp_sonetel_admin_row">
                            <div class="wp_sonetel_admin_bar_success">
                                <div>
									<?php echo file_get_contents( WP_SONETEL__PLUGIN_DIR . '_inc/icons/confirm.svg' ); ?>
                                </div>

                                <span class="wp_sonetel_admin_bar_span"><?php _e( 'Sonetel is installed on your website.',
										'text_bar_activated' ); ?>
                                </span>
                            </div>
                        </div>
						<?php
						delete_option( 'wp_sonetel_bar_activated' );
					}
					?>

                    <div class="wp_sonetel_admin_logo">
						<?php echo file_get_contents( WP_SONETEL__PLUGIN_DIR . '_inc/icons/logo-l.svg' ); ?>
                    </div>

					<?php
					if ( get_option( 'wp_sonetel_accountId' ) ) {
						?>

                        <div class="wp_sonetel_admin_row">

                            <iframe src="https://player.vimeo.com/video/364036206" width="480" height="300"
                                    frameborder="0"
                                    allow="autoplay; fullscreen" allowfullscreen style="border-radius: 4px;"></iframe>
                        </div>

                        <div class="wp_sonetel_admin_row">
                            <input type="button" value="Manage Website chat"
                                   class="wp_sonetel_admin_button button_connect wp_sonetel_admin_button_ext"
                                   onclick="window.open(<?php printf( "'%s/account-settings/website-chat/settings'",
								       WP_SONETEL__URL_SONETEL_LOGIN ) ?>,'_blank')">
                        </div>

                        <div class="wp_sonetel_admin_row">
                            <input type="submit" value="Disconnect your Sonetel account" name="Disconnect"
                                   class="wp_sonetel_admin_button button_disconnect wp_sonetel_admin_button_ext">
                        </div>

                        <input type="hidden" value="Sign in " id="buttonWindow"/>

						<?php
					} else {
						add_thickbox();
						?>
                        <div class="wp_sonetel_admin_row">
                            <input type="button"
                                   value="Connect your Sonetel account" id="buttonWindow" onclick="openWindowLogin()"
                                   class="wp_sonetel_admin_button button_connect wp_sonetel_admin_button_ext"/>

                            <script type="text/javascript">
								<?php echo windowSonetelLogin();?>
                            </script>
                        </div>
						<?php
					}
					?>
                </div>
            </div>

        </form>
    </div>
	<?php
}
