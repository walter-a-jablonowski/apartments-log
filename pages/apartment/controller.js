document.addEventListener('DOMContentLoaded', function() {
  // Form validation
  const apartmentForm = document.getElementById('apartmentForm');
  if( apartmentForm ) {
    apartmentForm.addEventListener('submit', function(event) {
      event.preventDefault();
      
      if( !apartmentForm.checkValidity() ) {
        event.stopPropagation();
        apartmentForm.classList.add('was-validated');
        return;
      }
      
      saveApartment();
    });
  }
  
  // Delete button
  const deleteBtn = document.getElementById('delete-entry-btn');
  if( deleteBtn ) {
    deleteBtn.addEventListener('click', function() {
      const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
      deleteModal.show();
    });
  }
  
  // Confirm delete
  const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
  if( confirmDeleteBtn ) {
    confirmDeleteBtn.addEventListener('click', function() {
      deleteApartment();
    });
  }
  
  // Add status button
  const addStatusBtn = document.getElementById('add-status-btn');
  if( addStatusBtn ) {
    addStatusBtn.addEventListener('click', function() {
      document.getElementById('statusModalTitle').textContent = 'Add Status Update';
      document.getElementById('status-index').value = '';
      document.getElementById('status-date').value = getTodayDate();
      document.getElementById('status-text').value = '';
      
      const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
      statusModal.show();
    });
  }
  
  // Edit status buttons
  const editStatusBtns = document.querySelectorAll('.edit-status-btn');
  editStatusBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      const listItem = this.closest('.list-group-item');
      const index = listItem.dataset.index;
      const statusText = listItem.textContent.trim();
      
      // Extract date and text
      let date = '';
      let text = statusText;
      
      const colonIndex = statusText.indexOf(':');
      if( colonIndex > 0 ) {
        date = statusText.substring(0, colonIndex).trim();
        text = statusText.substring(colonIndex + 1).trim();
      }
      
      document.getElementById('statusModalTitle').textContent = 'Edit Status Update';
      document.getElementById('status-index').value = index;
      document.getElementById('status-date').value = date;
      document.getElementById('status-text').value = text;
      
      const statusModal = new bootstrap.Modal(document.getElementById('statusModal'));
      statusModal.show();
    });
  });
  
  // Delete status buttons
  const deleteStatusBtns = document.querySelectorAll('.delete-status-btn');
  deleteStatusBtns.forEach(function(btn) {
    btn.addEventListener('click', function() {
      if( confirm('Are you sure you want to delete this status update?') ) {
        const listItem = this.closest('.list-group-item');
        const index = listItem.dataset.index;
        deleteStatus(index);
      }
    });
  });
  
  // Save status button
  const saveStatusBtn = document.getElementById('save-status-btn');
  if( saveStatusBtn ) {
    saveStatusBtn.addEventListener('click', function() {
      const statusForm = document.getElementById('statusForm');
      
      if( !statusForm.checkValidity() ) {
        statusForm.classList.add('was-validated');
        return;
      }
      
      const index = document.getElementById('status-index').value;
      const date = document.getElementById('status-date').value;
      const text = document.getElementById('status-text').value;
      
      if( index === '' ) {
        addStatus(date, text);
      } else {
        updateStatus(index, date, text);
      }
      
      bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
    });
  }
  
  // Camera functionality
  initCamera();
});

// Save apartment entry
async function saveApartment() {
  const entryId = document.getElementById('entry-id').value;
  const isNew = entryId === '';
  
  // Get form values
  const entry = {
    date: document.getElementById('entry-date').value,
    title: document.getElementById('entry-title').value,
    details: document.getElementById('entry-details').value,
    status: document.getElementById('entry-status').value,
    result: document.getElementById('entry-result').value,
    url: document.getElementById('entry-url').value,
    type: 'apartment'
  };
  
  // Add captured photos if any
  const capturedPhotos = document.querySelectorAll('#captured-photos img');
  if( capturedPhotos.length > 0 ) {
    const photoData = [];
    capturedPhotos.forEach(function(img) {
      photoData.push(img.src);
    });
    entry.photos = photoData;
  }
  
  try {
    const response = await fetchApi('ajax.php', {
      action: isNew ? 'create' : 'update',
      page: 'apartment',
      entry: entry,
      id: entryId
    });
    
    if( response.success ) {
      window.location.href = 'index.php?page=apartment&action=view&id=' + response.data.id;
    } else {
      alert('Error: ' + response.message);
    }
  } catch( error ) {
    console.error('Error saving apartment:', error);
  }
}

