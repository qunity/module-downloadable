<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;
use Qunity\Downloadable\Api\Data\LinkInterfaceFactory;

abstract class AbstractLink
{
    public const TABLE_NAME = 'qunity_downloadable_link';

    /**
     * @param ResourceConnection $connection
     * @param LinkInterfaceFactory $linkFactory
     */
    public function __construct(
        private readonly ResourceConnection $connection,
        private readonly LinkInterfaceFactory $linkFactory
    ) {
        // ...
    }

    /**
     * Get resource table name
     *
     * @return string
     */
    protected function getTableName(): string
    {
        return $this->connection->getTableName(self::TABLE_NAME);
    }

    /**
     * Get connection to resource
     *
     * @return AdapterInterface
     */
    protected function getConnection(): AdapterInterface
    {
        return $this->connection->getConnection();
    }

    /**
     * Get factory of link instance
     *
     * @return LinkInterfaceFactory
     */
    protected function getLinkFactory(): LinkInterfaceFactory
    {
        return $this->linkFactory;
    }
}
