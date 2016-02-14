<?php

/**
 * @file
 * Contains \Funnelback\Client.
 */

namespace Funnelback;

use GuzzleHttp\Client as HttpClient;
use GuzzleHttp\ClientInterface as HttpClientInterface;

/**
 * Funnelback client.
 */
class Client implements ClientInterface {

  /**
   * The http client.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $client;

  /**
   * The base uri.
   *
   * @var string
   */
  protected $baseUri;

  /**
   * The sub-path to the API.
   *
   * @var string
   */
  protected $subPath;

  /**
   * The response format.
   *
   * Valid values are XML, JSON, and HTML.
   *
   * @var string
   *  The response format.
   */
  protected $format;

  /**
   * The search collection.
   *
   * @var string
   */
  protected $collection;

  /**
   * Handler.
   */
  protected $handler;

  /**
   * Creates a new Funnelback client.
   *
   * @param array $config
   *   The funnelback config.
   * @param \GuzzleHttp\ClientInterface|null $client
   *   (optional) The http client.
   */
  public function __construct(array $config, HttpClientInterface $client = NULL) {
    $this->baseUri = $config['base_uri'];
    $this->subPath = isset($config['sub_path']) ? $config['sub_path'] : '';
    $format = isset($config['format']) ? $config['format'] : self::JSON_FORMAT;
    $this->setFormat($format);
    $this->collection = $config['collection'];
    $this->handler = $config['handler'];
    $this->client = $client;
  }

  /**
   * {@inheritdoc}
   */
  public function getClient() {
    $config = ['base_uri' => $this->getBaseUri() . '/s/search.' . $this->getFormat()];

    if ($handler = $this->getHandler()) {
      $config['handler'] = $handler;
    }

    if (!isset($this->client)) {
      $this->client = new HttpClient($config);
    }
    return $this->client;
  }

  /**
   * {@inheritdoc}
   */
  public function getBaseUri() {
    return $this->baseUri;
  }

  /**
   * {@inheritdoc}
   */
  public function getSubPath() {
    return $this->subPath;
  }

  /**
   * {@inheritdoc}
   */
  public function getCollection() {
    return $this->collection;
  }

  /**
   * Set the format to use.
   *
   * @param string $format
   *   The format.
   */
  protected function setFormat($format) {
    $format = trim(strtolower($format));
    if (!$this->isValidFormat($format)) {
      throw new \InvalidArgumentException(sprintf('Invalid format: %s. Allowed formats are %s',
        $format,
        implode(',', $this->allowedFormats())
      ));
    }
    $this->format = $format;
  }

  /**
   * {@inheritdoc}
   */
  public function getFormat() {
    return $this->format;
  }

  /**
   * {@inheritdoc}
   */
  public function getHandler() {
    return $this->handler;
  }

  /**
   * {@inheritdoc}
   */
  public function allowedFormats() {
    return [
      $this::XML_FORMAT,
      $this::JSON_FORMAT,
      $this::HTML_FORMAT,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function search($query, $params = []) {
    $params['query'] = $query;
    $params['collection'] = $this->getCollection();
    $http_response = $this->getClient()->get(NULL, ['query' => $params]);

    return new Response($http_response);
  }

  /**
   * Checks if the format is allowed.
   *
   * @param string $format
   *   The response format.
   *
   * @return bool
   *   TRUE if the format is allowed.
   */
  protected function isValidFormat($format) {
    return in_array($format, $this->allowedFormats());
  }
}
