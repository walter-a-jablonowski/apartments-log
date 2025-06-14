<?php
require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

// Initialize data directory if it doesn't exist
if( !file_exists( __DIR__ . '/data' ) ) {
  mkdir( __DIR__ . '/data', 0755, true );
}

// Initialize files directory if it doesn't exist
if( !file_exists( __DIR__ . '/data/files' ) ) {
  mkdir( __DIR__ . '/data/files', 0755, true );
}

// Initialize data.yml if it doesn't exist
if( !file_exists( __DIR__ . '/data/data.yml' ) ) {
  $initialData = [
    'entries' => []
  ];
  file_put_contents( __DIR__ . '/data/data.yml', Yaml::dump($initialData, 4) );
}

// Initialize counters.json if it doesn't exist
if( !file_exists( __DIR__ . '/data/counters.json' ) ) {
  $counters = [
    'entry_id' => 0,
    'files_id' => 0
  ];
  file_put_contents( __DIR__ . '/data/counters.json', json_encode($counters, JSON_PRETTY_PRINT) );
}

// Get the requested page or default to home
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Validate page to prevent directory traversal
$page = preg_replace('/[^a-zA-Z0-9_]/', '', $page);

// Check if the page exists, otherwise default to home
if( !file_exists( __DIR__ . "/pages/{$page}/controller.php" ) ) {
  $page = 'home';
}

// Include the page controller
include __DIR__ . "/pages/{$page}/controller.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Apartment Log</title>
  
  <!-- Bootstrap 5.3 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  
  <!-- Custom CSS -->
  <style>
    body {
      padding-bottom: 70px; /* For fixed bottom navbar */
    }
    .navbar-brand {
      font-weight: bold;
    }
    .list-group-item {
      border-left: none;
      border-right: none;
    }
    .status-new { background-color: #d1ecf1; }
    .status-current { background-color: #fff3cd; }
    .status-maybe { background-color: #d4edda; }
    .status-done { background-color: #f8d7da; }
    
    /* Mobile optimizations */
    @media (max-width: 767.98px) {
      .container {
        padding-left: 10px;
        padding-right: 10px;
      }
    }
    
    /* Camera preview */
    #camera-preview {
      width: 100%;
      max-height: 300px;
      object-fit: cover;
    }
    
    /* Image gallery */
    .image-gallery {
      display: flex;
      flex-wrap: wrap;
      gap: 10px;
    }
    .image-thumbnail {
      width: 80px;
      height: 80px;
      object-fit: cover;
      cursor: pointer;
    }
  </style>
  
  <?php if( file_exists( __DIR__ . "/pages/{$page}/style.css" ) ) : ?>
  <link rel="stylesheet" href="pages/<?= $page ?>/style.css">
  <?php endif; ?>
</head>
<body>
  <!-- Top navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-primary mb-3">
    <div class="container">
      <a class="navbar-brand" href="index.php">Apartment Log</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link <?= $page == 'home' ? 'active' : '' ?>" href="index.php">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
              New Entry
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="index.php?page=apartment&action=new">New Apartment</a></li>
              <li><a class="dropdown-item" href="index.php?page=common&action=new">New Common Activity</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  
  <!-- Main content -->
  <div class="container">
    <?php include __DIR__ . "/pages/{$page}/view.php"; ?>
  </div>
  
  <!-- Bottom navbar for mobile -->
  <nav class="navbar fixed-bottom navbar-dark bg-primary d-md-none">
    <div class="container-fluid">
      <div class="row w-100 text-center">
        <div class="col">
          <a class="text-white" href="index.php">
            <i class="bi bi-house-fill"></i><br>Home
          </a>
        </div>
        <div class="col">
          <a class="text-white" href="index.php?page=apartment&action=new">
            <i class="bi bi-building"></i><br>Apartment
          </a>
        </div>
        <div class="col">
          <a class="text-white" href="index.php?page=common&action=new">
            <i class="bi bi-journal-plus"></i><br>Activity
          </a>
        </div>
      </div>
    </div>
  </nav>
  
  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
  
  <!-- Bootstrap 5.3 JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  
  <!-- Common JS -->
  <script>
  // Helper function for AJAX requests
  async function fetchApi(url, data = {}) {
    try {
      const response = await fetch(url, {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
      });
      
      if( !response.ok ) {
        throw new Error(`HTTP error! Status: ${response.status}`);
      }
      
      return await response.json();
    } catch( error ) {
      console.error('Fetch error:', error);
      alert('An error occurred: ' + error.message);
      throw error;
    }
  }
  
  // Format date as YYYY-MM-DD
  function formatDate(date) {
    const d = new Date(date);
    const year = d.getFullYear();
    const month = String(d.getMonth() + 1).padStart(2, '0');
    const day = String(d.getDate()).padStart(2, '0');
    return `${year}-${month}-${day}`;
  }
  
  // Get today's date in YYYY-MM-DD format
  function getTodayDate() {
    return formatDate(new Date());
  }
  
  // Initialize date inputs with today's date if empty
  document.addEventListener('DOMContentLoaded', function() {
    const dateInputs = document.querySelectorAll('input[type="date"]:not([value])');
    const today = getTodayDate();
    
    dateInputs.forEach(input => {
      if( !input.value ) {
        input.value = today;
      }
    });
  });
  </script>
  
  <?php if( file_exists( __DIR__ . "/pages/{$page}/controller.js" ) ) : ?>
  <script src="pages/<?= $page ?>/controller.js"></script>
  <?php endif; ?>
</body>
</html>
