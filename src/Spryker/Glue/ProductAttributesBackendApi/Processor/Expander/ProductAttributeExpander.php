<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi\Processor\Expander;

use ArrayObject;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;

class ProductAttributeExpander implements ProductAttributeExpanderInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer> $productManagementAttributeValueTransfers
     * @param array<string, \Generated\Shared\Transfer\LocaleTransfer> $localeTransfers
     *
     * @return \ArrayObject<int, \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer>
     */
    public function expandProductManagementAttributeValueTransfersWithLocaleName(
        ArrayObject $productManagementAttributeValueTransfers,
        array $localeTransfers,
    ): ArrayObject {
        foreach ($productManagementAttributeValueTransfers as $productManagementAttributeValueTransfer) {
            $this->expandProductManagementAttributeValueTransferWithLocaleName($productManagementAttributeValueTransfer, $localeTransfers);
        }

        return $productManagementAttributeValueTransfers;
    }

    /**
     * @param \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer $productManagementAttributeValueTransfer
     * @param array<string, \Generated\Shared\Transfer\LocaleTransfer> $localeTransfers
     *
     * @return \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer
     */
    protected function expandProductManagementAttributeValueTransferWithLocaleName(
        ProductManagementAttributeValueTransfer $productManagementAttributeValueTransfer,
        array $localeTransfers,
    ): ProductManagementAttributeValueTransfer {
        foreach ($productManagementAttributeValueTransfer->getLocalizedValues() as $productManagementAttributeValueTranslationTransfer) {
            $localeName = $productManagementAttributeValueTranslationTransfer->getLocaleName();

            if (!$localeName || !isset($localeTransfers[$localeName])) {
                continue;
            }

            $productManagementAttributeValueTranslationTransfer->setFkLocale($localeTransfers[$localeName]->getIdLocale());
        }

        return $productManagementAttributeValueTransfer;
    }
}
