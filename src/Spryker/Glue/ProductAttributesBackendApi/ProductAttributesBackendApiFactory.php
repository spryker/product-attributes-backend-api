<?php

/**
 * Copyright © 2016-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace Spryker\Glue\ProductAttributesBackendApi;

use Spryker\Glue\Kernel\Backend\AbstractBackendApiFactory;
use Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToLocaleFacadeInterface;
use Spryker\Glue\ProductAttributesBackendApi\Dependency\Facade\ProductAttributesBackendApiToProductAttributeFacadeInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Builder\ProductAttributeRestResponseBuilder;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Builder\ProductAttributeRestResponseBuilderInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Creator\ProductAttributeCreator;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Creator\ProductAttributeCreatorInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Expander\ProductAttributeExpander;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Expander\ProductAttributeExpanderInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper\ProductAttributeMapper;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Mapper\ProductAttributeMapperInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Reader\ProductAttributeReader;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Reader\ProductAttributeReaderInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Updater\ProductAttributeUpdater;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Updater\ProductAttributeUpdaterInterface;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Validator\ProductAttributeLocaleValidator;
use Spryker\Glue\ProductAttributesBackendApi\Processor\Validator\ProductAttributeLocaleValidatorInterface;

/**
 * @method \Spryker\Glue\ProductAttributesBackendApi\ProductAttributesBackendApiConfig getConfig()
 */
class ProductAttributesBackendApiFactory extends AbstractBackendApiFactory
{
    public function createProductAttributeCreator(): ProductAttributeCreatorInterface
    {
        return new ProductAttributeCreator(
            $this->getProductAttributeFacade(),
            $this->createProductAttributeRestResponseBuilder(),
            $this->createProductAttributeMapper(),
            $this->createProductAttributeReader(),
            $this->createProductAttributeExpander(),
            $this->createProductAttributeLocaleValidator(),
            $this->getLocaleFacade(),
        );
    }

    public function createProductAttributeRestResponseBuilder(): ProductAttributeRestResponseBuilderInterface
    {
        return new ProductAttributeRestResponseBuilder($this->createProductAttributeMapper());
    }

    public function createProductAttributeMapper(): ProductAttributeMapperInterface
    {
        return new ProductAttributeMapper();
    }

    public function createProductAttributeExpander(): ProductAttributeExpanderInterface
    {
        return new ProductAttributeExpander();
    }

    public function createProductAttributeLocaleValidator(): ProductAttributeLocaleValidatorInterface
    {
        return new ProductAttributeLocaleValidator();
    }

    public function createProductAttributeUpdater(): ProductAttributeUpdaterInterface
    {
        return new ProductAttributeUpdater(
            $this->getProductAttributeFacade(),
            $this->createProductAttributeRestResponseBuilder(),
            $this->createProductAttributeMapper(),
            $this->createProductAttributeReader(),
            $this->createProductAttributeExpander(),
            $this->createProductAttributeLocaleValidator(),
            $this->getLocaleFacade(),
        );
    }

    public function createProductAttributeReader(): ProductAttributeReaderInterface
    {
        return new ProductAttributeReader(
            $this->getProductAttributeFacade(),
            $this->createProductAttributeRestResponseBuilder(),
        );
    }

    public function getProductAttributeFacade(): ProductAttributesBackendApiToProductAttributeFacadeInterface
    {
        return $this->getProvidedDependency(ProductAttributesBackendApiDependencyProvider::FACADE_PRODUCT_ATTRIBUTE);
    }

    public function getLocaleFacade(): ProductAttributesBackendApiToLocaleFacadeInterface
    {
        return $this->getProvidedDependency(ProductAttributesBackendApiDependencyProvider::FACADE_LOCALE);
    }
}
