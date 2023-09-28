$('.date-picker').datepicker({
    format: 'dd/mm/yyyy',
    language: "es"
});

$('.estado-usuario').change(function(e) {
	var select = $(this);
	var value = select.val();
	var id = select.parents('tr').data('id');
	if (value != "") {
		$.ajaxSetup({
		   	beforeSend: function() {
		   		select.parent().append('<i id="loader" class="fa fa-spinner fa-spin"></i>');
		   		select.hide();
	   		},
	   		complete: function() {
	   			$('#loader').remove();
	   			select.show();
	   		}
		});
		$.post("/admin/cambiarEstado/"+id, { estado: value } ).done(function( data ) {
			data = $.parseJSON(data);
	        if (data.error == null
	            || data.error == undefined
	            || data.error == ''
	        ) {
	        	select.val(data.estado);
	        } else {
	        	alert(data.error);
	        }
	  	});;
	}
});

$('.estado-animal').change(function(e) {
	var select = $(this);
	var value = select.val();
	var id = select.parents('tr').data('id');
	if (value != "") {
		$.ajaxSetup({
		   	beforeSend: function() {
		   		select.parent().append('<i id="loader" class="fa fa-spinner fa-spin"></i>');
		   		select.hide();
	   		},
	   		complete: function() {
	   			$('#loader').remove();
	   			select.show();
	   		}
		});
		$.post("/viajes/cambiarEstado/"+id, { estado: value } ).done(function( data ) {
			data = $.parseJSON(data);
	        if (data.error == null
	            || data.error == undefined
	            || data.error == ''
	        ) {
	        	select.val(data.estado);
	        } else {
	        	alert(data.error);
	        }
	  	});;
	}
});

