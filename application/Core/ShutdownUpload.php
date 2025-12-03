<?php
class ShutdownUpload {
    
    private $localDbPath = '/tmp/chromeseabed.sqlite';
    private $cloudBucket = 'chromeseabed-file-storage';
    private $cloudFileName = 'chromeseabed.sqlite';
    
    public function __construct() {
        // Register shutdown function to run when container stops
        register_shutdown_function([$this, 'uploadOnShutdown']);
    }
    
    public function uploadOnShutdown() {
        // Check if database file exists and has content
        if (!file_exists($this->localDbPath) || filesize($this->localDbPath) == 0) {
            error_log("📝 No database file to upload on shutdown");
            return false;
        }
        
        error_log("🔄 Container stopping - uploading database to Cloud Storage...");
        
        $accessToken = $this->getAccessToken();
        if (!$accessToken) {
            error_log("❌ Failed to get access token for upload");
            return false;
        }
        
        $url = "https://storage.googleapis.com/upload/storage/v1/b/{$this->cloudBucket}/o?uploadType=media&name={$this->cloudFileName}";
        
        $fileContent = file_get_contents($this->localDbPath);
        if ($fileContent === false) {
            error_log("❌ Failed to read database file");
            return false;
        }
        
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $fileContent,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$accessToken}",
                "Content-Type: application/x-sqlite3",
            ],
            CURLOPT_TIMEOUT => 10, // 10 second timeout
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $error = curl_error($ch);
        curl_close($ch);
        
        if ($httpCode === 200) {
            error_log("✅ Database successfully uploaded to Cloud Storage before shutdown");
            return true;
        } else {
            error_log("❌ Database upload failed: HTTP $httpCode - $error");
            return false;
        }
    }
    
    private function getAccessToken() {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => 'http://metadata.google.internal/computeMetadata/v1/instance/service-accounts/default/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => ['Metadata-Flavor: Google'],
            CURLOPT_TIMEOUT => 5,
        ]);
        
        $response = curl_exec($ch);
        curl_close($ch);
        
        if ($response === false) {
            return null;
        }
        
        $data = json_decode($response, true);
        return $data['access_token'] ?? null;
    }
    
    // Manual upload trigger (optional)
    public function uploadNow() {
        return $this->uploadOnShutdown();
    }
}
?>