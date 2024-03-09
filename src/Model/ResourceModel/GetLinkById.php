<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Model\ResourceModel;

use Qunity\Downloadable\Api\Data\LinkInterface;

class GetLinkById extends AbstractLink
{
    /**
     * Get link instance by id
     *
     * @param int $linkId
     * @return LinkInterface|null
     */
    public function execute(int $linkId): ?LinkInterface
    {
        $connection = $this->getConnection();
        $select = $connection->select()->from($this->getTableName())
            ->where(LinkInterface::LINK_ID . ' = ?', $linkId)->limit(1);

        if (!($data = $connection->fetchRow($select))) {
            return null;
        }

        return $this->getLinkFactory()->create()
            ->setLinkId((int) $data[LinkInterface::LINK_ID])
            ->setIsOnline((bool) $data[LinkInterface::IS_ONLINE]);
    }
}
