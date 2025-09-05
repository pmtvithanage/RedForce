// Toast helper
function showToast(message){
  const toast=document.getElementById('toast');
  if(!toast) return;
  toast.textContent=message;
  toast.classList.add('show');
  setTimeout(()=>toast.classList.remove('show'),2500);
}

// Wire actions after DOM ready
document.addEventListener('DOMContentLoaded',()=>{
  // Logout
  const logoutBtn=document.getElementById('logoutBtn');
  if(logoutBtn){
    logoutBtn.addEventListener('click',()=>{
      showToast('You have been logged out.');
    });
  }

  // Avatar edit
  const editAvatar=document.getElementById('editAvatar');
  if(editAvatar){
    editAvatar.addEventListener('click',()=>{
      showToast('Avatar change coming soon.');
    });
  }

     // Inline edits
   document.querySelectorAll('.icon-btn,[data-edit]').forEach(btn=>{
     btn.addEventListener('click',()=>{
       const key=btn.getAttribute('data-edit');
       if(!key) return;
       const map={
         name:{label:'Mobile Rider Name', el:'#nameValue'},
         password:{label:'Password', el:'#passwordValue', type:'password'},
         contact:{label:'Contact Number', el:'#contactValue'},
         email:{label:'Email', el:'#emailValue'}
       };
       const entry=map[key];
       if(!entry) return;
       const valueEl=document.querySelector(entry.el);
       
       if(entry.type === 'password') {
         // Handle password change with confirmation
         const currentPassword = prompt('Enter current password:');
         if(currentPassword === null) return;
         
         // In a real app, you'd verify against stored password
         if(currentPassword !== 'current123') { // Demo password
           showToast('Current password is incorrect');
           return;
         }
         
         const newPassword = prompt('Enter new password (min 8 characters):');
         if(newPassword === null) return;
         
         if(newPassword.length < 8) {
           showToast('Password must be at least 8 characters long');
           return;
         }
         
         const confirmPassword = prompt('Confirm new password:');
         if(confirmPassword === null) return;
         
         if(newPassword !== confirmPassword) {
           showToast('Passwords do not match');
           return;
         }
         
         if(valueEl) { 
           valueEl.textContent = '- ••••••••'; 
         }
         showToast('Password updated successfully');
       } else {
         // Handle regular field updates
         const current = valueEl ? valueEl.textContent.replace(/^\s*-\s*/,'').trim() : '';
         const next = prompt(`Update ${entry.label}:`, current);
         if(next !== null){
           if(valueEl){ valueEl.textContent = `- ${next.trim()}`; }
           showToast(`${entry.label} updated`);
         }
       }
     });
   });
});
