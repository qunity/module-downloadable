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
    private const PLAYER_BLOCK_ALIAS = 'player';

    /**
     * Video player block if it's added to child blocks
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

        // TODO: fix a condition for displaying purchased products
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
     * Checking whether sample have Online flag
     *
     * @param MagentoLinkInterface $link
     * @return bool
     */
    public function isOnlineSample(MagentoLinkInterface $link): bool
    {
        return (bool) $this->getQunityLink($link)?->getIsOnlineSample();
    }

    /**
     * Get child player block if exist it
     *
     * @return VideoPlayer|null
     */
    private function getChildPlayerBlock(): ?VideoPlayer
    {
        if (!isset($this->videoPlayerBlock)) {
            $block = $this->getChildBlock(self::PLAYER_BLOCK_ALIAS);
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
        if (empty($extension)) {
            return null;
        }

        /** @var Link|null $qunity */
        $qunity = $extension->getQunity();
        if (empty($qunity)) {
            return null;
        }

        return $qunity;
    }
}
