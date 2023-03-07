<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CheckOutApi extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('CheckOutModel');
	}
	//FOR SIGN IN
	public function LogIn()
	{
		$uname = $_POST['username'];
		$pass = $_POST['password2'];
		// $uname = 'emman';
		// $pass = '1';

		$result = $this->CheckOutModel->checkUser($uname, $pass);
		echo json_encode($result);
	}

	public function getTransaction()
	{
		$bcode = $_POST['receipt'];
		// $bcode = '133333337';

		$result = $this->CheckOutModel->gettingTransaction($bcode);
		echo json_encode($result);
	}

	public function loadForPickup()
	{
		$bcode = $_POST['bunit_code'];	
		// $bcode = '1';
		
		$result = $this->CheckOutModel->loadingForPickup($bcode);
		echo json_encode($result);
	}

	public function loadForDelivery()
	{
		$bcode = $_POST['bunit_code'];	
		// $bcode = '1';
		
		$result = $this->CheckOutModel->loadingForDelivery($bcode);
		echo json_encode($result);
	}

	public function getPackageNo()
	{
		$tran = $_POST['receipt'];
		$mop = $_POST['mop'];

		if($mop=='Delivery'){
			$stat = 'Pending';
		}else{
			$stat = 'Paid';	
		}

		$result = $this->CheckOutModel->gettingPackageNo($tran,$stat);
		echo json_encode($result);
	}

	public function searchBarcode()
	{
		$tran = $_POST['receipt'];
		$barcode = $_POST['barcode'];
		// $tran = '060721-358';
		// $barcode = '22222222222';

		$search = $this->CheckOutModel->searchingBarcode($tran,$barcode);

		if(!empty($search)){
			if($search[0]['status']=='Paid'){
			$result = $this->CheckOutModel->scanningBarcode($search[0]['receipt'],$search[0]['barcode']);
			}else{
			$result = 'ALREADY SCANNED';
			}
		}else{
			$result = 'NOT FOUND!';
		}
		
		echo json_encode($result);
	}


	public function changeTranStat()
	{
		date_default_timezone_set('Asia/Manila');

		$tran = $_POST['receipt'];
		$date = date('Y-m-d H:i:s');
		$user = $_POST['user_id'];
		$ticket = $_POST['ticket_id'];
		$mop = $_POST['mop'];


		// $tran = 'T72751210447679';
		// $date = date('Y-m-d H:i:s');
		// $user = '01000022590';
		// $ticket = '201229-1-015';
		if($mop=='Delivery'){
			$stat = 'Released';
		}else{
			$stat = 'Paid and Released';
		}
		
		$result2 = $this->CheckOutModel->changingTranCodeStat($tran,$stat);
		
		$checkId = $this->CheckOutModel->checkTicketId($ticket);

		if(!empty($checkId)){
			$result3 = $this->CheckOutModel->updateOrderStatus($checkId[0]['id'],$date);
		}

		$result = $this->CheckOutModel->changingTranStat($tran,$date,$user,$stat);

		echo json_encode($result);
		// echo json_encode($result2);
		// echo json_encode($date);
		// echo json_encode($result3);
		
		// $result = $this->CheckOutModel->changingTranStat($tran,$date,$user);
		// echo json_encode($result);

		// $result2 = $this->CheckOutModel->changingTranCodeStat($tran);
		// echo json_encode($result2);
	}

	public function loadHistory()
	{	

		$user = $_POST['user_id'];
		// $user = '04434-2015';
		// $date = date('Y-m-d H:i:s');

		$result = $this->CheckOutModel->loadingHistory($user);
		echo json_encode($result);
	}

	public function changeUserPassword()
	{
		$code = $_POST['emp_id'];
		$newPass = $_POST['password2'];
		// $code = '01000022590';
		// $newPass = '2';

		$result = $this->CheckOutModel->changingUserPassword($code,$newPass);
		echo json_encode($result);
	}

	public function searchHistory()
	{
		$id = $_POST['user_id'];
		$name = $_POST['customer'];
		// $id = '01000022590';
		// $name = 'L';

		$result = $this->CheckOutModel->searchingHistory($id,$name);
		echo json_encode($result);
	}
}

