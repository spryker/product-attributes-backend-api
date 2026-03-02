<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade;

use Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeCriteriaTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;

class ProductAttributesBackendApiToProductAttributeFacadeBridge implements ProductAttributesBackendApiToProductAttributeFacadeInterface
{
    /**
     * @var \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface
     */
    protected $productAttributeFacade;

    /**
     * @param \Spryker\Zed\ProductAttribute\Business\ProductAttributeFacadeInterface $productAttributeFacade
     */
    public function __construct($productAttributeFacade)
    {
        $this->productAttributeFacade = $productAttributeFacade;
    }

    public function createProductManagementAttribute(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): ProductManagementAttributeTransfer {
        return $this->productAttributeFacade->createProductManagementAttribute($productManagementAttributeTransfer);
    }

    public function updateProductManagementAttribute(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): ProductManagementAttributeTransfer {
        return $this->productAttributeFacade->updateProductManagementAttribute($productManagementAttributeTransfer);
    }

    public function getProductManagementAttributeCollection(
        ProductManagementAttributeCriteriaTransfer $productManagementAttributeCriteriaTransfer
    ): ProductManagementAttributeCollectionTransfer {
        return $this->productAttributeFacade->getProductManagementAttributeCollection($productManagementAttributeCriteriaTransfer);
    }

    public function translateProductManagementAttribute(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): void {
        $this->productAttributeFacade->translateProductManagementAttribute($productManagementAttributeTransfer);
    }
}
