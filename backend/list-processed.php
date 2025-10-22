<?php
require 'aws_functions.php';

header("Content-Type: application/json");

$bucket = getenv('PROCESSED_BUCKET'); // e.g. kc-poc-us-processed
$cloudfrontDomain = getenv('CLOUDFRONT_DOMAIN'); // e.g. dxxxxx.cloudfront.net
$prefix = "processed/";

$s3 = getS3Client();
$result = $s3->listObjectsV2([
  'Bucket' => $bucket,
  'Prefix' => $prefix
]);

$urls = [];
foreach ($result['Contents'] ?? [] as $obj) {
  $key = $obj['Key'];
  $urls[] = "https://{$cloudfrontDomain}/{$key}";
}

echo json_encode($urls);
?>
