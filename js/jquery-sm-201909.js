/* NUMBER OF SECONDS PER SCALE
* */
var Seconds = {
	"minute": 60,
	"hour": 60 * 60,
	"day": 24 * 60 * 60
}




jQuery.fn.extend({	
	
	stripeTable: function(settings){
		

		var config = {
			"change_hover"  : true
		};
		
		/* OVERWRITE PREDEFINED CONFIGURATION */
		if(settings){
			$.extend(config, settings);
		}
		
		$ob = $(this);
		$ob.find("tr:first").find("td").addClass("linha_head_esq");
		
		$ob.find("tr:not(:first):odd").each(function(){
			
			if(!$(this).hasClass("moved_row")){
				$(this).css("background-color", "#F1F1F1").attr("oldColor", "#F1F1F1");
			}else{
				$(this).attr("oldColor", "#F1F1F1");
			}
			
		});
		
		$ob.find("tr:not(:first):even").each(function(){
			
			if(!$(this).hasClass("moved_row")){
				$(this).css("background-color", "#FFFFFF").attr("oldColor", "#FFFFFF");
			}else{
				$(this).attr("oldColor", "#FFFFFF");
			}
			
		});
		
		/* ADD PADDING AND SPACE CLASS TO ALL LINES */
		$ob.find("tr:not(:first)").find("td").addClass("linha_mei")
		
		$ob.find("tr:not(:first)").each(function(){
			
			if($(this).attr("oldColor") == 'undefined'){
				$(this).attr("oldColor", $(this).css("background-color"));
			}
			
		});
		
		if(config.change_hover){
			$ob.find("tr:not(:first):not(.moved_row)").hover(
				function(){
					$(this)
					.css("background-color", "#B5B5B5");
				},
				function(){
					$(this).css("background-color", $(this).attr("oldColor"));
				}
			);
		}
		
		
		return $ob;
		
	},
	
	/* FUNÇÃO QUE CONVERTE UM VALOR DE SEGUNDOS PASSADO COMO PARÂMETRO PARA 'DIA HORA:MES:SEGUNDOS'
	* */
	formatCountDown: function(strValor){
		
		strValor = (isNaN(strValor)) ? 0 : strValor;
		
		d = parseInt(strValor / Seconds.day);
		h = parseInt((strValor - (d * Seconds.day)) / Seconds.hour); 
		m = parseInt(strValor / Seconds.minute) % Seconds.minute;
		s = (strValor - ((d * Seconds.day) + (h * Seconds.hour))) % Seconds.minute;
		
		d = UTILS.getDayDescription(d);
		h = UTILS.twoFields(h);
		m = UTILS.twoFields(m);
		s = UTILS.twoFields(s);
		
		dateFormat = d +"<br>"+ h +"h "+ m +"m "+ s +"s";
		return $(this).html(dateFormat);
		
	},
	
	countdownSeconds: function(){
	
		$ob__ = $(this);
		$(this).each(function(){
		
			$this = $(this)
			if(typeof($(this).attr("secondRemaining")) == 'undefined'){
				$seconds = $(this).text();
			}else{
				$seconds = $(this).attr("secondRemaining");
			}
			
			if(isNaN($seconds)){
				$seconds = 0;
			}else{
				if($seconds > 0){
					$seconds--;
				}else{
					$seconds = 0;
				}
			}
			
			$now = "";
			if(Seconds.hour * 2 > $seconds && $seconds > 0){
				
				//$now = "Near to...";
				//if($("#"+ $this.attr("changeID")).attr("blink_line") != 'yes')
				//	$("#"+ $this.attr("changeID")).attr("blink_line", "yes");
					
			}
			
			$this
				.formatCountDown($seconds)
				.attr("secondRemaining", $seconds);
			
		});
		
		setTimeout(function(){
				$ob__.countdownSeconds();
			}, 1000);
	
		return $ob__;
	},
	
	
	blinkLines: function($i){
		
		$i = (isNaN($i)) ? 0 : $i;
		$curr = $(this);
		$(this).each(function(){
			
			if(typeof($(this).attr("oldColor")) == 'undefined'){
				$(this).attr("oldColor", $(this).css("background-color"));
			}
			
			
			if($(this).attr("blink_line") == "yes"){
				
				if($i <= 0){
					$(this).css("background-color", "#FFFF00");
				}else{
					$(this).css("background-color", $(this).attr("oldColor") );
				}
				
			}
			
		});
		
		setTimeout(function(){
			
			if($i == 0){
				$i = 1;
			}else{
				$i = 0;
			}
			$curr.blinkLines($i);
		}, 1/2 * 1000);
		
	}
	
	
});

var CONSTANTS = {
	"tasks": {
		"record": "%!Reg!%",
		"field": "%!%"
	},
	
	"requirements": {
		"record": "%!reg_req!%"
	},
	
	"time_in_seconds": {
		"minute": 60,
		"hour": 60 * 60,
		"day": 24 * 60 * 60
	},
	"TZS" : {}
}



