<?php

	# Carregar libs
	include('libs/trade.php');
	include('libs/tradeuser.php');
	include('libs/btce.php');

	# Configurações API

	$config = (object) [
		'api' 			=> 'T23V49NE-BBBBBB--AAAAAAA-FDMILSMZ-AAAAAAA-',
		'key' 			=> '672c8ad1e80ea000g0bas00e0aa0d00a0f0edade9afb83b1821b1a81a117fds781499',
		'valTrade'		=> 25.0,
		'margin'		=> 0.001,
		'maxTime' 		=> 3600
	];

	$trade =  new btceTrade ();
	$api  = new btceUser( new BTCeAPI($config->api, $config->key) ) ;

	set_exception_handler("logError");


	if( $api->countOpenOrders() ) {
		foreach( $api->getOrdersID() as $id) {

			if( $api->getOrderTime($id) > $config->maxTime) {

				$api->btceApi->cancelOrder($id);

				throw new Exception("The order {$id} reached the maxTime. Cancelling ...");
			}
		}
		throw new Exception("Waiting orders ...");
	}
	else {

		$btcVal			= $trade->getBitcoinValue();
		$btcTrade 		= $trade->getDollar2Bitcoin($config->valTrade , $btcVal );
		$btcAsk			= $trade->algorithmTransversalTrade( $config->margin );
	
		$api->btceApi->makeOrder( round($btcTrade, 3) , 'btc_usd', 'sell', 	round( $btcAsk["s"] , 3));
		$api->btceApi->makeOrder( round($btcTrade, 3) , 'btc_usd', 'buy',	round( $btcAsk["b"], 3)) ;
		
		throw new Exception("Creating orders ... Buy: {$btcAsk['b']} Sell {$btcAsk['s']} (USD) | BTC Price: {$btcVal}");
	}

	

	function logError($exception){		

		$printStr = sprintf("[%d] %s \r\n", time(), $exception->getMessage());

		file_put_contents("trade.logs", $printStr, FILE_APPEND);

		print($printStr."<br>");

		exit;
	}

?>
