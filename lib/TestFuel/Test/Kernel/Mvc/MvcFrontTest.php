<?php
/**
 * Appfuel
 * PHP 5.3+ object oriented MVC framework supporting domain driven design. 
 *
 * @package     Appfuel
 * @author      Robert Scott-Buccleuch <rsb.code@gmail.com.com>
 * @copyright   2009-2010 Robert Scott-Buccleuch <rsb.code@gmail.com>
 * @license     http://www.apache.org/licenses/LICENSE-2.0
 */
namespace TestFuel\Test\Kernel\Mvc;

use StdClass,
	Appfuel\Kernel\Mvc\MvcFront,
	Appfuel\Kernel\KernelRegistry,
	TestFuel\TestCase\BaseTestCase,
	Appfuel\Console\ConsoleViewTemplate,
	Appfuel\Kernel\Mvc\MvcActionDispatcher;

/**
 */
class MvcFrontTest extends BaseTestCase
{
	/**
	 * System under test
	 * @var MvcFrontTest
	 */
	protected $front = null;

	/**
	 * Keep a backup copy of the route map
	 * @var array
	 */
	protected $bkRoutes = null;

	/**
	 * Keep a backup copy of the kernel registry settings
	 * @var array
	 */
	protected $bkParams = null;

	/**
	 * Keep a backup copy of $_GET, $_POST, $_FILES, $_COOKIE, 
	 * and $_SERVER['argv']
	 * @var array
	 */
	protected $bkSuperGlobals = array();

	/**
	 * @return null
	 */
	public function setUp()
	{
		$this->bkRoutes = KernelRegistry::getRouteMap();
		$this->bkParams = KernelRegistry::getParams();

		$myOut = function($data) {
			echo $data;
		};
		$output = $this->getMock('Appfuel\Output\OutputEngineInterface');
		$output->expects($this->any())
			   ->method('render')
			   ->will($this->returnCallback($myOut));
		$this->front = new MvcFront(null, $output);
		KernelRegistry::clearRouteMap();
		KernelRegistry::clearParams();

        $routeMap = array(
            'my-route' => 'TestFuel\Fake\Action\TestFront\ActionA'
        );
        KernelRegistry::setRouteMap($routeMap);
		$cli = null;
		if (isset($_SERVER['argv'])) {
			$cli = $_SERVER['argv'];
		}
		$this->bkSuperGlobals = array(
			'get'    => $_GET,
			'post'   => $_POST,
			'files'  => $_FILES,
			'cookie' => $_COOKIE, 
			'argv'   => $cli
		);
	}

	/**
	 * @return null
	 */
	public function tearDown()
	{
		KernelRegistry::setRouteMap($this->bkRoutes);
		KernelRegistry::setParams($this->bkParams);
		$this->front = null;

		$_GET    = $this->bkSuperGlobals['get'];
		$_POST   = $this->bkSuperGlobals['post'];
		$_FILES  = $this->bkSuperGlobals['files'];
		$_COOKIE = $this->bkSuperGlobals['cookie'];
		$cli = $this->bkSuperGlobals['argv'];
		if (null !== $cli) {
			$_SERVER['argv'] = $cli;
		}
	}

	/**
	 * @return	null
	 */
	public function testInterface()
	{
		$this->assertInstanceOf(
			'Appfuel\Kernel\Mvc\MvcFrontInterface',
			$this->front
		);
	}

	/**
	 * @depends	testInterface
	 * @return	null
	 */
	public function testRun()
	{
		$_SERVER['REQUEST_METHOD'] = 'cli';
		$_SERVER['REQUEST_URI'] = 'my-route/param1/value1';
		
		$this->expectOutputString('this action has been executed');
		$result = $this->front->run('console');
		$this->assertEquals(200, $result);
	}
}
