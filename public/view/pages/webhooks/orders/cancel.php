<?php
try {
    $log = new \Toll_Integration\Log();
    $api = new \Toll_Integration\API();
    $db = new \Toll_Integration\DB();
    $data = file_get_contents('php://input');
    $dataDecoded = json_decode($data);
    $hmac_header = getHeaderValue('X-Shopify-Hmac-Sha256');
    $orderId = $dataDecoded->id;
    $verified = $api->verifyWebhook($data, $hmac_header);
    if ($verified) {
        $log->log('WebHooked Intitated For Cancel Order: ' . $orderId);
        $orderDetails = [
            'order_number' => $orderId,
            'status' => 0,
            'action' => 'insert',
        ];
        $db->processCancel($orderDetails);
        http_response_code(200);
    } else {
        $log->log('Cancel Webhook Failed');
        http_response_code(401);
    }
} catch (\Throwable $th) {
    $log->log($th->getMessage(), 'error');
}
