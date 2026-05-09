<?php
/**
 * Plugin Name: ATP Campaign Site
 * Plugin URI:  https://americatrackingpolls.com
 * Description: America Tracking Polls — campaign website plugin with shortcode-driven pages, candidate intake form, white-label admin, and AI-powered page generation.
 * Version:     2.1.0
 * Author:      Mirror Factory / ROI Amplified
 * Text Domain: atp-demo
 * Requires Plugins: vibe-ai
 */
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'ATP_DEMO_VERSION', '2.1.0' );
define( 'ATP_DEMO_DIR', plugin_dir_path( __FILE__ ) );
define( 'ATP_DEMO_URL', plugin_dir_url( __FILE__ ) );

require_once ATP_DEMO_DIR . 'includes/registry.php';
require_once ATP_DEMO_DIR . 'includes/shortcodes.php';
require_once ATP_DEMO_DIR . 'includes/admin.php';
require_once ATP_DEMO_DIR . 'includes/updater.php';
require_once ATP_DEMO_DIR . 'includes/importer.php';
require_once ATP_DEMO_DIR . 'includes/setup-wizard.php';
require_once ATP_DEMO_DIR . 'includes/changelog.php';
require_once ATP_DEMO_DIR . 'includes/candidate-page.php';
require_once ATP_DEMO_DIR . 'includes/whitelabel.php';
require_once ATP_DEMO_DIR . 'includes/signup.php';

// Intake form plugin — load only if not already active as standalone
if ( ! function_exists( 'atp_default_questions' ) ) {
    require_once ATP_DEMO_DIR . 'includes/intake/atp-candidate-intake.php';
}
