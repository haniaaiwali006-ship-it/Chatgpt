<?php
require_once 'db.php';

$message = "";

// Handle clear history
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['clear_history'])) {
    $conn->query("SET FOREIGN_KEY_CHECKS = 0");
    $conn->query("TRUNCATE TABLE messages");
    $conn->query("TRUNCATE TABLE conversations");
    $conn->query("SET FOREIGN_KEY_CHECKS = 1");
    $message = "All chat history has been cleared.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings - ChatGPT Clone</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg-color: #0d1117;
            --sidebar-bg: #161b22;
            --input-bg: #21262d;
            --text-color: #e6edf3;
            --border-color: #30363d;
            --danger-color: #da3633;
            --success-color: #238636;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--bg-color);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .settings-container {
            width: 100%;
            max-width: 600px;
            background-color: var(--sidebar-bg);
            padding: 30px;
            border-radius: 10px;
            border: 1px solid var(--border-color);
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
        }

        h2 {
            margin-bottom: 20px;
            font-weight: 500;
            border-bottom: 1px solid var(--border-color);
            padding-bottom: 10px;
        }

        .setting-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
            border-bottom: 1px solid var(--border-color);
        }

        .setting-info h4 { margin-bottom: 5px; }
        .setting-info p { font-size: 0.85rem; color: #8b949e; }

        .btn {
            padding: 10px 20px;
            border-radius: 5px;
            border: 1px solid var(--border-color);
            background-color: var(--input-bg);
            color: var(--text-color);
            cursor: pointer;
            text-decoration: none;
            font-size: 0.9rem;
            transition: background 0.2s;
        }

        .btn:hover { background-color: var(--border-color); }
        
        .btn-danger {
            background-color: rgba(218, 54, 51, 0.1);
            color: var(--danger-color);
            border-color: rgba(218, 54, 51, 0.5);
        }
        
        .btn-danger:hover {
            background-color: var(--danger-color);
            color: white;
        }

        .alert {
            padding: 15px;
            background-color: rgba(35, 134, 54, 0.1);
            border: 1px solid var(--success-color);
            color: var(--success-color);
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .back-link {
            display: block;
            margin-top: 20px;
            color: #58a6ff;
            text-decoration: none;
        }
    </style>
</head>
<body>

    <div class="settings-container">
        <h2>Settings</h2>

        <?php if ($message): ?>
            <div class="alert"><?php echo $message; ?></div>
        <?php endif; ?>

        <div class="setting-item">
            <div class="setting-info">
                <h4>Theme</h4>
                <p>System is set to Dark Mode (Premium)</p>
            </div>
            <button class="btn" disabled>Dark Mode</button>
        </div>

        <div class="setting-item">
            <div class="setting-info">
                <h4>Data Controls</h4>
                <p>Clear all your conversation history.</p>
            </div>
            <form method="POST" onsubmit="return confirm('Are you sure? This cannot be undone.');">
                <button type="submit" name="clear_history" class="btn btn-danger">Clear All Chats</button>
            </form>
        </div>

        <a href="index.php" class="back-link">← Back to Chat</a>
    </div>

</body>
</html>
