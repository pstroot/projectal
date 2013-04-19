// the widget definition, where "pstroot" is the namespace,
// "RecordAdder" the widget name
$.widget( "pstroot.RecordSortable", {
	    
				
	options: {
		dbTable: 'archives',
		dbIdLabel: 'id',	
		dbOrderLabel: 'theOrder',		
	},
	
	_create: function() {
		
		
		
    },
	_init: function() {
		var self = this, o = this.options, e = this.element;
		
		e.tableDnD({
			onDragClass: "myDragClass",
			onDrop: function(table, row) {
				self.reorder(table);
			},
			//dragHandle: "dragHandle"
		});
	},
	
	reorder: function(table) {
		var self = this, o = this.options, e = this.element;
		var rows = table.tBodies[0].rows;
		var newOrder = new Array();
		var alertMsg = "";
		for (var i=0; i<rows.length; i++) {
			var theID = rows[i].id.replace("row_","");
			alertMsg += theID + " : " + i + "\n"
			newOrder.push({id:theID, order:i});
		}
		//alert(alertMsg);
		
		var postData = {
			action : "reorder",
			idLabel : this.options.dbIdLabel,
			orderLabel : this.options.dbOrderLabel,
			table : this.options.dbTable, 
			data : (newOrder)
		};
		self._showLoadingAnimation();
		$.post('includes/ReorderDatabase.php',postData, function(data) {
			self._hideLoadingAnimation();			
			if(data["result"] == "success"){
				self._trigger("success");				
				for (var i in newOrder) {
					e.find('tr#'+String(newOrder[i]["id"]) + ' td #order').text(newOrder[i]["order"]);
				}

			} else if (data["result"] == "error"){
				self._trigger("error",null,{ msg:data["errors"].join("\n"), details:data["detail"] });
			}	
			self._trigger("complete");		
		}, "json");
	},
	
	
	_showLoadingAnimation: function(val){
		this.element.find('td').css({'opacity':'.5'});
	},
	_hideLoadingAnimation: function(val){	
		this.element.find('td').css({'opacity':'1'});
	},
	
	
	_destroy : function() {
	} ,
	
	_refresh: function() {
		alert("Refresh recordSortable")
		this.reorder(this.element)		
    },
	
	
});
	
			
	
	
