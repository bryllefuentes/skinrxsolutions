<?php namespace Premmerce\SeoAddon\Frontend\Handler;

use Premmerce\SeoAddon\Core\Config\ConfigBundle;
use Premmerce\SeoAddon\Core\MetaEngines\EngineAggregator;
use Premmerce\SeoAddon\Core\MetaEngines\LinkedData;
use Premmerce\SeoAddon\Core\MetaEngines\OpenGraph;

class YoastSEO
{
    /**
     * @var EngineAggregator
     */
    private $engine;
    /**
     * @var ConfigBundle
     */
    private $config;

    public function __construct(EngineAggregator $engine, ConfigBundle $config)
    {
        $this->engine = $engine;
        $this->config = $config;

        add_action('plugins_loaded', function () {

            if ($this->config->isEnabled('markup_ld')) {
                $isYoastOldVersion = version_compare(WPSEO_VERSION, '11.0', '<');
                $filter = $isYoastOldVersion ? 'wpseo_json_ld_output' :  'wpseo_schema_organization';
                add_filter($filter, [$this, 'extendYoastLinkedData']);
            }

            if ($this->config->isEnabled('markup_og')) {

                add_action('wpseo_opengraph', [$this, 'extendYoastOG'], 40);

                add_filter('wpseo_opengraph_type', [$this, 'extendYoastOGType']);
            }
        });
    }

    /**
     * Change og:type to product if it is product page
     *
     * @param string $type
     *
     * @return string
     */
    public function extendYoastOGType($type)
    {
        if (is_singular(['product'])) {
            return 'product';
        }

        return $type;
    }

    /**
     * Add price currency and availability
     */
    public function extendYoastOG()
    {
        if (is_singular(['product'])) {
            /** @var OpenGraph $og */
            if ($og = $this->engine->getEngine('og')) {
                echo $og->prepare($og->extendProduct([]));
            }
        }
    }


    /**
     * Extend yoast json+ld fields with location data
     *
     * @param array $data
     *
     * @return array
     */
    public function extendYoastLinkedData(array $data)
    {
        /** @var LinkedData $ld */
        if ($ld = $this->engine->getEngine('ld')) {
            return $ld->extendLinkedData($data);
        }

        return $data;
    }


}