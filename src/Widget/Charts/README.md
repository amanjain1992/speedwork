## CHARTS USAGE 

we can call either flot chart or highchart with the same format of data 
available graph types are line chart , column/bar chart ,pie chart ,live chart 

Usage of various charts with the help of widgets

* Create a Array in defined manner
* call the widget

### Array structure for various graphs
 * for line/Column/bar charts
```PHP
(
		[series] => Array
	    	(
	            [1] => Array
	                (
	                    [categories1] => 6
	                    [categories2] => 4
	                    [categories3] => 3
	                    [categories4] => 10
	                )
	             [2] => Array
	                (
	                    [categories1] => 6
	                    [categories2] => 4
	                    [categories3] => 3
	                    [categories4] => 10
	                )
	            [3] => Array
	                (
	                    [categories1] => 7
	                    [categories2] => 3
	                )
	    	)
	    [categories] => Array
	        (
	            [categories1] => categories1
	            [categories2] => categories2
	            [categories3] => categories3
	            [categories4] => categories4
	        )

	)
```

* for Pie charts
```PHP
(
		[series] =>(
			    [categories1] => Array
			        (
			            [0] => 12
			        )
			    [categories2] => Array
			        (
			            [0] => 38
			        )
			    [categories3] => Array
			        (
			            [0] => 12
			        )
			    [categories4] => Array
			        (
			            [0] => 22
			        )
				)
	[categories] => Array
	        (
	            [categories1] => categories1
	            [categories2] => categories2
	            [categories3] => categories3
	            [categories4] => categories4
	        )
	)
```
