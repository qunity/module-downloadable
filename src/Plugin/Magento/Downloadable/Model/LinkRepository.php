<?php /** @noinspection PluginInspection */

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
