<?php

declare(strict_types=1);

namespace Qunity\Downloadable\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Locator\LocatorInterface;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\AbstractModifier;
use Magento\Config\Model\Config\Source\Yesno;
use Magento\Downloadable\Api\Data\LinkExtension;
use Magento\Downloadable\Api\Data\LinkInterface;
use Magento\Downloadable\Model\Product\Type;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Ui\Component\Form;
use Qunity\Downloadable\Model\Data\Link;

class Links extends AbstractModifier
{
    private const IS_ONLINE_KEY = 'extension_attribute_qunity_is_online';

    /**
     * @param LocatorInterface $locator
     * @param ArrayManager $arrayManager
     * @param Yesno $yesno
     */
    public function __construct(
        private readonly LocatorInterface $locator,
        private readonly ArrayManager $arrayManager,
        private readonly Yesno $yesno
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
     * Add data information for Online flag column
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
            if (!$extension) {
                continue;
            }

            /** @var Link|null $qunity */
            $qunity = $extension->getQunity();
            if (!$qunity) {
                continue;
            }

            $link[self::IS_ONLINE_KEY] = (int) $qunity->getIsOnline();
        }
    }

    /**
     * Add meta information for Online flag column
     *
     * @param array $meta
     * @return void
     */
    private function addOnlineFlagMeta(array &$meta): void
    {
        $recordPath = implode('/', [
            'downloadable/children',
            'container_links/children',
            'link/children',
            'record/children',
        ]);

        $record[self::IS_ONLINE_KEY]['arguments']['data']['config'] = [
            'label' => __('Online'),
            'formElement' => Form\Element\Select::NAME,
            'componentType' => Form\Field::NAME,
            'dataType' => Form\Element\DataType\Number::NAME,
            'dataScope' => self::IS_ONLINE_KEY,
            'sortOrder' => 51,
            'options' => $this->yesno->toOptionArray(),
        ];

        $meta = $this->arrayManager->merge($recordPath, $meta, $record);
    }
}
