<?php
	class btceUser {

		public $btceApi = null;
		public $userInfo = null;

		public $btc = 0; 
		public $usd = 0;
		public $orders = null;



		function __construct($api) {

			$this->btceApi = $api;

			$this->userInfo = $this->btceApi->apiQuery('getInfo');

			$this->btc 		= $this->userInfo["return"]["funds"]["btc"];
			$this->usd 	= $this->userInfo["return"]["funds"]["usd"];			
		
    			try {
    				$this->orders  = $this->btceApi->apiQuery('ActiveOrders', [ 'pair' => 'btc_usd' ]);
    			}
			catch(BTCeAPIException $e) {
				$this->orders = [];
			}

			if($this->usd < 1.00) 	throw new Exception('No dollars in your account. Min: 1.0');
			if($this->btc < 0.001) 	throw new Exception('No bitcoins in your account. Min: 0.001');
	
		}


		function getDollarsAccount() {
			return $this->usd ;
		}

		function getAmmountAccount($cot) {
			return ($this->btc * $cot ) +  $this->usd ;
		}

		function getBitcoinsAccount() {
			return $this->btc ;
		}


		function countOpenOrders() {
			return count($this->orders);    			
		}

		function getOrdersID() {

			if (  !$this->countOpenOrders() )
				return [];

			return array_keys($this->orders["return"]);
		}

		function getOpenOrders() {
			return $this->orders["return"];    			
		}

		function getOrderTime($id) {

			if (  !$this->countOpenOrders() )
				return -1;

			if (  !isset ( $this->orders["return"] [$id]) )
				return -1;

			return time() - $this->orders["return"] [$id]["timestamp_created"];
		}		
	}
?>
	