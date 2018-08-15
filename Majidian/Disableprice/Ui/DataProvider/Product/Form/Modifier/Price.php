<?php
/**
 * Price Field Modifier Class
 * Auth: David Majidian
 * Date: 8/14/2018
 * Time: 5:57 PM
 */
namespace Majidian\Disableprice\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Catalog\Api\ProductLinkRepositoryInterface;

class Price extends \Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier
{
    protected $locator;
    protected $scopeConfig;
    protected $attributeSetCollectionFactory;
    protected $productLinkRepository;

    public function __construct(
        LocatorInterface $locator,
        ScopeConfigInterface $scopeConfig,
        ProductLinkRepositoryInterface $productLinkRepository
    ) {
        $this->locator = $locator;
        $this->scopeConfig = $scopeConfig;
        $this->productLinkRepository = $productLinkRepository;
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
        $meta['product-details']['children']['container_price']['children']['price']['arguments']['data']['config']['default'] = 0.00;
        $meta['product-details']['children']['container_price']['children']['price']['arguments']['data']['config']['visible'] = 0;
        $meta['product-details']['children']['container_tax_class_id']['children']['tax_class_id']['arguments']['data']['config']['visible'] = 0;
        $meta['advanced-pricing']['arguments']['data']['config']['visible'] = 0;

        return $meta;
    }
}