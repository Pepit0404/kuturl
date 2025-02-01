function createShort() {
    let original = document.getElementById("origin").value;
    let shorter = document.getElementById("short").value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", `api/createShort`, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            const icon = response.success ? "success" : "error";
            const title = response.success ? "Link created" : "Error";
            CreateNotification(icon, title, response.message[0], ["Close", "Copy"]);
            if (response.success) {
                document.getElementById("origin").value = "";
                document.getElementById("short").value = "";
            }
        }
    };

    // Send data as key-value pairs
    var data = `original=${original}&shorter=${shorter}`;
    xhr.send(data);
}

// https://www.youtube.com/watch?v=dQw4w9WgXcQ
