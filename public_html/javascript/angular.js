(function() {
	
	//////////////////////////////////////
	//	FUNCTIONS FOR JS BY PHP.JS		//
	//////////////////////////////////////
	
	// UCFIRST()
	function ucfirst(str) {
		//  discuss at: http://phpjs.org/functions/ucfirst/
		// original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// bugfixed by: Onno Marsman
		// improved by: Brett Zamir (http://brett-zamir.me)
		//   example 1: ucfirst('kevin van zonneveld');
		//   returns 1: 'Kevin van zonneveld'

		str += '';
		var f = str.charAt(0)
		.toUpperCase();
		return f + str.substr(1);
	}
	
	// IMPLODE()
	function implode(glue, pieces) {
		//  discuss at: http://phpjs.org/functions/implode/
		// original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// improved by: Waldo Malqui Silva
		// improved by: Itsacon (http://www.itsacon.net/)
		// bugfixed by: Brett Zamir (http://brett-zamir.me)
		//   example 1: implode(' ', ['Kevin', 'van', 'Zonneveld']);
		//   returns 1: 'Kevin van Zonneveld'
		//   example 2: implode(' ', {first:'Kevin', last: 'van Zonneveld'});
		//   returns 2: 'Kevin van Zonneveld'

		var i = '',
		retVal = '',
		tGlue = '';
		
		if (arguments.length === 1) {
			pieces = glue;
			glue = '';
		}
		
		if (typeof pieces === 'object') {
			
			if (Object.prototype.toString.call(pieces) === '[object Array]') {
				return pieces.join(glue);
			}
			
			for (i in pieces) {
				retVal += tGlue + pieces[i];
				tGlue = glue;
			}
			
			return retVal;
			
		}
		
		return pieces;
		
	}
	
	
	// ISSET()
	function isset() {
		//  discuss at: http://phpjs.org/functions/isset/
		// original by: Kevin van Zonneveld (http://kevin.vanzonneveld.net)
		// improved by: FremyCompany
		// improved by: Onno Marsman
		// improved by: Rafał Kukawski
		//   example 1: isset( undefined, true);
		//   returns 1: false
		//   example 2: isset( 'Kevin van Zonneveld' );
		//   returns 2: true

		var a = arguments,
			l = a.length,
			i = 0,
			undef;

		if (l === 0) {
			throw new Error('Empty isset');
		}

		while (i !== l) {
			if (a[i] === undef || a[i] === null) {
				return false;
			}
			i++;
		}
		return true;
	}

	
	
	
	
	
	
	
	//////////////////////////////////////
	////		MAIN INDEX FOR		  ////
	////    	 	ANGULAR 	  	  ////
	////							  ////
	////							  ////
	////	1.) NAVIGATION			  ////
	////	2.) BAND PAGE			  ////
	////							  ////
	////							  ////
	//////////////////////////////////////
	////	GOTO: Use the listings	  ////
	////	above to navigate page	  ////
	//////////////////////////////////////
	
	
	
	
	//////////////////////////////////////
	//////////////////////////////////////
	/////////	 ANGULAR CODE	  ////////
	//////////////////////////////////////
	//////////////////////////////////////
	
	
	var base_uri = "http://realchemistry.loc/";
	
	
	// ADMIN INTIATION
	var app = angular.module('real-chem', []);

	app.controller('Elements', ['$http', '$scope', function($http, $scope) {
		
		$scope.elements = "";
		
		$http({
				
			method: 'POST',
			url: 'https://raw.githubusercontent.com/diniska/chemistry/master/PeriodicalTable/periodicTable.json'
			
		}).success(function(value) {
			
			$scope.info = value;
			
			// Send to PHP to decode
				$http({
					
					method: 'post',
					url: base_uri + "element",
					data: 'initial=' + JSON.stringify($scope.info),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' } // For AJAX interpretation
					
				// PHP RETURNS ELEMENT INFO
				}).success(function(element) {
					
					$scope.getColor(element);
					$scope.elements = element;
					
				});
			
			
		});
		
		
		
		
		/*
		 * --------------------------------------------------------------
		 * FUNCTION		-	Load all of the Elements Based on Search
		 * --------------------------------------------------------------
		 *	  
		 */
		$("#searchbar").on("bind change keydown", function(){
			
			if ($(this).val() == "") {
			
				$(".elementContainer").html();
				
			}
			
			
			
			$http({
				
				method: 'POST',
				url: 'https://raw.githubusercontent.com/diniska/chemistry/master/PeriodicalTable/periodicTable.json'
				
			}).success(function(value) {
				
				$scope.info = value;
				
				// Send to PHP to decode
				$http({
					
					method: 'post',
					url: base_uri + "element",
					data: 'data=' + JSON.stringify($scope.info)  + "&input=" + $("#searchbar").val(),
					headers: { 'Content-Type': 'application/x-www-form-urlencoded' } // For AJAX interpretation
					
				// PHP RETURNS ELEMENT INFO
				}).success(function(element) {
					
					$scope.getColor(element);
					$scope.elements = element;
					
				});
				
				
				
				
			});
			
		});
		
		
		
		
		$scope.getColor = function(group) {
		
			var type = group;
			
			switch(type) {
				
				case "None":
					return {background: "#8fcc82"};
					break;
				
				case "Noble Gas":
					return {background: "#8e96cb"};
					break;
					
				case "Alkali":
					return {background: "#e52470"};
					break;
					
				case "Alkaline Earth":
					return {background: "#fbae1f"};
					break;
				
				case "Transition Metal":
					return {background: "#f3ec26"};
					break;
				
				case "Semi-Metal":
					return {background: "#c7da3a"};
					break;
					
				case "Basic Metal":
					return {background: "#7bc9d4"};
					break;
					
				case "Lanthanide":
					return {background: "#a26693"};
					break;
					
				case "Actinide":
					return {background: "#d6509d"};
					break;
					
				case "Halogen":
					return {background: "#2fbfc3"};
					break;
					
				case "Non-Metal":
					return {background: "#64a9d7"};
					break;
					
				
			}
			
			
			
			
			
			
		}
		
	}]);
	
	
	app.filter('searchFor', function() {
		
		$("section").remove(".class");
		
		return function(arr, searchString) {
			
			if (!searchString) {
			
				return arr;
				
			}
			
			var result = [];
			var i = 0;
			searchString = searchString.toLowerCase();
			angular.forEach(arr, function(item) {
				
				
				if (item.name.toLowerCase().indexOf(searchString) > -1) {
					
					result.push(arr[i]);
					
				} else if (item.name.toLowerCase().indexOf(searchString) == -1) {
					
					$("section").html("<p>No Results Matched</p>");
					
				}
				
				i++;
				
			});
			
			return result;
			
		};
		
	});
	
	function escapeRegExp(string){
		return string.replace(/([.*+?^=!:${}()|\[\]\/\\])/g, "\\$1");
	}
	
	
	function MyController($scope) {
		$scope.elements = $scope.elements.name;
		
		$scope.search = '';
		var regex;
		$scope.$watch('search', function (value) {
			regex = new RegExp('\\b' + escapeRegExp(value), 'i');
		});
		
		$scope.filterBySearch = function(name) {
			console.log(name);
			if (!$scope.search) return true;
			return regex.test(name);
		};
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
})();	
	
	
