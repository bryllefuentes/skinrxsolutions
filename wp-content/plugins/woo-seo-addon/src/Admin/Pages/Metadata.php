<?php

namespace Premmerce\SeoAddon\Admin\Pages;

use Premmerce\SeoAddon\Core\Config\SettingsInterface;

class Metadata implements SettingsInterface
{

    public function getId()
    {
        return 'premmerce_seo_metadata';
    }

    public function getTitle()
    {
        return __('Indexing', 'woo-seo-addon');
    }

    public function getFields()
    {
        $settings['section_search']          = [
            'title' => __('Search', 'woo-seo-addon'),
            'type'  => 'section'
        ];
        $settings['indexing_disable_search'] = [
            'type'        => 'checkbox',
            'label'       => __('Disable', 'woo-seo-addon'),
            'title'       => __('Disable indexing', 'woo-seo-addon'),
            'description' => __('Add noindex, follow to search page', 'woo-seo-addon'),
        ];

        $settings['section_date_archive']    = [
            'title' => __('Date archives', 'woo-seo-addon'),
            'type'  => 'section'
        ];
        $settings['indexing_disable_date']   = [
            'type'        => 'checkbox',
            'label'       => __('Disable', 'woo-seo-addon'),
            'title'       => __('Disable indexing', 'woo-seo-addon'),
            'description' => __('Add noindex, follow to date archives page', 'woo-seo-addon'),
        ];

        $settings['section_author_archive']    = [
            'title' => __('Author archives', 'woo-seo-addon'),
            'type'  => 'section'
        ];
        $settings['indexing_disable_author']   = [
            'type'        => 'checkbox',
            'label'       => __('Disable', 'woo-seo-addon'),
            'title'       => __('Disable indexing', 'woo-seo-addon'),
            'description' => __('Add noindex, follow to author archives page', 'woo-seo-addon'),
        ];

        $settings['section_tag_archive']    = [
            'title' => __('Tag archives', 'woo-seo-addon'),
            'type'  => 'section'
        ];
        $settings['indexing_disable_post_tag']   = [
            'type'        => 'checkbox',
            'label'       => __('Disable', 'woo-seo-addon'),
            'title'       => __('Disable indexing', 'woo-seo-addon'),
            'description' => __('Add noindex, follow to tag archives page', 'woo-seo-addon'),
        ];

        $settings['section_category_archive']    = [
            'title' => __('Category archives', 'woo-seo-addon'),
            'type'  => 'section'
        ];
        $settings['indexing_disable_category']   = [
            'type'        => 'checkbox',
            'label'       => __('Disable', 'woo-seo-addon'),
            'title'       => __('Disable indexing', 'woo-seo-addon'),
            'description' => __('Add noindex, follow to category archives page', 'woo-seo-addon'),
        ];

        return $settings;
    }
}