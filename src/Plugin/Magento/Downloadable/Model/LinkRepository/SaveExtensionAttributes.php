<?php

/**
 * @noinspection PluginInspection
 * @noinspection PhpUnusedParameterInspection
 */

declare(strict_types=1);

namespace Qunity\Downloadable\Plugin\Magento\Downloadable\Model\LinkRepository;

use Magento\Downloadable\Api\Data\LinkExtension;
use Magento\Downloadable\Api\Data\LinkInterface;
use Magento\Downloadable\Model\LinkRepository as Target;
use Qunity\Downloadable\Api\Service\Link\DeleteLinkInterface;
use Qunity\Downloadable\Api\Service\Link\SaveLinkInterface;
use Qunity\Downloadable\Model\Data\Link;

class SaveExtensionAttributes
{
    /**
     * @param SaveLinkInterface $saveLink
     * @param DeleteLinkInterface $deleteLink
     */
    public function __construct(
        private readonly SaveLinkInterface $saveLink,
        private readonly DeleteLinkInterface $deleteLink
    ) {
        // ...
    }

    /**
     * Save information about associate Qunity extension to Link
     *
     * @param Target $subject
     * @param int $linkId
     * @param string $sku
     * @param LinkInterface $link
     *
     * @return int
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterSave(Target $subject, int $linkId, string $sku, LinkInterface $link): int
    {
        /** @var LinkExtension|null $extension */
        $extension = $link->getExtensionAttributes();
        if (!$extension) {
            return $linkId;
        }

        /** @var Link|null $qunity */
        $qunity = $extension->getQunity();
        if (!$qunity) {
            $this->deleteLink->execute($linkId);

            return $linkId;
        }

        $this->saveLink->execute($qunity->setLinkId($linkId));

        return $linkId;
    }
}
