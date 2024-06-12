<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Model\ResourceModel;

use Qunity\Downloadable\Api\Data\LinkInterface;
use Qunity\Downloadable\Api\Service\Link\DeleteLinkInterface;

class DeleteLink extends AbstractLink implements DeleteLinkInterface
{
    /**
     * @inheritDoc
     */
    public function execute(int $linkId): void
    {
        $this->getConnection()->delete(
            $this->getTableName(),
            [LinkInterface::LINK_ID . ' = ?' => $linkId]
        );
    }
}
