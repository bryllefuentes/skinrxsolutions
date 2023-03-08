<?php

namespace HelpieFaq\Includes;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
/**
 * Helpie upgrades.
 *
 * Helpie upgrades handler class is responsible for updating different
 * Helpie versions.
 *
 * @since 1.0.0
 */
if (!class_exists('\HelpieFaq\Includes\Upgrades')) {
    class Upgrades
    {
        /**
         * Add actions.
         *
         * Hook into WordPress actions and launch Helpie upgrades.
         *
         * @static
         * @since 1.0.0
         * @access public
         */
        public static function add_actions()
        {
            // helpie_error_log('Upgrades add_actions...');
            add_action('init', [__CLASS__, 'init'], 20);
        }
        /**
         * Init.
         *
         * Initialize Helpie upgrades.
         *
         * Fired by `init` action.
         *
         * @static
         * @since 1.0.0
         * @access public
         */
        public static function init()
        {
            helpie_error_log('upgrades init');
            $helpie_version = get_option('helpie_version');

            helpie_error_log('$helpie_version : ' . $helpie_version);
            helpie_error_log('HELPIE_FAQ_VERSION : ' . HELPIE_FAQ_VERSION);
            // Normal init.
            if (HELPIE_FAQ_VERSION === $helpie_version) {
                return;
            }
            self::check_upgrades($helpie_version);
            // Plugin::$instance->files_manager->clear_cache();
            update_option('helpie_version', HELPIE_FAQ_VERSION);
        }
        /**
         * Check upgrades.
         *
         * Checks whether a given Helpie version needs to be upgraded.
         *
         * If an upgrade required for a specific Helpie version, it will update
         * the `helpie_upgrades` option in the database.
         *
         * @static
         * @since 1.0.10
         * @access private
         *
         * @param string $helpie_version
         */
        public static function check_upgrades($helpie_version)
        {
            // It's a new install.
            if (!$helpie_version) {
                // return;
                $helpie_version = HELPIE_FAQ_VERSION;
            }
            $helpie_upgrades = get_option('helpie_upgrades', []);
            $upgrades = [
                '0.6' => 'upgrade_v06',
                '0.7' => 'upgrade_v07',
                '1.0' => 'upgrade_v10',
                '1.6.2' => 'upgrade_v162',
                '1.6.6' => 'upgrade_v166',
                '1.6.7' => 'upgrade_v167',
                '1.6.9' => 'upgrade_v169',
                '1.8' => 'upgrade_v18',
                '1.9' => 'upgrade_v19',
            ];

            foreach ($upgrades as $version => $function) {
                $should_run_version_upgrade = version_compare($helpie_version, $version, '<') && !isset($helpie_upgrades[$version]);

                helpie_error_log('$should_run_version_upgrade : ' . $should_run_version_upgrade);
                if ($should_run_version_upgrade) {
                    self::$function();
                    $helpie_upgrades[$version] = true;
                    update_option('helpie_upgrades', $helpie_upgrades);
                }
            }
        }

        private static function upgrade_v18()
        {
            // Get FAQ Groups
            $terms = self::get_all_faq_groups();

            if (empty($terms)) {
                return;
            }

            foreach ($terms as $term_id => $term) {
                $term_id = $term->term_id;
                $faq_group_settings = get_term_meta($term_id, 'faq_group_settings', true);

                if (empty($faq_group_settings)) {
                    continue;
                }

                // Get Post and Taxonomy Values
                $products = isset($faq_group_settings['products']) ? $faq_group_settings['products'] : [];
                $product_categories = isset($faq_group_settings['product_categories']) ? $faq_group_settings['product_categories'] : [];

                $fields = [];

                // Convert styles
                if (isset($faq_group_settings['header']) && !empty($faq_group_settings['header'])) {
                    $fields['header-background'] = [
                        'originId' => 'header-background',
                        'value' => $faq_group_settings['header']['background'] ? $faq_group_settings['header']['background'] : '',
                    ];
                    $fields['header-font-color'] = [
                        'originId' => 'header-font-color',
                        'value' => $faq_group_settings['header']['color'] ? $faq_group_settings['header']['color'] : '',
                    ];
                }

                if (isset($faq_group_settings['body']) && !empty($faq_group_settings['body'])) {
                    $fields['body-background'] = [
                        'originId' => 'body-background',
                        'value' => $faq_group_settings['body']['background'] ? $faq_group_settings['body']['background'] : '',
                    ];
                    $fields['body-font-color'] = [
                        'originId' => 'body-font-color',
                        'value' => $faq_group_settings['body']['color'] ? $faq_group_settings['body']['color'] : '',
                    ];
                }

                // Convert to FAQ Group Data Structure
                if (isset($products) && !empty($products)) {
                    $fields['post_type__1'] = [
                        'originId' => 'post_type',
                        'value' => 'product',
                    ];
                    $fields['taxonomy_or_post__1'] = [
                        'originId' => 'taxonomy_or_post',
                        'value' => 'post',
                    ];
                    $fields['post__1'] = [
                        'originId' => 'post',
                        'value' => $products,
                    ];
                    $fields['taxonomy__1'] = [
                        'originId' => 'taxonomy',
                        'value' => [],
                    ];

                    $fields['terms__1'] = [
                        'originId' => 'terms',
                        'value' => [],
                    ];
                }

                if (isset($product_categories) && !empty($product_categories)) {

                    $fields['post_type__2'] = [
                        'originId' => 'post_type',
                        'value' => 'product',
                    ];
                    $fields['taxonomy_or_post__2'] = [
                        'originId' => 'taxonomy_or_post',
                        'value' => 'taxonomy',
                    ];
                    $fields['post__2'] = [
                        'originId' => 'post',
                        'value' => [],
                    ];
                    $fields['taxonomy__2'] = [
                        'originId' => 'taxonomy',
                        'value' => 'product_cat',
                    ];

                    $fields['terms__2'] = [
                        'originId' => 'terms',
                        'value' => $product_categories,
                    ];
                }

                $new_settings = [
                    'fields' => $fields,
                ];

                // Update Term Meta
                \update_term_meta($term_id, 'faq_group_settings', $new_settings);
            }
        }

        private static function get_all_faq_groups()
        {
            $terms = get_terms(array(
                'taxonomy' => 'helpie_faq_group',
                'hide_empty' => false,
            ));

            return $terms;
        }

        private static function upgrade_v07()
        {

            $faq_wp_posts = get_posts(
                array(
                    'post_type' => HELPIE_FAQ_POST_TYPE,
                )
            );

            $meta_key = 'click_counter';

            foreach ($faq_wp_posts as $post) {
                // 1. Get current click_counter
                $count = get_post_meta($post->ID, $meta_key, true);

                // 2. Update click_counter with new format
                $new_click_counter = array(
                    '30days' => $count,
                    '1year' => $count,
                );
                update_post_meta($post->ID, $meta_key, $new_click_counter);
                // helpie_error_log('$new_click_counter : ' . print_r($new_click_counter, true));
            }
        }

        private static function upgrade_v06()
        {

            $faq_wp_posts = get_posts(
                array(
                    'post_type' => HELPIE_FAQ_POST_TYPE,
                )
            );

            foreach ($faq_wp_posts as $posts) {
                // update_post_meta();
                add_post_meta($posts->ID, 'click_counter', 0, true);
            }

            $terms = get_terms(array(
                'taxonomy' => 'helpie_faq_category',
                'hide_empty' => false,
            ));

            foreach ($terms as $term) {
                // update_post_meta();
                add_term_meta($term->term_id, 'click_counter', 0, true);
            }
        }

        private static function upgrade_v10()
        {

            $settings = get_option('helpie-faq');

            $settings['open_by_default'] = 'none';
            if (isset($settings['open_first']) && $settings['open_first'] == true) {
                $settings['open_by_default'] = 'open_first';
            }

            /* Set new version */
            $settings['last_version'] = '1.0';

            $result = \update_option('helpie-faq', $settings);
            $updated_option = get_option('helpie-faq');

            if (isset($updated_option['last_version']) && $updated_option['last_version'] == '1.0') {
                $result = true;
            }

            return $result;
        }

        private static function upgrade_v162()
        {
            $migration = new \HelpieFaq\Includes\Migrations\Version162();
            return $migration->run();
        }

        private static function upgrade_v166()
        {
            $migration = new \HelpieFaq\Includes\Migrations\Version166();
            return $migration->run();
        }

        private static function upgrade_v167()
        {
            $migration = new \HelpieFaq\Includes\Migrations\Version167();
            $migration->run();
        }

        private static function upgrade_v169()
        {
            $settings = get_option('helpie-faq');
            $faq_url_attribute_enabled = isset($settings['faq_url_attribute']) && $settings['faq_url_attribute'] == 1 ? true : false;
            $settings['faq_url_type'] = 'post_slug';
            if ($faq_url_attribute_enabled) {
                $settings['faq_url_type'] = 'post_id';
            }

            /* Set new version */
            $settings['last_version'] = '1.6.9';

            $result = \update_option('helpie-faq', $settings);
            $updated_option = get_option('helpie-faq');

            if (isset($updated_option['last_version']) && $updated_option['last_version'] == '1.6.9') {
                $result = true;
            }

            return $result;
        }

        private static function upgrade_v19()
        {

            helpie_error_log('upgrade_v19');
            $settings = get_option('helpie-faq');
            $migration_success = false;

            $faq_wp_posts = get_posts(
                array(
                    'post_type' => HELPIE_FAQ_POST_TYPE,
                    'nopaging' => true,
                    'post_status' => 'any',
                )
            );

            $post_count = count($faq_wp_posts);

            helpie_error_log('$post_count : ' . $post_count);
            $num_of_successful_meta_update = 0;

            foreach ($faq_wp_posts as $posts) {
                // update_post_meta();
                $result = add_post_meta($posts->ID, 'question_types', array('faq'), true);
                helpie_error_log('$result : ' . $result);

                if ($result) {
                    $num_of_successful_meta_update++;
                }
            }

            if ($num_of_successful_meta_update == $post_count) {
                $migration_success = true;
            }

            /* Set new version */
            $settings['last_version'] = '1.9';

            $result = \update_option('helpie-faq', $settings);
            $updated_option = get_option('helpie-faq');

            if ($migration_success && isset($updated_option['last_version']) && $updated_option['last_version'] == '1.9') {
                $migration_success = true;
            }

            helpie_error_log('$migration_success : ' . $migration_success);
            return $migration_success;
        }
    } // END CLASS
}
