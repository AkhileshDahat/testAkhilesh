var opened = false, vkb = null, text = null;

function keyb_change()
{
 document.getElementById("switch").innerHTML = (opened ? "Show keyboard" : "Hide keyboard");
 opened = !opened;

 if(opened && !vkb)
 {
   // Note: all parameters, starting with 3rd, in the following
   // expression are equal to the default parameters for the
   // VKeyboard object. The only exception is 15th parameter
   // (flash switch), which is false by default.

   vkb = new VKeyboard("keyboard",    // container's id
                       keyb_callback, // reference to the callback function
                       false,          // create the arrow keys or not? (this and the following params are optional)
                       false,          // create up and down arrow keys?
                       false,         // reserved
                       false,          // create the numpad or not?
                       "",            // font name ("" == system default)
                       "14px",        // font size in px
                       "#000",        // font color
                       "#F00",        // font color for the dead keys
                       "#FFF",        // keyboard base background color
                       "#FFF",        // keys' background color
                       "#DDD",        // background color of switched/selected item
                       "#777",        // border color
                       "#CCC",        // border/font color of "inactive" key (key with no value/disabled)
                       "#FFF",        // background color of "inactive" key (key with no value/disabled)
                       "#F77",        // border color of the language selector's cell
                       true,          // show key flash on click? (false by default)
                       "#CC3300",     // font color during flash
                       "#FF9966",     // key background color during flash
                       "#CC3300",     // key border color during flash
                       true,         // embed VKeyboard into the page?
                       true,          // use 1-pixel gap between the keys?
                       0);            // index(0-based) of the initial layout
 }
 else
   vkb.Show(opened);

 text = document.getElementById("vkey_field");
 text.focus();

 if(document.attachEvent)
   text.attachEvent("onblur", backFocus);
}

function backFocus()
{
 if(opened)
 {
   var l = text.value.length;

   setRange(text, l, l);

   text.focus();
 }
}

// Callback function:
function keyb_callback(ch)
{
 var val = text.value;

 switch(ch)
 {
   case "BackSpace":
     var min = (val.charCodeAt(val.length - 1) == 10) ? 2 : 1;
     text.value = val.substr(0, val.length - min);
     break;

   case "Enter":
     text.value += "\n";
     break;

   default:
     text.value += ch;
 }
}

function setRange(ctrl, start, end)
{
 if(ctrl.setSelectionRange) // Standard way (Mozilla, Opera, ...)
 {
   ctrl.setSelectionRange(start, end);
 }
 else // MS IE
 {
   var range;

   try
   {
     range = ctrl.createTextRange();
   }
   catch(e)
   {
     try
     {
       range = document.body.createTextRange();
       range.moveToElementText(ctrl);
     }
     catch(e)
     {
       range = null;
     }
   }

   if(!range) return;

   range.collapse(true);
   range.moveStart("character", start);
   range.moveEnd("character", end - start);
   range.select();
 }
}