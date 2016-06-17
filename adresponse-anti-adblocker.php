<?php
/*
Plugin Name: AdResponse - Anti-Ad Blocker
Plugin URI: http://wpdevelopers.com
Description: Notifications for users employing ad blocker.
Version: 1.2.2
Author: Tyler Johnson and Ted Slater
Author URI: http://wpdevelopers.com
Copyright: WP Developers
Text Domain: adresponse
*/

/*--------------------
Check for Plugin Updates & Update
--------------------*/

require 'plugin-update-checker-3.0/plugin-update-checker.php';
$wpdevClassName = PucFactory::getLatestClassVersion('PucGitHubChecker');
$wpdevUpdateChecker = new $wpdevClassName(
    'https://github.com/LibertyAllianceGit/adresponse',
    __FILE__,
    'master'
);
$wpdevUpdateChecker->setAccessToken('4921ce230f2bd252dd1fafc7afeac812ddf091de');

/*--------------------
Enqueue Plugin CSS & JS
--------------------*/

function adresponse_adblocker_files() {
        wp_enqueue_style( 'adresponse-admin-css', plugin_dir_url(__FILE__) . 'inc/adresponse-admin.css' );
        wp_enqueue_script( 'adresponse-admin-js', plugin_dir_url(__FILE__) . 'inc/adresponse-admin.js', array('jquery') );
}
add_action('admin_enqueue_scripts', 'adresponse_adblocker_files', 20);

/*--------------------
Add Options Page
--------------------*/

