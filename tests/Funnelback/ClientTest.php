<?php
/**
 * @file
 * Contains Funnelback\ClientTest
 */

namespace Funnelback;

use Funnelback\Client as HttpClient;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\Stream;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;

/**
 * Tests the client class.
 *
 * @coversDefaultClass \Funnelback\Client
 */
class ClientTest extends \PHPUnit_Framework_TestCase {

  /**
   * Tests the search method.
   */
  public function testSearch() {

    $body = new Stream(fopen(__DIR__ . '/../Fixtures/search-results.json', 'r+'));
    $response = new Response(200, [], $body);
    $mock = new MockHandler([$response]);
    $handler = HandlerStack::create($mock);
    $config = [
      'handler' => $handler,
      'base_uri' => 'http://agencysearch.australia.gov.au',
      'collection' => 'agencies',
    ];
    $client = new HttpClient($config);

    $response = $client->search('test');

    // Check the response.
    $this->assertEquals(200, $response->getReturnCode(), 'Return code ok');
    $this->assertEquals('form', $response->getQuery(), 'Query matches');
    $this->assertEquals(408, $response->getTotalTimeMillis(), 'Total time millis matches');

    // Check the summary.
    $summary = $response->getResultsSummary();
    $this->assertEquals(1, $summary->getStart(), 'Start matches');
    $this->assertEquals(10, $summary->getEnd(), 'End matches');
    $this->assertEquals(10, $summary->getPageSize(), 'Page size matches');
    $this->assertEquals(17632, $summary->getTotal(), 'Total matches');

    // Check the results.
    $results = $response->getResults();

    $this->assertNotEmpty($results, "Results are found");

    // Check an individual result.
    $result = $results[0];

    $this->assertEquals('Forms', $result->getTitle(), 'Title matches');
    $this->assertEquals('Forms. We provide electronic and printable forms that you can download, complete and return to us. ... An A to Z list by name of forms for Centrelink, Child Support and Medicare.', $result->getSummary(), 'Summary matches');
    $this->assertEquals('2014-10-27', $result->getDate()
      ->format('Y-m-d'), 'Date matches');
    $this->assertEquals('http://cache-au.funnelback.com/search/cache.cgi?collection=fed-gov&doc=funnelback-web-crawl.warc&off=27605782&len=6529&url=http%3A%2F%2Fwww.humanservices.gov.au%2Fcustomer%2Fforms%2F&profile=_default', $result->getCacheUrl(), 'Cache URL matches');
    $this->assertEquals('/search/click.cgi?rank=1&collection=agencies&url=http%3A%2F%2Fwww.humanservices.gov.au%2Fcustomer%2Fforms%2F&index_url=http%3A%2F%2Fwww.humanservices.gov.au%2Fcustomer%2Fforms%2F&auth=1gCsYnROvefyAqpyeyY78g&query=form&profile=_default', $result->getClickUrl(), 'Click URL matches');
    $this->assertEquals('http://www.humanservices.gov.au/customer/forms/', $result->getLiveUrl(), 'Live URL matches');

    // Check the facets.
    $facets = $response->getFacets();

    $this->assertNotEmpty($facets, "Facets were found");

    // Check a facet.
    $facet = $facets[0];

    $this->assertEquals('Keyword', $facet->getName(), 'The name matches');

    $facet_items = $facet->getFacetItems();
    $this->assertNotEmpty($facet_items, "Facet items are not empty");

    // Check a facet item.
    $facet_item = $facet_items[0];

    $this->assertEquals('13. Year books and other multi-subject products', $facet_item->getLabel(), 'Label matches');
    $this->assertEquals(7353, $facet_item->getCount(), 'Count matches');
    $this->assertEquals('f.Keyword%7Cs=13.+Year+books+and+other+multi-subject+products', $facet_item->getQueryStringParam(), 'Query string param matches');

  }

}
