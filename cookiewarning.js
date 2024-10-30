/////////////////////////////////////////////////////////////
//
// Author of original version: Scott Herbert (www.scott-herbert.com)
//
// Version History 
// 1 (19-June-2011) Inital release on to Google code.
//
// May 2012 integration into cookie waring plugin for WP by Marie Manandise (http://majweb.co.uk)
//


function getCookie(c_name)
{
var i,x,y,ARRcookies=document.cookie.split(";");
for (i=0;i<ARRcookies.length;i++)
  {
  x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
  y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
  x=x.replace(/^\s+|\s+$/g,"");
  if (x==c_name)
    {
    return unescape(y);
    }
  }
}


function displayNotification()
{

// this sets the page background to semi-transparent black should work with all browsers
var message = "<div id='cookiewarning' ><div id=\"back\">";

// center vert
message = message + "<div>";

// this is the message displayed to the user.


message = message + "<p>" + nl2br(user_options.messageContent) + "</p>";
	
	
// Displays the I agree/disagree buttons.
// Feel free to change the address of the I disagree redirection to either a non-cookie site or a Google or the ICO web site 

//message = message + "<p id=\"buttons\"><a href=\"#\" id=\"cookiewarningOK\" onClick='JavaScript:setCookie(\"jsCookiewarning29Check\",null,365);'>"+user_options.okText+"</a> <a id=\"cookienotOK\" href=\"#\" onClick='JavaScript:window.location = \""+user_options.redirectLink+"\"'>"+user_options.notOkText+"</a></p>";

message = message + "<p id=\"buttons\"><a href=\"\" id=\"cookiewarningOK\" >"+user_options.okText+"</a> <a id=\"cookienotOK\" href=\"\" >"+user_options.notOkText+"</a></p>";

	
// and this closes everything off.
message = message + "</div></div></div>";


jQuery('body').prepend(message);

jQuery('a[id=cookiewarningOK]').click( function(event){
	event.preventDefault();
	setCookie(user_options.cookieName,null,365);
    } );

jQuery('a[id=cookienotOK]').click( function(event){
	event.preventDefault();
	killCookies();
    } );


}

function setCookie(c_name,value,exdays)
{
var exdate=new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
document.cookie=c_name + "=" + c_value + ";path=/";

jQuery('#cookiewarning').css('display', 'none');

}



function checkCookie()
{
var cookieName= user_options.cookieName;
var cookieChk=getCookie(cookieName);
if (cookieChk!=null && cookieChk!="")
  {
  // the jsCookieCheck cookie exists so we can assume the person has read the notification
  // within the last year
  setCookie(cookieName,cookieChk,365);	// set the cookie to expire in a year.
  }
else 
  {
  // No cookie exists, so display the lightbox effect notification.
  displayNotification();
  } 
}


function nl2br (str, is_xhtml) {   
var breakTag = (is_xhtml || typeof is_xhtml === 'undefined') ? '<br />' : '<br>';    
return (str + '').replace(/([^>\r\n]?)(\r\n|\n\r|\r|\n)/g, '$1'+ breakTag +'$2');
}

function killCookies(){
    //AJAX call to php function that kill all cookies except this cookie
    jQuery('div#cookiewarning p#buttons').html('Clearing cookies and redirecting...');

    jQuery.getJSON( user_options.ajaxUrl , { action : 'php_clear_cookies' , cookie : user_options.cookieName } ,
	function( data ) {
		window.location = user_options.redirectLink;
	});
}

    
function are_cookies_enabled()
{
	var cookieEnabled = (navigator.cookieEnabled) ? true : false;

	if (typeof navigator.cookieEnabled == "undefined" && !cookieEnabled)
	{ 
		document.cookie="testcookie";
		cookieEnabled = (document.cookie.indexOf("testcookie") != -1) ? true : false;
	}
	return (cookieEnabled);
}

	
jQuery(window).load(function(){
        if(are_cookies_enabled()){
	    checkCookie();
	}
	
	jQuery(window).scroll(function(){
	    if(jQuery( 'div#cookiewarning' ).is(':visible')){
		if( jQuery(window).scrollTop() + jQuery(window).height() >= jQuery( 'div#cookiewarning div#back' ).outerHeight() ){
		 jQuery(window).scrollTop( jQuery( 'div#cookiewarning div#back' ).outerHeight() - jQuery(window).height() );
		}
	    }
	    
	});
	
	jQuery(window).resize(function(){
	    if(jQuery( 'div#cookiewarning' ).is(':visible')){
		    if( jQuery(window).scrollTop() + jQuery(window).height() >= jQuery( 'div#cookiewarning div#back' ).outerHeight() ){
		    jQuery(window).scrollTop( jQuery( 'div#cookiewarning div#back' ).outerHeight() - jQuery(window).height() );
		    }
		}
	    });

    });