<?php


class Wp_sonetel {

	public function __construct() {
		add_action( 'init', array( $this, 'get_accountid_url' ) );
		add_action( 'admin_menu', array( $this, 'add_menu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		add_action( 'plugins_loaded', array( $this, 'activation_redirect_admin' ) );
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );
		add_filter( 'plugin_action_links_sonetel-website-chat/wp-sonetel.php', array( $this, 'plugin_action_links' ) );
		add_action( 'load-plugins.php', array( $this, 'load_plugin_wp_sonetel' ) );

	}

	public function add_menu_page() {
		add_menu_page( 'Sonetel Settings', 'Sonetel', 'manage_options', 'wp_sonetel', array( $this, 'admin_page' ),
			'data:image/svg+xml;base64,' . $this->icon_admin() );
	}

	public function admin_page() {
		return require_once WP_SONETEL__PLUGIN_DIR . 'views/wp-sonetel-admin-display.php';
	}

	public function icon_admin() {
		$icon_data_in_base64 = base64_encode( file_get_contents( WP_SONETEL__PLUGIN_DIR . '_inc/icons/logo-s.svg' ) );

		return $icon_data_in_base64;
	}

	public function enqueue_styles() {
		wp_enqueue_style( 'wp_sonetel_style', plugin_dir_url( __FILE__ ) . '_inc/wp-sonetel-admin.css', array(),
			WP_SONETEL_VERSION, 'all' );
	}

	public function activation_redirect_admin() {
		if ( is_admin() && get_option( 'Activated_Plugin' ) == 'wp_sonetel' ) {
			delete_option( 'Activated_Plugin' );
			if ( ! get_option( 'wp_sonetel_accountId' ) ) {
				add_option( 'wp_sonetel_activated', 1 );

			}
			add_option( 'wp_sonetel_bar_activated', 1 );
			wp_safe_redirect( admin_url( 'admin.php?page=wp_sonetel' ) );
			exit;
		}
	}

	public static function get_accountid_url() {
		if ( isset( $_REQUEST['page'] ) && is_admin() ) {
			$parse_url = explode( '/', $_REQUEST['page'] );

			if ( count( $parse_url ) === 3 && preg_match( "/^\d/", $parse_url[2] ) ) {
				if ( $_REQUEST['page'] == 'wp_sonetel/accountID/' . $parse_url[2] ) {
					add_option( 'wp_sonetel_accountId', $parse_url[2] );
					add_option( 'wp_sonetel_window_close', true );
					wp_safe_redirect( admin_url( 'admin.php?page=wp_sonetel' ) );
					exit;
				}
			}
		}
	}

	public function wp_footer() {
		$account_id = get_option( 'wp_sonetel_accountId' );
		if ( ! empty( $account_id ) ) {
			printf( '<script async id="slcLiveChat" src="https://widget.sonetel.com/SonetelWidget.min.js" data-account-id="%s"></script>',
				$account_id );
		}
	}

	function plugin_action_links( $links ) {
		$links[] = sprintf( '<a href="%s">%s</a>', esc_url( admin_url( 'admin.php?page=wp_sonetel' ) ),
			__( 'Settings', 'sample-text-domain' ) );

		return $links;
	}

	function load_plugin_wp_sonetel() {
		add_action( 'admin_notices', array( $this, 'wp_sonetel_notices_activate' ) );
	}

	function wp_sonetel_notices_activate() {
		if ( ! get_option( 'wp_sonetel_accountId' ) ) {
			?>
            <div class="notice notice-info is-dismissible">
                <p>
					<?php echo file_get_contents( WP_SONETEL__PLUGIN_DIR . '_inc/icons/logo-s.svg' ); ?>
					<?php _e( 'Your chat is not active. Click here to activate.', 'sample-text-domain' ); ?></p>
            </div>
			<?php
		}
	}

	public static function activate_wp_sonetel() {
		add_option( 'Activated_Plugin', 'wp_sonetel' );

		return;
	}

	public static function deactivate_wp_sonetel() {
		delete_option( 'wp_sonetel_activated' );
		delete_option( 'wp_sonetel_bar_activated' );

		return;
	}
}
