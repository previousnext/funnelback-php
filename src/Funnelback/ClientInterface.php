<?php

/**
 * @file
 * Contains \Funnelback\ClientInterface
 */

namespace Funnelback;

/**
 * Provides an interface for the Funnelback client.
 */
interface ClientInterface {

  const XML_FORMAT = "xml";

  const JSON_FORMAT = "json";

  const HTML_FORMAT = "html";

  /**
   * Gets the http client.
   *
   * @return \GuzzleHttp\ClientInterface
   *   The http client.
   */
  public function getClient();

  /**
   * Returns the base uri.
   *
   * @return string
   *   The base uri.
   */
  public function getBaseUri();

  /**
   * Returns the sub-path.
   *
   * @return string
   *   The sub-path.
   */
  public function getSubPath();

  /**
   * The search collection.
   *
   * @return string
   *   The collection.
   */
  public function getCollection();

  /**
   * The default format.
   *
   * @return string
   *   The format.
   */
  public function getFormat();

  /**
   * Returns the allowed formats.
   *
   * @return array
   *   The allowed formats.
   */
  public function allowedFormats();

  /**
   * Perform a search query.
   *
   * @param string $query
   *   The query terms.
   * @param array $params
   *   (optional) The search parameters.
   *
   * @return \Funnelback\Response
   *   The search response.
   */
  public function search($query, $params = []);

}
