<?php

class Test extends Controller{

	public function __construct(){
		$this->visitorModel = $this->loadModel('Visitor');
	}

	public function index(){
		new Template("uploadTest.html", $data=[]);

		if($_SERVER['REQUEST_METHOD'] == 'POST'){
			$result = [];
			$fileCount = count($_FILES['file']['name']);
			$shred = Upload::shredMultiple($_FILES['file']);
			$i = 0;
			foreach($shred as $data){
				$fileObj = $data;
				$upload = new Upload(2*1024*1024, BASE_MEDIA_ROOT.'/public/uploads/gallery/', $fileObj);
				$upload = $upload->upload_picture_file();
				if($upload){
					$result[] = "$data[name]: File Uploaded Successfully";
				}else{					
					$result[] = SessionManager::getItem("msg"). "<span color='red'>($data[name])</span>";
				}
				$i++;
			}

			foreach($result as $data){
				print "$data<br>";
				
			}
		}
	}

	public function test_db_changes(){
		$get_all_visitors = $this->visitorModel->getAllVisitors();
		print_r($get_all_visitors);
		//new Template("test_db.")
	}
}

?>