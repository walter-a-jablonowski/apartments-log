<?php if( $action === 'new' || $action === 'edit' ) : ?>
  <div class="row">
    <div class="col-12">
      <h2><?= $action === 'new' ? 'New Common Activity' : 'Edit Common Activity' ?></h2>
      
      <form id="commonForm" class="needs-validation" novalidate>
        <input type="hidden" id="entry-id" value="<?= isset($entry['id']) ? $entry['id'] : '' ?>">
        
        <div class="mb-3">
          <label for="entry-date" class="form-label">Date</label>
          <input type="date" class="form-control" id="entry-date" value="<?= isset($entry['date']) ? $entry['date'] : '' ?>" required>
          <div class="invalid-feedback">Please select a date.</div>
        </div>
        
        <div class="mb-3">
          <label for="entry-title" class="form-label">Title</label>
          <input type="text" class="form-control" id="entry-title" value="<?= isset($entry['title']) ? htmlspecialchars($entry['title']) : '' ?>" required>
          <div class="invalid-feedback">Please enter a title.</div>
        </div>
        
        <div class="mb-3">
          <label for="entry-text" class="form-label">Text</label>
          <textarea class="form-control" id="entry-text" rows="4" required><?= isset($entry['text']) ? htmlspecialchars($entry['text']) : '' ?></textarea>
          <div class="invalid-feedback">Please enter some text.</div>
        </div>
        
        <div class="mb-3">
          <button type="submit" class="btn btn-primary">Save</button>
          <a href="index.php" class="btn btn-secondary">Cancel</a>
          <?php if( $action === 'edit' ) : ?>
            <button type="button" class="btn btn-danger float-end" id="delete-entry-btn">Delete</button>
          <?php endif; ?>
        </div>
      </form>
      
      <!-- Delete Confirmation Modal -->
      <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">Confirm Delete</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <p>Are you sure you want to delete this entry? This action cannot be undone.</p>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-danger" id="confirm-delete-btn">Delete</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<?php elseif( $action === 'view' ) : ?>
  <div class="row">
    <div class="col-12">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h2><?= htmlspecialchars($entry['title']) ?></h2>
        <div>
          <a href="index.php?page=common&action=edit&id=<?= $entry['id'] ?>" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
          </a>
          <a href="index.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
          </a>
        </div>
      </div>
      
      <div class="card mb-3">
        <div class="card-header bg-light">
          <span>Date: <?= $entry['date'] ?></span>
        </div>
        <div class="card-body">
          <p class="card-text"><?= nl2br(htmlspecialchars($entry['text'])) ?></p>
        </div>
      </div>
    </div>
  </div>
<?php else : ?>
  <div class="row">
    <div class="col-12">
      <div class="alert alert-danger">
        <p>Invalid action.</p>
        <a href="index.php" class="btn btn-primary">Back to Home</a>
      </div>
    </div>
  </div>
<?php endif; ?>
