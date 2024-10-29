<?php
$task = [
    'code_name' => 'Toxic Cascade',
    'description' => 'Find vulnerabilities in the blueprint of a Ukrainian wastewater treatment plant to create a chemical accident and force the Ukrainian government to concede. Our informant in Ukraine was arrested by the police before submitting the blueprint password, and you need to try to crack this password... maybe the attendance record form of the chemical wastewater treatment plant below can help you... oops! I forgot to tell you, we hacked into the DCS control panel and found some interesting things... perhaps it will assist your further hacking... -------Maptnh@S-H4CK13',
    'target' => 'http://' . $_SERVER['SERVER_ADDR'] . '/target',
    'status' => 'In Progress',
    'deadline' => 'March 30, 2025',
    'objectives' => [
        'Crack the password for the engineering blueprint provided by the informant',
        'Analyze the engineering blueprint to identify vulnerabilities.',
        'Sabotage equipment to create an accident, causing panic and applying pressure on the government.',
        'Each task has 2 chances for error (only 1 chance if input is malicious). If the current task fails, we will reset the task and automatically record your failure count.'
    ],
    'blueprint' => './work/5CFAB199126C48FA784ED1459CADD1A8.zip',
    'attendance' => './work/56F82CB5AADE0F57474C0F9610162E4B.xlsx'
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S-H4CK13-APT Task Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #1a1a1a;
            color: #E0E0E0;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            width: 90%;
            max-width: 800px;
            background-color: #2a2a2a;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.6);
            position: relative;
            z-index: 1;
        }
        h1 {
            color: #FFD700;
            font-size: 2em;
            margin-bottom: 20px;
            text-align: center;
            letter-spacing: 2px;
        }
        .task-info {
            margin-bottom: 20px;
        }
        .task-info h2 {
            font-size: 1.4em;
            color: #FFC107;
            margin-bottom: 10px;
        }
        .task-info p {
            margin: 5px 0;
            font-size: 1.1em;
            line-height: 1.6;
        }
        .objectives {
            list-style: none;
            padding: 0;
        }
        .objectives li {
            background-color: #333;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            color: #B0BEC5;
            font-size: 1.1em;
        }
        .objectives li:before {
            content: '✓';
            margin-right: 10px;
            color: #FFD700;
        }
        .download-link {
            display: block;
            text-align: center;
            background-color: #FFD700;
            color: #1a1a1a;
            padding: 15px;
            text-decoration: none;
            border-radius: 5px;
            font-size: 1.2em;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .download-link:hover {
            background-color: #FFC107;
        }
        .image-wall {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .image-wall img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.5);
            cursor: pointer;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.9);
            justify-content: center;
            align-items: center;
        }
        .modal img {
            max-width: 90%;
            max-height: 90%;
            object-fit: contain;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.8);
        }
        .play-button {
            width: 80px;
            height: 80px;
            background-color: #FFD700;
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.5);
            transition: background-color 0.3s, transform 0.3s;
            margin: 30px auto;
        }
        .play-button:hover {
            background-color: #FFC107;
            transform: scale(1.1);
        }
        .play-button i {
            font-size: 40px;
            color: #1a1a1a;
        }
    </style>
</head>
<body>
<div class="container">
    <h1><?php echo $task['code_name']; ?></h1>

    <div class="image-wall">
        <img src="./pic/1.jpg" alt="Intelligence Image 1" onclick="openModal(this)">
        <img src="./pic/2.jpg" alt="Intelligence Image 2" onclick="openModal(this)">
    </div>

    <div class="task-info">
        <h2>Task Details</h2>
        <p><strong>Description:</strong> <?php echo $task['description']; ?></p>
        <p><strong>Target:</strong> <?php echo $task['target']; ?></p>
        <p><strong>Status:</strong> <?php echo $task['status']; ?></p>
        <p><strong>Deadline:</strong> <?php echo $task['deadline']; ?></p>
    </div>

    <h2>Objectives</h2>
    <ul class="objectives">
        <?php foreach ($task['objectives'] as $objective): ?>
            <li><?php echo $objective; ?></li>
        <?php endforeach; ?>
    </ul>

    <a href="<?php echo $task['blueprint']; ?>" class="download-link" download>Download Blueprint</a>
    <a href="<?php echo $task['attendance']; ?>" class="download-link" download>Download Attendance Record</a>

    <div class="play-button" onclick="playAudio()">
        <i class="fas fa-play"></i>
    </div>
    <audio id="task-audio" src="./voice/main.mp3"></audio>
</div>

<div id="image-modal" class="modal" onclick="closeModal()">
    <img id="modal-image" src="">
</div>

<script>
    function openModal(image) {
        const modal = document.getElementById('image-modal');
        const modalImg = document.getElementById('modal-image');
        modal.style.display = "flex";
        modalImg.src = image.src;
    }

    function closeModal() {
        const modal = document.getElementById('image-modal');
        modal.style.display = "none";
    }

    function playAudio() {
        if (confirm("Do you want to enable sound?")) {
            const audio = document.getElementById('task-audio');
            audio.volume = 0.5;
            audio.play();
        }
    }
</script>

</body>
</html>
