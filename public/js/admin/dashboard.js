// ==========================
// UTILITY FUNCTION
// ==========================
function togglePopup(popup, show) {
  if (!popup) return;
  popup.style.display = show ? 'flex' : 'none';
}

// ==========================
// POPUP ELEMENTS
// ==========================
const popups = {
  assign: {
    openBtn: document.getElementById('assignBtn'),
    popup: document.getElementById('assignPopup'),
    closeBtn: document.getElementById('closePopup'),
    cancelBtn: document.getElementById('cancelAssign')
  },
  alert: {
    openBtn: document.getElementById('alertBtn'),
    popup: document.getElementById('alertPopup'),
    closeBtn: document.getElementById('closeAlertPopup'),
    cancelBtn: document.getElementById('cancelAlert')
  },
  activity: {
    openBtn: document.getElementById('viewActivityBtn'),
    popup: document.getElementById('activityPopup'),
    closeBtn: document.getElementById('closeActivityPopup'),
    cancelBtn: document.getElementById('closeActivityFooter')
  },
  messages: {
    openBtn: document.getElementById('viewMessagesBtn'),
    popup: document.getElementById('messagesPopup'),
    closeBtn: document.getElementById('closeMessagesPopup'),
    cancelBtn: document.getElementById('closeMessagesFooter')
  },
  pending: {
    openBtn: document.querySelector('.pending .view-button'),
    popup: document.getElementById('pendingPopup'),
    closeBtn: document.getElementById('closePendingPopup'),
    cancelBtn: document.getElementById('closePendingFooter')
  }
};

// ==========================
// ATTACH OPEN/CLOSE EVENTS
// ==========================
Object.values(popups).forEach(({ openBtn, popup, closeBtn, cancelBtn }) => {
  openBtn?.addEventListener('click', () => togglePopup(popup, true));
  closeBtn?.addEventListener('click', () => togglePopup(popup, false));
  cancelBtn?.addEventListener('click', () => togglePopup(popup, false));
});

// ==========================
// ASSIGN OFFICER SUBMIT
// ==========================
const submitAssign = document.getElementById('submitAssign');
submitAssign?.addEventListener('click', () => {
  const officer = document.getElementById('officer')?.value;
  const client = document.getElementById('client')?.value;
  const site = document.getElementById('site')?.value;
  const date = document.getElementById('date')?.value;
  const time = document.getElementById('time')?.value;
  const description = document.getElementById('description')?.value;

  if (!officer || !client || !site || !date || !time) {
    alert('Please fill in all required fields.');
    return;
  }

  console.log({ officer, client, site, date, time, description });
  alert('Officer assigned successfully!');
  togglePopup(popups.assign.popup, false);

  ['officer','client','site','date','time','description'].forEach(id => {
    const el = document.getElementById(id);
    if(el) el.value = '';
  });
});

// ==========================
// SEND ALERT SUBMIT
// ==========================
const sendAlert = document.getElementById('sendAlert');
sendAlert?.addEventListener('click', () => {
  const client = document.getElementById('alertClient')?.value;
  const site = document.getElementById('alertSite')?.value;
  const alertMessage = document.getElementById('alertMessage')?.value;
  const roles = Array.from(document.querySelectorAll('.roles-list input:checked'))
                     .map(cb => cb.parentNode.textContent.trim());

  if(!alertMessage) {
    alert('Please enter an alert message.');
    return;
  }

  console.log({client, site, roles, alertMessage});
  alert('Alert sent successfully!');
  togglePopup(popups.alert.popup, false);

  const alertMsgEl = document.getElementById('alertMessage');
  if(alertMsgEl) alertMsgEl.value = '';
});

// ==========================
// CLICK OUTSIDE ANY POPUP
// ==========================
window.addEventListener('click', (e) => {
  Object.values(popups).forEach(({ popup }) => {
    if (popup && e.target === popup) togglePopup(popup, false);
  });
});