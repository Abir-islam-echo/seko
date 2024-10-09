<?php

namespace Toll_Integration;

class XML
{

    protected $xml;
    protected $api;
    protected $remoteSftp;
    protected $db;

    public function __construct()
    {
        $this->api = new API();
        $this->db = new DB();
        $this->remoteSftp = new \Toll_Integration\RemoteSFTP();

    }

    // function isNorthernIrelandDelivery
    public function isNorthernIrelandDelivery($order)
    {
        if (
            ($order['shipping_address']['zip'][0] == 'B' && $order['shipping_address']['zip'][1] == 'T')
            || ($order['shipping_address']['zip'][0] == 'I' && $order['shipping_address']['zip'][1] == 'M')
            || ($order['shipping_address']['zip'][0] == 'J' && $order['shipping_address']['zip'][1] == 'E')
            || ($order['shipping_address']['zip'][0] == 'G' && $order['shipping_address']['zip'][1] == 'Y')
        ) {
            return true;
        }
        return false;
    }
    // function is Staff from C&P
    public function isStaff($order)
    {
        if (str_contains($order['contact_email'], '@chintiandparker.com')) {
            return true;
        }
        return false;
    }

    // AB Check order note attribute
    public function isNoteAttributeAvailable($order, $attributeValue)
    {
        foreach ($order['note_attributes'] as $note_attribute) {
            if ($note_attribute['name'] == $attributeValue) {
                return $note_attribute['value'];
            }
        }
        return false;
    }
    // AB Check order note attribute

