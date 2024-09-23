<?php

/**
 * 
 *  Admin info for Magical Addons For Elementor plugin
 * 
 * 
 * 
 */

class madAdminInfo
{
    public static function init()
    {

        add_action('admin_notices', [__CLASS__, 'mp_display_admin_info']);
        add_action('init', [__CLASS__, 'mp_display_admin_info_init']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'mgaddons_admin_scripts']);
    }

    static function mp_display_admin_info_output()
    {

?>
        <div class="mgadin-hero">
            <div class="mge-info-content">
                <div class="mge-info-hello">
                    <?php
                    $addons_name = esc_html__('Magical Addons, ', 'magical-addons-for-elementor');
                    $current_user = wp_get_current_user();

                    $pro_link = esc_url('https://wpthemespace.com/product/magical-addons-pro/?add-to-cart=7193');
                    $pricing_link = esc_url('https://magic.wpcolors.net/pricing-plan/#mgpricing');

                    esc_html_e('Hello, ', 'magical-addons-for-elementor');
                    echo esc_html($current_user->display_name);
                    ?>

                    <?php esc_html_e('👋🏻', 'magical-addons-for-elementor'); ?>
                </div>
                <div class="mge-info-desc">
                    <div><?php printf(esc_html__('Thanks for choosing %1$s. We\'re excited to announce that our pro version is now available, and it\'s packed with even more amazing features to take your web design game to the next level. Our library is growing, so you\'ll always have access to the latest and greatest features. So Upgrade pro now!!', 'magical-addons-for-elementor'), esc_html($addons_name)); ?></div>
                    <div class="mge-offer"><?php printf(esc_html__('Upgrade to Magical Addons Pro today and unlock a world of possibilities for just $21! 🤑', 'magical-addons-for-elementor'), esc_html($addons_name)); ?></div>
                </div>
                <div class="mge-info-actions">
                    <a href="<?php echo esc_url($pro_link); ?>" target="_blank" class="button button-primary upgrade-btn">
                        <?php esc_html_e('Quick Upgrade', 'magical-addons-for-elementor'); ?>
                    </a>
                    <a href="<?php echo esc_url($pricing_link); ?>" target="_blank" class="button button-primary demo-btn">
                        <?php esc_html_e('View All Pricing Plan', 'magical-addons-for-elementor'); ?>
                    </a>
                    <button class="button button-info mgad-dismiss"><?php esc_html_e('Dismiss this notice', 'magical-addons-for-elementor') ?></button>
                </div>
            </div>

        </div>
    <?php
    }




    public static function mp_display_admin_info()
    {

        $hide_date = get_option('mg_info_date');
        if (!empty($hide_date)) {
            $clickhide = round((time() - strtotime($hide_date)) / 24 / 60 / 60);
            if ($clickhide < 25) {
                return;
            }
        }

        $install_date = get_option('mg_install_date');
        if (!empty($install_date)) {
            $install_day = round((time() - strtotime($install_date)) / 24 / 60 / 60);
            if ($install_day < 2) {
                return;
            }
        }
    ?>
        <div class="mgadin-notice notice notice-success mgadin-theme-dashboard mgadin-theme-dashboard-notice mge is-dismissible meis-dismissible">
            <?php madAdminInfo::mp_display_admin_info_output(); ?>
        </div>

<?php


    }

    public static function mp_display_admin_info_init()
    {
        if (isset($_GET['mgpdismissed']) && $_GET['mgpdismissed'] == 1) {
            update_option('mg_info_date', current_time('mysql'));
        }
        if (isset($_GET['tinfohide']) && $_GET['tinfohide'] == 1) {
            update_option('mg_hide_tinfo1', current_time('mysql'));
        }
    }
    public static function mgaddons_admin_scripts()
    {
        wp_enqueue_style('mgaddons-admin-info',  MAGICAL_ADDON_URL . 'assets/css/mg-admin-info.css', array(), MAGICAL_ADDON_VERSION, 'all');

        wp_enqueue_script('mgaddons-admin-info',  MAGICAL_ADDON_URL . 'assets/js/mg-admin-info.js', array('jquery'), MAGICAL_ADDON_VERSION, true);
    }
}
madAdminInfo::init();
