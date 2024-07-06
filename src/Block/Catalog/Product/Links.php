<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Block\Catalog\Product;

use Magento\Downloadable\Api\Data\LinkInterface as MagentoLinkInterface;
use Magento\Downloadable\Block\Catalog\Product\Links as BaseBlockLinks;
use Qunity\Downloadable\Api\Data\LinkInterface as QunityLinkInterface;
use Qunity\Video\Api\Data\VideoPlayer\ConfigInterface;
use Qunity\Video\Block\VideoPlayer;

class Links extends BaseBlockLinks
{
    private const VIDEO_PLAYER_BLOCK_ALIAS = 'player';

    /**
     * Video Player block if it's added to child blocks
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

        /**
         * TODO:
         *  - fix a condition for displaying purchased products
         *  - pull it into the service, into some module, for example Qunity_Sales or Qunity_Customer
         *  - divide according to the principle “bought” / “not bought”
         */
        $data = [
            ConfigInterface::TITLE => $link->getTitle(),
            ConfigInterface::LINK_URL => $link->getSampleUrl(),
            'context' => [
                'product_id' => $this->getProduct()->getId(),
                'link_id' => $link->getId(),
            ],
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
     * Get child Video Player block if exist it in child blocks
     *
     * @return VideoPlayer|null
     */
    private function getChildPlayerBlock(): ?VideoPlayer
    {
        if (!isset($this->videoPlayerBlock)) {
            $block = $this->getChildBlock(self::VIDEO_PLAYER_BLOCK_ALIAS);
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
        /** @noinspection PhpUndefinedMethodInspection */
        return $link->getExtensionAttributes()?->getQunity();
    }
}
