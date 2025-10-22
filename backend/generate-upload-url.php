<?php
require 'aws_functions.php';

header("Content-Type: application/json");

$body = json_decode(file_get_contents("php://input"), true);
$filename = basename($body['filename'] ?? 'upload.mp4');

$bucket = getenv('UPLOAD_BUCKET'); // e.g. kc-poc-us-uploads
$region = getenv('AWS_REGION') ?: 'us-east-1';
$key = "uploads/" . uniqid() . "-" . $filename;

$s3 = getS3Client();
$cmd = $s3->getCommand('PutObject', [
    'Bucket' => $bucket,
    'Key' => $key,
    'ACL' => 'private',
    'ContentType' => 'video/mp4'
]);

$request = $s3->createPresignedRequest($cmd, '+15 minutes');
$url = (string)$request->getUri();

echo json_encode(['url' => $url, 'key' => $key]);
?>
