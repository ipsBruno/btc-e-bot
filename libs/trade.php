<?php


	class btceTrade {
	
	
		public $info = [];
	
		public $apiUrls = [
			"info" 		=> "https://btc-e.com/api/3/ticker/btc_usd",
			"exchange" 	=> "https://btc-e.com/api/3/info"
		];
		

		function __construct() {
			$this->setBitcoinInfo(); 
		}

		public function setBitcoinInfo() {
			$this->info = array();			
			array_push($this->info, json_decode(file_get_contents($this->apiUrls["info"]), true));	
			array_push($this->info, json_decode(file_get_contents($this->apiUrls["exchange"]), true));			
		}

		public function getBitcoinValue() {
			return $this->info[0]["btc_usd"]["last"];
		}

		public function getExchangeFee() {
			return $this->info[1]["pairs"]["btc_usd"]["fee"];
		}

		public function setExchangeFee($f) {
			return $this->info[1]["pairs"]["btc_usd"]["fee"] = $f;
		}

		public  function getBitcoinAveragePrice() {

			return $this->info[0]["btc_usd"]["avg"];
		}


		public function getDollar2Bitcoin($dollar, $bitcoincota) {
			return (float) $dollar/$bitcoincota;
		}


		/*
		 *   Funзгo para calcular valor de venda para Bitcoin
		 *   Algorнtimo simples para calcular lucro conforme taxa de juros
		 *   $val й o valor para bitcoin para vender e comprar
		 *   $margin й o valor de lucro em porcentagem que vocк quer
		 *   Quanto maior o $margin mais dificil a ordem fechar
		*/

		public function algorithmTransversalTrade($margin) {

			$v = $this->getBitcoinValue() ;
			
			$p = ($this->getExchangeFee() + ($margin /2)) ;

			
			return [
				"s"=>(float) $v * (1 + $p / 100),
				"b"=>(float) $v * (1 + (-$p) / 100)
			];
		}

	}

?>