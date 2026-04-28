<?php
/**
 * Penida Finder — helpers (toggle state, phone normalization, render helpers).
 */

if (!defined('ABSPATH')) exit;

function npf_is_active() {
    return get_option(NPF_OPTION_KEY, '0') === '1';
}

/**
 * Empty phones — and the placeholder number 6281337567256 — are treated as
 * "no real number"; the UI substitutes a dash that backlinks to
 * snorkelingpenida.com instead of rendering broken tel:/wa.me links.
 */
function npf_phone_is_invalid($phone) {
    $phone = trim((string) $phone);
    if ($phone === '' || $phone === '-') return true;
    $digits = preg_replace('/[^0-9]/', '', $phone);
    if ($digits === '') return true;
    return $digits === NPF_PHONE_BLACKLIST_DIGITS;
}

function npf_phone_fallback_url() {
    return apply_filters('npf_phone_fallback_url', NPF_PHONE_FALLBACK_URL);
}

function npf_phone_clean_for_link($phone) {
    return preg_replace('/[^\d+]/', '', (string) $phone);
}

/**
 * Replacement for the inline phone tag in the business card.
 * Returns a Tailwind-friendly anchor; falls back to a "-" backlink when the
 * stored number is missing or the placeholder.
 */
function npf_render_phone_tag($phone) {
    $icon = '<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>';

    if (npf_phone_is_invalid($phone)) {
        return sprintf(
            '<a href="%1$s" class="business-tag-item inline-flex items-center gap-1 text-gray-500 hover:text-gray-700" target="_blank" rel="noopener nofollow" aria-label="Phone number not available">%2$s<span>-</span></a>',
            esc_url(npf_phone_fallback_url()),
            $icon
        );
    }

    return sprintf(
        '<a href="tel:%1$s" class="business-tag-item inline-flex items-center gap-1" itemprop="telephone" aria-label="Call %2$s">%3$s<span>%2$s</span></a>',
        esc_attr(npf_phone_clean_for_link($phone)),
        esc_html($phone),
        $icon
    );
}

function npf_render_whatsapp_button($phone) {
    $icon = '<svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>';

    if (npf_phone_is_invalid($phone)) {
        return sprintf(
            '<a href="%1$s" class="action-btn-google whatsapp-btn opacity-70" target="_blank" rel="noopener nofollow" aria-label="WhatsApp not available">%2$s -</a>',
            esc_url(npf_phone_fallback_url()),
            $icon
        );
    }

    return sprintf(
        '<a href="https://wa.me/%1$s" class="action-btn-google whatsapp-btn" target="_blank" rel="noopener noreferrer" aria-label="Contact via WhatsApp">%2$s WhatsApp</a>',
        esc_attr(npf_phone_clean_for_link($phone)),
        $icon
    );
}

/**
 * Locate the standalone plugin (by Plugin Name match). Returns array of
 * matching plugin file paths relative to the plugins directory.
 */
function npf_find_directory_plugin_files() {
    if (!function_exists('get_plugins')) {
        require_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    $matches = [];
    foreach (get_plugins() as $file => $data) {
        $name = isset($data['Name']) ? $data['Name'] : '';
        if (
            stripos($name, 'Nusa Penida Business Directory') !== false
            || (isset($data['TextDomain']) && $data['TextDomain'] === 'npbd')
        ) {
            $matches[] = $file;
        }
    }
    return $matches;
}
