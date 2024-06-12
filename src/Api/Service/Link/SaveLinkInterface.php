<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Api\Service\Link;

use Qunity\Downloadable\Api\Data\LinkInterface;

interface SaveLinkInterface
{
    /**
     * Save link record of downloadable product
     *
     * @param LinkInterface $link
     * @return void
     */
    public function execute(LinkInterface $link): void;
}
