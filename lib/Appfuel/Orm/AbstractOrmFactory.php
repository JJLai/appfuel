<?php
/**
 * Appfuel
 * PHP 5.3+ object oriented MVC framework supporting domain driven design. 
 *
 * @package     Appfuel
 * @author      Robert Scott-Buccleuch <rsb.code@gmail.com.com>
 * @copyright   2009-2010 Robert Scott-Buccleuch <rsb.code@gmail.com>
 * @license		http://www.apache.org/licenses/LICENSE-2.0
 */
namespace Appfuel\Orm;

use Appfuel\Orm\Domain\DataBuilder,
	Appfuel\Orm\Domain\ObjectFactory,
	Appfuel\Framework\Orm\OrmFactoryInterface;

/**
 * The Orm factory interface enforces a series of creation methods used 
 * to build orm objects. It is used by the repository to create a
 * SourceHandler which determines what datasource is being used, 
 * DataBuilder which determines how the data will be formatted and built, and
 * IdentityHandler which is used for mapping raw data into domain data
 */
abstract class AbstractOrmFactory implements OrmFactoryInterface
{
	/**
	 * The data builder is used to convert raw data from a given source into
	 * domain models or domain datasets into different formats like arrays
	 * or strings
	 *
	 * @return	DataBuilderInterface
	 */
	public function createDataBuilder()
	{
		return new DataBuilder($this->createObjectFactory());
	}

	/**
	 * The object factory is responsible for create new domain or domain 
	 * related objects. It is used by the domains data builder
	 *
	 * @return	Domain\ObjectFactory
	 */
	public function createObjectFactory()
	{
		return new ObjectFactory();
	}
}
