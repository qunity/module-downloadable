<?php

/**
 * @noinspection PhpUnused
 * @noinspection PluginInspection
 * @noinspection PhpUnusedParameterInspection
 */

declare(strict_types=1);

namespace Qunity\Downloadable\Plugin\Magento\Downloadable\Model\LinkRepository;

use Magento\Downloadable\Api\Data\LinkInterface;
use Magento\Downloadable\Model\LinkRepository as Target;

class ClearUrlLinks
{
    /**
     * Clear URL links before saving
     *
     * @param Target $subject
     * @param string $sku
     * @param LinkInterface $link
     *
     * @return array
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSave(Target $subject, string $sku, LinkInterface $link): array
    {
        if (!empty($url = $link->getLinkUrl())) {
            $link->setLinkUrl(trim($url));
        }

        if (!empty($url = $link->getSampleUrl())) {
            $link->setSampleUrl(trim($url));
        }

        return [$sku, $link];
    }
}
