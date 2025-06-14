document.addEventListener('DOMContentLoaded', function() {
  // Form validation
  const commonForm = document.getElementById('commonForm');
  if( commonForm ) {
    commonForm.addEventListener('submit', function(event) {
      event.preventDefault();
      
      if( !commonForm.checkValidity() ) {
        event.stopPropagation();
        commonForm.classList.add('was-validated');
        return;
      }
      
      saveCommonActivity();
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
      deleteCommonActivity();
    });
  }
});

// Save common activity entry
async function saveCommonActivity() {
  const entryId = document.getElementById('entry-id').value;
  const isNew = entryId === '';
  
  // Get form values
  const entry = {
    date: document.getElementById('entry-date').value,
    title: document.getElementById('entry-title').value,
    text: document.getElementById('entry-text').value,
    type: 'common'
  };
  
  try {
    const response = await fetchApi('ajax.php', {
      action: isNew ? 'create' : 'update',
      page: 'common',
      entry: entry,
      id: entryId
    });
    
    if( response.success ) {
      window.location.href = 'index.php?page=common&action=view&id=' + response.data.id;
    } else {
      alert('Error: ' + response.message);
    }
  } catch( error ) {
    console.error('Error saving common activity:', error);
  }
}

// Delete common activity entry
async function deleteCommonActivity() {
  const entryId = document.getElementById('entry-id').value;
  
  try {
    const response = await fetchApi('ajax.php', {
      action: 'delete',
      page: 'common',
      id: entryId
    });
    
    if( response.success ) {
      window.location.href = 'index.php';
    } else {
      alert('Error: ' + response.message);
    }
  } catch( error ) {
    console.error('Error deleting common activity:', error);
  }
}
