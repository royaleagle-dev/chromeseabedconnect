<?php

class UploadGCS{

    private $bucketName;
    private $localFilePath;
    private $gcsPath;
    
    public function __construct($bucketName, $localFilePath, $gcsPath){
        $this->bucketName = $bucketName;
        $this->localFilePath = $localFilePath;
        $this->gcsPath = $gcsPath;

    }

    public function upload(){
        try {
            // Get access token from Cloud Run metadata server
            $accessToken = $this->getCloudRunAccessToken();
            if (!$accessToken) {
                throw new Exception('Failed to get access token from metadata server');
            }
        
            // Read file content
            $fileContent = file_get_contents($this->localFilePath);
            if ($fileContent === false) {
                throw new Exception('Failed to read local file: ' . $this->localFilePath);
            }
        
            // Prepare upload URL and headers
            $url = "https://storage.googleapis.com/upload/storage/v1/b/{$this->bucketName}/o";
            $params = [
                'uploadType' => 'media',
                'name' => $this->gcsPath
            ];
        
            $headers = [
                'Authorization: Bearer ' . $accessToken,
                'Content-Type: ' . mime_content_type($this->localFilePath),
                'Content-Length: ' . strlen($fileContent),
            ];
        
            // Upload file
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url . '?' . http_build_query($params),
                CURLOPT_POST => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_POSTFIELDS => $fileContent,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT => 30
            ]);
        
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);
        
            if ($httpCode === 200) {
                return "https://storage.googleapis.com/{$this->bucketName}/{$this->gcsPath}";
            } else {
                throw new Exception("Upload failed. HTTP {$httpCode}: {$response} - {$error}");
            }
        
        } catch (Exception $e) {
            error_log("GCS Upload Error: " . $e->getMessage());
            return false;
        }
    }

    public function getCloudRunAccessToken(){
        $url = 'http://metadata.google.internal/computeMetadata/v1/instance/service-accounts/default/token';    
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_HTTPHEADER => ['Metadata-Flavor: Google'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 5
        ]);
    
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($httpCode === 200) {
            $data = json_decode($response, true);
            return $data['access_token'] ?? null;
        }    
        error_log("Failed to get access token. HTTP Code: {$httpCode}");
        return null;
    }
}


// Example usage
// function exampleUsage() {
//     $bucketName = 'your-bucket-name'; // Replace with your actual bucket
    
//     // Example 1: Upload a local file
//     $localFile = '/tmp/myfile.jpg';
//     $gcsPath = 'images/' . basename($localFile);
//     $publicUrl = uploadToGCS($bucketName, $localFile, $gcsPath);
    
//     if ($publicUrl) {
//         echo "✅ Upload successful: " . $publicUrl;
//     } else {
//         echo "❌ Upload failed";
//     }
    
//     // Example 2: Upload from uploaded file
//     if (isset($_FILES['userfile'])) {
//         $uploadedFile = $_FILES['userfile'];
//         $gcsPath = 'uploads/' . uniqid() . '_' . $uploadedFile['name'];
//         $publicUrl = uploadToGCS($bucketName, $uploadedFile['tmp_name'], $gcsPath);
        
//         if ($publicUrl) {
//             echo "File uploaded: " . $publicUrl;
//         }
//     }
// }

// Uncomment to test
// exampleUsage();
?>