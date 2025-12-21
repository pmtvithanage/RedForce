// Minimal JavaScript for Messages - Chat Modal Only
// Conversations loaded from PHP

function openChat(conversationId) {
    document.getElementById('chatBackdrop').hidden = false;
    document.getElementById('chatModal').hidden = false;
    scrollToBottom();
}

function closeChat() {
    document.getElementById('chatBackdrop').hidden = true;
    document.getElementById('chatModal').hidden = true;
}

function scrollToBottom() {
    const wrap = document.getElementById('chatMessages');
    if (wrap) wrap.scrollTop = wrap.scrollHeight;
}

document.addEventListener('DOMContentLoaded', () => {
    const closeBtn = document.getElementById('closeChatBtn');
    const backdrop = document.getElementById('chatBackdrop');
    
    if (closeBtn) closeBtn.addEventListener('click', closeChat);
    if (backdrop) backdrop.addEventListener('click', closeChat);
});
