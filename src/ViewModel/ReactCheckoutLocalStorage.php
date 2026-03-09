<?php
declare(strict_types=1);

namespace FriendsOfHyva\ReactCheckout\ViewModel;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Customer\Model\Address;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Integration\Model\Oauth\TokenFactory as TokenModelFactory;

class ReactCheckoutLocalStorage implements ArgumentInterface
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var CustomerSession
     */
    private $customerSession;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var TokenModelFactory
     */
    private $tokenModelFactory;

    public function __construct(
        TokenModelFactory $tokenFactory,
        SerializerInterface $serializer,
        CheckoutSession $checkoutSession,
        CustomerSession $customerSession
    ) {
        $this->serializer = $serializer;
        $this->tokenModelFactory = $tokenFactory;
        $this->checkoutSession = $checkoutSession;
        $this->customerSession = $customerSession;
    }

    public function getLocalStorageConfig(): string
    {
        $config = [
            'customerIsLoggedIn' => $this->isCustomerLoggedIn(),
            'isBillingSameAsShipping' => $this->isAddressSame(),
            'quoteContainsBilling' => $this->hasQuoteContainsValidBillingAddress(),
            'quoteContainsShipping' => $this->hasQuoteContainsValidShippingAddress(),
            'defaultBillingAddressId' => $this->getCustomerDefaultBillingAddressId(),
            'defaultShippingAddressId' => $this->getCustomerDefaultShippingAddressId(),
            'quoteBillingAddressCustomerId' => $this->getQuoteBillingAddressCustomerId(),
            'quoteShippingAddressCustomerId' => $this->getQuoteShippingAddressCustomerId(),
        ];

        return $this->serializer->serialize($config);
    }

    public function getCustomerToken(): string
    {
        if (!$this->isCustomerLoggedIn()) {
            return '';
        }

        $customerId = (int)$this->customerSession->getCustomer()->getId();

        return $this->tokenModelFactory->create()->createCustomerToken($customerId)->getToken();
    }

    private function isCustomerLoggedIn(): bool
    {
        return $this->customerSession->isLoggedIn() && $this->getCustomer()->getId();
    }

    private function getCustomerDefaultBillingAddressId(): ?int
    {
        $defaultBillingAddress = $this->getCustomer()->getDefaultBillingAddress();

        return $defaultBillingAddress instanceof Address
            ? (int)$defaultBillingAddress->getId()
            : null;
    }

    private function getCustomerDefaultShippingAddressId(): ?int
    {
        $defaultShippingAddress = $this->getCustomer()->getDefaultShippingAddress();

        return $defaultShippingAddress instanceof Address
            ? (int)$defaultShippingAddress->getId()
            : null;
    }

    private function hasQuoteContainsValidBillingAddress(): bool
    {
        $errors = $this->checkoutSession->getQuote()->getBillingAddress()->validate();

        return $errors === true;
    }

    private function hasQuoteContainsValidShippingAddress(): bool
    {
        $errors = $this->checkoutSession->getQuote()->getShippingAddress()->validate();

        return $errors === true;
    }

    private function getQuoteBillingAddressCustomerId(): ?int
    {
        $customerAddressId = $this->checkoutSession->getQuote()->getBillingAddress()->getCustomerAddressId();

        return $customerAddressId !== null
            ? (int)$customerAddressId
            : null;
    }

    private function getQuoteShippingAddressCustomerId(): ?int
    {
        $customerAddressId = $this->checkoutSession->getQuote()->getShippingAddress()->getCustomerAddressId();

        return $customerAddressId !== null
            ? (int)$customerAddressId
            : null;
    }

    private function getCustomer(): Customer
    {
        return $this->customerSession->getCustomer();
    }

    private function isAddressSame(): bool
    {
        return (bool) $this->checkoutSession->getQuote()->getShippingAddress()->getSameAsBilling();
    }
}
