var psf1zssid = "pi5LxMU4I91W";
// safe-standard@gecko.js

var psf1zsiso;
try {
	psf1zsiso = (opener != null) && (typeof(opener.name) != "unknown") && (opener.psf1zswid != null);
} catch(e) {
	psf1zsiso = false;
}
if (psf1zsiso) {
	window.psf1zswid = opener.psf1zswid + 1;
	psf1zssid = psf1zssid + "_" + window.psf1zswid;
} else {
	window.psf1zswid = 1;
}
function psf1zsn() {
	return (new Date()).getTime();
}
var psf1zss = psf1zsn();
function psf1zsst(f, t) {
	if ((psf1zsn() - psf1zss) < 7200000) {
		return setTimeout(f, t * 1000);
	} else {
		return null;
	}
}
var psf1zsol = true;
function psf1zsow() {
	if (psf1zsol || (1 == 1)) {
		var pswo = "menubar=0,location=0,scrollbars=auto,resizable=1,status=0,width=650,height=680";
		var pswn = "pscw_" + psf1zsn();
		var url = "https://messenger.providesupport.com/messenger/openvpn.html?ps_s=" + psf1zssid;
		if (false && !true) {
			window.open(url, pswn, pswo); 
		} else {
			var w = window.open("", pswn, pswo); 
			try {
				w.document.body.innerHTML += '<form id="pscf" action="https://messenger.providesupport.com/messenger/openvpn.html" method="post" target="' + pswn + '" style="display:none"><input type="hidden" name="ps_s" value="'+psf1zssid+'"></form>';
				w.document.getElementById("pscf").submit();
			} catch (e) {
				w.location.href = url;
			}
		}
	} else if (1 == 2) {
		document.location = "http\u003a\u002f\u002f";
	}
}
var psf1zsil;
var psf1zsit;
function psf1zspi() {
	var il;
	if (3 == 2) {
		il = window.pageXOffset + 50;
	} else if (3 == 3) {
		il = (window.innerWidth * 50 / 100) + window.pageXOffset;
	} else {
		il = 50;
	}
	il -= (271 / 2);
	var it;
	if (3 == 2) {
		it = window.pageYOffset + 50;
	} else if (3 == 3) {
		it = (window.innerHeight * 50 / 100) + window.pageYOffset;
	} else {
		it = 50;
	}
	it -= (191 / 2);
	if ((il != psf1zsil) || (it != psf1zsit)) {
		psf1zsil = il;
		psf1zsit = it;
		var d = document.getElementById('cif1zs');
		if (d != null) {
			d.style.left  = Math.round(psf1zsil) + "px";
			d.style.top  = Math.round(psf1zsit) + "px";
		}
	}
	setTimeout("psf1zspi()", 100);
}
var psf1zslc = 0;
function psf1zssi(t) {
	window.onscroll = psf1zspi;
	window.onresize = psf1zspi;
	psf1zspi();
	psf1zslc = 0;
	var url = "http://messenger.providesupport.com/" + ((t == 2) ? "auto" : "chat") + "-invitation/openvpn.html?ps_s=" + psf1zssid + "&ps_t=" + psf1zsn() + "";
	var d = document.getElementById('cif1zs');
	if (d != null) {
		d.innerHTML = '<iframe allowtransparency="true" style="background:transparent;width:271;height:191" src="' + url + 
			'" onload="psf1zsld()" frameborder="no" width="271" height="191" scrolling="no"></iframe>';
	}
}
function psf1zsld() {
	if (psf1zslc == 1) {
		var d = document.getElementById('cif1zs');
		if (d != null) {
			d.innerHTML = "";
		}
	}
	psf1zslc++;
}
if (false) {
	psf1zssi(1);
}
var psf1zsd = document.getElementById('scf1zs');
if (psf1zsd != null) {
	if (psf1zsol || (1 == 1) || (1 == 2)) {
		if (false) {
			psf1zsd.innerHTML = '<table style="display:inline" cellspacing="0" cellpadding="0" border="0"><tr><td align="center"><a href="#" onclick="psf1zsow(); return false;"><img name="psf1zsimage" src="http://www.providesupport.com/resource/cp6x59/default/company/image/chat-icon/1/chat-icon-1-online-en.gif"  border="0"></a></td></tr><tr><td align="center"><a href="http://www.providesupport.com/pb/openvpn" target="_blank"><img src="http://image.providesupport.com/lcbps.gif" width="140" height="17" border="0"></a></td></tr></table>';
		} else {
			psf1zsd.innerHTML = '<a href="#" onclick="psf1zsow(); return false;"><img name="psf1zsimage" src="http://www.providesupport.com/resource/cp6x59/default/company/image/chat-icon/1/chat-icon-1-online-en.gif"  border="0"></a>';
		}
	} else {
		psf1zsd.innerHTML = '';
	}
}
var psf1zsop = false;
function psf1zsco() {
	var w1 = psf1zsci.width - 1;
	psf1zsol = (w1 & 1) != 0;
	psf1zssb(psf1zsol ? "http://www.providesupport.com/resource/cp6x59/default/company/image/chat-icon/1/chat-icon-1-online-en.gif" : "http://www.providesupport.com/resource/cp6x59/default/company/image/chat-icon/1/chat-icon-1-offline-en.gif");
	psf1zsscf((w1 & 2) != 0);
	var h = psf1zsci.height;

	if (h == 1) {
		psf1zsop = false;

	// manual invitation
	} else if ((h == 2) && (!psf1zsop)) {
		psf1zsop = true;
		psf1zssi(1);
		//alert("Chat invitation in standard code");
		
	// auto-invitation
	} else if ((h == 3) && (!psf1zsop)) {
		psf1zsop = true;
		psf1zssi(2);
		//alert("Auto invitation in standard code");
	}
}
var psf1zsci = new Image();
psf1zsci.onload = psf1zsco;
var psf1zspm = false;
var psf1zscp = psf1zspm ? 30 : 60;
var psf1zsct = null;
function psf1zsscf(p) {
	if (psf1zspm != p) {
		psf1zspm = p;
		psf1zscp = psf1zspm ? 30 : 60;
		if (psf1zsct != null) {
			clearTimeout(psf1zsct);
			psf1zsct = null;
		}
		psf1zsct = psf1zsst("psf1zsrc()", psf1zscp);
	}
}
function psf1zsrc() {
	psf1zsct = psf1zsst("psf1zsrc()", psf1zscp);
	try {
		psf1zsci.src = "http://image.providesupport.com/cmd/openvpn?" + "ps_t=" + psf1zsn() + "&ps_l=" + escape(document.location) + "&ps_r=" + escape(document.referrer) + "&ps_s=" + psf1zssid + "" + "";
	} catch(e) {
	}
}
psf1zsrc();
var psf1zscb = "http://www.providesupport.com/resource/cp6x59/default/company/image/chat-icon/1/chat-icon-1-online-en.gif";
function psf1zssb(b) {
	if (psf1zscb != b) {
		var i = document.images['psf1zsimage'];
		if (i != null) {
			i.src = b;
		}
		psf1zscb = b;
	}
}

