<?php

namespace Toll_Integration;

use phpseclib3\Net\SFTP;

class RemoteSFTP
{

    protected $sftp;
    protected $db;
    protected $api;
    protected $log;
    protected $xml;
    public $status;


    public function __construct()
    {
        $this->db = new DB();
        $this->log = new Log();
        $this->api = new API();
        $this->sftp = new SFTP($this->db->getConfig('sftp_host', 'toll'), $this->db->getConfig('sftp_port', 'toll'));
        $this->status = false;
        try {
            if (!$this->sftp->login($this->db->getConfig('sftp_username', 'toll'), $this->db->decryption($this->db->getConfig('sftp_password', 'toll')))) {
                $this->status = false;
            } else {
                $this->status = true;
            }
        } catch (\Throwable $th) {
            $this->log->log($th->getMessage(), 'error');
            $this->status = false;
        }
    }

    public function putFile($path, $dir)
    {
        $fileName = pathinfo($path)['basename'];
        try {
            return $this->sftp->put($dir . $fileName, $path, SFTP::SOURCE_LOCAL_FILE);
        } catch (\Throwable $th) {
            $this->log->log($th->getMessage(), 'error');
            $this->status = false;
        }
    }

    // Getting Despatched XML files from desired folder
    public function getDespatchedFile()
    {
        $xmlItems = $this->scanDirectory(APP_FOLDER . 'Push/Dispatch_Con/');
        $test = [];
        foreach ($xmlItems as $key => $value) {
            if ($value && (strpos($value, 'PT') !== 0 && strpos($value, 'TT') !== 0)) {
                $fullFilmentStatus = $this->setFulfillmentResponse($this->sftp->get(APP_FOLDER . 'Push/Dispatch_Con/' . $value));

                if ($fullFilmentStatus) {
                    $this->moveDespatchedFile($value, APP_FOLDER . 'Push/Dispatch_Con/', APP_FOLDER . 'Push/Dispatch_Con/Archive/');
                }
            }
        }
    }


    public function getDespatchedFileFullfilment()
    {
        // $xmlItems = $this->scanDirectory(APP_FOLDER . 'Push/Dispatch_Con/');

        $dispatchLocation = APP_DIR . '/' . 'dispatch_XML/';
        $xmlItems = scandir($dispatchLocation);



        // Remove elements starting with a dot from the beginning of the array
        while (isset($xmlItems[0]) && (strpos($xmlItems[0], '.') === 0 || $xmlItems[0] === 'Archive')) {
            array_shift($xmlItems);
        }


        $test = [];
        foreach ($xmlItems as $key => $value) {

            //$loadFile = simplexml_load_file('dispatch_XML/' . $value);
            $fullFilmentStatus = $this->setFulfillmentResponse($dispatchLocation . $value, 1);

            //  echo '<pre> remoteSftp $xmlItems';
            //  print_r($fullFilmentStatus);
            //  echo '</pre>';

            if ($fullFilmentStatus) {

                // echo '<pre> remoteSftp $xmlItems';
                // print_r($fullFilmentStatus);
                // echo '</pre>';
                //$this->moveDespatchedFile($value, 'dispatch_XML/', 'dispatch_XML/Archive/');
                rename($dispatchLocation . $value, $dispatchLocation . 'Archive/' . $value);
            }

        }
    }


    public function getDespatchedFileSeparator()
    {
        $xmlItems = $this->scanDirectory(APP_FOLDER . 'Push/Dispatch_Con/');
        $orderIds = [];
        foreach ($xmlItems as $key => $value) {
            if ($value && (strpos($value, 'PT') !== 0 && strpos($value, 'TT') !== 0)) {
                $fullFilmentStatus = $this->fileSeparator($this->sftp->get(APP_FOLDER . 'Push/Dispatch_Con/' . $value), $value);

                if ($fullFilmentStatus) {
                    $this->moveDespatchedFile($value, APP_FOLDER . 'Push/Dispatch_Con/', APP_FOLDER . 'Push/Dispatch_Con/Archive/');
                }
                $orderIds[] = $value;
            }
        }
        return implode(',', $orderIds);
    }

    // fullfillment of each response
    public function setFulfillmentResponse($xmlFile, $default = 0)
    {

        if ($default) {
            $xmlResponse = simplexml_load_file($xmlFile);
        } else {
            $xmlResponse = simplexml_load_string($xmlFile);
        }

        // echo '<pre> remoteSftp xmlResponse';
        // print_r($xmlResponse);
        // echo '</pre> remoteSftp xmlResponse';

        if (empty($xmlResponse)) {
            return false;
        }


        $response = isset($xmlResponse->Response) ? (array) $xmlResponse->Response : '';
        $responseLenght = count($xmlResponse);
        $count = 0;
        foreach ($xmlResponse as $value) {
            $value = (array) $value;

            if ($this->setFulfillment($value)) {
                $count++;
            }
        }


        if ($count == $responseLenght) {
            return true;
        }


    }

