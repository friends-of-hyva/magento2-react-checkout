<?php declare(strict_types=1);

namespace FriendsOfHyva\ReactCheckout\Plugin\DirectoryModel;

use Magento\Directory\Model\CountryInformationAcquirer;
use Magento\Directory\Model\Data\CountryInformation;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;

class SetStateRequired
{
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfig;

    /** @var array */
    private $stateRequiredCountries;

    /** @var boolean */
    private $showOptionalRegions;

    /** @var StoreInterface $store */
    private $store;

    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @param CountryInformationAcquirer $subject
     * @param array $countriesInfo
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetCountriesInfo(CountryInformationAcquirer $subject, array $countriesInfo): array
    {
        foreach ($countriesInfo as &$countryInfo) {
            /** @var CountryInformation $countryInfo */
            $countryInfo = $this->setRequiredRegionOnCountry($countryInfo, $countryInfo->getId());
        }

        return $countriesInfo;
    }

    /**
     * @param CountryInformationAcquirer $subject
     * @param CountryInformation $result
     * @param string $countryId
     * @return CountryInformation
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetCountryInfo(
        CountryInformationAcquirer $subject,
        CountryInformation $result,
        string $countryId
    ): CountryInformation {
        return $this->setRequiredRegionOnCountry($result, $countryId);
    }

    /**
     * @throws NoSuchEntityException
     */
    private function setRequiredRegionOnCountry(CountryInformation $country, $countryId): CountryInformation
    {

        $stateRequiredCountries = $this->getStateRequiredCountries();
        $showOptionalRegions = $this->getShowOptionalRegions();

        $isStateRequired = in_array($countryId, $stateRequiredCountries);

        if (!$isStateRequired && !$showOptionalRegions) {
            $country->setAvailableRegions([]);
        }

        $extensionAttributes = $country->getExtensionAttributes();
        $extensionAttributes->setStateRequired($isStateRequired);
        $country->setExtensionAttributes($extensionAttributes);

        return $country;
    }

    /**
     * @return \Magento\Store\Api\Data\StoreInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getStore(): StoreInterface
    {
        if (!$this->store) {
            $this->store = $this->storeManager->getStore();
        }
        return $this->store;
    }

    /**
     * @throws NoSuchEntityException
     */
    private function getStateRequiredCountries(): array
    {
        $store = $this->getStore();
        if (!$this->stateRequiredCountries) {
            $this->stateRequiredCountries = array_filter(explode(',', (string)$this->scopeConfig->getValue(
                'general/region/state_required',
                ScopeInterface::SCOPE_STORES,
                $store->getCode()
            )));
        }
        return $this->stateRequiredCountries;
    }

    /**
     * @throws NoSuchEntityException
     */
    private function getShowOptionalRegions(): bool
    {
        $store = $this->getStore();
        if ($this->showOptionalRegions === null) {
            $this->showOptionalRegions = (bool) $this->scopeConfig->getValue(
                'general/region/display_all',
                ScopeInterface::SCOPE_STORES,
                $store->getCode()
            );
        }
        return $this->showOptionalRegions;
    }
}
