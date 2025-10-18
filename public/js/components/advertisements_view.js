function viewAdvertisement(adId) {
  const modal = document.getElementById("adModal");
  const titleEl = document.getElementById("adModalTitle");
  const bodyEl = document.getElementById("adModalBody");

  if (!modal || !titleEl || !bodyEl) return;

  const adItem = document.querySelector(
    `.advertisement-item[data-ad-id="${adId}"]`
  );
  if (!adItem) return;

  const title =
    adItem.querySelector(".ad-title")?.textContent?.trim() || "Advertisement";
  const fullContent =
    document.getElementById(`full-ad-${adId}`)?.innerHTML ||
    "<p>No content available</p>";

  titleEl.textContent = title;
  bodyEl.innerHTML = fullContent;

  modal.style.display = "block";

  // Resize image after content is rendered
  setTimeout(adjustModalImageHeight, 50);
}

function adjustModalImageHeight() {
  const modal = document.getElementById("adModal");
  if (!modal || modal.style.display !== "block") return;

  const img = modal.querySelector(".ad-full-image");
  if (!img) return;

  const viewportHeight = window.innerHeight;
  const headerHeight =
    modal.querySelector(".ad-modal-header")?.offsetHeight || 60;
  const padding = 60; // top + bottom padding in modal body

  const maxHeight = Math.min(viewportHeight - headerHeight - padding, 500); // cap at 500px
  img.style.maxHeight = maxHeight + "px";
  img.style.width = "100%";
  img.style.height = "auto";
  img.style.objectFit = "contain";
}

function closeModal(modalId) {
  const modal = document.getElementById(modalId);
  if (modal) modal.style.display = "none";
}

// Close on outside click
window.addEventListener("click", (event) => {
  const adModal = document.getElementById("adModal");
  const chatModal = document.getElementById("chatModal");

  if (event.target === adModal) closeModal("adModal");
  if (event.target === chatModal) closeModal("chatModal");
});

// Optional: refresh advertisements
function refreshAdvertisements() {
  location.reload();
}

// Re-adjust image if window is resized while modal is open
window.addEventListener("resize", () => {
  const adModal = document.getElementById("adModal");
  if (adModal && adModal.style.display === "block") {
    adjustModalImageHeight();
  }
});
