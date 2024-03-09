<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Plugin\Magento\Downloadable\Model;

use Magento\Downloadable\Api\Data\LinkExtension;
use Magento\Downloadable\Api\Data\LinkInterface;
use Magento\Downloadable\Model\LinkRepository as Target;
use Qunity\Downloadable\Model\Data\Link;
use Qunity\Downloadable\Model\ResourceModel\DeleteLink;
use Qunity\Downloadable\Model\ResourceModel\SaveLink;

class LinkRepository
{
    /**
     * @param SaveLink $saveLink
     * @param DeleteLink $deleteLink
     */
    public function __construct(
        private readonly SaveLink $saveLink,
        private readonly DeleteLink $deleteLink
    ) {
        // ...
    }

    /**
     * Save information about associate qunity to link
     *
     * @param Target $subject
     * @param int $result
     * @param string $sku
     * @param LinkInterface $link
     *
     * @return int
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(Target $subject, int $result, string $sku, LinkInterface $link): int
    {
        /** @var LinkExtension|null $extension */
        $extension = $link->getExtensionAttributes();
        if (!$extension) {
            return $result;
        }

        /** @var Link|null $qunity */
        $qunity = $extension->getQunity();
        if (!$qunity) {
            $this->deleteLink->execute($result);

            return $result;
        }

        $this->saveLink->execute($qunity->setLinkId($result));

        return $result;
    }
}
