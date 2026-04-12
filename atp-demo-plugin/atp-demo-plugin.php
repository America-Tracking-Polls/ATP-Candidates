<?php
/**
 * Plugin Name: ATP Demo Shortcodes
 * Plugin URI:  https://americatrackingpolls.com
 * Description: America Tracking Polls — shortcode library for demo pages, brand guide, homepage sections, logos, and more. Includes a built-in shortcode manager for easy copy, edit, and paste.
 * Version:     2.0.1
 * Author:      Mirror Factory / ROI Amplified
 * Text Domain: atp-demo
 */
if ( ! defined( 'ABSPATH' ) ) exit;

define( 'ATP_DEMO_VERSION', '2.0.1' );
define( 'ATP_DEMO_DIR', plugin_dir_path( __FILE__ ) );
define( 'ATP_DEMO_URL', plugin_dir_url( __FILE__ ) );

require_once ATP_DEMO_DIR . 'includes/registry.php';
require_once ATP_DEMO_DIR . 'includes/shortcodes.php';
require_once ATP_DEMO_DIR . 'includes/admin.php';
require_once ATP_DEMO_DIR . 'includes/updater.php';
require_once ATP_DEMO_DIR . 'includes/importer.php';
require_once ATP_DEMO_DIR . 'includes/setup-wizard.php';
require_once ATP_DEMO_DIR . 'includes/changelog.php';

// Intake form plugin — load only if not already active as standalone
if ( ! function_exists( 'atp_default_questions' ) ) {
    require_once ATP_DEMO_DIR . 'includes/intake/atp-candidate-intake.php';
}
