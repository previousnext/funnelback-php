<?php

/**
 * @file
 * Contains Funnelback\ResultSummary
 */

namespace Funnelback;

/**
 * The search result summary.
 */
class ResultSummary {

  /**
   * The results summary json.
   *
   * @var array
   */
  protected $summaryData;

  /**
   * Creates a new summary.
   *
   * @param array $summary_data
   *   The raw summary data.
   */
  public function __construct($summary_data) {
    $this->summaryData = $summary_data;
  }

  /**
   * Gets the start index.
   *
   * @return int
   *   The start index.
   */
  public function getStart() {
    return $this->summaryData->currStart;
  }

  /**
   * Gets the end index.
   *
   * @return int
   *   The end index.
   */
  public function getEnd() {
    return $this->summaryData->currEnd;
  }

  /**
   * Gets the page size.
   *
   * @return int
   *   The page size.
   */
  public function getPageSize() {
    return $this->summaryData->numRanks;
  }

  /**
   * Gets the total result count.
   *
   * @return int
   *   The total result count.
   */
  public function getTotal() {
    return $this->summaryData->totalMatching;
  }

  /**
   * Returns the raw summary data.
   *
   * @return array
   *   The raw summary data.
   */
  public function getSummaryData() {
    return $this->summaryData;
  }

}
