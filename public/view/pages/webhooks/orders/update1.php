<?php
try {
    $log = new \Toll_Integration\Log();
    // $xml = new \Toll_Integration\XML();
    $api = new \Toll_Integration\API();
    $db = new \Toll_Integration\DB();
    $data = file_get_contents('php://input');
    $dataDecoded = json_decode($data);
    $hmac_header = getHeaderValue('X-Shopify-Hmac-Sha256');
    $orderId = $dataDecoded->id;
    $verified = $api->verifyWebhook($data, $hmac_header);
    if ($verified) {
        logMe($dataDecoded);
        $log->log('WebHooked Intitated For update Order: ' . $orderId);
        $cancelReason = $dataDecoded->cancel_reason;
        $cancelled_at = $dataDecoded->cancelled_at;
        if (empty($cancelReason) && empty($cancelled_at)) {
            http_response_code(200);
            // $xml->generateOrdersXML($orderId);
            $orderDetails = [
                'order_number' => $orderId,
                'status' => 0,
                'action' => 'insert',
            ];
            $db->processOrderNumber($orderDetails);
        }
    } else {
        $log->log('order update Webhook Failed');
    }
} catch (\Throwable $th) {
    $log->log($th->getMessage() . 'update error', 'error');
    logMe($th->getMessage());
}
