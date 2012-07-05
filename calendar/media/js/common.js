	/**
     * Simple function to refresh a page.
     */
    function calendarUpdate()
    {
        location.reload(true);
    }
    
    /**
     * Resets the filters in a form.
     * This should be renamed to calendarResetFormFilters
     * 
     * @param form
     * @return
     */
    function calendarFormReset(form)
    {
        // loop through form elements
        var str = new Array();
        for(i=0; i<form.elements.length; i++)
        {
            var string = form.elements[i].name;
            if (string.substring(0,6) == 'filter')
            {
                form.elements[i].value = '';
            }
        }
        form.submit();
    }
    
	/**
	 * 
	 * @param {Object} order
	 * @param {Object} dir
	 * @param {Object} task
	 */
	function calendarGridOrdering( order, dir ) 
	{
		var form = document.adminForm;
	     
		form.filter_order.value     = order;
		form.filter_direction.value	= dir;
	
		form.submit();
	}
	
	/**
	 * 
	 * @param id
	 * @param change
	 * @return
	 */
	function calendarGridOrder(id, change) 
	{
		var form = document.adminForm;
		
		form.id.value= id;
		form.order_change.value	= change;
		form.task.value = 'order';
		
		form.submit();
	}
	
    /**
     * Sends form values to server for validation and outputs message returned.
     * Submits form if error flag is not set in response
     * 
     * @param {String} url for performing validation
     * @param {String} form element name
     * @param {String} task being performed
     */
    function calendarFormValidation( url, container, task, form, doModal, msg, onCompleteFunction ) 
    {
    	if (doModal != false) { calendarNewModal(msg); }
    	
        if (task == 'save' || task == 'apply' || task == 'savenew') 
        {
            // loop through form elements and prepare an array of objects for passing to server
            var str = new Array();
            for(i=0; i<form.elements.length; i++)
            {
                postvar = {
                    name : form.elements[i].name,
                    value : form.elements[i].value,
                    checked : form.elements[i].checked,
                    id : form.elements[i].id
                };
                str[i] = postvar;
            }
            
            // execute request to server
            var a = new Request({
                url: url,
                method:"post",
                data:{"elements":JSON.encode(str)},
                onSuccess: function(response){
                    var resp = JSON.decode(response, false);
                    if (resp.error != '1')
                    {
                    	if (doModal != false) { (function() { document.body.removeChild(document.id('calendarModal')); }).delay(500); }
                        if (typeof onCompleteFunction == 'function') {
                            onCompleteFunction();
                        }
                        form.task.value = task;
                        form.submit();
                    } else {
                        if (document.id(container)) { document.id(container).set( 'html', resp.msg); }
                    }
                }
            }).send();
            
        }
            else 
        {
            form.task.value = task;
            form.submit();
        }
    }
    
    /**
     * Submits form using onsubmit if present
     * @param task
     * @return
     */
    function calendarSubmitForm(task)
    {
        document.adminForm.task.value = task;

        if (typeof document.adminForm.onsubmit == "function") 
        {
            document.adminForm.onsubmit();
        }
            else
        {
            document.adminForm.submit();
        }
    }
    
    /**
     * Overriding core submitbutton task to perform our onsubmit function
     * without submitting form afterwards
     * 
     * @param task
     * @return
     */
    function submitbutton(task) 
    {
        if (task) 
        {
            document.adminForm.task.value = task;
        }

        if (typeof document.adminForm.onsubmit == "function") 
        {
            document.adminForm.onsubmit();
        }
            else
        {
            submitform(task);
        }
    }
	
	/**
	 * 
	 * @param {Object} divname
	 * @param {Object} spanname
	 * @param {Object} showtext
	 * @param {Object} hidetext
	 */
	function calendarDisplayDiv (divname, spanname, showtext, hidetext) { 
		var div = document.getElementById(divname);
		var span = document.getElementById(spanname);
	
		if (div.style.display == "none")	{
			div.style.display = "";
			span.innerHTML = hidetext;
		} else {
			div.style.display = "none";
			span.innerHTML = showtext;
		}
	}
	
	/**
	 * 
	 * @param {Object} prefix
	 * @param {Object} newSuffix
	 */
	function calendarSwitchDisplayDiv( prefix, newSuffix )
	{
		var newName = prefix + newSuffix;
		var currentSuffixDiv = document.getElementById('currentSuffix');
		var currentSuffix = currentSuffixDiv.innerHTML;	
		var oldName = prefix + currentSuffix;
		var newDiv = document.getElementById(newName);
		var oldDiv = document.getElementById(oldName);
	
		currentSuffixDiv.innerHTML = newSuffix;
		newDiv.style.display = "";
		oldDiv.style.display = "none";
	}
	
	function calendarShowHideDiv(divname)
	{
		var divObject = document.getElementById(divname);
		if (divObject == null){return;}
		if (divObject.style.display == "none"){
			divObject.style.display = "";
		}
		else{
			divObject.style.display = "none";
		}
	}

	/**
	 * 
	 * @param {String} url to query
	 * @param {String} document element to update after execution
	 * @param {String} form name (optional)
	 * @param {String} msg message for the modal div (optional)
	 */
	function calendarDoTask( url, container, form, msg, doModal, onCompleteFunction ) 
	{
		if (doModal != false) { calendarNewModal(msg); }
		
		
    	// if url is present, do validation
		if (url && form) 
		{	
			// loop through form elements and prepare an array of objects for passing to server
			var str = new Array();
			for(i=0; i<form.elements.length; i++)
			{
				postvar = {
					name : form.elements[i].name,
					value : form.elements[i].value,
					checked : form.elements[i].checked,
					id : form.elements[i].id
				};
				str[i] = postvar;
			}
			
	        var a = new Request({
	            url: url,
	            method:"post",
	            data:{"elements":JSON.encode(str)},
	            onSuccess: function(response){
	                var resp = JSON.decode(response, false);
	                if (document.id(container)) { document.id(container).set( 'html', resp.msg); }
	                if (doModal != false) { (function() { document.body.removeChild(document.id('calendarModal')); }).delay(500); }
	                if (typeof onCompleteFunction == 'function') {
	                    onCompleteFunction();
	                }
	                return true;
	            }
	        }).send();
	        
		}
			else if (url && !form) 
		{
		        var a = new Request({
		            url: url,
		            method:"post",
		            onSuccess: function(response){
		                var resp = JSON.decode(response, false);
		                if (document.id(container)) { document.id(container).set( 'html', resp.msg); }
		                if (doModal != false) { (function() { document.body.removeChild(document.id('calendarModal')); }).delay(500); }
		                if (typeof onCompleteFunction == 'function') {
		                    onCompleteFunction();
		                }
		                return true;
		            }
		        }).send();
		}
		return a;
	}

	/**
	 * 
	 * @param {String} msg message for the modal div (optional)
	 */
	function calendarNewModal (msg)
	{
	    if (typeof window.innerWidth != 'undefined') {
	        var h = window.innerHeight;
	        var w = window.innerWidth;
	    } else {
	        var h = document.documentElement.clientHeight;
	        var w = document.documentElement.clientWidth;
	    }
	    var t = (h / 2) - 15;
	    var l = (w / 2) - 15;
		var i = document.createElement('img');
		var s = window.location.toString();
		var src = com_calendar.jbase + 'media/com_calendar/images/ajax-loader.gif';
		i.src = (s.match(/administrator\/index.php/)) ? '../' + src : src;
		i.style.position = 'absolute';
		i.style.top = t + 'px';
		i.style.left = l + 'px';
		i.style.backgroundColor = '#000000';
		i.style.zIndex = '100001';
		var d = document.createElement('div');
		d.id = 'calendarModal';
		d.style.position = 'fixed';
		d.style.top = '0px';
		d.style.left = '0px';
		d.style.width = w + 'px';
		d.style.height = h + 'px';
		d.style.backgroundColor = '#000000';
		d.style.opacity = 0.5;
		d.style.filter = 'alpha(opacity=50)';
		d.style.zIndex = '100000';
		d.appendChild(i);
	    if (msg != '' && msg != null) {
		    var m = document.createElement('div');
		    m.style.position = 'absolute';
		    m.style.width = '200px';
		    m.style.top = t + 50 + 'px';
		    m.style.left = (w / 2) - 100 + 'px';
		    m.style.textAlign = 'center';
		    m.style.zIndex = '100002';
		    m.style.fontSize = '1.2em';
		    m.style.color = '#ffffff';
		    m.innerHTML = msg;
		    d.appendChild(m);
		}
		document.body.appendChild(d);
	}

	
	/**
	 * Gets the value of a selected radiolist item
	 * 
	 * @param radioObj
	 * @return string
	 */
	function calendarGetCheckedValue(radioObj) 
	{
	    if (!radioObj) { return ""; }
	    
	    var radioLength = radioObj.length;
	    if (radioLength == undefined)
	    {
	        if(radioObj.checked)
	            return radioObj.value;
	        else
	            return "";
	    }
	    
	    for (var i = 0; i < radioLength; i++) 
	    {
	        if(radioObj[i].checked) {
	            return radioObj[i].value;
	        }
	    }
	    return "";
	}
	
    /**
     * 
     * @param id
     * @param container
     * @param msg
     */
    function calendarAddEventInstance( container, msg, refreshVenues ) 
    {
        var url = 'index.php?option=com_calendar&format=raw&view=events&task=addInstance';
        if (calendarDoTask( url, container, document.adminForm, msg, true ))
        {
            if (refreshVenues == 1)
            {
                var url = 'index.php?option=com_calendar&format=raw&view=events&task=refreshVenues';
                if (calendarDoTask( url, 'venues', document.adminForm ))
                {
                    document.adminForm.new_venue_name.value = '';
                }
            }
        }
    }

    /**
     * 
     * @param id
     * @param container
     * @param msg
     */
    function calendarRemoveEventInstance(id, container, msg) 
    {
        var url = 'index.php?option=com_calendar&view=events&task=removeInstance&format=raw&eventinstance_id=' + id;
        calendarDoTask( url, container, document.adminForm, msg, true );
    }
    
    /**
     * 
     * @param divname
     * @param booleanname
     */
    function calendarDisplayDivOnBoolean( divname, booleanname, form )
    {
        var name = 'input[name='+booleanname+']:checked';
        var value = form.getElement(name).value;
        var divObject = document.getElementById(divname);
        if (divObject == null){return;}
        
        if (value == '1') { 
            divObject.style.display = "block";
        } else {
            divObject.style.display = "none";
        }
    }
    
    /**
     * 
     * @param divname
     * @param booleanname
     */
    function calendarDisplayRecurringParams( selectname, form )
    {
        var selected = document.getElementById(selectname).getSelected();
        var value = selected[0].value;
        var divs = $$('repeats_params');
        
        $$('.repeats_params').each(function(el) {
            el.style.display = "none";
        });
        
        var divObject = document.getElementById(value);
        if (divObject == null){return;}
        
        divObject.style.display = "block";
    }
    
    function calendarUpdateCategoriesModule( module_id, container, form, month, year, current_date, view ) 
    {
        var url = 'index.php?option=com_calendar&view=' + view + '&task=loadModuleAjax&format=raw&v=2&element=' + module_id + "&month=" + month + "&year=" + year;
        if (current_date)
        {
            var url = url + "&current_date=" + current_date; 
        }
        calendarDoTask( url, container, form, '', false );
    }
    
    function calendarUpdateCategories( container, form, month, year, current_date, view, module_id, module_container, msg, doModal, onCompleteFunction ) 
    {
        var url = "index.php?option=com_calendar&format=raw&view=" + view + "&task=filtercategories&month=" + month + "&year=" + year + "&v=2&module_id=" + module_id;
        if (current_date)
        {
            var url = url + "&current_date=" + current_date; 
        }
        
        if (doModal != false) { calendarNewModal(msg); }

        // loop through form elements and prepare an array of objects for passing to server
        var str = new Array();
        for(i=0; i<form.elements.length; i++)
        {
            postvar = {
                name : form.elements[i].name,
                value : form.elements[i].value,
                checked : form.elements[i].checked,
                id : form.elements[i].id
            };
            str[i] = postvar;
        }
        
        var a = new Request({
            url: url,
            method:"post",
            data:{"elements":JSON.encode(str)},
            onSuccess: function(response){
                var resp = JSON.decode(response, false);
                if (document.id(container)) { document.id(container).set( 'html', resp.content); }
                if (document.id(module_container)) { document.id(module_container).set( 'html', resp.module); }
            	if (doModal != false) { (function() { document.body.removeChild(document.id('calendarModal')); }).delay(500); }
                if (typeof onCompleteFunction == 'function') {
                    onCompleteFunction();
                }
                
                if (resp.error == '1')
                {
                    return false;
                }
                else
                {
                    return true;
                }
            }
        }).send();
        
        return a;
    }
    
    function calendarChangeDate( module_id, container, form, msg, year, month, current, handler ) 
    {
        var url = 'index.php?option=com_calendar&view=events&task=loadModuleAjax&format=raw&v=2&element=' + module_id + '&year=' + year + '&month=' + month + '&current_date=' + current + '&handler=' + handler;
        calendarDoTask( url, container, form, msg, false );
    }