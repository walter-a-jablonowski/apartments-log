<div class="row">
  <div class="col-12">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h1>Apartment Log</h1>
      <div class="dropdown">
        <button class="btn btn-primary dropdown-toggle" type="button" id="newEntryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
          <i class="bi bi-plus-lg"></i> New Entry
        </button>
        <ul class="dropdown-menu" aria-labelledby="newEntryDropdown">
          <li><a class="dropdown-item" href="index.php?page=apartment&action=new">New Apartment</a></li>
          <li><a class="dropdown-item" href="index.php?page=common&action=new">New Common Activity</a></li>
        </ul>
      </div>
    </div>
    
    <?php if( empty($entries) ) : ?>
      <div class="alert alert-info">
        <p>No entries found. Create your first entry using the "New Entry" button.</p>
      </div>
    <?php else : ?>
      <div class="list-group mb-4">
        <?php foreach( $entries as $entry ) : ?>
          <?php if( $entry['type'] === 'apartment' ) : ?>
            <div class="list-group-item list-group-item-action status-<?= $entry['status'] ?>">
              <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1"><?= htmlspecialchars($entry['title']) ?></h5>
                <small><?= $entry['date'] ?></small>
              </div>
              <p class="mb-1"><?= nl2br(htmlspecialchars($entry['details'])) ?></p>
              <div class="d-flex justify-content-between align-items-center">
                <div>
                  <span class="badge bg-primary"><?= ucfirst($entry['status']) ?></span>
                  <?php if( !empty($entry['files_id']) ) : ?>
                    <span class="badge bg-secondary">Files: <?= $entry['files_id'] ?></span>
                  <?php endif; ?>
                </div>
                <div>
                  <a href="index.php?page=apartment&action=view&id=<?= $entry['id'] ?>" class="btn btn-sm btn-outline-primary">
                    <i class="bi bi-eye"></i> View
                  </a>
                  <a href="index.php?page=apartment&action=edit&id=<?= $entry['id'] ?>" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-pencil"></i> Edit
                  </a>
                </div>
              </div>
            </div>
          <?php elseif( $entry['type'] === 'common' ) : ?>
            <div class="list-group-item list-group-item-action">
              <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1"><?= htmlspecialchars($entry['title']) ?></h5>
                <small><?= $entry['date'] ?></small>
              </div>
              <p class="mb-1"><?= nl2br(htmlspecialchars($entry['text'])) ?></p>
              <div class="d-flex justify-content-end">
                <a href="index.php?page=common&action=view&id=<?= $entry['id'] ?>" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-eye"></i> View
                </a>
                <a href="index.php?page=common&action=edit&id=<?= $entry['id'] ?>" class="btn btn-sm btn-outline-secondary ms-1">
                  <i class="bi bi-pencil"></i> Edit
                </a>
              </div>
            </div>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</div>
