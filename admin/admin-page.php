<?php
if (!defined('ABSPATH')) exit;

function dmm_admin_menu() {
    add_options_page(
        'مدیریت منوی کشویی',
        'Dropdown Menu',
        'manage_options',
        'dmm-menu',
        'dmm_render_admin_page'
    );
}
add_action('admin_menu', 'dmm_admin_menu');

function dmm_render_admin_page() {
    $items = get_option('dmm_menu_items', []);
    ?>
    <div class="wrap" dir="rtl">
        <h1>مدیریت منوی کشویی</h1>
        <form method="post" action="">
            <?php wp_nonce_field('dmm_save_menu', 'dmm_nonce'); ?>
            <table class="form-table" id="dmm-items">
                <thead>
                    <tr>
                        <th>عنوان</th>
                        <th>لینک</th>
                        <th>آیکون</th>
                        <th>محل آیکون</th>
                        <th>عملیات</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($items as $index => $item): ?>
                    <tr class="dmm-item">
                        <td><input type="text" name="items[<?php echo $index; ?>][title]" value="<?php echo esc_attr($item['title']); ?>" required></td>
                        <td><input type="url" name="items[<?php echo $index; ?>][link]" value="<?php echo esc_url($item['link']); ?>" required></td>
                        <td>
                            <div class="dmm-icon-wrapper">
                                <img src="<?php echo esc_url($item['icon']); ?>" class="dmm-icon-preview" style="max-width:40px; max-height:40px;<?php echo empty($item['icon']) ? 'display:none;' : ''; ?>">
                                <input type="hidden" name="items[<?php echo $index; ?>][icon]" class="dmm-icon-input" value="<?php echo esc_url($item['icon']); ?>">
                                <button type="button" class="button dmm-select-icon">انتخاب آیکون</button>
                                <button type="button" class="button dmm-remove-icon" style="<?php echo empty($item['icon']) ? 'display:none;' : ''; ?>">حذف</button>
                            </div>
                        </td>
                        <td>
                            <select name="items[<?php echo $index; ?>][icon_position]" class="dmm-icon-position">
                                <option value="before" <?php selected($item['icon_position'] ?? '', 'before'); ?>>قبل عنوان</option>
                                <option value="after" <?php selected($item['icon_position'] ?? '', 'after'); ?>>بعد عنوان</option>
                                <option value="opposite" <?php selected($item['icon_position'] ?? '', 'opposite'); ?>>روبرو</option>
                            </select>
                        </td>
                        <td><button type="button" class="remove-row button">حذف</button></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p><button type="button" class="button" id="add-row">افزودن آیتم</button></p>
            <?php submit_button('ذخیره'); ?>
        </form>
    </div>
    <?php
}

function dmm_save_menu_items() {
    if (!isset($_POST['dmm_nonce']) || !wp_verify_nonce($_POST['dmm_nonce'], 'dmm_save_menu')) {
        return;
    }
    if (!current_user_can('manage_options')) return;

    if (isset($_POST['items'])) {
        $cleaned = array_map(function($item) {
            return [
                'title' => sanitize_text_field($item['title']),
                'link'  => esc_url_raw($item['link']),
                'icon'  => esc_url_raw($item['icon'] ?? ''),
                'icon_position' => in_array($item['icon_position'] ?? '', ['before', 'after', 'opposite']) ? $item['icon_position'] : 'before',
            ];
        }, $_POST['items']);
        update_option('dmm_menu_items', $cleaned);
    }
}
add_action('admin_init', 'dmm_save_menu_items');
