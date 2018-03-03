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

	public function testSetCustomFiltersFunction(): void
	{
		$request = $this->request->create(
			'/',
			'GET',
			[
				'invalid_integer_field' => 'string instead of integer',
				'valid_integer_field' => '2018',
				'email_field' => 'invalid email'
			]
		);

		$request->setCustomFilters([
			'invalid_integer_field' => FILTER_SANITIZE_NUMBER_INT,
			'valid_integer_field' => FILTER_SANITIZE_NUMBER_INT,
			'email_field' => FILTER_VALIDATE_EMAIL
		]);

		$this->assertNotEmpty($request->custom_filters);
	}

	public function testFilterFunction(): void
	{
		$request = $this->request->create(
			'/',
			'GET',
			[
				'invalid_integer_field' => 'string instead of integer',
				'valid_integer_field' => '2018',
				'email_field' => 'invalid email',
				'allowed_html_field' => '<strong>Allowed</strong>',
				'invalid_html_field' => '<script></script>',
				'multiple_integer_field' => ['value1', 'value2']
			]
		);

		$request->setCustomFilters([
			'invalid_integer_field' => FILTER_SANITIZE_NUMBER_INT,
			'valid_integer_field' => FILTER_SANITIZE_NUMBER_INT,
			'email_field' => FILTER_VALIDATE_EMAIL,
			'allowed_html_field' => [
				'filter' => FILTER_UNSAFE_RAW
			]
		]);

		$this->assertEmpty($request->get('invalid_integer_field'));
		$this->assertEquals(2018, $request->get('valid_integer_field'));
		$this->assertFalse($request->get('email_field'));
		$this->assertEquals('<strong>Allowed</strong>', $request->get('allowed_html_field'));
		$this->assertEmpty($request->get('invalid_html_field'));
		$this->assertEquals(['value1', 'value2'], $request->get('multiple_integer_field'));
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

	// public function testServerFunction(): void
	// {

	// }

	// public function testCookieFunction(): void
	// {

	// }

	public function testIsGetFunction(): void
	{
		$request = $this->request->create(
			'/',
			'GET'
		);
		
		$this->assertTrue($request->isGet());
	}

	public function testIsPostFunction(): void
	{
		$request = $this->request->create(
			'/',
			'POST'
		);
		
		$this->assertTrue($request->isPost());
	}

	public function testIsPutFunction(): void
	{
		$request = $this->request->create(
			'/',
			'PUT'
		);
		
		$this->assertTrue($request->isPut());
	}

	public function testIsDeleteFunction(): void
	{
		$request = $this->request->create(
			'/',
			'DELETE'
		);
		
		$this->assertTrue($request->isDelete());
	}

	public function testIsHeadFunction(): void
	{
		$request = $this->request->create(
			'/',
			'HEAD'
		);
		
		$this->assertTrue($request->isHead());
	}

	// public function testIsValidFunction(): void
	// {

	// }
}