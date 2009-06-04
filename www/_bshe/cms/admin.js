/*------------------------------------------------------------------------
	True portable WYSIWYG tool built for SEO and easy integration.
	Copyright (C) 2007  Stephen L. Blum (LightTheSun)

	This library is free software; you can redistribute it and/or
	modify it under the terms of the GNU Lesser General Public
	License as published by the Free Software Foundation version
	2.1 of the License.

	This library is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
	Lesser General Public License for more details.

	You should have received a copy of the GNU Lesser General Public
	License along with this library; if not, write to the Free Software
	Foundation, Inc., 51 Franklin Street, Fifth Floor,
	Boston, MA 02110-1301 USA

	LightTheSun hereby reserves all copyright interests in any LightTheSun,
	LTSun or LTS library written by Stephen L. Blum.

	Stephen L. Blum, 8 February 2007
	President of LightTheSun
------------------------------------------------------------------------*/



/*----------------------------------------------------------------------------------------
	Text Editor Module
	------------------
	This module will make any element editable with as true to WYSIWYG as possible.
----------------------------------------------------------------------------------------*/

LTSun_AddModule(initTextModule);

function initTextModule()
{
	/*----------------------------------------------------------------------------------------
		Text Editor Instances
		---------------------
		Element {IDs} for each IFRAME
	----------------------------------------------------------------------------------------*/
	LTSun.textEditorInstances = new Array();


	/*----------------------------------------------------------------------------------------
		Text Editor Button Instances
		----------------------------
		Element {OBJECTs} for each button menu.
	----------------------------------------------------------------------------------------*/
	LTSun.textEditorButtonInstances = new Array();


	/*----------------------------------------------------------------------------------------
		Select Elements Editable
		------------------------
		This is a secondary option to render an editable text area. Comma delimit BLOCK-ONLY
		recommended element ID tags in the settings array. This is sent during the init call.
		Non-block elements may work, but are unsupported. Using a DIV is best.

		Example: selectTextElementEditable : "contactDiv,appleLevelDiv,footerDiv,etcDiv"
	----------------------------------------------------------------------------------------*/
	if(LTSun.settings['selectTextElementEditable'])
	{
		var selectTextElementEditableArray = LTSun.settings['selectTextElementEditable'];//.split(',');
		var selectTextElementEditable;

		for(var i = 0; selectTextElementEditable = selectTextElementEditableArray[i]; i++)
		{
			if($(selectTextElementEditable))
			{
				LTSun.createTextControls(selectTextElementEditable);
				LTSun.createIframe(selectTextElementEditable);
				LTSun.textEditorInstances.push(selectTextElementEditable);
			}
		}
	}

	return true;
}



/*----------------------------------------------------------------------------------------
	Text Add Editor
	---------------
	Adds an element to a global variable for later processing in initTextModule().
----------------------------------------------------------------------------------------*/
LTSun.textAddEditor = function(elementId)
{
	LTSun.settings['selectTextElementEditable'].push(elementId);
};



/*----------------------------------------------------------------------------------------
	Create iFrame Object
	--------------------
	This function is called from the init function above, it will create an LTSun editor
	instance using the "noinc-blank.php" file.
----------------------------------------------------------------------------------------*/
LTSun.createIframe = function(elementId)
{

//	var iframe = document.createElement("iframe");
	var elementObject = document.getElementById(elementId);

	elementObject.setAttribute("contenteditable","true");
	if( elementObject.outerHTML ){
		elementObject.outerHTML = elementObject.outerHTML; }

//	var pageId = LTSunSettings["selectText"+elementId+"PageId"];
/*
	iframe.setAttribute("id", elementId);
	iframe.setAttribute("name", elementId);
	iframe.setAttribute("frameBorder", "0");
	iframe.setAttribute("marginWidth", "0");
	iframe.setAttribute("marginHeight", "0");
	iframe.setAttribute("leftMargin", "0");
	iframe.setAttribute("topMargin", "0");
	iframe.setAttribute("border", "0");
	iframe.setAttribute("style", elementObject.getAttribute("style"));
	iframe.setAttribute("class", elementObject.className);
	iframe.className = elementObject.className;
	iframe.setAttribute("width", LTSun.getElementWidth(elementId));
	iframe.setAttribute("height", LTSun.getElementHeight(elementId));
	iframe.setAttribute("allowtransparency", "true");
	iframe.allowTransparency = "true";
	iframe.setAttribute("scrolling", "no");

	iframe.setAttribute("src",  LTSunSettings['bshe_indexphp_path'] + "/text/noinc-blank.html" +
								"?elementClass=" + elementObject.className +
								"&pageId=" + LTSun.settings['bshe_templatename'] +
								"&elementId=" + elementId);

	elementObject.parentNode.replaceChild(iframe, elementObject);
*/

	return true;
};



