$(document).ready(function(){	
	$("ul.reorder-gallery").sortable({		
		update: function( event, ui ) {
			updateOrder();
		}
	});  
});
function updateOrder() {	
	var order_string = 'order='+item_order;
	$.ajax({
		type: "GET",
		url: "update_order.php",
		data: order_string,
		cache: false,
		success: function(data){			
		}
	});
}