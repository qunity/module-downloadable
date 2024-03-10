<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Api\Data;

interface LinkInterface
{
    public const LINK_ID = 'link_id';
    public const IS_ONLINE_LINK = 'is_online_link';
    public const IS_ONLINE_SAMPLE = 'is_online_sample';

    /**
     * Get product Link ID
     *
     * @return int|null
     */
    public function getLinkId(): ?int;

    /**
     * Set product Link ID
     *
     * @param int $linkId
     * @return $this
     */
    public function setLinkId(int $linkId): self;

    /**
     * Get link Online flag
     *
     * @return bool|null
     */
    public function getIsOnlineLink(): ?bool;

    /**
     * Set link Online flag
     *
     * @param bool $isOnline
     * @return $this
     */
    public function setIsOnlineLink(bool $isOnline): self;

    /**
     * Get sample Online flag
     *
     * @return bool|null
     */
    public function getIsOnlineSample(): ?bool;

    /**
     * Set sample Online flag
     *
     * @param bool $isOnline
     * @return $this
     */
    public function setIsOnlineSample(bool $isOnline): self;
}
