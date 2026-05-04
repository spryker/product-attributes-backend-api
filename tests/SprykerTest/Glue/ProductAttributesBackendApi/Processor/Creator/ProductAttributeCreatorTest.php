<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace SprykerTest\Glue\ProductAttributesBackendApi\Processor\Creator;

use ArrayObject;
use Codeception\Test\Unit;
use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeValueTranslationTransfer;
use Generated\Shared\Transfer\RestProductAttributesBackendAttributesTransfer;
use Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToLocaleFacadeInterface;
use Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToProductAttributeFacadeInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Builder\ProductAttributeRestResponseBuilder;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Creator\ProductAttributeCreator;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Expander\ProductAttributeExpander;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper\ProductAttributeMapper;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper\ProductAttributeMapperInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Reader\ProductAttributeReaderInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Validator\ProductAttributeLocaleValidator;
use Spryker\Glue\ProductAttributesBackendApi\ProductAttributesBackendApiConfig;
use SprykerTest\Glue\ProductAttributesBackendApi\ProductAttributesBackendApiTester;
use Symfony\Component\HttpFoundation\Response;

/**
 * Auto-generated group annotations
 *
 * @group SprykerTest
 * @group Glue
 * @group ProductAttributesBackendApi
 * @group Processor
 * @group Creator
 * @group ProductAttributeCreatorTest
 * Add your own group annotations below this line
 */
class ProductAttributeCreatorTest extends Unit
{
    /**
     * @var string
     */
    protected const ATTRIBUTE_KEY = 'memory_channels';

    /**
     * @var string
     */
    protected const LOCALE_UNKNOWN = 'fr_FR';

    /**
     * @var string
     */
    protected const LOCALE_UNKNOWN_SECOND = 'es_ES';

    /**
     * @var \SprykerTest\Glue\ProductAttributesBackendApi\ProductAttributesBackendApiTester
     */
    protected ProductAttributesBackendApiTester $tester;

