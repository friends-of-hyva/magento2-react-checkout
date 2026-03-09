<?php
declare(strict_types=1);

namespace FriendsOfHyva\ReactCheckout\ViewModel;

use Magento\Checkout\Model\CompositeConfigProvider;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Framework\Locale\ResolverInterface as LocaleResolverInterface;
use Magento\Framework\Event\ManagerInterface as EventManager;

class CheckoutConfigProvider implements ArgumentInterface
{
    /**
     * @var CompositeConfigProvider
     */
    private $compositeConfigProvider;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var LocaleResolverInterface
     */
    private $localeResolver;

    /**
     * @var CurrencyProvider
     */
    private $currencyProvider;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * CheckoutConfigProvider constructor.
     *
     * @param SerializerInterface $serializer
     * @param LocaleResolverInterface $localeResolver
     * @param CompositeConfigProvider $compositeConfigProvider
     * @param CurrencyProvider $currencyProvider
     * @param EventManager $eventManager
     */
    public function __construct(
        SerializerInterface $serializer,
        LocaleResolverInterface $localeResolver,
        CompositeConfigProvider $compositeConfigProvider,
        CurrencyProvider $currencyProvider,
        EventManager $eventManager
    ) {
        $this->serializer = $serializer;
        $this->localeResolver = $localeResolver;
        $this->compositeConfigProvider = $compositeConfigProvider;
        $this->currencyProvider = $currencyProvider;
        $this->eventManager = $eventManager;
    }

    /**
     * Provide checkout configuration
     *
     * The payment configuration inside checkout configuration is what really
     * needed for the react app at the moment. Hence we are passing only that
     * information for the moment.
     *
     * In future, if we need full checkout configuration, then we can alter that
     * here. It may need some changes in the react app too. But now, we are good
     * to go with only payment configuration.
     *
     * @return string
     */
    public function getConfig(): string
    {
        $checkoutConfig = $this->compositeConfigProvider->getConfig();
        $storeCode = $checkoutConfig['storeCode'];
        $checkoutConfig['payment']['restUrlPrefix'] = "/rest/$storeCode/V1/";

        $transport = new \Magento\Framework\DataObject([
            'checkoutConfig' => $checkoutConfig,
            'output' => [
                'storeCode' => $storeCode,
                'payment' => $checkoutConfig['payment'],
                'language' => $this->localeResolver->getLocale(),
                'currency' => $this->currencyProvider->getConfig(),
                'defaultCountryId' => $checkoutConfig['defaultCountryId'],
            ]
        ]);

        $this->eventManager->dispatch('hyva_react_checkout_config', ['transport' => $transport]);

        return $this->serializer->serialize($transport->getData('output'));
    }
}
