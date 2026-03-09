<?php
declare(strict_types=1);

namespace FriendsOfHyva\ReactCheckout\Result;

use Magento\Framework\Translate\InlineInterface;
use Magento\Framework\Serialize\Serializer\Json as JsonSerializer;

class Json extends \Magento\Framework\Controller\Result\Json
{
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json|null
     */
    private $jsonSerializer;

    /**
     * Json constructor.
     *
     * @param  \Magento\Framework\Translate\InlineInterface  $translateInline
     * @param  \Magento\Framework\Serialize\Serializer\Json  $serializer
     */
    public function __construct(
        InlineInterface $translateInline,
        JsonSerializer $serializer
    ) {
        $this->jsonSerializer = $serializer;
        parent::__construct($translateInline, $serializer);
    }

    /**
     * There is no way we can access the json data set to the original Json object.
     *
     * Adding this method in order collect the data.
     *
     * @return array|bool|float|int|mixed|string|null
     */
    public function getData()
    {
        if (!$this->json) {
            return $this->json;
        }

        return $this->jsonSerializer->unserialize($this->json);
    }
}
