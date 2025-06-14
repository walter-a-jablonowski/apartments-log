<?php
require_once __DIR__ . '/../../lib/DataManager.php';

$dataManager = new DataManager();
$action = isset($_GET['action']) ? $_GET['action'] : 'list';
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$entry = null;

if( $action === 'view' || $action === 'edit' ) {
  $entry = $dataManager->getEntryById($id);
  
  // Redirect to home if entry not found or not a common activity
  if( !$entry || $entry['type'] !== 'common' ) {
    header('Location: index.php');
    exit;
  }
}
