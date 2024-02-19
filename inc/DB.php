<?php

namespace Toll_Integration;

use Medoo\Medoo;

class DB
{
    // DB instances
    protected $db;
    protected $configTable = "app_config";
    protected $userTable = "user";
    protected $orderTable = "order_type";
    protected $orderNumberTable = "orders";
    protected $cancelTable = "cancel_order";


    public function __construct()
    {

        $dbArgs = [
            'type' => getenv('DATABASE_TYPE'),
            'host' => getenv('DATABASE_HOST'),
            'database' => getenv('DATABASE_NAME'),
            'username' => getenv('DATABASE_USER'),
            'password' => getenv('DATABASE_PASSWORD')
        ];
        $this->db = new Medoo($dbArgs);
    }


    public function init()
    {
        $this->db->create($this->configTable, [
            "id" => [
                "INT",
                "NOT NULL",
                "AUTO_INCREMENT",
                "PRIMARY KEY"
            ],
            "config_form" => [
                "VARCHAR(30)",
                "NOT NULL"
            ],
            "config_key" => [
                "VARCHAR(30)",
                "NOT NULL",
                "UNIQUE"
            ],
            "config_value" => [
                "TEXT",
            ]
        ]);
        $this->db->create($this->userTable, [
            "id" => [
                "INT",
                "NOT NULL",
                "AUTO_INCREMENT",
                "PRIMARY KEY"
            ],
            "email" => [
                "VARCHAR(30)",
                "UNIQUE"
            ],
            "password" => [
                "VARCHAR(256)",
                "NOT NULL",

            ]
        ]);
        $this->db->create($this->orderTable, [
            "id" => [
                "INT",
                "NOT NULL",
                "AUTO_INCREMENT",
                "PRIMARY KEY"
            ],
            "order_form" => [
                "VARCHAR(30)",
                "NOT NULL"
            ],
            "prefix" => [
                "VARCHAR(30)",
                "NOT NULL",

            ],
            "order_type" => [
                "VARCHAR(30)",
                "NOT NULL",

            ]
        ]);
        $this->db->create($this->orderNumberTable, [
            "id" => [
                "INT",
                "NOT NULL",
                "AUTO_INCREMENT",
                "PRIMARY KEY"
            ],
            "order_number" => [
                "VARCHAR(30)",
                "NOT NULL",
                "UNIQUE"
            ],
            "order_name" => [
                "VARCHAR(30)",
                "NOT NULL",
                "UNIQUE"
            ],
            "status" => [
                "INT",
                "NOT NULL",

            ],
        ]);
        $this->db->create($this->cancelTable, [
            "id" => [
                "INT",
                "NOT NULL",
                "AUTO_INCREMENT",
                "PRIMARY KEY"
            ],
            "order_number" => [
                "VARCHAR(30)",
                "NOT NULL",
                "UNIQUE"
            ],
            "status" => [
                "INT",
                "NOT NULL",

            ],
        ]);
    }

    public function getInfo()
    {
        return $this->db->info();
    }


    public function getConfig($configKey, $configForm)
    {
        return $this->db->get($this->configTable, [
            "config_value"
        ], [
            "config_form" => $configForm,
            "config_key" => $configKey
        ])['config_value'];
    }

    public function hasConfigKey($config_form, $key)
    {
        return $this->db->has($this->configTable, [
            $config_form => $key
        ]);
    }


    public function getUser($column, $value)
    {
        return $this->db->get($this->userTable, [
            $column
        ], [
            "email" => $value,

        ])[$column];
    }


