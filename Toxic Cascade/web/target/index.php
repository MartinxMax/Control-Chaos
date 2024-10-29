<?php
session_start();

$initFilePath = './data/init.json';
$tempFilePath = './data/tempwork.json';
$failFilePath = './data/fail.json';
$pwnedFilePath = './data/pwned.json';
$redirectFail = './stat/fail.php';
$redirectSuccess = './stat/success.php';

if (!file_exists($initFilePath)) {
    die("Initialization parameter file not found. Please ensure init.json exists.");
}

$pressures_mpa = json_decode(file_get_contents($initFilePath), true);

if (!file_exists($tempFilePath)) {
    file_put_contents($tempFilePath, json_encode($pressures_mpa));
}

$initial_pressures = json_decode(file_get_contents($tempFilePath), true);

$ip = $_SERVER['REMOTE_ADDR'];
$fail_count = [];
if (file_exists($failFilePath)) {
    $fail_count = json_decode(file_get_contents($failFilePath), true);
}

if (!isset($_SESSION['tolerance_count'])) {
    $_SESSION['tolerance_count'] = 0;
}

$h2so4_pressure = $initial_pressures['H2SO4'] ?? [];
$naclo_pressure = $initial_pressures['NaClO'] ?? [];

if ($h2so4_pressure['pressure'] >= 1 && $h2so4_pressure['H'] == 55.40 &&
    $naclo_pressure['pressure'] >= 1 && $naclo_pressure['H'] == 86.38) {
    file_put_contents($pwnedFilePath, json_encode(['pwned' => 1]));
    header("Location: $redirectSuccess");
    exit();
}

$updated_pressures = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    foreach ($pressures_mpa as $fluid => $data) {
        $h = isset($_POST["h_$fluid"]) ? htmlspecialchars(trim($_POST["h_$fluid"])) : 10;

        if (preg_match('/<[^>]+>/', $h)) {
            die("Input contains invalid characters!");
        }

        $h = round(floatval($h), 2);

        if ($h < 0 || $h > 100) {
            $fail_count[$ip] = ($fail_count[$ip] ?? 0) + 1;
            file_put_contents($failFilePath, json_encode($fail_count));

            setcookie('tolerance_count', '', time() - 3600);
            header("Location: $redirectFail");
            exit();
        }

        if ($fluid === 'H2SO4' && ($h < 55.40 || $h > 55.40)) {
            $_SESSION['tolerance_count']++;
        } elseif ($fluid === 'NaClO' && ($h < 86.38 || $h > 86.38)) {
            $_SESSION['tolerance_count']++;
        }

        if ($_SESSION['tolerance_count'] > 3) {
            $fail_count[$ip] = ($fail_count[$ip] ?? 0) + 1;
            file_put_contents($failFilePath, json_encode($fail_count));

            setcookie('tolerance_count', '', time() - 3600);
            header("Location: $redirectFail");
            exit();
        }

        $density = match ($fluid) {
            'NaClO' => 1180,
            'NaOH' => 1325,
            'FeSO4' => 1500,
            'PAM' => 1000,
            'H2SO4' => 1840,
        };

        $pressure = round(($h * $density * 9.81) / 1000000, 2);

        $updated_pressures[$fluid] = [
            'pressure' => $pressure,
            'H' => $h
        ];
    }

    file_put_contents($tempFilePath, json_encode($updated_pressures));

    $initial_pressures = $updated_pressures;

    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
} else {
    if (isset($_COOKIE['tolerance_count'])) {
        $_SESSION['tolerance_count'] = intval($_COOKIE['tolerance_count']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DCS Control Panel</title>
    <style>
        body {
            background-color: #333;
            color: white;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }
        h1 {
            text-align: center;
            font-size: 28px;
            color: #FFD700;
        }
        .pump-panel {
            margin-bottom: 30px;
            border: 1px solid #444;
            border-radius: 8px;
            padding: 20px;
            background-color: #222;
        }
        .controls, .inputs {
            width: 48%;
            display: inline-block;
            vertical-align: top;
        }
        .controls button {
            background-color: #FFD700;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            margin: 5px;
            cursor: pointer;
            border-radius: 5px;
        }
        .controls button:hover {
            background-color: #e6b800;
        }
        .inputs input {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            font-size: 16px;
        }
        .inputs label {
            font-size: 14px;
        }
        .output {
            background-color: #1a1a1a;
            padding: 10px;
            border-radius: 5px;
            margin-top: 10px;
            font-size: 16px;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>DCS Control Panel</h1>
    <form method="post" action="">
        <?php foreach ($pressures_mpa as $fluid => $data): ?>
            <div class="pump-panel">
                <h2><?php echo $fluid; ?></h2>
                <div class="controls">
                    <button type="submit">Save Parameters</button>
                </div>
                <div class="inputs">
                    <label for="h_<?php echo $fluid; ?>">Head H:</label>
                    <input type="number" id="h_<?php echo $fluid; ?>" name="h_<?php echo $fluid; ?>" value="<?php echo number_format($initial_pressures[$fluid]['H'], 2); ?>" min="0" step="0.01" required>
                </div>
                <div class="output">
                    Current Pressure:
                    <?php
                    if (isset($initial_pressures[$fluid])) {
                        echo number_format($initial_pressures[$fluid]['pressure'], 2) . " MPa (initial value)";
                    }
                    ?>
                </div>
            </div>
        <?php endforeach; ?>
    </form>
</div>
</body>
</html>