    // set in  Fulfillment status to particular order
    public function setFulfillment($xmlFile)
    {

        // newly added code for seko
        $line_tems = [];
        $count = 0;
        foreach ($xmlFile['List'] as $key => $value) {
            $temp = (array) $value;
            if ($temp['Quantity'] != 0) {
                $line_tems[$count++] = [
                    'barcode' => $temp['ProductCode'],
                    'quantity' => $temp['Quantity']
                ];
            }
        }
        $temp1 = (array) $xmlFile['Dispatch'];
        $CustomerOrderId = '';
        if (str_contains($temp1['SalesOrderNumber'], '#EBay-')) {
            isset($temp1['SalesInvoiceNumber']) ? $CustomerOrderId = $temp1['SalesInvoiceNumber'] : $CustomerOrderId = $temp1['SalesOrderReference'];
        } else {
            $CustomerOrderId = $temp1['SalesOrderReference'];
        }

        $data = [
            "order_id" => $temp1['SalesOrderNumber'],
            "customer_order_id" => $CustomerOrderId,
            "tracking_number" => $temp1['CourierRef'],
            "tracking_url" => $temp1['TrackingUrl'],
            "line_items" => $line_tems,
            "tracking_company" => $temp1['CourierName']
        ];


        // // AB    
        // echo '<pre>rs';
        // print_r($data);
        // echo '</pre>';
        // // AB
        return $this->api->fullFillmentOrder($data);
    }

    public function scanDirectory($identifier, $files = true, $folders = true, $recursive = false)
    {
        $directoryEntries = [];
        if (!$files && !$folders) {
            return $directoryEntries;
        }
        $items = $this->sftp->nlist($identifier, $recursive);
        foreach ($items as $item) {
            if (preg_match("/\.xml$/i", $item)) {
                if ($item === '.' || $item === '..') {
                    continue;
                }
                $itemIdentifier = $item;
                $directoryEntries[] = $itemIdentifier;
            }
        }
        return $directoryEntries;
    }

    public function moveDespatchedFile($xmlFile, $pathFrom, $pathTo)
    {

        $this->sftp->rename($pathFrom . $xmlFile, $pathTo . $xmlFile);
    }

    public function alldirectoreis()
    {

        $this->sftp->chdir('PROD');
        $this->sftp->chdir('outbound');
        $this->sftp->chdir('ORDERS_DESP');

        return $this->sftp->nlist();
    }
    public function singleUnfullfillXml($file)
    {
        return $this->sftp->get('/PROD/outbound/ORDERS_DESP/' . $file);
    }

    public function fileCheckSftp()
    {
        $this->sftp->chdir('PROD');
        $this->sftp->chdir('outbound');
        $this->sftp->chdir('ORDERS_DESP');
        $this->sftp->chdir('PROCESSED');
        print_r($this->sftp->get('/PROD/outbound/ORDERS_DESP/PROCESSED/disting.xml'));
        return $this->sftp->nlist();
    }

    public function fileSeparator($xmlFile, $parentFileName)
    {

        //Load the XML file
        $xml = simplexml_load_string($xmlFile);
        $responseNodes = $xml->Response;

        // Check if the XML file was loaded successfully
        if ($xml) {
            // Create the destination folder if it doesn't exist
            $destinationFolder = APP_DIR . '/' . 'dispatch_XML';

            if (!is_dir($destinationFolder)) {
                mkdir($destinationFolder, 0777, true);
            }

            $responseLenght = count($responseNodes);
            $count = 0;


            // Loop through each <Response> nodegetDespatchedFileSeparator
            foreach ($responseNodes as $responseNode) {

                // Convert the current <Response> node to XML string
                $responseXml = $responseNode->asXML();

                $dispatchArray = (array) $responseNode->Dispatch;
                $orderPrefix = $dispatchArray["SalesOrderNumber"];
                $orderNumber = $dispatchArray["SalesOrderReference"];
                $orderPrefix = str_replace('#', '', $orderPrefix);
                $modifyOrderPefix = substr($orderPrefix, 0, 2);

                // echo '<pre>';
                // print_r($count);
                // print_r($responseLenght);

                // if(str_contains($modifyOrderPefix, 'PT') === true){
                //     return false;
                // }

                $responseXml = "<Responses>" . "\r\n" . $responseXml . "\r\n" . "</Responses>";

                // Generate a unique filename for each response
                $filename = $orderPrefix . '-' . uniqid() . '-' . date("Y-m-d") . '-' . $parentFileName;

                // Save the response XML to the destination folder
                $destinationPath = $destinationFolder . '/' . $filename;
                $fileContainer = file_put_contents($destinationPath, $responseXml);
                if ($fileContainer) {
                    $count++;
                }



            }



            if ($count == $responseLenght) {
                return true;
            }

        } else {
            $log->log('Failed to load the XML file. ' . $parentFileName, 'error');
        }
    }
}