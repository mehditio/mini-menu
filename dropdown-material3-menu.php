<?php
/*
Plugin Name: Dropdown Material 3 Menu
Description: منوی کشویی با تنظیمات آیکون و محل قرارگیری از پنل وردپرس
Version: 1.1
Author: Your Name
*/

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'admin/admin-page.php';

function dmm_enqueue_assets() {
    wp_register_style('dmm-style', plugin_dir_url(__FILE__) . 'css/style.css');
    wp_register_script('dmm-script', plugin_dir_url(__FILE__) . 'js/script.js', [], false, true);
}
add_action('wp_enqueue_scripts', 'dmm_enqueue_assets');

function dmm_admin_scripts($hook) {
    if ($hook !== 'settings_page_dmm-menu') return;
    wp_enqueue_media();
    wp_enqueue_script('dmm-admin-script', plugin_dir_url(__FILE__) . 'js/admin.js', ['jquery'], false, true);
}
add_action('admin_enqueue_scripts', 'dmm_admin_scripts');

function dropdown_material3_menu_shortcode() {
    wp_enqueue_style('dmm-style');
    wp_enqueue_script('dmm-script');

    $items = get_option('dmm_menu_items', []);
    ob_start();
    ?>
    <div class="dropdown-wrapper" dir="rtl">
        <div class="dropdown-icon" onclick="toggleDropdownMenu(this)">
            <img src="https://pottermarket.ir/wp-content/uploads/2025/05/more_vert_80dp_E3E3E3_FILL0_wght400_GRAD0_opsz48.svg" alt="menu icon">
        </div>
        <div class="dropdown-menu">
            <?php foreach ($items as $item): 
                $icon_pos = isset($item['icon_position']) ? $item['icon_position'] : 'before';
                $icon_html = '';
                if (!empty($item['icon'])) {
                    $icon_html = '<img src="'.esc_url($item['icon']).'" style="width:16px; height:16px; vertical-align: middle; margin: 0 6px;">';
                }
                $title = esc_html($item['title']);
                $link = esc_url($item['link']);
                ?>
                <a href="<?php echo $link; ?>" class="dmm-item-link dmm-icon-<?php echo esc_attr($icon_pos); ?>">
                    <?php 
                    if ($icon_pos === 'before') {
                        echo $icon_html . $title;
                    } elseif ($icon_pos === 'after') {
                        echo $title . $icon_html;
                    } elseif ($icon_pos === 'opposite') {
                        ?>
                        <span style="float:<?php echo is_rtl() ? 'left' : 'right'; ?>; margin-<?php echo is_rtl() ? 'left' : 'right'; ?>:6px;"><?php echo $icon_html; ?></span>
                        <span><?php echo $title; ?></span>
                        <?php
                    } else {
                        echo $icon_html . $title;
                    }
                    ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}
add_shortcode('dropdown_menu', 'dropdown_material3_menu_shortcode');
