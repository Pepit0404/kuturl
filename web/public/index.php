<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <link rel="icon" href="/public/asset/icon.png">
    <title>KutURL</title>

    <meta name="description" content="Link shortener">
    <meta name="keywords" content="link, shortener, kuturl, pinto, samuel">
    <meta name="author" content="Pepit0404">
    <meta name="theme-color" content="#44475A">

    <link rel="stylesheet" href="/public/style/style.css">
    <script src="/public/script/Notification.js"></script>
    <script src="/public/script/script.js"></script>

    <meta name="viewport" id="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=10.0,minimal-ui">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="referrer" content="always">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
</head>
<body>
    <svg display="none">
        <symbol id="error" viewBox="0 0 32 32" >
            <circle r="15" cx="16" cy="16" fill="none" stroke="hsl(13,90%,55%)" stroke-width="2" />
            <line x1="10" y1="10" x2="22" y2="22" stroke="hsl(13,90%,55%)" stroke-width="2" stroke-linecap="round" />
            <line x1="22" y1="10" x2="10" y2="22" stroke="hsl(13,90%,55%)" stroke-width="2" stroke-linecap="round" />
        </symbol>
        <symbol id="success" viewBox="0 0 32 32" >
            <circle r="15" cx="16" cy="16" fill="none" stroke="hsl(93,90%,40%)" stroke-width="2" />
            <polyline points="9,18 13,22 23,12" fill="none" stroke="hsl(93,90%,40%)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
        </symbol>
        <symbol id="warning" viewBox="0 0 32 32" >
            <polygon points="16,1 31,31 1,31" fill="none" stroke="hsl(33,90%,55%)" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            <line x1="16" y1="12" x2="16" y2="20" stroke="hsl(33,90%,55%)" stroke-width="2" stroke-linecap="round" />
            <line x1="16" y1="25" x2="16" y2="25" stroke="hsl(33,90%,55%)" stroke-width="3" stroke-linecap="round" />
        </symbol>
    </svg>
    <section id="headband">
        <h1 class="title">Make a shorter personalized link</h1>
    </section>
    <section id="card">
        <h2 class="text-Indicator">Short URL</h2>
        <h3 class="text-Indicator">Original URL</h3>
        <label>
            <input name="target" type="url" id="origin" required="" placeholder="https://www.youtube.com/watch?v=dQw4w9WgXcQ">
        </label>
        <h3 class="text-Indicator">Desired Link</h3>
        <label class="one-line">
            <label>
                <input class="disabled" id="domain" type="text" disabled="" value="kut.samuel-pinto.fr">
            </label>
            <span>/</span>
            <label id="wanted">
                <input name="shortCut" id="short" type="text" pattern="[a-zA-Z0-9]*" minlength="3" placeholder="pepito">
            </label>
        </label>
        <button id="execute" onclick="createShort()" value="CREATE">CREATE</button>
    </section>
    <section id="footer">
        Made by Pepito
    </section>
</body>