$(document).ready(function () {
	//initial call to draw categories bubble
	drawCategories(baseURL+'/categories/vizData/');
	
	//when cancel is clicked, hide both edit and add deal forms.
	$(".cancel_b").click( function() {
		$("#viz_deal_add").css("display", "none");
		$("#viz_deal_edit").css("display", "none");
		
		//console.log("yo");
		return true;
	});
	
	//when deal delete button is clicked
	$("#vdel_deal").click( function() {
		if (confirm('Are you sure you want to delete this deal?'))
		{
			console.log($('[name="dealid"]').val());
			$.post(
				baseURL+'/dealviz/deldeal/process/',
				{
					'dealid': $('[name="dealid"]').val()
				},
				function(data) {
					console.log("data");
					if(data.success == 'success') {
						// Add successful
						//var deal_name = document.getElementById("deal_name").value;
						alert(" deal has been deleted!");
						
						//hide add deal panel
						$("#viz_deal_edit").css("display", "none");
						
						//redraw categories viz
						drawCategories(baseURL+'/categories/vizData/');
					} else if (data.error != '') {
						alert(data.error); // show error as popup
					}
				},
				'json'
			);
			return true;
		}

		return false;
	});
	
	/*On submitting the viz deal add form*/
	/* Start_date >=today and start_date < end_date are the validations*/
	$("#vdealadd_form").submit( function(e) {
		//prevents default submit action
		e.preventDefault();
		var d1 = document.getElementById("start_date").value;
		var d2 = document.getElementById("end_date").value;

		var today = new Date();
		today = today.toISOString().substring(0, 10);

		if (d1 < today)
		{
			alert("Sorry, you can't post old deals");
			document.getElementById("start_date").value="mm/dd/yyyy";
			$("#start_date").css("border-color","red");

			return false;
		}

		if (d1 > d2)
		{
			alert("Start Date should be before end date");
			document.getElementById("start_date").value="mm/dd/yyyy";
			document.getElementById("end_date").value="mm/dd/yyyy";
			$("#start_date").css("border-color","red");
			$("#end_date").css("border-color","red");

			return false;
		}
		
		$.post(
			baseURL+'/dealviz/adddeal/process/',
			{
				'store_name': $('[name="store_name"]').val(),
				'category': $('[name="category"]').val(),
				'type': $('input[name=type]:checked', '#vdealadd_form').val(),
				'start_date': $('[name="start_date"]').val(),
				'end_date': $('[name="end_date"]').val(),
				'deal_desc': $('[name="deal_desc"]').val(),
				'store_url': $('[name="store_url"]').val()
			},
			function(data) {
				if(data.success == 'success') {
					// Add successful
					//var deal_name = document.getElementById("deal_name").value;
					alert(" deal has been added!");
					
					//hide add deal panel
					$("#viz_deal_add").css("display", "none");
					
					//redraw deals viz
					drawDeals(baseURL+'/deals/vizData/category='+($('[name="category"]').val())); 
				} else if (data.error != '') {
					alert(data.error); // show error as popup
				}
			},
			'json'
		);
		
		return false;
	});
	
	/*On submitting the viz deal edit form*/
	/* start_date < end_date are the validations*/
	$("#vdealedit_form").submit( function(e) {
		//prevents default submit action
		e.preventDefault();
		var d1 = document.getElementById("start_date1").value;
		var d2 = document.getElementById("end_date1").value;

		var today = new Date();
		today = today.toISOString().substring(0, 10);
		
		//console.log("today" + today);
		//console.log("start" + d1);
		//console.log($('[name="start_date1"]').val());

		if (d1 > d2)
		{
			alert("Start Date should be before end date");
			document.getElementById("start_date1").value="mm/dd/yyyy";
			document.getElementById("end_date1").value="mm/dd/yyyy";
			$("#start_date1").css("border-color","red");
			$("#end_date1").css("border-color","red");

			return false;
		}
		
		$.post(
			baseURL+'/dealviz/editdeal/process/',
			{
				'store_name': $('[name="store_name1"]').val(),
				'category': $('[name="category1"]').val(),
				'type': $('input[name=type1]:checked', '#vdealedit_form').val(),
				'start_date': $('[name="start_date1"]').val(),
				'end_date': $('[name="end_date1"]').val(),
				'deal_desc': $('[name="deal_desc1"]').val(),
				'store_url': $('[name="store_url1"]').val(),
				'dealid': $('[name="dealid"]').val()
			},
			function(data) {
				if(data.success == 'success') {
					// Add successful
					//var deal_name = document.getElementById("deal_name").value;
					alert(" deal has been edited!");
					
					//hide edit deal panel
					$("#viz_deal_edit").css("display", "none");
					
					//redraw deals viz
					drawDeals(baseURL+'/deals/vizData/category='+($('[name="category1"]').val())); 
				} else if (data.error != '') {
					alert(data.error); // show error as popup
				}
			},
			'json'
		);
		
		return false;
	});
});

