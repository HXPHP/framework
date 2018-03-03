<?php
namespace Tests\System\Http;

use Symfony\Component\HttpFoundation\Request as SymfonyHttpFoundationRequest;
use HXPHP\System\Http\Request;
use Tests\BaseTestCase;

final class RequestTest extends BaseTestCase
{
	protected $request;

	public function setUp()
	{
		parent::setUp();

		SymfonyHttpFoundationRequest::setFactory(function (
            array $query = array(),
            array $request = array(),
            array $attributes = array(),
            array $cookies = array(),
            array $files = array(),
            array $server = array(),
            $content = null
        ) {
            return new Request(
                $query,
                $request,
                $attributes,
                $cookies,
                $files,
                $server,
                $content
            );
        });

		$this->request = Request::createFromGlobals();
	}

	public function testGetMethodFunction(): void
	{
		$request = $this->request->create(
			'/',
			'POST'
		);
		
		$method = $request->getMethod();

		$this->assertEquals("POST", $method);
	}

	public function testIsGetMethodFunction(): void
	{
		$request = $this->request->create(
			'/',
			'GET'
		);
		
		$this->assertTrue($request->isGet());
	}

	public function testIsPostMethodFunction(): void
	{
		$request = $this->request->create(
			'/',
			'POST'
		);
		
		$this->assertTrue($request->isPost());
	}

	public function testIsPutMethodFunction(): void
	{
		$request = $this->request->create(
			'/',
			'PUT'
		);
		
		$this->assertTrue($request->isPut());
	}

	public function testIsHeadMethodFunction(): void
	{
		$request = $this->request->create(
			'/',
			'HEAD'
		);
		
		$this->assertTrue($request->isHead());
	}

	public function testPostFunction(): void
	{
		$request = $this->request->create(
			'/',
			'POST',
			[
				'hello' => 'world'
			]
		);

		$hello = $request->post('hello');
		$this->assertEquals("world", $hello);

		$all_params = $request->post();
		$this->assertInternalType("array", $all_params);

		$not_exists = $request->post('not_exists');
		$this->assertNull($not_exists);
	}

	public function testGetFunction(): void
	{
		$request = $this->request->create(
			'/',
			'GET',
			[
				'hello' => 'world'
			]
		);

		$hello = $request->get('hello');
		$this->assertEquals("world", $hello);

		$all_params = $request->get();
		$this->assertInternalType("array", $all_params);

		$not_exists = $request->get('not_exists');
		$this->assertNull($not_exists);

		$type_error = $request->get(['something']);
		$this->assertFalse($type_error);
	}
}