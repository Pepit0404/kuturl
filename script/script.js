let originInput
let domain
let wantedLink

function initPage() {
    originInput = document.getElementById("origin");  // original link
    domain = document.getElementById("domain");   // domain of the shorter link
    wantedLink = document.getElementById("short");   // wanted link

    const form = document.getElementById("generator");  // trigger of the action
    form.addEventListener("submit", (e) => {
        console.log("[DEBUG] -> IN !")
        exec();
    });

    console.log("[DEBUG] -> initialisation finish");
}

function exec() {
    if(wantedLink.value == "" || wantedLink.value == null) {
        console.log(`[DEBUG] -> wanted shorter link is null`);
    } else {
        console.log("");
    }
}

// https://www.youtube.com/watch?v=dQw4w9WgXcQ