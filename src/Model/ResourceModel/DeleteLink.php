<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Model\ResourceModel;

use Qunity\Downloadable\Api\Data\LinkInterface;

class DeleteLink extends AbstractLink
{
    /**
     * Remove Link instance
     *
     * @param int $linkId
     * @return void
     */
    public function execute(int $linkId): void
    {
        $this->getConnection()->delete(
            $this->getTableName(),
            [LinkInterface::LINK_ID . ' = ?' => $linkId]
        );
    }
}
