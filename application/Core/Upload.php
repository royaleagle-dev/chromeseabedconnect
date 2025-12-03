<?php

class Upload{
    private $size;
    private $target_dir;
    private $file;

    public function __construct($size, $target_dir, $file_obj){
        //accepts size in bytes 2000000 = 2mb
        $this->size = $size;
        $this->target_dir = $target_dir;
        $this->file = $file_obj;
    }

    private function isLocalEnv(){
        if(ENVIRONMENT === 'DEVELOPMENT'){return true;}
        else{return false;}
    }

    public function upload_local_file(){
        $target_file = $this->target_dir . basename($this->file['name']);
        if($this->file['size'] > 5000000){
            return false;
        }else{
            move_uploaded_file($this->file['tmp_name'], $target_file);
            return([
                'upload_path' => $target_file,
                'upload_path_d' => URL_ROOT."public/uploads/wallpapers/".basename($this->file['name']),
                'status' => true,
            ]);
        }
    }

    public function upload_cloud_file(){
        $gcsPath = 'public/uploads/'. uniqid() . '_' . $this->file['name'];
        $upload = new UploadGCS(GCLOUD_BUCKET_NAME, $this->file['tmp_name'], $gcsPath);
        $upload = $upload->upload();
        if($upload){
            return([
                'upload_path' => '',
                'upload_path_d' => $upload,
                'status' => true,
            ]);
        }else{
            return false;
        }
    }

    public function upload_file(){
        if($this->isLocalEnv()){
            return $this->upload_local_file();
        }else{
            return $this->upload_cloud_file();
        }
    }

}

?>