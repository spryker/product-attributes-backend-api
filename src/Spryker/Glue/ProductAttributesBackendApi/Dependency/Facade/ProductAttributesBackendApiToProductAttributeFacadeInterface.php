<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade;

use Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeCriteriaTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;

interface ProductAttributesBackendApiToProductAttributeFacadeInterface
{
    public function createProductManagementAttribute(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): ProductManagementAttributeTransfer;

    public function updateProductManagementAttribute(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): ProductManagementAttributeTransfer;

    public function getProductManagementAttributeCollection(
        ProductManagementAttributeCriteriaTransfer $productManagementAttributeCriteriaTransfer
    ): ProductManagementAttributeCollectionTransfer;

    public function translateProductManagementAttribute(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): void;
}