/* GENERATE HTMLENTITIES */
String.prototype.htmlentities = function(){
	return String(this)
		.replace(/&/g, '&amp;')
		.replace(/</g, '&lt;')
		.replace(/>/g, '&gt;')
		.replace(/"/g, '&quot;')
		.replace(/ /g, '&nbsp;');
}

/* GENERATE BREAK LINE FOR STRING */
String.prototype.breakLine = function(){
	return String(this).replace(/\n/ig, "<br>\n");	
}

/* CHECK IF THE VALUE IS DATE FORMATED */
String.prototype.isDateFormat = function(){
	
	var check1 = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/\d{4}\ (0[0-9]|1[0-9]|2[0-3])\:([0-5][0-9]|)/;
	var check2 = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/\d{4}\ (0[0-9]|1[0-9]|2[0-3])\:([0-5][0-9]|):([0-5][0-9]|)/;
	
	return check1.test(String(this)) || check2.test(String(this));
		
}

String.prototype.isMySQLDateTimeFormat = function(){
	
	var str = String(this)
	
	
}


/* DEFAULT ALERT MESSAGES */
var MESSAGES = {
	"CHANGE": {
		"INSERT": {
			"CHANGEID_ALREADY_EXIST": "A change with this change ID already exist, it is not possible to create a new one.",
			"NO_CHANGEID": "Please, enter at least a change ID to save the change.",
			"OK": "Change successfully created!",
			"NOK": "Error while trying to create a new change. Please try again!"
		},
		"UPDATE": {
			"OK": "Change successfully updated!",
			"NOK": "Error while trying to update the change. Please try again!",
			"CLOSED_STATE": "You tried to update a change in 'closed state'. This is not possible! If you need to update the change, set it another state different than CLOSED and CANCELED and try again."
		}
		
	},
	"PRECHANGE": {
		"INSERT": {
			"OK": "PRE change successfully created!",
			"NOK": "PRE change could not be saved, please try again!"
		},
		"UPDATE": {
			"OK": "PRE change successfully updated!",
			"NOK": "PRE change could not be updated, please try again!"
		},
		"SAVE_ONLY": {
			"OK": "PRE change successfully saved only. It hasn't been sent do FIS SM yet!",
			"NOK": "Error while trying to save the PRE Change! Please try again."
		},
		"SEND_SM": {
			"OK": "PRE change successfully sent to FIS SM!",
			"NOK": "Error while trying to send the PRE change to FIS SM!"
		},
		"REJECT": {
			"OK": "PRE change successfully REJECTED!",
			"NOK": "Error while trying to REJECT a PRE Change!",
			"ALREADY_REJECTED": "PRE change was already rejected, status will not be changed!"
		},
		"ACCEPT": {
			"OK": "PRE change successfully Accepted!",
			"NOK": "Error while trying to Accept a PRE Change!"
		}
	},
	"tasks": {
		"delete_task": "Are you sure you want to delete the task:\n\n\t- Responsible: %s \n\t- Date From: %s\n\t- Date Until: %s\n\t- Activity: %s\n\t- Time: %s",
		"no_task": "There are no activities.",
		"recalculated_ok": "Tasks have been sucessfully recalculated!",
		"enter_valid_date_recalculate": "Please, enter a valid date with format: 'DD/MM/YYYY HH:MM' to recalculate the tasks!",
		
		"inform": {
			"responsible": "Please inform the responsible!",
			"date_from": "Please inform the 'date from'!",
			"date_until": "Please inform the 'date until'!",
			"description": "Please inform the 'description'!",
			"valid_date": "Please, enter a '%s' date in the format:\n\t date = 'DD/MM/YYYY'\n\t time = 'HH:MM'!"
		},
		
		"table_header": {
			
			// MESSAGES.tasks.table_header.
			"responsible": "Responsible",
			"date_from": "Date From",
			"date_until": "Date Until",
			"duration": "Duration",
			"description": "Description",
			"edit_del": "Edit / Delete",
			"options": "Options",
			"move": "Move",
			"edit": "Edit",
			"delete": "Delete",
			"move_up": "<i class='octicon octicon-triangle-up' title='Move UP'></i>",
			"move_down": "<i class='octicon octicon-triangle-down' title='Move down'></i>",
			"raw_move_up": "<i class='octicon octicon-triangle-up'></i>",
			"raw_move_down": "<i class='octicon octicon-triangle-down'></i>"
			
		},
		
		"remaining_effort": {
			"equal": "Remaining effort time",
			"lower": "Remaining effort time",
			"greater": "Exceeded time in comparison to effort time"
		},
		
		"total_effort": {
			"equal": "Timespan defined in all tasks match with planned effort time.",
			"greater": "Overall tasks time is greater than effort time.",
			"lower": "Overall tasks time is lower than effort time."
		},
		
		"bootstrap_class_alert":{
			"total_tasks": { "equal": "alert-success", "greater": "alert-danger", "lower": "alert-warning"},
			"remaining": { "equal": "alert-success", "greater": "alert-danger", "lower": "alert-success"}
			
		}
		
	},
	
	"parseMsg": function(msg, arr){
		
		for(i = 0; i < arr.length; i++){
			msg = msg.replace("%s", arr[i]);
		}
		
		return msg;
		
	}
}

/* DEFAULT PARSERS */
var UTILS = {

	"loadFieldsFromJSON": function(json){
		
		$.each(json, function(iKey, iVal){
			
			$("#"+ iKey).val(iVal);
			
		});
		
		$(".currencyBRL").each(function(){
			
			$curr_val = $(this).val();
			
			$curr_val = $curr_val
				.replace("R$ ", "")
				.replace(",", "")
				.replace(".", ",");
			
			$(this).val($curr_val);
				
		}).focus().blur();
		
		$(".typeDate").each(function(){
			
			$curr_val = $(this).val();
			if($curr_val != ""){
				$(this).val(  UTILS.toStringFromMySQL($curr_val) );
			}
			
		});
		
	},
	/* POPULATE IDS BASED ON THE JSON ID AND VALUE
	*
	* @param json_rec = a JSON object
	* @json_id_versus_fields = a JSON object with respective key id and json_rec value that id should have. i.e. {"id1": "id_json1"}
	*
	*                          WHERE id1 --> the concerning HTML object ID in the HTML page
	*                                id_json1 --> the property content in the JSON object. This is usually returned by the database objects
	*
	* */
	"processReturn": function(json_rec, id_versus_json_response){
	
		UTILS.recreateUsers("#id_usu_applicant", json_users, json_rec.idusuapplicant);
		UTILS.recreateUsers("#id_usu_supervisor", json_users, json_rec.idususupervisor);
		UTILS.recreateUsers("#monitor_after_implement", json_users, json_rec.monitorafterimplement);
		
		$.each(id_versus_json_response, function(id, val){
			
			if(UTILS.isDefinedAndNotNull(json_rec[val]))
			$("#"+ id).val(json_rec[val]).change();
			
		});
		
		UTILS.checkRequirementsValues("#req_field", ".class_requirements_checkbox");
	},
	"postData": function(json_param){
		

		let obj = json_param.obj;
		let page = json_param.action_page;
		let json = json_param.json_class;
		let return_page = json_param.return_page;
		let method_type = json_param.method_type;
		
		
		
		if(typeof(method_type) == 'undefined') method_type = "POST";
		
		
		$(obj).submit(function( event ){
	
			event.preventDefault();
			
			 $.ajax({
				 url: page,
				 type: method_type,
				 dataType: 'json',
				 cache: false,
				 //contentType: 'application/json',
				 contentType: 'application/json; charset=utf-8',
				 success: function (data) {
					
					alert(data.response_msg);
					
					if(typeof(return_page) != 'undefined'){
						if(data.response_code == 200 || data.response_code == 201){
							
							setTimeout(function(){
								window.location.href = return_page;
							}, 1000 * 3)
						}
					}
	
				 },
				 error: function(){
				 
					
				 
				 },
				 data: JSON.stringify( UTILS.getJSONtoSave(json) )
			 });
			
		});
		
	},
	
	/* READS OBJECTS AN CONVERT THE INPUT CONTENT INTO A JSON OBJECT
	*
	* @param --> objects to be read. It can be a class or an ID.
	*            if the object ID is 'myID' and its content is 'MY CONTENT', the output json would be '{"myID": "MY CONTENT"}'
	* 
	*/
	"getJSONtoSave": function(obj){
		
		var pre_change_arr = {};
		$(obj).each(function(){
			
			id = $(this).attr("id");
			pre_change_arr[ id ] = $(this).val()
			
		});
		return pre_change_arr;
		
	},
	
	"savePreChangeTemporary": function(obj, cookie_name){
		
		var pre_change_arr = UTILS.getJSONtoSave(obj)
		
		if(typeof($.cookie("GLOGAL_NOW")) != 'undefined'){
			
			
			pre_change_arr['GLOBAL_NOW'] = $.cookie("GLOGAL_NOW");
			pre_change_arr['RECORD_TYPE'] = "PRECHANGE";
			
			/* TODO 
			* - ADD AJAX FUNCION TO SAVE THE CHANGE DATA
			* */
			
		}

		
	},
	
	"isUndefined": function(val){
		return typeof(val) == 'undefined';
	},
	
	"isDefined": function(val){
		return !UTILS.isUndefined(val);
	},
	
	"isDefinedAndNotNull": function(val){
		
		var isDef = UTILS.isDefined(val);
		if(isDef){
			if(val != null){
				return true;
			}
		}
		
		return false;
		
	},
	
	"paint": function(bool, objs){
		if(typeof(objs[0]) != 'undefined' && bool) if( !objs[0].hasClass("alert-danger") ) objs[0].addClass("alert-danger");
		if(typeof(objs[1]) != 'undefined' && bool) objs[1].fadeIn();
	},
	
	"unpaint": function(bool, objs){
		if(typeof(objs[0]) != 'undefined' && bool) objs[0].removeClass("alert-danger");
		if(typeof(objs[1]) != 'undefined' && bool) objs[1].fadeOut();
	},
	
	"paintUnpaint": function(bool, objs){
		if(bool) UTILS.paint(bool, objs);
		if(!bool) UTILS.unpaint(!bool, objs);
	},
	
	
	"checkTasksWithTimeStamp": function(json_input){
		
		var json = {
			"exec_oth_from"					: "#exec_oth_from",
			"exec_oth_until"				: "#exec_oth_until",
			"taskstext"						: "#taskstext",
			"exec_from_date_time"			: ".exec_from_date_time",
			"id_button_box_copy_local_from"	: "#id_button_box_copy_local_from",
			"exec_until_date_time"			: ".exec_until_date_time",
			"id_button_box_copy_local_until": "#id_button_box_copy_local_until"
		}
		
		$.extend( json, json_input );
		
		var exec_oth_from = $(json.exec_oth_from);
		var exec_oth_until = $(json.exec_oth_until);
		
		first_task_exec = TASKS.getFirstTask(json.taskstext);
		last_task_exec = TASKS.getLastTask(json.taskstext);
		
		if(first_task_exec != null){
			
			if(exec_oth_from.val() == ""){
				
				UTILS.paint(  (true),  new Array($(json.exec_from_date_time), $(json.id_button_box_copy_local_from)));
				
			}else if(exec_oth_from.val().isDateFormat()){
				
				from_oth   = UTILS.getFormatDateTime(UTILS.toDate(exec_oth_from.val()));
				from_tasks = UTILS.getFormatDateTime(UTILS.toDate(first_task_exec[1]));
				
				UTILS.paintUnpaint((from_oth !=  from_tasks), new Array($(json.exec_from_date_time), $(json.id_button_box_copy_local_from)))

			}
			
		}else{
			
			UTILS.unpaint( (true),  new Array($(json.exec_from_date_time), $(json.id_button_box_copy_local_from)));
		
		}
		
		if(last_task_exec != null){
			
			if(exec_oth_until.val() == ""){
				
				UTILS.paint(  (true),  new Array($(json.exec_until_date_time), $(json.id_button_box_copy_local_until)));
				
			}else if(exec_oth_until.val().isDateFormat()){
				
				until_oth   = UTILS.getFormatDateTime(UTILS.toDate(exec_oth_until.val()));
				until_tasks = UTILS.getFormatDateTime(UTILS.toDate(last_task_exec[2]));
				
				UTILS.paintUnpaint((until_oth !=  until_tasks), new Array($(json.exec_until_date_time), $(json.id_button_box_copy_local_until)));
				
			}

			
		}else{
			UTILS.unpaint( (true),  new Array($(json.exec_until_date_time), $(json.id_button_box_copy_local_until)));
		}
		
	},
	
	
	"parseRequirements": function( json, obj_out){
		
		out = "";
		$.each(json, function(key, item){
			
			out += "<input type='checkbox' value='"+ item.id_requirement +"' name='chk_req' id='req_id_"+ item.id_requirement +"' label='"+ item.de_requirement +"'>";
			
			
		});
		$(obj_out).html(out);
	
	},

	"recreateUsers": function(obj, jsons, user){
		
		UTILS.removeUsers(obj);
		UTILS.appendUser(obj, jsons, user);
		
	},
	"removeUsers": function(obj){
		
		$(obj).find("option:not(:first)").each(function(){
			
			$(this).remove();
			
		});
		
	},
	"appendUser": function(obj, jsons, user){
		
		if(typeof(user) == 'undefined') user = "";
		
		$.each(jsons, function(key, item){
			
			isCurrent = ($.trim(user).toLowerCase() == $.trim(item["id_user"]).toLowerCase());
			
			if(item.status_user != "delete" || isCurrent ){
				$(obj).append("<option value='"+ $.trim(item["id_user"]) +"'>"+ $.trim(item["nome_user"]) +" ("+ $.trim(item["id_user"]) +")</option>");
			}
			
		});
		
	},
	

	//if(typeof(json_option_ativo) != 'undefined') UTILS.appendOptions("#ativo", json_option_ativo, ["idativo", "ativo"]);

	/* void: UTILS.appendOptionsValidationUndefined(strId, json_hash, arr_key_fields) 
	*
	* This function validades if a HASH received is defined and if it is, append the options to the list of values
	* @param: strId --> ID of the selectbox where the values will be appended
	* @param: json_hash --> hash of values to fullfill the selectbox
	* @param: arr_key_fields --> key to be found in the 'json_hash' to fullfill respetievely the value and display name of the <option> in the selectbox
	*
	* */
	"appendOptionsValidationUndefined": function(strId, json_hash, arr_key_fields){

		//if(typeof(json_option_ativo) != 'undefined') UTILS.appendOptions("#ativo", json_option_ativo, ["idativo", "ativo"]);
		if(typeof(json_hash) != 'undefined') UTILS.appendOptions(strId, json_hash, arr_key_fields);

	},
	"appendOptions": function(obj, jsons, array_keys){
		
		
		$.each(jsons, function(key, item){
			
			if(typeof(array_keys) != 'undefined'){
				
 				$(obj).append("<option value='"+ item[array_keys[0]] +"'>"+ item[array_keys[1]] +"</span></option>");
				
			}else{
				
				$(obj).append("<option value='"+ item["0"] +"'>"+ item["1"] +"</option>");
				
			}
			
			
		});
		
		/*
		obj = $(obj);
		
		if(!obj.hasClass("selectpicker")){
			obj.addClass("selectpicker");
		}
		
		if(obj.attr("data-show-subtext") == null){
			
			obj.attr("data-show-subtext", "true");
			
		}
		
		if(obj.attr("data-live-search") == null){
			
			obj.attr("data-live-search", "true");
			
		}
		*/
		
		
	},	
	
	"right": function(str, len){
		
		str = str + "   ";
		return str.substr(str, len);
		
	},
	
	"parseHourToMilis": function(hour){
		
		
		total = UTILS.parseHourToMinute(hour) * 60 * 1000;
		
		tmp = hour.split(":");
		if(typeof(tmp[2]) != 'undefined'){
			total += (parseInt(tmp[2]) * 1000)
		}
		
		return total;
		
	},
	
	"parsePlant2JSON": function(plants){
		
		json = {};
		
		$.each(plants, function(key, item){
			
			
			json[UTILS.right(item.de_plant, 3)] = {"plant": UTILS.right(item.de_plant, 3), "hours": item.de_hour, "type": item.de_hour_type, "hours_milis": UTILS.parseHourToMilis(item.de_hour)}
			
		});
		
		return json;
	
	},
	
	/* MARK THE CHECKBOX BASED ON THE THE VALUE EXISTING IN THE TEXTAREA
	* */
	"checkRequirementsValues": function(input_text, class_to_check){
		
		arr_selected = UTILS.toRequirementsArray( input_text );
		
		$(class_to_check).each(function(){
			
			curr = $(this);
			
			if(curr.attr("type") == "checkbox"){
				
				for(i = 0; i < arr_selected.length; i++){
					
					if(curr.val() == arr_selected[i]){
						curr.attr("checked", true);
					}
					
				}
			}
			
		});
		
	},
	
	"getTZByPlant": function(date_time, fromType){
		
		def_tz = {"plant":"", "hours": "00:00:00", "type": "+", "hours_milis": 0};
		date_time = UTILS.toDate(date_time);
		
		plant_selected = selectedText = $("#id_plant option:selected").text() +"   ";
		plant_selected = plant_selected.substr(0, 3);
		
		if(typeof(CONSTANTS.TZS[plant_selected]) != 'undefined'){
			
			def_tz = CONSTANTS.TZS[plant_selected];
			
			date_time = date_time.getTime();
			
			if(fromType == "bra"){
				if(def_tz.type == "+"){
					date_time = date_time + def_tz.hours_milis;
				}else{
					date_time = date_time - def_tz.hours_milis;
				}
			}else{
				if(def_tz.type == "+"){
					date_time = date_time - def_tz.hours_milis
				}else{
					date_time = date_time + def_tz.hours_milis;
				}
			}
			
			
			date_time = new Date(date_time);
			return date_time;
			
		}else{
			
			return date_time;
		}
		
	},
	
			
	/* CONVERT A SIMPLE CHECKBOX INTO A 'BOOTSTRAP' CHECKBOX
	* required 'attrs' on the simple checkbox:
	*   - id
	*   - name
	*   - value
	*   - label
	*/
	"convertCheckBoxToBootstrap": function(obj, out, myclass){
		
		template_boot_check  = '<div class="col-md-3 mb-3">\n';
		template_boot_check += '<div class="custom-control custom-checkbox">\n';
		template_boot_check += '<input type="checkbox" class="custom-control-input '+ myclass +'" id="#id#" value="#value#" name="#name#">\n';
		template_boot_check += '<label class="custom-control-label" for="#id#">#label#</label>\n';
		template_boot_check += '</div>\n';
		template_boot_check += '</div>\n';
		
		all_out = "";
		
		$(obj).each(function(){
			
			$curr = $(this);
			
			
			$curr.find('input[type="checkbox"]').each(function(){
				
				check = $(this);
				
				this_temp = template_boot_check;
				
				this_temp = this_temp
					.replace(/#id#/ig, check.attr("id"))
					.replace(/#name#/ig, check.attr("name"))
					.replace(/#value#/ig, check.attr("value"))
					.replace(/#label#/ig, check.attr("label"));
				
				
				all_out += this_temp;
				
			
			})
			
		});
		
		//alert(all_out);
		return $(out).html(all_out);
		
		
	},
	
	/* GET THE CONTENT ITSELF OR THE TEXTAREA CONTENT
	* */
	"getGenericContent": function(content){
		
		//CONSTANTS.requirements.record
		
		if(typeof(content) == 'undefined') content = "";
		
		if(UTILS.isObjectString(content)){
			if(content.indexOf("#") != -1){
				content = $(content).val();
			}
		}
		
		return content;
		
	},
	
	/* CONVERT A REQUIREMENT CONTENT INTO AN ARRAY */
	"toRequirementsArray": function(content){
		
		content = UTILS.getGenericContent(content).split(CONSTANTS.requirements.record);
		return content;
		
	},
	
	/* CONVERT AN ARRAY INTO REQUIREMENTS CONTENT */
	"generateRequirementsToStringField": function(content, to){
		
		if(typeof(to) == 'undefined') to = "";
		
		if(!UTILS.isObjectArray(content))
			content = UTILS.toRequirementsArray(content);
			
		if(UTILS.isObjectArray(content)){
			
			if(to != ""){
			
				
				$(to).val(content.join(CONSTANTS.requirements.record));
				
			}else{
				
				return content.join(CONSTANTS.requirements.record);
				
			}
			
		}
		
	},
	
	/* FUNÇÃO PARA RETORNAR O NOME DE DIAS COM PLURAL OU SINGULAR
	* */
	"getDayDescription": function(strDay){
		if(strDay < 10){
			
			if(strDay > 1){
				return strDay +" days";
			}else{
				if(strDay > 0){
					return strDay +" day";
				}else{
					return "0 day";
				}
			}
			
		}else{
			return strDay +" days";
		}
	},
	
	/* RETORNA UM NÚMERO COM DUAS CASAS, QUANDO MENOR QUE 10
	* */
	"twoFields": function(strVal){
		if(strVal < 10){
			return "0"+ strVal;
		}else{
			return strVal;
		}
	},
	
	/* CHECK IF NO POS ARE AS 'UNDEFINED' */
	"allPosOK": function(arr, to){
		
		if(typeof(to) == 'undefined') to = 0;
		
		for(_i = 0; _i < to; _i++){
			
			
			if(typeof(arr[_i]) == 'undefined'){
				return false;
			}
		}
		
		return true;
	},

	/* CHANGE '\n' TO '<BR>' */
	"breakLine": function(text){
		
		return text.replace(/\n/ig, "<br>\n");
		
	},
	
	/* CHECK IF IT IS DATE BY USING THE 'UTILS' */
	"isDateFormat": function(valor){

		var reDateTime = /^((0[1-9]|[12]\d)\/(0[1-9]|1[0-2])|30\/(0[13-9]|1[0-2])|31\/(0[13578]|1[02]))\/\d{4}\ (0[0-9]|1[0-9]|2[0-3])\:([0-5][0-9]|)$/;
		return reDateTime.test(valor);
	
	},
	
	"isObjectDate": function(obj){
		if(typeof(obj) == 'undefined') return false;
		return ( (obj).constructor == Date);
	},
	"isObjectJSON": function(obj){
		if(typeof(obj) == 'undefined') return false;
		return ( (obj).constructor == Object);
	},
	
	"isObjectArray": function(obj){
		if(typeof(obj) == 'undefined') return false;
		return ( (obj).constructor == Array);
	},
	"isObjectString": function(obj){
		if(typeof(obj) == 'undefined') return false;
		return ( (obj).constructor == String);
	},
	
	"zeroFill": function(val){
		if(val < 10) return "0"+ val;
		return val;
	},

	"getDateTimeTimestamp": function(strDate){
		
		var yy = strDate.getYear();
		var mm = UTILS.zeroFill(strDate.getMonth() + 1);
		var dd = UTILS.zeroFill(strDate.getDate());
		
		var hh = UTILS.zeroFill(strDate.getHours());
		var ii = UTILS.zeroFill(strDate.getMinutes());
		var ss = UTILS.zeroFill(strDate.getSeconds());
		
		if(parseInt(yy) < 1000){
			yy = 1900 + parseInt(yy);
		}
		
		return yy +""+ mm +""+ dd +""+ hh +""+ ii +""+ ss;
		
		
	},
	/* FORMAT A DATE OBJECT INTO 'HOUR'*/
	"getHourFromDate": function(strDate){
		
		strDate = strDate;
		
		var hh = UTILS.zeroFill(strDate.getHours());
		var mm = UTILS.zeroFill(strDate.getMinutes());
		
		return hh +":"+ mm;
		
	},

	"getFormatDateTime": function(strDate){
		return UTILS.getFormatFromDate(strDate) +" "+ UTILS.getHourFromDate(strDate);
	},

	/* FORMAT A DATE OBJECT INTO 'DATE'*/
	"getFormatFromDate": function(strDate){
		strDate = strDate;
		
		var yy = strDate.getYear();
		var mm = UTILS.zeroFill(strDate.getMonth() + 1);
		var dd = UTILS.zeroFill(strDate.getDate());
		
		if(parseInt(yy) < 1000){
			yy = 1900 + parseInt(yy);
		}
		
		return dd +"/"+ mm +"/"+ yy;
		
	},

	"parseHourToMinute": function(hour){
		
		minutes = 0;
		
		
		if(hour != ""){
			if(typeof(hour) != undefined){
			
				
				hour = hour.split(":");
				
				if(typeof(hour[0]) != 'undefined'){
					if(typeof(parseInt((hour[0]))) != 'NaN')
						minutes += ( parseInt(hour[0]) * 60 );
				}
				if(typeof(hour[1]) != 'undefined'){
					if(typeof(parseInt((hour[1]))) != 'NaN')
						minutes += ( parseInt(hour[1]) );
				}
			
			}
		}

		
		return minutes;
		
	},
	
	"fromMinutesToHours": function(minutes){
		
		signal = "";
		if(minutes < 0){
			signal = "-";
			minutes = minutes * (-1);
		}		
		
		minutes = minutes * 60;
		
		signal += UTILS.twoFields(UTILS.parseSeconds.getFullHours(minutes));
		signal += ":"+ UTILS.twoFields(UTILS.parseSeconds.getMinutes(minutes));
		
		return signal;
		
	},
	
	
	"compareHours": function(hour1, hour2){
		
		minutes1 = UTILS.parseHourToMinute(hour1);
		minutes2 = UTILS.parseHourToMinute(hour2);
		
		if(minutes1 == minutes2) return "equal";
		if(minutes1 > minutes2) return "greater";
		if(minutes1 < minutes2) return "lower";
		
	},
	
	"toStringFromMySQL": function(date_time){
		
		return UTILS.toDateFromMySQL(date_time, false);
		
	},
	
	"toDateFromMySQL": function(date_time, dateObject){
		
		//console.log("toDateFromMysql() date_time = "+ date_time);
		if(typeof(dateObject) == 'undefined') dateObject = true;
		date_time = date_time.split(" ");
		
		date = date_time[0].split("-");
		date = date[2] +"/"+ date[1] +"/"+ date[0];
		
		time = "";
		if(typeof(date_time[1]) != 'undefined'){
			time = date_time[1].substr(0, 5);
		}
		//console.log("toDateFromMySQL() = "+ time);
		
		if(dateObject){
			if(time == "") time = "00:00";
			return UTILS.toDate(date +" "+ time);
		}else{
			return date + (time != "" ? " "+ time : "");
			
		}
		
	},
	
	/**
 	* Converte uma data em formato de string para um objeto Date.
 	*
 	* @param {string} date - A data deve estar no formato "dd/mm/aaaa HH:mm".
 	* @returns {Date} Retorna um objeto Date correspondente à data fornecida ou `null` se o formato for inválido.
 	*
 	* @throws {Error} Lança um erro se o `dateString` não for uma string válida.
 	*
 	* @example
 	* // Exemplo de uso:
 	* const date = UTILS.toDate("01/01/2024 10:00");
 	* console.log(date);
 	*/
	"toDate": function (date){
		
		if(UTILS.isObjectDate(date)){
			return date;
		}
		
		date = date.split(" ");
		dt = date[0].split("/");
		hr = date[1].split(":");
		
		year = dt[2];
		month = parseInt(dt[1]) - 1;
		day = dt[0];
		
		hours = hr[0];
		minutes = hr[1];
		
		return new Date(year, month, day, hours, minutes);
		
	},

	/* GET A 'DATE' OECJT IN MILIS
	* param 'date': date || string -> formated date
	* */
	"getInMilis": function(date){
		
		if(typeof(date) == 'string') date = UTILS.toDate(date);
		return date.getTime();
		
	},

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Compara duas strings de data e retorna a diferença em milissegundos
	*  retorna long
	* */
	"diffMilisseconds": function(date1, date2){
		
		//console.log("diffMilisseconds(date1, date2) = ("+ date1 +", "+ date2 +")");
		date1 = UTILS.toDate(date1);
		date2 = UTILS.toDate(date2);
		
		diff = date2.getTime() - date1.getTime();
		
		return diff;
		
	},

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Compara duas strings de data e retorna a diferença em segundos
	*  retorna long
	* */
	"diffSeconds": function(date1, date2){
		
		diff = UTILS.diffMilisseconds(date1, date2);
		diff = diff / 1000;
		return diff;
		
	},

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Compara duas strings de data e retorna a diferença em minutos
	*  retorna long
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	"diffMinutes": function(date1, date2){
		diff = UTILS.diffSeconds(date1, date2);
		diff = diff / 60;
		return diff;
	},

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Compara duas strings de data e retorna a diferença em minutos
	*  retorna long
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	"diffHours": function(date1, date2){
		diff = UTILS.diffMinutes(date1, date2);
		diff = diff / 60;
		return diff;
	},

	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *
	* Compara duas strings de data e retorna a diferença em minutos
	*  retorna long
	* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */
	"diffDays": function(date1, date2){
		diff = UTILS.diffHours(date1, date2);
		diff = diff / 24;
		return diff;
	},
	
	/* PARSE SECONDS INTO SECONDS, MINUTES, HOURS, ETC */
	"parseSeconds":{
		
		/* RETURN THE MOD OF SECOND BY MNUTES, EXAMPLE: INPUT 63, OUTPUT 3 */
		"getSeconds": function(seconds){
			return seconds % 60;
		},
		
		/* RETURN THE VALUES OF SECONDS CONVERTED INTO MINUTES RAW */
		"getFullMinutes": function(seconds){
			diff = UTILS.parseSeconds.getSeconds(seconds);
			return ((seconds - diff) / 60);
		},
		
		/* RETURN THE MOD OF MINUTES BY HOURS, EXAMPLE: INPUT 63, OUTPUT 3 */
		"getMinutes": function(seconds){
			diff = UTILS.parseSeconds.getSeconds(seconds);
			return ((seconds - diff) / 60) % 60;
		},
		
		"getFullHours": function(seconds){
			
			sec = UTILS.parseSeconds.getSeconds(seconds);
			min = UTILS.parseSeconds.getMinutes(seconds);
			
			return (( seconds - sec - ( min * 60 ) ) / (60 * 60) );
			
			
		},
		"getHours": function(seconds){
			
			return (UTILS.parseSeconds.getFullHours(seconds) % 24);
			
		},
		"getDays": function(seconds){
			
			
			hours = UTILS.parseSeconds.getFullHours(seconds);
			
			days = 0;
			if(hours >= 24){
				days = (hours - (hours % 24)) / 24;
			}
			
			return days;
			
		}
	},
	
	"diffFormatedWithSeconds": function(date1, date2){
		return UTILS.diffFormated(date1, date2, true);
	},
	
	"diffFormated": function(date1, date2, displaySeconds){
		
		seconds = UTILS.diffSeconds(date1, date2);

		ret_string = "";
		
		has_days = UTILS.parseSeconds.getDays(seconds) > 0;
		has_hours = UTILS.parseSeconds.getHours(seconds) > 0;
		has_minutes = UTILS.parseSeconds.getMinutes(seconds) > 0;
		
		if(has_days){
			
			ret_string += UTILS.getPlural(UTILS.parseSeconds.getDays(seconds), " day", "days") + ", ";
			ret_string += UTILS.getPlural(UTILS.parseSeconds.getHours(seconds), "hour", "hours") +", ";
			
		}else if(has_hours){
		
			ret_string += UTILS.getPlural(UTILS.parseSeconds.getHours(seconds), "hour", "hours") +", ";
			
		}
		ret_string += UTILS.getPlural(UTILS.parseSeconds.getMinutes(seconds), "minute", "minutes")
		
		if(typeof(displaySeconds) == 'undefined') displaySeconds = false;
		
		if(displaySeconds){
			ret_string += ", ";
			ret_string += UTILS.getPlural(UTILS.parseSeconds.getSeconds(seconds), "second", "seconds");
		}
		
		return ret_string;
		
	},
	
	"getPlural": function(val, word1, word2){
		
		word = (val <= 1) ? word1 : word2;
		return val +" "+ word;
		
	},
	
	formatarMoedaBRL: function(valorFloat){
		
		return UTILS.formatMoney(valorFloat, 2, ",", ".");
	},
	formatMoney: function(number, decPlaces, decSep, thouSep) {
		decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
		decSep = typeof decSep === "undefined" ? "." : decSep;
		thouSep = typeof thouSep === "undefined" ? "," : thouSep;
		var sign = number < 0 ? "-" : "";
		var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
		var j = (j = i.length) > 3 ? j % 3 : 0;

		return sign +
			(j ? i.substr(0, j) + thouSep : "") +
			i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
			(decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
	}
	
	
	
}



function toDateAndMiliss2BRAAndOth(date, milis, type){
	
	date = toDate(date);
	
	
	if(milis['type'] == '+'){
		if(type == "bra"){
			date.setTime(date.getTime() - milis['hours_milis']);
		}else{
			date.setTime(date.getTime() + milis['hours_milis']);
		}
	}else{
		if(type == "bra"){
			date.setTime(date.getTime() + milis['hours_milis']);
		}else{
			date.setTime(date.getTime() - milis['hours_milis']);
		}
	}
	

	
	
	return date;
	
}
				
				

var TASKS = {
	
	/* Add a task to a given array
	*
	* addTask(json, array) OR
	* addTask(json, string) OR
	*    --> if `string` has an `#` in its content, then the object is fullfilled
	* addTask(string, string, string, string, array)
	*
	* return array with the object added
	*
	* */
	"addTask": function($responsible, $from, $until, $description, $arr){
		
		toFullFill = false;
		toID = "";
		
		if(UTILS.isObjectJSON($responsible)){
			
			$copy = $responsible;
			
			/* CHECK IF TASKS CONTENT IS 'STRING' IF IT IS, THEN CONVERT TO ARRAY */
			if(UTILS.isObjectString($from)){
			
				/* USER PROVIDED ONE OBJECT ID WHERE THE TASKS ARE IN */
				if($from.indexOf("#") != -1){
					
					toFullFill = true;
					$arr = TASKS.parseFromTasksToArray($($from).val());
					toID = $from;
					
				}else{
				
					$arr = TASKS.parseFromTasksToArray($from);
					
				}
				
			}else{
				$arr = $from;
			}
			
			$responsible = $copy['responsible'];
			$from = $copy['from'];
			$until= $copy['until'];
			$description = $copy['description'];
			
		}
		
		
		$from = UTILS.isObjectDate($from) ? UTILS.getFormatDateTime($from) : $from;
		$until = UTILS.isObjectDate($until) ? UTILS.getFormatDateTime($until) : $until;
		
		var arr_obj = new Array();
		arr_obj.push($responsible);
		arr_obj.push($from);
		arr_obj.push($until);
		arr_obj.push($description);
		
		$arr.push(arr_obj);
		

		
		/* IF OBJECT WAS AN `ID` THEN, FULLFILL THE FIELD */
		if(toFullFill){
			//$(toID).val(TASKS.parseToTasksFromArray($arr))
			TASKS.parseToTasksFromArray($arr, toID);
		}
		
		return $arr;
	
	},
	
	"recalculateTasks": function(json){
	
		var toFullFill = false;
		var targetID = "";
		var objs = json['arr'];
		var new_date = json['date'];
		var responsible = json['responsible'];
		
		if(UTILS.isObjectString(objs)){
			if(objs.indexOf("#") != -1){
				
				targetID = objs;
				toFullFill = true;

				objs = $(objs).val();
				
			}
			
			objs = TASKS.parseFromTasksToArray(objs);
		}
		
		//var new_date = "";
		var new_date_milis = UTILS.getInMilis(new_date);
		var sec_first_date = null;
		var sec_from = null;
		
		for(i = 0; i < objs.length; i++){
			
			curr = objs[i];
			
			/* CHECK FIRST DATE OF TIME */
			if(sec_first_date == null){
				sec_first_date = UTILS.getInMilis(curr[1]);
			}
			
			/* SETTIN THE NEW RESPONSIBLE */
			if(typeof(responsible) != 'undefined'){
				if($.trim(responsible) != ""){
					curr[0] = responsible;
				}
			}
			
			if(i == 0){
			
				sec_from = new_date_milis;
		
			/** IF IT IS NOT FIRST, then generate the real NEXT */
			}else{
				
				sec_from = UTILS.getInMilis(curr[1]);
				sec_from = sec_from - sec_first_date + new_date_milis;
				
			}
			
			sec_to = UTILS.getInMilis(curr[2]);
			sec_to = sec_to - sec_first_date + new_date_milis;
			
			//curr[1] = UTILS.getFormatDateTime(new Date(sec_from));
			curr[1] = UTILS.getFormatDateTime(new Date(sec_from));
			//curr[2] = UTILS.getFormatDateTime(new Date(sec_to));
			curr[2] = UTILS.getFormatDateTime(new Date(sec_to));
			
			objs[i] = curr;
			
		}
		
		if(toFullFill){
		
			TASKS.parseToTasksFromArray(objs, targetID);
			
		}
		
		return objs;
		
	},
	
	"parseToTasksFromArray": function(arr, strID){
		
		
		var local_var = new Array();
		for(i = 0; i < arr.length; i++) local_var.push(arr[i]);
		
		for(i = 0; i < local_var.length; i++){
			
			__curr = local_var[i];
			
			if(__curr != null){
			
				if(UTILS.allPosOK(__curr, 4)){
				
					
					if(UTILS.isObjectDate(__curr[1])) __curr[1] = UTILS.getFormatDateTime(__curr[1]);
					if(UTILS.isObjectDate(__curr[2])) __curr[2] = UTILS.getFormatDateTime(__curr[2]);
					
					__curr = __curr.join(CONSTANTS.tasks.field);
					
					local_var[i] = __curr;
					
				}
				
			}
			
		}
		
		if(typeof(strID) != 'undefined') $(strID).val(local_var.join(CONSTANTS.tasks.record));
		
		return local_var.join(CONSTANTS.tasks.record);
		
	},
	
	"parseFromTasksToArray": function(str){
		
		if(UTILS.isObjectString(str)){
			if(str.indexOf("#") != -1){
				str = $(str).val();
			}
		}
		
		str = str.split(CONSTANTS.tasks.record);
		
		var arr_ret = new Array();
		var local_curr = null;
		
		for(i = 0; i < str.length; i++){
			
			local_curr = str[i].split(CONSTANTS.tasks.field);
			
			if(UTILS.allPosOK(local_curr, 4)){
				
				arr_tmp = new Array();
				
				arr_tmp.push(local_curr[0]);
				arr_tmp.push(UTILS.toDate(local_curr[1]));
				arr_tmp.push(UTILS.toDate(local_curr[2]));
				arr_tmp.push(local_curr[3]);
				
				arr_ret[i] = (arr_tmp);
				
			}
			


		}
		
		
		return arr_ret;
	},
	

	"showTable": function(contentId, showTable, movedRow){
		
		arr = $(contentId).val();
		
		if(UTILS.isObjectString(arr)){
			arr = TASKS.parseFromTasksToArray(arr);
		}
		
		var myTable = "";
		
		var nl = "\n";
		
		//myTable = "<table border='0' cellpadding='0' cellspacing='0' class='table table-striped table-sm table-hover table-bordered' style='max-width: none;'>";
		myTable = "<table border='0' cellpadding='0' cellspacing='0' id='myTasksTable' width='100%' class='table table-bordered table-striped table-sm table-hover'>" + nl;
		myTable += "<thead>" + nl;
		myTable += "<tr>" + nl;
		
		
		myTable += "<th>"+ MESSAGES.tasks.table_header.responsible +"</th>" + nl;
		myTable += "<th>"+ MESSAGES.tasks.table_header.date_from +"</th>" + nl;
		myTable += "<th>"+ MESSAGES.tasks.table_header.date_until +"</th>";
		myTable += "<th>"+ MESSAGES.tasks.table_header.duration +"</th>";
		myTable += "<th>"+ MESSAGES.tasks.table_header.description +"</th>" + nl;
		//myTable += "<th>"+ MESSAGES.tasks.table_header.edit_del +"</th>";
		//myTable += "<th>"+ MESSAGES.tasks.table_header.move +"</th>";
		myTable += "<th style='text-align: center'>"+ MESSAGES.tasks.table_header.options +"</th>" + nl;
		myTable += "</tr>" + nl;
		
		myTable += "</thead>" + nl;
		myTable += "<tbody>" + nl;
		
		$.each(arr, function(i, obj){
			
			from = (UTILS.isObjectDate(obj[1]) ? UTILS.getFormatDateTime(obj[1]) : obj[1]);
			until = (UTILS.isObjectDate(obj[2]) ? UTILS.getFormatDateTime(obj[2]) : obj[2]);

			
			myTable += "<tr>\n";
			
			myTable += "<td style='width: 150px'>"+ obj[0] +"</td>" + nl;
			myTable += "<td style='width: 120px'>"+ from +"</td>" + nl;
			myTable += "<td style='width: 120px'>"+ until +"</td>" + nl;
			myTable += "<td style='width: 100px'>"+ UTILS.diffFormated(from, until) +"</td>\n";
			
			
			
			
			
			myTable += "<td style='word-wrap: break-word;min-width: 200px; max-width: 200px; width: 200px'><a href='#' title='Edit this task' class='tasks_action' contentid='"+ contentId +"' pos='"+ i +"' type='edit' showTable='"+ showTable +"'>"+ obj[3].htmlentities() +"</A></td>" + nl;
			myTable += "<td style='text-align: center; width: 90px'><nobr>\n"+
				"<a href='#' class='tasks_action' contentid='"+ contentId +"' pos='"+ i +"' type='edit' showTable='"+ showTable +"'><i class='octicon octicon-pencil' title='"+ MESSAGES.tasks.table_header.edit +"'></i></a>\n"+
				"<a href='#' class='tasks_action' contentid='"+ contentId +"' pos='"+ i +"' type='delete' showTable='"+ showTable +"'><i class='octicon octicon-x' title='"+ MESSAGES.tasks.table_header.delete +"'></i></a>\n"+
				//"</td><nobr>\n";
				"<nobr>\n";
			
			//myTable += "<td style='text-align: center; width: 100px'><nobr>\n";
			myTable += "<nobr>\n";
			if(arr.length <= 1){
				myTable += MESSAGES.tasks.table_header.raw_move_up + " " + MESSAGES.tasks.table_header.raw_move_down;
			}else{
				
				if(i > 0){
					myTable += ""+
						"<a href='#' class='tasks_action' contentid='"+ contentId +"' pos='"+ i +"' type='move_up' showTable='"+ showTable +"'>"+ MESSAGES.tasks.table_header.move_up +"</a>\n";
				}else{
					myTable += MESSAGES.tasks.table_header.raw_move_up;
				}
				
				myTable += " ";
				
				if(i < arr.length - 1){
					myTable += ""+
						"<a href='#' class='tasks_action' contentid='"+ contentId +"' pos='"+ i +"' type='move_down' showTable='"+ showTable +"'>"+ MESSAGES.tasks.table_header.move_down +"</a>\n";
				}else{
					myTable += MESSAGES.tasks.table_header.raw_move_down;
				}
				
			}
			myTable += "</nobr></td>\n";
			
			myTable += "</tr>\n";
			
		});
		
		
		if(arr.length <= 0){
			myTable += "<tr><td colspan='7'>"+ MESSAGES.tasks.no_task +"</td></tr>";
		}
		
		myTable += "</tbody>";
		myTable += "</table>";
		
		//alert(myTable);
		
		$(showTable).html(myTable);
		
		//myTable
		//setTimeout(function(){
		//var table = new Tabulator("#myTasksTable", {});
		//}, 1000 * 5);
		
	},
	
	
	
	"showTableLegacy": function(contentId, showTable, movedRow){
		
		if(typeof(movedRow) == 'undefined') movedRow = -1;
		
		arr = $(contentId).val();
		
		if(UTILS.isObjectString(arr)){
			arr = TASKS.parseFromTasksToArray(arr);
		}
		
		var myTable = "";
		
		myTable = "<table border='0' cellpadding='0' cellspacing='0' class='activities_table'>";
		myTable += "</thead>";
		myTable += "<tr>";
		
		
		myTable += "<td>"+ MESSAGES.tasks.table_header.responsible +"</td>";
		myTable += "<td>"+ MESSAGES.tasks.table_header.date_from +"</td>";
		myTable += "<td>"+ MESSAGES.tasks.table_header.date_until +"</td>";
		myTable += "<td>"+ MESSAGES.tasks.table_header.duration +"</td>";
		myTable += "<td>"+ MESSAGES.tasks.table_header.description +"</td>";
		myTable += "<td>"+ MESSAGES.tasks.table_header.edit_del +"</td>";
		myTable += "<td>"+ MESSAGES.tasks.table_header.move +"</td>";
		
		myTable += "</thead>";
		myTable += "<tbody>";
		myTable += "</tr>";
		
		$.each(arr, function(i, obj){
			
			from = (UTILS.isObjectDate(obj[1]) ? UTILS.getFormatDateTime(obj[1]) : obj[1]);
			until = (UTILS.isObjectDate(obj[2]) ? UTILS.getFormatDateTime(obj[2]) : obj[2]);
			
			class_task = " task_line";
			class_moved = (movedRow == i ? "moved_row" : "");
			class_style  = (movedRow == i ? " style='background-color: #FFF2B4'" : "");
			
			
			myTable += "<tr class='"+ class_moved + class_task +"' "+ class_style +" id='task_line_"+ i +"'>\n";
			myTable += "<td>"+ obj[0] +"</td>\n";
			
			
			myTable += "<td>"+ from +"</td>\n";
			myTable += "<td>"+ until +"</td>\n";
			myTable += "<td>"+ UTILS.diffFormated(from, until) +"</td>\n";
			
			
			myTable += "<td style='text-align: left'>"+ obj[3].htmlentities().breakLine() +"</td>\n";
			myTable += "<td>\n"+
				"<a href='#' class='tasks_action' contentid='"+ contentId +"' pos='"+ i +"' type='edit' showTable='"+ showTable +"'>"+ MESSAGES.tasks.table_header.edit +"</a>\n"+
				"<a href='#' class='tasks_action' contentid='"+ contentId +"' pos='"+ i +"' type='delete' showTable='"+ showTable +"'>"+ MESSAGES.tasks.table_header.delete +"</a>\n"+
				"</td>\n";
			
			myTable += "<td>\n";
			if(arr.length <= 1){
				myTable += MESSAGES.tasks.table_header.move_up + " | " + MESSAGES.tasks.table_header.move_down;
			}else{
				
				if(i > 0){
					myTable += ""+
						"<a href='#' class='tasks_action' contentid='"+ contentId +"' pos='"+ i +"' type='move_up' showTable='"+ showTable +"'>"+ MESSAGES.tasks.table_header.move_up +"</a>\n";
				}else{
					myTable += MESSAGES.tasks.table_header.move_up;
				}
				
				myTable += " | ";
				
				if(i < arr.length - 1){
					myTable += ""+
						"<a href='#' class='tasks_action' contentid='"+ contentId +"' pos='"+ i +"' type='move_down' showTable='"+ showTable +"'>"+ MESSAGES.tasks.table_header.move_down +"</a>\n";
				}else{
					myTable += MESSAGES.tasks.table_header.move_down;
				}
				
			}
			myTable += "</td>\n";
			
			myTable += "</tr>\n";
			
		});
		
		
		if(arr.length <= 0){
			myTable += "<tr><td colspan='7'>"+ MESSAGES.tasks.no_task +"</td></tr>";
		}
		
		myTable += "</tbody>";
		myTable += "</table>";
		
		$(showTable).html(myTable);
		
	},
	
	"getTaskArray": function(tasks, pos){
		
		if(UTILS.isObjectString(tasks)){
			
			// IF USER PROVIDED THE 'ID', THEN CAPTURE THE TEXT FROM THE ID
			if(tasks.indexOf("#") != -1){
				tasks = TASKS.parseFromTasksToArray($(tasks).val());
			}else{
				tasks = TASKS.parseFromTasksToArray(tasks);
			}
			
		}
		
		if(UTILS.isObjectArray(tasks)){
			
			obj = tasks[pos];
			if(typeof(obj) != 'undefined') return obj;
			
		}
		
		return null;
		
	},
	
	"convertToJSON": function(arr){
		return {
			"responsible": arr[0],
			"from": arr[1],
			"until": arr[2],
			"description": arr[3]			
		};
	},
	"getTaskJSON": function(tasks, pos){
		
		$tasks = TASKS.getTaskArray(tasks, pos);
		
		if($tasks == null) return $tasks;
		return TASKS.convertToJSON($tasks);
		
	},
	
	"updateTask": function(task, arr){
		
		arr_new = arr;
		
		toFullFill = false;
		if(UTILS.isObjectString(arr)){
			if(arr.indexOf("#") != -1){
				arr_new = $(arr).val();
				toFullFill = true;
			}
			
			arr_new = TASKS.parseFromTasksToArray(arr_new);
		}
		
		arr_new[task[4]] = task;
		
		if(toFullFill){
			//$(arr).val( TASKS.parseToTasksFromArray(arr_new) )
			TASKS.parseToTasksFromArray(arr_new, arr);
		}
		
		return arr_new;
		
	},
	
	"getLastTask": function(arr){
	
		if(UTILS.isObjectString(arr)){
			if(arr.indexOf("#") != -1){
				arr = TASKS.parseFromTasksToArray($(arr).val());
			}else{
				arr = TASKS.parseFromTasksToArray(arr);
			}
		}
		
		if(arr.length <= 0) return null;
		
		return arr[ arr.length - 1];
		
	},
	
	"getFirstTask": function(arr){
	
		
		
		if(UTILS.isObjectString(arr)){
			if(arr.indexOf("#") != -1){
				arr = TASKS.parseFromTasksToArray($(arr).val());
			}else{
				arr = TASKS.parseFromTasksToArray(arr);
			}
		}
		
		if(arr.length <= 0) return null;
		
		return arr[0];
		
	},
	
	"moveTask": function(pos, type, arr){
		
		if(typeof(type) == 'undefined') type = '';
		
		var toFullFill = false;
		var ret_new = new Array();
		var ret_id = "";
		var other_pos = -1;
		
		if(UTILS.isObjectString(arr)){
			if(arr.indexOf("#") != -1){
				
				ret_id = arr;
				arr = TASKS.parseFromTasksToArray($(arr).val());
				toFullFill = true;
				
			}else{
				arr = TASKS.parseFromTasksToArray(arr);
			}
	
		}
		
		
		if(type == 'up'){
			other_pos = parseInt(pos) - 1;
		}else if(type == 'down'){
			other_pos = parseInt(pos) + 1;
		}
		
		to_original_task = TASKS.getTaskArray(arr, parseInt(pos));
		to_movable_task = TASKS.getTaskArray(arr, parseInt(other_pos));
		
		if(type == 'up'){
			
			arr[other_pos] = to_original_task;
			arr[pos] = to_movable_task;
			
		}else if(type == 'down'){
			
			arr[other_pos] = to_original_task;
			arr[pos] = to_movable_task;
			
		}
		
		if(toFullFill){
			TASKS.parseToTasksFromArray(arr, ret_id);
		}
		
	},
	
	"removeTask": function(pos, arr){
		
		var toFullFill = false;
		var ret_new = new Array();
		var ret_id = "";
		
		if(UTILS.isObjectString(arr)){
			if(arr.indexOf("#") != -1){
				
				ret_id = arr;
				arr = TASKS.parseFromTasksToArray($(arr).val());
				toFullFill = true;
				
			}else{
				arr = TASKS.parseFromTasksToArray(arr);
			}
			
			
			
		}
		arr[pos] = null;
		
		for(iLocal = 0; iLocal <= arr.length; iLocal++){
			
			
			if(typeof(arr[iLocal]) != 'undefined'){
				if(arr[iLocal] != null){
					ret_new.push(arr[iLocal]);
				}
			}
			
		}
		
		
		if(toFullFill){
			TASKS.parseToTasksFromArray(ret_new, ret_id);
		}
		
		return ret_new;
		
	},
	
	"totalTimeSeconds": function(source){
		
		if(UTILS.isObjectString(source)){
			if(source.indexOf("#") != -1){
				source = $(source).val();
			}
		}
		objs = TASKS.parseFromTasksToArray(source);
		
		
		seconds = 0;
		
		$.each(objs, function(i, obj){
			
			seconds += (UTILS.diffSeconds(obj[1], obj[2]));
			
		});
		
		
		return seconds;
		
	},

	/* RETURNS THE */
	"totalTimeSecondsFormated": function(source, target){
		
		seconds = TASKS.totalTimeSeconds(source);
		
		formated = (UTILS.zeroFill(UTILS.parseSeconds.getFullHours(seconds)) +":"+ UTILS.zeroFill(UTILS.parseSeconds.getMinutes(seconds)));
		
		if(typeof(target) != 'undefined'){
			
			$(target).html(formated);
			$(target).val(formated);
			
		}
		
		return formated;
		
	}
	
	
	
}





jQuery(document).ready(function(){

	var proxied = window.alert;
	window.alert = function() {
	
		modal_screen = '';
		modal_screen += '<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">';
		modal_screen += '<div class="modal-dialog modal-dialog-centered" role="document">';
		modal_screen += '<div class="modal-content">';
		modal_screen += '<div class="modal-header">';
		modal_screen += '<h5 class="modal-title" id="exampleModalLabel" style="font-weight: bold">Modal title</h5>';
		modal_screen += '<button type="button" class="close" data-dismiss="modal" aria-label="Close">';
		modal_screen += '<span aria-hidden="true">&times;</span>';
		modal_screen += '</button>';
		modal_screen += '</div>';
		modal_screen += '<div class="modal-body">';
		modal_screen += '  ...';
		modal_screen += '</div>';
		modal_screen += '<div class="modal-footer">';
		modal_screen += '  <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>';
		//modal_screen += '  <button type="button" class="btn btn-primary">Save changes</button>';
		modal_screen += '</div>';
		modal_screen += '</div>';
		modal_screen += '</div>';
		modal_screen += '</div>';
		modal = $(modal_screen);
		
		if(typeof(arguments[1]) != 'undefined'){
			modal.find(".modal-title").html(arguments[0]);
			modal.find(".modal-body").html(arguments[1]);
		}else{
			modal.find(".modal-title").html("Alerta!");
			modal.find(".modal-body").html(arguments[0]);
		}

		
		modal.modal('show');
	
	}
	
});

