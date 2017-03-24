$(document).ready(function () {

//array for storing colors for different reatings
var colour = ["#008000", "#800080", "#008080", "#DC143C", "#7B68EE","#D2691E","#FF1493", "#191970", "#FFA500", "#00FFFF", "#FFC300","#229954"];
var writereview = true;
//function called to fetch data and render the visualtion
function render(){
var dealid = $("#deal_id").val();
$.ajax({
	type: "POST",
	aync: "false",
	url: baseURL+'/getReviews',
	data: {'dealID': dealid},
	dataType: 'json',
	success: 	function(data){
			var array = $.map(data, function(a){
				var content = "";
				if( $.cookie("f_name") == a.user){
					content = "<a class='edit_review'>Click To Edit</a>";
					writereview = false;
				}
				if($.cookie("f_name") == null || $.cookie("f_name") == ""){
					writereview = false;
				}
				var rating = (parseFloat(a.rating) < 5.5 ? a.rating: 0);
				return {
					label : a.review,
					value : 1+ parseFloat(a.rating),
					rating: rating,
					date: a.date,
					id: a.id,
					user: a.user,
					review: a.fullreview,
					color: colour[a.rating * 2],
					html: content
				}
			})
			//d3 function to render the visualization
			change(array);
	}
});
}

render();

var svg = d3.select("svg")
	.append("g")

svg.append("g")
	.attr("class", "slices");
svg.append("g")
	.attr("class", "labels");
svg.append("g")
	.attr("class", "lines");

var width = 960,
    height = 450,
	radius = Math.min(width, height) / 2;

var pie = d3.layout.pie()
	.sort(null)
	.value(function(d) {
		return d.value;
	});

var arc = d3.svg.arc()
	.outerRadius(radius * 0.8)
	.innerRadius(radius * 0.4);

var outerArc = d3.svg.arc()
	.innerRadius(radius * 0.9)
	.outerRadius(radius * 0.9);

svg.attr("transform", "translate(" + width/2 +","+ height/2+")");

var key = function(d){ return d.data.label; };

d3.select(".randomize")
	.on("click", function(){
		change(randomData());
	});


function change(data) {

	if(writereview){

	var g = svg.append('g')
			.attr('class', 'button')
			//.attr("transform", "translate(" + width/2 +","+ height/2+")");

	var text = g.append('text')
			.text("Write Review");

	button()
		.container(g)
		.text(text)
		.count(0)
		.cb(function() { $('#review_write_div').show(); })();
	}

  var div = d3.select("body").append("div")
    .attr("class", "tooltip")
    .style("opacity", 0);

		//var div = d3.select('.tooltip');

	///* ------- PIE SLICES -------*/
	var slice = svg.select(".slices").selectAll("path.slice")
		.data(pie(data), key);

	slice.enter()
		.insert("path")
		.style("fill", function(d) { return d.data.color; })
		.attr("class", "slice")
		.on("mouseover", function(d){
      div.transition()
                .duration(200)
                .style("opacity", .9);
            div.html("<strong>Rating:</strong> "+ printStars(d.data.rating) + "<br><strong>Review:</strong> " + d.data.review + "<br><strong>User:</strong> " +
											d.data.user + "<br><strong>Date: </strong>" + d.data.date)
                .style("left", (d3.event.pageX) + "px")
                .style("top", (d3.event.pageY - 28) + "px");
    })
		.on("mouseout", function(d) {
            div.transition()
                .duration(500)
                .style("opacity", 0);
        });
//mouseover and mouseout for tooltip function
	slice
		.transition().duration(1000)
		.attrTween("d", function(d) {
			this._current = this._current || d;
			var interpolate = d3.interpolate(this._current, d);
			this._current = interpolate(0);
			return function(t) {
				return arc(interpolate(t));
			};
		})

	slice.exit()
		.remove();

	/* ------- TEXT LABELS -------*/

	var text = svg.select(".labels").selectAll("text")
		.data(pie(data), key);
//modified below to bring up the form for edit and create review
	text.enter()
		.append("text")
		.attr("dy", ".35em")
		.html(function(d) {
			return d.data.label + "   <br>" + d.data.html;
		})
		.on("click", function(d) {

			if($.cookie('f_name') == d.data.user){
				$('div#review_edit_div select').val(parseFloat(d.data.rating));
				$('div#review_edit_div textarea').val(d.data.review);
				$('#review_id').val(d.data.id);
				$('#review_edit_div').show();
			}
		 });

	function midAngle(d){
		return d.startAngle + (d.endAngle - d.startAngle)/2;
	}

	text.transition().duration(1000)
		.attrTween("transform", function(d) {
			this._current = this._current || d;
			var interpolate = d3.interpolate(this._current, d);
			this._current = interpolate(0);
			return function(t) {
				var d2 = interpolate(t);
				var pos = outerArc.centroid(d2);
				pos[0] = radius * (midAngle(d2) < Math.PI ? 1 : -1);
				return "translate("+ pos +")";
			};
		})
		.styleTween("text-anchor", function(d){
			this._current = this._current || d;
			var interpolate = d3.interpolate(this._current, d);
			this._current = interpolate(0);
			return function(t) {
				var d2 = interpolate(t);
				return midAngle(d2) < Math.PI ? "start":"end";
			};
		});

	text.exit()
		.remove();

	/* ------- SLICE TO TEXT POLYLINES -------*/

	var polyline = svg.select(".lines").selectAll("polyline")
		.data(pie(data), key);

	polyline.enter()
		.append("polyline");

	polyline.transition().duration(1000)
		.attrTween("points", function(d){
			this._current = this._current || d;
			var interpolate = d3.interpolate(this._current, d);
			this._current = interpolate(0);
			return function(t) {
				var d2 = interpolate(t);
				var pos = outerArc.centroid(d2);
				pos[0] = radius * 0.95 * (midAngle(d2) < Math.PI ? 1 : -1);
				return [arc.centroid(d2), outerArc.centroid(d2), pos];
			};
		});

	polyline.exit()
		.remove();
};

//function to print the stars for review
function printStars(rating) {
	rating = parseFloat(rating)+0.25;
	var ret = "";
	var i = 0;
	for (i=1; i<= rating; i++) {
		ret += '<i class="fa fa-star" aria-hidden="true"></i>';
	}

	if (i - rating <= 0.5)
		ret += '<i class="fa fa-star-half" aria-hidden="true"></i>';

	return ret;
}

//code to cancel the edit and write review forms
$('#cancel_edit').click(function(){
	$('#review_edit_div').hide();
});

$('#cancel_create').click(function(){
	$('#write_review_form').hide();
});

//code to ask for confirmation before deleting a deal
$('#delete_review').click(function(){
		var confirmation = window.confirm("Are you sure you want to delete?");
		if(!confirmation)
		{
			return false;
		}
});

});
