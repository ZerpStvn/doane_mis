(function($){
    $.fn.validationEngineLanguage = function(){
    };
    $.validationEngineLanguage = {
        newLang: function(){
            $.validationEngineLanguage.allRules = {
                "required": { // Add your regex rules here, you can take telephone as an example
                    "regex": "none",
                    "alertText": "* 이 필드는 필수 항목입니다.",
                    "alertTextCheckboxMultiple": "* 옵션을 선택해주세요",
                    "alertTextCheckboxe": "* 이 확인란은 필수 항목입니다.",
                    "alertTextDateRange": "* 두 날짜 범위 필드 모두 필수 항목입니다."
                },
                "requiredInFunction": { 
                    "func": function(field, rules, i, options){
                        return (field.val() == "test") ? true : false;
                    },
                    "alertText": "* 필드는 테스트와 같아야 합니다."
                },
                "dateRange": {
                    "regex": "none",
                    "alertText": "* Invalid ",
                    "alertText2": "Date Range"
                },
                "dateTimeRange": {
                    "regex": "none",
                    "alertText": "* Invalid ",
                    "alertText2": "Date Time Range"
                },
                "minSize": {
                    "regex": "none",
                    "alertText": "* Minimum ",
                    "alertText2": " 필요한 문자"
                },
                "maxSize": {
                    "regex": "none",
                    "alertText": "* Maximum ",
                    "alertText2": " 허용되는 문자"
                },
		"groupRequired": {
                    "regex": "none",
                    "alertText": "* 다음 필드 중 하나를 채워야 합니다.",
                    "alertTextCheckboxMultiple": "* 옵션을 선택해주세요",
                    "alertTextCheckboxe": "* 이 확인란은 필수 항목입니다."
                },
                "min": {
                    "regex": "none",
                    "alertText": "* 최소값은 "
                },
                "max": {
                    "regex": "none",
                    "alertText": "* 최대값: "
                },
                "past": {
                    "regex": "none",
                    "alertText": "* 이전 날짜 "
                },
                "future": {
                    "regex": "none",
                    "alertText": "* 지난 날짜 "
                },	
                "maxCheckbox": {
                    "regex": "none",
                    "alertText": "* Maximum ",
                    "alertText2": " 허용되는 옵션"
                },
                "minCheckbox": {
                    "regex": "none",
                    "alertText": "* Please select ",
                    "alertText2": " 옵션"
                },
                "equals": {
                    "regex": "none",
                    "alertText": "* 필드가 일치하지 않습니다"
                },
                "creditCard": {
                    "regex": "none",
                    "alertText": "* 잘못된 신용 카드 번호"
                },
                "phone": {
                    // credit: jquery.h5validate.js / orefalo
                    "regex": /^([\+][0-9]{1,3}([ \.\-])?)?([\(][0-9]{1,6}[\)])?([0-9 \.\-]{1,32})(([A-Za-z \:]{1,11})?[0-9]{1,4}?)$/,
                    "alertText": "* 유효하지 않은 전화 번호"
                },
                "email": {
                    // HTML5 compatible email regex ( http://www.whatwg.org/specs/web-apps/current-work/multipage/states-of-the-type-attribute.html#    e-mail-state-%28type=email%29 )
                    "regex": /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
                    "alertText": "* 잘못된 이메일 주소"
                },
                "fullname": {
                    "regex":/^([a-zA-Z]+[\'\,\.\-]?[a-zA-Z ]*)+[ ]([a-zA-Z]+[\'\,\.\-]?[a-zA-Z ]+)+$/,
                    "alertText":"* 이름과 성을 입력해야 합니다."
                },
                "zip": {
                    "regex":/^\d{5}$|^\d{5}-\d{4}$/,
                    "alertText":"* 잘못된 우편 형식"
                },
                "integer": {
                    "regex": /^[\-\+]?\d+$/,
                    "alertText": "* 유효한 정수가 아닙니다."
                },
                "number": {
                    // Number, including positive, negative, and floating decimal. credit: orefalo
                    "regex": /^[\-\+]?((([0-9]{1,3})([,][0-9]{3})*)|([0-9]+))?([\.]([0-9]+))?$/,
                    "alertText": "* 잘못된 부동 소수점"
                },
                "date": {                    
                    //	Check if date is valid by leap year
			"func": function (field) {
					var pattern = new RegExp(/^(\d{4})[\/\-\.](0?[1-9]|1[012])[\/\-\.](0?[1-9]|[12][0-9]|3[01])$/);
					var match = pattern.exec(field.val());
					if (match == null)
					   return false;
	
					var year = match[1];
					var month = match[2]*1;
					var day = match[3]*1;					
					var date = new Date(year, month - 1, day); // because months starts from 0.
	
					return (date.getFullYear() == year && date.getMonth() == (month - 1) && date.getDate() == day);
				},                		
			 "alertText": "* Invalid date, must be in YYYY-MM-DD format"
                },
                "ipv4": {
                    "regex": /^((([01]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))[.]){3}(([0-1]?[0-9]{1,2})|(2[0-4][0-9])|(25[0-5]))$/,
                    "alertText": "* Invalid IP address"
                },
                "url": {
                    "regex": /^(https?|ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(\#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i,
                    "alertText": "* 잘못된 URL"
                },
                "onlyNumberSp": {
                    "regex": /^[0-9\ ]+$/,
                    "alertText": "* 숫자 만"
                },
				//add new validation//
				"onlyNumber": {
                    "regex": /^[0-9]+$/,
                    "alertText": "* 숫자 만"
                },
			
				"amount": {
                    "regex": /^[0-9]+$/,
                    "alertText": "* 숫자 및 부동 숫자 허용"
                },
				////////////////
                "onlyLetterSp": {
                    "regex": /^([\u3131-\u314e|\u314f-\u3163|\uac00-\ud7a3]|[a-zA-Z\ \'])+$/,
                    "alertText": "* Letters only"
                },
				"onlyLetterAccentSp":{
                    "regex": /^([\u3131-\u314e|\u314f-\u3163|\uac00-\ud7a3]|[a-z\u00C0-\u017F\ ])+$/i,
                    "alertText": "* 문자만(악센트 허용)"
                },
                "onlyLetterNumber": {
                    "regex": /^([\u3131-\u314e|\u314f-\u3163|\uac00-\ud7a3]|[0-9a-zA-Z])+$/,
                    "alertText": "* 특수 문자는 허용되지 않습니다."
                },
				//Start Custom Validation
				//1)First Name,Last Name
				"onlyLetter_specialcharacter": 
				{
                    "regex": /^([\u3131-\u314e|\u314f-\u3163|\uac00-\ud7a3]|[a-zA-Z\ \_\,\`\.\'\^\-\ç\´\~])+$/,
                    //(allow Latin word or Croatian latters)"regex": /^([^\u0000-\u007F]|[a-zA-Z\ \_\,\`\.\'\^\-])+$/,                     
                    "alertText": "* 문자와 ' _,`.'^-' 문자만 허용됨"
                },
				//2)City,State,Country
				"city_state_country_validation": 
				{
                    "regex": /^([\u3131-\u314e|\u314f-\u3163|\uac00-\ud7a3]|[a-zA-Z\ \_\,\`\.\'\^\-\&])+$/,
                    "alertText": "* 문자와 ' _,`.'^-&' 문자만 허용됨"
                },
				//3)PopUp Category,Medicine Name,Event Name
				"popup_category_validation": 
				{
                    "regex": /^([\u3131-\u314e|\u314f-\u3163|\uac00-\ud7a3]|[0-9a-zA-Z\ \_\,\`\.\'\^\(\)\"\/\:\;\?\[\]\=\+\*\%\$\#\@\!\-\&\^\ç\´\~])+$/,
                    "alertText": "* 문자, 숫자 및 ' _,`.'^' 문자만 허용됨"
                },
				//4)Address and Description
				"address_description_validation": 
				{
                    "regex": /^([\u3131-\u314e|\u314f-\u3163|\uac00-\ud7a3]|[0-9a-zA-Z\ \_\,\`\.\'\^\(\)\"\/\:\;\?\[\]\=\+\*\%\$\#\@\!\-\&\n\ç\´\~])+$/,
                    "alertText": "* 문자, 숫자 및 ' _,`.'^-&' 문자만 허용됨"
                },
				//5)UserName
				"username_validation": 
				{
                    "regex": /^[0-9a-zA-Z\_\.\-\@]+$/,
                    "alertText": "* 문자, 숫자 및 '_.-@' 문자만 허용됨"
                }, 
				//6)Phone Number
				"phone_number": 
				{
                    "regex": /^[0-9\ \-\+]+$/,
                    "alertText": "* 숫자와 '-+' 문자만 허용됨"
                }, 
				// End Custom Validation
                // --- CUSTOM RULES -- Those are specific to the demos, they can be removed or changed to your likings
                "ajaxUserCall": {
                    "url": "ajaxValidateFieldUser",
                    // you may want to pass extra data on the ajax call
                    "extraData": "name=eric",
                    "alertText": "* This user - user name is already registered",
                    "alertTextLoad": "* Validating, please wait"
                },
				"ajaxUserCallPhp": {
                    "url": "phpajax/ajaxValidateFieldUser.php",
                    // you may want to pass extra data on the ajax call
                    "extraData": "name=eric",
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertTextOk": "* This username is available",
                    "alertText": "* This user is already taken",
                    "alertTextLoad": "* Validating, please wait"
                },
                "ajaxNameCall": {
                    // remote json service location
                    "url": "ajaxValidateFieldName",
                    // error
                    "alertText": "* This user - user name is already registered",
                    // if you provide an "alertTextOk", it will show as a green prompt when the field validates
                    "alertTextOk": "* This name is available",
                    // speaks by itself
                    "alertTextLoad": "* Validating, please wait"
                },
				 "ajaxNameCallPhp": {
	                    // remote json service location
	                    "url": "phpajax/ajaxValidateFieldName.php",
	                    // error
	                    "alertText": "* This user - user name is already registered",
	                    // speaks by itself
	                    "alertTextLoad": "* Validating, please wait"
	                },
                "validate2fields": {
                    "alertText": "* Please input HELLO"
                },
	            //tls warning:homegrown not fielded 
                "dateFormat":{
                    "regex": /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])$|^(?:(?:(?:0?[13578]|1[02])(\/|-)31)|(?:(?:0?[1,3-9]|1[0-2])(\/|-)(?:29|30)))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(?:(?:0?[1-9]|1[0-2])(\/|-)(?:0?[1-9]|1\d|2[0-8]))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^(0?2(\/|-)29)(\/|-)(?:(?:0[48]00|[13579][26]00|[2468][048]00)|(?:\d\d)?(?:0[48]|[2468][048]|[13579][26]))$/,
                    "alertText": "* Invalid Date"
                },
                //tls warning:homegrown not fielded 
				"dateTimeFormat": {
	                "regex": /^\d{4}[\/\-](0?[1-9]|1[012])[\/\-](0?[1-9]|[12][0-9]|3[01])\s+(1[012]|0?[1-9]){1}:(0?[1-5]|[0-6][0-9]){1}:(0?[0-6]|[0-6][0-9]){1}\s+(am|pm|AM|PM){1}$|^(?:(?:(?:0?[13578]|1[02])(\/|-)31)|(?:(?:0?[1,3-9]|1[0-2])(\/|-)(?:29|30)))(\/|-)(?:[1-9]\d\d\d|\d[1-9]\d\d|\d\d[1-9]\d|\d\d\d[1-9])$|^((1[012]|0?[1-9]){1}\/(0?[1-9]|[12][0-9]|3[01]){1}\/\d{2,4}\s+(1[012]|0?[1-9]){1}:(0?[1-5]|[0-6][0-9]){1}:(0?[0-6]|[0-6][0-9]){1}\s+(am|pm|AM|PM){1})$/,
                    "alertText": "* Invalid Date or Date Format",
                    "alertText2": "Expected Format: ",
                    "alertText3": "mm/dd/yyyy hh:mm:ss AM|PM or ", 
                    "alertText4": "yyyy-mm-dd hh:mm:ss AM|PM"
	            }
            };
            
        }
    };

    $.validationEngineLanguage.newLang();
    
})(jQuery);