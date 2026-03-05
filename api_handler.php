<?php
require_once 'database.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonResponse(false, [], 'Invalid request method');
}

// Check if user is logged in
if (!isLoggedIn()) {
    jsonResponse(false, [], 'Authentication required');
}

$user_id = getCurrentUserId();
$message = sanitizeInput($_POST['message'] ?? '');

if (empty($message)) {
    jsonResponse(false, [], 'Message cannot be empty');
}

try {
    // Start transaction
    $conn->begin_transaction();
    
    // Check if we need to create a new chat session
    $chat_id = $_SESSION['current_chat_id'] ?? null;
    
    if (!$chat_id) {
        // Generate chat title from first message
        $chat_title = substr($message, 0, 50) . (strlen($message) > 50 ? '...' : '');
        $chat_session_id = generateSessionId();
        
        // Create new chat
        $chat_id = $db->insert('chats', [
            'user_id' => $user_id,
            'chat_session_id' => $chat_session_id,
            'title' => $chat_title
        ]);
        
        $_SESSION['current_chat_id'] = $chat_id;
    }
    
    // Save user message
    $message_id = $db->insert('messages', [
        'chat_id' => $chat_id,
        'user_id' => $user_id,
        'message_type' => 'user',
        'content' => $message
    ]);
    
    // Get AI response using stored function
    $response_query = "SELECT GetBestAIResponse(?) as response";
    $stmt = $conn->prepare($response_query);
    $stmt->bind_param("s", $message);
    $stmt->execute();
    $result = $stmt->get_result();
    $ai_response = $result->fetch_assoc()['response'];
    $stmt->close();
    
    // If no response from function, use fallback
    if (!$ai_response) {
        $ai_response = generateFallbackResponse($message);
    }
    
    // Save AI response
    $db->insert('messages', [
        'chat_id' => $chat_id,
        'user_id' => $user_id,
        'message_type' => 'ai',
        'content' => $ai_response,
        'response_time' => 0.8
    ]);
    
    // Update chat timestamp
    $conn->query("UPDATE chats SET updated_at = NOW() WHERE id = $chat_id");
    
    // Update user statistics
    $conn->query("
        UPDATE user_statistics 
        SET total_messages = total_messages + 2,
            last_activity = NOW()
        WHERE user_id = $user_id
    ");
    
    // Commit transaction
    $conn->commit();
    
    // Return success response
    jsonResponse(true, [
        'response' => $ai_response,
        'chat_id' => $chat_id,
        'message_id' => $message_id,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    
} catch (Exception $e) {
    // Rollback on error
    $conn->rollback();
    
    error_log("API Handler Error: " . $e->getMessage());
    jsonResponse(false, [], 'An error occurred. Please try again.');
}

// Fallback response generator
function generateFallbackResponse($message) {
    $responses = [
        "I understand you're asking about **" . ucfirst($message) . "**. As a premium AI assistant, I can provide detailed insights on this topic. Could you please specify what particular aspect you'd like to know about?",
        
        "**Premium Analysis:** Your query about '" . $message . "' is interesting. Based on my knowledge base, I can provide comprehensive information. Would you like a detailed explanation, examples, or practical applications?",
        
        "Thank you for your premium question! Regarding **" . $message . "**, I can offer:\n1. Basic explanation\n2. Advanced concepts\n3. Real-world examples\n4. Implementation guide\n\nPlease specify your preference.",
        
        "**Classic AI Response:** The topic '" . $message . "' falls within my expertise. I can provide you with a structured response including:\n• Key definitions\n• Historical context\n• Current applications\n• Future trends\n\nLet me know which aspect interests you most.",
        
        "I've analyzed your query about **" . $message . "**. Here's what I can help you with:\n\n**Quick Overview:** Basic concepts and definitions\n**Deep Dive:** Advanced details and technical aspects\n**Examples:** Practical implementations and use cases\n**Resources:** Further reading and learning materials\n\nWhich level of detail would you prefer?"
    ];
    
    return $responses[array_rand($responses)];
}
?>