    public function processConfigForm($request)
    {
        $status = false;

        if ($request->action == 'shopify') {
            $formData = $request->shopify;
            if ($formData['shopify_access_token'] != $this->getConfig('shopify_access_token', 'shopify')) {
                $formData['shopify_access_token'] = $this->encryption($formData['shopify_access_token']);
            }
            if ($formData['shopify_api_key'] != $this->getConfig('shopify_api_key', 'shopify')) {
                $formData['shopify_api_key'] = $this->encryption($formData['shopify_api_key']);
            }
            if ($formData['shopify_api_secret'] != $this->getConfig('shopify_api_secret', 'shopify')) {
                $formData['shopify_api_secret'] = $this->encryption($formData['shopify_api_secret']);
            }
            if ($formData['shopify_webhook_secret'] != $this->getConfig('shopify_webhook_secret', 'shopify')) {
                $formData['shopify_webhook_secret'] = $this->encryption($formData['shopify_webhook_secret']);
            }
        }
        if ($request->action == 'toll') {
            $formData = $request->toll;
            if ($formData['sftp_password'] != $this->getConfig('sftp_password', 'toll')) {
                $formData['sftp_password'] = $this->encryption($formData['sftp_password']);
            }
        }
        if ($request->action == 'mail') {
            $formData = $request->mail;
            if ($formData['mail_password'] != $this->getConfig('mail_password', 'mail')) {
                $formData['mail_password'] = $this->encryption($formData['mail_password']);
            }
        }
        if ($request->action == 'mail_settings') {
            $formData = $request->mail_settings;
        }
        if ($request->action == 'cancel') {
            $formData = $request->cancel;
        }
        if ($request->action == 'hold') {
            $formData = $request->hold;
        }
        try {

            foreach ($formData as $key => $value) {
                if ($this->db->has($this->configTable, ["config_key" => $key])) {
                    $this->db->update(
                        $this->configTable,
                        [
                            "config_value" => $value
                        ],
                        [
                            "config_form" => $request->action,
                            "config_key" => $key

                        ]
                    );
                } else {
                    $this->db->insert(
                        $this->configTable,
                        [
                            "config_form" => $request->action,
                            "config_key" => $key,
                            "config_value" => $value
                        ]
                    );
                }
            }
            $status = true;
            return ["status" => $status];
        } catch (\Throwable $th) {
            $this->log->log($th->getMessage(), 'error');
            $status = false;
        }




        $_SESSION["status"] = $status;
        loadRoute(getRoute(CURRENTPAGE));
    }
    public function processOrderForm($request)
    {

        $status = false;

        if ($request->action == 'order') {
            $formData = $request->order;
        }
        if ($request->action == 'email') {
            $formData = $request->email;
        }
        if ($request->action == 'order_update') {
            $formData = $request->order;
        }
        if ($request->action == 'email_update') {
            $formData = $request->email;
        }
        if ($request->action == 'delete') {
            $formData = $request->table_id;
        }

        try {

            if (($request->action == 'email_update' || $request->action == 'order_update') && isset($formData['id']) && $this->db->has($this->orderTable, ["id" => $formData['id']])) {
                $this->db->update(
                    $this->orderTable,
                    [
                        "prefix" => strtoupper($formData['prefix']),
                        "order_type" => strtoupper($formData['order_type'])
                    ],
                    [
                        "id" => $formData['id']

                    ]
                );
            } elseif ($request->action == 'delete' && isset($formData['id'])) {
                $this->db->delete(
                    $this->orderTable,
                    ["id" => $formData['id']]
                );
            } else {
                $this->db->insert(
                    $this->orderTable,
                    [
                        "order_form" => $request->action,
                        "prefix" => strtoupper($formData['prefix']),
                        "order_type" => strtoupper($formData['order_type'])
                    ]
                );
            }

            $status = true;
            return ["status" => $status];
        } catch (\Throwable $th) {
            $this->log->log($th->getMessage(), 'error');
            $status = false;
        }
    }
    public function processOrderNumber($orderDetails)
    {

        try {

            if ($orderDetails['action'] == 'update') {
                $this->db->update(
                    $this->orderNumberTable,
                    [
                        "order_number" => $orderDetails['order_number'],
                        "status" => $orderDetails['status']
                    ],
                    [
                        "id" => $orderDetails['id']

                    ]
                );
            } else {
                $this->db->insert(
                    $this->orderNumberTable,
                    [
                        "order_number" => $orderDetails['order_number'],
                        "order_name" => $orderDetails['order_name'],
                        "status" => $orderDetails['status']
                    ]
                );
            }
        } catch (\Throwable $th) {
            $this->log->log($th->getMessage(), 'error');
        }
    }
    public function processCancel($orderDetails)
    {

        try {

            if ($orderDetails['action'] == 'update') {
                $this->db->update(
                    $this->cancelTable,
                    [
                        "order_number" => $orderDetails['order_number'],
                        "status" => $orderDetails['status']
                    ],
                    [
                        "id" => $orderDetails['id']

                    ]
                );
            } else {
                $this->db->insert(
                    $this->cancelTable,
                    [
                        "order_number" => $orderDetails['order_number'],
                        "status" => $orderDetails['status']
                    ]
                );
            }
        } catch (\Throwable $th) {
            $this->log->log($th->getMessage(), 'error');
        }
    }

    public function getOrderType($orderName, $email, $order_number, $customerName)
    {

        $orderData = $this->db->select($this->orderTable, [
            "id",
            "order_form",
            "prefix",
            "order_type"
        ]);
        $orderType = '';
        $emailType = '';
        foreach ($orderData as $key => $value) {
            $keyword = $value['order_form'] == 'email' ?  '@' . $value['prefix'] : '';
            if (!empty($email)) {
                if (!empty($keyword) && preg_match("/$keyword/i", $email)) {
                    $emailType = $value['order_type'];
                    return $emailType;
                }
            }
        }

        foreach ($orderData as $key => $value) {
            $keyword = $value['order_form'] == 'email' ?  '@' . $value['prefix'] : $value['prefix'];
            preg_match('/([A-z][0-9]?[A-z]+)[-\d]{3}.+/i', $orderName, $matches);
            if (isset($matches[1]) && $matches[1] == 'GBP') {
                if (preg_match("/THREAD/i", $customerName)) {
                    return "TH";
                }
            }
            if (isset($matches[1]) && $matches[1] == $keyword) {
                $orderType = $value['order_type'];
                return $orderType;
            }
        }
        return "N/A";
    }

    public function getOrders()
    {
        $orders = $this->db->select($this->orderNumberTable, "*", ['status' => 0]);
        return $orders;
    }
    public function getCancelOrders()
    {
        $orders = $this->db->select($this->cancelTable, "*", ['status' => 0]);
        return $orders;
    }
    public function getOrderData()
    {
        $orderData = $this->db->select($this->orderTable, [
            "id",
            "order_form",
            "prefix",
            "order_type"
        ]);
        return $orderData;
    }


    public function encryption($string)
    {
        $encryption_key = "toll";
        $encrypted_string = openssl_encrypt($string, "AES-128-ECB", $encryption_key);
        return $encrypted_string;
    }
    public function decryption($string)
    {
        $encryption_key = "toll";
        $decrypted_string = openssl_decrypt($string, "AES-128-ECB", $encryption_key);
        return $decrypted_string;
    }
}
