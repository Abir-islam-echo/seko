<?php
/*
This will change the fulfillment status of orders.
A corn Job will run this file with certain periord of time
*/

$sftp = new \Toll_Integration\RemoteSFTP();

//$sftp->getDespatchedFile();
$sftp->getDespatchedFileSeparator();
$sftp->getDespatchedFileFullfilment();
