<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Model\ResourceModel;

use Qunity\Downloadable\Api\Data\LinkInterface;
use Qunity\Downloadable\Api\Service\Link\SaveLinkInterface;

class SaveLink extends AbstractLink implements SaveLinkInterface
{
    /**
     * @inheritDoc
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
