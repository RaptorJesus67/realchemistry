<div class='wrapper' ng-controller='Elements as elem'>
	<div class='formWrapper'>
		<h3>Search For Elements</h3>
		<input type="text" name="search" ng-model="searchString" id="searchbar" placeholder='Search...' />
		
	</div>
	
	<section>
		<p>Narrow Search of Element. Click Element to go to it's Wikipedia Page</p>
	</section>
	
	<!-- Element Wrapper -->
	<div class='elementContainer'>
		
		<div class='elementWrapper' ng-repeat='e in elements | searchFor:searchString | orderBy: "num"'>
			<a href='http://en.wikipedia.org/wiki/{{e.name}}' target='_blank'>
				<div class='elementBox' ng-style='getColor(e.group)' ng-attr-title='{{e.group}}'>
					<div id='atomicNumber'>
						<span>{{e.num}}</span>
					</div>
					<div id='elementSymbol'>
						<span>{{e.symbol}}</span>
					</div>
					<div id='elementName'>
						<span>{{e.name}}</span>
					</div>
					<div id='molarMass'>
						<span>{{e.mass}}</span>
					</div>
				</div>
			</a>
		</div>
		
		
	</div>
</div>
