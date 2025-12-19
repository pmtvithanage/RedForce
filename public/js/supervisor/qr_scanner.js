// QR Scanner Implementation for Supervisor Dashboard
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('qrScannerModal');
    const openBtn = document.getElementById('openQRScanner');
    const closeBtn = document.getElementById('closeQRScanner');
    const statusDiv = document.getElementById('qr-status');
    let html5QrcodeScanner = null;
    let isScanning = false;

    // Open QR Scanner Modal
    openBtn.addEventListener('click', function() {
        openQRScanner();
    });

    // Close QR Scanner Modal
    closeBtn.addEventListener('click', function() {
        closeQRScanner();
    });

    // Close on ESC key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeQRScanner();
        }
    });

    // Close on overlay click
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            closeQRScanner();
        }
    });

    // Open Scanner Function
    function openQRScanner() {
        modal.classList.add('active');
        showStatus('Initializing camera...', 'info');
        
        // Initialize html5-qrcode
        html5QrcodeScanner = new Html5Qrcode("qr-reader");
        
        const config = {
            fps: 10,
            qrbox: { width: 250, height: 250 },
            aspectRatio: 1.0
        };

        html5QrcodeScanner.start(
            { facingMode: "environment" },
            config,
            onScanSuccess,
            onScanError
        ).then(() => {
            showStatus('Ready to scan', 'info');
            isScanning = true;
        }).catch(err => {
            console.error('Camera initialization error:', err);
            showStatus('Camera access denied or not available', 'error');
            setTimeout(() => {
                closeQRScanner();
            }, 3000);
        });
    }

    // Close Scanner Function
    function closeQRScanner() {
        if (html5QrcodeScanner && isScanning) {
            html5QrcodeScanner.stop().then(() => {
                html5QrcodeScanner.clear();
                html5QrcodeScanner = null;
                isScanning = false;
                modal.classList.remove('active');
                statusDiv.className = 'qr-status';
                statusDiv.textContent = '';
            }).catch(err => {
                console.error('Error stopping scanner:', err);
                modal.classList.remove('active');
            });
        } else {
            modal.classList.remove('active');
        }
    }

    // On Scan Success
    function onScanSuccess(decodedText, decodedResult) {
        if (!isScanning) return;
        
        console.log('QR Code scanned:', decodedText);
        showStatus('Processing...', 'processing');
        
        // Stop scanner temporarily
        isScanning = false;
        
        // Parse QR code data
        let qrData = parseQRData(decodedText);
        
        if (!qrData) {
            showStatus('Invalid QR code format', 'error');
            setTimeout(() => {
                isScanning = true;
                showStatus('Ready to scan', 'info');
            }, 2000);
            return;
        }

        // Send attendance data to server
        markAttendance(qrData);
    }

    // On Scan Error (ignore continuous errors)
    function onScanError(errorMessage) {
        // Silent errors - these occur continuously during scanning
        // console.warn('QR Scan error:', errorMessage);
    }

    // Parse QR Data (supports JSON and pipe-delimited format)
    function parseQRData(text) {
        try {
            // Try JSON format first
            const jsonData = JSON.parse(text);
            if (jsonData.officer_id && jsonData.name) {
                return {
                    officer_id: jsonData.officer_id,
                    name: jsonData.name,
                    timestamp: jsonData.timestamp || new Date().toISOString()
                };
            }
        } catch (e) {
            // Try pipe-delimited format: officer_id|name|timestamp
            const parts = text.split('|');
            if (parts.length >= 2) {
                return {
                    officer_id: parts[0].trim(),
                    name: parts[1].trim(),
                    timestamp: parts[2] ? parts[2].trim() : new Date().toISOString()
                };
            }
        }
        
        return null;
    }

    // Mark Attendance via AJAX
    function markAttendance(qrData) {
        const url = window.location.origin + '/RedForce/supervisor/markAttendance';
        
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(qrData)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showStatus(`✓ Attendance marked for ${qrData.name}`, 'success');
                
                // Close modal after 2 seconds
                setTimeout(() => {
                    closeQRScanner();
                    // Reload page to show updated attendance
                    window.location.reload();
                }, 2000);
            } else {
                showStatus(data.message || 'Failed to mark attendance', 'error');
                setTimeout(() => {
                    isScanning = true;
                    showStatus('Ready to scan', 'info');
                }, 2500);
            }
        })
        .catch(error => {
            console.error('Attendance marking error:', error);
            showStatus('Network error. Please try again.', 'error');
            setTimeout(() => {
                isScanning = true;
                showStatus('Ready to scan', 'info');
            }, 2500);
        });
    }

    // Show Status Message
    function showStatus(message, type) {
        statusDiv.textContent = message;
        statusDiv.className = `qr-status ${type} show`;
        
        // Add loading spinner for processing
        if (type === 'processing') {
            statusDiv.innerHTML = `<span class="qr-loading"></span> ${message}`;
        }
    }
});
