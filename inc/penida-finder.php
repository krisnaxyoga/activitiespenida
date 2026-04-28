<?php
/**
 * Penida Finder — bootstrap loader.
 * Autoloaded by functions.php glob('/inc/*.php').
 *
 * When "Finder Mode" is active, the theme natively registers the Business CPT,
 * meta-boxes, admin tools, importer, and priority module — and auto-deactivates
 * the standalone "Nusa Penida Business Directory" plugin so they never collide.
 * Stored data (posts, meta, taxonomy terms) is preserved either way.
 */

if (!defined('ABSPATH')) exit;

define('NPF_VERSION', '1.0.0');
define('NPF_OPTION_KEY', 'npf_finder_active');
define('NPF_DIR', get_template_directory() . '/inc/penida-finder');
define('NPF_URL', get_template_directory_uri() . '/inc/penida-finder');
define('NPF_PHONE_BLACKLIST_DIGITS', '6281337567256');
define('NPF_PHONE_FALLBACK_URL', 'https://snorkelingpenida.com');

require_once NPF_DIR . '/helpers.php';
require_once NPF_DIR . '/settings.php';

if (npf_is_active()) {
    require_once NPF_DIR . '/cpt.php';
    require_once NPF_DIR . '/meta.php';
    require_once NPF_DIR . '/priority.php';
    require_once NPF_DIR . '/admin-columns.php';
    require_once NPF_DIR . '/importer.php';
}
