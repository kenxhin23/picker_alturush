<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class CheckOutModel extends CI_Model{
        public function checkUser($username = null, $password){
			$this->db->select('*,user_types.usertype');
			$this->db->from('gc_users');
			$this->db->join('user_types', 'user_types.id = gc_users.usertype_id');
			$this->db->where('gc_users.username', $username);

			$query = $this->db->get();
			$res = $query->row_array();

			// var_dump($res);

			if(isset($res)){
				if($res['password2'] == md5($password)){
				   	return $res;
				   	// return "Success";
				}else{
					return "failed password";
				}
			}else{
				// return $res;
				return "failed username";
			}

		}

		// public function gettingTransaction($code){
		// 	$this->db->select('*');
		// 	$this->db->from('gc_transactions');
		// 	$this->db->where('gc_transactions.status', "Pending");
		// 	$this->db->where('gc_transactions.receipt', $code);

		// 	$query = $this->db->get();
		// 	$res = $query->row_array();


		// 	if(isset($res)){
		// 		return $res;
		// 	}else{
		// 		return "NOT FOUND!";
		// 	}

		// }

		public function gettingTransaction($code){
			$this->db->select('*,tickets.mop');
			$this->db->from('gc_transactions');
			$this->db->join('tickets', '(tickets.ticket = gc_transactions.ticket_id AND tickets.mop = "Pick-up" AND gc_transactions.status = "Paid") OR (tickets.ticket = gc_transactions.ticket_id AND tickets.mop = "Delivery" AND gc_transactions.status = "Pending")');
			// $this->db->where('gc_transactions.status', "Paid");
			$this->db->where('gc_transactions.receipt', $code);

			$query = $this->db->get();
			$res = $query->row_array();


			if(isset($res)){
				return $res;
			}else{
				return "NOT FOUND!";
			}

		}

		// public function loadingForPickup($code){
		// 	$this->db->select('*,tickets.mop');
		// 	$this->db->from('gc_transactions');
		// 	$this->db->join('tickets', 'tickets.ticket = gc_transactions.ticket_id');
		// 	$this->db->where('gc_transactions.status !=', "Paid and Released");
		// 	$this->db->where('gc_transactions.bunit_code', $code);

		// 	$query = $this->db->get();
		// 	$res = $query->result_array();


		// 	if(isset($res)){
		// 		return $res;
		// 	}else{
		// 		return "NOT FOUND!";
		// 	}

		// }

		public function loadingForPickup($code){
			$this->db->select('*');
			$this->db->from('gc_transactions');
			$this->db->join('tickets', 'tickets.ticket = gc_transactions.ticket_id AND tickets.mop = "Pick-up" ');
			$this->db->where('gc_transactions.status', "Paid");
			$this->db->where('gc_transactions.bunit_code', $code);

			$query = $this->db->get();
			$res = $query->result_array();


			if(isset($res)){
				return $res;
			}else{
				return "NOT FOUND!";
			}

		}

		public function loadingForDelivery($code){
			$this->db->select('*');
			$this->db->from('gc_transactions');
			$this->db->join('tickets', 'tickets.ticket = gc_transactions.ticket_id AND tickets.mop = "Delivery" ');
			$this->db->where('gc_transactions.status', "Pending");
			$this->db->where('gc_transactions.bunit_code', $code);

			$query = $this->db->get();
			$res = $query->result_array();


			if(isset($res)){
				return $res;
			}else{
				return "NOT FOUND!";
			}

		}

		public function gettingPackageNo($tran,$stat){
			$this->db->select('*, 0 as no');
			$this->db->from('gc_package_barcode');
			$this->db->where('gc_package_barcode.status', $stat);
			$this->db->where('gc_package_barcode.receipt', $tran);

			$query = $this->db->get();
			$res = $query->result_array();


			if(isset($res)){
				return $res;
			}else{
				return "NOT FOUND!";
			}

		}
		

		public function searchingBarcode($tran,$bcode){
			$this->db->select('*');
			$this->db->from('gc_package_barcode');
			// $this->db->where('tb_checkout_pack_bcode.status', "Pending");
			$this->db->where('gc_package_barcode.barcode', $bcode);
			$this->db->where('gc_package_barcode.receipt', $tran);

			$query = $this->db->get();
			$res = $query->result_array();


			if(isset($res)){
				return $res;
			}

		}


		public function scanningBarcode($tran,$code){
	    	$data = array(
	    			'receipt' => $tran,
			        'barcode' => $code,

			);
			$this->db->set('gc_package_barcode.status',"Paid and Released");
			$this->db->where('gc_package_barcode.barcode',$code);
			$this->db->where('gc_package_barcode.receipt',$tran);
			$this->db->update('gc_package_barcode');

			$query = $this->db->affected_rows();
	    	if($query == 1)
	    	{
	    		// return $query;
	    		return "SCANNED";
	    	}else
	    	{
	    		// return FALSE;
	    		return "SCAN FAILED";
	    	}
	    }

	    public function changingTranStat($tran,$date,$user,$stat){
	    	$data = array(
	    			'receipt' => $tran,
	    			'updated_at' => $date,
	    			'user_id' => $user,
			);
			$this->db->set('gc_transactions.status',$stat);
			$this->db->set('gc_transactions.updated_at',$date);
			$this->db->set('gc_transactions.user_id',$user);
			$this->db->where('gc_transactions.receipt',$tran);
			$this->db->update('gc_transactions');

			$query = $this->db->affected_rows();
	    	if($query == 1)
	    	{
	    		// return $query;
	    		return "STATUS CHANGED";
	    	}else
	    	{
	    		// return FALSE;
	    		return "FAILED";
	    	}
	    }

	    public function changingTranCodeStat($tran,$stat){
	    	$data = array(
	    			'receipt' => $tran,
	    			
			);
			$this->db->set('gc_package_barcode.status',$stat);
			$this->db->where('gc_package_barcode.receipt',$tran);
			$this->db->update('gc_package_barcode');
	    }

	    public function checkTicketId($ticket){
			$this->db->select('tickets.id');
			$this->db->from('tickets');
			$this->db->where('tickets.ticket', $ticket);

			$query = $this->db->get();
			$res = $query->result_array();

			if(isset($res)){
				return $res;
			}

		}

	     public function updateOrderStatus($ticket,$date){
	    	$data = array(
	    			'ticket_id' =>$ticket,
	    			
			);
			$this->db->set('gc_order_statuses.released_status','1');
			$this->db->set('gc_order_statuses.released_at',$date);
			$this->db->where('gc_order_statuses.ticket_id',$ticket);
			$this->db->update('gc_order_statuses');
	    }

	    public function loadingHistory($user){
			$this->db->select('*,gc_transactions.updated_at, tickets.mop');
			$this->db->from('gc_transactions');
			$this->db->join('tickets', 'tickets.ticket = gc_transactions.ticket_id');
			$this->db->where('gc_transactions.status !=',"Pending");
			$this->db->where('gc_transactions.status !=',"Paid");
			$this->db->where('gc_transactions.user_id',$user);
			$this->db->order_by('gc_transactions.updated_at','desc');

			$query = $this->db->get();
			$res = $query->result_array();


			if(isset($res)){
				return $res;
			}else{
				return "NOT FOUND!";
			}

		}

		public function changingUserPassword($id, $password){
			$data = array(
			        'password2' => md5($password)
			);
			$np = md5($password);
			$this->db->where('emp_id',$id);
			$this->db->update('gc_users', $data);

			$query = $this->db->affected_rows();
	    	if(isset($query))
	    	{
	    		// return $query;
	    		return "success";
	    	}else
	    	{
	    		// return FALSE;
	    		// return $query;
	    		return "failed";
	    	}
		}

		public function searchingHistory($uid,$name){
				$this->db->select('*');
				$this->db->from('gc_transactions');
				$this->db->like('gc_transactions.customer', $name);
				$this->db->where('gc_transactions.user_id', $uid);
				$this->db->where('gc_transactions.status', "Paid and Released");
				$this->db->order_by('gc_transactions.customer','asc');

				$query = $this->db->get();
				$res = $query->result_array();


				if(isset($res)){
					return $res;
				}else{
					return "No transaction found!";
				}

			}

// 		public function checkUser($username = null, $password){
// 			$this->db->select('*,user_types.usertype');
// 			$this->db->from('gc_users');
// 			$this->db->join('user_types', 'user_types.id = gc_users.usertype_id');
// 			$this->db->where('gc_users.username', $username);

// 			$query = $this->db->get();
// 			$res = $query->row_array();

// 			// var_dump($res);

// 			if(isset($res)){
// 				if($res['password2'] == md5($password)){
// 				   	return $res;
// 				   	// return "Success";
// 				}else{
// 					return "failed password";
// 				}
// 			}else{
// 				// return $res;
// 				return "failed username";
// 			}

// 		}

// // 		public function gettingTransaction($code){
// // 			$this->db->select('*');
// // 			$this->db->from('gc_transactions');
// // 			$this->db->where('gc_transactions.status', "Paid");
// // 			$this->db->where('gc_transactions.receipt', $code);

// // 			$query = $this->db->get();
// // 			$res = $query->row_array();


// // 			if(isset($res)){
// // 				return $res;
// // 			}else{
// // 				return "NOT FOUND!";
// // 			}

// // 		}

//         public function gettingTransaction($code){
// 			$this->db->select('*,tickets.mop');
// 			$this->db->from('gc_transactions');
// 			$this->db->join('tickets', '(tickets.ticket = gc_transactions.ticket_id AND tickets.mop = "Pick-up" AND gc_transactions.status = "Paid") OR (tickets.ticket = gc_transactions.ticket_id AND tickets.mop = "Delivery" AND gc_transactions.status = "Pending")');
// 			// $this->db->where('gc_transactions.status', "Paid");
// 			$this->db->where('gc_transactions.receipt', $code);

// 			$query = $this->db->get();
// 			$res = $query->row_array();


// 			if(isset($res)){
// 				return $res;
// 			}else{
// 				return "NOT FOUND!";
// 			}

// 		}

// // 		public function loadingForPickup($code){
// // 			$this->db->select('*');
// // 			$this->db->from('gc_transactions');
// // 			$this->db->where('gc_transactions.status', "Paid");
// // 			$this->db->where('gc_transactions.bunit_code', $code);

// // 			$query = $this->db->get();
// // 			$res = $query->result_array();


// // 			if(isset($res)){
// // 				return $res;
// // 			}else{
// // 				return "NOT FOUND!";
// // 			}

// // 		}

//         public function loadingForPickup($code){
// 			$this->db->select('*');
// 			$this->db->from('gc_transactions');
// 			$this->db->join('tickets', 'tickets.ticket = gc_transactions.ticket_id AND tickets.mop = "Pick-up" ');
// 			$this->db->where('gc_transactions.status', "Paid");
// 			$this->db->where('gc_transactions.bunit_code', $code);

// 			$query = $this->db->get();
// 			$res = $query->result_array();


// 			if(isset($res)){
// 				return $res;
// 			}else{
// 				return "NOT FOUND!";
// 			}

// 		}
		
		
// 		public function loadingForDelivery($code){
// 			$this->db->select('*');
// 			$this->db->from('gc_transactions');
// 			$this->db->join('tickets', 'tickets.ticket = gc_transactions.ticket_id AND tickets.mop = "Delivery" ');
// 			$this->db->where('gc_transactions.status', "Pending");
// 			$this->db->where('gc_transactions.bunit_code', $code);

// 			$query = $this->db->get();
// 			$res = $query->result_array();


// 			if(isset($res)){
// 				return $res;
// 			}else{
// 				return "NOT FOUND!";
// 			}

// 		}

// // 		public function gettingPackageNo($tran){
// // 			$this->db->select('*, 0 as no');
// // 			$this->db->from('gc_package_barcode');
// // 			$this->db->where('gc_package_barcode.status', "Paid");
// // 			// $this->db->where('tb_checkout_package_bcode.barcode', $bcode);
// // 			$this->db->where('gc_package_barcode.receipt', $tran);

// // 			$query = $this->db->get();
// // 			$res = $query->result_array();


// // 			if(isset($res)){
// // 				return $res;
// // 			}else{
// // 				return "NOT FOUND!";
// // 			}

// // 		}
//         public function gettingPackageNo($tran,$stat){
// 			$this->db->select('*, 0 as no');
// 			$this->db->from('gc_package_barcode');
// 			$this->db->where('gc_package_barcode.status', $stat);
// 			$this->db->where('gc_package_barcode.receipt', $tran);

// 			$query = $this->db->get();
// 			$res = $query->result_array();


// 			if(isset($res)){
// 				return $res;
// 			}else{
// 				return "NOT FOUND!";
// 			}

// 		}

// 		public function searchingBarcode($tran,$bcode){
// 			$this->db->select('*');
// 			$this->db->from('gc_package_barcode');
// 			// $this->db->where('tb_checkout_pack_bcode.status', "Pending");
// 			$this->db->where('gc_package_barcode.barcode', $bcode);
// 			$this->db->where('gc_package_barcode.receipt', $tran);

// 			$query = $this->db->get();
// 			$res = $query->result_array();


// 			if(isset($res)){
// 				return $res;
// 			}

// 		}


// 		public function scanningBarcode($tran,$code){
// 	    	$data = array(
// 	    			'receipt' => $tran,
// 			        'barcode' => $code,

// 			);
// 			$this->db->set('gc_package_barcode.status',"Paid and Released");
// 			$this->db->where('gc_package_barcode.barcode',$code);
// 			$this->db->where('gc_package_barcode.receipt',$tran);
// 			$this->db->update('gc_package_barcode');

// 			$query = $this->db->affected_rows();
// 	    	if($query == 1)
// 	    	{
// 	    		// return $query;
// 	    		return "SCANNED";
// 	    	}else
// 	    	{
// 	    		// return FALSE;
// 	    		return "SCAN FAILED";
// 	    	}
// 	    }

// 	    public function changingTranStat($tran,$date,$user){
// 	    	$data = array(
// 	    			'receipt' => $tran,
// 	    			'updated_at' => $date,
// 	    			'user_id' => $user,
// 			);
// 			$this->db->set('gc_transactions.status',"Paid and Released");
// 			$this->db->set('gc_transactions.updated_at',$date);
// 			$this->db->set('gc_transactions.user_id',$user);
// 			$this->db->where('gc_transactions.receipt',$tran);
// 			$this->db->update('gc_transactions');

// 			$query = $this->db->affected_rows();
// 	    	if($query == 1)
// 	    	{
// 	    		// return $query;
// 	    		return "STATUS CHANGED";
// 	    	}else
// 	    	{
// 	    		// return FALSE;
// 	    		return "FAILED";
// 	    	}
// 	    }

// 	    public function changingTranCodeStat($tran){
// 	    	$data = array(
// 	    			'receipt' => $tran,
	    			
// 			);
// 			$this->db->set('gc_package_barcode.status',"Paid and Released");
// 			$this->db->where('gc_package_barcode.receipt',$tran);
// 			$this->db->update('gc_package_barcode');
// 	    }

// 	    public function checkTicketId($ticket){
// 			$this->db->select('tickets.id');
// 			$this->db->from('tickets');
// 			$this->db->where('tickets.ticket', $ticket);

// 			$query = $this->db->get();
// 			$res = $query->result_array();

// 			if(isset($res)){
// 				return $res;
// 			}

// 		}

// 	     public function updateOrderStatus($ticket,$date){
// 	    	$data = array(
// 	    			'ticket_id' =>$ticket,
	    			
// 			);
// 			$this->db->set('gc_order_statuses.released_status','1');
// 			$this->db->set('gc_order_statuses.released_at',$date);
// 			$this->db->where('gc_order_statuses.ticket_id',$ticket);
// 			$this->db->update('gc_order_statuses');
// 	    }

// 	    public function loadingHistory($user){
// 			$this->db->select('*');
// 			$this->db->from('gc_transactions');
// 			$this->db->where('gc_transactions.status', "Paid and Released");
// 			$this->db->where('gc_transactions.user_id',$user);

// 			$query = $this->db->get();
// 			$res = $query->result_array();


// 			if(isset($res)){
// 				return $res;
// 			}else{
// 				return "NOT FOUND!";
// 			}

// 		}

// 		public function changingUserPassword($id, $password){
// 			$data = array(
// 			        'password2' => md5($password)
// 			);
// 			$np = md5($password);
// 			$this->db->where('emp_id',$id);
// 			$this->db->update('gc_users', $data);

// 			$query = $this->db->affected_rows();
// 	    	if(isset($query))
// 	    	{
// 	    		// return $query;
// 	    		return "success";
// 	    	}else
// 	    	{
// 	    		// return FALSE;
// 	    		// return $query;
// 	    		return "failed";
// 	    	}
// 		}

// 		public function searchingHistory($uid,$name){
// 				$this->db->select('*');
// 				$this->db->from('gc_transactions');
// 				$this->db->like('gc_transactions.customer', $name);
// 				$this->db->where('gc_transactions.user_id', $uid);
// 				$this->db->where('gc_transactions.status', "Paid and Released");
// 				$this->db->order_by('gc_transactions.customer','asc');

// 				$query = $this->db->get();
// 				$res = $query->result_array();


// 				if(isset($res)){
// 					return $res;
// 				}else{
// 					return "No transaction found!";
// 				}

// 			}

}