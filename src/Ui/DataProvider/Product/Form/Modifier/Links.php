<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Downloadable\Api\Data\LinkInterface;
use Magento\Downloadable\Model\Product\Type;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Form;

class Links extends AbstractModifier
{
    private const IS_ONLINE_LINK_KEY = 'extension_attribute_qunity_is_online_link';
    private const IS_ONLINE_SAMPLE_KEY = 'extension_attribute_qunity_is_online_sample';
    private const INPUT_VIDEO_URL_ELEMENT_COMPONENT = 'Qunity_Downloadable/js/form/element/input-video-url';

    /**
     * @param ArrayManager $arrayManager
     * @param LocatorInterface $locator
     */
    public function __construct(
        private readonly ArrayManager $arrayManager,
        private readonly LocatorInterface $locator
    ) {
        // ...
    }

    /**
     * @inheritDoc
     */
    public function modifyData(array $data): array
    {
        $this->addOnlineFlagData($data);

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function modifyMeta(array $meta): array
    {
        $this->addOnlineFlagMeta($meta);

        return $meta;
    }

    /**
     * Add data information for Online flag
     *
     * @param array $data
     * @return void
     */
    private function addOnlineFlagData(array &$data): void
    {
        /** @var Product $product */
        $product = $this->locator->getProduct();
        if ($product->getTypeId() != Type::TYPE_DOWNLOADABLE) {
            return;
        }

        /** @var Type $productType */
        $productType = $product->getTypeInstance();

        /** @var LinkInterface[] $productLinks */
        $productLinks = $productType->getLinks($product);

        foreach ($data[$product->getId()]['downloadable']['link'] as &$link) {
            $linkId = $link['link_id'];
            if (!isset($productLinks[$linkId])) {
                continue;
            }

            /** @noinspection PhpUndefinedMethodInspection */
            $qunity = $productLinks[$linkId]->getExtensionAttributes()?->getQunity();
            if (empty($qunity)) {
                continue;
            }

            $link[self::IS_ONLINE_LINK_KEY] = (string) $qunity->getIsOnlineLink();
            $link[self::IS_ONLINE_SAMPLE_KEY] = (string) $qunity->getIsOnlineSample();
        }
    }

    /**
     * Add meta information about Online flag
     *
     * @param array $meta
     * @return void
     */
    private function addOnlineFlagMeta(array &$meta): void
    {
        $delimiter = ArrayManager::DEFAULT_PATH_DELIMITER;
        $getPath = fn (array $parts): string => implode($delimiter, $parts);

        $path = $getPath([
            $getPath(['downloadable', 'children']),
            $getPath(['container_links', 'children']),
            $getPath(['link', 'children']),
            $getPath(['record', 'children']),
        ]);

        $containerFile['children'] = $this->getContainerChildren(
            self::IS_ONLINE_LINK_KEY,
            'type',
            'link_url'
        );

        $containerSample['children'] = $this->getContainerChildren(
            self::IS_ONLINE_SAMPLE_KEY,
            'sample_type',
            'sample_url'
        );

        $meta = $this->arrayManager->merge($path, $meta, [
            'container_file' => $containerFile,
            'container_sample' => $containerSample,
        ]);
    }

    /**
     * Get elements configuration for cooperation with Online flag
     *
     * @param string $onlineFlagElement
     * @param string $typeElement
     * @param string $urlElement
     *
     * @return array
     */
    private function getContainerChildren(
        string $onlineFlagElement,
        string $typeElement,
        string $urlElement
    ): array {
        return [
            $onlineFlagElement => ['arguments' => ['data' => ['config' => [
                'formElement' => Form\Element\Checkbox::NAME,
                'componentType' => Form\Field::NAME,
                'dataType' => Form\Element\DataType\Number::NAME,
                'dataScope' => $onlineFlagElement,
                'description' => __('Online'),
                'valueMap' => ['false' => '0', 'true' => '1'],
            ]]]],
            $typeElement => ['arguments' => ['data' => ['config' => [
                'isOnline' => $onlineFlagElement,
            ]]]],
            $urlElement => ['arguments' => ['data' => ['config' => [
                'component' => self::INPUT_VIDEO_URL_ELEMENT_COMPONENT,
                'videoValidation' => [
                    'validate-youtube-url' => [$onlineFlagElement => '1'],
                    'validate-youtube-video-metadata' => [$onlineFlagElement => '1'],
                ],
                'validation' => ['no-whitespace' => true],
            ]]]],
        ];
    }
}
