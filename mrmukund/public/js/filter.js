$(document).ready(function () {
	/*When document is ready this is called, handles 'going back to deals page(from browser)' condition */
	checkStoreFilters();
	
	/*When filters are clicked. */
	
	$("#store_sort_on").click( function() {
		checkStoreFilters();
	});
	
	$("#store_sort_off").click( function() {
		checkStoreFilters();
	});
	
	$("#deal_sort_today").click( function() {
		checkStoreFilters();
	});
	
	$("#deal_sort_future").click( function() {
		checkStoreFilters();
	});
	
	/*Find the enabled filter(s) and set 'time_type', future deals have end_date >=today and today's
	deals have end_date = today's date. */
	function checkDealTimeFilters() {
		var time_type = null;
		if (document.getElementById("deal_sort_today").checked)
			time_type = document.getElementById("deal_sort_today").value;
		
		if (document.getElementById("deal_sort_future").checked)
			time_type = document.getElementById("deal_sort_future").value;
		
		return time_type;
	}
	
	/*Check Offline and Online filters and also time filters. */
	function checkStoreFilters() {
		var deal_type = null;
		if (document.getElementById("store_sort_on").checked) {
			deal_type = document.getElementById("store_sort_on").value;
			
			if (document.getElementById("store_sort_off").checked)
				deal_type = null;
		} else {
			if (document.getElementById("store_sort_off").checked)
				deal_type = document.getElementById("store_sort_off").value;
		}
		
		if (pageName == "Offline-Deals")
			deal_type = "offline";
		
		if (pageName == "Online-Deals")
			deal_type = "online";
		
		filterDeals(deal_type,checkDealTimeFilters());
	}
	
	/*Callback function would print out appropriate deals, without refreshing the page.
	  If no deal matches the filters, it prints out an appropriate message. */
	function filterDeals(deal_type,time_type) {
		$(".deal_detail").hide();
		$.get(baseURL+'/deal/filter/',
				{'type':deal_type,'time':time_type},
				function(data) {
					
					if (data == null || data.length == 0) {
						$("#deals_list").append("<div class =deal_detail> <b> Sorry, No deals match your filter(s) </b> </div>");
						return;
					}
					
					var i = 0;
					
					for (i = 0; i < data.length; i++) {
						$deal_detail = '<div class="deal_detail">' +
							'<img style="vertical-align:middle" class="store_img" src="' + baseURL+ '/' + data[i].image_url+ '"  ' +  'alt="Deal">' +
							'<b>' + data[i].deal_desc + '</b>' +
							'<a class="view_deal" href= "' + baseURL  + '/viewdeal/dealid=' + data[i].id+ '"> View Deal </a>' +
							'<a class= "view_store" href= "' + data[i].store_url + '">' + data[i].store_name + '</a>' + 
							'</div>';
		
						$("#deals_list").append($deal_detail);
					}
				},
				"json"
			)
				.fail(function() {
					$("#deals_list").append("<div class =deal_detail> <b> Sorry, No deals match your filter(s) </b> </div>");
				});
	}
});