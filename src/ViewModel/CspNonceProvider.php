<?php

declare(strict_types=1);

namespace FriendsOfHyva\ReactCheckout\ViewModel;

use Exception;
use Magento\Framework\View\Element\Block\ArgumentInterface;

class CspNonceProvider implements ArgumentInterface
{
    public function __construct(
        private readonly \Magento\Csp\Helper\CspNonceProvider $cspNonceProvider,
    ) {
    }

    public function getNonce(): string
    {
        try {
            return $this->cspNonceProvider->generateNonce();
        } catch (Exception) {
            return '';
        }
    }
}
