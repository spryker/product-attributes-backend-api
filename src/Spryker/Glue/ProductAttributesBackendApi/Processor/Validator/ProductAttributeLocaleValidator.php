<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi\Processor\Validator;

use ArrayObject;

class ProductAttributeLocaleValidator implements ProductAttributeLocaleValidatorInterface
{
    /**
     * @param \ArrayObject<int, \Generated\Shared\Transfer\ProductManagementAttributeValueTransfer> $productManagementAttributeValueTransfers
     * @param array<string, \Generated\Shared\Transfer\LocaleTransfer> $localeTransfers
     *
     * @return array<string> Locale names present in the values that are not registered in the system
     */
    public function getUnknownLocaleNames(ArrayObject $productManagementAttributeValueTransfers, array $localeTransfers): array
    {
        $availableLocaleNames = array_keys($localeTransfers);
        $unknownLocaleNames = [];

        foreach ($productManagementAttributeValueTransfers as $productManagementAttributeValueTransfer) {
            foreach ($productManagementAttributeValueTransfer->getLocalizedValues() as $translationTransfer) {
                $localeName = $translationTransfer->getLocaleName();

                if ($localeName && !in_array($localeName, $availableLocaleNames, true)) {
                    $unknownLocaleNames[] = $localeName;
                }
            }
        }

        return array_unique($unknownLocaleNames);
    }
}
