import boto3, json, subprocess, os, time

region = os.getenv('AWS_REGION', 'us-east-1')
upload_bucket = os.getenv('UPLOAD_BUCKET')
processed_bucket = os.getenv('PROCESSED_BUCKET')
queue_url = os.getenv('SQS_URL')

s3 = boto3.client('s3', region_name=region)
sqs = boto3.client('sqs', region_name=region)

print("Worker started...")

while True:
    msgs = sqs.receive_message(QueueUrl=queue_url, MaxNumberOfMessages=1, WaitTimeSeconds=20)
    if 'Messages' not in msgs:
        continue
    for msg in msgs['Messages']:
        body = json.loads(msg['Body'])
        record = body['Records'][0]
        key = record['s3']['object']['key']
        bucket = record['s3']['bucket']['name']

        print(f"Processing {key} from {bucket}")
        s3.download_file(bucket, key, '/tmp/input.mp4')
        # Run ffmpeg to compress or change format
        subprocess.run(["ffmpeg", "-i", "/tmp/input.mp4", "-vcodec", "libx264", "-acodec", "aac", "/tmp/output.mp4"], check=True)

        out_key = "processed/" + os.path.basename(key)
        s3.upload_file('/tmp/output.mp4', processed_bucket, out_key)
        print(f"Uploaded processed: {out_key}")

        sqs.delete_message(QueueUrl=queue_url, ReceiptHandle=msg['ReceiptHandle'])
