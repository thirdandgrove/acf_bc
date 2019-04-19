<?php

namespace Drupal\acf_bc\Plugin\Connection;

use Drupal\acf_bc\Form\BigCommerceSettingsForm as Form;
use Drupal\basic_data\Entity\BasicDataInterface;
use Drupal\connection\Plugin\ConnectionBase;
use GuzzleHttp\Exception\RequestException;

/**
 * Class BigCommerceConnection
 *
 * @package Drupal\connection\Plugin\DrupalConnection
 *
 * @Connection(
 *   id = "bc_connection",
 *   label = @Translation("BigCommerce Connection"),
 * )
 */
class BigCommerceConnection extends ConnectionBase {

  /**
   * BC Product data endpoint pattern
   * @const ENDPOINT
   */
  const ENDPOINT = '%s/%s/v3/catalog/products?include=variants,images';

  /**
   * {@inheritdoc}
   */
  public function response($url)
  {
    // Get token and client ID from config.
    $config = $this->config->get(Form::CONFIG_KEY);
    $req_options = [
      'headers' => [
        'X-Auth-Token' => $config->get('token'),
        'X-Auth-Client' => $config->get('client_id'),
      ],
    ];

    // Run products request.
    try {
      $endpoint = sprintf(self::ENDPOINT, $url, $config->get('store_id'));
      $response = $this->httpClient->request('GET', $endpoint, $req_options);
      $status = $response->getStatusCode();
      if ($status == 200) {
        $body = json_decode($response->getBody()->getContents(), TRUE);
        $data = ($body['data'] ?? []);

        $results = array_filter(array_map(
          function($product) {
            if (!strlen(($product['sku'] ?? ''))) {
              return NULL;
            }

            return [
              'title'       => ($product['name'] ?? ''),
              'description' => ($product['description'] ?? ''),
              'sku'         => ($product['sku'] ?? ''),
              'price'       => ($product['price'] ?? 0),
              'category'    => ($product['categories'] ?? []),
              'images'      => $this->handleImages($product),
              'variants'    => $this->handleVariants($product),
            ];
          },
          $data
        ));

        return json_encode($results);
      }
      else {
        return $response->withStatus($status);
      }
    } catch (RequestException $e) {
      $this->logger->error($e->getMessage());
    }
  }

  /**
   * Parse product image URLs.
   *
   * @param array $product BC Product data
   *
   * @return string[]
   */
  private function handleImages(array $product)
  {
    $images = ($product['images'] ?? []);
    if (!is_array($images)) {
      return [];
    }

    return array_filter(array_map(
      function ($image) {
        return ($image['url_standard'] ?? NULL);
      },
      $images
    ));
  }

  /**
   * Parse product variants and create / update storage.
   *
   * @param array $product BC Product data
   *
   * @return int[]
   */
  private function handleVariants($product)
  {
    $variants = ($product['variants'] ?? []);
    if (!is_array($variants)) {
      return [];
    }

    $handler = \Drupal::service('basic_data.handler');
    $storage = $handler->getStorage();

    return array_filter(array_map(
      function ($variant) use ($handler, $storage) {
        $sku = ($variant['sku'] ?? NULL);
        if (!strlen($sku)) {
          return NULL;
        }

        $options = ($variant['option_values'] ?? []);
        $price = ($variant['calculated_price'] ?? 0);
        $image_url = ($variant['image_url'] ?? '');

        // Load existing basic data or create new.
        $existing_data = $storage->loadByProperties([
          'type' => 'variant',
          'field_sku' => $sku,
        ]);

        if (is_array($existing_data) && !empty($existing_data)) {
          $existing_data = reset($existing_data);
        }

        if ($existing_data instanceof BasicDataInterface) {
          $data = $existing_data;
        }
        else {
          $data = $handler->createBasicData('variant', json_encode($options));
        }

        $data->setName($sku);
        $data->setData(json_encode($options));
        $data->set('field_sku', $sku);
        $data->set('field_price', $price);
        $data->set('field_image_url', $image_url);
        $handler->saveData($data);

        return $data->id();
      },
      $variants
    ));
  }
}
