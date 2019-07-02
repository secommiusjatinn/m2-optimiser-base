<?php
/**
 * OptimiserBase Helper  
 *
 * @category   Scommerce
 * @package    Scommerce_OptimiserBase
 * @author     Sommerce Mage <core@scommerce-mage.co.uk>
 */

namespace Scommerce\OptimiserBase\Helper;

use Magento\Store\Model\ScopeInterface;

/**
 * Class Data
 * @package Scommerce_OptimiserBase
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     * @const config path
     */    
    const ENABLED              = 'scommerce_optimiserbase/general/enabled';
    
    const LICENSE_KEY = 'scommerce_optimiserbase/general/license_key';
      

    /**
     * @var \Scommerce\Core\Helper\Data
     */
    public $coreHelper;

    /**
     * __construct
     * 
     * @param \Magento\Framework\App\Helper\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Scommerce\Core\Helper\Data $coreHelper
    ) {
        parent::__construct($context);
        $this->coreHelper = $coreHelper;
    }
    
    
    /**
     * Is Catalog Url module active
     *
     * @return bool
     */
    public function isEnabled()
    {
        $enabled = $this->isSetFlag(self::ENABLED);
        return $this->isCliMode() ? $enabled : $enabled && $this->isLicenseValid();
    }
    
    /**
     * Returns license key administration configuration option
     *
     * @return string
     */
    public function getLicenseKey()
    {
        return $this->getValue(self::LICENSE_KEY);
    }
    
    /**
     * Returns whether license key is valid or not
     *
     * @return bool
     */
    public function isLicenseValid()
    {
        $sku = strtolower(str_replace('\\Helper\\Data','',str_replace('Scommerce\\','',get_class($this))));
        return $this->coreHelper->isLicenseValid($this->getLicenseKey(),$sku);
    }
    
    /**
     * Helper method for retrieve config value by path and scope
     *
     * @param string $path The path through the tree of configuration values, e.g., 'general/store_information/name'
     * @param string $scopeType The scope to use to determine config value, e.g., 'store' or 'default'
     * @param null|string $scopeCode
     * @return mixed
     */
    protected function getValue($path, $scopeType = ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return $this->scopeConfig->getValue($path, $scopeType, $scopeCode);
    }

    /**
     * Helper method for retrieve config flag by path and scope
     *
     * @param string $path The path through the tree of configuration values, e.g., 'general/store_information/name'
     * @param string $scopeType The scope to use to determine config value, e.g., 'store' or 'default'
     * @param null|string $scopeCode
     * @return bool
     */
    protected function isSetFlag($path, $scopeType = ScopeInterface::SCOPE_STORE, $scopeCode = null)
    {
        return $this->scopeConfig->isSetFlag($path, $scopeType, $scopeCode);
    }

    /**
     * Check if running in cli mode
     *
     * @return bool
     */
    protected function isCliMode()
    {
        return php_sapi_name() === 'cli';
    }
}