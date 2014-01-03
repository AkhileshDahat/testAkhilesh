function contextmenu(cmenuid,cclickhandler,nwidth,mouseoff) {

	//document.clickhandler=cclickhandler;
	document.isie5=(document.all && document.getElementById) ? true : false;
	document.isns6=(document.getElementById && !document.all) ? true : false;
	this.compliant=(document.isns6 || document.isie5) ? true : false;
	document.menuobject=(this.compliant) ? document.getElementById(cmenuid) : 0;
	this.compliant=(this.compliant && document.menuobject && document.menuobject.childNodes) ? true : false;
	if (this.compliant) {
   this.showmenu=function(e) {
      var rightedge=(document.isie5) ? document.body.clientWidth-event.clientX : window.innerWidth-e.clientX;
	  var bottomedge=(document.isie5) ? document.body.clientHeight-event.clientY : window.innerHeight-e.clientY;
	  if (rightedge < document.menuobject.offsetWidth) {
	     document.menuobject.style.left=(document.isie5) ? document.body.scrollLeft+event.clientX-this.menuobject.offsetWidth : window.pageXOffset+e.clientX-this.menuobject.offsetWidth;
		 }
      else {
	     document.menuobject.style.left=(document.isie5) ? document.body.scrollLeft+event.clientX : window.pageXOffset+e.clientX;
		 }
	  if (bottomedge < document.menuobject.offsetHeight) {
	     document.menuobject.style.top=(document.isie5) ? document.body.scrollTop+event.clientY-this.menuobject.offsetHeight : window.pageYOffset+e.clientY-this.menuobject.offsetHeight;
		 }
      else {
	     document.menuobject.style.top=(document.isie5) ? document.body.scrollTop+event.clientY : window.pageYOffset+e.clientY;
		 }
      document.menuobject.style.visibility="visible";
	  return false;
	  };
   this.hidemenu=function() {
      document.menuobject.style.visibility="hidden";
   };
   this.hilitemenuitem=function(e) {
      var clickedobject=(document.isie5) ? event.srcElement : e.target;
	  if (clickedobject.className=="contextmenuitem" || document.isns6 && clickedobject.parentNode.className=="contextmenuitem") {
   	  	 if (document.isns6 && clickedobject.parentNode.className=="contextmenuitem") {
		 	clickedobject=clickedobject.parentNode;
			} //up one node
         clickedobject.style.backgroundColor="highlight";
         clickedobject.style.color="highlighttext";
		 }
      };
   this.lolitemenuitem=function(e) {
      var clickedobject=(document.isie5) ? event.srcElement : e.target;
	  if (clickedobject.className=="contextmenuitem" || document.isns6 && clickedobject.parentNode.className=="contextmenuitem") {
   	  	 if (document.isns6 && clickedobject.parentNode.className=="contextmenuitem") {
		 	clickedobject=clickedobject.parentNode;
			} //up one node
         clickedobject.style.backgroundColor="menu";
   		 clickedobject.style.color="menutext";
   		 }
	  };
   this.clickmenuitem=function(e) {
      var clickedobject=(document.isie5) ? event.srcElement : e.target;
	  if (clickedobject.className=="contextmenuitem" || document.isns6 && clickedobject.parentNode.className=="contextmenuitem") {
   	  	 if (document.isns6 && clickedobject.parentNode.className=="contextmenuitem") {
   	  	 	clickedobject=clickedobject.parentNode;
	  	    }
         if (clickedobject.getAttribute("url")) {
		 	if (clickedobject.getAttribute("target")) {
   	  	       document.menuobject.menumethods.hidemenu();
			   window.open(clickedobject.getAttribute("url"),clickedobject.getAttribute("target"));
	  	    }
         	else {
               document.menuobject.menumethods.hidemenu();
			   window.location=clickedobject.getAttribute("url");
	     	   }
			}
         else {
		    document.menuobject.menumethods.hidemenu();
			eval(document.clickhandler+"('"+clickedobject.innerHTML+"')");
			}
         }
	  };
		document.body.onselectstart=function() {return false;};
		document.body.style.cursor="default";
		document.oncontextmenu=this.showmenu;
		document.onclick=this.hidemenu;
		document.menuobject.style.display="none";
		document.menuobject.style.width=nwidth;
		document.menuobject.style.fontFamily="menutext,Arial,Verdana,sans-serif";
		document.menuobject.style.fontSize="12px";
		document.menuobject.style.lineHeight="18px";
		document.menuobject.style.position="absolute";
		document.menuobject.style.borderRight="2px menu outset";
		document.menuobject.style.borderBottom="2px menu outset";
		document.menuobject.style.borderLeft="2px white outset";
		document.menuobject.style.borderTop="2px white outset";
		document.menuobject.style.backgroundColor="menu";
		document.menuobject.style.cursor=(document.isie5) ? "hand" : "pointer";
		document.menuobject.style.visibility="hidden";
		document.menuobject.style.display='';
		document.menuobject.style.paddingBottom="1px";
		document.menuobject.style.zIndex="999";
		document.menuobject.onselectstart=function() {return false;};
		document.menuobject.onmouseover=this.hilitemenuitem;
		document.menuobject.onmouseout=this.lolitemenuitem;
		document.menuobject.onclick=this.clickmenuitem;
   	for (var i=0;i<document.menuobject.childNodes.length;i++) {
   	   switch (document.menuobject.childNodes[i].className) {
	      case "contextmenuitem" :
		   		document.menuobject.childNodes[i].style.paddingLeft="10px";
					document.menuobject.childNodes[i].style.paddingRight="10px";
					document.menuobject.childNodes[i].style.marginLeft="1px";
					document.menuobject.childNodes[i].style.marginRight="1px";
					document.menuobject.childNodes[i].style.whiteSpace="nowrap";
			 		break;
			 	case "contextmenuhead" :
					//document.menuobject.onmouseover="";
		   		document.menuobject.childNodes[i].style.fontWeight="bold";
		   		document.menuobject.childNodes[i].style.fontSize="12px";
		   		document.menuobject.childNodes[i].style.paddingLeft="10px";
					document.menuobject.childNodes[i].style.paddingRight="10px";
					document.menuobject.childNodes[i].style.marginLeft="5px";
					document.menuobject.childNodes[i].style.marginRight="1px";
					document.menuobject.childNodes[i].style.whiteSpace="nowrap";
			 		break;
	      case "contextmenusep" :
					document.menuobject.childNodes[i].style.cursor="default";
					document.menuobject.childNodes[i].style.marginLeft="2px";
					document.menuobject.childNodes[i].style.marginTop="2px";
					document.menuobject.childNodes[i].style.marginBottom="1px";
					document.menuobject.childNodes[i].style.height="0px";
					document.menuobject.childNodes[i].style.fontSize="3px";
					document.menuobject.childNodes[i].style.lineHeight="0px";
					document.menuobject.childNodes[i].style.borderTop="1px gray solid";
					document.menuobject.childNodes[i].style.borderBottom="1px white solid";
					document.menuobject.childNodes[i].style.width=(nwidth-8);
			 		break;
				default :
		    	break;
	      } /* switch childNodes.style */
   	} /* for i=0 to childNodes.length */
  } /* if compliant */
}


function initpage(_x) {
	var _x = _x;
	document.getElementById(_x).menumethods=new contextmenu(_x,"rightclickhandler",135);
}

function rightclickhandler(citem) {
switch (citem) {
   case "Cancel" :
      break;
   case "Print" :
      window.print();
	  break;
   case "Save As (IE only)" :
      if (document.all) {
	  	 document.execCommand("SaveAs",true,document.title+".htm");
		 }
      else {
	     alert("Not available in Netscape");
	     }
	  break;
   case "Page Source..." :
      document.location = "view-source:" + document.location.href
	  break;
   default :
      alert("You selected: \""+citem+"\", but that feature is under development.");
	  break;
   }
}