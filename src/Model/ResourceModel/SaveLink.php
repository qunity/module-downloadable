<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Model\ResourceModel;

use Qunity\Downloadable\Api\Data\LinkInterface;

class SaveLink extends AbstractLink
{
    /**
     * Save link instance of downloadable product
     *
     * @param LinkInterface $link
     * @return void
     */
    public function execute(LinkInterface $link): void
    {
        $this->getConnection()->insertOnDuplicate(
            $this->getTableName(),
            [
                LinkInterface::LINK_ID => $link->getLinkId(),
                LinkInterface::IS_ONLINE_LINK => $link->getIsOnlineLink(),
                LinkInterface::IS_ONLINE_SAMPLE => $link->getIsOnlineSample(),
            ],
            [
                LinkInterface::IS_ONLINE_LINK,
                LinkInterface::IS_ONLINE_SAMPLE,
            ]
        );
    }
}
