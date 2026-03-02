<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper;

use Generated\Shared\Transfer\ProductManagementAttributeTransfer;
use Generated\Shared\Transfer\RestProductAttributesBackendAttributesTransfer;

interface ProductAttributeMapperInterface
{
    public function mapProductManagementAttributeTransferToRestProductAttributesBackendAttributesTransfer(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer,
        RestProductAttributesBackendAttributesTransfer $restProductAttributesBackendAttributesTransfer
    ): RestProductAttributesBackendAttributesTransfer;

    public function mapRestProductAttributesBackendAttributesTransferToProductManagementAttributeTransfer(
        RestProductAttributesBackendAttributesTransfer $restProductAttributesBackendAttributesTransfer,
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): ProductManagementAttributeTransfer;
}
