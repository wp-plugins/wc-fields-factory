/**
 * @author  	: Saravana Kumar K
 * @author url 	: http://iamsark.com
 * @url			: http://sarkware.com/
 * @copyrights	: SARKWARE
 * @purpose 	: wccpf Controller Object.
 */
(function($) {	

	var wccpf = function() {
		/* used to holds next request's data (most likely to be transported to server) */
		this.request = null;
		/* used to holds last operation's response from server */
		this.response = null;
		/* to prevetn Ajax conflict. */
		this.ajaxFlaQ = true;
		/*Holds currently selected fields */
		this.activeField = null;
		
		this.initialize = function() {
			this.registerEvents();
		};
		
		this.registerEvents = function() {
			$(document).on( "click", "a.condition-add-rule", this, function(e) {
				e.data.addRule( $(this) );
				e.preventDefault();
			});
			$(document).on( "click", "a.condition-remove-rule", this, function(e) {
				e.data.removeRule( $(this) );
				e.preventDefault();
			});
			$(document).on( "click", "a.condition-add-group", this, function(e) {
				e.data.addRuleGroup( $(this) );
				e.preventDefault();
			});
			$(document).on( "click", "a.wccpf-meta-option-delete", this, function(e) {
				e.data.prepareRequest( "DELETE", "wccpf_fields", { field_key : $(this).attr("data-key") } );
				e.data.dock( "wccpf_fields", $(this) );
				e.preventDefault();
			});
			$(document).on( "change", "#wccpf-field-type-meta-type", this, function(e) {
				e.data.prepareRequest( "GET", "wccpf_meta_fields", { type : $(this).val() } );
				e.data.dock( "wccpf_meta_fields", $(this) );
			});
			$(document).on( "click", ".wccpf-field-label", this, function() {
				$(this).next().find("a.wccpf-meta-option-edit").trigger("click");
			});		
			$(document).on( "click", ".wccpf-meta-option-edit", this, function(e) {
				$(".wccpf-meta-row").removeClass("active");
				$(this).parent().parent().parent().parent().parent().parent().addClass("active");
				
				e.data.prepareRequest( "GET", "wccpf_fields", { field_key : $(this).attr("data-key") } );
				e.data.dock( "wccpf_fields", $(this) );
				
				e.preventDefault();
			});
			$(document).on( "keyup", "#wccpf-field-type-meta-label", this, function(e){
				$( "#wccpf-field-type-meta-name" ).val( e.data.sanitizeStr( $(this).val() ) );			
			});	
			$(document).on( "change", ".wccpf_condition_param", this, function(e) {
				e.data.prepareRequest( "GET", $(this).val(), "" );
				e.data.dock( $(this).val(), $(this) );
			});		
			$(document).on( "click", "a.wccpf-cancel-update-field-btn", this, function(e) {
				$(".wccpf-add-new-field").html("+ Add Field");
				$("#wccpf_fields_factory").attr("POST");
				$("#wccpf-field-factory-footer").hide();
				
				$("#wccpf-field-type-meta-label").val("");
				$("#wccpf-field-type-meta-name").val("");				
				$("#wccpf-field-type-meta-type").trigger("change");
				
				$(".wccpf-meta-row").removeClass("active");
				e.preventDefault();
			});
			$(document).on( "click", "a.wccpf-add-new-field", this, function(e) {
				e.data.onFieldSubmit( $(this) );
				e.preventDefault();
			});
			$(document).on( "submit", "form#post", this, function(e) {			
				return e.data.onPostSubmit( $(this));
			});
		};
		
		this.addRule = function( target ) {
			var ruleTr = $( '<tr></tr>' );
			target.parent().parent().parent().append( ruleTr );
			
			ruleTr.append( '<td><select class="wccpf_condition_param select"><option value="product">Product</option><option value="product_cat">Product Category</option></select></td>' );		
			ruleTr.append( '<td><select class="wccpf_condition_operator select"><option value="==" selected="selected">is equal to</option><option value="!=">is not equal to</option></select></td>' );
			ruleTr.append( '<td class="condition_value_td"></td>' );
			ruleTr.append( '<td class="add"><a href="#" class="condition-add-rule button">and</a></td>' );
			ruleTr.append( '<td class="remove"><a href="#" class="condition-remove-rule wccpf-button-remove"></a></td>' );		
					
			ruleTr.find( "select.wccpf_condition_param" ).trigger( "change" );
		};
		
		this.removeRule = function( target ) {		
			var parentTable = target.parent().parent().parent().parent(),
			rows = parentTable.find( 'tr' );		
			if( rows.size() == 1 ) {
				parentTable.parent().remove();
			} else {
				target.parent().parent().remove();
			}
		}; 
		
		this.addRuleGroup = function( target ) {
			var groupDiv = $( '<div class="wccpf_logic_group"></div>' ),
			groupTable = $( '<table class="wccpf_table wccpf_rules_table"><tbody></tbody></table>' ),
			groupTr = $( '<tr></tr>' );
			
			target.prev().before( groupDiv );
			groupDiv.append( $( '<h4>or</h4>' ) );
			groupTable.append( groupTr );
			groupDiv.append( groupTable );
			
			groupTr.append( '<td><select class="wccpf_condition_param select"><option value="product" selected="selected">Product</option><option value="product_cat">Product Category</option></select></td>' );
			groupTr.append( '<td><select class="wccpf_condition_operator select"><option value="==" selected="selected">is equal to</option><option value="!=">is not equal to</option></select></td>' );
			groupTr.append( '<td class="condition_value_td"></td>' );
			groupTr.append( '<td class="add"><a href="#" class="condition-add-rule button">and</a></td>' );
			groupTr.append( '<td class="remove"><a href="#" class="condition-remove-rule wccpf-button-remove"></a></td>' );
			
			groupTr.find( "select.wccpf_condition_param" ).trigger( "change" );
		};
		
		this.renderSingleView = function( _target ) {
			/* Store meta key in to activeField */
			this.activeField["key"] = _target.attr( "data-key" );
			/* Scroll down to Field Factory Container */
			$('html,body').animate(
				{ scrollTop: $("#wccpf_factory").offset().top - 50  },
		        'slow'
		    );
			/* Update fields with corresponding values */
			$("#wccpf-field-type-meta-label").val( this.unEscapeQuote( this.activeField["label"] ) );
			$("#wccpf-field-type-meta-name").val( this.unEscapeQuote( this.activeField["name"] ) );
			$("#wccpf-field-type-meta-type").val( this.unEscapeQuote( this.activeField["type"] ) );
			
			var me = this;		
			$("#wccpf-field-types-meta-body div.wccpf-field-types-meta").each(function() {
				if( $(this).attr("data-param") == "choices" || $(this).attr("data-param") == "default_value"  || $(this).attr("data-param") == "palettes" ) {
					me.activeField[ $(this).attr("data-param") ] = me.activeField[ $(this).attr("data-param") ].replace( /;/g, "\n" );
				}			
				if( $(this).attr("data-type") == "check" ) {
					var choices = me.activeField[ $(this).attr("data-param") ];				
					for( var i = 0; i < choices.length; i++ ) {					
						$("input[name=wccpf-field-type-meta-"+ $(this).attr("data-param") +"][value="+ choices[i] +"]" ).prop( 'checked', true );	
					}
				} else if( $(this).attr("data-type") == "radio" ) {
					$("input[name=wccpf-field-type-meta-"+ $(this).attr("data-param") +"][value="+ me.activeField[ $(this).attr("data-param") ] +"]" ).prop( 'checked', true );				
				} else {
					$("#wccpf-field-type-meta-"+$(this).attr("data-param")).val( me.unEscapeQuote( me.activeField[ $(this).attr("data-param") ] ) );	
				}
			});		
			
			/* Set Fields Factory mode to PUT */
			$(".wccpf-add-new-field").html("Update");
			$("#wccpf_fields_factory").attr("action", "PUT");
			$("#wccpf-field-factory-footer").show();
			$("#wccpf-field-factory-footer").find( "a.wccpf-meta-option-delete" ).attr( "data-key", _target.attr( "data-key" ) );
		};
		
		this.onFieldSubmit = function( target ) {
			var me = this, 
			payload = {};
			payload.type = me.escapeQuote( $("#wccpf-field-type-meta-type").val() );
			payload.label = me.escapeQuote( $("#wccpf-field-type-meta-label").val() );
			payload.name = me.escapeQuote( $("#wccpf-field-type-meta-name").val() );
			
			$("#wccpf-field-types-meta-body div.wccpf-field-types-meta").each(function() {
				if( $(this).attr("data-type") == "check" ) {			
					payload[ $(this).attr("data-param") ] = $("input[name=wccpf-field-type-meta-"+ $(this).attr("data-param") +"]:checked").map(function() {
					    return this.value;
					}).get();
				} else if( $(this).attr("data-type") == "radio" ) {
					payload[ $(this).attr("data-param") ] = me.escapeQuote( $("input[name=wccpf-field-type-meta-"+ $(this).attr("data-param") +"]:checked" ).val() );			
				} else {				
					payload[ $(this).attr("data-param") ] = me.escapeQuote( $("#wccpf-field-type-meta-"+ $(this).attr("data-param") ).val() );				
					if( $(this).attr("data-param") == "choices" || $(this).attr("data-param") == "default_value" || $(this).attr("data-param") == "palettes" ) {
						payload[ $(this).attr("data-param") ] = payload[ $(this).attr("data-param") ].replace( /\n/g, ";" );
					}
				}
			});	
			
			if( $("#wccpf_fields_factory").attr("action") == "POST" ) {
				payload["order"] = $('.wccpf-meta-row').length;
			} else if( $("#wccpf_fields_factory").attr("action") == "PUT" ) {
				payload["key"] = this.activeField["key"];
				payload["order"] = $('input[name='+ this.activeField["key"] +'-order').val();
			}
			
			this.prepareRequest( $("#wccpf_fields_factory").attr("action"), "wccpf_fields", payload );
			this.dock( "wccpf_fields", $(this) );
		};
		
		this.onPostSubmit = function( _target ) {		
			var rules_group = [];
			$(".wccpf_logic_group").each(function() {
				var rules = [];
				$(this).find("table.wccpf_rules_table tr").each(function() {
					rule = {};
					rule["context"] = $(this).find("select.wccpf_condition_param").val();
					rule["logic"] = $(this).find("select.wccpf_condition_operator").val();
					rule["endpoint"] = $(this).find("select.wccpf_condition_value").val();
					rules.push( rule );
				});
				rules_group.push( rules );
			});		
			
			$("#wccpf_rules").val(JSON.stringify(rules_group));
			return true;
		};	
		
		this.reloadHtml = function( _where ) {
			_where.html( this.response.payload );
		}
		
		/* convert string to url slug */
		this.sanitizeStr = function( str ) {
			return str.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'_');
		};	 
		
		this.escapeQuote = function( str ) {
			str = str.replace( /[']/g, '&#39;' );
			str = str.replace( /["]/g, '&#34;' );
			return str;
		}
		
		this.unEscapeQuote = function( str ) {
			str = str.replace( '&#39;', "'" );
			str = str.replace( '&#34;', '"' );
			return str;
		}
		
		this.prepareRequest = function( _request, _context, _payload ) {
			this.request = {
				request : _request,
				context : _context,
				post 	: wccpf_var.post_id,
				payload : _payload
			};
		};
		
		this.prepareResponse = function( _status, _msg, _data ) {
			this.response = {
				status : _status,
				message : _msg,
				payload : _data
			};
		};
		
		this.dock = function( _action, _target ) {		
			var me = this;
			/* see the ajax handler is free */
			if( !this.ajaxFlaQ ) {
				return;
			}		
			
			$.ajax({  
				type       : "POST",  
				data       : { action : "wccpf_ajax", WCCPF_AJAX_PARAM : JSON.stringify(this.request)},  
				dataType   : "json",  
				url        : wccpf_var.ajaxurl,  
				beforeSend : function(){  				
					/* enable the ajax lock - actually it disable the dock */
					me.ajaxFlaQ = false;				
				},  
				success    : function(data) {				
					/* disable the ajax lock */
					me.ajaxFlaQ = true;				
					me.prepareResponse( data.status, data.message, data.data );		               
	
					/* handle the response and route to appropriate target */
					if( me.response.status ) {
						me.responseHandler( _action, _target );
					} else {
						/* alert the user that some thing went wrong */
						//me.responseHandler( _action, _target );
					}				
				},  
				error      : function(jqXHR, textStatus, errorThrown) {                    
					/* disable the ajax lock */
					me.ajaxFlaQ = true;
				}  
			});		
		};
		
		this.responseHandler = function( _action, _target ){		
			if( _action == "product" ) {
				this.reloadHtml( _target.parent().parent().find("td.condition_value_td") );
			} else if( _action == "product_cat" ) {
				this.reloadHtml( _target.parent().parent().find("td.condition_value_td") );
			} else if( _action == "wccpf_meta_fields" ) {
				this.reloadHtml( $("#wccpf-field-types-meta-body") );
			} else if( _action == "wccpf_fields" ) {			
				if( this.request.request == "GET" ) {	
					this.activeField = JSON.parse( this.response.payload );				
					if( this.activeField["type"] == $("#wccpf-field-type-meta-type").val() ) {
						this.renderSingleView( _target );
					} else {
						this.prepareRequest( "GET", "wccpf_meta_fields", { type : this.activeField["type"] } );
						this.dock( "single", _target );
					}				
				} else {
					if(this.response.status ) {
						/* Set Fields Factory to POST mode, on successfull completeion of any operation */
						$("#wccpf-empty-field-set").hide();
						$("#wccpf-field-factory-footer").hide();
						$(".wccpf-add-new-field").html("+ Add Field");
						$("#wccpf_fields_factory").attr("action","POST");					
					}				
					if( this.request.request == "DELETE" ) {						
						if( $(".wccpf-meta-row").length <= 1 ) {										
							$("#wccpf-empty-field-set").show();
						} else {
							$("#wccpf-empty-field-set").hide();
						}
					}								
					this.reloadHtml( $("#wccpf-fields-set") );				
					$("#wccpf-field-type-meta-label").val("");
					$("#wccpf-field-type-meta-name").val("");				
					$("#wccpf-field-type-meta-type").trigger("change");
				}
			} else if( _action == "single" ) {
				this.reloadHtml( $("#wccpf-field-types-meta-body") );
				this.renderSingleView( _target );
			} 	
		};
	};
		
	$(document).ready( function() {
		$('#wccpf-fields-set').sortable({
			update : function(){
				var order = 0;
				$('.wccpf-meta-row').each(function(){
					$(this).find("input.wccpf-field-order-index").val(order);
					order++;
				});
			}
		});
	});
	
	var wccpfObj = new wccpf();
	wccpfObj.initialize();
	
})(jQuery);