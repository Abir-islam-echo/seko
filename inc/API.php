<?php

namespace Toll_Integration;

use Shopify\Clients\Rest;
use Shopify\Clients\Graphql;
use Shopify\Context;
use Shopify\Auth\FileSessionStorage;

class API
{

    protected $clientRest;
    protected $clientGraphql;
    protected $db;
    protected $log;
    public $status;

    public function __construct()
    {
        $this->db = new DB();
        $this->log = new Log();
        //Setup Shopify API
        $this->status = false;
        try {
            Context::initialize(
                $this->db->decryption($this->db->getConfig('shopify_api_key', 'shopify')),
                $this->db->decryption($this->db->getConfig('shopify_api_secret', 'shopify')),
                $this->db->getConfig('shopify_api_scope', 'shopify'),
                $this->db->getConfig('shopify_shop', 'shopify'),
                new FileSessionStorage(APP_DIR . '/tmp/php_sessions'),
                '2021-04',
                true,
                false,
            );
            $this->clientRest = new Rest($this->db->getConfig('shopify_shop', 'shopify'), $this->db->decryption($this->db->getConfig('shopify_access_token', 'shopify')));
            $this->clientGraphql = new Graphql($this->db->getConfig('shopify_shop', 'shopify'), $this->db->decryption($this->db->getConfig('shopify_access_token', 'shopify')));
            if ($this->clientRest->get('shop')->getStatusCode() == 200) {
                $this->status = true;
            } else {
                $this->status = false;
            }
        } catch (\Throwable $th) {
            $this->status = false;
        }
    }

    public function getAllProducts($published_status = '')
    {
        $products = $this->clientRest->get(
            "products",
            [
                "published_status" => $published_status
            ]
        );
        if ($products->getStatusCode() == 200) {
            return $products->getDecodedBody();
        }
        return [];
    }

    public function getAllOrders($status = 'any')
    {
        $orders = $this->clientRest->get(
            "orders",
            [
                "status" => $status
            ]
        );
        if ($orders->getStatusCode() == 200) {
            return $orders->getDecodedBody();
        }
        return [];
    }

    public function getProduct($productID)
    {
        $products = $this->clientRest->get(
            "products/" . $productID
        );
        if ($products->getStatusCode() == 200) {
            return $products->getDecodedBody();
        }
        return [];
    }

    public function getOrder($orderID)
    {
        // echo '<pre> data from API.php';
        // print_r($orderID);
        // echo '</pre>';
        $orders = $this->clientRest->get(
            "orders/" . $orderID
        );
        if ($orders->getStatusCode() == 200) {
            return $orders->getDecodedBody();
        }
        return [];
    }

    public function getFulfillment($orderID)
    {
        $fulfillment = $this->clientRest->get(
            "orders/" . $orderID . "/fulfillment_orders"
        );
        if ($fulfillment->getStatusCode() == 200) {
            return $fulfillment->getDecodedBody();
        }
        return [];
    }


    public function updateOrder($orderID, $data)
    {
        $orders = $this->clientRest->put(
            "orders/" . $orderID,
            [
                "order" => $data
            ]
        );
        if ($orders->getStatusCode() == 200) {
            return $orders->getDecodedBody();
        }
        return [];
    }

    public function getLocation($inventory_id)
    {
        $location = $this->clientRest->get(
            "inventory_items/" . $inventory_id . "/inventory_levels",
        );

        if ($location->getStatusCode() == 200) {
            return $location->getDecodedBody();
        }
        return [];
    }

    public function checkAvailableItemProperty($order, $name, $value)
    {
        foreach ($order['line_items'] as $key => $item) {
            if (isset($item['properties'])) {
                foreach ($item['properties'] as $key => $property) {
                    if ($property['name'] == $name && $property['value'] == $value) {
                        return $property['value'];
                    }
                }
            }
        }
        return false;
    }


