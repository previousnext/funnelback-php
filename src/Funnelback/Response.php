<?php

/**
 * @file
 * Contains Funnelback\Response
 */

namespace Funnelback;
use Psr\Http\Message\ResponseInterface;

/**
 * The Funnelback search response.
 */
class Response {

  /**
   * The http response.
   *
   * @var \GuzzleHttp\Message\ResponseInterface
   */
  protected $httpResponse;

  /**
   * The json response body.
   *
   * @var array
   */
  protected $responseJson;

  /**
   * The return code.
   *
   * @var int
   */
  protected $returnCode;

  /**
   * The facets, grouped by category.
   *
   * @var array
   */
  protected $facets;

  /**
   * The result summary.
   *
   * @var \Funnelback\ResultSummary
   */
  protected $resultsSummary;

  /**
   * The best bets.
   *
   * @var \Funnelback\BestBet[]
   */
  protected $bestBets;

  /**
   * The results.
   *
   * @var \Funnelback\Result[]
   */
  protected $results;

  /**
   * The search query.
   *
   * @var string
   */
  protected $query;

  /**
   * The total time taken in millis.
   *
   * @var int
   */
  protected $totalTimeMillis;

  /**
   * Creates a new Funnelback response.
   *
   * @param Psr\Http\Message\ResponseInterface $http_response
   *   The http response.
   */
  public function __construct(ResponseInterface $http_response) {
    $this->httpResponse = $http_response;
    $this->responseJson = json_decode($http_response->getBody()->getContents());
    $this->query = $this->responseJson->question->query;
    $response = $this->responseJson->response;
    $this->returnCode = $this->httpResponse->getStatusCode();
    $this->totalTimeMillis = $response->performanceMetrics->totalTimeMillis;
    $result_packet = $response->resultPacket;
    $this->resultsSummary = new ResultSummary($result_packet->resultsSummary);
    $this->results = $this->buildResults($result_packet->results);
    $this->facets = $this->buildFacets($response->facets);
  }

  /**
   * Gets the return code.
   *
   * @return int
   *   The return code.
   */
  public function getReturnCode() {
    return $this->returnCode;
  }

  /**
   * Gets the summary.
   *
   * @return \Funnelback\ResultSummary
   *   The results summary.
   */
  public function getResultsSummary() {
    return $this->resultsSummary;
  }

  /**
   * Gets the results.
   *
   * @return \Funnelback\Result[]
   *   The results.
   */
  public function getResults() {
    return $this->results;
  }

  /**
   * Gets the facets.
   *
   * @return \Funnelback\Facet[]
   *   The facets.
   */
  public function getFacets() {
    return $this->facets;
  }

  /**
   * Gets the best bets.
   *
   * @return \Funnelback\BestBet[]
   *   The best bets.
   */
  public function getBestBets() {
    return $this->bestBets;
  }

  /**
   * Gets the query.
   *
   * @return string
   *   The query.
   */
  public function getQuery() {
    return $this->query;
  }

  /**
   * Gets the time taken in millis.
   *
   * @return int
   *   The time taken in millis.
   */
  public function getTotalTimeMillis() {
    return $this->totalTimeMillis;
  }

  /**
   * Builds a list of results from the results data.
   *
   * @param array $results_data
   *   The results data.
   *
   * @return \Funnelback\Result[]
   *   The results.
   */
  protected function buildResults($results_data) {
    $results = [];
    foreach ($results_data as $result_data) {
      $results[] = new Result($result_data);
    }
    return $results;
  }

  /**
   * Builds a list of facets grouped by category.
   *
   * @param array $facets_data
   *   The raw facet data.
   *
   * @return array
   *   A list of facets grouped by category.
   */
  protected function buildFacets($facets_data) {
    $facets = [];
    foreach ($facets_data as $facet_data) {
      $facets[] = new Facet($facet_data);
    }
    return $facets;
  }

}
