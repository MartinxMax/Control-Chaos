<?php
function clearCookies() {
    $cookies = explode("; ", $_SERVER['HTTP_COOKIE']);
    foreach ($cookies as $cookie) {
        $eqPos = strpos($cookie, "=");
        $name = $eqPos > -1 ? substr($cookie, 0, $eqPos) : $cookie;
        setcookie($name, '', time() - 3600, '/');
    }
}

clearCookies();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Failed</title>
    <style>
        body {
            background-color: #f44336;
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
    <script>
        function resetTask() {
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "", true);
            xhr.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                }
            };
            xhr.send(JSON.stringify({ reset: 1 }));
        }

        window.onload = function() {
            clearCookies();
            resetTask();
        };

        function clearCookies() {
            const cookies = document.cookie.split("; ");
            for (let cookie of cookies) {
                const eqPos = cookie.indexOf("=");
                const name = eqPos > -1 ? cookie.substr(0, eqPos) : cookie;
                document.cookie = name + "=;expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/";
            }
        }
    </script>
</head>
<body>
    <h1>Task Failed!</h1>
    <p>You have been detected by the administrator!</p>
    <a href="/target" style="color: yellow; font-size: 18px;">Restart Task</a>
</body>
</html>
