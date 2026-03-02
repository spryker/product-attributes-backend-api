<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeConditionsTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeCriteriaTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToProductAttributeFacadeInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Builder\ProductAttributeRestResponseBuilderInterface;

class ProductAttributeReader implements ProductAttributeReaderInterface
{
    /**
     * @var \Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToProductAttributeFacadeInterface
     */
    protected ProductAttributesBackendApiToProductAttributeFacadeInterface $productAttributeFacade;

    /**
     * @var \Spryker\Glue\ProductAttributesBackendApi\Processor\Builder\ProductAttributeRestResponseBuilderInterface
     */
    protected ProductAttributeRestResponseBuilderInterface $productAttributeRestResponseBuilder;

    public function __construct(
        ProductAttributesBackendApiToProductAttributeFacadeInterface $productAttributeFacade,
        ProductAttributeRestResponseBuilderInterface $productAttributeRestResponseBuilder
    ) {
        $this->productAttributeFacade = $productAttributeFacade;
        $this->productAttributeRestResponseBuilder = $productAttributeRestResponseBuilder;
    }

    public function getProductAttributeCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        $productManagementAttributeCollectionTransfer = $this->productAttributeFacade->getProductManagementAttributeCollection(
            (new ProductManagementAttributeCriteriaTransfer())
                ->setPagination($glueRequestTransfer->getPagination()),
        );

        return $this->productAttributeRestResponseBuilder->createProductAttributesCollectionRestResponse($productManagementAttributeCollectionTransfer);
    }

    public function getProductAttribute(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer
    {
        if (!$glueRequestTransfer->getResource() || !$glueRequestTransfer->getResource()->getId()) {
            return $this->productAttributeRestResponseBuilder->createProductAttributeKeyIsNotProvidedErrorRestResponse();
        }

        $productManagementAttributeTransfer = $this->findProductAttributeByKey($glueRequestTransfer->getResource()->getId());

        if (!$productManagementAttributeTransfer) {
            return $this->productAttributeRestResponseBuilder->createProductAttributeNotFoundErrorRestResponse();
        }

        return $this->productAttributeRestResponseBuilder->createProductAttributesRestResponse($productManagementAttributeTransfer);
    }

    public function findProductAttributeByKey(string $key): ?ProductManagementAttributeTransfer
    {
        $productManagementAttributeCollectionTransfer = $this->productAttributeFacade->getProductManagementAttributeCollection(
            (new ProductManagementAttributeCriteriaTransfer())
                ->setProductManagementAttributeConditions((new ProductManagementAttributeConditionsTransfer())->addKey($key)),
        );

        return $productManagementAttributeCollectionTransfer->getProductManagementAttributes()->getIterator()->current();
    }
}