    public function removeSpecificItems($order, $productIdsToRemove)
    {
        // Filter out items whose product_id matches any of the IDs in $productIdsToRemove
        $order['line_items'] = array_values(array_filter($order['line_items'], function ($lineItem) use ($productIdsToRemove) {
            // Check if the line item's product_id is not in the array of IDs to remove
            return !in_array($lineItem['product_id'], $productIdsToRemove);
        }));

        return $order;
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

    public function generateOrdersXML($orderID = '')
    {

        if (!empty($orderID)) {
            $orders[] = $this->api->getOrder($orderID) ? $this->api->getOrder($orderID)['order'] : [];
        } else {
            $orders = $this->api->getAllOrders()['orders'];
        }


        if (empty($orders[0])) {
            return;
        }

        foreach ($orders as $key => $order) {
            $this->isNorthernIrelandDelivery($order);
            echo '<pre> XML ORDER data ';
            // print_r($order['shipping_address']['zip'][0]);
            // print_r(str_contains($order['shipping_address']['zip'], 'BT'));
            echo '</pre>';

            if ((str_contains($order['shipping_lines'][0]['title'], 'Express Courier') || str_contains($order['shipping_lines'][0]['title'], 'Standard Courier')) && (!str_contains($order['tags'], 'Globale::'))) {

                return false;

            }
            ;

            if (str_contains($order['name'], 'SGC-')) {
                return false;
            }



            //check if the order contains only tattoo sheet, then it will not go to the seko end.
            if (count($order['line_items']) === 1) {
                //need to change the tattoo sheet product id
                if ($order['line_items'][0]['product_id'] === 6886488473678) {
                    break;
                }

                //need to change the gift card product id
                if ($order['line_items'][0]['product_id'] === 1718097084531) {
                    break;
                }
            }
            ;
            echo '<pre> XML ORDER';
            print_r($order);
            echo '</pre>';

            $lineItemsArray = $order['line_items'];
            $isPackageType7 = false;
            $isSelectedJumper = array_filter($lineItemsArray, array($this, "checkedJumper"));
            $isSelectedTattoos = array_filter($lineItemsArray, array($this, "checkedTattoo"));
            $isSelectedGiftCard = array_filter($lineItemsArray, array($this, "checkedGiftCardItem"));
            $isSelectedOtherItems = array_filter($lineItemsArray, array($this, "checkedOtherItems"));

            // product IDs to remove
            // $swapProtectProductId = 7404001919054;
            // $personalisationChargeProductId = 14586113556859;

            // Define the product IDs for items to remove
            $productIdsToRemove = [7404001919054, 14586113556859];

            // Remove the specified items from the order
            $order = $this->removeSpecificItems($order, $productIdsToRemove);

            if (count($isSelectedJumper) === 0 && count($isSelectedTattoos) >= 1 && count($isSelectedOtherItems) >= 1) {
                //no selected Jumpers but tattoo sheet and another items
                $order = $this->removeTattoofromItems($order);
            }
            ;

            if (count($isSelectedJumper) >= 1 && count($isSelectedTattoos) >= 1 && count($isSelectedOtherItems) >= 1) {
                //no selected Jumpers but tattoo sheet and another items
                $order = $this->removeTattoofromItems($order);
            }
            ;

            if (count($isSelectedJumper) >= 1 && count($isSelectedTattoos) >= 1 && count($isSelectedOtherItems) === 0) {
                //no selected Jumpers but tattoo sheet and another items
                $order = $this->removeTattoofromItems($order);
            }

            if (count($isSelectedGiftCard) >= 1) {
                $order = $this->removeGiftCardfromItems($order);
            }

            //if any order has seleceted jumpers then, specific package_type will be added.
            // if (count($isSelectedJumper) >= 1) {
            //     $isPackageType7 = true;
            // }

            $xmlFile = APP_DIR . '/xml/SHOPIFY_' . date('Ymd_hms') . '_' . preg_replace('/[^A-Za-z0-9\-]/', '', $order['name']) . '.xml';
            $ordersXML = new \SimpleXMLElement('<Requests></Requests>');


            if (isset($order['name']) && str_contains($order['name'], 'FF-')) {
                $order = $this->ffOrder($order);
            }

            if (isset($order['name']) && str_contains($order['name'], 'HR-')) {
                $order = $this->hrOrder($order);
            }


            $ordersXML->addChild('Request');

            $ordersXML->Request->addChild('WebSalesOrder');
            isset($order['name']) ? $ordersXML->Request->WebSalesOrder->addChild('SalesOrderNumber', $order['name']) : $ordersXML->Request->WebSalesOrder->addChild('SalesOrderNumber', ' ');

            if (!empty($order['note_attributes']) && str_contains($order['tags'], 'Globale::') !== false) {
                $this->isNoteAttributeAvailable($order, "GEOrderId") && $ordersXML->Request->WebSalesOrder->addChild('SalesOrderReference', $this->isNoteAttributeAvailable($order, "GEOrderId"));
            } else if (!empty($order['note_attributes']) && str_contains($order['tags'], 'The Bay') !== false) {
                $this->isNoteAttributeAvailable($order, "Order_id") && $ordersXML->Request->WebSalesOrder->addChild('SalesOrderReference', $this->isNoteAttributeAvailable($order, "Order_id"));

                $this->isNoteAttributeAvailable($order, "Mirakl order link") && $ordersXML->Request->WebSalesOrder->addChild('OrderLink', $this->isNoteAttributeAvailable($order, "Mirakl order link"));
            } elseif (!empty($order['note_attributes']) && str_contains($order['name'], '#EBay-') !== false) {
                $this->isNoteAttributeAvailable($order, "eBay Order Id") && $ordersXML->Request->WebSalesOrder->addChild('SalesOrderReference', $this->isNoteAttributeAvailable($order, "eBay Order Id"));
                $ordersXML->Request->WebSalesOrder->addChild('SalesInvoiceNumber', $order['id']);
            } else {
                isset($order['name']) ? $ordersXML->Request->WebSalesOrder->addChild('SalesOrderReference', $order['id']) : $ordersXML->Request->WebSalesOrder->addChild('SalesOrderReference', ' ');
            }

            isset($order['created_at']) ? $ordersXML->Request->WebSalesOrder->addChild('SalesOrderDate', $order['created_at']) : $ordersXML->Request->WebSalesOrder->addChild('SalesOrderDate', ' ');
            isset($order['currency']) ? $ordersXML->Request->WebSalesOrder->addChild('CurrencyCode', $order['currency']) : $ordersXML->Request->WebSalesOrder->addChild('CurrencyCode', ' ');



            // JL AB 12/6/2023
            if (str_contains($order['name'], '#JL') !== false && str_contains($order['tags'], 'Globale::') === false) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'Royal Mail');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'RMTRACKEDSTDNOSIG');
            }
            // JL AB 12/6/2023

