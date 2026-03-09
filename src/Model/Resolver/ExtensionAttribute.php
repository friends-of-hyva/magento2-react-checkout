<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace FriendsOfHyva\ReactCheckout\Model\Resolver;

use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

/**
 * ExtensionAttribute field resolver, used for GraphQL request processing.
 * Turns any custom extension attribute into a GQL field.
 */
class ExtensionAttribute implements ResolverInterface
{
    /**
     * @inheritdoc
     */
    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    )
    {
        return $value['extension_attributes'][$field->getName()] ?? null;
    }
}
