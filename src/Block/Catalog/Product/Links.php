<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Block\Catalog\Product;

use Magento\Downloadable\Api\Data\LinkExtension;
use Magento\Downloadable\Api\Data\LinkInterface as MagentoLinkInterface;
use Magento\Downloadable\Block\Catalog\Product\Links as BaseLinks;
use Qunity\Downloadable\Api\Data\LinkInterface as QunityLinkInterface;
use Qunity\Downloadable\Model\Data\Link;
use Qunity\Video\Api\Data\VideoPlayer\ConfigInterface;
use Qunity\Video\Block\VideoPlayer;

class Links extends BaseLinks
{
    /**
     * Get child Video Player block and configure it by link info
     *
     * @param MagentoLinkInterface $link
     * @return VideoPlayer|null
     */
    public function getVideoPlayerBlock(MagentoLinkInterface $link): ?VideoPlayer
    {
        /** @var VideoPlayer $videoPlayer */
        $videoPlayer = $this->getChildBlock('player');
        if (empty($videoPlayer)) { // @phpstan-ignore-line
            return null;
        }

        $data = [
            ConfigInterface::LINK_URL => $link->getSampleUrl(),
            'product_id' => $this->getProduct()->getId(),
            'link_id' => $link->getId(),
            'title' => $link->getTitle(),
        ];

        return $videoPlayer->update($data);
    }

    /**
     * Checking whether link have Online flag
     *
     * @param MagentoLinkInterface $link
     * @return bool
     */
    public function isOnlineLink(MagentoLinkInterface $link): bool
    {
        return (bool) $this->getQunityLink($link)?->getIsOnlineLink();
    }

    /**
     * Checking whether link sample have Online flag
     *
     * @param MagentoLinkInterface $link
     * @return bool
     */
    public function isOnlineSample(MagentoLinkInterface $link): bool
    {
        return (bool) $this->getQunityLink($link)?->getIsOnlineSample();
    }

    /**
     * Get extension Qunity attributes object
     *
     * @param MagentoLinkInterface $link
     * @return QunityLinkInterface|null
     */
    private function getQunityLink(MagentoLinkInterface $link): ?QunityLinkInterface
    {
        /** @var LinkExtension|null $extension */
        $extension = $link->getExtensionAttributes();
        if (!$extension) {
            return null;
        }

        /** @var Link|null $qunity */
        $qunity = $extension->getQunity();
        if (!$qunity) {
            return null;
        }

        return $qunity;
    }
}
