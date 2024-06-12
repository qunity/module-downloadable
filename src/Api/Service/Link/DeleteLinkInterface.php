<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Api\Service\Link;

interface DeleteLinkInterface
{
    /**
     * Remove link record of downloadable product
     *
     * @param int $linkId
     * @return void
     */
    public function execute(int $linkId): void;
}
