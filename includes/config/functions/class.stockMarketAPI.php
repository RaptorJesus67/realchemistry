<?php
/**
 * A simple API to retrieve current stock market data
 *
 * This PHP class uses the Yahoo! Finanace API to get current stock market data.
 *
 * PHP version 5
 *
 * @package   StockMarketAPI
 * @author    Ben Marshall <me@benmarshall.me>
 * @author	  Ryan Allen <ryan@ndigit.co>
 * @license   http://www.opensource.org/licenses/mit-license.html  MIT License
 * @version   1.3
 * @link      http://www.benmarshall.me/php-stock-market-api
 * @since     Class available since Release 1.0
 */

class StockMarketAPI {
	
	/*
	 * The stock symbol to request data for
	 *
	 * @var string
	 * @access public
	 */
	public $symbol;
  
	/*
	 * The type of data to request
	 *
	 * @var string
	 * @access public
	 */
	public $stat;
  
	/*
	 * Config options for historial data
	 *
	 * @var boolean/array
	 * @access public
	 */
	public $history;
  
	/*
	 * Initializes the MarketWatchAPI
	 *
	 * @param string  $symbol   the stock symbol
	 * @param string  $stat     the type of data to retrieve, default grabs all data
	 *
	 * @access public
	 * @since Method available since Release 1.0
	 */
	public function __construct($symbol = '', $stat = 'all', $history = false) {
		if($symbol) $this->_setParam('symbol', $symbol);
		$this->_setParam('stat', $stat);
		$this->_setParam('history', $history);
	}

	/*
	 * Sets the class parameters
	 *
	 * @param string  $param  the parameter to set
	 * @param string  $val    the value of the parameter
	 *
	 * @access private
	 * @since Method available since Release 1.0
	 */
	private function _setParam($param, $val) {
	
		switch($param) {
			case 'symbol':
				$this->symbol = $val;
				break;
			case 'stat':
				$this->stat = $val;
				break;
			case 'history':
				$this->history = $val;
				break;
		}
	}
	
	/*
	 * Makes a request to the Yahoo! Finance API
	 *
	 * @access private
	 * @since Method available since Release 1.0
	 */
	private function _request() {
		if(!$this->history) {
			$file = 'http://download.finance.yahoo.com/d/quotes.csv?s='.$this->symbol.'&f='.$this->_convertStat($this->stat).'=.csv';
		} elseif(is_array($this->history)) {
			
		  //Make sure they aren't trying to use multiple stocks. Unsupported as of 11-5-14.	
		  if (strstr($this->symbol,"+")) {
			  trigger_error("This method is not supported by the Yahoo! Finance API. You cannot select a date range AND multiple stocks.");
			  return;  
		  }
		  
		  $this->history['start'] = isset($this->history['start']) ? $this->history['start'] : '1-1-'.(date('Y') - 1);
		  $start = explode('-', $this->history['start']); // dd-mm-yyyy
		  $a = $start[0] - 1; // Month
		  $b = $start[1]; // Day
		  $c = $start[2]; // Year
	
		  $this->history['end'] = isset($this->history['end']) ? $this->history['end'] : '12-31-'.date('Y');
		  $end = explode('-', $this->history['end']); // dd-mm-yyyy
		  $d = $end[0] - 1; // Month
		  $e = $end[1]; // Day
		  $f = $end[2]; // Year
			
		  $g = isset($this->history['interval']) ? $this->history['interval'] : 'd'; // d = Daily, w = Weekly, m = Monthly
	
		  $file = 'http://ichart.yahoo.com/table.csv?s='.$this->symbol.'&a='.$a.'&b='.$b.'&c='.$c.'&d='.$d.'&e='.$e.'&f='.$f.'&g='.$g.'&ignore=.csv';
		}
		
		$handle = fopen($file, "r");
		if(!$this->history) {
			while (($data = fgetcsv($handle, false, ",")) !== FALSE) { $return[] = $data;} //Loop through and store each item in an indice
			
		} elseif(is_array($this->history)) {
		  $return = array();
		  $row = 0;  
		  while (($data = fgetcsv($handle, false, ',')) !== FALSE) {
			  $num = count($data);
			  $return[$this->symbol][$row] = array();
			  for ($c=0; $c < $num; $c++) {
				  switch($c) {
					case 0:
						$key = 'date';
						break;
					case 1:
						$key = 'open';
						break;
					case 2:
						$key = 'high';
						break;
					case 3:
						$key = 'low';
						break;
					case 4:
						$key = 'close';
						break;
					case 5:
						$key = 'volume';
						break;
					case 6:
						$key = 'adj_close';
						break;
				  }
				  $return[$this->symbol][$row][$key] = $data[$c];
			  }
			  $row++;
		  } //end while
	
		}
		
		fclose($handle);
		return $return;
	}