// Delete apartment entry
async function deleteApartment() {
  const entryId = document.getElementById('entry-id').value;
  
  try {
    const response = await fetchApi('ajax.php', {
      action: 'delete',
      page: 'apartment',
      id: entryId
    });
    
    if( response.success ) {
      window.location.href = 'index.php';
    } else {
      alert('Error: ' + response.message);
    }
  } catch( error ) {
    console.error('Error deleting apartment:', error);
  }
}

// Add status update
async function addStatus(date, text) {
  const entryId = document.getElementById('entry-id').value;
  
  try {
    const response = await fetchApi('ajax.php', {
      action: 'add_status',
      page: 'apartment',
      id: entryId,
      status: {
        date: date,
        text: text
      }
    });
    
    if( response.success ) {
      window.location.reload();
    } else {
      alert('Error: ' + response.message);
    }
  } catch( error ) {
    console.error('Error adding status:', error);
  }
}

// Update status
async function updateStatus(index, date, text) {
  const entryId = document.getElementById('entry-id').value;
  
  try {
    const response = await fetchApi('ajax.php', {
      action: 'update_status',
      page: 'apartment',
      id: entryId,
      index: index,
      status: {
        date: date,
        text: text
      }
    });
    
    if( response.success ) {
      window.location.reload();
    } else {
      alert('Error: ' + response.message);
    }
  } catch( error ) {
    console.error('Error updating status:', error);
  }
}

// Delete status
async function deleteStatus(index) {
  const entryId = document.getElementById('entry-id').value;
  
  try {
    const response = await fetchApi('ajax.php', {
      action: 'delete_status',
      page: 'apartment',
      id: entryId,
      index: index
    });
    
    if( response.success ) {
      window.location.reload();
    } else {
      alert('Error: ' + response.message);
    }
  } catch( error ) {
    console.error('Error deleting status:', error);
  }
}

// Camera functionality
function initCamera() {
  const startCameraBtn = document.getElementById('start-camera');
  const stopCameraBtn = document.getElementById('stop-camera');
  const capturePhotoBtn = document.getElementById('capture-photo');
  const cameraPreview = document.getElementById('camera-preview');
  const photoCanvas = document.getElementById('photo-canvas');
  const capturedPhotos = document.getElementById('captured-photos');
  
  let stream = null;
  
  if( !startCameraBtn || !stopCameraBtn || !capturePhotoBtn || !cameraPreview || !photoCanvas || !capturedPhotos ) {
    return;
  }
  
  // Start camera
  startCameraBtn.addEventListener('click', async function() {
    try {
      // Use the most reliable approach for camera access
      stream = await navigator.mediaDevices.getUserMedia({
        video: {
          facingMode: { ideal: 'environment' }, // Prefer back camera
          width: { ideal: 1280 },
          height: { ideal: 720 }
        },
        audio: false
      });
      
      cameraPreview.srcObject = stream;
      cameraPreview.style.display = 'block';
      
      startCameraBtn.disabled = true;
      capturePhotoBtn.disabled = false;
      stopCameraBtn.disabled = false;
    } catch( error ) {
      console.error('Error accessing camera:', error);
      alert('Error accessing camera: ' + error.message);
    }
  });
  
  // Stop camera
  stopCameraBtn.addEventListener('click', function() {
    if( stream ) {
      stream.getTracks().forEach(track => track.stop());
      cameraPreview.style.display = 'none';
      
      startCameraBtn.disabled = false;
      capturePhotoBtn.disabled = true;
      stopCameraBtn.disabled = true;
    }
  });
  
  // Capture photo
  capturePhotoBtn.addEventListener('click', function() {
    if( !stream ) return;
    
    // Set canvas dimensions to match video
    photoCanvas.width = cameraPreview.videoWidth;
    photoCanvas.height = cameraPreview.videoHeight;
    
    // Draw video frame to canvas
    const context = photoCanvas.getContext('2d');
    context.drawImage(cameraPreview, 0, 0, photoCanvas.width, photoCanvas.height);
    
    // Convert to data URL
    const dataUrl = photoCanvas.toDataURL('image/jpeg');
    
    // Create thumbnail
    const img = document.createElement('img');
    img.src = dataUrl;
    img.className = 'image-thumbnail';
    img.onclick = function() {
      window.open(dataUrl, '_blank');
    };
    
    // Add delete button overlay
    const container = document.createElement('div');
    container.className = 'position-relative d-inline-block me-2 mb-2';
    
    const deleteBtn = document.createElement('button');
    deleteBtn.className = 'btn btn-sm btn-danger position-absolute top-0 end-0';
    deleteBtn.innerHTML = '&times;';
    deleteBtn.onclick = function(e) {
      e.stopPropagation();
      container.remove();
    };
    
    container.appendChild(img);
    container.appendChild(deleteBtn);
    capturedPhotos.appendChild(container);
  });
}
