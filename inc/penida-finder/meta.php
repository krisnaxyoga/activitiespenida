<?php
/**
 * Penida Finder — meta-boxes & save handling for the Business CPT.
 * Stored under the original plugin's _business_* keys so legacy data is reused.
 */

if (!defined('ABSPATH')) exit;

add_action('add_meta_boxes', 'npf_add_business_meta_boxes');
function npf_add_business_meta_boxes() {
    add_meta_box('business_details', 'Business Details', 'npf_business_details_callback', 'business', 'normal', 'high');
}

function npf_business_details_callback($post) {
    wp_nonce_field('npf_save_business_meta', 'npf_business_meta_nonce');

    $website     = get_post_meta($post->ID, '_business_website', true);
    $phone       = get_post_meta($post->ID, '_business_phone', true);
    $email       = get_post_meta($post->ID, '_business_email', true);
    $address     = get_post_meta($post->ID, '_business_address', true);
    $description = get_post_meta($post->ID, '_business_description', true);
    $google_maps = get_post_meta($post->ID, '_business_google_maps', true);
    $instagram   = get_post_meta($post->ID, '_business_instagram', true);
    $facebook    = get_post_meta($post->ID, '_business_facebook', true);
    $tiktok      = get_post_meta($post->ID, '_business_tiktok', true);

    $invalid_phone = npf_phone_is_invalid($phone);
    ?>
    <div style="display: grid; gap: 15px; padding: 15px 0;">
        <div>
            <label style="display:block;margin-bottom:5px;font-weight:bold;" for="business_website">Website URL</label>
            <input type="url" id="business_website" name="business_website" value="<?php echo esc_url($website); ?>" style="width:100%;padding:8px;" placeholder="https://example.com">
        </div>
        <div>
            <label style="display:block;margin-bottom:5px;font-weight:bold;" for="business_phone">Phone Number</label>
            <input type="tel" id="business_phone" name="business_phone" value="<?php echo esc_attr($phone); ?>" style="width:100%;padding:8px;" placeholder="+62 812-3456-7890">
            <?php if ($invalid_phone && $phone !== '') : ?>
                <p style="margin:6px 0 0 0;font-size:12px;color:#b45309;">⚠️ Detected as the placeholder number — frontend will render a "-" backlink to <?php echo esc_html(npf_phone_fallback_url()); ?>.</p>
            <?php endif; ?>
        </div>
        <div>
            <label style="display:block;margin-bottom:5px;font-weight:bold;" for="business_email">Email</label>
            <input type="email" id="business_email" name="business_email" value="<?php echo esc_attr($email); ?>" style="width:100%;padding:8px;" placeholder="business@example.com">
        </div>
        <div>
            <label style="display:block;margin-bottom:5px;font-weight:bold;" for="business_address">Address</label>
            <textarea id="business_address" name="business_address" style="width:100%;padding:8px;height:80px;" placeholder="Full business address in Nusa Penida"><?php echo esc_textarea($address); ?></textarea>
        </div>
        <div>
            <label style="display:block;margin-bottom:5px;font-weight:bold;" for="business_description">Description</label>
            <textarea id="business_description" name="business_description" style="width:100%;padding:8px;height:120px;"><?php echo esc_textarea($description); ?></textarea>
        </div>
        <div style="border-top:2px solid #ddd;padding-top:20px;margin-top:10px;">
            <h3 style="margin-bottom:15px;color:#333;">Social &amp; Location</h3>
            <div style="margin-bottom:15px;">
                <label style="display:block;margin-bottom:5px;font-weight:bold;" for="business_google_maps">Google Maps URL</label>
                <input type="url" id="business_google_maps" name="business_google_maps" value="<?php echo esc_url($google_maps); ?>" style="width:100%;padding:8px;">
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block;margin-bottom:5px;font-weight:bold;" for="business_instagram">Instagram URL</label>
                <input type="url" id="business_instagram" name="business_instagram" value="<?php echo esc_url($instagram); ?>" style="width:100%;padding:8px;">
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block;margin-bottom:5px;font-weight:bold;" for="business_facebook">Facebook URL</label>
                <input type="url" id="business_facebook" name="business_facebook" value="<?php echo esc_url($facebook); ?>" style="width:100%;padding:8px;">
            </div>
            <div>
                <label style="display:block;margin-bottom:5px;font-weight:bold;" for="business_tiktok">TikTok URL</label>
                <input type="url" id="business_tiktok" name="business_tiktok" value="<?php echo esc_url($tiktok); ?>" style="width:100%;padding:8px;">
            </div>
        </div>
    </div>
    <?php
}

add_action('save_post_business', 'npf_save_business_meta', 10, 2);
function npf_save_business_meta($post_id, $post) {
    if (!isset($_POST['npf_business_meta_nonce']) || !wp_verify_nonce($_POST['npf_business_meta_nonce'], 'npf_save_business_meta')) {
        return;
    }
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $fields = [
        'business_website'     => 'url',
        'business_phone'       => 'text',
        'business_email'       => 'email',
        'business_address'     => 'textarea',
        'business_description' => 'textarea',
        'business_google_maps' => 'url',
        'business_instagram'   => 'url',
        'business_facebook'    => 'url',
        'business_tiktok'      => 'url',
    ];

    foreach ($fields as $field => $type) {
        if (!isset($_POST[$field])) continue;
        $value = $_POST[$field];
        switch ($type) {
            case 'url':       $value = esc_url_raw($value); break;
            case 'email':     $value = sanitize_email($value); break;
            case 'textarea':  $value = sanitize_textarea_field($value); break;
            default:          $value = sanitize_text_field($value);
        }
        update_post_meta($post_id, '_' . $field, $value);
    }
}
