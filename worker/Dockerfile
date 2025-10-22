FROM python:3.11-slim

RUN apt-get update && apt-get install -y ffmpeg
WORKDIR /app
COPY worker.py /app/

RUN pip install boto3

CMD ["python", "worker.py"]
