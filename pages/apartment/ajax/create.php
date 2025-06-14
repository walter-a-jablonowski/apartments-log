<?php
require_once __DIR__ . '/../../../lib/DataManager.php';

$dataManager = new DataManager();

// Default response
$response = [
  'success' => false,
  'message' => 'Failed to create apartment entry',
  'data' => null
];

// Check if entry data is provided
if( isset($requestData['entry']) && is_array($requestData['entry']) ) {
  $entry = $requestData['entry'];
  
  // Validate required fields
  if( empty($entry['title']) || empty($entry['date']) || empty($entry['status']) ) {
    $response['message'] = 'Missing required fields';
  } else {
    // Add required fields
    $entry['id'] = $dataManager->getNextEntryId();
    $entry['type'] = 'apartment';
    
    // Initialize status list
    $entry['status_list'] = [];
    
    // Process photos if any
    if( isset($entry['photos']) && is_array($entry['photos']) && !empty($entry['photos']) ) {
      // Generate files_id
      $filesId = $dataManager->getNextFilesId();
      $entry['files_id'] = $filesId;
      
      // Create directory for files
      $filesDir = __DIR__ . "/../../../data/files/{$filesId}";
      if( !file_exists($filesDir) ) {
        mkdir($filesDir, 0755, true);
      }
      
      // Save photos
      foreach( $entry['photos'] as $index => $photoData ) {
        // Extract base64 image data
        if( strpos($photoData, 'data:image/') === 0 ) {
          $parts = explode(',', $photoData, 2);
          if( count($parts) === 2 ) {
            $photoData = $parts[1];
          }
        }
        
        // Decode and save
        $photoData = base64_decode($photoData);
        if( $photoData !== false ) {
          $filename = "photo_" . date('Ymd_His') . "_{$index}.jpg";
          file_put_contents("{$filesDir}/{$filename}", $photoData);
        }
      }
      
      // Remove photos from entry data
      unset($entry['photos']);
    }
    
    // Save entry
    if( $dataManager->addEntry($entry) ) {
      $response['success'] = true;
      $response['message'] = 'Apartment entry created successfully';
      $response['data'] = ['id' => $entry['id']];
    }
  }
} else {
  $response['message'] = 'No entry data provided';
}
