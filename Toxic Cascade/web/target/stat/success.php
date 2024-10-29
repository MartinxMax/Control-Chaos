<?php
$pwnedFilePath = '../data/pwned.json';

if (!file_exists($pwnedFilePath)) {
    header("Location: fuck.html");
    exit();
}

$pwnedData = json_decode(file_get_contents($pwnedFilePath), true);

if (isset($pwnedData['pwned']) && $pwnedData['pwned'] === 1) {
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Task Success</title>
        <style>
            body {
                background-color: #4CAF50;
                color: white;
                font-family: Arial, sans-serif;
                text-align: center;
                padding: 50px;
            }
            h1 {
                font-size: 48px;
            }
            p {
                font-size: 20px;
            }
        </style>
    </head>
    <body>
        <h1>Task Success!</h1>
        <p>You have successfully completed the task!</p>
    </body>
    </html>
    <?php
} else {
    header("Location: fuck.html");
    exit();
}
?>
