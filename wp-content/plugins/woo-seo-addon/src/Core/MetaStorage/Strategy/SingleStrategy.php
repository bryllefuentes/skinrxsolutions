<?php namespace Premmerce\SeoAddon\Core\MetaStorage\Strategy;

use Premmerce\SeoAddon\Core\MetaStorage\Strategy\Base\AbstractStrategy;
use WP_Post;

/**
 * Class SingleStrategy
 * @property  WP_Post $object
 * @package Premmerce\SeoAddon\Core\MetaStorage\Strategy
 */
class SingleStrategy extends AbstractStrategy
{


    /**
     * @return string
     */
    public function getId()
    {
        return 'post';
    }

    /**
     * @return array|null
     */
    public function getImage()
    {
        return wp_get_attachment_image_src(get_post_thumbnail_id($this->object->ID), 'full');
    }

    /**
     * @return string|null
     */
    public function getTopCategoryTitle()
    {
        $categoryIds = wp_get_post_categories($this->getObject()->ID, ['number' => 1]);

       if(is_array($categoryIds) && $categoryIds){
           $category = get_term($categoryIds[0]);
           while ($category->parent){
               $category = get_term($category->parent);
           }

           return $category->name;
       }

       return null;
    }

    /**
     * @return string|null
     */
    public function getPublishedTime()
    {
        return get_the_time(DATE_ATOM, $this->getObject()) ?: null;
    }

    /**
     * @return string|null
     */
    public function getModifiedTime()
    {
        return get_the_modified_time(DATE_ATOM, $this->getObject()) ?: null;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->object->post_excerpt ?: $this->getPostExcerptFromContent();
    }

    /**
     * @return false|string
     */
    public function getPermalink()
    {
        return get_permalink($this->object);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'article';
    }

    /**
     * @return int
     */
    public function getPaginationPagesCount()
    {
        return substr_count($this->object->post_content, '<!--nextpage-->') + 1;
    }

    /**
     * @return string
     */
    public function getPaginationPageVar()
    {
        return 'page';
    }

    /**
     * @return string
     */
    private function getPostExcerptFromContent()
    {
        $excerpt = wp_strip_all_tags(get_the_excerpt());
        return str_replace('[&hellip;]', '&hellip;', $excerpt);
    }
}