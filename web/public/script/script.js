const BASE_API = "api";

function createShort() {
    let original = document.getElementById("origin").value;
    let shorter = document.getElementById("short").value;

    let xhr = new XMLHttpRequest();
    xhr.open("POST", `${BASE_API}/url`, true);
    xhr.setRequestHeader("Content-Type", "application/json; charset=utf-8");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            const success = response.status === "success";
            const icon = success ? "success" : "warning";
            const title = success ? "Link created" : "Warning";
            const actions = success ? ["Close", "Copy"] : ["Close"];
            response.message = response.message ??  [];
            if ( response.message.lenght === 0 && success) {
                response.message.push("Your short link has been created successfully.");
            }
            CreateNotification(icon, title, response.message, actions, `${document.URL}${response.result.short_url}`);
            if (success) {
                document.getElementById("origin").value = "";
                document.getElementById("short").value = "";
            }
        } // TODO: afficher un message d'erreur
    };

    const dataCompose = {original_url: original, shorter_url: shorter};
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
                window.location.href = response.result.long_url;
            } else {
                window.location.href = baseUrl;
            }
        }
    };

    xhr.send();
}

// https://www.youtube.com/watch?v=dQw4w9WgXcQ