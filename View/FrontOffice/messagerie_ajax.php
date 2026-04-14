<?php
session_start();
require_once __DIR__ . '/../../Model/MessageModel.php';

// ID de l'utilisateur connecté (TechCorp = 1)
$current_user_id = 1;

$model = new MessageModel();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

header('Content-Type: application/json');

switch ($action) {
    case 'send_message':
        $conv_id = intval($_POST['conv_id']);
        $content = trim($_POST['content']);
        if ($content !== '') {
            $success = $model->sendMessage($conv_id, $current_user_id, $content);
            echo json_encode(['success' => $success]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Message vide']);
        }
        break;

    case 'get_or_create_conv':
        $other_id = intval($_GET['other_id']);
        $conv_id = $model->getOrCreateConversation($current_user_id, $other_id);
        echo json_encode(['conv_id' => $conv_id]);
        break;

    case 'get_conversations':
        $conversations = $model->getConversations($current_user_id);
        $unread_total = $model->countUnreadMessages($current_user_id);
        echo json_encode([
            'conversations' => $conversations,
            'unread_total' => $unread_total
        ]);
        break;

    case 'get_messages':
        $conv_id = intval($_GET['conv_id']);
        $model->markAllAsRead($conv_id, $current_user_id);
        $messages = $model->getMessages($conv_id);
        echo json_encode($messages);
        break;

    case 'update_message':
        $message_id = intval($_POST['message_id']);
        $content = trim($_POST['content']);
        if ($content !== '') {
            $success = $model->updateMessage($message_id, $current_user_id, $content);
            echo json_encode(['success' => $success]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Message vide']);
        }
        break;

    case 'delete_message':
        $message_id = intval($_POST['message_id']);
        $success = $model->softDeleteMessage($message_id, $current_user_id);
        echo json_encode(['success' => $success]);
        break;

    case 'delete_conversation':
        $conv_id = intval($_POST['conv_id']);
        $success = $model->deleteConversation($conv_id, $current_user_id);
        echo json_encode(['success' => $success]);
        break;

    default:
        echo json_encode(['error' => 'Action inconnue']);
        break;
}
?>