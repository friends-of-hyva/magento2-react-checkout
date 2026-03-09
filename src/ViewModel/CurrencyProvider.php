<?php
declare(strict_types=1);

namespace FriendsOfHyva\ReactCheckout\ViewModel;

use Magento\Directory\Model\Currency;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Store\Model\StoreManagerInterface;

class CurrencyProvider implements ArgumentInterface
{
    /**
     * @var StoreManagerInterface $storeManager
     */
    private $storeManager;

    /**
     * @var Currency $currentCurrency
     */
    private $currentCurrency;

    /**
     * LocaleProvider constructor.
     *
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->storeManager = $storeManager;
    }

    /**
     * @throws LocalizedException
     */
    public function getConfig(): array
    {
        return
            [
                'code' => $this->getCurrentCurrencyCode(),
                'symbol' => $this->getCurrentCurrencySymbol()
            ];
    }

    /**
     * @return string
     * @throws LocalizedException
     */
    public function getCurrentCurrencyCode(): string
    {
        // do not use $this->storeManager->getStore()->getCurrentCurrencyCode() because of probability
        // to get an invalid (without base rate) currency from code saved in session
        return $this->getCurrentCurrency()->getCode();
    }

    /**
     * @return Currency
     * @throws LocalizedException
     */
    public function getCurrentCurrency(): Currency
    {
        if (!$this->currentCurrency) {
            $this->currentCurrency = $this->storeManager->getStore()->getCurrentCurrency();
        }
        return $this->currentCurrency;
    }

    /**
     * Retrieve Current Currency Symbol
     *
     * @return string
     * @throws LocalizedException
     */
    public function getCurrentCurrencySymbol(): string
    {
        // do not use $this->storeManager->getStore()->getCurrentCurrencyCode() because of probability
        // to get an invalid (without base rate) currency from code saved in session
        return $this->getCurrentCurrency()->getCurrencySymbol() ?: $this->getCurrentCurrencyCode();
    }
}
