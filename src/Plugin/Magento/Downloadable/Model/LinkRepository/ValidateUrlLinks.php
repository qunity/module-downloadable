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
use Magento\Framework\Exception\LocalizedException;
use Qunity\Video\Api\Service\YouTube\GetMetadataByUrlInterface;

class ValidateUrlLinks
{
    /**
     * @param GetMetadataByUrlInterface $getMetadataByUrl
     */
    public function __construct(
        private readonly GetMetadataByUrlInterface $getMetadataByUrl
    ) {
        // ...
    }

    /**
     * Validate URL if it is video link for downloadable product
     *
     * @param Target $subject
     * @param string $sku
     * @param LinkInterface $link
     *
     * @return array
     * @throws LocalizedException
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeSave(Target $subject, string $sku, LinkInterface $link): array
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $qunity = $link->getExtensionAttributes()?->getQunity();
        if (!$qunity) {
            return [$sku, $link];
        }

        if ($qunity->getIsOnlineLink()) {
            $this->getYoutubeMetadata($link->getLinkUrl());
        }

        if ($qunity->getIsOnlineSample()) {
            $this->getYoutubeMetadata($link->getSampleUrl());
        }

        return [$sku, $link];
    }

    /**
     * Get metadata for YouTube video
     *
     * @param string $url
     * @return void
     *
     * @throws LocalizedException
     */
    private function getYoutubeMetadata(string $url): void
    {
        try {
            $this->getMetadataByUrl->execute($url);
        } catch (LocalizedException) {
            /** @noinspection HtmlUnknownTarget */
            $exceptionMessage = 'Failed to get video metadata from YouTube: <a href="%1" target="_blank">%1</a>';

            throw new LocalizedException(__($exceptionMessage, $url));
        }
    }
}
