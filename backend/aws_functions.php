<?php
require 'vendor/autoload.php';

use Aws\S3\S3Client;

function getS3Client() {
    return new S3Client([
        'version' => 'latest',
        'region'  => getenv('AWS_REGION') ?: 'us-east-1'
    ]);
}
?>
