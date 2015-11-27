(function($) {	
	
	var wccpfCloner = function(){
		this.initialize = function(){			
			$( document ).on( "change", "input[name=quantity]", function() {
				var product_count = $(this).val();
				var fields_count = parseInt( $("#wccpf_fields_clone_count").val() );
				$("#wccpf_fields_clone_count").val( product_count );
				
				if( fields_count < product_count ) {
					for( var i = fields_count + 1; i <= product_count; i++ ) {
						var cloned = $('.wccpf-fields-group:first').clone( true );
						cloned.find("script").remove();				
						cloned.find("div.sp-replacer").remove();
						cloned.find("span.wccpf-fields-group-title-index").html( i );
						
						cloned.find(".wccpf-field").each(function(){
							var name_attr = $(this).attr("name");					
							if( name_attr.indexOf("[]") != -1 ) {
								var temp_name = name_attr.substring( 0, name_attr.lastIndexOf("_") );							
								name_attr = temp_name + "_" + i + "[]";						
							} else {
								name_attr = name_attr.slice( 0, -1 ) + i;
							}
							$(this).attr( "name", name_attr );
						});
						
						$("#wccpf-fields-container").append( cloned );		
						
						setTimeout( function(){ if( typeof( wccpf_init_color_pickers ) == 'function' ) { wccpf_init_color_pickers(); } }, 500 );
					}					
				} else {					
					$("div.wccpf-fields-group:eq("+ ( product_count - 1 ) +")").nextAll().remove();
				}
				
				if( $(this).val() == 1 ) {
		            $(".wccpf-fields-group-title-index").hide();
		        } else {
		            $(".wccpf-fields-group-title-index").show();
		        }
				
			});
			/* Trigger to change event - fix for min product quantity */
			setTimeout( function(){ $( "input[name=quantity]" ).trigger("change"); }, 300 );
		};
	};
	
	var wccpf_cloner_obj = new wccpfCloner();
	wccpf_cloner_obj.initialize();
	
})(jQuery);