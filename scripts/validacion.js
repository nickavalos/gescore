
/*
Mioo LeValidate v1.25
modified: Nick Avalos on Date: 05/05/2013
2011 ï¿½ scripts.mioo.sk
 */
var validate = function(jQuery,window,document,undefined){
	"use strict";
	var pluginName='LeValidate',
	defaults={
		formMsgContainer:".form_message",
		notifyBox:"le-notification",
		showNotification:true,
		type:"box",
		onEvent:"keyup",
		singleMsg:false,
		position:"right",
	
		errorMessages:{
			_default:function(){
				return"Incorrect value";
			},
			require:function(){
				return"Required field";
			},
			maxLength:function(val){
				return"Maximum field length is "+val+" characters";
			},
			minLength: function(val){
				return"Minimum field length is "+val+" characters";
			},
			email:function(){
				return"Incorrect email format";
			},
			number:function(){
				return"Number allowed only";},
			text:function(){
				return"Text allowed only";
			},
			max:function(val){
				return"Maximum value is "+val;
			},
			min:function(val){
				return"Minimum value is "+val;
			},
			date:function(){
				return"Incorrect date";
			},
			dateMayorQueIni:function(val){
            	return"End date less than " + $(val).val();
            },
			url:function(){
				return"Incorrect url format";
			},
			passwordAgain:function(){
				return"Passwords does not match";
			}
		}
	},
	regExp={
		email:new RegExp(/^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/),
		text:new RegExp(/[^a-zA-Z]/),
		url:new RegExp(/(ftp|http|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/)
	},
	
	ico={visible:{'background-position':'98%'},hidden:{'background-position':'113%'}};
	
	function LeValidate(element,options){
		this.element=element;
		this.$element=jQuery(this.element);
		this.options=jQuery.extend(true,{},defaults,options);
		this._defaults=defaults;
		this._name=pluginName;
		this.$inputs=this.$element.find("input[type=text], input[type=password]");
		this.formValid=false;
		this.init();
	}

	LeValidate.prototype={
		validators:{
			optional:function(val){},
			require:function(val){return val.length>0;},
			email:function(val){return!this.validators.require(val)||regExp.email.test(val);},
			number:function(val){
				try{
					return!this.validators.require(val)||jQuery.isNumeric(val);
				}
				catch(err){
					return!this.validators.require(val)||!isNaN(val);
				}
			},
			text:function(val){
				return!this.validators.require(val)||!regExp.text.test(val);
			},
			maxLength:function(val,arg){
				return!this.validators.require(val)||val.length<=arg;
			},
			minLength:function(val,arg){
				return!this.validators.require(val)||val.length>=arg;
			},
			max:function(val,arg){
				return!this.validators.require(val)||parseInt(val,10)<=arg;
			},
			min:function(val,arg){
				return!this.validators.require(val)||parseInt(val,10)>=arg;
			},
			date:function(val) {
				var currVal = val;
				if(currVal == '')
					return true;
				
				//var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{4})$/; //Acepta / y -
				var rxDatePattern = /^(\d{1,2})(\/)(\d{1,2})(\/)(\d{4})$/; //Acepta /
				var dtArray = currVal.match(rxDatePattern); // formato ok?
				
				if (dtArray == null) return false;

				//formato dd/mm/yyyy.
				var dtDay= parseInt(dtArray[1]);
				var dtMonth = parseInt(dtArray[3]);
				var dtYear = parseInt(dtArray[5]);

                var today = new Date();
                var fecha = new Date(dtYear, dtMonth -1, dtDay + 1);
                if (fecha < today) return false;

				if (dtMonth < 1 || dtMonth > 12) return false;
				else if (dtDay < 1 || dtDay> 31) return false;
				else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31) return false;
				else if (dtMonth == 2) {
					var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
					if (dtDay> 29 || (dtDay ==29 && !isleap)) return false;
				}
				return!this.validators.require(val) || true;
			},
			dateMayorQueIni:function(val, arg) {
			    arg = $(arg).val();
			    return isDateMenorQue(arg, val);
			},
			url:function(val){
				return!this.validators.require(val)||regExp.url.test(val);
			},
			passwordAgain:function(val){
				return!this.validators.require(val)||(this.$inputs.filter(".password").val()===val);
			}
		},
		init:function(){
			if(this.options.type==="ico"){
				this.$inputs.css(ico.hidden);
			}
			try{
				this.$element.on("submit",{self:this},this.formSubmit);
			}
			catch(err){
				this.$element.bind("submit",{self:this},this.formSubmit);
			}
			this.setHtmlValidation();
			var self=this;
		},
		isFormValid:function() {
            return this.formValid;
        },
		formSubmit:function(e){
			e.data.self.formValid=true;
			e.data.self.$inputs.trigger(e.data.self.options.onEvent);
			if(e.data.self.formValid===false){
				e.data.self.formMsgToggle(true);
				return false;
			}
		},
		formMsgToggle:function(show){
			var el=this.$element.find(this.options.formMsgContainer);
			if(show) el.fadeIn(150);
			else el.fadeOut(200);
		},
		setHtmlValidation:function(){
			var argument,fn,self=this;
			jQuery.each(this.$inputs,function(i,el){
				fn=[];
				var el_class;
				if(typeof jQuery(el).attr("class")==="undefined") el_class=[];
				else el_class=jQuery(el).attr("class").split(" ");
				jQuery.each(el_class,function(key,val){
					argument="";
					if(val.indexOf("-")){
						argument=val.split("-")[1];
						val=val.split("-")[0];
					}
					if(val in self.validators){
						fn.push({val:val,arg:argument});
					}
				});
				try{
					jQuery(el).on(self.options.onEvent,{obj:fn},function(e){
						self.doValidation(e.data.obj,jQuery(this));
					});
				}
				catch(err){
					jQuery(el).bind(self.options.onEvent,{obj:fn},function(e){
						self.doValidation(e.data.obj,jQuery(this));
					});
				}
			});
		},
		doValidation:function(fn,el){
			var self=this,_func,_msg,emptymsg,msg=[];
			jQuery.each(fn,function(i,val){
				_func=eval("self.validators."+val.val);
				if(_func.call(self,el.val(),val.arg)!==true){
					_msg=self.options.errorMessages[val.val]||self.options.errorMessages._default;
					if(self.options.showNotification!==false&&self.options.notifyBox!=false) msg.push(_msg(val.arg));self.formValid=false;
				}
			});
			emptymsg=jQuery.isEmptyObject(msg);
			if(self.options.type==="ico"){
				this.toggleIco(el,emptymsg);
				return false;
			}
			if(emptymsg){
				this.toggleNotification(this.getNotification(el),false);
				return false;
			}
			this.toggleMsg(el,msg);
		},
		toggleIco:function(el,valid){
			var self=this;
			if(valid){
				if(el.hasClass("ico-invalid")){
					el.stop().animate(ico.hidden,150,function(){
						self.switchIco(el,'ico-valid');
					});
				}
				else this.switchIco(el,'ico-valid');
			}
			else{
				if(el.hasClass("ico-valid")){
					el.stop().animate(ico.hidden,150,function(){
						self.switchIco(el,'ico-invalid');
					});
				}else this.switchIco(el,'ico-invalid');
			}
		},
		switchIco:function(el,css){
			jQuery(el).removeClass('ico-valid').removeClass('ico-invalid').addClass(css).stop().animate(ico.visible,150);
		},
		toggleMsg:function(el,msg){
			if(!this.getNotification(el)) this.setNotification(el);
			var self=this,notification=this.getNotification(el);
			notification.empty();
			jQuery.each(msg,function(i,val){
				notification.append("<div class='msg_item'>"+"<span>"+val+"</span></div>");
				if(self.options.singleMsg) return false;
			});
			this.setNotificationPosition(el);
			this.toggleNotification(notification,true);
		},
		setNotification:function(el){
			var new_note=jQuery("<div class='"+this.options.notifyBox+"' />").appendTo("body");
			el.data("notification",new_note);
		},
		setNotificationPosition:function(el){
			var notification=this.getNotification(el),props={left:el.offset().left,top:el.offset().top,width:el.outerWidth(),height:el.outerHeight()};
			notification.css({left:(this.options.position==="left")?props.left-notification.outerWidth()-5:props.left+props.width+5,top:props.top-(notification.outerHeight()/2)+props.height/2});
		},
		getNotification:function(el){
			return el.data("notification")||false;
		},
		toggleNotification:function(notification,show){
			if(!notification)return false;
			if(show){
				notification.stop(true,true).fadeIn(150);
			}else{
				notification.stop(true,true).fadeOut(200);
			}
		},
		toggleAll:function(show){
			var self=this;
			this.$inputs.each(function(){
				self.toggleNotification(self.getNotification(jQuery(this)),show);
			});
		}
	};
	jQuery.fn[pluginName]=function(options){
		return this.each(function(){
			if(!jQuery.data(this,'plugin_'+pluginName)){
				jQuery.data(this,'plugin_'+pluginName,new LeValidate(this,options));
			}
		});
	};
};
var ejecutar = function() {
    validate(jQuery,window,document);
    jQuery(".le-validate").LeValidate();
};
/*
jQuery(document).bind("ready",function(){
	jQuery(".le-validate").LeValidate();
});*/