<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Api\Data;

interface LinkInterface
{
    public const LINK_ID = 'link_id';
    public const IS_ONLINE = 'is_online';

    /**
     * Get product link id
     *
     * @return int|null
     */
    public function getLinkId(): ?int;

    /**
     * Set product link id
     *
     * @param int $linkId
     * @return $this
     */
    public function setLinkId(int $linkId): self;

    /**
     * Get link online flag
     *
     * @return bool|null
     */
    public function getIsOnline(): ?bool;

    /**
     * Set link online flag
     *
     * @param bool $isOnline
     * @return $this
     */
    public function setIsOnline(bool $isOnline): self;
}
