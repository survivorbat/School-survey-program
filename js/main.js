$(document).ready(function(){
	desktopModeCheck();
	hideAllContent();
	hideThisClass();
	repeatCheck();
	if(window.name!=""){
		openPage(window.name);
	} else {
		openPage("home");
	}
})

function desktopModeCheck() {
	var sPath = window.location.pathname;
	var sPage = sPath.substring(sPath.lastIndexOf('/') + 1);
	if(sPage=="index.php" || sPage==""){
		if (typeof window.orientation==='undefined'){
			$("#container").css("width","40%");
		}
	}
}
function hideThisClass(){
	$(".hideThis").each(function(){
		$(this).hide();
	})
}

function hideAllContent(){
	$(".contentblock").each(function(){
		$(this).hide()
	})
}

function openPage(page){
	hideAllContent();
	$("#"+page).show();
	window.name=page;	
	reloadContentlength();
}

function reloadContentlength() {
	var contentheight=$("#content").css("height").split("px");
	contentheight=parseInt(contentheight[0])+370;
	contentheight=contentheight+"px";
	$("#sidebar").css("height",contentheight);
}

function passwordCheck() {
	if($("#newpassword").val()==$("#secondnewpasswordcheck").val()){
		$("#passwordcheckdisplay").text("Deze wachtwoorden komen overeen!");
		$("#editpasswordbutton").prop("disabled",false);
	} else {
		$("#passwordcheckdisplay").text("Deze wachtwoorden komen niet overeen!");
		$("#editpasswordbutton").prop("disabled",true);
	}
}

function repeatCheck() {
	$("#secondnewpasswordcheck").keyup(function(){
		passwordCheck();
	})
}