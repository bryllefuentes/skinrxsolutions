<?php

namespace HelpieFaq\Includes\Repos;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\HelpieFaq\Includes\Repos\Faq')) {
    class Faq
    {

        public function __construct()
        {
            $this->faq_group_repo = new \HelpieFaq\Includes\Repos\Faq_Group();
        }

        private function modifying_group_termmeta_by_post($post_id, $groups, $action)
        {
            if (empty($groups)) {
                return;
            }
            foreach ($groups as $group) {
                $faq_group_items = $this->faq_group_repo->get_faq_group_items($group->term_id);
                $faq_group_items = $this->faq_group_repo->modify_faq_group_items($action, $post_id, $faq_group_items);
                $this->faq_group_repo->update_faq_group_term_meta($group->term_id, $faq_group_items);
            }
        }

        public function update_post($postId)
        {
            // get current post terms
            $terms = get_the_terms($postId, 'helpie_faq_group');

            // Get all groups
            $groups = get_terms(array(
                'taxonomy' => 'helpie_faq_group',
                'hide_empty' => false,
            ));

            $should_remove_all_faqs_in_group_termmeta = (empty($terms) && !empty($groups));

            if ($should_remove_all_faqs_in_group_termmeta) {
                $this->modifying_group_termmeta_by_post($postId, $groups, 'remove');
                return;
            }

            if (!empty($terms)) {
                $this->modifying_group_termmeta_by_post($postId, $terms, 'update');
            }
        }

        public function remove_post($postId)
        {
            // 1. get the current post
            $post = get_post($postId);

            // 2. get all current post terms
            $terms = get_the_terms($post->ID, 'helpie_faq_group');

            if (isset($terms) && empty($terms) || count($terms) == 0) {
                return;
            }

            $this->modifying_group_termmeta_by_post($postId, $terms, 'remove');
        }

        public function get_post_content($post)
        {
            return array(
                'post_id' => $post->ID,
                'title' => $post->post_title,
                'content' => $post->post_content,
            );
        }

        public function updating_the_post_status($new_status, $old_status, $post)
        {
            if (isset($post) && $post->post_type != HELPIE_FAQ_POST_TYPE) {
                return;
            }
            $allowed_old_post_status = ['draft', 'trash'];
            if ($new_status == 'publish' && in_array($old_status, $allowed_old_post_status)) {

                /** get all terms for this post */
                $faq_group_terms = get_the_terms($post->ID, 'helpie_faq_group');

                if (empty($faq_group_terms)) {
                    return;
                }

                $this->modifying_group_termmeta_by_post($post->ID, $faq_group_terms, 'add');
            }
        }

        public function save_post($post_id, $post, $update)
        {
            if (isset($post) && HELPIE_FAQ_POST_TYPE != $post->post_type) {
                return;
            }

            $this->update_question_types($post_id);

            $validation_map = array(
                'action' => 'String',
            );
            $sanitized_data = hfaq_get_sanitized_data("POST", $validation_map);
            $action = isset($sanitized_data['action']) ? $sanitized_data['action'] : '';

            /** Don't do anything, if the post edited by post page or in-line edit */
            if ($action == 'inline-save' || $action == 'helpie_faq_submission' || $update == 1) {
                return;
            }

            $terms = get_the_terms($post->ID, 'helpie_faq_category');
            if (empty($terms)) {
                $helpers = new \HelpieFaq\Includes\Utils\Helpers();
                $term_id = $helpers->get_default_category_term_id();
                $cat_ids = array_map('intval', (array) $term_id);
                /** Set the faq category term when create a new faq post */
                wp_set_object_terms($post->ID, $cat_ids, 'helpie_faq_category');
            }
        }

        private function update_question_types($post_id)
        {
            if (empty($post_id)) {
                return $post_id;
            }

            $sanitized_data = hfaq_get_sanitized_data("POST", "READ_ALL_AS_TEXT");
            $nonce = 'helpie_qna_metabox_' . $post_id . '_nonce';

            $nonce_value = isset($sanitized_data[$nonce]) ? $sanitized_data[$nonce] : '';
            $auto_save = (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE);
            $nonce_validated = wp_verify_nonce($nonce_value, 'helpie_qna_metabox_' . $post_id);

            if (empty($nonce_value) || !$nonce_validated || $auto_save) {
                return $post_id;
            }

            $selected_options = isset($sanitized_data['helpie_question_types']) ? $sanitized_data['helpie_question_types'] : [];
            helpie_error_log('$selected_options : ' . print_r($selected_options, true));

            update_post_meta($post_id, 'question_types', $selected_options);
        }
    }
}
