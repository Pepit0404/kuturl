const BASE_API = "api";

document.addEventListener("keydown", (event) => {
  if (event.key === "Enter" && event.target.nodeName === "INPUT") {
    createShort();
  }
});

async function createShort() {
  let original = document.getElementById("origin").value;
  let shorter = document.getElementById("short").value;

  const url = `${BASE_API}/url`;

  const response = await fetch(url, {
    method: "POST",
    body: JSON.stringify({ original_url: original, shorter_url: shorter }),
    headers: {
      "Content-Type": "application/json; charset=utf-8",
    },
  });
  const result = await response.json();

  if (!response.ok) {
    CreateNotification(
      "warning",
      "Warning",
      result.message,
      ["Close"],
      `${document.URL}`,
    );
    throw new Error("No api response", error);
  }

  console.log(result);
  if (result.status === "success") {
    CreateNotification(
      "success",
      "Link created",
      "Your short link has been created successfully.",
      ["Close", "Copy"],
      `${document.URL}${result.result.shorter_url}`,
    );
    document.getElementById("origin").value = "";
    document.getElementById("short").value = "";
  }
}

async function redirectTo() {
  const shorter = document.URL.split("/").slice(3, 4).join("/");
  const baseUrl = document.URL.split("/").slice(0, 3).join("/");
  if (shorter === "") {
    console.error("No shorter URL found");
    return;
  }

  const url = `${BASE_API}/url/${shorter}`;
  const response = await fetch(url);

  if (!response.ok) {
    if (response.status === 404) {
      window.location.href = baseUrl;
    }
    throw new Error(`Response status: ${response.status}`);
  }
  const result = await response.json();

  const success = result.status === "success";
  if (success) {
    window.location.href = result.result.original_url;
  }
}

// https://www.youtube.com/watch?v=dQw4w9WgXcQ