/*----------------------------------------------------------------------------------------
	Get Text Module HTML
	--------------------
	Returns the element's HTML.
----------------------------------------------------------------------------------------*/
LTSun.getTextModuleHTML = function(elementId)
{
	return document.getElementById(elementId).innerHTML;
};


/*----------------------------------------------------------------------------------------
	Update Window Height
	--------------------
	This function is called from noinc-blank.php when ever a key press is captured by an event
	listener that is was started onLoad in noinc-blank.php. This function also updates the
	position of the menu button hovering div.
----------------------------------------------------------------------------------------*/
LTSun.updateWindowHeight = function(windowHeight, elementId)
{
	var selectedObjectIframe;
	var selectedObjectDivMenu;

	if(windowHeight > 0 && elementId != "")
		document.getElementById(elementId).style.height = windowHeight + 'px';

	for(var i=0; i < this.textEditorInstances.length; i++)
	{
		selectedObjectIframe = this.textEditorInstances[i];
		selectedObjectDivMenu = this.textEditorButtonInstances[i];
		selectedObjectDivMenu.style.top = LTSun.getElementTop(selectedObjectIframe) + 'px';
		selectedObjectDivMenu.style.left = LTSun.getElementLeft(selectedObjectIframe) + 'px';
	}
};


/*----------------------------------------------------------------------------------------
	Update Text Module HTML
	-----------------------
	Updates the current text editor with newly edited HTML from the Advanced Editor.
----------------------------------------------------------------------------------------*/
LTSun.updateTextModuleHTML = function(elementId, html, saveOkay)
{
/*
	document.getElementById(elementId).contentWindow.document.body.innerHTML = html;
	document.getElementById(elementId).contentWindow.updateWindowHeight();
	if(saveOkay) LTSun.saveTextModule(LTSunSettings['bshe_indexphp_path'] + "/text/noinc-save.html", elementId, document.getElementById(elementId).contentWindow.document.body.innerHTML, true, null, null);
	LTSun.hideWindow({});
*/
document.getElementById(elementId).innerHTML = html;
//document.getElementById(elementId).contentWindow.updateWindowHeight();
if(saveOkay) LTSun.saveTextModule(LTSunSettings['bshe_indexphp_path'] + "/text/noinc-save.html", elementId, document.getElementById(elementId).innerHTML, true, null, null);
LTSun.hideWindow({});
document.getElementById(elementId).focus();

};


/*----------------------------------------------------------------------------------------
	Messaging Text Editor
	---------------------
	This displays information over the top of the text editor instance showing status of
	requested action also blocks multiple tasks from being executed simultaneously.
----------------------------------------------------------------------------------------*/
LTSun.showTextMessage = function(elementId, message)
{
	var srcLDiv = document.createElement("div");
	var msgWidth = LTSun.getElementWidth(elementId);
	var msgHeight = LTSun.getElementHeight(elementId);
	var msgTop = LTSun.getElementTop(elementId);
	var msgLeft = LTSun.getElementLeft(elementId);

	srcLDiv.id = "window_srcLDiv_" + elementId;
	srcLDiv.style.opacity = 0.8;
	srcLDiv.style.filter = "alpha(opacity=80)";
	srcLDiv.style.width = msgWidth + 'px';
	srcLDiv.style.height = msgHeight + 'px';
	srcLDiv.style.position = "absolute";
	srcLDiv.style.top = msgTop + 'px';
	srcLDiv.style.left = msgLeft + 'px';
	srcLDiv.style.zIndex = 2000;
	srcLDiv.style.backgroundColor = "#000000";
	srcLDiv.innerHTML = "<table width='"+msgWidth+"' height='"+msgHeight+"' cellpadding='0' cellspacing='0' border='0' style='border-collapse:collapse;'><tr><td style='line-height:18px;font-size:20px;color:#ffffff;text-align:center;font-weight:bold;cursor:default;' id='window_srcLDiv_content_"+elementId+"'>" + message + "</td></tr></table>";

	document.body.appendChild(srcLDiv);
}
LTSun.changeTextMessage = function(elementId, message)
{
	if($("window_srcLDiv_content_" + elementId)) $("window_srcLDiv_content_" + elementId).innerHTML = message;
}
LTSun.hideTextMessage = function(elementId)
{
	if($("window_srcLDiv_" + elementId)) document.body.removeChild($("window_srcLDiv_" + elementId));
}


