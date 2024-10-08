<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Model\Data;

use Magento\Framework\DataObject;
use Qunity\Downloadable\Api\Data\LinkInterface;

class Link extends DataObject implements LinkInterface
{
    /**
     * @inheritDoc
     */
    public function getLinkId(): ?int
    {
        return $this->hasData(self::LINK_ID)
            ? (int) $this->getData(self::LINK_ID) : null;
    }

    /**
     * @inheritDoc
     */
    public function setLinkId(int $linkId): LinkInterface
    {
        return $this->setData(self::LINK_ID, $linkId);
    }

    /**
     * @inheritDoc
     */
    public function getIsOnlineLink(): ?bool
    {
        return $this->hasData(self::IS_ONLINE_LINK)
            ? (bool) $this->getData(self::IS_ONLINE_LINK) : null;
    }

    /**
     * @inheritDoc
     */
    public function setIsOnlineLink(bool $isOnline): LinkInterface
    {
        return $this->setData(self::IS_ONLINE_LINK, $isOnline);
    }

    /**
     * @inheritDoc
     */
    public function getIsOnlineSample(): ?bool
    {
        return $this->hasData(self::IS_ONLINE_SAMPLE)
            ? (bool) $this->getData(self::IS_ONLINE_SAMPLE) : null;
    }

    /**
     * @inheritDoc
     */
    public function setIsOnlineSample(bool $isOnline): LinkInterface
    {
        return $this->setData(self::IS_ONLINE_SAMPLE, $isOnline);
    }
}
