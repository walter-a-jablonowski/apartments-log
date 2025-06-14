<?php
require_once __DIR__ . '/../../lib/DataManager.php';

$dataManager = new DataManager();
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$entry = null;
$images = [];

if( $action === 'view' || $action === 'edit' ) {
  $entry = $dataManager->getEntryById($id);
  
  if( $entry && isset($entry['files_id']) ) {
    $images = $dataManager->getImagesByFilesId($entry['files_id']);
  }
  
  // Redirect to home if entry not found or not an apartment
  if( !$entry || $entry['type'] !== 'apartment' ) {
    header('Location: index.php');
    exit;
  }
}