/*----------------------------------------------------------------------------------------
	Save Text
	---------
	This button calls on noinc-save.php with AJAX to save the text then displays a window
	with confirmation where options of publishing are presented.
----------------------------------------------------------------------------------------*/
LTSun.saveTextModule = function(fileName, elementId, html, displayResponse, onSuccess, onFailure)
{
//	var pageId = LTSunSettings["selectText"+elementId+"PageId"];
//	var stringDataHTML = (html.length > 5 ? html : document.getElementById(elementId).contentWindow.document.body.innerHTML);
	var stringDataHTML = (html.length > 5 ? html : document.getElementById(elementId).innerHTML);

	if(displayResponse)
	{
		LTSun.showTextMessage(elementId, "Saving Content...");
	}


	new Ajax.Request
	(
		fileName, //  "LTSun-Engine/modules/text/noinc-save.php"
		{
			parameters:
			{
				pageId: LTSun.settings['bshe_templatename'],
				elementId: elementId,
				stringDataHTML: stringDataHTML
			},

			onSuccess : onSuccess != null ? onSuccess : function(transport)
			{
				if(displayResponse)
				{
					if(transport.responseText == "1")
					{
						LTSun.changeTextMessage(elementId, "Saving Complete");
						setTimeout("LTSun.hideTextMessage('"+elementId+"');", 1000);
					}
					else
					{
						LTSun.changeTextMessage(elementId, "<span style='color:#ff0000;'>Save ERROR!</span>");
						setTimeout("LTSun.hideTextMessage('"+elementId+"');", 2000);
					}
				}

				return true;
			},

			onFailure : function(transport)
			{
				new Ajax.Request
				(
					fileName.substr(fileName.lastIndexOf("/")+1, fileName.length-fileName.indexOf("?")-1), //"noinc-save.php",
					{
						parameters:
						{
							pageId: LTSun.settings['bshe_templatename'],
							elementId: elementId,
							stringDataHTML: stringDataHTML
						},

						onSuccess : onSuccess != null ? onSuccess : function(transport)
						{
							if(displayResponse)
							{
								if(transport.responseText == "1")
								{
									LTSun.changeTextMessage(elementId, "Saving Complete");
									setTimeout("LTSun.hideTextMessage('"+elementId+"');", 1000);

									return true;
								}
								else
								{
									LTSun.changeTextMessage(elementId, "<span style='color:#ff0000;'>Save ERROR!</span>");
									setTimeout("LTSun.hideTextMessage('"+elementId+"');", 2000);

									return false;
								}
							}
						},

						onFailure : onFailure != null ? onFailure : function(transport)
						{
							if(displayResponse)
							{
								LTSun.changeTextMessage(elementId, "<span style='color:#ff0000;'>Save ERROR!</span>");
								setTimeout("LTSun.hideTextMessage('"+elementId+"');", 2000);
							}

							return false;
						}
					}
				);
			}
		}
	);
};