	/*
	 * Retrieve's stock market data
	 *
	 * @param string/array  $symbol   the stock symbol(s)
	 * @param string  	  $stat     the type of data to retrieve, default grabs all data
	 *
	 * @return  array the requested data
	 *
	 * @access public
	 * @since Method available since Release 1.0
	 */
	public function getData($symbol='', $stat='') {
	  
		if (is_array($this->symbol)) {
			$symbol = implode("+", $this->symbol); //The Yahoo! API will take multiple symbols
		}
		
		if($symbol) $this->_setParam('symbol', $symbol);
		if($stat) $this->_setParam('stat', $stat);
	  
		$data = $this->_request();
	  
		if(!$this->history) {
			if ($this->stat === 'all') { 
				foreach ($data as $item) {
					  
					//Add to $return[$symbol] array. Indice 23 is the symbol.
					$return[$item[23]] = array(
						'price'                       =>  strip_tags($item[0]),
						'change'                      =>  strip_tags($item[1]),
						'volume'                      =>  strip_tags($item[2]),
						'avg_daily_volume'            =>  strip_tags($item[3]),
						'stock_exchange'              =>  strip_tags($item[4]),
						'market_cap'                  =>  strip_tags($item[5]),
						'book_value'                  =>  strip_tags($item[6]),
						'ebitda'                      =>  strip_tags($item[7]),
						'dividend_per_share'          =>  strip_tags($item[8]),
						'dividend_yield'              =>  strip_tags($item[9]),
						'earnings_per_share'          =>  strip_tags($item[10]),
						'fiftytwo_week_high'          =>  strip_tags($item[11]),
						'fiftytwo_week_low'           =>  strip_tags($item[12]),
						'fiftyday_moving_avg'         =>  strip_tags($item[13]),
						'twohundredday_moving_avg'    =>  strip_tags($item[14]),
						'price_earnings_ratio'        =>  strip_tags($item[15]),
						'price_earnings_growth_ratio' =>  strip_tags($item[16]),
						'price_sales_ratio'           =>  strip_tags($item[17]),
						'price_book_ratio'            =>  strip_tags($item[18]),
						'short_ratio'                 =>  strip_tags($item[19]),
						'name'                 		=>  strip_tags($item[20])
					);
				}
			} else {
				foreach ($data as $item)
					$return[] = array($this->stat => $item);
			}
		} elseif(is_array($this->history)) {
			$return = $data;
		}
	  
		return $return;
	}

	public function getHistoricalData($symbol='', $stat='') {
		if($symbol) $this->_setParam('symbol', $symbol);
		if($stat) $this->_setParam('stat', $stat);
	}
  
	/*
	 * Converts the string stat to Yahoo! value. 
	 * A full list of symbols is available at http://greenido.wordpress.com/2009/12/22/yahoo-finance-hidden-api/
	 *
	 * @param string  $stat   the text value stat
	 *
	 * @return  string the Yahoo! stat value
	 *
	 * @access private
	 * @since Method available since Release 1.0
	 */
	private function _convertStat($stat) {
		switch($stat) {
			case 'all':
				return 'l1c1va2xj1b4j4dyekjm3m4rr5p5p6s7n';
				break;
			case '200DayMovingAvg':
				return 'm3';
				break;
			case '50DayMovingAvg':
				return 'm4';
				break;	
			case '52WeekLow':
				return 'j';
				break;
			case '52WeekHigh':
				return 'k';
				break;
			case 'avgDailyVolume':
			  return 'a2';
			  break;
			case 'bookValue':
				return 'b4';
				break;	
			case 'dividendPerShare':
				return 'd';
				break;
			case 'dividendYield':
				return 'y';
				break;
			case 'ebitda':
				return 'j4';
				break;
			case 'eps':
				return 'e';
				break;
			case 'peGrowthRatio':
				return 'r5';
				break;	
			case 'peRatio':
				return 'r';
				break;
			case 'price':
				return 'l1';
				break;
			case 'priceBookRatio':
				return 'p6';
				break;
			case 'priceSalesRatio':
				return 'p5';
				break;
			case 'change':
				return 'c1';
				break;
			case 'marketCap':
				return 'j1';
				break;
			case 'name':
				return 'n';
				break;
			case 'shortRatio':
				return 's7';
				break;
			case 'stockExchange':
				return 'x';
				break;	
			case 'volume':
				return 'v';
				break;
		}
	}
}
