<?php
session_start();
$current_user_id = 1; // TechCorp
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Entreprise | Messagerie</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        body { background-color: #f1f5f9; }
        .dashboard-container { display: flex; min-height: 100vh; }
        .sidebar { width: 280px; background: white; box-shadow: var(--shadow-md); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 100; transition: var(--transition); }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid var(--gray-light); display: flex; align-items: center; }
        .sidebar-menu { padding: 1rem 0; flex: 1; overflow-y: auto; }
        .menu-item { padding: 0.75rem 1.5rem; display: flex; align-items: center; gap: 1rem; color: var(--gray); font-weight: 500; cursor: pointer; transition: var(--transition); border-left: 3px solid transparent; text-decoration: none;}
        .menu-item:hover, .menu-item.active { background: rgba(37, 99, 235, 0.05); color: var(--primary); }
        .menu-item.active { border-left-color: var(--primary); }
        .menu-item i { width: 20px; text-align: center; font-size: 1.1rem; }
        .user-profile-widget { padding: 1rem 1.5rem; border-top: 1px solid var(--gray-light); display: flex; align-items: center; gap: 1rem; background: white; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--primary); color: white; display: flex; justify-content: center; align-items: center; font-weight: 600; }
        .main-content { flex: 1; margin-left: 280px; padding: 2rem; }
        .top-navbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; background: white; padding: 1rem 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); }
        .chat-container { display: flex; height: 550px; border: 1px solid var(--gray-light); border-radius: var(--radius-lg); overflow: hidden; background: white; }
        .chat-sidebar { width: 280px; border-right: 1px solid var(--gray-light); background: white; overflow-y: auto; }
        .chat-list-item { padding: 1rem; border-bottom: 1px solid var(--gray-light); display: flex; gap: 1rem; cursor: pointer; transition: var(--transition); align-items: center; }
        .chat-list-item:hover, .chat-list-item.active { background: #eff6ff; }
        .chat-main { flex: 1; display: flex; flex-direction: column; background: #f8fafc; }
        .chat-header { padding: 1rem; background: white; border-bottom: 1px solid var(--gray-light); display: flex; justify-content: space-between; align-items: center; }
        .chat-messages { flex: 1; padding: 1.5rem; overflow-y: auto; display: flex; flex-direction: column; gap: 1rem; }
        .message { max-width: 70%; padding: 0.75rem 1rem; border-radius: var(--radius-lg); font-size: 0.95rem; position: relative; }
        .message.received { background: white; align-self: flex-start; box-shadow: var(--shadow-sm); border-bottom-left-radius: 0; }
        .message.sent { background: var(--primary); color: white; align-self: flex-end; box-shadow: var(--shadow-sm); border-bottom-right-radius: 0; }
        .message-actions { position: absolute; top: -25px; right: 0; display: none; gap: 5px; background: white; padding: 4px 8px; border-radius: 20px; box-shadow: var(--shadow-sm); }
        .message:hover .message-actions { display: flex; }
        .message-actions button { background: none; border: none; cursor: pointer; font-size: 12px; padding: 2px 5px; border-radius: 50%; }
        .message-actions button.edit:hover { color: var(--primary); }
        .message-actions button.delete:hover { color: var(--danger); }
        .chat-input { padding: 1rem; background: white; border-top: 1px solid var(--gray-light); display: flex; gap: 0.75rem; align-items: center; }
        .chat-input input { flex: 1; padding: 0.75rem 1rem; border: 1px solid var(--gray-light); border-radius: var(--radius-full); outline: none; font-family: var(--font-main); }
        .chat-input input:focus { border-color: var(--primary); }
        .btn { padding: 0.5rem 1rem; border-radius: var(--radius); font-weight: 500; cursor: pointer; transition: var(--transition); border: none; font-family: inherit; }
        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: #1d4ed8; }
        .btn-outline { background: transparent; border: 1px solid var(--gray-light); color: var(--gray); }
        .btn-outline:hover { border-color: var(--primary); color: var(--primary); }
        .unread-badge { background: red; color: white; border-radius: 50%; padding: 2px 6px; font-size: 0.7rem; margin-left: 5px; }
        .modal { display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 1001; justify-content: center; align-items: center; }
        .modal-content { background: white; border-radius: 16px; padding: 2rem; width: 400px; }
        .modal-content textarea { width: 100%; padding: 10px; margin: 15px 0; border: 1px solid var(--gray-light); border-radius: 8px; font-family: inherit; resize: vertical; }
        .message-time { font-size: 0.7rem; margin-top: 5px; opacity: 0.7; }
        .fade-in-up { animation: fadeInUp 0.5s ease forwards; }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>
<div class="dashboard-container">
    <aside class="sidebar slide-in-right" style="animation-duration: 0.4s;">
        <div class="sidebar-header"><a href="index.php" class="logo" style="text-decoration: none;"><i class="fa-solid fa-chart-pie text-primary"></i> Digit Advisory</a></div>
        <div class="sidebar-menu">
            <a href="front-entreprise-dashboard.php" class="menu-item"><i class="fa-solid fa-house"></i> Vue d'ensemble</a>
            <a href="front-utilisateur.php" class="menu-item"><i class="fa-solid fa-building"></i> Profil Entreprise</a>
            <a href="front-quiz.php" class="menu-item"><i class="fa-solid fa-list-check"></i> Questionnaire</a>
            <a href="front-portfolio.php" class="menu-item"><i class="fa-solid fa-folder-open"></i> Mon Portfolio</a>
            <a href="front-offres.php" class="menu-item"><i class="fa-solid fa-briefcase"></i> Mes Offres de Mission</a>
            <a href="front-certification.php" class="menu-item"><i class="fa-solid fa-award"></i> Certifications ISO</a>
            <a href="front-messagerie.php" class="menu-item active"><i class="fa-solid fa-comments"></i> Messagerie</a>
        </div>
        <div class="user-profile-widget">
            <div class="user-avatar">TC</div>
            <div><h4 style="font-size: 0.95rem; margin-bottom: 0.2rem;">TechCorp SAS</h4><span style="font-size: 0.8rem; color: var(--gray);">Compte Entreprise</span></div>
            <a href="login.php" style="margin-left: auto; color: var(--danger);"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
        </div>
    </aside>

    <main class="main-content">
        <div class="top-navbar">
            <h2 style="margin: 0; font-size: 1.5rem;">Messagerie <span id="unreadBadge" style="background:red; color:white; border-radius:50%; padding:2px 8px; font-size:0.8rem; margin-left:10px; display:none;"></span></h2>
            <button class="btn btn-primary" id="newConversationBtn" style="padding: 0.5rem 1rem;"><i class="fa-solid fa-plus"></i> Nouvelle conversation</button>
        </div>
        <section class="fade-in-up">
            <div class="chat-container">
                <div class="chat-sidebar" id="conversationsList">
                    <div style="padding:1rem; text-align:center; color:var(--gray);">Chargement...</div>
                </div>
                <div class="chat-main">
                    <div class="chat-header" id="chatHeader">
                        <div style="color:var(--gray);">Sélectionnez une conversation</div>
                    </div>
                    <div class="chat-messages" id="chatMessages">
                        <div style="text-align:center; color:var(--gray); padding:2rem;">Sélectionnez une conversation pour voir les messages</div>
                    </div>
                    <div class="chat-input" id="chatInput" style="display: none;">
                        <input type="text" id="messageInput" placeholder="Écrire un message...">
                        <button class="btn btn-primary" id="sendBtn"><i class="fa-solid fa-paper-plane"></i> Envoyer</button>
                        <button class="btn btn-outline" id="deleteConvBtn" style="color:var(--danger); border-color:var(--danger);"><i class="fa-solid fa-trash"></i> Supprimer</button>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>

<!-- Modal nouvelle conversation -->
<div id="newConvModal" class="modal">
    <div class="modal-content">
        <h3><i class="fa-solid fa-plus-circle"></i> Nouvelle conversation</h3>
        <select id="newConvUser" style="width:100%; padding:10px; margin:15px 0; border-radius:8px; border:1px solid var(--gray-light);">
            <option value="2">👩‍💼 Alice Martin (Expert)</option>
            <option value="3">👨‍💼 Jean Dupont (Expert)</option>
        </select>
        <div style="display:flex; gap:10px; justify-content:flex-end;">
            <button class="btn btn-outline" id="closeModalBtn">Annuler</button>
            <button class="btn btn-primary" id="createConvBtn">Créer</button>
        </div>
    </div>
</div>

<!-- Modal modification message -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <h3><i class="fa-solid fa-pen"></i> Modifier le message</h3>
        <textarea id="editContent" rows="4" style="width:100%;"></textarea>
        <input type="hidden" id="editMessageId">
        <div style="display:flex; gap:10px; justify-content:flex-end;">
            <button class="btn btn-outline" id="closeEditBtn">Annuler</button>
            <button class="btn btn-primary" id="saveEditBtn">Enregistrer</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let currentConversationId = null;
let currentOtherUserName = '';

// ========== CHARGER LES CONVERSATIONS ==========
function loadConversations() {
    $.get('messagerie_ajax.php', { action: 'get_conversations' }, function(data) {
        let html = '';
        if (!data.conversations || data.conversations.length === 0) {
            html = '<div style="padding:1rem; text-align:center; color:var(--gray);">Aucune conversation</div>';
            $('#chatInput').hide();
            $('#chatHeader').html('<div style="color:var(--gray);">Sélectionnez une conversation</div>');
            $('#chatMessages').html('<div style="text-align:center; color:var(--gray); padding:2rem;">Créez une nouvelle conversation pour commencer</div>');
            currentConversationId = null;
            currentOtherUserName = '';
        } else {
            data.conversations.forEach(conv => {
                let activeClass = (currentConversationId == conv.id) ? 'active' : '';
                let unreadBadge = conv.unread > 0 ? `<span class="unread-badge">${conv.unread}</span>` : '';
                let lastMsg = conv.last_message ? (conv.last_message.length > 35 ? conv.last_message.substring(0,35)+'...' : conv.last_message) : 'Nouvelle conversation';
                html += `
                    <div class="chat-list-item ${activeClass}" data-id="${conv.id}" data-other="${conv.other_user_name}">
                        <div style="width:40px; height:40px; min-width:40px; border-radius:50%; background:var(--secondary); color:white; display:flex; justify-content:center; align-items:center; font-weight:600;">${escapeHtml(conv.other_user_name.charAt(0))}</div>
                        <div style="flex:1;">
                            <h4 style="font-size: 0.9rem;">${escapeHtml(conv.other_user_name)}</h4>
                            <p style="font-size: 0.8rem; color: var(--gray);">${escapeHtml(lastMsg)}</p>
                        </div>
                        ${unreadBadge}
                    </div>
                `;
            });
        }
        $('#conversationsList').html(html);
        
        if (data.unread_total > 0) {
            $('#unreadBadge').text(data.unread_total).show();
        } else {
            $('#unreadBadge').hide();
        }
        
        $('.chat-list-item').off('click').on('click', function() {
            let convId = $(this).data('id');
            let otherName = $(this).data('other');
            currentConversationId = convId;
            currentOtherUserName = otherName;
            loadMessages(convId);
            $('.chat-list-item').removeClass('active');
            $(this).addClass('active');
            $('#chatInput').show();
        });
        
        if (currentConversationId !== null) {
            let stillExists = false;
            data.conversations.forEach(conv => {
                if (conv.id == currentConversationId) stillExists = true;
            });
            if (!stillExists) {
                currentConversationId = null;
                currentOtherUserName = '';
                $('#chatInput').hide();
                $('#chatHeader').html('<div style="color:var(--gray);">Sélectionnez une conversation</div>');
                $('#chatMessages').html('<div style="text-align:center; color:var(--gray); padding:2rem;">Sélectionnez une conversation pour voir les messages</div>');
            }
        }
        
        if (currentConversationId === null && data.conversations && data.conversations.length > 0) {
            $('.chat-list-item').first().trigger('click');
        }
    }, 'json');
}

// ========== CHARGER LES MESSAGES ==========
function loadMessages(convId) {
    if (!convId) return;
    $('#chatMessages').html('<div style="text-align:center; padding:2rem;">Chargement des messages...</div>');
    $.get('messagerie_ajax.php', { action: 'get_messages', conv_id: convId }, function(messages) {
        let html = '';
        if (!messages || messages.length === 0) {
            html = '<div style="text-align:center; color:var(--gray); padding:2rem;">Aucun message. Envoyez le premier message !</div>';
        } else {
            messages.forEach(msg => {
                let isSent = (msg.sender_id == 1);
                let cssClass = isSent ? 'sent' : 'received';
                let time = new Date(msg.created_at).toLocaleString();
                let actions = '';
                if (isSent) {
                    actions = `
                        <div class="message-actions">
                            <button class="edit" onclick="openEditModal(${msg.id}, '${escapeHtml(msg.content).replace(/'/g, "\\'")}')"><i class="fa-solid fa-pen"></i></button>
                            <button class="delete" onclick="deleteMessage(${msg.id})"><i class="fa-solid fa-trash"></i></button>
                        </div>
                    `;
                }
                html += `
                    <div class="message ${cssClass}">
                        ${actions}
                        ${escapeHtml(msg.content)}
                        <div class="message-time">${time}</div>
                    </div>
                `;
            });
        }
        $('#chatMessages').html(html);
        $('#chatHeader').html(`
            <div style="display:flex; align-items:center; gap:1rem;">
                <div style="width:35px; height:35px; border-radius:50%; background:var(--secondary); color:white; display:flex; justify-content:center; align-items:center; font-weight:600; font-size:0.8rem;">${escapeHtml(currentOtherUserName.charAt(0))}</div>
                <div>
                    <h4 style="font-size: 0.95rem;">${escapeHtml(currentOtherUserName)}</h4>
                    <span style="font-size: 0.75rem; color: var(--success);"><i class="fa-solid fa-circle" style="font-size:0.5rem;"></i> En ligne</span>
                </div>
            </div>
        `);
        $('#chatMessages').scrollTop($('#chatMessages')[0].scrollHeight);
    }, 'json');
}

// ========== ENVOYER UN MESSAGE ==========
function sendMessage() {
    let content = $('#messageInput').val().trim();
    if (!content) { alert('Veuillez écrire un message'); return; }
    if (!currentConversationId) { alert('Veuillez sélectionner une conversation'); return; }
    
    $.post('messagerie_ajax.php', { action: 'send_message', conv_id: currentConversationId, content: content }, function(response) {
        if (response.success) {
            $('#messageInput').val('');
            loadMessages(currentConversationId);
            loadConversations();
        } else {
            alert('Erreur lors de l\'envoi');
        }
    }, 'json');
}

// ========== MODIFIER UN MESSAGE ==========
function openEditModal(msgId, content) {
    $('#editMessageId').val(msgId);
    $('#editContent').val(content);
    $('#editModal').css('display', 'flex');
}

function saveEditMessage() {
    let msgId = $('#editMessageId').val();
    let newContent = $('#editContent').val().trim();
    if (!newContent) { alert('Le message ne peut pas être vide'); return; }
    
    $.post('messagerie_ajax.php', { action: 'update_message', message_id: msgId, content: newContent }, function(response) {
        if (response.success) {
            $('#editModal').css('display', 'none');
            loadMessages(currentConversationId);
            loadConversations();
        } else {
            alert('Erreur modification');
        }
    }, 'json');
}

// ========== SUPPRIMER UN MESSAGE ==========
function deleteMessage(msgId) {
    if (confirm('Supprimer ce message ?')) {
        $.post('messagerie_ajax.php', { action: 'delete_message', message_id: msgId }, function(response) {
            if (response.success) {
                loadMessages(currentConversationId);
                loadConversations();
            } else {
                alert('Erreur suppression');
            }
        }, 'json');
    }
}

// ========== SUPPRIMER LA CONVERSATION ==========
function deleteConversation() {
    if (!currentConversationId) return;
    if (confirm('⚠️ Supprimer toute cette conversation ? Cette action est irréversible.')) {
        $.post('messagerie_ajax.php', { action: 'delete_conversation', conv_id: currentConversationId }, function(response) {
            if (response.success) {
                currentConversationId = null;
                currentOtherUserName = '';
                $('#chatInput').hide();
                $('#chatHeader').html('<div style="color:var(--gray);">Sélectionnez une conversation</div>');
                $('#chatMessages').html('<div style="text-align:center; color:var(--gray); padding:2rem;">Conversation supprimée</div>');
                loadConversations();
            } else {
                alert('Erreur suppression conversation');
            }
        }, 'json');
    }
}

// ========== NOUVELLE CONVERSATION ==========
function showNewConversationModal() { $('#newConvModal').css('display', 'flex'); }
function closeNewConversationModal() { $('#newConvModal').css('display', 'none'); }
function createNewConversation() {
    let otherId = $('#newConvUser').val();
    let otherName = $('#newConvUser option:selected').text().replace(/^[^)]+\)\s*/, '');
    $.get('messagerie_ajax.php', { action: 'get_or_create_conv', other_id: otherId }, function(data) {
        if (data.conv_id) {
            currentConversationId = data.conv_id;
            currentOtherUserName = otherName;
            closeNewConversationModal();
            loadMessages(currentConversationId);
            loadConversations();
            $('#chatInput').show();
            setTimeout(() => {
                $('.chat-list-item').removeClass('active');
                $(`.chat-list-item[data-id="${currentConversationId}"]`).addClass('active');
                $('#messageInput').focus();
            }, 500);
        } else {
            alert('Erreur lors de la création de la conversation');
        }
    }, 'json');
}

// ========== UTILITAIRE ==========
function escapeHtml(text) {
    if (!text) return '';
    return text.replace(/[&<>]/g, function(m) {
        if (m === '&') return '&amp;';
        if (m === '<') return '&lt;';
        if (m === '>') return '&gt;';
        return m;
    });
}

// ========== INITIALISATION ==========
$(document).ready(function() {
    loadConversations();
    $('#sendBtn').click(sendMessage);
    $('#messageInput').keypress(function(e) { if (e.which == 13) sendMessage(); });
    $('#deleteConvBtn').click(deleteConversation);
    $('#newConversationBtn').click(showNewConversationModal);
    $('#closeModalBtn').click(closeNewConversationModal);
    $('#createConvBtn').click(createNewConversation);
    $('#closeEditBtn').click(function() { $('#editModal').css('display', 'none'); });
    $('#saveEditBtn').click(saveEditMessage);
    $(window).click(function(e) { if ($(e.target).hasClass('modal')) { $('.modal').css('display', 'none'); } });
    setInterval(() => {
        if (currentConversationId) { loadMessages(currentConversationId); }
        loadConversations();
    }, 5000);
});
</script>
</body>
</html>