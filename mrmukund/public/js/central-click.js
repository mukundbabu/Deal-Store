/**
 * central-click.js
 */
d3.svg.BubbleChart.define("central-click", function (options) {
  var self = this;

  self.setup = (function (node) {
    var original = self.setup;
    return function (node) {
      var fn = original.apply(this, arguments);
      self.event.on("click", function(node) {
        if (node.selectAll("text.central-click")[0].length === 1) {
			
			var json_text = JSON.parse(JSON.stringify(self.clickedNode[0]));
			
			if ((json_text[0].__data__).item.json_type == 0) {
				//when central bubble is clicked on categories viz, draw deals of that category
				var category = (json_text[0].__data__).item.text;
				drawDeals(baseURL+'/deals/vizData/category='+category);
			} else {
				
				if ((json_text[0].__data__).item.adddeal_text) {
					//add deal form
					$("#viz_deal_edit").css("display", "none");
					$("#vdealadd_form").trigger('reset');
					$("#viz_deal_add").css("display", "block");
				} else if((json_text[0].__data__).item.edit_deal) {
					//edit deal form
					$("#viz_deal_add").css("display", "none");
					$("#viz_deal_edit").css("display", "block");
					
					$("#vdealedit_form").trigger('reset');
					$('[name="store_name1"]').val((json_text[0].__data__).item.store_name);
					$('[name="category1"]').val((json_text[0].__data__).item.category);
					//console.log((json_text[0].__data__).item.type);
					if ((json_text[0].__data__).item.type=="online") {
						$("input[name=type1][value=online]").attr('checked', true);
						$("input[name=type1][value=offline]").attr('checked', false);
					} else {
						$("input[name=type1][value=online]").attr('checked', false);
						$("input[name=type1][value=offline]").attr('checked', true);
					}
					$('[name="start_date1"]').val((json_text[0].__data__).item.start_date);
					$('[name="end_date1"]').val((json_text[0].__data__).item.end_date);
					$('[name="deal_desc1"]').val((json_text[0].__data__).item.deal_desc);
					$('[name="store_url1"]').val((json_text[0].__data__).item.store_url);
					$('[name="dealid"]').val((json_text[0].__data__).item.id);
				}
			}
        }
      });
      return fn;
    };
  })();

  self.reset = (function (node) {
    var original = self.reset;
    return function (node) {
      var fn = original.apply(this, arguments);
      node.select("text.central-click").remove();
      return fn;
    };
  })();

  self.moveToCentral = (function (node) {
	
	//var self = this;
	
    var original = self.moveToCentral;
    return function (node) {
	
	var central_text = options.text;
	if (self && self.clickedNode != undefined) {
		var json_text = JSON.parse(JSON.stringify(self.clickedNode[0]));
		
		if ((json_text[0].__data__).item.json_type == 1 && (json_text[0].__data__).item.adddeal_text)
			central_text = "Click here to add a deal";
		else if ((json_text[0].__data__).item.json_type == 1 && (json_text[0].__data__).item.edit_deal == 0)
			central_text = "";
	}
	
      var fn = original.apply(this, arguments);
      var transition = self.getTransition().centralNode;
      transition.each("end", function() {
        node.append("text").classed({"central-click": true})
          .attr(options.attr)
          .style(options.style)
          .attr("x", function (d) {return d.cx;})
          .attr("y", function (d) {return d.cy;})
          .text(central_text)
          .style("opacity", 0).transition().duration(self.getOptions().transitDuration / 2).style("opacity", "0.8");
      });
      return fn;
    };
  })();
});