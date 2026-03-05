<?php
header('Content-Type: application/json');
require_once 'db.php';
require_once 'ChatBot.php';

$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['message']) && isset($input['conversation_id'])) {
    $userMessage = $conn->real_escape_string($input['message']);
    $conversationId = (int)$input['conversation_id']; // For now, we might assume a single conversation or passed ID

    // Deal with new conversation logic if ID is 0 or -1
    if ($conversationId <= 0) {
        $title = substr($userMessage, 0, 30) . "...";
        $sql = "INSERT INTO conversations (title) VALUES ('$title')";
        if ($conn->query($sql) === TRUE) {
            $conversationId = $conn->insert_id;
        } else {
            echo json_encode(['error' => 'Database error creating conversation']);
            exit;
        }
    }

    // Save User Message
    $sql = "INSERT INTO messages (conversation_id, role, content) VALUES ($conversationId, 'user', '$userMessage')";
    $conn->query($sql);

    // Get Bot Response
    $bot = new ChatBot();
    $botResponse = $bot->getResponse($userMessage);
    $escapedBotResponse = $conn->real_escape_string($botResponse);

    // Save Bot Message
    $sql_bot = "INSERT INTO messages (conversation_id, role, content) VALUES ($conversationId, 'assistant', '$escapedBotResponse')";
    $conn->query($sql_bot);

    echo json_encode([
        'response' => $botResponse,
        'conversation_id' => $conversationId
    ]);

} elseif (isset($_GET['action']) && $_GET['action'] == 'history') {
    // Fetch conversations
    $sql = "SELECT * FROM conversations ORDER BY created_at DESC";
    $result = $conn->query($sql);
    $history = [];
    while($row = $result->fetch_assoc()) {
        $history[] = $row;
    }
    echo json_encode($history);

} elseif (isset($_GET['action']) && $_GET['action'] == 'load' && isset($_GET['id'])) {
    // Load specific conversation
    $id = (int)$_GET['id'];
    $sql = "SELECT * FROM messages WHERE conversation_id = $id ORDER BY created_at ASC";
    $result = $conn->query($sql);
    $messages = [];
    while($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }
    echo json_encode($messages);
} else {
    echo json_encode(['error' => 'Invalid request']);
}

$conn->close();
?>
