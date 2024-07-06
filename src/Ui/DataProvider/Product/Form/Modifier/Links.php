<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Downloadable\Api\Data\LinkExtension;
use Magento\Downloadable\Api\Data\LinkInterface;
use Magento\Downloadable\Model\Product\Type;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Form;
use Qunity\Downloadable\Model\Data\Link;

class Links extends AbstractModifier
{
    private const IS_ONLINE_LINK_KEY = 'extension_attribute_qunity_is_online_link';
    private const IS_ONLINE_SAMPLE_KEY = 'extension_attribute_qunity_is_online_sample';
    private const INPUT_VIDEO_URL_ELEMENT_COMPONENT = 'Qunity_Downloadable/js/form/element/input-video-url';

    /**
     * @param LocatorInterface $locator
     * @param ArrayManager $arrayManager
     */
    public function __construct(
        private readonly LocatorInterface $locator,
        private readonly ArrayManager $arrayManager
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

            /** @var LinkExtension|null $extension */
            $extension = $productLinks[$linkId]->getExtensionAttributes();
            if (empty($extension)) {
                continue;
            }

            /** @var Link|null $qunity */
            $qunity = $extension->getQunity();
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

        $containerFile['children'] = [
            self::IS_ONLINE_LINK_KEY => $this->getOnlineFlag(self::IS_ONLINE_LINK_KEY),
            'type' => ['arguments' => ['data' => ['config' => [
                'isOnline' => self::IS_ONLINE_LINK_KEY,
            ]]]],
            'link_url' => ['arguments' => ['data' => ['config' => [
                'component' => self::INPUT_VIDEO_URL_ELEMENT_COMPONENT,
                'videoValidation' => [
                    'validate-youtube-url' => [self::IS_ONLINE_LINK_KEY => '1'],
                    'validate-youtube-video-metadata' => [self::IS_ONLINE_LINK_KEY => '1'],
                ],
                'validation' => ['no-whitespace' => true],
            ]]]],
        ];

        $containerSample['children'] = [
            self::IS_ONLINE_SAMPLE_KEY => $this->getOnlineFlag(self::IS_ONLINE_SAMPLE_KEY),
            'sample_type' => ['arguments' => ['data' => ['config' => [
                'isOnline' => self::IS_ONLINE_SAMPLE_KEY,
            ]]]],
            'sample_url' => ['arguments' => ['data' => ['config' => [
                'component' => self::INPUT_VIDEO_URL_ELEMENT_COMPONENT,
                'videoValidation' => [
                    'validate-youtube-url' => [self::IS_ONLINE_SAMPLE_KEY => '1'],
                    'validate-youtube-video-metadata' => [self::IS_ONLINE_SAMPLE_KEY => '1'],
                ],
                'validation' => ['no-whitespace' => true],
            ]]]],
        ];

        $meta = $this->arrayManager->merge($path, $meta, [
            'container_file' => $containerFile,
            'container_sample' => $containerSample,
        ]);
    }

    /**
     * Get configuration of Online flag
     *
     * @param string $dataScope
     * @return array
     */
    private function getOnlineFlag(string $dataScope): array
    {
        $result['arguments']['data']['config'] = [
            'formElement' => Form\Element\Checkbox::NAME,
            'componentType' => Form\Field::NAME,
            'dataType' => Form\Element\DataType\Number::NAME,
            'dataScope' => $dataScope,
            'description' => __('Online'),
            'valueMap' => ['false' => '0', 'true' => '1'],
        ];

        return $result;
    }
}
