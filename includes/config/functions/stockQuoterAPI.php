<?php

	 /**
	 * A simple API to retrieve current stock market data
	 *
	 * This PHP class uses nasdaq.com, morningstar.com, google.com, wikipedia.com, and other misc. sites 
	 * to get current stock market data.
	 *
	 * Built in PHP 5.6
	 *
	 * 	@package	   	stockQuoterAPI
	 * 	@author	  		Mitch Mullvain <experienceit12@gmail.com>
	 * 	@license   		MIT License - [http://www.opensource.org/licenses/mit-license.html]
	 *	@created		October 9, 2015
	 *
	 * 	@version   		1.0
	 *	@release		TBA
	 *
	 *	@NOTICE:		This API requires php_curl to be either installed or activated
	 *					
	 *					WINDOWS
	 *						1.)	If needed to, uncomment the ";extension=php_curl.dll" in you php.ini file
	 *						2.)	Restart Apache
	 *
	 *					LINUX
	 *						1.) Install through "sudo apt-get install php5-curl" in the Terminal. I know Ubuntu 13.0 and up requires it seperately.
	 *						2.)	Restart Apache (for me it is with "service apache2 restart" in the terminal)
	 *
	 *
	 *	@NOTICE:		This API requires "Simple HTML DOM" for php. Find it on Source Forge Here:
	 *			
	 *						http://sourceforge.net/projects/simplehtmldom/files/
	 *
	 *					In your site's configuration file, "require_once()" the file. The class is called within this API.
	 *
	 */
	 
	
	 // Constant to remove html tags and contents inside from string
	 define("STRIP_TAGS_CONTENTS", true);
	 
	 
	 
	 class stockQuoterAPI {
	 
		private $elements;
		
		
		
		/**
		*
		*	Any array that is for the developer to use in their own pages.
		*
		*	Used after class and "getStockInfo()" are called. Returns an array:
		*		- $this->value[$valueKey]["value"]
		*
		*	After class and method are called, call it like such: (use text as the second array value)
		*		- $this->value[$valueKey]["text"]
		*
		*	@access			public			[used by the user outside the class]
		*
		*/
		public $value;
	 
	 
	 
		 /**
		 *
		 *		VALUES ALLOWED
		 *
		 *		List of allowed parameters. 
		 *		Any other syntax will return "N/A".
		 *
		 *		"fName"			-	Formal, Full Name of the company
		 *		"sName"			-	Simple, Short Name of the Company
		 *		"price"			-	The current pice of the stock
		 *		"mChange"		-	Price change for the day
		 *		"pChange"		-	Change in Percentage for the day
		 *		"exchange"		-	Stock Exchange that hosts the company
		 *		"high"			-	The high price of the day
		 *		"low"			- 	The low price of the day
		 *		"income"		-	The Net Income of the company (in Millions)
		 *		"divided"		- 	The yearly dividend for each stock
		 *		"eps"			-	'Earnings Per Share'; (Net Income - Dividend Per Stock) / Avg. Outstanding Shares
		 *		"equity"		-	'Return on Equity'; How much of the net profits go towards shareholders
		 *		"revenue"		-	The amount of money (in USD Millions) that a company generates
		 *		"payRatio"		-	The amount of revenue payed to sharholders via dividend payments
		 *		"cashFlow"		-	The amount of money (in USD Millions) a corporation has to spend freely
		 *		"cbv"			-	The 'Current Book Value'; value of a stock if the company were to "liquidate"
		 *		"obv[$i]"		-	The 'Old Book Value'; "$i" indicates year to go back (1 - 10yrs) ***Default is 10***
		 *		"shares"		-	The number of shares a company has in Millions
		 *		"growthRate"	-	The "Growth Rate" of a company found at 'buyupside.com'
		 *		"tenYearGrowth"	-	The "10 Year Growth Projection" for U.S. Treasury Bonds
		 *		"website"		-	The Company's Website
		 *		"logo"			-	Link to their logo via Wikipedia
		 *
		 */
		
		
		public function __construct($ticker = null, $values = array() ) {
			
			// Set the ticker name
			$this->_setTicker($ticker);
			
			// Determine the values and set them to an array
			$this->_setValues($values);
			
		}
		
		
		
		/**
		*
		*	Creates the class global variable: ticker
		*
		*	Method is called in constructor above
		*
		*	@access			private				[used in the class only]
		*	@var			ticker				[The 2-4 digit code given to each stock as an id]
		*
		*/
		private function _setTicker($ticker) {
			
			$this->ticker = $ticker;
			
		}
		
		
		
		/**
		*
		*	Creates the class global variable: elements; 
		*	This gets changed early on, and therefore isn't as important as ticker
		*
		*	Method is called in constructor above
		*
		*	@access			private				[used in the class only]
		*	@var			elements			[The array the user gives requesting the desired data]
		*
		*/
		private function _setValues($values) {
			
			$this->elements = $values;
			
		}
		
		
		
		/**
		*
		*	Loops the values in the order presented,
		*	and then executes "replaceSiteTags()"
		*
		*	Used by the user after class declaration
		*
		*	@access			public				[used by the user outside class]
		*	@method			loopValues			[assigns values to each user input]
		*	@method			replaceSiteTags		[opens assigned webpages and retrieves selected data]
		*	@return			array				[returns the "$this->value()" array]
		*
		*/
		public function getStockInfo() {
			
			
			// Get an array of all the values
			// Returns "$this->value"
			$this->loopValues($this->elements);
			
			// Replace all of the site tags
			$this->replaceSiteTags($this->value, $this->ticker);
			
			
			// DISPLAYS DATA THAT IS RETURNING
			//	TO THE USER
			// COMMENT OUT WHEN USING IN THE REAL
			#echo "<pre>";
			#print_r($this->value);
			#echo "</pre>";
			
			return $this->value;
			
		}
		
		
		
		
		
		
		private function getTagData($basepage, $key) {
			
			// Load DOM Class
			$this->_loadDOM($basepage);
			
			if (!isset($this->value["exchange"]["text"]) || $this->value["exchange"]["text"] == null) {
				$this->value["exchange"]["text"] = "OTCMKTS";
			}
			
			$this->value[$key]["text"] = $this->phpDOM->find($this->value[$key]["tag"]);
			
			// If "tag" is equal to string
			if (!is_array($this->value[$key]["tag"])) {
				
				// Loop through the given "tag"
				foreach ($this->phpDOM->find($this->value[$key]["tag"]) as $search) {
					
					// Store each value in the value variable/array "text"; grab value innertext
					$this->value[$key]["text"] = $search->innertext;
					
				}
			
			
			
			// "tag" is an array
			} else {
				
				$count = count($this->value[$key]["tag"]);
				
				foreach ($this->value[$key]["tag"] as $value) {
					
					// No sub values
					if ($count == 1) {
						
						// Find the values of the "$this->value[$key]["tag"]" variable
						foreach ($this->phpDOM->find($value) as $val) {
							
							// Set the global value variabl to the innertext of DOM HTML
							$this->value[$key]["text"] = $val->innertext;
							
							// Create a simple variable of text variable above
							$text = $this->value[$key]["text"];
							
							// If there is a constant set for special purposes
							if (isset($this->value[$key]["const"])) {
								// Simplify constant variable
								$const = $this->value[$key]["const"];
								
								// Replace the "text" mulit-array/variable with the corresponding variable
								$this->value[$key]["text"] = preg_replace($this->phpRegex($const), "", $text);
								
							}
						}				
						
					// If there is more than one value in the array
					} else {
						
						
						
					}
					
				}
				
				if (isset($this->value[$key]["special"])) {
							
					// Switch the special array
					$this->phpSpecial($this->value[$key]["special"], $this->value[$key]["tag"], $key);
					
				}
				
				
			}
			
			
			// Close DOM Page/Class
			$this->_unsetDOM();
			
		}
		
		
		
		private function phpSpecial($special, $tag, $key) {
			
			switch ($special) {
				
				
				// The table under the "special" array
				case "table":
					
					// Return nothing if the value isn't 3
					if (count($tag) != 3) {
						return null;
					}
					
					
					foreach ($this->phpDOM->find($tag[0]) as $table) {
						$rowData = array();
						foreach ($table->find('tr') as $row) {
							$flight = array();
							foreach ($row->find('td') as $cell) {
								
								$flight[] = $cell->plaintext;
								
							}
							$rowData[] = $flight;
						}
					}
					$this->value[$key]["text"] = $rowData[$tag[1]][$tag[2]];
					break;
					
					
					
				// The image under the "special array"
				case "image":
					
					// Return nothing if the value isn't 1
					if (count($tag) != 1) {
						return null;
					}
					
					foreach ($this->phpDOM->find($tag[0]) as $img) {
						$imgSrc = $img->getAttribute('href');
					}
					
					$wikipedia = "google.com" . $imgSrc;
					
					
					// LOAD GOOGLE REDIRECT
					$this->_loadDOM($wikipedia);
					
						foreach ($this->phpDOM->find("div._jFe a") as $page) {
							$pg[] = $page->getAttribute("href");
						}
						
					$redirect = $pg[0];
					
					
					// LOAD WIKIPEDIA PAGE
					$this->_loadDOM($redirect);
					
					foreach ($this->phpDOM->find("td.logo a.image") as $wiki) {
						$wikip[] = $wiki->getAttribute("href");
					}
					
					$wikiImage = "https://wikipedia.org" . $wikip[0];
					
					
					// GET WIKIPEDIA IMAGE
					$this->_loadDOM($wikiImage);
					
					
					foreach ($this->phpDOM->find("div.fullImageLink a") as $ifg) {
						$wmg[] = $ifg->getAttribute("href");
					}
					
					$wamge = "https:" . $wmg[0];
					
					$this->value[$key]["text"] = $wamge;
					
					break;
					
				
				
				// GET THE CHILD ELEMENT
				case "childSpan":
					
					if (count($tag) != 3) {
						return null;
					}
					
					foreach ($this->phpDom->find($tag[0]) as $div) {
						$childArray = array();
						foreach ($div->find($tag[2]) as $child) {
							$childArray[] = $child->plaintext;
						}
					}
					$this->value[$key]["text"] = $childArray;
					
					break;
					
					
					
				// WIKIPEDIA VIA GOOGLE SEARCH
				case "wikipedia":
				
					// If Array doesn't contain three iterations, return empty.
					if (count($tag) != 3) {
						return null;
					}
					
					// Grab Wikipedia Link From Google Search
					foreach ($this->phpDOM->find($tag[0]) as $img) {
						$imgSrc = $img->getAttribute('href');
					}
					
					$wikipedia = "google.com" . $imgSrc;
					
					// LOAD GOOGLE REDIRECT
					$this->_loadDOM($wikipedia);
					
						foreach ($this->phpDOM->find("div._jFe a") as $page) {
							$pg[] = $page->getAttribute("href");
						}
						
					$redirect = $pg[0];
					
					// LOAD WIKIPEDIA PAGE
					$this->_loadDOM($redirect);
					
					foreach ($this->phpDOM->find($tag[1]) as $wiki) {
						$wikip[] = $wiki->{$tag[2]};
					}
					
					// Store the value in the "text" sub-array
					$this->value[$key]["text"] = $wikip[0];
					
					break;
					
					
					
				
				
				// If the "special" array doesn't have a correct value
				default:
					return null;
					break;
				
			}
			
		}
		
		
		
		
		private function phpRegex($const) {
			
			switch ($const) {
				
				case 'STRIP_TAGS_CONTENT':
					return "(<([a-z]+)>.*?</\\1>)is";
					break;
					
				default:
					return null;
					break;
				
			}
			
		}
		
		
		
		/**
		*
		*	Method will take in inputs that change the "site code" into a viable, dynamic URL to be opened
		*
		*	Called in "$this->getStockInfo()"
		*
		*	@access				private				[used in the class only]
		*	@param				string				[the "site code" assigned in "loopValues()" below]
		*	@param				string				[The ticker; create on class creation in the constructor]
		*	
		*	@var				value					[the 2nd multi-array value is changed to a correct URL to be opened later]
		*
		*/
		private function replaceSiteTags($vals, $ticker) {
			
			foreach ($vals as $key => $val) {
				
				// Literaly "Switch" the code to a proper URL
				switch ($val["site"]) {
					
					// NASDAQ WEBSITE
					case $val["site"] == "nasdaq":
						$this->value[$key]["site"] = "http://www.nasdaq.com/symbol/" . strtolower($ticker);
						break;
						
					// MORNING STAR
					case $val["site"] == "morningStar":
						$this->value[$key]["site"] = "http://financials.morningstar.com/ratios/r.html?t=" . $ticker;
						break;
						
					// BUYUPSIDE.COM
					case "buyUpside":
						$this->value[$key]["site"] = "http://www.buyupside.com/calculators/dividendgrowthrateinclude.php?symbol=" . $ticker;
						break;
					
					// TREASURY TEN-YEAR NOTE
					case "tenYearNote":
						$this->value[$key]["site"] = "http://www.multpl.com/10-year-treasury-rate/table/by-year";
						break;
						
					// GOOGLE FINANCE
					case "googleFinance":
						$this->value[$key]["site"] = "https://www.google.com/finance?q=" . strtolower($ticker);
						break;
					
					// GOOGLE SEARCH
					case $val["site"] == "googleSearch":
						
						// IF THE EXCHANGE AND TICKER ARE ALREADY SET, LOOK UP ON GOOGLE
						if (isset($this->value["exchange"], $this->ticker)) {
							
							$this->value[$key]["site"] = "https://www.google.com/search?q=" . strtoupper($this->value["exchange"]["text"]) . ":" . strtoupper($ticker);
						
						// EXCHANGE IS NOT SET, LOAD GOOGLE SEARCH FOR JUST TICKER
						} else {
							
							$this->value[$key]["site"] = "https://www.google.com/search?q=" . strtolower($ticker);
						
						}
						
						break;
					
					
					// DEFAULT
					default:
						$this->value[$key]["site"] = null;
						break;
					
				}
				
				
				// Get the data from the tags
				$this->getTagData($this->value[$key]["site"], $key);
				
			}
			
		}
		
		
		
		/**
		*
		*	Loops through each array value called by the user in their code
		*	
		*
		*	Used in the public method "$this->getStockInfo()"
		*
		*	@access			private				[used in the class only]
		*	@param			array				[values from user to be looped]
		*
		*	@var			value					[1st mulit-array is the name of the value the user provided, or the "key";
		*												2nd mulit-array stores certain values:
		*													- "site": a site code that is used in "replaceSiteTags()" to
		*																indicate the site to be queried
		*													- "tag": the php-DOM code that translates into browser HTML/CSS
		*																tag data
		*																																					]
		*
		*/
		private function loopValues($values) {
			
			// If array is empty or contains the tag "all", loop all values
			if ($values == array() || $values == array("all")) {
				
				$values = array("name", "price", "mChange", "pChange", "exchange", "high", "low", "income",
										"dividend", "eps", "equity", "revenue", "payRatio", "cashFlow", "cbv", "obv", "shares",
										"growthRate", "tenYearGrowth");
				
			}
			
			// PRE-DEFINED TAGS
			
			$wikiTag = "div._tXc span a.fl";		# Google Search Tag for Wikipedia
			
			
			// Loop through each value array
			foreach ($values as $key => $value) {
				
				// Switch value to determine the case to assign values
				switch ($value) {
					
					case "fName":
						$this->value["fName"]["site"] = "googleSearch";
						$this->value["fName"]["tag"] = array($wikiTag, "caption.org", "plaintext");
						$this->value["fName"]["special"] = "wikipedia";
						break;
						
					case "sName":
						$this->value["sName"]["site"] = "googleSearch";
						$this->value["sName"]["tag"] = array($wikiTag, "h1.firstHeading", "plaintext");
						$this->value["sName"]["special"] = "wikipedia";
						break;
						
						
					case "price":
						$this->value["price"]["site"] = "nasdaq";
						$this->value["price"]["tag"] = "#qwidget_lastsale";
						break;
						
					case "mChange":
						$this->value["mChange"]["site"] = "nasdaq";
						$this->value["mChange"]["tag"] = "#qwidget_netchange";
						break;
						
					case "pChange":
						$this->value["pChange"]["site"] = "nasdaq";
						$this->value["pChange"]["tag"] = "#qwidget_percent";
						$this->value["pChange"]["function"] = "convertToDecimal";
						break;
						
					case "exchange":
						$this->value["exchange"]["site"] = "nasdaq";
						$this->value["exchange"]["tag"] = array("span#qbar_exchangeLabel");
						$this->value["exchange"]["const"] = STRIP_TAGS_CONTENTS;
						break;
						
					case "high":
						$this->value["high"]["site"] = "nasdaq";
						$this->value["high"]["tag"] = "label#Label3";
						break;
						
					case "low":
						$this->value["low"]["site"] = "nasdaq";
						$this->value["low"]["tag"] = "label#Label1";
						break;
						
					case "income":
						$this->value["income"]["site"] = "morningStar";
						$this->value["income"]["tag"] = array("table.r_table1.text2", 0);
						break;
						
					case "dividend":
						$this->value["dividend"]["site"] = "nasdaq";
						$this->value["dividend"]["tag"] = array("table.widthF div.genTable table", 11, 1);
						$this->value["dividend"]["special"] = "table";
						break;
						
					case "eps":
						$this->value["eps"]["site"] = "morningStar";
						
						break;
						
					case "equity":
						$this->value["equity"]["site"] = "morningStar";
						break;
						
					case "revenue":
						$this->value["revenue"]["site"] = "morningStar";
						$this->value["revenue"]["tag"] = "td[headers='Y9 i0']";
						break;
						
					case "payRatio":
						$this->value["payRatio"]["site"] = "morningStar";
						break;
						
					case "cashFlow":
						$this->value["cashFlow"]["site"] = "morningStar";
						break;
						
					case "cbv":
						$this->value["cbv"]["site"] = "morningStar";
						break;
						
					case "obv":
						$this->value["obv"]["site"] = "morningStar";
						break;
						
					case "shares":
						$this->value["shares"]["site"] = "morningStar";
						break;
						
					case "growthRate":
						$this->value["growthRate"]["site"] = "buyUpside";
						$this->value["growthRate"]["tag"] = array("div.colwrap table", 7, 1);
						$this->value["growthRate"]["special"] = "table";
						break;
						
					case "tenYearGrowth":
						$this->value["tenYearGrowth"]["site"] = "tenYearNote";
						$this->value["tenYearGrowth"]["tag"] = array(".tcol table", 1, 1);
						$this->value["tenYearGrowth"]["special"] = "table";
						break;
						
					case "website":
						$this->value["website"]["site"] = "googleSearch";
						$this->value["website"]["tag"] = array($wikiTag, "span.url a.external", "innertext");
						$this->value["website"]["special"] = "wikipedia";
						break;
						
					case "logo":
						$this->value["logo"]["site"] = "googleSearch";
						$this->value["logo"]["tag"] = array("div._tXc span a.fl");
						$this->value["logo"]["special"] = "image";
						break;
						
					default:
						return;
						break;
					
				}
				
			}
			
		}
		
		
		
		
		/**
		*
		*	Initiate cURL, define options for it, execute connection, terminate connection
		*
		*	Used in "$this->_loadDOM()" below
		*
		*	@access				private				[used in the class only]
		*	@var				website				[parameter in "loadDOM()" function call]
		*
		*/
		private function _curlData($base) {
			
			$curl = curl_init();
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($curl, CURLOPT_URL, $base);
			curl_setopt($curl, CURLOPT_REFERER, $base);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
			$this->website = curl_exec($curl);
			curl_close($curl);
			
		}
		
		
		
		
		/**
		*
		*	Open the Webpage to extract information out of it
		*
		*	Used in "$this->getTagData()"
		*
		*	@access				private				[used in this class only]
		*	@param				string				[string of the website to be queried]
		*
		*	@call				_curlData()			[initiates the built in PHP cURL options]
		*	@var				phpDOM				[class call to "simple_html_dom"]
		*
		*/
		private function _loadDOM($basePage) {
			
			// Load in CURL Data from above
			$this->_curlData($basePage);
				
			// Create a DOM object
			$this->phpDOM = new simple_html_dom();
			// Load HTML from a string
			$this->phpDOM->load($this->website);
			
		}
		
		
		
		/**
		*
		*	Closes DOM extraction
		*
		*	Used in "$this->getTagData()"
		*
		*	@access				private			[used in this class only]
		*	@unset				phpDOM			[terminates connection to site that was previously opened]
		*
		*/
		private function _unsetDOM() {
			
			$this->phpDOM->clear(); 
			unset($this->phpDOM);
			
		}
		
		
		
	}

?>
