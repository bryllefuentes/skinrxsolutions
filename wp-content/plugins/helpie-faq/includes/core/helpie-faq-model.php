<?php

namespace HelpieFaq\Includes\Core;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (!class_exists('\HelpieFaq\Includes\Core\Helpie_Faq_Model')) {
    class Helpie_Faq_Model
    {
        public function __construct()
        {
            $this->option = get_option('helpie-faq');
            // error_log(' [option] ' . print_r($this->option, true));
        }

        public function get_configured_slug($option_name)
        {
            $slug = '';
            if (isset($this->option[$option_name]) && !empty($this->option[$option_name])) {
                $slug = $this->option[$option_name];
            }
            return $slug;
        }

        public function get_global_search_option()
        {
            $faq_option = $this->option;
            $exclude_from_search = false;
            if (isset($faq_option['exclude_from_search'])) {
                $exclude_from_search = ($faq_option['exclude_from_search'] == 1) ? true : false;
            }
            return $exclude_from_search;
        }
    }
}