    public function fullFillmentOrder($data)
    {

        // getting order ID from XML file
        $orderID = $data['customer_order_id'];

        // echo '<pre> data from API.php';
        // print_r($data);
        // echo '</pre>';

        //AB 6:30pm 1/17/2024
        if (str_contains($orderID, 'GE') && str_contains($orderID, 'GB')) {
            return true;
        }
        //AB 6:30pm 1/17/2024

        $orderData = $this->getOrder($orderID);

        if (empty($orderData)) {
            return false;
        }


        // get fulfillment data for a particular order
        $fulfillmentData = $this->getFulfillment($orderID);


        // fetch order info from order ID
        $orderArray = $orderData['order'];

        // stopping fulfillment for personalised order
        if (str_contains($orderArray['tags'], 'personalised') || $this->checkAvailableItemProperty($orderArray, '_type', 'personalised')) {
            return true;
        }


        /**
         * getting Products sku id from the despatched xml file
         */
        preg_match('/([A-z][0-9]?[A-z]+)[-\d]{3}.+/i', $data['order_id'], $prefix);

        foreach ($data['line_items'] as $product) {
            $products_disp_items[] = [
                "quantity" => $product['quantity'],
                "barcode" => $product['barcode'],
            ];
        }

        /**
         * getting Products  id from the order Array
         */
        foreach ($orderArray['line_items'] as $product) {
            $products_get_ids[] = $product['product_id'];
        }

        $products_get_ids = array_values(array_unique($products_get_ids));

        foreach ($products_get_ids as $product_id) {
            $productArray = $this->getProduct($product_id)['product'];
            foreach ($productArray['variants'] as $variant) {
                foreach ($products_disp_items as $products_disp_item) {
                    if ($variant['barcode'] == $products_disp_item['barcode']) {
                        $products_skus[] = [
                            "sku" => $variant['sku'],
                            "quantity" => $products_disp_item['quantity'],
                        ];
                        $inventory_item_ids[] = $variant['inventory_item_id'];
                    }
                }
            }
        }

        /**
         * Genrating line items and products id array from order array based on despatched xml's sku
         */
        foreach ($orderArray['line_items'] as $line_item) {
            foreach ($products_skus as $product_sku) {
                if ($product_sku['sku'] == $line_item['sku']) {
                    $line_items_ids[] = [
                        "id" => $line_item['id'],
                        "quantity" => $product_sku['quantity'],
                        "fulfillment_status" => $line_item['fulfillment_status'],
                    ];
                }
            }
        }

        /** Filtering already fulfillItems */
        // echo '<pre> line_items_ids';
        // print_r($line_items_ids);
        // echo '</pre>';
        foreach ($line_items_ids as $key => $line_items_id) {
            if ($line_items_id['fulfillment_status'] == 'fulfilled') {
                unset($line_items_ids[$key]);
            }
        }
        // echo '<pre> post line_items_ids';
        // print_r($line_items_ids);
        // echo '</pre>';
        if (empty($line_items_ids)) {
            return true;
        }

        /**
         * Fulfillment request send to mark order as fulfilled
         */
        $location_line_items = [];
        foreach ($inventory_item_ids as $key => $inventory_item_id) {
            $locations = $this->getLocation($inventory_item_id)['inventory_levels'];
            foreach ($locations as $location) {
                if (isset($line_items_ids[$key])) {
                    $location_line_items[$location['location_id']][] = [
                        "id" => $line_items_ids[$key]['id'],
                        "quantity" => $line_items_ids[$key]['quantity'],
                    ];
                }
            }
        }

        // prepare fulfillment data for creating post request for fulfillment

        foreach ($fulfillmentData['fulfillment_orders'] as $fulfillment_order) {
            $fulfillment_order_id = $fulfillment_order['id'];
            $line_items = [];

            foreach ($location_line_items as $key => $location_line_item) {
                if ($key == $fulfillment_order['assigned_location']['location_id']) {
                    foreach ($fulfillment_order['line_items'] as $line_item) {

                        foreach ($location_line_item as $key1 => $location_line) {
                            if ($location_line['id'] == $line_item['line_item_id']) {

                                $line_items[] = [
                                    'id' => $line_item['id'],
                                    'quantity' => $location_line['quantity'],
                                ];
                            }
                        }

                    }
                }
            }

            $line_items_by_fulfillment_order = [
                'fulfillment_order_id' => $fulfillment_order_id,
                'fulfillment_order_line_items' => $line_items,
            ];

            // 1/26/2024
            if (!empty($data['tracking_company']) && str_contains($data['tracking_company'], 'DPD')) {
                $fulfillment_data_required = [
                    "notify_customer" => true,
                    "tracking_info" => [
                        "number" => $data['tracking_number'],
                        "url" => $data['tracking_url'],
                        "company" => 'DPD UK'
                    ],
                    'line_items_by_fulfillment_order' => [
                        $line_items_by_fulfillment_order
                    ]
                ];
            } else {
                $fulfillment_data_required = [
                    "notify_customer" => true,
                    "tracking_info" => [
                        "number" => $data['tracking_number'],
                    ],
                    'line_items_by_fulfillment_order' => [
                        $line_items_by_fulfillment_order
                    ]
                ];
            }
            // 1/26/2024

            $fulfillment_post_data_required = [
                "fulfillment" => $fulfillment_data_required,
            ];

            // post request send for induvidual fulfillment order
            $orders = $this->clientRest->post(
                "fulfillments",
                $fulfillment_post_data_required
            );

        }

        // echo '<pre> api getDecodedBody()';
        // print_r($orders->getDecodedBody());

        if ($orders->getStatusCode() == 201) {
            return true;
        } else {
            $this->log->log($orderID . ' cannot be processed', 'error');
        }

    }

    public function getStatus()
    {
        return $this->clientRest->get('shop')->getStatusCode();
    }

    /**
     * Verifying webhook that the request is coming from authentic soruce
     */
    public function verifyWebhook($data, $hmac_header)
    {
        $calculated_hmac = base64_encode(hash_hmac('sha256', $data, $this->db->decryption($this->db->getConfig('shopify_webhook_secret', 'shopify')), true));
        return hash_equals($hmac_header, $calculated_hmac);
    }
}