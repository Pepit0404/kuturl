const BASE_API = "api";

document.addEventListener("keydown", (event) => {
    if (event.key === "Enter" && event.target.nodeName === "INPUT") {
        createShort();
    }
});

function createShort() {
    let original = document.getElementById("origin").value;
    let shorter = document.getElementById("short").value;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", `${BASE_API}/url`, true);
    xhr.setRequestHeader("Content-Type", "application/json; charset=utf-8");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            const response = JSON.parse(xhr.responseText);
            if (xhr.status === 200) {                
                CreateNotification("success", "Link created", "Your short link has been created successfully.", ["Close", "Copy"], `${document.URL}${response.result.shorter_url}`);
                document.getElementById("origin").value = "";
                document.getElementById("short").value = "";
            } else {
                CreateNotification("warning", "Warning", response.message, ["Close"], `${document.URL}`);
            }
        }
    };

    // Send data as key-value pairs
    let data = JSON.stringify({original_url: original, shorter_url: shorter});
    xhr.send(data);
}

function redirectTo() {
    const shorter = document.URL.split('/').slice(3,4).join('/');
    const baseUrl = document.URL.split('/').slice(0,3).join('/');
    if (shorter === "") {
        console.error("No shorter URL found");
        return;
    }
    let xhr = new XMLHttpRequest();
    xhr.open("GET", `${BASE_API}/url/${shorter}`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            const success = response.status === "success";
            if (success) {
                window.location.href = response.result.original_url;
            } else {
                window.location.href = baseUrl;
            }
        }
    };

    xhr.send();
}

// https://www.youtube.com/watch?v=dQw4w9WgXcQ