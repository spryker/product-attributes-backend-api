<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductAttributesBackendApi\Processor\Expander;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTranslationTransfer;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Expander\ProductAttributeExpander;
use SprykerTest\Glue\ProductAttributesBackendApi\ProductAttributesBackendApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductAttributesBackendApi
 * @group Processor
 * @group Expander
 * @group ProductAttributeExpanderTest
 * Add your own group annotations below this line
 */
class ProductAttributeExpanderTest extends Unit
{
    /**
     * @var string
     */
    protected const LOCALE_DE = 'de_DE';

    /**
     * @var string
     */
    protected const LOCALE_UNKNOWN = 'fr_FR';

    /**
     * @var int
     */
    protected const ID_LOCALE_DE = 46;

    /**
     * @var \SprykerTest\Glue\ProductAttributesBackendApi\ProductAttributesBackendApiTester
     */
    protected ProductAttributesBackendApiTester $tester;

    public function testExpandSetsLocaleIdOnTranslationForKnownLocale(): void
    {
        //Arrange
        $expander = new ProductAttributeExpander();
        $localeTransfers = [
            static::LOCALE_DE => (new LocaleTransfer())->setIdLocale(static::ID_LOCALE_DE),
        ];

        $translation = (new ProductManagementAttributeValueTranslationTransfer())->setLocaleName(static::LOCALE_DE);
        $valueTransfer = (new ProductManagementAttributeValueTransfer())->addLocalizedValue($translation);

        //Act
        $expander->expandProductManagementAttributeValueTransfersWithLocaleName(
            new ArrayObject([$valueTransfer]),
            $localeTransfers,
        );

        //Assert
        $this->assertSame(static::ID_LOCALE_DE, $translation->getFkLocale());
    }

    public function testExpandSkipsTranslationWithUnknownLocaleWithoutCrashing(): void
    {
        //Arrange
        $expander = new ProductAttributeExpander();
        $localeTransfers = [
            static::LOCALE_DE => (new LocaleTransfer())->setIdLocale(static::ID_LOCALE_DE),
        ];

        $unknownTranslation = (new ProductManagementAttributeValueTranslationTransfer())->setLocaleName(static::LOCALE_UNKNOWN);
        $valueTransfer = (new ProductManagementAttributeValueTransfer())->addLocalizedValue($unknownTranslation);

        //Act
        $expander->expandProductManagementAttributeValueTransfersWithLocaleName(
            new ArrayObject([$valueTransfer]),
            $localeTransfers,
        );

        //Assert
        $this->assertNull($unknownTranslation->getFkLocale());
    }
}