class AdResponseAntiAdBlocker {
	private $adresponse_anti_ad_blocker_options;

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'adresponse_anti_ad_blocker_add_plugin_page' ) );
		add_action( 'admin_init', array( $this, 'adresponse_anti_ad_blocker_page_init' ) );
	}

	public function adresponse_anti_ad_blocker_add_plugin_page() {
		add_options_page(
			'AdResponse - Anti-Ad Blocker', // page_title
			'AdResponse - Anti-Ad Blocker', // menu_title
			'manage_options', // capability
			'adresponse-anti-ad-blocker', // menu_slug
			array( $this, 'adresponse_anti_ad_blocker_create_admin_page' ) // function
		);
	}

	public function adresponse_anti_ad_blocker_create_admin_page() {
		$this->adresponse_anti_ad_blocker_options = get_option( 'adresponse_anti_ad_blocker_option_name' ); ?>

    <div class="wrap adresponse-wrap">
        <h2 class="adresponse-head"><img src="<?php echo plugin_dir_url(__FILE__) . 'inc/adresponse-logo.png'; ?>" alt="AdResponse Anti-Ad Blocker" /></h2>
        <p></p>

        <form method="post" action="options.php">
            <?php
					settings_fields( 'adresponse_anti_ad_blocker_option_group' );
					do_settings_sections( 'adresponse-anti-ad-blocker-admin' );
					submit_button();
				?>
        </form>
    </div>
    <?php }

	public function adresponse_anti_ad_blocker_page_init() {
		register_setting(
			'adresponse_anti_ad_blocker_option_group', // option_group
			'adresponse_anti_ad_blocker_option_name', // option_name
			array( $this, 'adresponse_anti_ad_blocker_sanitize' ) // sanitize_callback
		);
		add_settings_section(
			'adresponse_anti_ad_blocker_setting_section', // id
			'Settings', // title
			array( $this, 'adresponse_anti_ad_blocker_section_info' ), // callback
			'adresponse-anti-ad-blocker-admin' // page
		);
		add_settings_field(
			'enable_ad_detection_0', // id
			'Enable Ad Detection<span class="sub-info">Enable ad detection across the entire site.</span>', // title
			array( $this, 'enable_ad_detection_0_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
		add_settings_field(
			'message_background_color_1', // id
			'Message Background Color<span class="sub-info">Set the background color for the messages enabled. Click on the input box to select a color.</span>', // title
			array( $this, 'message_background_color_1_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
		add_settings_field(
			'message_text_color_2', // id
			'Message Text Color<span class="sub-info">Set the text color for the messages enabled. Click on the input box to select a color.</span>', // title
			array( $this, 'message_text_color_2_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
		add_settings_field(
			'enable_site_overlay_3', // id
			'Enable Site Overlay<span class="sub-info">Enables an opaque overlay, using your selected background color, that covers the entire page and displays an anti-ad blocker message.</span>', // title
			array( $this, 'enable_site_overlay_3_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);

		add_settings_field(
			'site_overlay_message_4', // id
			'Site Overlay Message<span class="sub-info">Set your own custom message for the overlay, instead of using the default message.</span>', // title
			array( $this, 'site_overlay_message_4_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
		add_settings_field(
			'enable_header_bar_5', // id
			'Enable Header Bar<span class="sub-info">Enable a fixed header bar that will display an anti-ad blocker message.</span>', // title
			array( $this, 'enable_header_bar_5_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
		add_settings_field(
			'header_bar_message_6', // id
			'Header Bar Message<span class="sub-info">Set your own custom message for the header bar, instead of using the default message.</span>', // title
			array( $this, 'header_bar_message_6_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
        add_settings_field(
			'enable_sidebar_message_5', // id
			'Enable Sidebar Message<span class="sub-info">Enable a box above your widget area that will display an anti-ad blocker message.</span>', // title
			array( $this, 'enable_sidebar_message_5_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
        add_settings_field(
			'sidebar_container_class_or_id_7', // id
			'Sidebar Container Class or ID<span class="sub-info">Set the container class or ID for your sidebar, in order for the anti-ad blocker message to display.</span>', // title
			array( $this, 'sidebar_container_class_or_id_7_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
		add_settings_field(
			'sidebar_message_6', // id
			'Sidebar Message<span class="sub-info">Set your own custom message for the sidebar, instead of using the default message.</span>', // title
			array( $this, 'sidebar_message_6_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
		add_settings_field(
			'enable_above_below_post_message_7', // id
			'Enable Above & Below Post Message<span class="sub-info">Enable a box above and below the post content that will display an anti-ad blocker message.</span>', // title
			array( $this, 'enable_above_below_post_message_7_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
		add_settings_field(
			'post_content_container_class_or_id_8', // id
			'Post Content Container Class or ID<span class="sub-info">Set the container class or ID for your content area, in order for the anti-ad blocker message to display.</span>', // title
			array( $this, 'post_content_container_class_or_id_8_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
		add_settings_field(
			'above_below_post_message_9', // id
			'Above & Below Post Message<span class="sub-info">Set a custom message for the above and below post boxes, instead of using the default message.</span>', // title
			array( $this, 'above_below_post_message_9_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
		add_settings_field(
			'enable_comment_message_10', // id
			'Enable Comment Message<span class="sub-info">Enable a message above the comments section, which will display an anti-ad blocker message.</span>', // title
			array( $this, 'enable_comment_message_10_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
        add_settings_field(
			'comment_message_cont_10', // id
			'Comment Message Container ID or Class<span class="sub-info">Set the container class or ID for the comment seciton, in order for the anti-ad blocker message to display.</span>', // title
			array( $this, 'comment_message_cont_10_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
		add_settings_field(
			'comment_message_11', // id
			'Comment Message<span class="sub-info">Set a custom message for the comment box, instead of using the default message.</span>', // title
			array( $this, 'comment_message_11_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
		add_settings_field(
			'enable_hide_comments_12', // id
			'Enable Hide Comments<span class="sub-info">Remove the comment box completely and only display the anti-ad blocker message.</span>', // title
			array( $this, 'enable_hide_comments_12_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
		add_settings_field(
			'enable_footer_bar_14', // id
			'Enable Footer Bar<span class="sub-info">Enable a fixed, floating footer bar, which will display an anti-ad blocker message.</span>', // title
			array( $this, 'enable_footer_bar_14_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
		add_settings_field(
			'footer_bar_message_15', // id
			'Footer Bar Message<span class="sub-info">Set a custom message for the footer bar, instead of using the default message.</span>', // title
			array( $this, 'footer_bar_message_15_callback' ), // callback
			'adresponse-anti-ad-blocker-admin', // page
			'adresponse_anti_ad_blocker_setting_section' // section
		);
	}

	public function adresponse_anti_ad_blocker_sanitize($input) {
		$sanitary_values = array();
		if ( isset( $input['enable_ad_detection_0'] ) ) {
			$sanitary_values['enable_ad_detection_0'] = $input['enable_ad_detection_0'];
		}
		if ( isset( $input['message_background_color_1'] ) ) {
			$sanitary_values['message_background_color_1'] = sanitize_text_field( $input['message_background_color_1'] );
		}
		if ( isset( $input['message_text_color_2'] ) ) {
			$sanitary_values['message_text_color_2'] = sanitize_text_field( $input['message_text_color_2'] );
		}
		if ( isset( $input['enable_site_overlay_3'] ) ) {
			$sanitary_values['enable_site_overlay_3'] = $input['enable_site_overlay_3'];
		}
		if ( isset( $input['site_overlay_message_4'] ) ) {
			$sanitary_values['site_overlay_message_4'] = esc_textarea( $input['site_overlay_message_4'] );
		}
		if ( isset( $input['enable_header_bar_5'] ) ) {
			$sanitary_values['enable_header_bar_5'] = $input['enable_header_bar_5'];
		}
		if ( isset( $input['header_bar_message_6'] ) ) {
			$sanitary_values['header_bar_message_6'] = esc_textarea( $input['header_bar_message_6'] );
		}
        if ( isset( $input['enable_sidebar_message_5'] ) ) {
			$sanitary_values['enable_sidebar_message_5'] = $input['enable_sidebar_message_5'];
		}
        if ( isset( $input['sidebar_container_class_or_id_7'] ) ) {
			$sanitary_values['sidebar_container_class_or_id_7'] = sanitize_text_field( $input['sidebar_container_class_or_id_7'] );
		}
		if ( isset( $input['sidebar_message_6'] ) ) {
			$sanitary_values['sidebar_message_6'] = esc_textarea( $input['sidebar_message_6'] );
		}
		if ( isset( $input['enable_above_below_post_message_7'] ) ) {
			$sanitary_values['enable_above_below_post_message_7'] = $input['enable_above_below_post_message_7'];
		}
		if ( isset( $input['post_content_container_class_or_id_8'] ) ) {
			$sanitary_values['post_content_container_class_or_id_8'] = sanitize_text_field( $input['post_content_container_class_or_id_8'] );
		}
		if ( isset( $input['above_below_post_message_9'] ) ) {
			$sanitary_values['above_below_post_message_9'] = esc_textarea( $input['above_below_post_message_9'] );
		}
		if ( isset( $input['enable_comment_message_10'] ) ) {
			$sanitary_values['enable_comment_message_10'] = $input['enable_comment_message_10'];
		}
        if ( isset( $input['comment_message_cont_10'] ) ) {
			$sanitary_values['comment_message_cont_10'] = $input['comment_message_cont_10'];
		}
		if ( isset( $input['comment_message_11'] ) ) {
			$sanitary_values['comment_message_11'] = esc_textarea( $input['comment_message_11'] );
		}
		if ( isset( $input['enable_hide_comments_12'] ) ) {
			$sanitary_values['enable_hide_comments_12'] = $input['enable_hide_comments_12'];
		}
		if ( isset( $input['enable_footer_bar_14'] ) ) {
			$sanitary_values['enable_footer_bar_14'] = $input['enable_footer_bar_14'];
		}
		if ( isset( $input['footer_bar_message_15'] ) ) {
			$sanitary_values['footer_bar_message_15'] = esc_textarea( $input['footer_bar_message_15'] );
		}
		return $sanitary_values;
	}

	public function adresponse_anti_ad_blocker_section_info() {

	}
	public function enable_ad_detection_0_callback() {
		printf(
			'<input type="checkbox" name="adresponse_anti_ad_blocker_option_name[enable_ad_detection_0]" id="enable_ad_detection_0" class="ios" value="enable_ad_detection_0" %s>',
			( isset( $this->adresponse_anti_ad_blocker_options['enable_ad_detection_0'] ) && $this->adresponse_anti_ad_blocker_options['enable_ad_detection_0'] === 'enable_ad_detection_0' ) ? 'checked' : ''
		);
	}

	public function message_background_color_1_callback() {
		printf(
			'<input class="regular-text jscolor" type="text" name="adresponse_anti_ad_blocker_option_name[message_background_color_1]" id="message_background_color_1" value="%s">',
			isset( $this->adresponse_anti_ad_blocker_options['message_background_color_1'] ) ? esc_attr( $this->adresponse_anti_ad_blocker_options['message_background_color_1']) : ''
		);
	}

	public function message_text_color_2_callback() {
		printf(
			'<input class="regular-text jscolor" type="text" name="adresponse_anti_ad_blocker_option_name[message_text_color_2]" id="message_text_color_2" value="%s">',
			isset( $this->adresponse_anti_ad_blocker_options['message_text_color_2'] ) ? esc_attr( $this->adresponse_anti_ad_blocker_options['message_text_color_2']) : ''
		);
	}

	public function enable_site_overlay_3_callback() {
		printf(
			'<input type="checkbox" name="adresponse_anti_ad_blocker_option_name[enable_site_overlay_3]" id="enable_site_overlay_3" class="ios" value="enable_site_overlay_3" %s>',
			( isset( $this->adresponse_anti_ad_blocker_options['enable_site_overlay_3'] ) && $this->adresponse_anti_ad_blocker_options['enable_site_overlay_3'] === 'enable_site_overlay_3' ) ? 'checked' : ''
		);
	}
	public function site_overlay_message_4_callback() {
		printf(
			'<textarea class="large-text" rows="5" name="adresponse_anti_ad_blocker_option_name[site_overlay_message_4]" id="site_overlay_message_4">%s</textarea>',
			isset( $this->adresponse_anti_ad_blocker_options['site_overlay_message_4'] ) ? esc_attr( $this->adresponse_anti_ad_blocker_options['site_overlay_message_4']) : ''
		);
	}

	public function enable_header_bar_5_callback() {
		printf(
			'<input type="checkbox" name="adresponse_anti_ad_blocker_option_name[enable_header_bar_5]" id="enable_header_bar_5" class="ios" value="enable_header_bar_5" %s>',
			( isset( $this->adresponse_anti_ad_blocker_options['enable_header_bar_5'] ) && $this->adresponse_anti_ad_blocker_options['enable_header_bar_5'] === 'enable_header_bar_5' ) ? 'checked' : ''
		);
	}

	public function header_bar_message_6_callback() {
		printf(
			'<textarea class="large-text" rows="5" name="adresponse_anti_ad_blocker_option_name[header_bar_message_6]" id="header_bar_message_6">%s</textarea>',
			isset( $this->adresponse_anti_ad_blocker_options['header_bar_message_6'] ) ? esc_attr( $this->adresponse_anti_ad_blocker_options['header_bar_message_6']) : ''
		);
	}

    public function enable_sidebar_message_5_callback() {
		printf(
			'<input type="checkbox" name="adresponse_anti_ad_blocker_option_name[enable_sidebar_message_5]" id="enable_sidebar_message_5" class="ios" value="enable_sidebar_message_5" %s>',
			( isset( $this->adresponse_anti_ad_blocker_options['enable_sidebar_message_5'] ) && $this->adresponse_anti_ad_blocker_options['enable_sidebar_message_5'] === 'enable_sidebar_message_5' ) ? 'checked' : ''
		);
	}

    public function sidebar_container_class_or_id_7_callback() {
		printf(
			'<input class="regular-text" type="text" name="adresponse_anti_ad_blocker_option_name[sidebar_container_class_or_id_7]" id="sidebar_container_class_or_id_7" value="%s">',
			isset( $this->adresponse_anti_ad_blocker_options['sidebar_container_class_or_id_7'] ) ? esc_attr( $this->adresponse_anti_ad_blocker_options['sidebar_container_class_or_id_7']) : ''
		);
	}

	public function sidebar_message_6_callback() {
		printf(
			'<textarea class="large-text" rows="5" name="adresponse_anti_ad_blocker_option_name[sidebar_message_6]" id="sidebar_message_6">%s</textarea>',
			isset( $this->adresponse_anti_ad_blocker_options['sidebar_message_6'] ) ? esc_attr( $this->adresponse_anti_ad_blocker_options['sidebar_message_6']) : ''
		);
	}

	public function enable_above_below_post_message_7_callback() {
		printf(
			'<input type="checkbox" name="adresponse_anti_ad_blocker_option_name[enable_above_below_post_message_7]" class="ios" id="enable_above_below_post_message_7" value="enable_above_below_post_message_7" %s>',
			( isset( $this->adresponse_anti_ad_blocker_options['enable_above_below_post_message_7'] ) && $this->adresponse_anti_ad_blocker_options['enable_above_below_post_message_7'] === 'enable_above_below_post_message_7' ) ? 'checked' : ''
		);
	}

	public function post_content_container_class_or_id_8_callback() {
		printf(
			'<input class="regular-text" type="text" name="adresponse_anti_ad_blocker_option_name[post_content_container_class_or_id_8]" id="post_content_container_class_or_id_8" value="%s">',
			isset( $this->adresponse_anti_ad_blocker_options['post_content_container_class_or_id_8'] ) ? esc_attr( $this->adresponse_anti_ad_blocker_options['post_content_container_class_or_id_8']) : ''
		);
	}

	public function above_below_post_message_9_callback() {
		printf(
			'<textarea class="large-text" rows="5" name="adresponse_anti_ad_blocker_option_name[above_below_post_message_9]" id="above_below_post_message_9">%s</textarea>',
			isset( $this->adresponse_anti_ad_blocker_options['above_below_post_message_9'] ) ? esc_attr( $this->adresponse_anti_ad_blocker_options['above_below_post_message_9']) : ''
		);
	}

	public function enable_comment_message_10_callback() {
		printf(
			'<input type="checkbox" name="adresponse_anti_ad_blocker_option_name[enable_comment_message_10]" class="ios" id="enable_comment_message_10" value="enable_comment_message_10" %s>',
			( isset( $this->adresponse_anti_ad_blocker_options['enable_comment_message_10'] ) && $this->adresponse_anti_ad_blocker_options['enable_comment_message_10'] === 'enable_comment_message_10' ) ? 'checked' : ''
		);
	}

    public function comment_message_cont_10_callback() {
		printf(
			'<input class="regular-text" type="text" name="adresponse_anti_ad_blocker_option_name[comment_message_cont_10]" id="comment_message_cont_10" value="%s">',
			isset( $this->adresponse_anti_ad_blocker_options['comment_message_cont_10'] ) ? esc_attr( $this->adresponse_anti_ad_blocker_options['comment_message_cont_10']) : ''
		);
	}

	public function comment_message_11_callback() {
		printf(
			'<textarea class="large-text" rows="5" name="adresponse_anti_ad_blocker_option_name[comment_message_11]" id="comment_message_11">%s</textarea>',
			isset( $this->adresponse_anti_ad_blocker_options['comment_message_11'] ) ? esc_attr( $this->adresponse_anti_ad_blocker_options['comment_message_11']) : ''
		);
	}
	public function enable_hide_comments_12_callback() {
		printf(
			'<input type="checkbox" name="adresponse_anti_ad_blocker_option_name[enable_hide_comments_12]" id="enable_hide_comments_12" class="ios" value="enable_hide_comments_12" %s>',
			( isset( $this->adresponse_anti_ad_blocker_options['enable_hide_comments_12'] ) && $this->adresponse_anti_ad_blocker_options['enable_hide_comments_12'] === 'enable_hide_comments_12' ) ? 'checked' : ''
		);
	}
	public function enable_footer_bar_14_callback() {
		printf(
			'<input type="checkbox" name="adresponse_anti_ad_blocker_option_name[enable_footer_bar_14]" id="enable_footer_bar_14" class="ios" value="enable_footer_bar_14" %s>',
			( isset( $this->adresponse_anti_ad_blocker_options['enable_footer_bar_14'] ) && $this->adresponse_anti_ad_blocker_options['enable_footer_bar_14'] === 'enable_footer_bar_14' ) ? 'checked' : ''
		);
	}
	public function footer_bar_message_15_callback() {
		printf(
			'<textarea class="large-text" rows="5" name="adresponse_anti_ad_blocker_option_name[footer_bar_message_15]" id="footer_bar_message_15">%s</textarea>',
			isset( $this->adresponse_anti_ad_blocker_options['footer_bar_message_15'] ) ? esc_attr( $this->adresponse_anti_ad_blocker_options['footer_bar_message_15']) : ''
		);
	}
}
if ( is_admin() )
	$adresponse_anti_ad_blocker = new AdResponseAntiAdBlocker();

/*--------------------
Setup Options
--------------------*/

$adblock = get_option( 'adresponse_anti_ad_blocker_option_name' );
$enablead = $adblock['enable_ad_detection_0']; // Enable Ad Detection
$messagebg = $adblock['message_background_color_1']; // Message Background Color
$messagecolor = $adblock['message_text_color_2']; // Message Text Color
$enableoverlay = $adblock['enable_site_overlay_3']; // Enable Site Overlay
$siteoverlaymess = $adblock['site_overlay_message_4']; // Site Overlay Message
$enableheader = $adblock['enable_header_bar_5']; // Enable Header Bar
$headermess = $adblock['header_bar_message_6']; // Header Bar Message
$enablesidebar = $adblock['enable_sidebar_message_5']; // Enable Sidebar Message
$sidebarmesscont = $adblock['sidebar_container_class_or_id_7']; // Sidebar Container Class or ID
$sidebarmess = $adblock['sidebar_message_6']; // Sidebar Message
$enablepostmess = $adblock['enable_above_below_post_message_7']; // Enable Above & Below Post Message
$postmesscont = $adblock['post_content_container_class_or_id_8']; // Post Content Container Class or ID
$postmess = $adblock['above_below_post_message_9']; // Above & Below Post Message
$enablecommentmess = $adblock['enable_comment_message_10']; // Enable Comment Message
$commentmesscont = $adblock['comment_message_cont_10']; // Comment Message Container Class or ID
$commentmess = $adblock['comment_message_11']; // Comment Message
$enablecommenthide = $adblock['enable_hide_comments_12']; // Enable Hide Comments
$enablefooter = $adblock['enable_footer_bar_14']; // Enable Footer Bar
$footermess = $adblock['footer_bar_message_15']; // Footer Bar Message

// Add Detection
function adresponse_detection_antiadblock_head() {
    // Grab Options
    global $enablead;
    global $messagebg;
    global $messagecolor;
    global $enableoverlay;
    global $siteoverlaymess;
    global $enableheader;
    global $headermess;

    // Turn Background Hex into RGBA
    $hex = str_replace("#", "", $messagebg);

    if(strlen($hex) == 3) {
       $r = hexdec(substr($hex,0,1).substr($hex,0,1));
       $g = hexdec(substr($hex,1,1).substr($hex,1,1));
       $b = hexdec(substr($hex,2,1).substr($hex,2,1));
    } else {
       $r = hexdec(substr($hex,0,2));
       $g = hexdec(substr($hex,2,2));
       $b = hexdec(substr($hex,4,2));
    }
    $rgb = array($r, $g, $b);
    $bgcolor = 'rgba(' . $rgb[0] . ',' . $rgb[1] . ',' . $rgb[2] . ',0.95)';

    // Enable Ad Detection
    if(!empty($enablead)) { ?>

        <script type="text/javascript">
            // Create Random Numbers for DIV
            window.c4vl39 = {
                sitead: '' + Math.random().toString(36),
                siteover: '' + Math.random().toString(36),
                siteoverin: '' + Math.random().toString(36),
                sitebanner: '' + Math.random().toString(36),
                siteside: '' + Math.random().toString(36),
                sitepostt: '' + Math.random().toString(36),
                sitepostb: '' + Math.random().toString(36),
                sitecomment: '' + Math.random().toString(36),
                sitefooter: '' + Math.random().toString(36)
            }

            // Create Detection Ad
            document.write('<div style="width: 0px; height: 0px; overflow: hidden;"><div id="' + c4vl39.sitead + '" class="BannerAd AdArea AdContainer AdDiv AdDiv AdPanel AdServer AdsDiv AdsFrame ad-banner" style="height: 1px; width: 1px;"></div></div>\n');

            // Enable Site Overlay
            <?php if(!empty($enableoverlay)) {
                // Setup Message
                if(!empty($siteoverlaymess)) {
                    $overlaymessage = str_replace("'", "\'", $siteoverlaymess);
                } else {
                    $overlaymessage = 'Please disable your <strong>Ad Blocker</strong> in order to interact with the site. The ads on this site support us and our families.';
                } ?>

                // Create Overlay
                document.write('<div id="' + c4vl39.siteover + '" style="display: none; background: <?php echo $bgcolor; ?>;width: 100%;height: 100%;position: fixed;z-index: 99999;text-align: center;"><div id="' + c4vl39.siteoverin + '" style="color: #<?php echo $messagecolor; ?>;padding: 0 20%;top: 40%;position: relative;font-size: 1.6rem;"><?php echo $overlaymessage; ?></div></div>\n');
            <?php } ?>

            // Enable Header Bar
            <?php if(!empty($enableheader)) {
                // Setup Message
                if(!empty($headermess)) {
                    $headmess = str_replace("'", "\'", $headermess);
                } else {
                    $headmess = 'Enjoying the site? Please disable your <strong>Ad Blocker</strong>. The ads on this site support us and our families.';
                } ?>

                // Write Top Bar Container DIV
                document.write('<div id="' + c4vl39.sitebanner + '" style="display: none; background-color:#<?php echo $messagebg; ?>; color: #<?php echo $messagecolor; ?>; padding: 5px 0 0; text-align: center; font-size: 18px; position: fixed; top: 0px; width: 100%; z-index: 99999999;-webkit-box-shadow: 0px 0px 1px 0px #fff,0px 0px 15px 0px rgba(0, 0, 0, 0.6);-moz-box-shadow: 0px 0px 1px 0px #fff,0px 0px 15px 0px rgba(0, 0, 0, 0.6);box-shadow: 0px 0px 1px 0px #fff,0px 0px 15px 0px rgba(0, 0, 0, 0.6);"><p style="display: inline-block; margin: 0;"><?php echo $headmess; ?></p></div>\n');
            <?php } ?>

        </script>

        <?php }
}
add_action('wp_head', 'adresponse_detection_antiadblock_head', 100);

function adresponse_detection_antiadblock_footer() {
    // Grab Options
    global $enablead;
    global $messagebg;
    global $messagecolor;
    global $enableoverlay;
    global $siteoverlaymess;
    global $enableheader;
    global $headermess;
    global $enablesidebar;
    global $sidebarmesscont;
    global $sidebarmess;
    global $enablepostmess;
    global $postmesscont;
    global $postmess;
    global $enablecommentmess;
    global $commentmesscont;
    global $commentmess;
    global $enablecommenthide;
    global $enablefooter;
    global $footermess;

    // Enable Ad Detection
    if(!empty($enablead)) { ?>

        <script type="text/javascript">

        // Enable Sidebar Message
        <?php if(!empty($enablesidebar) && !empty($sidebarmesscont)) {
            if(!empty($sidebarmess)) {
                $sidebarmessage = str_replace("'", "\'", $sidebarmess);
            } else {
                $sidebarmessage = '<h1><strong>Please disable Ad Blocker</strong></h1><p>Enjoying the site? Please disable your <strong>Ad Blocker</strong>. The ads on this site support us and our families.</p>';
            } ?>

            jQuery('<?php echo $sidebarmesscont; ?>').prepend('<div id="' + c4vl39.siteside + '" style="display: none;background:#<?php echo $messagebg; ?>;padding: 1rem;margin: 0 0 1rem 0;color: #<?php echo $messagecolor; ?>;text-align: center;width: 100%;"><?php echo $sidebarmessage; ?></div>');

        <?php } ?>

        // Enable Above & Below Post Message
        <?php if(!empty($enablepostmess) && !empty($postmesscont)) {
            if(!empty($postmess)) {
                $postmessage = str_replace("'", "\'", $postmess);
            } else {
                $postmessage = '<h1><strong>Please disable Ad Blocker</strong></h1><p>Enjoying the site? Please disable your <strong>Ad Blocker</strong>. The ads on this site support us and our families.</p>';
            } ?>

            jQuery('<?php echo $postmesscont; ?>').prepend('<div id="' + c4vl39.sitepostt + '" style="display: none;background:#<?php echo $messagebg; ?>;padding: 1rem;margin: 0 0 1rem 0;color: #<?php echo $messagecolor; ?>;text-align: center;width: 100%;"><?php echo $postmessage; ?></div>');
            jQuery('<?php echo $postmesscont; ?>').append('<div id="' + c4vl39.sitepostb + '" style="display: none;background:#<?php echo $messagebg; ?>;padding: 1rem;margin: 0 0 1rem 0;color: #<?php echo $messagecolor; ?>;text-align: center;width: 100%;"><?php echo $postmessage; ?></div>');
        <?php } ?>

        // Enable Comment Message
        <?php if(!empty($enablecommentmess) && !empty($commentmesscont)) {
            if(!empty($commentmess)) {
                $commentmessage = str_replace("'", "\'", $commentmess);
            } elseif (!empty($enablecommenthide)) {
                $commentmessage = '<h1><strong>COMMENTING BLOCKED</strong></h1><p>Commenting is blocked because of Ad Blocker. Please disable your <strong>Ad Blocker</strong>. The ads on this site support us and our families.';
            } else {
                $commentmessage = 'Enjoying the site? Please disable your <strong>Ad Blocker</strong>. The ads on this site support us and our families.';
            }

            if(!empty($enablecommenthide)) {
                $commentfunction = '.html';
            } else {
                $commentfunction = '.prepend';
            } ?>

            jQuery('<?php echo $commentmesscont; ?>')<?php echo $commentfunction; ?>('<div id="' + c4vl39.sitecomment + '" style="display: none;background:#<?php echo $messagebg; ?>;padding: 1rem;margin: 0 0 1rem 0;color: #<?php echo $messagecolor; ?>;text-align: center;width: 100%;"><?php echo $commentmessage; ?></div>');

        <?php } ?>

        // Enable Footer Bar
        <?php if(!empty($enablefooter)) {
            if(!empty($footermess)) {
                $footermessage = str_replace("'", "\'", $footermess);
            } else {
                $footermessage = 'Enjoying the site? Please disable your <strong>Ad Blocker</strong>. The ads on this site support us and our families.';
            } ?>

            jQuery('body').append('<div id="' + c4vl39.sitefooter + '" style="display: none; background-color:#<?php echo $messagebg; ?>; color: #<?php echo $messagecolor; ?>; padding: 5px 0 0; text-align: center; font-size: 18px; position: fixed; bottom: 0px; width: 100%; z-index: 99999999;-webkit-box-shadow: 0px 0px 1px 0px #fff,0px 0px 15px 0px rgba(0, 0, 0, 0.6);-moz-box-shadow: 0px 0px 1px 0px #fff,0px 0px 15px 0px rgba(0, 0, 0, 0.6);box-shadow: 0px 0px 1px 0px #fff,0px 0px 15px 0px rgba(0, 0, 0, 0.6);"><p style="display: inline-block; margin: 0;"><?php echo $footermessage; ?></p></div>');

        <?php } ?>

        // Set Interval to Check if Fake Ad DIV Exists
        setInterval(function () {
            try {
                if (document.getElementById(c4vl39.sitead).offsetHeight === 0) {
                // Display Site Overlay
                <?php if(!empty($enableoverlay)) { ?>
                    document.getElementById(c4vl39.siteover).style.display = 'block';
                <?php } ?>
                // Display Top Bar
                <?php if(!empty($enableheader)) { ?>
                    document.getElementById(c4vl39.sitebanner).style.display = 'block';
                <?php } ?>
                <?php if(!empty($enablesidebar) && !empty($sidebarmesscont)) { ?>
                    document.getElementById(c4vl39.siteside).style.display = 'block';
                <?php } ?>
                <?php if(!empty($enablepostmess) && !empty($postmesscont)) { ?>
                    document.getElementById(c4vl39.sitepostt).style.display = 'block';
                    document.getElementById(c4vl39.sitepostb).style.display = 'block';
                <?php } ?>
                <?php if(!empty($enablecommentmess) && !empty($commentmesscont)) { ?>
                    document.getElementById(c4vl39.sitecomment).style.display = 'block';
                <?php } ?>
                <?php if(!empty($enablefooter)) { ?>
                    document.getElementById(c4vl39.sitefooter).style.display = 'block';
                <?php } ?>
                }
            } catch (e) {}
        }, 1000);

        </script>

<?php }
}
add_action('wp_footer', 'adresponse_detection_antiadblock_footer', 100);
