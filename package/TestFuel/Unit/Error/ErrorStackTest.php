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
namespace TestFuel\Unit\Error;

use StdClass,
	Iterator,
	Countable,
	SplFileInfo,
	Appfuel\Error\ErrorStack,
	Appfuel\Error\AppfuelError,
	TestFuel\TestCase\BaseTestCase;

/**
 * The ErrorStack handles a collection of error it can also treat the whole 
 * stack as a single error
 */
class ErrorStackTest extends BaseTestCase
{
	/**
	 * System under test
	 * @var	AppfuelError
	 */
	protected $stack = null;

	/**
	 * @return null
	 */
	public function setUp()
	{
		$this->stack = new ErrorStack();
	}

	/**
	 * @return null
	 */
	public function tearDown()
	{
		$this->stack = null;
	}

	/**
	 * @return	null
	 */
	public function testInterface()
	{	
		$this->assertInstanceOf(
			'Appfuel\Error\ErrorStackInterface',
			$this->stack
		);
			
		$this->assertInstanceOf('Countable', $this->stack);
		$this->assertInstanceOf('Iterator', $this->stack);
	}

	/**
	 * @depends	testInterface
	 * @return	null
	 */
	public function testCountAddGetErrorItem()
	{
		$this->assertEquals(0, $this->stack->count());
		$this->assertFalse($this->stack->getError());
		$this->assertFalse($this->stack->getLastError());

		$errorA = $this->getMock('Appfuel\Error\ErrorInterface');
		$errorB = $this->getMock('Appfuel\Error\ErrorInterface');
		$errorC = $this->getMock('Appfuel\Error\ErrorInterface');
		
		$this->assertSame(
			$this->stack,
			$this->stack->addErrorItem($errorA)
		);
		$this->assertEquals(1, $this->stack->count());

		/* getError returns the current error */
		$this->assertSame($errorA, $this->stack->getError());
		$this->assertSame($errorA, $this->stack->current());
		$this->assertSame($errorA, $this->stack->getLastError());

		$this->assertSame(
			$this->stack,
			$this->stack->addErrorItem($errorB)
		);
		$this->assertEquals(2, $this->stack->count());

		$this->assertSame(
			$this->stack,
			$this->stack->addErrorItem($errorC)
		);
		$this->assertEquals(3, $this->stack->count());

		/* test the iterator interface */
		$this->assertEquals($errorA, $this->stack->current());
		$this->assertEquals(0, $this->stack->key());
		$this->assertTrue($this->stack->valid());
		$this->assertNull($this->stack->next());
	
		/* iteration 2 */
		$this->assertEquals($errorB, $this->stack->current());
		$this->assertEquals(1, $this->stack->key());
		$this->assertTrue($this->stack->valid());
		$this->assertNull($this->stack->next());

		/* iteration 3 */
		$this->assertEquals($errorC, $this->stack->current());
		$this->assertEquals(2, $this->stack->key());
		$this->assertTrue($this->stack->valid());
		$this->assertNull($this->stack->next());

		/* out of range */
		$this->assertFalse($this->stack->current());
		$this->assertNull($this->stack->key());
		$this->assertFalse($this->stack->valid());
		$this->assertNull($this->stack->next());

		/* rewind */
		$this->assertNull($this->stack->rewind());
		$this->assertEquals($errorA, $this->stack->current());
		$this->assertEquals(0, $this->stack->key());
		$this->assertTrue($this->stack->valid());
	}

	/**
	 * @depends	testInterface
	 * @return	null
	 */
	public function testAddError()
	{
		$this->assertEquals(0, $this->stack->count());
		
		$this->assertSame(
			$this->stack,
			$this->stack->addError('my message'),
			'uses a fluent interface'
		);
		$this->assertEquals(1, $this->stack->count());
		$this->assertTrue($this->stack->valid());
		$error = $this->stack->getError();
		$this->assertInstanceOf('Appfuel\Error\ErrorItem', $error);
		$this->assertEquals('my message', $error->getMessage());

		$this->assertSame(
			$this->stack,
			$this->stack->addError('other message'),
			'uses a fluent interface'
		);	
		$this->assertEquals(2, $this->stack->count());
		
		$error = $this->stack->getLastError();
		$this->assertInstanceOf('Appfuel\Error\ErrorItem', $error);
		$this->assertEquals('other message', $error->getMessage());
	}

	/**
	 * getMessage, getCode are from the ErrorInterface and always refer to 
	 * the message or code of the stacks current error.
	 *
	 * @depends	testInterface
	 * @return	null
	 */
	public function testGetErrorMessageCode()
	{
		$this->stack->addError('error 1', 'a1')
					->addError('error 2', 'a2')
					->addError('error 3', 'a3');

		$error = $this->stack->current();
		$this->assertSame($error, $this->stack->getError());
		$this->assertEquals('error 1', $this->stack->getMessage());
		$this->assertEquals('a1', $this->stack->getCode());

		$this->stack->next();
		$this->assertEquals('error 2', $this->stack->getMessage());
		$this->assertEquals('a2', $this->stack->getCode());

		$this->stack->next();
		$this->assertEquals('error 3', $this->stack->getMessage());
		$this->assertEquals('a3', $this->stack->getCode());

		/* out of range, need to rewind */
		$this->stack->next();
		$this->assertNull($this->stack->getMessage());
		$this->assertNull($this->stack->getCode());

		$this->stack->rewind();
		$this->assertEquals('error 1', $this->stack->getMessage());
		$this->assertEquals('a1', $this->stack->getCode());
	}
}
