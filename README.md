![alt text](http://bitcoinprice.net/wp-content/uploads/2015/05/icon1.png "BTC-E TRADE BOT") 

# BTC-E Trade BOT 


## Configs


Change this array in index.php


	$config = (object) [
		'api' 	    	=> 'YOURAPI',     // https://btc-e.com/profile#api_keys
		'key' 		    => 'YOURAPIKEY',  // https://btc-e.com/profile#api_keys
		'valTrade'		=> 25.0,          // DOLLARS to TRADE
		'margin'        => 0.001,         // Percent Gain per Trade (without fees)
		'maxTime' 	    => 3600           // Time out per order
	];
	
	

## In your BTC-E account:
In Your Account, set half Bitcoin, half Dollars value
 
**Example:**
 
25 Dollars, 0.0434 Bitcoin
 
## Running:
  Put it on cron job:
 
		wget http://yourserver:80/index.php
	
