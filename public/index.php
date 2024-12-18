<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>KutURL</title>
    <link rel="stylesheet" href="public/style/style.css">
    <script src="public/script/script.js"></script>

    <meta name="viewport" id="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0,maximum-scale=10.0,minimal-ui">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="referrer" content="always">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Rubik:ital,wght@0,300..900;1,300..900&display=swap" rel="stylesheet">
</head>
<body>
    <section id="headband">
        <h1 class="title">Make a shorter personalized link</h1>
    </section>
    <section id="card">
        <form method="post" id="generator">
            <h2 class="text-Indicator">Short a link</h2>
            <h3 class="text-Indicator">Original URL</h3>
            <label>
                <input name="target" type="url" id="origin" required="" placeholder="https://www.youtube.com/watch?v=dQw4w9WgXcQ">
            </label>
            <h3 class="text-Indicator">Link wanted</h3>
            <label class="one-line">
                <label>
                    <input class="disabled" id="domain" type="text" disabled="" value="short.dev">
                </label>
                /
                <label id="wanted">
                    <input name="shortCut" id="short" type="text" pattern="[a-zA-Z0-9]*" minlength="3" placeholder="pepito">
                </label>
            </label>
            <button id="execute" type="submit" value="CREATE">CREATE</button>
        </form>
        <?php
        $error = $error ?? [];
        $success = $success ?? null;
        if ($success == null || !$success) {
            foreach ($error as $e) {
                print "<p>$e</p>";
            }
        } else {
            print "<p>Success</p>";
        }
        ?>
    </section>
    <section id="notification"></section>
    <section id="footer">
        Made by Pepito
    </section>
</body>