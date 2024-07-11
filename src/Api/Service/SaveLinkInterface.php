<?php

/**
 * @noinspection PhpUnnecessaryFullyQualifiedNameInspection
 */

declare(strict_types=1);

namespace Qunity\Downloadable\Api\Service;

use Qunity\Downloadable\Api\Data\LinkInterface;

interface SaveLinkInterface
{
    /**
     * Save link record of downloadable product
     *
     * @param \Qunity\Downloadable\Api\Data\LinkInterface $link
     * @return void
     */
    public function execute(LinkInterface $link): void;
}
