<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi\Processor\Updater;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\RestProductAttributesBackendAttributesTransfer;
use Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToLocaleFacadeInterface;
use Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToProductAttributeFacadeInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Builder\ProductAttributeRestResponseBuilderInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Expander\ProductAttributeExpanderInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper\ProductAttributeMapperInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Reader\ProductAttributeReaderInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Validator\ProductAttributeLocaleValidatorInterface;

class ProductAttributeUpdater implements ProductAttributeUpdaterInterface
{
    /**
     * @var \Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToProductAttributeFacadeInterface
     */
    protected ProductAttributesBackendApiToProductAttributeFacadeInterface $productAttributeFacade;

    /**
     * @var \Spryker\Glue\ProductAttributesBackendApi\Processor\Builder\ProductAttributeRestResponseBuilderInterface
     */
    protected ProductAttributeRestResponseBuilderInterface $productAttributeRestResponseBuilder;

    /**
     * @var \Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper\ProductAttributeMapperInterface
     */
    protected ProductAttributeMapperInterface $productAttributeMapper;

    /**
     * @var \Spryker\Glue\ProductAttributesBackendApi\Processor\Reader\ProductAttributeReaderInterface
     */
    protected ProductAttributeReaderInterface $productAttributeReader;

    /**
     * @var \Spryker\Glue\ProductAttributesBackendApi\Processor\Expander\ProductAttributeExpanderInterface
     */
    protected ProductAttributeExpanderInterface $productAttributeExpander;

    /**
     * @var \Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToLocaleFacadeInterface
     */
    protected ProductAttributesBackendApiToLocaleFacadeInterface $localeFacade;

    /**
     * @var \Spryker\Glue\ProductAttributesBackendApi\Processor\Validator\ProductAttributeLocaleValidatorInterface
     */
    protected ProductAttributeLocaleValidatorInterface $productAttributeLocaleValidator;

    public function __construct(
        ProductAttributesBackendApiToProductAttributeFacadeInterface $productAttributeFacade,
        ProductAttributeRestResponseBuilderInterface $productAttributeRestResponseBuilder,
        ProductAttributeMapperInterface $productAttributeMapper,
        ProductAttributeReaderInterface $productAttributeReader,
        ProductAttributeExpanderInterface $productAttributeExpander,
        ProductAttributeLocaleValidatorInterface $productAttributeLocaleValidator,
        ProductAttributesBackendApiToLocaleFacadeInterface $localeFacade,
    ) {
        $this->productAttributeFacade = $productAttributeFacade;
        $this->productAttributeRestResponseBuilder = $productAttributeRestResponseBuilder;
        $this->productAttributeMapper = $productAttributeMapper;
        $this->productAttributeReader = $productAttributeReader;
        $this->productAttributeExpander = $productAttributeExpander;
        $this->productAttributeLocaleValidator = $productAttributeLocaleValidator;
        $this->localeFacade = $localeFacade;
    }

    public function updateProductAttribute(
        RestProductAttributesBackendAttributesTransfer $restProductAttributesBackendAttributesTransfer,
        GlueRequestTransfer $glueRequestTransfer
    ): GlueResponseTransfer {
        if (!$glueRequestTransfer->getResource() || !$glueRequestTransfer->getResource()->getId()) {
            return $this->productAttributeRestResponseBuilder->createProductAttributeKeyIsNotProvidedErrorRestResponse();
        }

        $productAttributeKey = $glueRequestTransfer->getResource()->getId();
        $productManagementAttributeTransfer = $this->productAttributeReader->findProductAttributeByKey($productAttributeKey);

        if (!$productManagementAttributeTransfer) {
            return $this->productAttributeRestResponseBuilder->createProductAttributeNotFoundErrorRestResponse();
        }

        $productManagementAttributeTransfer = $this->productAttributeMapper->mapRestProductAttributesBackendAttributesTransferToProductManagementAttributeTransfer(
            $restProductAttributesBackendAttributesTransfer,
            $productManagementAttributeTransfer,
        );
        $productManagementAttributeTransfer->setKey($productAttributeKey);
        $localeTransfers = $this->localeFacade->getLocaleCollection();
        $unknownLocaleNames = $this->productAttributeLocaleValidator->getUnknownLocaleNames($productManagementAttributeTransfer->getValues(), $localeTransfers);

        if ($unknownLocaleNames !== []) {
            return $this->productAttributeRestResponseBuilder->createLocaleNotFoundErrorsRestResponse($unknownLocaleNames);
        }

        $this->productAttributeExpander->expandProductManagementAttributeValueTransfersWithLocaleName($productManagementAttributeTransfer->getValues(), $localeTransfers);

        $productManagementAttributeTransfer = $this->productAttributeFacade->updateProductManagementAttribute($productManagementAttributeTransfer);
        $this->productAttributeFacade->translateProductManagementAttribute($productManagementAttributeTransfer);
        /** @var \Generated\Shared\Transfer\ProductManagementAttributeTransfer $productManagementAttributeTransfer */
        $productManagementAttributeTransfer = $this->productAttributeReader->findProductAttributeByKey($productAttributeKey);

        return $this->productAttributeRestResponseBuilder->createProductAttributesRestResponse($productManagementAttributeTransfer);
    }
}
