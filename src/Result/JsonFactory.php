<?php
declare(strict_types=1);

namespace FriendsOfHyva\ReactCheckout\Result;

use Magento\Framework\ObjectManagerInterface;

class JsonFactory extends \Magento\Framework\Controller\Result\JsonFactory
{
    /**
     * Providing a custom Json instance here.
     *
     * @param  \Magento\Framework\ObjectManagerInterface  $objectManager
     * @param  string  $instanceName
     */
    // phpcs:ignore Generic.CodeAnalysis.UselessOverridingMethod.Found
    public function __construct(
        ObjectManagerInterface $objectManager,
        $instanceName = Json::class
    ) {
        parent::__construct($objectManager, $instanceName);
    }
}