/*----------------------------------------------------------------------------------------
	Create Control Menu
	-------------------
	Creates a DIV Block that will hover around and over the editable area. This control
	menu will have functions to Save, tinyMCE, Revisions and Configure/Publish.
----------------------------------------------------------------------------------------*/
LTSun.createTextControls = function(elementId)
{
	var menuDiv = document.createElement("div");
	var menuTop = LTSun.getElementTop(elementId);
	var menuLeft = LTSun.getElementLeft(elementId);
	var menuHTML = "";

	menuHTML += "<div class='LTsun LTSunTextControlDiv'>";
	menuHTML += "<div class='LTsun LTSunTextControlDivPadding'>";
	menuHTML += "<table class='LTsun LTSunTextControlTable'>";
	menuHTML += "<tr class='LTsun'>";
	menuHTML += "<td class='LTsun' style='position:relative;'><div id='elementId_saveText' title='" + LTSunSettings['titleTextControls_save'] + "' class='LTsun LTSunButtonStandard LTSunSaveButton'></div></td>";
	menuHTML += "<td class='LTsun' style='position:relative;'><div id='elementId_publishText' title='" + LTSunSettings['titleTextControls_publish'] + "' class='LTsun LTSunButtonStandard LTSunPublishButton'></div></td>";
	menuHTML += "<td class='LTsun' style='position:relative;'><div id='elementId_advancedEditorText' title='" + LTSunSettings['titleTextControls_edit'] + "' class='LTsun LTSunButtonStandard LTSunEditButton'></div></td>";
	menuHTML += "<td class='LTsun' style='position:relative;'><div id='elementId_revisionsText' title='" + LTSunSettings['titleTextControls_revisions'] + "' class='LTsun LTSunButtonStandard LTSunRevisionsButton'></div></td>";
	menuHTML += "<td class='LTsun'>&nbsp;&nbsp;&nbsp;</td>";
	menuHTML += "<td class='LTsun' style='position:relative;'><div id='elementId_menu' title='" + LTSunSettings['titleTextControls_menu'] + "' class='LTsun LTSunButtonStandard LTSunLogoutButton'></div></td>";
	menuHTML += "</tr>";
	menuHTML += "</table></div></div>";

	menuHTML = menuHTML.replace(/elementId_/gi, elementId + "_");
	menuDiv.innerHTML = menuHTML;

	menuDiv.style.position = "absolute";
	menuDiv.style.overflow = "hidden";
	menuDiv.style.top = menuTop + 'px';
	menuDiv.style.zIndex = 50;
	menuDiv.style.left = menuLeft + 'px';
	menuDiv.style.height = '30px';
	LTSun.changeOpacity(30, menuDiv);

	menuDiv.onmouseover = function() { menuDiv.style.height = '30px'; LTSun.changeOpacity(100, menuDiv); };
	menuDiv.onmouseout = function()	{ menuDiv.style.height = '30px'; LTSun.changeOpacity(30, menuDiv); };

	LTSun.textEditorButtonInstances.push(menuDiv);
	document.body.appendChild(menuDiv);

	/*----------------------------------------------------------------------------------------
		Save Text
		---------
		This button calls on noinc-save.php with AJAX to save the text then displays a window
		with confirmation where options of publishing are presented.
	----------------------------------------------------------------------------------------*/
	document.getElementById(elementId + "_saveText").onclick = function()
	{
		LTSun.saveTextModule(LTSunSettings['bshe_indexphp_path'] + "/text/noinc-save.html", elementId, "", true, null, null);
	};


	/*----------------------------------------------------------------------------------------
		Publish Text
		------------
		Saves current text twice: once as published.htm and again as date-time.htm.
	----------------------------------------------------------------------------------------*/
	document.getElementById(elementId + "_publishText").onclick = function()
	{
		LTSun.showTextMessage(elementId, "Publishing Content...");
		LTSun.saveTextModule(LTSunSettings['bshe_indexphp_path'] + "/text/noinc-publish.html", elementId, "", false, null, null);
		LTSun.saveTextModule(LTSunSettings['bshe_indexphp_path'] + "/text/noinc-save.html", elementId, "", false,
		function()
		{
			LTSun.changeTextMessage(elementId, "Publish Complete");
			setTimeout("LTSun.hideTextMessage('"+elementId+"');", 2000);
		},
		function()
		{
			LTSun.changeTextMessage(elementId, "<span style='color:#ff0000;'>Publish ERROR!</span>");
			setTimeout("LTSun.hideTextMessage('"+elementId+"');", 1000);
		});
	};


	/*----------------------------------------------------------------------------------------
		Advanced Editor Text
		--------------------
		Load an HTML editor that has all the supreme HTML customizing features.
	----------------------------------------------------------------------------------------*/
	document.getElementById(elementId + "_advancedEditorText").onclick = function()
	{
		var advEditorURL = "";
		var pageId = LTSunSettings["selectText"+elementId+"PageId"];

		advEditorURL += LTSunSettings['bshe_indexphp_path'] + "/text/noinc-advEditor.html?css=" + LTSun.settings['cascadingStyleSheet'] +
						"&elementClass=" + $(elementId).className +
						"&pageId=" + LTSun.settings['bshe_templatename'] +
						"&elementId=" + elementId;

		LTSun.showWindow({
			size: "large",
			bgColor: "#ffffff",
			animationSpeed: 100,
			url: advEditorURL,
			okAvailable: false,
			cancelAvailable: false
		});
	};


	/*----------------------------------------------------------------------------------------
		Revisions for Text
		------------------
		Displays a window with all save history of the text editor instance. The user can
		restore any point thye wish guided by date and time markers.
	----------------------------------------------------------------------------------------*/
	document.getElementById(elementId + "_revisionsText").onclick = function()
	{
		var revisionsURL = "";
//		var pageId = LTSunSettings["selectText"+elementId+"PageId"];

		revisionsURL += LTSunSettings['bshe_indexphp_path'] + "/text/noinc-revisions.html?css=" + LTSun.settings['cascadingStyleSheet'] +
						"&elementClass=" + $(elementId).className +
						"&pageId=" + LTSun.settings['bshe_templatename']  +
						"&elementId=" + elementId;

		LTSun.showWindow({
			size: "large",
			bgColor: "#ffffff",
			animationSpeed: 100,
			url: revisionsURL,
			okAvailable: false,
			cancelAvailable: false
		});
	};


	/*----------------------------------------------------------------------------------------
		About LTSun-Engine
		------------------
		Opens an internal window which displays LightTheSun CMS About.
	----------------------------------------------------------------------------------------*/
/*	document.getElementById(elementId + "_logout").onclick = function()
	{
		//ログアウト
		location.href = location.href + '?bshe_specializer_auth=logout';
	};
*/
	/*----------------------------------------------------------------------------------------
	About LTSun-Engine
	------------------
	Opens an internal window which displays LightTheSun CMS About.
	----------------------------------------------------------------------------------------*/
	document.getElementById(elementId + "_menu").onclick = function()
	{
		//ログアウト
		location.href = LTSunSettings['bshe_indexphp_path'] + '/admin/index.html';
	};
	return true;
};


