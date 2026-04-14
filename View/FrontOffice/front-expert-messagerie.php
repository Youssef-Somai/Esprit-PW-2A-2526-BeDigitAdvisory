<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Expert | Messagerie</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&family=Poppins:wght@500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        body { background-color: #f1f5f9; }
        .dashboard-container { display: flex; min-height: 100vh; }
        .sidebar { width: 280px; background: white; box-shadow: var(--shadow-md); display: flex; flex-direction: column; position: fixed; height: 100vh; z-index: 100; }
        .sidebar-header { padding: 1.5rem; border-bottom: 1px solid var(--gray-light); display: flex; align-items: center; }
        .sidebar-menu { padding: 1rem 0; flex: 1; overflow-y: auto; }
        .menu-item { padding: 0.75rem 1.5rem; display: flex; align-items: center; gap: 1rem; color: var(--gray); font-weight: 500; cursor: pointer; transition: var(--transition); border-left: 3px solid transparent; text-decoration: none; }
        .menu-item:hover, .menu-item.active { background: rgba(14, 165, 233, 0.05); color: var(--secondary); }
        .menu-item.active { border-left-color: var(--secondary); }
        .menu-item i { width: 20px; text-align: center; font-size: 1.1rem; }
        .user-profile-widget { padding: 1rem 1.5rem; border-top: 1px solid var(--gray-light); display: flex; align-items: center; gap: 1rem; background: white; }
        .user-avatar { width: 40px; height: 40px; border-radius: 50%; background: var(--secondary); color: white; display: flex; justify-content: center; align-items: center; font-weight: 600; }
        .main-content { flex: 1; margin-left: 280px; padding: 2rem; }
        .top-navbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; background: white; padding: 1rem 2rem; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); }

        .chat-wrapper { display: flex; height: calc(100vh - 180px); min-height: 500px; background: white; border-radius: var(--radius-lg); box-shadow: var(--shadow-sm); overflow: hidden; }
        .conv-sidebar { width: 300px; min-width: 300px; border-right: 1px solid var(--gray-light); display: flex; flex-direction: column; }
        .conv-sidebar-header { padding: 1rem 1.2rem; border-bottom: 1px solid var(--gray-light); display: flex; justify-content: space-between; align-items: center; }
        .conv-sidebar-header h3 { font-size: 1rem; margin: 0; }
        .conv-list { flex: 1; overflow-y: auto; }
        .conv-item { padding: 0.9rem 1.2rem; border-bottom: 1px solid var(--gray-light); cursor: pointer; transition: var(--transition); display: flex; align-items: flex-start; gap: 0.75rem; text-decoration: none; color: inherit; }
        .conv-item:hover { background: #f8fafc; }
        .conv-item.active { background: #f0f9ff; border-left: 3px solid var(--secondary); }
        .conv-avatar { width: 40px; height: 40px; min-width: 40px; border-radius: 50%; background: var(--primary); color: white; display: flex; justify-content: center; align-items: center; font-weight: 600; font-size: 0.85rem; }
        .conv-info { flex: 1; overflow: hidden; }
        .conv-info h4 { font-size: 0.9rem; margin: 0 0 0.2rem; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .conv-info p { font-size: 0.78rem; color: var(--gray); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; margin: 0; }
        .conv-time { font-size: 0.72rem; color: var(--gray); white-space: nowrap; }

        .chat-main { flex: 1; display: flex; flex-direction: column; }
        .chat-header { padding: 1rem 1.5rem; border-bottom: 1px solid var(--gray-light); display: flex; justify-content: space-between; align-items: center; }
        .chat-header-info { display: flex; align-items: center; gap: 0.75rem; }
        .online-dot { width: 8px; height: 8px; border-radius: 50%; background: var(--success); display: inline-block; margin-right: 4px; }

        .chat-messages { flex: 1; padding: 1.5rem; overflow-y: auto; display: flex; flex-direction: column; gap: 0.75rem; background: #f8fafc; }
        .chat-empty { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--gray); gap: 0.5rem; background: #f8fafc; }

        .msg-row { display: flex; align-items: flex-end; gap: 0.5rem; }
        .msg-row.sent { flex-direction: row-reverse; }
        .msg-avatar-sm { width: 28px; height: 28px; min-width: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 0.65rem; font-weight: 700; color: white; }
        .msg-bubble-wrapper { max-width: 65%; display: flex; flex-direction: column; gap: 0.25rem; }
        .msg-row.sent .msg-bubble-wrapper { align-items: flex-end; }
        .msg-bubble { padding: 0.65rem 1rem; border-radius: 16px; font-size: 0.9rem; line-height: 1.5; word-break: break-word; }
        .msg-bubble.received { background: white; border-bottom-left-radius: 4px; box-shadow: var(--shadow-sm); }
        .msg-bubble.sent { background: var(--secondary); color: white; border-bottom-right-radius: 4px; }
        .msg-meta { font-size: 0.72rem; color: var(--gray); display: flex; gap: 0.5rem; align-items: center; }
        .msg-row.sent .msg-meta { flex-direction: row-reverse; }
        .edited-tag { font-size: 0.68rem; color: var(--gray); font-style: italic; }

        .msg-actions { display: none; gap: 0.25rem; }
        .msg-row:hover .msg-actions { display: flex; }
        .msg-action-btn { background: white; border: 1px solid var(--gray-light); border-radius: 6px; padding: 0.2rem 0.45rem; font-size: 0.75rem; cursor: pointer; color: var(--gray); transition: var(--transition); }
        .msg-action-btn:hover { background: #f1f5f9; color: var(--secondary); }
        .msg-action-btn.del:hover { color: var(--danger); border-color: var(--danger); }

        .chat-input-area { padding: 1rem 1.5rem; background: white; border-top: 1px solid var(--gray-light); }
        .chat-input-bar { display: flex; gap: 0.75rem; align-items: center; }
        .chat-input-bar input { flex: 1; padding: 0.7rem 1rem; border: 1px solid var(--gray-light); border-radius: var(--radius-full); outline: none; font-family: var(--font-main); font-size: 0.95rem; }
        .chat-input-bar input:focus { border-color: var(--secondary); }
        .send-btn { width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--secondary), #0284c7); color: white; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 1rem; transition: var(--transition); }
        .send-btn:hover { transform: scale(1.1); }

        .edit-area { display: none; flex-direction: column; gap: 0.4rem; }
        .edit-area textarea { width: 100%; border: 1px solid var(--secondary); border-radius: 8px; padding: 0.5rem; font-family: var(--font-main); font-size: 0.9rem; resize: none; outline: none; }
        .edit-btns { display: flex; gap: 0.4rem; }
        .edit-save { background: var(--secondary); color: white; border: none; border-radius: 6px; padding: 0.3rem 0.8rem; font-size: 0.8rem; cursor: pointer; }
        .edit-cancel { background: transparent; border: 1px solid var(--gray-light); border-radius: 6px; padding: 0.3rem 0.8rem; font-size: 0.8rem; cursor: pointer; color: var(--gray); }

        .no-conv { flex: 1; display: flex; flex-direction: column; align-items: center; justify-content: center; color: var(--gray); background: #f8fafc; gap: 1rem; }
        .badge { padding: 0.2rem 0.65rem; border-radius: var(--radius-full); font-size: 0.8rem; font-weight: 500; display: inline-block; }
        .badge.secondary { background: rgba(14,165,233,0.1); color: var(--secondary); }
    </style>
</head>
<body>
<?php
session_start();
$current_role = 'expert';
$current_nom  = 'Alice Martin';
$current_id   = 2; // demo expert id

require_once '../../Controller/MessageController.php';
$ctrl = new MessageController();

$flash = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    switch ($_POST['action']) {
        case 'send':
            $res = $ctrl->sendMessage((int)$_POST['id_conversation'], $current_role, $current_nom, $_POST['contenu'] ?? '');
            $flash = $res['success'] ? '' : $res['error'];
            break;
        case 'edit':
            $ctrl->editMessage((int)$_POST['id'], $_POST['contenu'] ?? '', $current_role, $current_nom);
            break;
        case 'delete':
            $ctrl->deleteMessage((int)$_POST['id'], $current_role, $current_nom);
            break;
    }
}

$conversations = $ctrl->getConversations($current_id, $current_role);
$active_conv_id = isset($_GET['conv']) ? (int)$_GET['conv'] : ($conversations[0]['id'] ?? null);
$active_conv = $active_conv_id ? $ctrl->getConversation($active_conv_id) : null;
$messages = $active_conv_id ? $ctrl->getMessages($active_conv_id) : [];

function initials($name) {
    $parts = explode(' ', trim($name));
    return count($parts) >= 2 ? strtoupper($parts[0][0] . $parts[1][0]) : strtoupper(substr($name, 0, 2));
}
function timeAgo($datetime) {
    if (!$datetime) return '';
    $diff = time() - strtotime($datetime);
    if ($diff < 60) return 'à l\'instant';
    if ($diff < 3600) return round($diff/60) . ' min';
    if ($diff < 86400) return round($diff/3600) . 'h';
    return date('d/m', strtotime($datetime));
}
?>

<div class="dashboard-container">
    <aside class="sidebar">
        <div class="sidebar-header"><a href="index.php" class="logo" style="text-decoration:none;"><i class="fa-solid fa-chart-pie text-secondary"></i> Digit Advisory</a></div>
        <div class="sidebar-menu">
            <a href="front-expert-dashboard.php" class="menu-item"><i class="fa-solid fa-house"></i> Vue d'ensemble</a>
            <a href="front-expert-profil.php" class="menu-item"><i class="fa-solid fa-user"></i> Mon Profil Expert</a>
            <a href="front-expert-portfolio.php" class="menu-item"><i class="fa-solid fa-folder-open"></i> Portfolio & CV</a>
            <a href="front-expert-offres.php" class="menu-item"><i class="fa-solid fa-briefcase"></i> Explorer les Offres</a>
            <a href="front-expert-candidatures.php" class="menu-item"><i class="fa-solid fa-file-contract"></i> Mes Candidatures</a>
            <a href="front-expert-messagerie.php" class="menu-item active"><i class="fa-solid fa-comments"></i> Messagerie</a>
        </div>
        <div class="user-profile-widget">
            <div class="user-avatar">AL</div>
            <div><h4 style="font-size:0.95rem;margin-bottom:0.2rem;">Alice Martin</h4><span style="font-size:0.8rem;color:var(--gray);">Consultant Senior</span></div>
            <a href="login.php" style="margin-left:auto;color:var(--danger);"><i class="fa-solid fa-arrow-right-from-bracket"></i></a>
        </div>
    </aside>

    <main class="main-content">
        <div class="top-navbar">
            <h2 style="margin:0;font-size:1.5rem;">Messagerie</h2>
            <div style="display:flex;gap:0.5rem;align-items:center;">
                <span style="font-size:0.85rem;color:var(--gray);"><?= count($conversations) ?> conversation(s)</span>
            </div>
        </div>

        <?php if ($flash): ?>
        <div style="background:rgba(239,68,68,0.1);color:var(--danger);padding:0.75rem 1rem;border-radius:var(--radius);margin-bottom:1rem;border:1px solid rgba(239,68,68,0.2);">
            <i class="fa-solid fa-circle-exclamation"></i> <?= htmlspecialchars($flash) ?>
        </div>
        <?php endif; ?>

        <div class="chat-wrapper fade-in-up">
            <!-- Conversations list -->
            <div class="conv-sidebar">
                <div class="conv-sidebar-header">
                    <h3><i class="fa-solid fa-comments" style="color:var(--secondary);margin-right:6px;"></i> Conversations</h3>
                    <span style="font-size:0.8rem;color:var(--gray);"><?= count($conversations) ?></span>
                </div>
                <div class="conv-list">
                    <?php if (empty($conversations)): ?>
                    <div style="padding:2rem;text-align:center;color:var(--gray);font-size:0.9rem;">
                        <i class="fa-regular fa-comment-dots" style="font-size:2rem;margin-bottom:0.5rem;display:block;"></i>
                        Aucune conversation
                    </div>
                    <?php else: foreach ($conversations as $conv):
                        $isActive = $conv['id'] == $active_conv_id;
                        $otherName = 'TechCorp SAS'; // demo
                    ?>
                    <a href="front-expert-messagerie.php?conv=<?= $conv['id'] ?>" class="conv-item <?= $isActive ? 'active' : '' ?>">
                        <div class="conv-avatar" style="background:<?= $isActive ? 'var(--secondary)' : 'var(--primary)' ?>;">
                            <?= initials($otherName) ?>
                        </div>
                        <div class="conv-info">
                            <h4><?= htmlspecialchars($otherName) ?></h4>
                            <p><?= htmlspecialchars(substr($conv['dernier_message'] ?? 'Aucun message', 0, 38)) ?><?= strlen($conv['dernier_message'] ?? '') > 38 ? '…' : '' ?></p>
                            <p style="font-size:0.73rem;margin-top:2px;color:var(--secondary);"><i class="fa-solid fa-briefcase" style="font-size:0.7rem;"></i> <?= htmlspecialchars($conv['titre_offre']) ?></p>
                        </div>
                        <div class="conv-time"><?= timeAgo($conv['dernier_at']) ?></div>
                    </a>
                    <?php endforeach; endif; ?>
                </div>
            </div>

            <!-- Chat area -->
            <?php if ($active_conv && $active_conv_id): ?>
            <div class="chat-main">
                <div class="chat-header">
                    <div class="chat-header-info">
                        <div style="width:36px;height:36px;border-radius:50%;background:var(--primary);color:white;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:0.8rem;">TC</div>
                        <div>
                            <h4 style="margin:0;font-size:0.95rem;">TechCorp SAS</h4>
                            <span style="font-size:0.75rem;color:var(--success);"><span class="online-dot"></span>En ligne</span>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <span class="badge secondary"><i class="fa-solid fa-briefcase"></i> <?= htmlspecialchars($active_conv['titre_offre']) ?></span>
                        <span style="font-size:0.8rem;color:var(--gray);"><?= count($messages) ?> messages</span>
                    </div>
                </div>

                <div class="chat-messages" id="messagesContainer">
                    <?php if (empty($messages)): ?>
                    <div class="chat-empty">
                        <i class="fa-regular fa-paper-plane" style="font-size:2.5rem;color:var(--gray-light);"></i>
                        <p>Aucun message. Démarrez la conversation !</p>
                    </div>
                    <?php else: foreach ($messages as $msg):
                        $isSent = ($msg['expediteur_role'] === $current_role);
                        $bgAvatar = $isSent ? 'var(--secondary)' : 'var(--primary)';
                    ?>
                    <div class="msg-row <?= $isSent ? 'sent' : 'received' ?>" id="row-<?= $msg['id'] ?>">
                        <?php if (!$isSent): ?>
                        <div class="msg-avatar-sm" style="background:<?= $bgAvatar ?>;"><?= initials($msg['expediteur_nom']) ?></div>
                        <?php endif; ?>

                        <div class="msg-bubble-wrapper">
                            <div class="msg-bubble <?= $isSent ? 'sent' : 'received' ?>" id="bubble-<?= $msg['id'] ?>">
                                <?= nl2br(htmlspecialchars($msg['contenu'])) ?>
                            </div>
                            <div class="edit-area" id="edit-<?= $msg['id'] ?>">
                                <textarea rows="2" id="edit-text-<?= $msg['id'] ?>"><?= htmlspecialchars($msg['contenu']) ?></textarea>
                                <div class="edit-btns">
                                    <button class="edit-save" onclick="saveEdit(<?= $msg['id'] ?>)">Sauvegarder</button>
                                    <button class="edit-cancel" onclick="cancelEdit(<?= $msg['id'] ?>)">Annuler</button>
                                </div>
                            </div>
                            <div class="msg-meta">
                                <span><?= date('H:i', strtotime($msg['created_at'])) ?></span>
                                <?php if ($msg['is_edited']): ?><span class="edited-tag">(modifié)</span><?php endif; ?>
                                <?php if ($isSent): ?>
                                <div class="msg-actions">
                                    <button class="msg-action-btn" onclick="startEdit(<?= $msg['id'] ?>, `<?= addslashes(htmlspecialchars($msg['contenu'])) ?>`)">
                                        <i class="fa-solid fa-pen"></i>
                                    </button>
                                    <button class="msg-action-btn del" onclick="deleteMsg(<?= $msg['id'] ?>)">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ($isSent): ?>
                        <div class="msg-avatar-sm" style="background:<?= $bgAvatar ?>;"><?= initials($msg['expediteur_nom']) ?></div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; endif; ?>
                </div>

                <div class="chat-input-area">
                    <div class="chat-input-bar">
                        <input type="text" id="messageInput" placeholder="Écrire un message…"
                               onkeydown="if(event.key==='Enter')sendMessage()">
                        <button class="send-btn" onclick="sendMessage()">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </div>
                </div>
            </div>

            <?php else: ?>
            <div class="no-conv">
                <i class="fa-regular fa-comments" style="font-size:3rem;color:var(--gray-light);"></i>
                <p style="font-size:0.95rem;">Sélectionnez une conversation pour commencer</p>
            </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
const CONV_ID = <?= $active_conv_id ?? 'null' ?>;
const MY_ROLE = '<?= $current_role ?>';
const MY_NOM  = '<?= addslashes($current_nom) ?>';
const API_URL = '../../Controller/MessageController.php';

function scrollBottom() {
    const c = document.getElementById('messagesContainer');
    if (c) c.scrollTop = c.scrollHeight;
}
scrollBottom();

function sendMessage() {
    const input = document.getElementById('messageInput');
    const text = input.value.trim();
    if (!text || !CONV_ID) return;

    const fd = new FormData();
    fd.append('action', 'send');
    fd.append('id_conversation', CONV_ID);
    fd.append('role', MY_ROLE);
    fd.append('nom', MY_NOM);
    fd.append('contenu', text);

    fetch(API_URL, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                input.value = '';
                appendMessage(data.id, text, new Date().toLocaleTimeString('fr-FR', {hour:'2-digit',minute:'2-digit'}));
                scrollBottom();
            } else {
                alert(data.error || 'Erreur.');
            }
        });
}

function appendMessage(id, text, time) {
    const container = document.getElementById('messagesContainer');
    const empty = container.querySelector('.chat-empty');
    if (empty) empty.remove();

    const initials = '<?= initials($current_nom) ?>';
    const html = `
    <div class="msg-row sent" id="row-${id}">
        <div class="msg-bubble-wrapper">
            <div class="msg-bubble sent" id="bubble-${id}">${escHtml(text)}</div>
            <div class="edit-area" id="edit-${id}">
                <textarea rows="2" id="edit-text-${id}">${escHtml(text)}</textarea>
                <div class="edit-btns">
                    <button class="edit-save" onclick="saveEdit(${id})">Sauvegarder</button>
                    <button class="edit-cancel" onclick="cancelEdit(${id})">Annuler</button>
                </div>
            </div>
            <div class="msg-meta">
                <span>${time}</span>
                <div class="msg-actions">
                    <button class="msg-action-btn" onclick="startEdit(${id}, '${escJs(text)}')"><i class="fa-solid fa-pen"></i></button>
                    <button class="msg-action-btn del" onclick="deleteMsg(${id})"><i class="fa-solid fa-trash"></i></button>
                </div>
            </div>
        </div>
        <div class="msg-avatar-sm" style="background:var(--secondary);">${initials}</div>
    </div>`;
    container.insertAdjacentHTML('beforeend', html);
}

function startEdit(id, text) {
    document.getElementById('bubble-' + id).style.display = 'none';
    document.getElementById('edit-' + id).style.display = 'flex';
    const ta = document.getElementById('edit-text-' + id);
    ta.value = text;
    ta.focus();
}

function cancelEdit(id) {
    document.getElementById('bubble-' + id).style.display = '';
    document.getElementById('edit-' + id).style.display = 'none';
}

function saveEdit(id) {
    const newText = document.getElementById('edit-text-' + id).value.trim();
    if (!newText) return alert('Le message ne peut pas être vide.');

    const fd = new FormData();
    fd.append('action', 'edit');
    fd.append('id', id);
    fd.append('contenu', newText);
    fd.append('role', MY_ROLE);
    fd.append('nom', MY_NOM);

    fetch(API_URL, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                document.getElementById('bubble-' + id).innerHTML = escHtml(newText);
                const meta = document.getElementById('bubble-' + id).closest('.msg-bubble-wrapper').querySelector('.msg-meta');
                if (!meta.querySelector('.edited-tag')) {
                    meta.insertAdjacentHTML('afterbegin', '<span class="edited-tag">(modifié)</span>');
                }
                cancelEdit(id);
            } else {
                alert(data.error || 'Erreur modification.');
            }
        });
}

function deleteMsg(id) {
    if (!confirm('Supprimer ce message ?')) return;

    const fd = new FormData();
    fd.append('action', 'delete');
    fd.append('id', id);
    fd.append('role', MY_ROLE);
    fd.append('nom', MY_NOM);

    fetch(API_URL, { method: 'POST', body: fd })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const row = document.getElementById('row-' + id);
                row.style.transition = 'opacity 0.3s';
                row.style.opacity = '0';
                setTimeout(() => row.remove(), 300);
            } else {
                alert(data.error || 'Erreur suppression.');
            }
        });
}

function escHtml(t) {
    return t.replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/\n/g,'<br>');
}
function escJs(t) {
    return t.replace(/\\/g,'\\\\').replace(/'/g,"\\'").replace(/\n/g,'\\n');
}
</script>
</body>
</html>