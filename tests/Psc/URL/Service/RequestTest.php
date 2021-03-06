<?php

namespace Psc\URL\Service;

use \Psc\Code\Test\Base,
    \Psc\URL\Service\Request;

/**
 * @group class:Psc\URL\Service\Request
 */
class RequestTest extends \Psc\Code\Test\Base {
  
  public function testApi() {
    
    $request = new Request(Request::GET, '/episodes/8/status');
    
    $this->assertEquals('episodes',$request->getPart(1));
    $this->assertEquals('8',$request->getPart(2));
    $this->assertEquals('status',$request->getPart(3));
    $this->assertEquals(NULL,$request->getPart(4));
    
    $this->assertEquals(Request::GET,$request->getMethod());
  }
  
  public function testFactory() {
    
    $GET = array('mod_rewrite_request' => 'episodes/8/status');
    $POST = array();
    $COOKIE = array();
    $SERVER = array ('serien-loader_host' => 'psc-laptop',
                      'HTTP_HOST' => 'serien-loader.philipp.zpintern',
                      'HTTP_USER_AGENT' => 'Mozilla/5.0 (Windows NT 6.0; rv:7.0.1) Gecko/20100101 Firefox/7.0.1',
                      'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                      'HTTP_ACCEPT_LANGUAGE' => 'en-us,en;q=0.5',
                      'HTTP_ACCEPT_ENCODING' => 'gzip, deflate',
                      'HTTP_ACCEPT_CHARSET' => 'ISO-8859-1,utf-8;q=0.7,*;q=0.7',
                      'HTTP_CONNECTION' => 'keep-alive',
                      'SERVER_NAME' => 'serien-loader.philipp.zpintern',
                      'DOCUMENT_ROOT' => 'D:/www/serien-loader/Umsetzung/base/htdocs',
                      'REDIRECT_QUERY_STRING' => 'request=episodes/8/status',
                      'REDIRECT_URL' => '/episodes/8/status',
                      'GATEWAY_INTERFACE' => 'CGI/1.1',
                      'SERVER_PROTOCOL' => 'HTTP/1.1',
                      'REQUEST_METHOD' => 'GET',
                      'QUERY_STRING' => 'request=episodes/8/status',
                      'REQUEST_URI' => '/episodes/8/status',
                      'SCRIPT_NAME' => '/api.php',
                      'PHP_SELF' => '/api.php',
                      'REQUEST_TIME' => 1320162765
                    );
    
    $request = Request::infer($GET, $POST, $COOKIE, $SERVER);
    $this->assertEquals('episodes',$request->getPart(1));
    $this->assertEquals('8',$request->getPart(2));
    $this->assertEquals('status',$request->getPart(3));
    $this->assertEquals(Request::GET,$request->getMethod());
    
    // hack dirty für test
    $_GET = $GET;
    $_POST = $POST;
    $_COOKIE = $COOKIE;
    $_SERVER = $SERVER;
    
    $request = Request::infer();
    $this->assertEquals('episodes',$request->getPart(1));
    $this->assertEquals('8',$request->getPart(2));
    $this->assertEquals('status',$request->getPart(3));
    $this->assertEquals(Request::GET,$request->getMethod());


    $GET = array('mod_rewrite_request' => 'episodes/8/status');
    $SERVER['REQUEST_METHOD'] = 'POST';
    $POST = array();
    $COOKIE = array();
    $request = Request::infer($GET, $POST, $COOKIE, $SERVER);
    $this->assertEquals('episodes',$request->getPart(1));
    $this->assertEquals('8',$request->getPart(2));
    $this->assertEquals('status',$request->getPart(3));
    $this->assertEquals(Request::POST,$request->getMethod());


    $GET = array(); // nimmt dann request_URI
    $SERVER['REQUEST_METHOD'] = 'POST';
    $POST = array();
    $COOKIE = array();
    $request = Request::infer($GET, $POST, $COOKIE, $SERVER);
    $this->assertEquals('episodes',$request->getPart(1));
    $this->assertEquals('8',$request->getPart(2));
    $this->assertEquals('status',$request->getPart(3));
    $this->assertEquals(Request::POST,$request->getMethod());
  }
  
  /**
   * @expectedException \InvalidArgumentException
   */
  public function testPart0Exception() {
    $request = new Request(Request::GET, '/episodes/8/status');
    $request->getPart(0);
  }

  /**
   * @expectedException \Psc\Exception
   */
  public function testTypeException() {
    $request = new Request('blubb', '/episodes/8/status');
  }
  
  public function testTypes() {
    $request = new Request(Request::GET,'/service/identifier/sub');
    $request = new Request(Request::POST,'/service/identifier/sub');
    $request = new Request(Request::PUT,'/service/identifier/sub');
    $request = new Request(Request::DELETE,'/service/identifier/sub');
  }
}

?>