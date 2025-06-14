<?php if( $action === 'new' || $action === 'edit' ) : ?>
  <div class="row">
    <div class="col-12">
      <h2><?= $action === 'new' ? 'New Apartment' : 'Edit Apartment' ?></h2>
      
      <form id="apartmentForm" class="needs-validation" novalidate>
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
          <label for="entry-details" class="form-label">Details</label>
          <textarea class="form-control" id="entry-details" rows="4"><?= isset($entry['details']) ? htmlspecialchars($entry['details']) : '' ?></textarea>
        </div>
        
        <div class="mb-3">
          <label for="entry-status" class="form-label">Status</label>
          <select class="form-select" id="entry-status" required>
            <option value="new" <?= (isset($entry['status']) && $entry['status'] === 'new') ? 'selected' : '' ?>>New</option>
            <option value="current" <?= (isset($entry['status']) && $entry['status'] === 'current') ? 'selected' : '' ?>>Current</option>
            <option value="maybe" <?= (isset($entry['status']) && $entry['status'] === 'maybe') ? 'selected' : '' ?>>Maybe</option>
            <option value="done" <?= (isset($entry['status']) && $entry['status'] === 'done') ? 'selected' : '' ?>>Done</option>
          </select>
          <div class="invalid-feedback">Please select a status.</div>
        </div>
        
        <div class="mb-3">
          <label for="entry-result" class="form-label">Result</label>
          <input type="text" class="form-control" id="entry-result" value="<?= isset($entry['result']) ? htmlspecialchars($entry['result']) : '' ?>">
        </div>
        
        <div class="mb-3">
          <label for="entry-url" class="form-label">URL</label>
          <input type="url" class="form-control" id="entry-url" value="<?= isset($entry['url']) ? htmlspecialchars($entry['url']) : '' ?>">
          <div class="invalid-feedback">Please enter a valid URL.</div>
        </div>
        
        <?php if( isset($entry['files_id']) ) : ?>
          <div class="mb-3">
            <label class="form-label">Files ID</label>
            <input type="text" class="form-control" value="<?= $entry['files_id'] ?>" readonly>
          </div>
          
          <?php if( !empty($images) ) : ?>
            <div class="mb-3">
              <label class="form-label">Images</label>
              <div class="image-gallery">
                <?php foreach( $images as $image ) : ?>
                  <img src="<?= $image['path'] ?>" class="image-thumbnail" 
                       onclick="window.open('<?= $image['path'] ?>', '_blank')">
                <?php endforeach; ?>
              </div>
            </div>
          <?php endif; ?>
        <?php endif; ?>
        
        <div class="mb-3">
          <label class="form-label">Camera</label>
          <div class="card">
            <div class="card-body">
              <div class="mb-2">
                <button type="button" id="start-camera" class="btn btn-primary">
                  <i class="bi bi-camera"></i> Start Camera
                </button>
                <button type="button" id="capture-photo" class="btn btn-success" disabled>
                  <i class="bi bi-camera"></i> Capture Photo
                </button>
                <button type="button" id="stop-camera" class="btn btn-danger" disabled>
                  <i class="bi bi-x-circle"></i> Stop Camera
                </button>
              </div>
              <video id="camera-preview" autoplay playsinline style="display: none;"></video>
              <canvas id="photo-canvas" style="display: none;"></canvas>
              <div id="captured-photos" class="image-gallery mt-2"></div>
            </div>
          </div>
        </div>
        
        <?php if( $action === 'edit' && isset($entry['status_list']) && is_array($entry['status_list']) ) : ?>
          <div class="mb-3">
            <label class="form-label">Status History</label>
            <div class="list-group" id="status-list">
              <?php foreach( $entry['status_list'] as $index => $status ) : ?>
                <div class="list-group-item" data-index="<?= $index ?>">
                  <div class="d-flex justify-content-between align-items-center">
                    <div>
                      <strong><?= !empty($status['date']) ? $status['date'] : 'No date' ?></strong>: 
                      <?= htmlspecialchars($status['text']) ?>
                    </div>
                    <div>
                      <button type="button" class="btn btn-sm btn-outline-secondary edit-status-btn">
                        <i class="bi bi-pencil"></i>
                      </button>
                      <button type="button" class="btn btn-sm btn-outline-danger delete-status-btn">
                        <i class="bi bi-trash"></i>
                      </button>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          </div>
          
          <div class="mb-3">
            <button type="button" class="btn btn-outline-primary" id="add-status-btn">
              <i class="bi bi-plus-circle"></i> Add Status Update
            </button>
          </div>
        <?php endif; ?>
        
        <div class="mb-3">
          <button type="submit" class="btn btn-primary">Save</button>
          <a href="index.php" class="btn btn-secondary">Cancel</a>
          <?php if( $action === 'edit' ) : ?>
            <button type="button" class="btn btn-danger float-end" id="delete-entry-btn">Delete</button>
          <?php endif; ?>
        </div>
      </form>
      
      <!-- Status Update Modal -->
      <div class="modal fade" id="statusModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="statusModalTitle">Add Status Update</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="statusForm">
                <input type="hidden" id="status-index" value="">
                <div class="mb-3">
                  <label for="status-date" class="form-label">Date</label>
                  <input type="date" class="form-control" id="status-date">
                </div>
                <div class="mb-3">
                  <label for="status-text" class="form-label">Status Text</label>
                  <textarea class="form-control" id="status-text" rows="3" required></textarea>
                  <div class="invalid-feedback">Please enter status text.</div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
              <button type="button" class="btn btn-primary" id="save-status-btn">Save</button>
            </div>
          </div>
        </div>
      </div>
      
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
          <a href="index.php?page=apartment&action=edit&id=<?= $entry['id'] ?>" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Edit
          </a>
          <a href="index.php" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
          </a>
        </div>
      </div>
      
      <div class="card mb-3">
        <div class="card-header bg-light">
          <div class="d-flex justify-content-between">
            <span>Date: <?= $entry['date'] ?></span>
            <span class="badge bg-primary"><?= ucfirst($entry['status']) ?></span>
          </div>
        </div>
        <div class="card-body">
          <h5 class="card-title">Details</h5>
          <p class="card-text"><?= nl2br(htmlspecialchars($entry['details'])) ?></p>
          
          <?php if( !empty($entry['result']) ) : ?>
            <h5 class="card-title mt-3">Result</h5>
            <p class="card-text"><?= htmlspecialchars($entry['result']) ?></p>
          <?php endif; ?>
          
          <?php if( !empty($entry['url']) ) : ?>
            <h5 class="card-title mt-3">URL</h5>
            <p class="card-text">
              <a href="<?= htmlspecialchars($entry['url']) ?>" target="_blank">
                <?= htmlspecialchars($entry['url']) ?>
              </a>
            </p>
          <?php endif; ?>
          
          <?php if( !empty($entry['files_id']) ) : ?>
            <h5 class="card-title mt-3">Files ID</h5>
            <p class="card-text"><?= $entry['files_id'] ?></p>
          <?php endif; ?>
          
          <?php if( !empty($images) ) : ?>
            <h5 class="card-title mt-3">Images</h5>
            <div class="image-gallery">
              <?php foreach( $images as $image ) : ?>
                <img src="<?= $image['path'] ?>" class="image-thumbnail" 
                     onclick="window.open('<?= $image['path'] ?>', '_blank')">
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
          
          <?php if( isset($entry['status_list']) && !empty($entry['status_list']) ) : ?>
            <h5 class="card-title mt-3">Status History</h5>
            <div class="list-group">
              <?php foreach( $entry['status_list'] as $status ) : ?>
                <div class="list-group-item">
                  <strong><?= !empty($status['date']) ? $status['date'] : 'No date' ?></strong>: 
                  <?= htmlspecialchars($status['text']) ?>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
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
