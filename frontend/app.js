const apiBase = "https://YOUR_API_ALB_OR_DOMAIN"; // e.g. https://api.example.com

async function uploadFile() {
  const fileInput = document.getElementById("fileInput");
  const file = fileInput.files[0];
  if (!file) {
    alert("Please select a file");
    return;
  }

  document.getElementById("status").textContent = "Requesting upload URL...";

  const res = await fetch(`${apiBase}/generate-upload-url.php`, {
    method: "POST",
    headers: { "Content-Type": "application/json" },
    body: JSON.stringify({ filename: file.name })
  });
  const data = await res.json();
  const uploadUrl = data.url;
  const key = data.key;

  document.getElementById("status").textContent = "Uploading to S3...";
  await fetch(uploadUrl, {
    method: "PUT",
    body: file
  });

  document.getElementById("status").textContent = "Upload complete! Processing will start shortly.";
  setTimeout(listProcessed, 3000);
}

async function listProcessed() {
  const res = await fetch(`${apiBase}/list-processed.php`);
  const videos = await res.json();
  const list = document.getElementById("videoList");
  list.innerHTML = "";
  videos.forEach(url => {
    const li = document.createElement("li");
    li.innerHTML = `<video width="480" controls src="${url}"></video>`;
    list.appendChild(li);
  });
}
