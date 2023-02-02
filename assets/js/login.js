window.onbeforeunload = function(e){
	  gapi.auth2.getAuthInstance().signOut();
	};

	function onSignIn(googleUser) {
		var auth2 = gapi.auth2.getAuthInstance();
    	auth2.disconnect();
    	var profile = googleUser.getBasicProfile();
    	console.log(profile);
	  	var requestData = {};
	  	requestData.social_id = profile.getId();
	  	requestData.email = profile.getEmail();
	  	requestData.name = profile.getName();
	  	requestData.avatar = profile.getImageUrl();
	  	requestData.provider = 'google';
	  	requestData.role_id = 2;
	  	requestData.device = 'web';

	  	sociallogin(requestData);
	}

	function signOut() {
	    var auth2 = gapi.auth2.getAuthInstance();
	    auth2.signOut().then(function () {
	      console.log('User signed out.');
	    });
	}

	function sociallogin(requestData)
	{
		var url = baseurl+"/sociallogin";
	  	$.ajax({
			url : url,
			data : requestData,
			dataType : 'json',
			type : 'post',
			success : function(res){
				window.location.href = surl;
			},
			error : function(err){
				$(".reg-btn").prop('disabled',false);
				var msg = err.responseJSON.message;
				$(".error-message-area").find('.error-msg').text(msg);
				$(".error-message-area").show();
			}
		});
	}