/*----------------------------------------------------------------------------------------
Image Module
---------------

----------------------------------------------------------------------------------------*/
LTSun_AddModule(initImageModule);

function initImageModule()
{

/*----------------------------------------------------------------------------------------
	Select Elements Editable
	------------------------
	Creates system for Image editing.

	Example: selectImageElement : "contactDiv,appleLevelDiv,footerDiv,etcDiv"
----------------------------------------------------------------------------------------*/
if(LTSun.settings['selectImageElement'])
{
	var selectImageElementArray = LTSun.settings['selectImageElement'];
	var selectImageElement;
	var elementLength = selectImageElementArray.length;

	for(var i = 0; elementLength > i; i++)
	{
		selectImageElementId = selectImageElementArray[i];

		if($(selectImageElementId))
			LTSun.createImageControl(selectImageElementId);
	}
}

return true;
}



/*----------------------------------------------------------------------------------------
Image Add
------------
Adds an element to a global variable for later processing in initImageModule().
----------------------------------------------------------------------------------------*/
LTSun.imageAdd = function(args)
{
LTSun.settings['selectImageElement'].push(args);
};


/*----------------------------------------------------------------------------------------
Create Image Control
-----------------------
Makes Image editable by adding an onClick event that opens a config window.
----------------------------------------------------------------------------------------*/
LTSun.createImageControl = function(elementId)
{
var elementObject = document.getElementById(elementId);

elementObject.onclick = function()
{
	var imgEditorURL = "";
	var pageId = LTSunSettings["selectImage"+elementId+"PageId"];
/*		var windowId = elementId + "_imgEditorWindow";
	var win;*/

	imgEditorURL += LTSunSettings['bshe_indexphp_path'] + "/image/noinc-uploader.html?css=" + LTSun.settings['cascadingStyleSheet'] +
					"&pageId=" + LTSun.settings['bshe_templatename'] +
					"&elementId=" + elementId;

	LTSun.showWindow({
		size: "small",
		bgColor: "#ffffff",
		bgFirstColor: "#a0c6ff",
		url: imgEditorURL
	});

	return false;

};

elementObject.style.cursor = "pointer";
};


