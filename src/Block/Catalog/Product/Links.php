<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Block\Catalog\Product;

use Magento\Downloadable\Api\Data\LinkExtension;
use Magento\Downloadable\Api\Data\LinkInterface as MagentoLinkInterface;
use Magento\Downloadable\Block\Catalog\Product\Links as BaseBlockLinks;
use Qunity\Downloadable\Api\Data\LinkInterface as QunityLinkInterface;
use Qunity\Downloadable\Model\Data\Link;
use Qunity\Video\Api\Data\VideoPlayer\ConfigInterface;
use Qunity\Video\Block\VideoPlayer;

class Links extends BaseBlockLinks
{
    /**
     * TODO: описание...
     * @var VideoPlayer|null
     */
    private ?VideoPlayer $videoPlayerBlock;

    /**
     * Get child Video Player block and configure it by link info
     *
     * @param MagentoLinkInterface $link
     * @return VideoPlayer|null
     */
    public function getVideoPlayerBlock(MagentoLinkInterface $link): ?VideoPlayer
    {
        $videoPlayer = $this->getChildPlayerBlock();
        if (empty($videoPlayer)) {
            return null;
        }

        $data = [ // TODO: вытащить в сервис, в какой-нибудь модуль, типо Qunity_Sales или Qunity_Customer
            ConfigInterface::LINK_URL => $link->getSampleUrl(), // TODO: разделять по принципу "купил" / "не купил"
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
     * TODO: описание...
     *
     * @return VideoPlayer|null
     */
    private function getChildPlayerBlock(): ?VideoPlayer
    {
        if (!isset($this->videoPlayerBlock)) {
            $block = $this->getChildBlock('player');
            $this->videoPlayerBlock = $block instanceof VideoPlayer ? $block : null;
        }

        return $this->videoPlayerBlock;
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
