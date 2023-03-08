<?php namespace Premmerce\SeoAddon\Core\MetaEngines;

use Premmerce\SeoAddon\Core\MetaStorage\MetaDataStorage;
use WC_Product;
use WP_Post;


class OpenGraph implements MetaEngineInterface
{
    /**
     * @var MetaDataStorage
     */
    private $storage;
    /**
     * @var array
     */
    private $config;

    /**
     * OpenGraph constructor.
     *
     * @param MetaDataStorage $storage
     * @param array $config
     */
    public function __construct(MetaDataStorage $storage, array $config = [])
    {
        $this->storage = $storage;
        $this->config = $config;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return 'og';
    }

    /**
     * Display markup
     */
    public function display()
    {
        $type = $this->storage->getType();

        $data = [
            'og:locale'       => $this->storage->getLocale(),
            'og:type'         => $type,
            'og:title'        => $this->storage->getTitle(),
            'og:description'  => $this->storage->getDescription(),
            'og:url'          => $this->storage->getPermalink(),
            'og:site_name' => $this->storage->getSiteName(),
        ];

        if ($image = $this->storage->getImage()) {
            list($data['og:image'], $data['og:image:width'], $data['og:image:height']) = $image;
        }

        switch ($type) {
            case 'website':
                $data = $this->extendWebsite($data);
                break;
            case 'product':
                $data = $this->extendProduct($data);
                break;
            case 'article':
                $data = $this->extendArticle($data);
        }

        echo $this->prepare($data);
    }


    /**
     * @param array $data
     *
     * @return string
     */
    public function prepare(array $data)
    {
        $string = '';
        $data   = array_filter($data);
        foreach ($data as $key => $value) {
            $string .= $this->format($key, $value);
        }

        return $string;
    }

    /**
     * @param string $property
     * @param string $content
     *
     * @return string
     */
    public function format($property, $content)
    {
        return sprintf('<meta property="%s" content="%s" />%s', esc_attr($property), esc_attr($content), PHP_EOL);
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function extendProduct(array $data)
    {

        $product = wc_get_product();

        if ($this->storage->getType() === 'product' && $product instanceof WC_Product) {

            if ($price = $product->get_price()) {
                $data['og:price:amount']   = $price;
                $data['og:price:currency'] = esc_attr(get_woocommerce_currency());
            }

            $data['product:availability'] = $product->is_in_stock() ? 'instock' : 'pending';
            $data['product:retailer_item_id'] = $product->get_sku();
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function extendWebsite(array $data)
    {
        if ($logo = $this->storage->getSiteLogo()) {
            $data['og:logo'] = $logo;
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function extendArticle(array $data)
    {
       $data['article:section'] = $this->storage->getStrategy()->getTopCategoryTitle();
       $data['article:published_time'] = $this->storage->getStrategy()->getPublishedTime();
       $data['article:modified_time'] = $this->storage->getStrategy()->getModifiedTime();

        return $data;
    }


}