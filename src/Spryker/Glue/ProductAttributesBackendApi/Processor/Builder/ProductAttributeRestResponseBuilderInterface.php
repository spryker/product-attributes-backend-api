<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi\Processor\Builder;

use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeCollectionTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;

interface ProductAttributeRestResponseBuilderInterface
{
    public function createProductAttributesCollectionRestResponse(
        ProductManagementAttributeCollectionTransfer $productManagementAttributeCollectionTransfer
    ): GlueResponseTransfer;

    public function createProductAttributesRestResponse(
        ProductManagementAttributeTransfer $productManagementAttributeTransfer
    ): GlueResponseTransfer;

    public function createProductAttributeKeyExistsErrorRestResponse(): GlueResponseTransfer;

    public function createProductAttributeKeyIsNotProvidedErrorRestResponse(): GlueResponseTransfer;

    public function createProductAttributeNotFoundErrorRestResponse(): GlueResponseTransfer;

    /**
     * @param array<string> $localeNames
     */
    public function createLocaleNotFoundErrorsRestResponse(array $localeNames): GlueResponseTransfer;
}
