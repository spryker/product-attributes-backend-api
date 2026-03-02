<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBundleDependencyProvider;
use Spryker\Glue\Kernel\Backend\Container;
use Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToLocaleFacadeBridge;
use Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToProductAttributeFacadeBridge;

/**
 * @method \Spryker\Glue\ProductAttributesBackendApi\ProductAttributesBackendApiConfig getConfig()
 */
class ProductAttributesBackendApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_PRODUCT_ATTRIBUTE = 'FACADE_PRODUCT_ATTRIBUTE';

    /**
     * @var string
     */
    public const FACADE_LOCALE = 'FACADE_LOCALE';

    public function provideBackendDependencies(Container $container): Container
    {
        $container = parent::provideBackendDependencies($container);
        $container = $this->addProductAttributeFacade($container);
        $container = $this->addLocaleFacade($container);

        return $container;
    }

    protected function addLocaleFacade(Container $container): Container
    {
        $container->set(static::FACADE_LOCALE, function (Container $container) {
            return new ProductAttributesBackendApiToLocaleFacadeBridge($container->getLocator()->locale()->facade());
        });

        return $container;
    }

    protected function addProductAttributeFacade(Container $container): Container
    {
        $container->set(static::FACADE_PRODUCT_ATTRIBUTE, function (Container $container) {
            return new ProductAttributesBackendApiToProductAttributeFacadeBridge($container->getLocator()->productAttribute()->facade());
        });

        return $container;
    }
}
