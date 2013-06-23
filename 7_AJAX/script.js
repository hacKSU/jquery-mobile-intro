if (window.location.href.indexOf('#') != -1)
	window.location = "./";

$(document).bind("pageinit", function(){
	$("#loginBtn").click(function(){
		if($("#username").val().trim() == "" || $("#password").val() == "") {
			alert("Please fill in both fields");
			return false;
		}	
		
		schedule();
		flashcash();
	
		window.location.href = "#schedule";

	});
});

function schedule(){
	$.ajax({
			type: 'POST',
			url: 'http://hacksu.herokuapp.com/schedule/',
			dataType: 'jsonp',
			data: { username: $("#username").val(), password: $("#password").val() },
			contentType: 'application/json',
			success : function(data) {
				for (var key in data) 
				{
					if (data.hasOwnProperty(key))
					{
						var course = data[key]["Course"].substring(0,data[key]["Course"].indexOf("-"));
						var time = data[key]["Time"];
						var place = data[key]["Where"];
						var instructor = data[key]["Assigned Instructor:"];
						var insert = "<li><h3>"+course+"</h3><p><strong>"+place+"</strong></p><p><strong>"+time+"</strong></p><p>"+instructor+"</p></li>";
												
						if(data[key].Days.indexOf("M") != -1)
							$("#monday").append(insert);
						if(data[key].Days.indexOf("T") != -1)
							$("#tuesday").append(insert);
						if(data[key].Days.indexOf("W") != -1)
							$("#wednesday").append(insert);
						if(data[key].Days.indexOf("R") != -1)
							$("#thursday").append(insert);
						if(data[key].Days.indexOf("F") != -1)
							$("#friday").append(insert);	
					}
				}
			}
		});
};


function flashcash(){
	$.ajax({
			type: 'POST',
			url: 'http://hacksu.herokuapp.com/flashcash/',
			dataType: 'jsonp',
			data: { username: $("#username").val(), password: $("#password").val() },
			contentType: 'application/json',
			success : function(data) {				
				$("#meal_plan").append(String(data[0]["meal_plan"]));
				$("#flash_cash").append(String(data[1]["flash_cash"]));	
			}
		});
};