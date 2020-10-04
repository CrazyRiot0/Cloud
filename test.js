$("#button").click(function() {
	$.get("test.php", function(data) {
		alert(data);
		alert($(data).find("#test").html());
	});
});