<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductAttributesBackendApi\Processor\Validator;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\LocaleTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTranslationTransfer;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Validator\ProductAttributeLocaleValidator;
use SprykerTest\Glue\ProductAttributesBackendApi\ProductAttributesBackendApiTester;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductAttributesBackendApi
 * @group Processor
 * @group Validator
 * @group ProductAttributeLocaleValidatorTest
 * Add your own group annotations below this line
 */
class ProductAttributeLocaleValidatorTest extends Unit
{
    /**
     * @var string
     */
    protected const LOCALE_DE = 'de_DE';

    /**
     * @var string
     */
    protected const LOCALE_EN = 'en_US';

    /**
     * @var string
     */
    protected const LOCALE_UNKNOWN = 'fr_FR';

    /**
     * @var \SprykerTest\Glue\ProductAttributesBackendApi\ProductAttributesBackendApiTester
     */
    protected ProductAttributesBackendApiTester $tester;

    /**
     * Bug: sending localizedValues with a locale not registered in the system caused a fatal 500 error.
     *
     * Expected: validator returns the unknown locale names so the caller can return a 422 response.
     */
    public function testGetUnknownLocaleNamesReturnsLocaleNamesNotRegisteredInSystem(): void
    {
        //Arrange
        $validator = new ProductAttributeLocaleValidator();
        $localeTransfers = $this->createLocaleTransfers();

        $valueTransfer = (new ProductManagementAttributeValueTransfer())
            ->addLocalizedValue(
                (new ProductManagementAttributeValueTranslationTransfer())->setLocaleName(static::LOCALE_DE),
            )
            ->addLocalizedValue(
                (new ProductManagementAttributeValueTranslationTransfer())->setLocaleName(static::LOCALE_UNKNOWN),
            );

        //Act
        $unknownLocaleNames = $validator->getUnknownLocaleNames(new ArrayObject([$valueTransfer]), $localeTransfers);

        //Assert
        $this->assertSame([static::LOCALE_UNKNOWN], $unknownLocaleNames);
    }

    public function testGetUnknownLocaleNamesReturnsEmptyArrayWhenAllLocalesAreRegistered(): void
    {
        //Arrange
        $validator = new ProductAttributeLocaleValidator();
        $localeTransfers = $this->createLocaleTransfers();

        $valueTransfer = (new ProductManagementAttributeValueTransfer())
            ->addLocalizedValue(
                (new ProductManagementAttributeValueTranslationTransfer())->setLocaleName(static::LOCALE_DE),
            )
            ->addLocalizedValue(
                (new ProductManagementAttributeValueTranslationTransfer())->setLocaleName(static::LOCALE_EN),
            );

        //Act
        $unknownLocaleNames = $validator->getUnknownLocaleNames(new ArrayObject([$valueTransfer]), $localeTransfers);

        //Assert
        $this->assertSame([], $unknownLocaleNames);
    }

    /**
     * @return array<string, \Generated\Shared\Transfer\LocaleTransfer>
     */
    protected function createLocaleTransfers(): array
    {
        return [
            static::LOCALE_DE => (new LocaleTransfer())->setLocaleName(static::LOCALE_DE),
            static::LOCALE_EN => (new LocaleTransfer())->setLocaleName(static::LOCALE_EN),
        ];
    }
}