/*----------------------------------------------------------------------------------------
Envoke Live Click
-----------------
Clicks as if the admin was not logged in.
----------------------------------------------------------------------------------------*/
LTSun.envokeLiveClick = function(elementId)
{
if(LTSunSettings[elementId]['href'].length > 3)
{
	if(LTSunSettings[elementId]['href'].indexOf("script:") == -1)
		LTSunSettings[elementId]['href'] = "javascript:window.location.href='" + LTSunSettings[elementId]['href'] + "';";

	setTimeout(LTSunSettings[elementId]['href'], 10);
}
};

/*----------------------------------------------------------------------------------------
Update Image
--------------
Creates new Image then updates current image on page with the new image.
----------------------------------------------------------------------------------------*/
LTSun.updateImage = function(elementId, imageFileName, imgHref, imgTitle, ymd)
{
LTSunSettings[elementId]['org_src'] = $(elementId).src;
LTSunSettings[elementId]['org_altText'] = $(elementId).alt;

$(elementId).src = imageFileName;
$(elementId).alt = imgTitle;

LTSunSettings[elementId]['src'] = imageFileName;
LTSunSettings[elementId]['href'] = imgHref;
LTSunSettings[elementId]['altText'] = imgTitle;
LTSunSettings[elementId]['ymd'] = ymd;

LTSunSettings[elementId]['updated'] = true;

LTSun.hideWindow({});
LTSun.hideLoadingWindow({});

/*	Windows.getFocusedWindow().close();*/
};

/*----------------------------------------------------------------------------------------
Revert Image
--------------
----------------------------------------------------------------------------------------*/
LTSun.revertImage = function(elementId)
{
$(elementId).src = LTSunSettings[elementId]['org_src'];
$(elementId).alt = LTSunSettings[elementId]['org_altText'];

LTSunSettings[elementId]['updated'] = false;

LTSun.hideWindow({});
LTSun.hideLoadingWindow({});

/*	Windows.getFocusedWindow().close();*/
};


/*----------------------------------------------------------------------------------------
Is Updated Image
--------------
----------------------------------------------------------------------------------------*/
LTSun.isUpdatedImage = function(elementId)
{
if(LTSunSettings[elementId]['updated'])
{
	return true;
} else {
	return false;
}
};

/*----------------------------------------------------------------------------------------
Publish Image
---------------
Copies unpublished.png to published.png making the file live on the site.
----------------------------------------------------------------------------------------*/
LTSun.publishImage = function(success)
{
if(success)
{
	LTSun.hideWindow({});
	LTSun.hideLoadingWindow({});
/*		$("imageUpdate_" + elementId).disabled = false;
	$("imageUpdate_" + elementId).value = "Update";
	$("imagePublish_" + elementId).disabled = false;
	$("imagePublish_" + elementId).value = "Publish";*/
}
else
{
	Windows.getFocusedWindow().close();
	Dialog.alert("<h1>Error: Image Publish Failed...</h1><br /><br />"+transport.responseText,
		{zIndex:1000, className:"alphacube", width:450, height:200, showProgress: false});

/*		$("imageUpdate_" + elementId).disabled = false;
	$("imageUpdate_" + elementId).value = "Update";
	$("imagePublish_" + elementId).disabled = false;
	$("imagePublish_" + elementId).value = "Publish";*/
}
};




