<footer>
    
</footer>


<script src="build/js/libs.js"></script> 
<script src="build/js/scripts.js"></script>  

<!-- Start Force24 tracking -->
<script>     
	!function(f,o,r,c,e,_2,_4){f.Force24Object=e,f[e]=f[e]||function(){
		(f[e].q=f[e].q||[]).push(arguments)},f[e].l=1*new Date,     
		_2=o.createElement(r),_4=o.getElementsByTagName(r)[0],_2.async=1,     
		_2.src=c,_4.parentNode.insertBefore(_2,_4)
	}
	(window,document, "script","//tracking1.force24.co.uk/tracking/V2/main.min.js","f24");  
	f24("create", "00000000-0000-0000-0000-000000000000");//replace with correct cliendId
	f24("send", "pageview");
	f24("formSelector", "form#contact-form"); //replace with correct id/class
	f24("formMap", [{
		selector: "form#contact-form", //replace with correct id/class
		meta: {id: "contact-form", name: "contact-form", f24name: "contact-form"}, //replace with correct id/class/name/f24name
		fields: { //replace contents of these brackets with correct mappings and field names
			FirstName: "givenname",
			EmailAddress: "email",
			Aux6: "optin"
		},
		marketingList: "12345678-1234-1234-1234-123456789012", //replace with correct marketing list ID
		status: { //replace contents of these brackets with correct field names
			optInEmail: "optin",
			optInSms: "optin-sms",
			optInDirectmail: "optin-directmail"
		},
		subscriptionPrefs: { //replace contents of these brackets with correct preference GUID and field names
			"12345678-1234-1234-1234-123456789012": "checkbox1",
			"87654321-1234-1234-1234-123456789012": "checkbox2"
		}
	}]);
</script>
<!-- End Force24 tracking -->

</body> 
</html>