//http://bl.ocks.org/phuonghuynh/54a2f97950feadb45b07
function drawCategories(jsonUrl) {
	//set h2 value
	$('h2#category_name').text("Categories").show();
	//clear svg elements
	$('svg').empty();
	d3.select("svg").remove();
	d3.select(".d3-tip").remove();
	
	d3.json(jsonUrl, function(error,jsonData) {
		if (error) 
			return console.warn(error);
			
		var bubbleChart = new d3.svg.BubbleChart({
		supportResponsive: true,
		//container: => use @default
		size: 600,
		//viewBoxSize: => use @default
		innerRadius: 600 / 3.5,
		//outerRadius: => use @default
		radiusMin: 50,
		//radiusMax: use @default
		//intersectDelta: use @default
		//intersectInc: use @default
		//circleColor: use @default
	
		data: {
		  items: jsonData,
		  eval: function (item) {return item.count;},
		  classed: function (item) {return item.text.split(" ").join("");}
		},
		plugins: [
		 {
			name: "central-click",
			options: {
			  text: "(See all deals in this Category)",
			  style: {
				"font-size": "12px",
				"font-style": "italic",
				"font-family": "Source Sans Pro, sans-serif",
				//"font-weight": "700",
				"text-anchor": "middle",
				"fill": "white"
			  },
			  attr: {dy: "65px"},
			  centralClick: function() {
				
				alert("Here is more details!!");
			  }
			}
		  },
		  {
			name: "lines",
			options: {
			  format: [
				{// Line #0
				  textField: "count",
				  classed: {count: true},
				  style: {
					"font-size": "28px",
					"font-family": "Source Sans Pro, sans-serif",
					"text-anchor": "middle",
					fill: "white"
				  },
				  attr: {
					dy: "0px",
					x: function (d) {return d.cx;},
					y: function (d) {return d.cy;}
				  }
				},
				{// Line #1
				  textField: "text",
				  classed: {text: true},
				  style: {
					"font-size": "14px",
					"font-family": "Source Sans Pro, sans-serif",
					"text-anchor": "middle",
					fill: "white"
				  },
				  attr: {
					dy: "20px",
					x: function (d) {return d.cx;},
					y: function (d) {return d.cy;}
				  }
				}
			  ],
			  centralFormat: [
				{// Line #0
				  style: {"font-size": "50px"},
				  attr: {}
				},
				{// Line #1
				  style: {"font-size": "30px"},
				  attr: {dy: "40px"}
				}
			  ]
			}
		  }]
		});
	});	
	
}

//deals viz-similar to above with central text being different.
function drawDeals(jsonUrl) {
	$('h2#category_name').text("Deals").show();
	$('svg').empty();
	d3.select("svg").remove();
	d3.select(".d3-tip").remove();
	
	d3.json(jsonUrl, function(error,jsonData) {
		if (error) 
			return console.warn(error);
			
		var bubbleChart = new d3.svg.BubbleChart({
		supportResponsive: true,
		//container: => use @default
		size: 600,
		//viewBoxSize: => use @default
		innerRadius: 600 / 3.5,
		//outerRadius: => use @default
		radiusMin: 50,
		//radiusMax: use @default
		//intersectDelta: use @default
		//intersectInc: use @default
		//circleColor: use @default
	
		data: {
		  items: jsonData,
		  eval: function (item) {return (item.count);},
		  classed: function (item) {return item.text.split(" ").join("");}
		},
		plugins: [
		 {
			name: "central-click",
			options: {
			  text: "(Modify this deal)",
			  style: {
				"font-size": "12px",
				"font-style": "italic",
				"font-family": "Source Sans Pro, sans-serif",
				//"font-weight": "700",
				"text-anchor": "middle",
				"fill": "white"
			  },
			  attr: {dy: "65px"},
			  centralClick: function() {
				
				alert("Here is more details!!");
			  }
			}
		  },
		  {
			name: "lines",
			options: {
			  format: [
				{// Line #0
				  textField: "count",
				  classed: {count: true},
				  style: {
					"font-size": "28px",
					"font-family": "Source Sans Pro, sans-serif",
					"text-anchor": "middle",
					fill: "white"
				  },
				  attr: {
					dy: "0px",
					x: function (d) {return d.cx;},
					y: function (d) {return d.cy;}
				  }
				},
				{// Line #1
				  textField: "text",
				  classed: {text: true},
				  style: {
					"font-size": "14px",
					"font-family": "Source Sans Pro, sans-serif",
					"text-anchor": "middle",
					fill: "white"
				  },
				  attr: {
					dy: "20px",
					x: function (d) {return d.cx;},
					y: function (d) {return d.cy;}
				  }
				}
			  ],
			  centralFormat: [
				{// Line #0
				  style: {"font-size": "50px"},
				  attr: {}
				},
				{// Line #1
				  style: {"font-size": "30px"},
				  attr: {dy: "40px"}
				}
			  ]
			}
		  }]
		});
	});	
	
}