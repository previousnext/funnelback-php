<?php

/**
 * @file
 * Contains Funnelback\FacetItem
 */

namespace Funnelback;

/**
 * A facet data item.
 */
class FacetItem {

  /**
   * The label.
   *
   * @var string
   */
  protected $label;

  /**
   * The count.
   *
   * @var int
   */
  protected $count;

  /**
   * The query string parameter.
   *
   * @var string
   */
  protected $queryStringParam;

  /**
   * The raw facet item data.
   *
   * @var array
   */
  protected $facetItemData;

  /**
   * Creates a new facet item.
   *
   * @param array $facet_item_data
   *   The raw facet item data.
   */
  public function __construct($facet_item_data) {
    $this->facetItemData = $facet_item_data;
    $this->label = $facet_item_data->label;
    $this->count = $facet_item_data->count;
    $this->queryStringParam = $facet_item_data->queryStringParam;
  }

  /**
   * Gets the count.
   *
   * @return int
   *   The count.
   */
  public function getCount() {
    return $this->count;
  }

  /**
   * Gets the raw facet item data.
   *
   * @return array
   *   The raw facet item data.
   */
  public function getFacetItemData() {
    return $this->facetItemData;
  }

  /**
   * Gets the label.
   *
   * @return string
   *   The label.
   */
  public function getLabel() {
    return $this->label;
  }

  /**
   * Gets the query string param.
   *
   * @return string
   *   The query string param.
   */
  public function getQueryStringParam() {
    return $this->queryStringParam;
  }

}
