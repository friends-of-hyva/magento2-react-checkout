<?php

declare(strict_types=1);

namespace FriendsOfHyva\ReactCheckout\Controller\ReactCheckout;

use Magento\Backend\Model\View\Result\Page;
use Magento\Checkout\Controller\Action;
use Magento\Checkout\Helper\Data;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface as HttpGetActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;

class Index extends Action implements HttpGetActionInterface
{
    /**
     * @var Data
     */
    private $checkoutHelper;

    /**
     * @param Context $context
     * @param Session $customerSession
     * @param CustomerRepositoryInterface $customerRepository
     * @param AccountManagementInterface $accountManagement
     * @param Data $checkoutHelper
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        CustomerRepositoryInterface $customerRepository,
        AccountManagementInterface $accountManagement,
        Data $checkoutHelper
    ) {
        $this->checkoutHelper = $checkoutHelper;
        parent::__construct(
            $context,
            $customerSession,
            $customerRepository,
            $accountManagement
        );
    }

    /**
     * Checkout page
     *
     * @return ResultInterface
     */
    public function execute(): ResultInterface
    {
        //phpcs:ignore MEQP2.Classes.ObjectManager.ObjectManagerFound
        $checkoutHelper = $this->checkoutHelper;
        if (!$checkoutHelper->canOnepageCheckout()) {
            $this->messageManager->addErrorMessage(__('One-page checkout is turned off.'));
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        $quote = $checkoutHelper->getQuote();
        if (!$quote->hasItems() || !$quote->validateMinimumAmount()) {
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        if (!$this->_customerSession->isLoggedIn() && !$checkoutHelper->isAllowedGuestCheckout($quote)) {
            $this->messageManager->addErrorMessage(__('Guest checkout is disabled.'));
            return $this->resultRedirectFactory->create()->setPath('checkout/cart');
        }

        $this->_customerSession->regenerateId();

        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        $resultPage->getConfig()->getTitle()->set(__('Checkout'));

        return $resultPage;
    }
}
