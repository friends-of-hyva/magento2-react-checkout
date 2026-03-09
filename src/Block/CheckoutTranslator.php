<?php
declare(strict_types=1);

namespace FriendsOfHyva\ReactCheckout\Block;

use Magento\Framework\View\Element\Template;

class CheckoutTranslator extends Template
{
    public function getTranslations(): string
    {
        $translations = $this->getCheckoutTranslations();

        if (!isset($translations)) {
            return json_encode([]);
        }

        $stringsTranslated = [];

        foreach ($translations as $translatableItem) {
            $stringCollection  = str_getcsv($translatableItem);

            foreach ($stringCollection as $stringToBeTranslated) {
                $stringsTranslated[$stringToBeTranslated] = __($stringToBeTranslated);
            }
        }

        return json_encode($stringsTranslated);
    }
}
