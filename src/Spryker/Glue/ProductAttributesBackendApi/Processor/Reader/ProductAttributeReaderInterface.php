<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi\Processor\Reader;

use Generated\Shared\Transfer\GlueRequestTransfer;
use Generated\Shared\Transfer\GlueResponseTransfer;
use Generated\Shared\Transfer\ProductManagementAttributeTransfer;

interface ProductAttributeReaderInterface
{
    public function getProductAttributeCollection(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer;

    public function getProductAttribute(GlueRequestTransfer $glueRequestTransfer): GlueResponseTransfer;

    public function findProductAttributeByKey(string $key): ?ProductManagementAttributeTransfer;
}
