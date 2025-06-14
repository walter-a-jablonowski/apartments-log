<?php
require_once __DIR__ . '/../../../lib/DataManager.php';

$dataManager = new DataManager();

// Default response
$response = [
  'success' => false,
  'message' => 'Failed to update apartment entry',
  'data' => null
];

// Check if entry data and ID are provided
if( isset($requestData['entry']) && is_array($requestData['entry']) && isset($requestData['id']) ) {
  $entryId = (int)$requestData['id'];
  $updatedEntry = $requestData['entry'];
  
  // Get existing entry
  $existingEntry = $dataManager->getEntryById($entryId);
  
  if( $existingEntry && $existingEntry['type'] === 'apartment' ) {
    // Validate required fields
    if( empty($updatedEntry['title']) || empty($updatedEntry['date']) || empty($updatedEntry['status']) ) {
      $response['message'] = 'Missing required fields';
    } else {
      // Preserve existing fields that shouldn't be changed
      $updatedEntry['id'] = $entryId;
      $updatedEntry['type'] = 'apartment';
      
      // Preserve status list if it exists
      if( isset($existingEntry['status_list']) ) {
        $updatedEntry['status_list'] = $existingEntry['status_list'];
      } else {
        $updatedEntry['status_list'] = [];
      }
      
      // Preserve files_id if it exists
      if( isset($existingEntry['files_id']) ) {
        $updatedEntry['files_id'] = $existingEntry['files_id'];
      }
      
      // Process photos if any
      if( isset($updatedEntry['photos']) && is_array($updatedEntry['photos']) && !empty($updatedEntry['photos']) ) {
        // Generate files_id if it doesn't exist
        if( !isset($updatedEntry['files_id']) ) {
          $filesId = $dataManager->getNextFilesId();
          $updatedEntry['files_id'] = $filesId;
        } else {
          $filesId = $updatedEntry['files_id'];
        }
        
        // Create directory for files
        $filesDir = __DIR__ . "/../../../data/files/{$filesId}";
        if( !file_exists($filesDir) ) {
          mkdir($filesDir, 0755, true);
        }
        
        // Save photos
        foreach( $updatedEntry['photos'] as $index => $photoData ) {
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
        unset($updatedEntry['photos']);
      }
      
      // Update entry
      if( $dataManager->updateEntry($entryId, $updatedEntry) ) {
        $response['success'] = true;
        $response['message'] = 'Apartment entry updated successfully';
        $response['data'] = ['id' => $entryId];
      }
    }
  } else {
    $response['message'] = 'Apartment entry not found';
  }
} else {
  $response['message'] = 'Missing entry data or ID';
}
