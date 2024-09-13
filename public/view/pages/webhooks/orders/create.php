<?php
function checkOnlyGiftCardOrder($dataDecoded)
{
    foreach ($dataDecoded->line_items as $item) {
        if ($item->gift_card == false) {
            return false;
        }
    }
    return true;
}

try {
    $log = new \Toll_Integration\Log();
    $api = new \Toll_Integration\API();
    $db = new \Toll_Integration\DB();
    $data = file_get_contents('php://input');
    $dataDecoded = json_decode($data);
    $hmac_header = getHeaderValue('X-Shopify-Hmac-Sha256');
    $orderId = $dataDecoded->id;
    $orderName = $dataDecoded->name;
    $verified = $api->verifyWebhook($data, $hmac_header);
    if ($verified) {
        $log->log('Webhook initiated for creating order:' . $orderId);
        $orderDetails = [
            'order_number' => $orderId,
            'order_name' => $orderName,
            'status' => 0,
            'action' => 'insert',
        ];
        $db->processOrderNumber($orderDetails);
        if (checkOnlyGiftCardOrder($dataDecoded)) {
            // not process
            $log->log('Not inserted into DB - only gift card' . $orderId);
        } else {
            // process
            $log->log('processed - Not only gift card' . $orderId);
        }
        http_response_code(200);
    } else {
        $log->log('create Webhook Failed');
        http_response_code(401);
    }
} catch (\Throwable $th) {
    $log->log($th->getMessage(), 'error');
}