            // in between 5th july to 12th July
            if (str_contains($order['name'], '#GBP') !== false && str_contains($order['tags'], 'Globale::') === false) {

                if (isset($order['shipping_lines']) && isset($order['shipping_lines'][0]['title']) && str_contains($order['shipping_lines'][0]['title'], 'Standard Delivery') !== false) {
                    $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'Royal Mail');
                    $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'RMTRACKEDSTDNOSIG');
                }
                if ($this->isNorthernIrelandDelivery($order) && isset($order['shipping_lines']) && isset($order['shipping_lines'][0]['title']) && str_contains($order['shipping_lines'][0]['title'], 'Express Delivery') !== false) {
                    $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'Royal Mail');
                    $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'RMTRACKEDNDNOSIG');
                }

                // disable before 7:00PM 15th December AB
                // if (isset($order['shipping_lines']) && isset($order['shipping_lines'][0]['title']) && str_contains($order['shipping_lines'][0]['title'], 'Next Working Day') !== false) {
                //     $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'Royal Mail');
                //     $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'RMTRACKEDNDNOSIG');
                // }
                // enable on 15th January AB

                // enable before 7:00PM 15th December AB
                if (isset($order['shipping_lines']) && isset($order['shipping_lines'][0]['title']) && str_contains($order['shipping_lines'][0]['title'], 'Next Working Day') !== false) {
                    $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'DPD');
                    $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'PLNEXTDAY');
                }
                // disable on 15th January AB

                if (isset($order['shipping_lines']) && isset($order['shipping_lines'][0]['title']) && str_contains($order['shipping_lines'][0]['title'], 'Saturday Delivery') !== false) {
                    $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'DPD');
                    $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'PLSAT');
                }
                if (isset($order['shipping_lines']) && isset($order['shipping_lines'][0]['title']) && str_contains($order['shipping_lines'][0]['title'], 'Free shipping') !== false) {
                    $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'Royal Mail');
                    $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'RMTRACKEDSTDNOSIG');
                }
                if (isset($order['shipping_lines']) && isset($order['shipping_lines'][0]['title']) && str_contains($order['shipping_lines'][0]['title'], 'Seko Omni Returns Shipping charge') !== false) {
                    $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'Royal Mail');
                    $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'RMTRACKEDSTDNOSIG');
                }

                if (count($isSelectedTattoos) >= 1) {
                    $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'Royal Mail');
                    $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'RMTRACKEDSTDNOSIG');
                }

            }

            if (str_contains($order['name'], '#GBP') === false && isset($order['shipping_lines']) && isset($order['shipping_lines'][0]['title']) && str_contains($order['shipping_lines'][0]['title'], 'Standard Delivery') !== false) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'Royal Mail');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'RMTRACKEDNDNOSIG');
            }

            if (str_contains($order['name'], '#GBP') === false && isset($order['shipping_lines']) && isset($order['shipping_lines'][0]['title']) && str_contains($order['shipping_lines'][0]['title'], 'Free shipping') !== false) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'DHL');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'DHLIREM');
            }
            // spacial case AB
            // if (str_contains($order['name'], '#GBP') !== false && isset($order['shipping_lines']) && isset($order['shipping_lines'][0]['title']) && str_contains($order['shipping_lines'][0]['title'], 'Express Courier (Air)') !== false) {
            //     $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'DHL');
            //     $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'DHLIREM');
            // }
            // spacial case AB

            if (str_contains($order['name'], '#GBP') === false && isset($order['shipping_lines']) && isset($order['shipping_lines'][0]['title']) && str_contains($order['shipping_lines'][0]['title'], 'Next Working Day') !== false) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'DPD');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'PLNEXTDAY');
            }

            if (str_contains($order['name'], '#B2B') !== false && str_contains($order['name'], '#B2BUSD') === false) {
                if (str_contains($order['shipping_lines'][0]['title'], 'Standard International') !== false) {
                    $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'DHL');
                    $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'DHLIREM');
                } else {
                    $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'DPD');
                    $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'PLNEXTDAY');
                }

            }

            if (str_contains($order['tags'], 'Harper') !== false) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'DPD');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'PLNEXTDAY');
            }


            if (isset($order['shipping_lines']) && isset($order['shipping_lines'][0]['title']) && str_contains($order['shipping_lines'][0]['title'], 'International Shipping') !== false) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'DHL');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'DHLIREM');
            }

            if (str_contains($order['name'], 'LDC') !== false && str_contains($order['shipping_lines'][0]['title'], 'UK Shipping') !== false) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'DPD');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'PLNEXTDAY');
            }

            if (str_contains($order['tags'], 'Globale::') !== false) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'GLOBALE');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', ' ');
            }

            if (str_contains($order['tags'], 'Faire') !== false) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'Customer Collect');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', ' ');
            }

            if (str_contains($order['name'], 'FF-') || str_contains($order['name'], 'HR-')) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'Customer Collect');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', ' ');
            }

            if (isset($orders[0]['tags']) && str_contains($orders[0]['tags'], '24S')) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'Customer Collect');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', ' ');
            } elseif (isset($orders[0]['tags']) && str_contains($orders[0]['tags'], 'IFCHIC')) {

                $countryName = strtolower($order['shipping_address']['country']);

                if (str_contains($countryName, 'taiwan') || str_contains($countryName, 'united states')) {
                    $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'DHL');
                    $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'DHLIREM');
                    $ordersXML->Request->WebSalesOrder->addChild('ShippingTerm', 'DDP');
                } else {
                    $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'DHL');
                    $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'DHLIREM');
                    $ordersXML->Request->WebSalesOrder->addChild('ShippingTerm', 'DDU');
                }

            } else if (isset($orders[0]['tags']) && str_contains($orders[0]['tags'], 'Shop Premium Outlets')) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'DHL');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'DHLIREM');
            } elseif (isset($orders[0]['tags']) && str_contains($orders[0]['tags'], 'The Bay')) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'DHL');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'DHLIREM');
                $ordersXML->Request->WebSalesOrder->addChild('ShippingTerm', 'DDP');
            } elseif (str_contains($order['name'], 'ZL') !== false) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'Royal Mail');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'RMTRACKEDSTDNOSIG');
                if (!empty($order['note_attributes'])) {
                    $this->isNoteAttributeAvailable($order, "Delivery note URL 1") && $ordersXML->Request->WebSalesOrder->addChild('SpecialInstructions', $this->isNoteAttributeAvailable($order, "Delivery note URL 1"));
                }
            } elseif (str_contains($order['name'], '#EBay-') !== false || str_contains($order['name'], 'SW-') !== false) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'Royal Mail');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'RMTRACKEDSTDNOSIG');
            }


            if ((isset($orders[0]['tags']) && str_contains($orders[0]['tags'], '24S')) || (isset($orders[0]['tags']) && str_contains($orders[0]['tags'], 'The Bay'))) {
                $ordersXML->Request->WebSalesOrder->addChild('Notes', 'Package_Type_6');
                $ordersXML->Request->WebSalesOrder->addChild('GroupReference', 'Package_Type_6');
            } elseif (isset($orders[0]['tags']) && str_contains($orders[0]['tags'], 'Harper')) {
                $ordersXML->Request->WebSalesOrder->addChild('Notes', 'Package_Type_4');
                $ordersXML->Request->WebSalesOrder->addChild('GroupReference', 'Package_Type_4');
            } elseif (isset($orders[0]['tags']) && str_contains($orders[0]['tags'], 'Faire')) {
                $ordersXML->Request->WebSalesOrder->addChild('Notes', 'Package_Type_8');
                $ordersXML->Request->WebSalesOrder->addChild('GroupReference', 'Package_Type_8');
            } elseif (isset($order['shipping_lines']) && isset($order['shipping_lines'][0]['title']) && str_contains($order['shipping_lines'][0]['title'], 'OFFICE') !== false) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'Royal Mail');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'RMTRACKEDSTDNOSIG');
                $ordersXML->Request->WebSalesOrder->addChild('Notes', 'Package_Type_8');
                $ordersXML->Request->WebSalesOrder->addChild('GroupReference', 'Package_Type_8');
            } elseif (isset($order['shipping_lines']) && isset($order['shipping_lines'][0]['title']) && str_contains($order['shipping_lines'][0]['title'], 'FREE') !== false) {
                $ordersXML->Request->WebSalesOrder->addChild('CourierName', 'Royal Mail');
                $ordersXML->Request->WebSalesOrder->addChild('CourierService', 'RMTRACKEDSTDNOSIG');
                $ordersXML->Request->WebSalesOrder->addChild('Notes', 'Package_Type_1');
                $ordersXML->Request->WebSalesOrder->addChild('GroupReference', 'Package_Type_1');
            } elseif (str_contains($order['name'], '#B2B') !== false) {
                $ordersXML->Request->WebSalesOrder->addChild('Notes', 'Package_Type_8');
                $ordersXML->Request->WebSalesOrder->addChild('GroupReference', 'Package_Type_8');
            } elseif ($isPackageType7 === true) {
                $ordersXML->Request->WebSalesOrder->addChild('Notes', 'Package_Type_7');
                $ordersXML->Request->WebSalesOrder->addChild('GroupReference', 'Package_Type_7');
            } elseif (str_contains($order['name'], '#JL')) {
                $ordersXML->Request->WebSalesOrder->addChild('Notes', 'Package_Type_9');
                $ordersXML->Request->WebSalesOrder->addChild('GroupReference', 'Package_Type_9');
            } elseif (str_contains($order['name'], 'ZL') !== false) {
                $ordersXML->Request->WebSalesOrder->addChild('Notes', 'Package_Type_10');
                $ordersXML->Request->WebSalesOrder->addChild('GroupReference', 'Package_Type_10');
            } elseif (str_contains($order['name'], '#EBay-') !== false || str_contains($order['name'], 'SW-') !== false || str_contains($order['name'], '#MPUSD') !== false) {
                $ordersXML->Request->WebSalesOrder->addChild('Notes', 'Package_Type_3');
                $ordersXML->Request->WebSalesOrder->addChild('GroupReference', 'Package_Type_3');
            } elseif ($this->isStaff($order)) {
                $ordersXML->Request->WebSalesOrder->addChild('Notes', 'Package_Type_8');
                $ordersXML->Request->WebSalesOrder->addChild('GroupReference', 'Package_Type_8');
            } elseif (str_contains($order['tags'], 'personalised') || $this->checkAvailableItemProperty($order, '_type', 'personalised')) {
                $ordersXML->Request->WebSalesOrder->addChild('Notes', 'Package_Type_8');
                $ordersXML->Request->WebSalesOrder->addChild('GroupReference', 'Package_Type_8');
            } else {
                $ordersXML->Request->WebSalesOrder->addChild('Notes', $this->getPackageType($order));
                $ordersXML->Request->WebSalesOrder->addChild('GroupReference', $this->getPackageType($order));
            }



            $ordersXML->Request->WebSalesOrder->addChild('OrderType', 'Web');
            // // gift note message AB
            // if (!empty($order['note_attributes']) && !empty($order['note_attributes'][0]) && !empty($order['note_attributes'][0]['value'])) {
            //     $ordersXML->Request->WebSalesOrder->addChild('GiftMessage', $order['note_attributes'][0]['value']);
            //     if (!empty($order['note_attributes'][2]) && !empty($order['note_attributes'][2]['value'])) {
            //         $ordersXML->Request->WebSalesOrder->addChild('GiftMessageFrom', $order['note_attributes'][2]['value']);
            //     }
            //     if (!empty($order['note_attributes'][1]) && !empty($order['note_attributes'][1]['value'])) {
            //         $ordersXML->Request->WebSalesOrder->addChild('GiftMessageTo', $order['note_attributes'][1]['value']);
            //     }
            // }
            // // gift note message AB
            $ordersXML->Request->addChild('DeliveryDetails');


            if (str_contains($order['tags'], 'personalised') || $this->checkAvailableItemProperty($order, '_type', 'personalised')) {
                $ordersXML->Request->DeliveryDetails->addChild('City', 'London');
                $ordersXML->Request->DeliveryDetails->addChild('CountryCode', 'GB');
                $ordersXML->Request->DeliveryDetails->addChild('EmailAddress', 'info@chintiandparker.com');
                $ordersXML->Request->DeliveryDetails->addChild('FirstName', 'STORE');
                $ordersXML->Request->DeliveryDetails->addChild('LastName', 'PERSONALISATION');
                $ordersXML->Request->DeliveryDetails->addChild('Line1', 'Chinti and Parker');
                $ordersXML->Request->DeliveryDetails->addChild('Line2', '322 Kings Road');
                $ordersXML->Request->DeliveryDetails->addChild('PhoneNumber', '+44 7562 208415');
                $ordersXML->Request->DeliveryDetails->addChild('PostcodeZip', 'SW3 5UH');
            } else {
                isset($order['customer']['default_address']['city']) ? $ordersXML->Request->DeliveryDetails->addChild('City', $order['customer']['default_address']['city']) : $ordersXML->Request->DeliveryDetails->addChild('City', $order['shipping_address']['city']);
                isset($order['shipping_address']['country_code']) ? $ordersXML->Request->DeliveryDetails->addChild('CountryCode', $order['shipping_address']['country_code']) : $ordersXML->Request->DeliveryDetails->addChild('CountryCode', ' ');
                isset($order['contact_email']) ? $ordersXML->Request->DeliveryDetails->addChild('EmailAddress', $order['contact_email']) : $ordersXML->Request->DeliveryDetails->addChild('EmailAddress', ' ');
                isset($order['shipping_address']['first_name']) ? $ordersXML->Request->DeliveryDetails->addChild('FirstName', $order['shipping_address']['first_name']) : $ordersXML->Request->DeliveryDetails->addChild('FirstName', ' ');
                isset($order['shipping_address']['last_name']) ? $ordersXML->Request->DeliveryDetails->addChild('LastName', $order['shipping_address']['last_name']) : $ordersXML->Request->DeliveryDetails->addChild('LastName', ' ');

                if (isset($order['shipping_address']['company']) && !empty($order['shipping_address']['company'])) {

                    $data1 = $this->checkSpecialCharacter($order['shipping_address']['company']);
                    $ordersXML->Request->DeliveryDetails->addChild('Line1', $data1);

                    if (isset($order['shipping_address']['address1'])) {
                        $ordersXML->Request->DeliveryDetails->addChild('Line2', $this->checkSpecialCharacter($order['shipping_address']['address1']));
                    }

                    if (isset($order['shipping_address']['address2'])) {
                        $ordersXML->Request->DeliveryDetails->addChild('Line3', $this->checkSpecialCharacter($order['shipping_address']['address2']));
                    }

                    if (isset($order['shipping_address']['address3'])) {
                        $ordersXML->Request->DeliveryDetails->addChild('Line4', $this->checkSpecialCharacter($order['shipping_address']['address3']));
                    }
                } else {

                    if (isset($order['shipping_address']['address1'])) {
                        $ordersXML->Request->DeliveryDetails->addChild('Line1', $this->checkSpecialCharacter($order['shipping_address']['address1']));
                    }

                    if (isset($order['shipping_address']['address2'])) {
                        $ordersXML->Request->DeliveryDetails->addChild('Line2', $this->checkSpecialCharacter($order['shipping_address']['address2']));
                    }

                    if (isset($order['shipping_address']['address3'])) {
                        $ordersXML->Request->DeliveryDetails->addChild('Line3', $this->checkSpecialCharacter($order['shipping_address']['address3']));
                    }
                }

                isset($order['shipping_address']['phone']) ? $ordersXML->Request->DeliveryDetails->addChild('PhoneNumber', $order['shipping_address']['phone']) : $ordersXML->Request->DeliveryDetails->addChild('PhoneNumber', ' ');
                isset($order['shipping_address']['zip']) && !empty($order['shipping_address']['zip']) ? $ordersXML->Request->DeliveryDetails->addChild('PostcodeZip', $order['shipping_address']['zip']) : $ordersXML->Request->DeliveryDetails->addChild('PostcodeZip', '00000');
            }



            $ordersXML->Request->addChild('SalesOrderHeader');
            if (str_contains($order['name'], '#EUR')) {
                $ordersXML->Request->SalesOrderHeader->addChild('DCCode', 'DCRTDM02');
            } else {
                $ordersXML->Request->SalesOrderHeader->addChild('DCCode', 'DCCL01');
            }

            $ordersXML->Request->addChild('List');

            $order = $this->checkDuplicate($order);

            foreach ($order['line_items'] as $line_key => $line_item) {
                $ordersXML->Request->List->addChild('SalesOrderLineItem');

                isset($line_item['price_set']['shop_money']['currency_code']) ? $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('CurrencyCode', $line_item['price_set']['shop_money']['currency_code']) : $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('CurrencyCode', ' ');


                $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('LineNumber', $line_key + 1);


                if (isset($line_item['sku']) && isset($line_item['product_id'])) {
                    $barcode = $this->getBarcode($line_item['sku'], $line_item['product_id']);
                    !empty($barcode) ? $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('ProductCode', $barcode) : $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('ProductCode', 0);
                }


                isset($line_item['quantity']) ? $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('Quantity', $line_item['quantity']) : $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('Quantity', 0);


                if ($order['taxes_included'] == true && isset($line_item['tax_lines']) && !empty($line_item['tax_lines']) && isset($line_item['tax_lines'][0]['rate'])) {
                    $taxRate = $line_item['tax_lines'][0]['rate'];
                    $itemTax = ($line_item['price'] / (1 + $taxRate) * $taxRate);
                    $itemTax = round($itemTax, 2);
                    $itemPrice = $line_item['price'] - $itemTax;
                    isset($line_item['price']) ? $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('UnitPrice', $itemPrice) : $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('UnitPrice', 0);
                } elseif ($order['taxes_included'] == false && isset($line_item['tax_lines']) && !empty($line_item['tax_lines']) && isset($line_item['tax_lines'][0]['rate'])) {

                    isset($line_item['price']) ? $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('UnitPrice', $line_item['price']) : $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('UnitPrice', 0);
                } else {
                    isset($line_item['price']) ? $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('UnitPrice', $line_item['price']) : $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('UnitPrice', 0);
                }

                //item-prixe-tax

                if ($order['taxes_included'] == true && isset($line_item['tax_lines'][0]['price'])) {
                    $taxRate = $line_item['tax_lines'][0]['rate'];
                    $itemTax = ($line_item['price'] / (1 + $taxRate) * $taxRate);
                    $itemTax = round($itemTax, 2);
                    !empty($itemTax) ? $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('VAT', $itemTax) : $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('VAT', 0);
                } elseif ($order['taxes_included'] == false && isset($line_item['tax_lines'][0]['price'])) {
                    $taxRate = $line_item['tax_lines'][0]['rate'];
                    $itemTax = $line_item['price'] * $taxRate;
                    $itemTax = round($itemTax, 2);
                    !empty($itemTax) ? $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('VAT', $itemTax) : $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('VAT', 0);
                } else {
                    isset($line_item['tax_lines'][0]['price']) ? $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('VAT', $line_item['tax_lines'][0]['price']) : $ordersXML->Request->List->SalesOrderLineItem[$line_key]->addChild('VAT', 0);
                }
            }



            $ordersXML->asXML($xmlFile);



            return $this->remoteSftp->putFile($xmlFile, APP_FOLDER . 'Load/Web_Sales_Orders/');
            // unlink($xmlFile);
        }
    }

    public function checkDuplicate($order)
    {
        $lineItemSkus = [];
        $duplicateArr = [];
        $flag = true;
        foreach ($order['line_items'] as $line_key => $line_item) {
            $lineItemSkus[] = $line_item['sku'];
        }
        $duplicateArrCounts = array_count_values($lineItemSkus);
        foreach ($duplicateArrCounts as $key => $duplicateArrCount) {
            if ($duplicateArrCount > 1) {
                $duplicateArr[$key] = $duplicateArrCount;
            }
        }
        foreach ($order['line_items'] as $line_key => $line_item) {
            foreach ($duplicateArr as $key => $duplicateCount) {
                if ($line_item['sku'] == $key && $flag == true) {
                    $flag = false;
                    $order['line_items'][$line_key]['quantity'] = $line_item['quantity'] * $duplicateCount;
                } elseif ($line_item['sku'] == $key && $flag == false) {
                    unset($order['line_items'][$line_key]);
                    $flag = true;
                }
            }
        }
        $order['line_items'] = array_values($order['line_items']);
        return $order;
    }


    public function orderType($orderName, $email, $order_number, $customerName)
    {
        $orderType = $this->db->getOrderType($orderName, $email, $order_number, $customerName);
        return $orderType;
    }
    public function getBarcode($sku, $productID)
    {
        $productArray = $this->api->getProduct($productID)['product'];
        foreach ($productArray['variants'] as $variant) {
            if ($variant['sku'] == $sku) {
                return $variant['barcode'];
            }
        }
    }
    public function getOrderTotalTax($order)
    {
        $itemTax = [];
        foreach ($order['line_items'] as $line_key => $line_item) {
            if ($order['taxes_included'] == true && isset($line_item['tax_lines'][0]['price'])) {
                $taxRate = $line_item['tax_lines'][0]['rate'];
                $itemTaxSIngle = ($line_item['price'] / (1 + $taxRate) * $taxRate);
                $itemTaxSIngle = round($itemTaxSIngle, 2);
                $itemTax[] = $itemTaxSIngle * $line_item['quantity'];
            } elseif ($order['taxes_included'] == false && isset($line_item['tax_lines'][0]['price'])) {
                $taxRate = $line_item['tax_lines'][0]['rate'];
                $itemTaxSIngle = ($line_item['price'] * $taxRate);
                $itemTaxSIngle = round($itemTaxSIngle, 2);
                $itemTax[] = $itemTaxSIngle * $line_item['quantity'];
            }
        }
        return array_sum($itemTax);
    }
    public function getOrderTotal($order)
    {
        $itemTotal = [];
        foreach ($order['line_items'] as $line_key => $line_item) {
            if ($order['taxes_included'] == true && isset($line_item['tax_lines'][0]['price'])) {
                $taxRate = $line_item['tax_lines'][0]['rate'];
                $itemTax = ($line_item['price'] / (1 + $taxRate) * $taxRate);
                $itemTax = round($itemTax, 2);
                $itemPrice = $line_item['price'] - $itemTax;
                $itemTotal[] = $itemPrice * $line_item['quantity'];
            } elseif ($order['taxes_included'] == false && isset($line_item['tax_lines'][0]['price'])) {
                $itemTotal[] = $line_item['price'] * $line_item['quantity'];
            } else {
                $itemTotal[] = $line_item['price'] * $line_item['quantity'];
            }
        }
        return array_sum($itemTotal);
    }
    public function getPackageType($order)
    {

        // $orderName=$order['name'];
        preg_match('/([A-z][0-9]?[A-z]+)[-\d]{3}.+/i', $order['name'], $prefix);
        $packageType = "";
        $prefix = isset($prefix[1]) ? $prefix[1] : '';


        switch ($prefix) {
            case "EUR":
            case "USD":
            case "GBP":
            case "GE":
            case "LDC":
            case "MPUSD":
            case "HR":




                /**getting staff vaerdict */
                $staff = false;
                foreach ($this->db->getOrderData() as $value) {

                    if ($value['order_form'] == 'email') {

                        $prefix = '@' . $value['prefix'];
                        if (isset($order['contact_email']) && !empty($order['contact_email'])) {
                            if (preg_match("/$prefix/i", $order['contact_email'])) {
                                $staff = true;
                            }
                        }
                    }
                }






                if ($staff) {
                    $packageType = "Package_Type_3";
                    break;
                }

                $p12tatus = false;
                /**Package Type 1 and 2 */
                foreach ($order['line_items'] as $line_key => $line_item) {
                    $comparePrice = $this->getSalesOrder($line_item);


                    if ($comparePrice == 0 && $line_item['price'] >= 150) {
                        if (preg_match("/cashmere|wool/i", $line_item['name'])) {
                            $packageType = "Package_Type_1";
                            $p12tatus = true;
                            break;
                        } else {
                            $packageType = "Package_Type_2";
                            $p12tatus = true;
                            break;
                        }
                    }
                }
                /** if Package Type 1 and 2 any of them get statisfied */
                if ($p12tatus) {
                    break;
                }

                /**Package Type 3 */

                /**getting Orders with all items on sales Verdict */
                $salesStatus = [];
                foreach ($order['line_items'] as $line_key => $line_item) {
                    $comparePrice = $this->getSalesOrder($line_item);
                    if ($comparePrice != 0) {
                        $salesStatus[] = true;
                    }
                }

                /**Full price Orders under £150.00 verdict */
                $fullPriceStatus = [];
                foreach ($order['line_items'] as $line_key => $line_item) {
                    $comparePrice = $this->getSalesOrder($line_item);
                    if ($comparePrice == 0 && $line_item['price'] < 150) {
                        $fullPriceStatus[] = true;
                    }
                }

                if (count($order['line_items']) == count($salesStatus)) {

                    $packageType = "Package_Type_3";
                    break;
                } elseif (count($order['line_items']) == count($fullPriceStatus)) {

                    $packageType = "Package_Type_3";
                    break;
                } else {

                    $packageType = "Package_Type_3";
                    break;
                }

                break;
            case "SS":
                $packageType = "Package_Type_3";
                break;
            case "HA":
            case "TH":
                $packageType = "Package_Type_4";
                break;
            case "FF":
                $packageType = "Package_Type_5";
                break;
            case "TFS":
                $packageType = "Package_Type_6";
                break;
            default:
                $packageType = "N/A";
        }

        return $packageType;
    }

    public function getSalesOrder($line_item)
    {
        $comparePrice = '';
        $productArray = $this->api->getProduct($line_item['product_id'])['product'];
        foreach ($productArray['variants'] as $variant) {
            if ($variant['sku'] == $line_item['sku']) {
                $comparePrice = $variant['compare_at_price'];
            }
        }
        return $comparePrice;
    }
    /**uneecassary just delete afetr testing completed */
    public function putDIspatch()
    {
        $this->remoteSftp->putFile(APP_DIR . '/xml/disting2.xml', APP_FOLDER . 'outbound/ORDERS_DESP');
    }

    public function ffOrder($order)
    {
        $order['customer']['default_address']['city'] = 'Milton Keynes';
        $order['customer']['default_address']['address1'] = 'FARFETCH c/o SEKO Logistics';
        $order['shipping_address']['country_code'] = 'GB';
        $order['shipping_address']['first_name'] = 'FarFetch';
        $order['shipping_address']['last_name'] = 'Seko Logistics';
        $order['shipping_address']['zip'] = 'MK10 0DF';

        return $order;
    }

    public function hrOrder($order)
    {
        $order['customer']['default_address']['city'] = 'Milton Keynes';
        $order['customer']['default_address']['address1'] = 'Harrods C/O Seko Logistics';
        $order['shipping_address']['country_code'] = 'GB';
        $order['shipping_address']['first_name'] = 'FarFetch';
        $order['shipping_address']['last_name'] = 'Seko Logistics';
        $order['shipping_address']['zip'] = 'MK10 0DF';

        return $order;
    }

    public function checkSpecialCharacter($info)
    {
        $replacedString = $info;
        if (!empty($replacedString)) {
            if (strpos($info, '&') !== false) {
                $replacedString = str_replace('&', 'and', $info);
            }
        } else {
            $replacedString = '';
        }


        return $replacedString;
    }

    public function removeTattoofromItems($order)
    {
        foreach ($order['line_items'] as $line_key => $line_item) {
            $id = $line_item['product_id'];
            if ($id === 6886488473678) {
                unset($order['line_items'][$line_key]);
            }
        }
        ;
        $order['line_items'] = array_values($order['line_items']);

        return $order;
    }

    public function removeGiftCardfromItems($order)
    {
        foreach ($order['line_items'] as $line_key => $line_item) {
            $id = $line_item['product_id'];
            if ($id === 1718097084531) {
                unset($order['line_items'][$line_key]);
            }
        }
        ;
        $order['line_items'] = array_values($order['line_items']);

        return $order;
    }

    public function checkedJumper($number)
    {
        //need to change the selected two jumpers product ids
        if ($number['product_id'] === 6884976394318 || $number['product_id'] === 6884976328782 || $number['product_id'] === 7041557692494 || $number['product_id'] === 7041557659726) {
            return $number;
        }
    }

    public function checkedTattoo($number)
    {
        //need to change the tatto sheet product id
        if ($number['product_id'] === 6886488473678) {
            return $number;
        }
    }

    public function checkedOtherItems($number)
    {
        //need to change the tatto sheet, selected two jumpers product id
        if ($number['product_id'] !== 6884976394318 && $number['product_id'] !== 6884976328782 && $number['product_id'] !== 7041557692494 && $number['product_id'] !== 7041557659726 && $number['product_id'] !== 6886488473678) {
            return $number['product_id'];
        }
    }

    public function checkedGiftCardItem($number)
    {
        //need to change the gift card product id
        if ($number['product_id'] === 1718097084531) {
            return $number;
        }
    }
    public function checkedSwapProtectItem($number)
    {
        //need to change the gift card product id
        if ($number['product_id'] === 7404001919054) {
            return $number;
        }
    }
}