<?php

namespace Tests\System\Http;

use HXPHP\System\Http\Request;
use Symfony\Component\HttpFoundation\Request as SymfonyHttpFoundationRequest;
use Tests\BaseTestCase;

final class RequestTest extends BaseTestCase
{
    protected $request;

    public function setUp()
    {
        parent::setUp();

        SymfonyHttpFoundationRequest::setFactory(function (
            array $query = [],
            array $request = [],
            array $attributes = [],
            array $cookies = [],
            array $files = [],
            array $server = [],
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

    public function testGetMethodFunction()
    {
        $request = $this->request->create(
            '/',
            'POST'
        );

        $method = $request->getMethod();

        $this->assertEquals('POST', $method);
    }

    public function testSetCustomFiltersFunction()
    {
        $request = $this->request->create(
            '/',
            'GET',
            [
                'invalid_integer_field' => 'string instead of integer',
                'valid_integer_field'   => '2018',
                'email_field'           => 'invalid email',
            ]
        );

        $request->setCustomFilters([
            'invalid_integer_field' => FILTER_SANITIZE_NUMBER_INT,
            'valid_integer_field'   => FILTER_SANITIZE_NUMBER_INT,
            'email_field'           => FILTER_VALIDATE_EMAIL,
        ]);

        $this->assertNotEmpty($request->custom_filters);
    }

    public function testFilterFunction()
    {
        $request = $this->request->create(
            '/',
            'GET',
            [
                'invalid_integer_field'  => 'string instead of integer',
                'valid_integer_field'    => '2018',
                'email_field'            => 'invalid email',
                'allowed_html_field'     => '<strong>Allowed</strong>',
                'invalid_html_field'     => '<script></script>',
                'multiple_integer_field' => ['value1', 'value2'],
            ]
        );

        $request->setCustomFilters([
            'invalid_integer_field' => FILTER_SANITIZE_NUMBER_INT,
            'valid_integer_field'   => FILTER_SANITIZE_NUMBER_INT,
            'email_field'           => FILTER_VALIDATE_EMAIL,
            'allowed_html_field'    => [
                'filter' => FILTER_UNSAFE_RAW,
            ],
            'multiple_integer_field' => [
                'filter' => FILTER_SANITIZE_NUMBER_INT,
                'flags'  => FILTER_FORCE_ARRAY,
            ],
        ]);

        $this->assertEmpty($request->get('invalid_integer_field'));
        $this->assertEquals(2018, $request->get('valid_integer_field'));
        $this->assertFalse($request->get('email_field'));
        $this->assertEquals('<strong>Allowed</strong>', $request->get('allowed_html_field'));
        $this->assertEmpty($request->get('invalid_html_field'));
        $this->assertEquals([1, 2], $request->get('multiple_integer_field'));
    }

    public function testGetFunction()
    {
        $request = $this->request->create(
            '/',
            'GET',
            [
                'hello' => 'world',
            ]
        );

        $hello = $request->get('hello');
        $this->assertEquals('world', $hello);

        $all_params = $request->get();
        $this->assertInternalType('array', $all_params);

        $not_exists = $request->get('not_exists');
        $this->assertNull($not_exists);

        $type_error = $request->get(['something']);
        $this->assertFalse($type_error);
    }

    public function testPostFunction()
    {
        $request = $this->request->create(
            '/',
            'POST',
            [
                'hello' => 'world',
            ]
        );

        $hello = $request->post('hello');
        $this->assertEquals('world', $hello);

        $all_params = $request->post();
        $this->assertInternalType('array', $all_params);

        $not_exists = $request->post('not_exists');
        $this->assertNull($not_exists);
    }

    public function testServerFunction()
    {
        $this->assertNotEmpty($this->request->server('SCRIPT_FILENAME'));

        $all_params = $this->request->server();
        $this->assertTrue(is_array($all_params) && !empty($all_params));
    }

    public function testCookieFunction()
    {
        $request = $this->request->cookies->add(['foo' => 'bar']);

        $this->assertEquals('bar', $this->request->cookie('foo'));
    }

    public function testIsGetFunction()
    {
        $request = $this->request->create(
            '/',
            'GET'
        );

        $this->assertTrue($request->isGet());
    }

    public function testIsPostFunction()
    {
        $request = $this->request->create(
            '/',
            'POST'
        );

        $this->assertTrue($request->isPost());
    }

    public function testIsPutFunction()
    {
        $request = $this->request->create(
            '/',
            'PUT'
        );

        $this->assertTrue($request->isPut());
    }

    public function testIsDeleteFunction()
    {
        $request = $this->request->create(
            '/',
            'DELETE'
        );

        $this->assertTrue($request->isDelete());
    }

    public function testIsHeadFunction()
    {
        $request = $this->request->create(
            '/',
            'HEAD'
        );

        $this->assertTrue($request->isHead());
    }

    public function testIsValidFunction()
    {
        $request = $this->request->create(
            '/',
            'GET',
            [
                'invalid_integer_field' => 'string instead of integer',
                'valid_integer_field'   => '2018',
                'email_field'           => 'invalid email',
            ]
        );

        $request->setCustomFilters([
            'invalid_integer_field' => FILTER_VALIDATE_INT,
            'valid_integer_field'   => FILTER_VALIDATE_INT,
            'email_field'           => FILTER_VALIDATE_EMAIL,
        ]);

        $this->assertFalse($request->isValid());
    }
}
