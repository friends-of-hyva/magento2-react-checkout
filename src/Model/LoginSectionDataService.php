<?php
declare(strict_types=1);

namespace FriendsOfHyva\ReactCheckout\Model;

use Magento\Checkout\CustomerData\Cart as CartCustomerData;
use Magento\Customer\CustomerData\Customer as CustomerCustomerData;

/**
 * This is used to provide necessary section data directly in the ajax login response
 * without an additional AJAX call
 */
class LoginSectionDataService
{
    /**
     * @var \Magento\Checkout\CustomerData\Cart
     */
    private $cartCustomerData;

    /**
     * @var \Magento\Customer\CustomerData\Customer
     */
    private $customerCustomerData;

    /**
     * LoginSectionDataService constructor.
     *
     * @param  \Magento\Checkout\CustomerData\Cart  $cartCustomerData
     * @param  \Magento\Customer\CustomerData\Customer  $customerCustomerData
     */
    public function __construct(
        CartCustomerData $cartCustomerData,
        CustomerCustomerData $customerCustomerData
    ) {
        $this->cartCustomerData = $cartCustomerData;
        $this->customerCustomerData = $customerCustomerData;
    }

    /**
     * We just need cart and customer section data.
     *
     * Rest of section data we can force load from the frontend side.
     *
     * @return array
     */
    public function getSectionData(): array
    {
        $cartData = $this->cartCustomerData->getSectionData();
        $customerData = $this->customerCustomerData->getSectionData();

        return [
            'cart' => $cartData,
            'customer' => $customerData,
        ];
    }
}
