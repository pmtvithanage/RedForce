const conversations=[
  {id:1,sender:'Admin - Red Force',subject:'Deployment update',preview:'Dear Mr. Fernando, kindly note that we are assigning 2 officers tonight to the Kurune...',
   chat:[
    {dir:'in', text:'Dear Mr. Fernando, kindly note that we are assigning 2 officers tonight to the Kurunegala branch as per your request.'},
    {dir:'out', text:'What do you mean?'}
   ]},
  {id:2,sender:'John Silva',subject:'Attendance notice',preview:'Officer Ravindu Fernando was...',
   chat:[{dir:'in', text:'Officer Ravindu Fernando was late today.'}]},
  {id:3,sender:'Nadi Senanayake',subject:'Training docs',preview:'Officer training certificates...',
   chat:[{dir:'in', text:'Officer training certificates have been sent.'}]},
  {id:4,sender:'Nishadi Dissanayake',subject:'Shift schedules',preview:'Updated shift schedules for all...',
   chat:[{dir:'in', text:'Updated shift schedules for all officers are attached.'}]}
];

let currentConversationId=null;

function openChat(){
  document.getElementById('chatBackdrop').hidden=false;
  document.getElementById('chatModal').hidden=false;
  scrollToBottom();
}
function closeChat(){
  document.getElementById('chatBackdrop').hidden=true;
  document.getElementById('chatModal').hidden=true;
}

function renderChat(list){
  const wrap=document.getElementById('chatMessages');
  wrap.innerHTML='';
  list.forEach(m=>{
    const row=document.createElement('div');
    row.className='msg '+(m.dir==='out'?'out':'in');
    const bubble=document.createElement('div');
    bubble.className='bubble';
    bubble.textContent=m.text;
    row.appendChild(bubble);
    wrap.appendChild(row);
  });
}

function scrollToBottom(){
  const wrap=document.getElementById('chatMessages');
  wrap.scrollTop=wrap.scrollHeight;
}

function setActive(id){
  document.querySelectorAll('.message-item').forEach(el=>el.classList.remove('active'));
  const idx=conversations.findIndex(c=>c.id===id);
  const list=document.getElementById('messagesList');
  if(idx>-1 && list.children[idx]) list.children[idx].classList.add('active');
}

document.addEventListener('DOMContentLoaded',()=>{
  const listEl=document.getElementById('messagesList');
  const tpl=document.getElementById('messageItemTpl');
  if(listEl&&tpl){
    conversations.forEach(c=>{
      const node=tpl.content.cloneNode(true);
      const item=node.querySelector('.message-item');
      node.querySelector('.avatar').dataset.initials=(c.sender.split(' ').map(p=>p[0]).join('').slice(0,2)).toUpperCase();
      node.querySelector('.sender').textContent=c.sender;
      node.querySelector('.subject').textContent=c.subject;
      node.querySelector('.preview').textContent=c.preview;
      node.querySelector('.time').textContent='';
      item.addEventListener('click',()=>{
        currentConversationId=c.id;
        document.getElementById('chatSubtitle').textContent=c.sender;
        renderChat(c.chat);
        openChat();
        setActive(c.id);
      });
      listEl.appendChild(node);
    });
  }

  const closeBtn=document.getElementById('closeChatBtn');
  const backdrop=document.getElementById('chatBackdrop');
  const input=document.getElementById('chatInput');
  const send=document.getElementById('sendBtn');
  closeBtn&&closeBtn.addEventListener('click',closeChat);
  backdrop&&backdrop.addEventListener('click',closeChat);

  function sendMessage(){
    const value=input.value.trim();
    if(!value) return;
    const conv=conversations.find(x=>x.id===currentConversationId);
    if(conv){
      conv.chat.push({dir:'out', text:value});
      renderChat(conv.chat);
    }
    input.value='';
    scrollToBottom();
  }
  send&&send.addEventListener('click',sendMessage);
  input&&input.addEventListener('keydown',e=>{ if(e.key==='Enter') sendMessage(); });

  // No auto-open; chat opens only when a message is clicked
});