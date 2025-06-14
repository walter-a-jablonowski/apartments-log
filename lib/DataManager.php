<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

class DataManager
{
  private $dataFile;
  private $counterFile;
  private $data;
  private $counters;
  
  public function __construct()
  {
    $this->dataFile = __DIR__ . '/../data/data.yml';
    $this->counterFile = __DIR__ . '/../data/counters.json';
    $this->loadData();
    $this->loadCounters();
  }
  
  private function loadData()
  {
    if( file_exists($this->dataFile) ) {
      $this->data = Yaml::parseFile($this->dataFile);
    } else {
      $this->data = ['entries' => []];
      $this->saveData();
    }
  }
  
  private function loadCounters()
  {
    if( file_exists($this->counterFile) ) {
      $this->counters = json_decode(file_get_contents($this->counterFile), true);
    } else {
      $this->counters = [
        'entry_id' => 0,
        'files_id' => 0
      ];
      $this->saveCounters();
    }
  }
  
  public function saveData()
  {
    file_put_contents($this->dataFile, Yaml::dump($this->data, 4));
  }
  
  private function saveCounters()
  {
    file_put_contents($this->counterFile, json_encode($this->counters, JSON_PRETTY_PRINT));
  }
  
  public function getNextEntryId()
  {
    $this->counters['entry_id']++;
    $this->saveCounters();
    return $this->counters['entry_id'];
  }
  
  public function getNextFilesId()
  {
    $this->counters['files_id']++;
    $this->saveCounters();
    return sprintf('%04d', $this->counters['files_id']);
  }
  
  public function getAllEntries()
  {
    return isset($this->data['entries']) ? $this->data['entries'] : [];
  }
  
  public function getEntryById($id)
  {
    foreach( $this->data['entries'] as $entry ) {
      if( $entry['id'] == $id ) {
        return $entry;
      }
    }
    return null;
  }
  
  public function addEntry($entry)
  {
    if( !isset($this->data['entries']) ) {
      $this->data['entries'] = [];
    }
    
    // Add entry to the beginning of the array (for latest first sorting)
    array_unshift($this->data['entries'], $entry);
    $this->saveData();
    return true;
  }
  
  public function updateEntry($id, $updatedEntry)
  {
    if( !isset($this->data['entries']) ) {
      return false;
    }
    
    foreach( $this->data['entries'] as $key => $entry ) {
      if( $entry['id'] == $id ) {
        $this->data['entries'][$key] = $updatedEntry;
        $this->saveData();
        return true;
      }
    }
    
    return false;
  }
  
  public function deleteEntry($id)
  {
    if( !isset($this->data['entries']) ) {
      return false;
    }
    
    foreach( $this->data['entries'] as $key => $entry ) {
      if( $entry['id'] == $id ) {
        // If it's an apartment entry with files, delete the files directory
        if( isset($entry['type']) && $entry['type'] === 'apartment' && !empty($entry['files_id']) ) {
          $filesDir = __DIR__ . "/../data/files/{$entry['files_id']}";
          if( file_exists($filesDir) ) {
            $this->deleteDirectory($filesDir);
          }
        }
        
        unset($this->data['entries'][$key]);
        $this->data['entries'] = array_values($this->data['entries']); // Re-index array
        $this->saveData();
        return true;
      }
    }
    
    return false;
  }
  
  public function addStatusToApartment($id, $statusEntry)
  {
    foreach( $this->data['entries'] as $key => $entry ) {
      if( $entry['id'] == $id && isset($entry['type']) && $entry['type'] === 'apartment' ) {
        if( !isset($this->data['entries'][$key]['status_list']) ) {
          $this->data['entries'][$key]['status_list'] = [];
        }
        
        // Add status entry to the beginning of the array (for latest first)
        array_unshift($this->data['entries'][$key]['status_list'], $statusEntry);
        $this->saveData();
        return true;
      }
    }
    
    return false;
  }
  
  public function updateStatusInApartment($entryId, $statusIndex, $updatedStatus)
  {
    foreach( $this->data['entries'] as $key => $entry ) {
      if( $entry['id'] == $entryId && isset($entry['type']) && $entry['type'] === 'apartment' ) {
        if( isset($this->data['entries'][$key]['status_list'][$statusIndex]) ) {
          $this->data['entries'][$key]['status_list'][$statusIndex] = $updatedStatus;
          $this->saveData();
          return true;
        }
      }
    }
    
    return false;
  }
  
  public function deleteStatusFromApartment($entryId, $statusIndex)
  {
    foreach( $this->data['entries'] as $key => $entry ) {
      if( $entry['id'] == $entryId && isset($entry['type']) && $entry['type'] === 'apartment' ) {
        if( isset($this->data['entries'][$key]['status_list'][$statusIndex]) ) {
          unset($this->data['entries'][$key]['status_list'][$statusIndex]);
          $this->data['entries'][$key]['status_list'] = array_values($this->data['entries'][$key]['status_list']); // Re-index array
          $this->saveData();
          return true;
        }
      }
    }
    
    return false;
  }
  
  private function deleteDirectory($dir)
  {
    if( !file_exists($dir) ) {
      return true;
    }
    
    if( !is_dir($dir) ) {
      return unlink($dir);
    }
    
    foreach( scandir($dir) as $item ) {
      if( $item == '.' || $item == '..' ) {
        continue;
      }
      
      if( !$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item) ) {
        return false;
      }
    }
    
    return rmdir($dir);
  }
  
  public function getImagesByFilesId($filesId)
  {
    $filesDir = __DIR__ . "/../data/files/{$filesId}";
    $images = [];
    
    if( file_exists($filesDir) && is_dir($filesDir) ) {
      $files = scandir($filesDir);
      
      foreach( $files as $file ) {
        if( $file !== '.' && $file !== '..' ) {
          $images[] = [
            'name' => $file,
            'path' => "data/files/{$filesId}/{$file}"
          ];
        }
      }
    }
    
    return $images;
  }
}