    public function testCreateReturnsKeyNotProvidedErrorWhenKeyIsAbsent(): void
    {
        //Arrange
        $creator = $this->createCreator($this->createReaderMock(null), $this->createMapperMock(null));
        $restTransfer = new RestProductAttributesBackendAttributesTransfer();

        //Act
        $response = $creator->createProductAttribute($restTransfer, new GlueRequestTransfer());

        //Assert
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getHttpStatus());
        $this->assertSame(ProductAttributesBackendApiConfig::RESPONSE_CODE_PRODUCT_ATTRIBUTE_NOT_PROVIDED, $response->getErrors()->offsetGet(0)->getCode());
    }

    public function testCreateReturnsKeyExistsErrorWhenAttributeAlreadyExists(): void
    {
        //Arrange
        $creator = $this->createCreator($this->createReaderMock(new ProductManagementAttributeTransfer()), $this->createMapperMock(null));
        $restTransfer = (new RestProductAttributesBackendAttributesTransfer())->setKey(static::ATTRIBUTE_KEY);

        //Act
        $response = $creator->createProductAttribute($restTransfer, new GlueRequestTransfer());

        //Assert
        $this->assertSame(Response::HTTP_BAD_REQUEST, $response->getHttpStatus());
        $this->assertSame(ProductAttributesBackendApiConfig::RESPONSE_CODE_PRODUCT_ATTRIBUTE_KEY_EXISTS, $response->getErrors()->offsetGet(0)->getCode());
    }

    public function testCreateReturnsLocaleNotFoundErrorWhenLocaleIsUnknown(): void
    {
        //Arrange
        $attributeTransferWithUnknownLocale = $this->createAttributeTransferWithUnknownLocale();
        $creator = $this->createCreator(
            $this->createReaderMock(null),
            $this->createMapperMock($attributeTransferWithUnknownLocale),
        );
        $restTransfer = (new RestProductAttributesBackendAttributesTransfer())->setKey(static::ATTRIBUTE_KEY);

        //Act
        $response = $creator->createProductAttribute($restTransfer, new GlueRequestTransfer());

        //Assert
        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getHttpStatus());
        $this->assertSame(ProductAttributesBackendApiConfig::RESPONSE_CODE_LOCALE_NOT_FOUND, $response->getErrors()->offsetGet(0)->getCode());
        $this->assertStringContainsString(static::LOCALE_UNKNOWN, $response->getErrors()->offsetGet(0)->getMessage());
    }

    public function testCreateReturnsOneErrorPerUnknownLocaleWhenMultipleLocalesAreUnknown(): void
    {
        //Arrange
        $attributeTransferWithTwoUnknownLocales = $this->createAttributeTransferWithTwoUnknownLocales();
        $creator = $this->createCreator(
            $this->createReaderMock(null),
            $this->createMapperMock($attributeTransferWithTwoUnknownLocales),
        );
        $restTransfer = (new RestProductAttributesBackendAttributesTransfer())->setKey(static::ATTRIBUTE_KEY);

        //Act
        $response = $creator->createProductAttribute($restTransfer, new GlueRequestTransfer());

        //Assert
        $this->assertSame(Response::HTTP_UNPROCESSABLE_ENTITY, $response->getHttpStatus());
        $this->assertCount(2, $response->getErrors());
        $this->assertStringContainsString(static::LOCALE_UNKNOWN, $response->getErrors()->offsetGet(0)->getMessage());
        $this->assertStringContainsString(static::LOCALE_UNKNOWN_SECOND, $response->getErrors()->offsetGet(1)->getMessage());
    }

    protected function createCreator(ProductAttributeReaderInterface $reader, ProductAttributeMapperInterface $mapper): ProductAttributeCreator
    {
        $responseBuilder = new ProductAttributeRestResponseBuilder(new ProductAttributeMapper());

        return new ProductAttributeCreator(
            $this->createMock(ProductAttributesBackendApiToProductAttributeFacadeInterface::class),
            $responseBuilder,
            $mapper,
            $reader,
            new ProductAttributeExpander(),
            new ProductAttributeLocaleValidator(),
            $this->createLocaleFacadeMock(),
        );
    }

    protected function createReaderMock(?ProductManagementAttributeTransfer $returnValue): ProductAttributeReaderInterface
    {
        $reader = $this->createMock(ProductAttributeReaderInterface::class);
        $reader->method('findProductAttributeByKey')->willReturn($returnValue);

        return $reader;
    }

    protected function createMapperMock(?ProductManagementAttributeTransfer $returnValue): ProductAttributeMapperInterface
    {
        $mapper = $this->createMock(ProductAttributeMapperInterface::class);
        $mapper->method('mapRestProductAttributesBackendAttributesTransferToProductManagementAttributeTransfer')
            ->willReturn($returnValue ?? new ProductManagementAttributeTransfer());

        return $mapper;
    }

    protected function createLocaleFacadeMock(): ProductAttributesBackendApiToLocaleFacadeInterface
    {
        $localeFacade = $this->createMock(ProductAttributesBackendApiToLocaleFacadeInterface::class);
        $localeFacade->method('getLocaleCollection')->willReturn([]);

        return $localeFacade;
    }

    protected function createAttributeTransferWithUnknownLocale(): ProductManagementAttributeTransfer
    {
        $translation = (new ProductManagementAttributeValueTranslationTransfer())->setLocaleName(static::LOCALE_UNKNOWN);
        $value = (new ProductManagementAttributeValueTransfer())->addLocalizedValue($translation);

        return (new ProductManagementAttributeTransfer())
            ->setValues(new ArrayObject([$value]));
    }

    protected function createAttributeTransferWithTwoUnknownLocales(): ProductManagementAttributeTransfer
    {
        $value = (new ProductManagementAttributeValueTransfer())
            ->addLocalizedValue((new ProductManagementAttributeValueTranslationTransfer())->setLocaleName(static::LOCALE_UNKNOWN))
            ->addLocalizedValue((new ProductManagementAttributeValueTranslationTransfer())->setLocaleName(static::LOCALE_UNKNOWN_SECOND));

        return (new ProductManagementAttributeTransfer())
            ->setValues(new ArrayObject([$value]));
    }
}
