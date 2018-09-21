<?php
/**
 * Price Field Modifier Class
 * Auth: David Majidian
 * Date: 8/14/2018
 */
namespace Majidian\Disableprice\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;

class Price extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{
    protected $locator;
    protected $scopeConfig;

    public function __construct(
        LocatorInterface $locator,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->locator = $locator;
        $this->scopeConfig = $scopeConfig;
    }

    public function modifyMeta(array $meta)
    {
        if($this->isConfigEnabled())
        {
            $meta = $this->customizePrice($meta);
        }

        return $meta;
    }

    public function modifyData(array $data)
    {
        return $data;
    }

    private function isConfigEnabled()
    {
        $model = $this->locator->getProduct();
        $attributeSetId = $model->getAttributeSetId();
        $configs_str = $this->scopeConfig->getValue('catalog/disableprice/attributes', \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        $configs = explode(',', $configs_str);

        return in_array($attributeSetId,$configs);
    }

    protected function customizePrice(array $meta)
    {
        
        //User ArrayAccess here ....but I was too lazy in this demo
        $meta['product-details']['children']['container_price']['children']['price']['arguments']['data']['config']['default'] = 0.00;
        $meta['product-details']['children']['container_price']['children']['price']['arguments']['data']['config']['visible'] = 0;
        $meta['product-details']['children']['container_tax_class_id']['children']['tax_class_id']['arguments']['data']['config']['visible'] = 0;
        $meta['advanced-pricing']['arguments']['data']['config']['visible'] = 0;

        return $meta;
    }
}
