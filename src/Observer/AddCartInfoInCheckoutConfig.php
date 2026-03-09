<?php
declare(strict_types=1);

namespace FriendsOfHyva\ReactCheckout\Observer;

use Exception;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Serialize\SerializerInterface;
use Magento\Quote\Model\QuoteIdToMaskedQuoteIdInterface;
use Psr\Log\LoggerInterface;

/**
 * An example to update checkout config data via observer.
 */
class AddCartInfoInCheckoutConfig implements ObserverInterface
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var QuoteIdToMaskedQuoteIdInterface
     */
    private $quoteIdToMaskedQuoteId;

    public function __construct(
        LoggerInterface $logger,
        SerializerInterface $serializer,
        CheckoutSession $checkoutSession,
        QuoteIdToMaskedQuoteIdInterface $quoteIdToMaskedQuoteId
    ) {
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->checkoutSession = $checkoutSession;
        $this->quoteIdToMaskedQuoteId = $quoteIdToMaskedQuoteId;
    }

    /**
     * Adding cart id as part of checkout config.
     *
     * There are some occasions where browser local storage won't hold the cart id.
     * This may be a browser specific problem or in some cases, there is an error
     * while yield customer/section/load request. In such cases, we can rely upon
     * the mask id passed via checkout config.
     */
    public function execute(Observer $observer): void
    {
        $cartId = (int)$this->checkoutSession->getQuoteId();

        if (!$cartId) {
            return;
        }

        try {
            $transport = $observer->getTransport();
            $outputData = $transport->getOutput();
            $cartMaskId = $this->quoteIdToMaskedQuoteId->execute($cartId);
            $outputData['cartMaskedId'] = $cartMaskId;
            $transport->setOutput($outputData);
        } catch (Exception $exception) {
            $this->logger->info('Adding cartId to checkoutConfig failed.', [$exception]);
        }
    }
}
