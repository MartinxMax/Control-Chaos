<?php
session_start();

if ($_SERVER['REMOTE_ADDR'] !== '127.0.0.1' && $_SERVER['REMOTE_ADDR'] !== '::1') {
    header("Location: ./stat/fuck.html");
    exit();
}

$initFilePath = './data/init.json';
$tempFilePath = './data/tempwork.json';
$pwnedFilePath = './data/pwned.json';
$failFilePath = './data/fail.json';

if (!file_exists($initFilePath)) {
    die("Initialization parameter file not found. Please ensure init.json exists.");
}

if (copy($initFilePath, $tempFilePath)) {
    echo "Reset successful: initial data copied to temporary file.<br>";
} else {
    die("Reset failed: unable to copy file.");
}

if (isset($_GET['pwned']) && $_GET['pwned'] == '1') {
    file_put_contents($pwnedFilePath, json_encode(['pwned' => 0]));
    echo "pwned.json status reset to 0.<br>";
}
if (isset($_GET['ip']) && $_GET['ip'] == '1') {
file_put_contents($failFilePath, json_encode([]));
echo "fail.json status to clean.<br>";
}
echo "Fail.json has been restored.";
?>
