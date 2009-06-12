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

/*--------------------------------------------------------------------------------------
	LTSun CMS (LightTheSun Content Managment Solution)
	--------------------------------------------------
	Author: Stephen L. Blum
	Version: 0.1.2 Alpha
	Last Modified: 4:04 PM 02/03/2007
--------------------------------------------------------------------------------------*/


function LTSun_Engine()
{
	/*----------------------------------------------------------------------------------------
		Initiation Functions
		--------------------
		This will hold all the init procedures for each of the modules loaded from the
		"ltsun.inc.php" file.
	----------------------------------------------------------------------------------------*/
	this.initiationFunctions = new Array();

	this.onResizeFunctions = new Array();
	this.onLoadFunctions = new Array();
}

function LTSun_AddModule(varFunction)
{
	LTSun.initiationFunctions.push(varFunction);
}

function AddOnResize(varFunction)
{
	LTSun.onResizeFunctions.push(varFunction);
}
function AddOnLoadFunctions(varFunction)
{
	LTSun.onLoadFunctions.push(varFunction);
}

LTSun_Engine.prototype =
{
	init : function (settings)
	{
		var i = 0;
		/*----------------------------------------------------------------------------------------
			Settings
			--------
			This is an Array that is created and passed into the init function. These are
			standard global Engine settings used to configure the LTSun Engine.
		----------------------------------------------------------------------------------------*/
		this.settings = settings;

		/*----------------------------------------------------------------------------------------
			Initiation Functions Loop
			-------------------------
			Loops through all functions pushed into an array outside of this file. These array
			additions are pushed by modules that are included in the "ltsun.inc.php" file.
		----------------------------------------------------------------------------------------*/
		for(i=0;i<this.initiationFunctions.length;i++)
			this.initiationFunctions[i]();


		window.onresize = function()
		{
			for(var i=0;i<LTSun.onResizeFunctions.length;i++)
				LTSun.onResizeFunctions[i]();
		};

		for(i=0;i<this.onLoadFunctions.length;i++)
			this.onLoadFunctions[i]();

		clearInterval(loadingWindowInterval);
		LTSun.resizeLoadingWindow();
		LTSun.hideLoadingWindow({});

		return true;
	},


	/*----------------------------------------------------------------------------------------
		Show Info Window
		----------------
		Shows a window with spcified content.
	----------------------------------------------------------------------------------------*/
	showInfoWindow : function(args) /* width, height, title, elementHTML, url */
	{
		var windowId = (args['windowId'] ? args['windowId'] : Application.getNewId());

		if(!$(windowId))
		{
			var win = new Window
			(
				windowId,
				{
					className: "mac_os_x",
					width: args['width'],
					height: args['height'],
					minWidth: args['width'],
					minHeight: args['height'],
					resizable: args['resizeable'],
					zIndex: 100,
					title: args['title'].replace(/_/gi, " "),
					showEffect: Effect.PhaseIn,
					hideEffect: Effect.PhaseOut,
					draggable: true,
					wiredDrag: false
				}
			);

			if(args['elementHTML'])
				win.getContent().innerHTML = document.getElementById(args['elementHTML']).innerHTML.replace(/window_id_x_close/gi, windowId);
			else if(args['url'])
				win.setURL(args['url']);
			else if(args['html'])
				win.getContent().innerHTML = args['html'];
			else
				alert("Improper call to LTSun.showInfoWindow()\n\nPlease use a URL or elementId to pass HTML");

			win.setDestroyOnClose();

			//if(args['showCenter'])
				win.showCenter(false,(Math.random()*500)-(Math.random()*10),(Math.random()*500)-(Math.random()*10));
			//else
				//win.show();
		}
	},




	/*----------------------------------------------------------------------------------------
		Show Window
		-----------
		Multi-use window that usually displays a form to edit the content.
		--------------------------------------------------------------------------------------
		size              = small, medium, large
		bgColor           = Background Color
		bgFirstColor      = Color that is faded quickly to bgColor
		screenLockBgColor = Full Screen Overlay
		animationSpeed    = percent
		zIndex            = CSS Z-Index
		html              = html content
		url               = iframe src
		okLabel           = OK or Continue or Upload
		cancelLabel       = Cancel or Quit or Exit or End
		okAction          = onclick
		cancelAction      = onclick
		okAvailable       = true
		cancelAvailable   = true
		okStyle           = style attribute
		cancelStyle       = style attribute
		animateOnExit     = true
		animationExitType = standard, fadeAll
		afterFinish       = after the animation is finished
		onCreate          = after all elements have been created
		draggable		  = false
	----------------------------------------------------------------------------------------*/
	showWindow : function (args)
	{
		var bgDiv = document.createElement("div");
		var fgDiv = document.createElement("div");
		var closeDiv = document.createElement("div");
		var contentDiv = document.createElement("div");
		var screenLockDiv = document.createElement("div");

		var screenCenterTop = Math.ceil(window.getSize().y/2) + window.getScroll().y;
//		var screenCenterTop = Math.ceil(document.body.scrollHeight==0?window.innerHeight/2:document.body.scrollHeight/2) + document.body.scrollTop;
		var screenCenterLeft = Math.ceil(window.getSize().x / 2) + window.getScroll().x;

		var width = 0, height = 0;
		var html = "";

		/*-------------
		Default Values
		-------------*/
		if(!args['size']) args['size'] = "small";
		if(!args['bgFirstColor']) args['bgFirstColor'] = "#000000";
		if(!args['bgColor']) args['bgColor'] = "#ffffff";
		if(!args['screenLockBgColor']) args['screenLockBgColor'] = "#000000";
		if(!args['zIndex']) args['zIndex'] = "1000";
		if(!args['html']) args['html'] = "";
		if(!args['url']) args['url'] = "";
		if(!args['animationSpeed']) args['animationSpeed'] = 100;
		if(!args['animateOnExit']) args['animateOnExit'] = true;
		if(!args['animationExitType']) args['animationExitType'] = "standard";

		if(!args['okLabel']) args['okLabel'] = "OK";
		if(!args['cancelLabel']) args['cancelLabel'] = "Cancel";
		if(!args['okStyle']) args['okStyle'] = "color:#000000;width:90px;";
		if(!args['cancelStyle']) args['cancelStyle'] = "color:#000000;width:90px;";

		if(!args['okAction']) args['okAction'] = "alert('"+args['okLabel']+" clicked.');";
		if(!args['cancelAction']) args['cancelAction'] = "LTSun.hideWindow({animationSpeed: "+args['animationSpeed']+",animateOnExit: "+args['animateOnExit']+",animationExitType: '"+args['animationExitType']+"'});";

		if(!args['okAvailable']) args['okAvailable'] = "available";
		if(!args['cancelAvailable']) args['cancelAvailable'] = "available";

		if(!args['afterFinish']) args['afterFinish'] = function(){};
		if(!args['onCreate']) args['onCreate'] = function(){};

		if(!args['draggable']) args['draggable'] = false;

		/*--------------
		Args Validation
		--------------*/
		if(args['okAction'].indexOf("\"") != -1)
			args['okAction'] = "alert('[okAction] -> contains Double Quotes. Please only use Single Quotes.');";
		if(args['cancelAction'].indexOf("\"") != -1)
			args['cancelAction'] = "alert('[cancelAction] -> contains Double Quotes. Please only use Single Quotes.');";

		args['okLabel'].replace(/\"/g, "");
		args['cancelLabel'].replace(/\"/g, "");

		/*-------------
		PNG Image Dims
		-------------*/
		switch(args['size'])
		{
			case "small":
				width = 396;
				height = 210;
				break;
			case "medium":
				width = 396;
				height = 210;
				break;
			case "large":
				width = 1002;
				height = 502;
				break;
		}


		/*--------------
		Screen Lock Div
		--------------*/
		screenLockDiv.id = "window_screenLockDiv";
		screenLockDiv.style.opacity = 0.0;
		screenLockDiv.style.filter = "alpha(opacity=0)";
		screenLockDiv.style.width = window.getScroll().x + 'px';
		screenLockDiv.style.height = window.getScroll().y + 'px';
		screenLockDiv.style.position = "absolute";
		screenLockDiv.style.top = '0px';
		screenLockDiv.style.left = '0px';
		screenLockDiv.style.zIndex = args['zIndex'] - 4;
		screenLockDiv.style.backgroundColor = args['screenLockBgColor'];

		AddOnResize(function()
		{
			if($("window_screenLockDiv"))
				$("window_screenLockDiv").style.width = document.body.clientWidth;
		});


		/*--------
		Close Div
		--------*/
		closeDiv.id = "window_closeDiv";
		closeDiv.style.opacity = 0;
		closeDiv.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + LTSun.settings['bshe_indexphp_path'] + "/media/windows/close.png') alpha(opacity=0)";
		closeDiv.innerHTML = "<img style='filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0);' src='" + LTSun.settings['bshe_indexphp_path'] + "/media/windows/close.png' border='0' width='"+74+"' height='"+55+"' />";
		closeDiv.style.cursor = "pointer";
		closeDiv.style.width = '74px';
		closeDiv.style.height = '55px';
		closeDiv.style.position = "absolute";
		closeDiv.style.top = (screenCenterTop - Math.ceil(height / 2)) + 'px';
		closeDiv.style.left = (screenCenterLeft - Math.ceil(width / 2) + width - 75) + 'px';
		closeDiv.style.zIndex = args['zIndex'] - 3;

		closeDiv.onclick = function()
		{
			LTSun.hideWindow({
				animationSpeed: args['animationSpeed'],
				animateOnExit: args['animateOnExit'],
				animationExitType: args['animationExitType']
			});
		};

		/*-------------
		Background Div
		-------------*/
		bgDiv.id = "window_bgDiv";
		bgDiv.style.opacity = 0;
		bgDiv.style.filter = "alpha(opacity=0)";
		bgDiv.style.width = (width - 20) + 'px';
		bgDiv.style.height = '0px';
		bgDiv.style.position = "absolute";
		bgDiv.style.lineHeight = '1px';
		bgDiv.style.fontSize = '1px';
		bgDiv.style.top = (screenCenterTop - Math.ceil((height-20) / 2)) + 'px';
		bgDiv.style.left = (screenCenterLeft - Math.ceil((width-20) / 2)) + 'px';
		bgDiv.style.zIndex = args['zIndex'] - 2;
		bgDiv.style.backgroundColor = args['bgFirstColor'];

		/*------------
		Forground Div
		------------*/
		fgDiv.id = "window_fgDiv";
		fgDiv.style.opacity = 0;
		fgDiv.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + LTSun.settings['bshe_indexphp_path'] + "/media/windows/"+args['size']+"-window.png') alpha(opacity=0)";
		fgDiv.innerHTML = "<img style='filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0)' src='" + LTSun.settings['bshe_indexphp_path'] + "/media/windows/"+args['size']+"-window.png' border='0' width='"+width+"' height='"+height+"' />";
		fgDiv.style.width = width + 'px';
		fgDiv.style.height = height + 'px';
		fgDiv.style.position = "absolute";
		fgDiv.style.top = (screenCenterTop - Math.ceil(height / 2)) + 'px';
		fgDiv.style.left = (screenCenterLeft - Math.ceil(width / 2)) + 'px';
		fgDiv.style.zIndex = args['zIndex'] - 1;

		/*----------
		Content Div
		----------*/
		contentDiv.id = "window_contentDiv";
		contentDiv.style.opacity = '0px';
		contentDiv.style.filter = "alpha(opacity=0)";
		contentDiv.style.width = (width - 30) + 'px';
		contentDiv.style.height = (height - 30) + 'px';
		contentDiv.style.position = "absolute";
		contentDiv.style.top = (screenCenterTop - Math.ceil((height-30) / 2)) + 'px';
		contentDiv.style.left = (screenCenterLeft - Math.ceil((width-30) / 2)) + 'px';
		contentDiv.style.zIndex = args['zIndex'];

		html += "<table style=\"border-collapse:collapse;width:"+(width-31)+"px;height:"+(height-31)+"px\"><tr><td width=\""+(width-31)+"\">"+args['html'];

		if(args['url'].length > 0)
		{
			html += "<iframe id=\"utilityWindowIframe\" allowtransparency=\"true\" frameborder=\"0\" height=\""+(height - 35)+"px\" width=\""+(width - 35)+"px\" marginheight=\"0px\" marginwidth=\"0px\" scrolling=\"auto\" style=\"display: block;\"></iframe>";
		}
		else
		{
			if(args['okAvailable']=="available" || args['cancelAvailable']=="available") html += "<br /><br /><center>";
				if(args['okAvailable']=="available") html += "<input type=\"button\" style=\""+args['okStyle']+"\" value=\""+args['okLabel']+"\" onclick=\""+args['okAction']+"\" />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
				if(args['cancelAvailable']=="available") html += "<input type=\"button\" style=\""+args['cancelStyle']+"\" value=\""+args['cancelLabel']+"\" onclick=\""+args['cancelAction']+"\" />";
			if(args['okAvailable']=="available" || args['cancelAvailable']=="available") html += "</center>";
		}

		html += "</td></tr></table>";

		contentDiv.innerHTML = html;

		document.body.appendChild(screenLockDiv);
		document.body.appendChild(fgDiv);
		document.body.appendChild(bgDiv);
		document.body.appendChild(closeDiv);
		document.body.appendChild(contentDiv);

		if(args['url'].length > 0)
			$("utilityWindowIframe").src = args['url'];

		$("window_contentDiv").setStyle({
			overflow: "-moz-scrollbars-vertical",
			overflow: "auto",
			overflowX: "hidden"
		});

		AddOnResize(function()
		{
			if($("window_contentDiv") && $("window_fgDiv") && $("window_bgDiv"))
			{
				//screenCenterTop = Math.ceil(document.body.clientHeight!=document.body.scrollHeight?document.body.clientHeight/2:window.innerHeight/2) +  + (document.documentElement.scrollTop);
				//screenCenterTop = Math.ceil(document.body.scrollHeight==0?window.innerHeight/2:document.body.scrollHeight/2) + document.body.scrollTop;

				//screenCenterLeft = Math.ceil(document.body.clientWidth / 2) + document.body.scrollLeft;
				screenCenterTop = Math.ceil(window.getSize().y/2) + window.getScroll().y;
//				var screenCenterTop = Math.ceil(document.body.scrollHeight==0?window.innerHeight/2:document.body.scrollHeight/2) + document.body.scrollTop;
				screenCenterLeft = Math.ceil(window.getSize().x / 2) + window.getScroll().x;

				$("window_contentDiv").style.top = (screenCenterTop - Math.ceil((height-30) / 2)) + 'px';
				$("window_contentDiv").style.left = (screenCenterLeft - Math.ceil((width-30) / 2)) + 'px';

				$("window_fgDiv").style.top = (screenCenterTop - Math.ceil(height / 2)) + 'px';
				$("window_fgDiv").style.left = (screenCenterLeft - Math.ceil(width / 2)) + 'px';

				$("window_closeDiv").style.top = (screenCenterTop - Math.ceil(height / 2) - 23) + 'px';
				$("window_closeDiv").style.left = (screenCenterLeft - Math.ceil(width / 2) + width - 75) + 'px';

				$("window_bgDiv").style.top = (screenCenterTop - Math.ceil((height-20) / 2)) + 'px';
				$("window_bgDiv").style.left = (screenCenterLeft - Math.ceil((width-20) / 2)) + 'px';
			}
		});

		args['onCreate']();

		new Effect.Opacity(screenLockDiv, {duration: 0.2 * (100/args['animationSpeed']), from: 0.0, to: 0.6,
			afterFinish: function()
			{
				new Effect.Parallel([
						new Effect.Opacity(fgDiv, {sync: true, from: 0.0, to: 1.0}),
						new Effect.Opacity(bgDiv, {sync: true, from: 0.0, to: 1.0}),
						new Effect.Morph(bgDiv, {sync: true, style: "background:"+args['bgColor']}),
						new Effect.Scale(bgDiv, 100.0, {sync: true, scaleX: false, scaleFromCenter: true, scaleMode: { originalHeight: height-20, originalWidth: width }, scaleFrom: 30.0})
				], {duration: 0.4 * (100/args['animationSpeed']), afterFinish: function()
				{
					new Effect.Parallel([
						new Effect.Opacity(closeDiv, {sync: true, from: 0.0, to: 1.0}),
						new Effect.Move(closeDiv,{sync: true, x: 0, y: -23, mode: 'relative'}),
						new Effect.Opacity(contentDiv, {sync: true, from: 0.0, to: 1.0})
					], {duration: 0.8 * (100/args['animationSpeed']), afterFinish: function()
					{
						args['afterFinish']();
					}});
				}});
			}
		});

		/*-------------------
		Make Window Dragable
		-------------------*/
		/*new Draggable('id_of_element',[options]);*/
	},


	/*----------------------------------------------------------------------------------------
		Hide Window
		-----------
		Hide the window that is currently in use. This will destroy all elements that were
		created. Exit animation is optional.
		--------------------------------------------------------------------------------------
		animateOnExit        = true
		animationSpeed       = percent
		animationExitType    = standard, fadeAll
	----------------------------------------------------------------------------------------*/
	hideWindow : function(args)
	{
		/*-------------
		Default Values
		-------------*/
		if(!args['animateOnExit']) args['animateOnExit'] = true;
		if(!args['animationSpeed']) args['animationSpeed'] = 100;
		if(!args['animationExitType']) args['animationExitType'] = "standard";

		/*--------------
		Animate On Exit
		--------------*/
		if(args['animateOnExit'])
		{
			switch(args['animationExitType'])
			{
				case "standard":
					new Effect.Move($("window_closeDiv"),{duration: 0.1 * (100/args['animationSpeed']), x: 0, y: -4, mode: 'relative', afterFinish: function()
					{
						new Effect.Parallel([
							new Effect.Opacity($("window_closeDiv"), {sync: true, from: 1.0, to: 0.0}),
							new Effect.Move($("window_closeDiv"),{sync: true, x: 0, y: 23, mode: 'relative'}),
							new Effect.Opacity($("window_contentDiv"), {sync: true, from: 1.0, to: 0.0})
						], {duration: 0.4 * (100/args['animationSpeed']), afterFinish: function()
						{
							if($("window_closeDiv")) $("window_closeDiv").style.display = "none";
							if($("window_contentDiv")) $("window_contentDiv").style.display = "none";
							new Effect.Parallel([
									new Effect.Opacity($("window_fgDiv"), {sync: true, from: 1.0, to: 0.0}),
									new Effect.Opacity($("window_bgDiv"), {sync: true, from: 1.0, to: 0.0}),
									new Effect.Scale($("window_bgDiv"), 0.0, {sync: true, scaleX: false, scaleFromCenter: true, scaleFrom: 100.0})
							], {duration: 0.3 * (100/args['animationSpeed']), afterFinish: function()
							{
								if($("window_bgDiv")) $("window_bgDiv").style.display = "none";
								if($("window_fgDiv")) $("window_fgDiv").style.display = "none";
								new Effect.Opacity($("window_screenLockDiv"), {duration: 0.2 * (100/args['animationSpeed']), from: 0.6, to: 0.0, afterFinish: function()
								{
									if($("window_closeDiv")) document.body.removeChild($("window_closeDiv"));
									if($("window_contentDiv")) document.body.removeChild($("window_contentDiv"));
									if($("window_bgDiv")) document.body.removeChild($("window_bgDiv"));
									if($("window_fgDiv")) document.body.removeChild($("window_fgDiv"));
									if($("window_screenLockDiv")) document.body.removeChild($("window_screenLockDiv"));
								}});
							}});
						}});
					}});

					break;

				case "fadeAll":
					break;
			}
		}
		else
		{
			LTSun.destroyWindow();
		}
	},

	/*----------------------------------------------------------------------------------------
		Destroy Window
		--------------
		Destroys All Window Elements
	----------------------------------------------------------------------------------------*/
	destroyWindow : function()
	{
		document.body.removeChild($("window_closeDiv"));
		document.body.removeChild($("window_contentDiv"));
		document.body.removeChild($("window_bgDiv"));
		document.body.removeChild($("window_fgDiv"));
		document.body.removeChild($("window_screenLockDiv"));
	},


	/*----------------------------------------------------------------------------------------
		Morph Window
		------------
		Animates window in its full state.
		--------------------------------------------------------------------------------------
		bgColor         = style
		fgColor         = style
		textElementIDs  = array of text IDs (turns to fgColor)
		animationSpeed  = percent
		afterFinish     = function after animation has finished
	----------------------------------------------------------------------------------------*/
	morphWindow : function(args)
	{
		/*-------------
		Default Values
		-------------*/
		if(!args['animationSpeed']) args['animationSpeed'] = 100;
		if(!args['afterFinish']) args['afterFinish'] = function(){};
		if(!args['bgColor']) args['bgColor'] = "#ff0000";
		if(!args['fgColor']) args['fgColor'] = "#ffffff";
		if(!args['textElementIDs']) args['textElementIDs'] = new Array();

		/*------------
		Animate Error
		------------*/
		for(var i=0;i<args['textElementIDs'].length;i++)
			new Effect.Morph(args['textElementIDs'][i], {duration: 0.5 * (100/args['animationSpeed']), style: "color:"+args['fgColor']});

		new Effect.Morph("window_bgDiv", {duration: 0.5 * (100/args['animationSpeed']), style: "background:"+args['bgColor'],
			afterFinish: function()
			{
				args['afterFinish']();
			}
		});
	},


	/*----------------------------------------------------------------------------------------
		Show Loading Window
		-------------------
		Displays Loading Window
		--------------------------------------------------------------------------------------
		zIndex          = z-index style
		afterFinish     = function that will execute after the loading screen appears.
		animationSpeed  = speed for fading
	----------------------------------------------------------------------------------------*/
	showLoadingWindow : function(args)
	{
		var loadingDiv = document.createElement("div");
		var landingPadDiv = document.createElement("div");
		var screenLockDiv = document.createElement("div");

//		var screenCenterTop = Math.ceil(document.body.clientHeight!=document.body.scrollHeight?document.body.clientHeight/2:window.innerHeight/2) + document.body.scrollTop;
		//var screenCenterTop = Math.ceil(document.body.scrollHeight==0?window.innerHeight/2:document.body.scrollHeight/2) + document.body.scrollTop;
		//var screenCenterLeft = Math.ceil(document.body.clientWidth / 2) + document.body.scrollLeft;
		var screenCenterTop = Math.ceil(window.getSize().y/2) + window.getScroll().y;
//		var screenCenterTop = Math.ceil(document.body.scrollHeight==0?window.innerHeight/2:document.body.scrollHeight/2) + document.body.scrollTop;
		var screenCenterLeft = Math.ceil(window.getSize().x / 2) + window.getScroll().x;
		/*-------------
		Default Values
		-------------*/
		if(!args['zIndex']) args['zIndex'] = 2000;
		if(!args['afterFinish']) args['afterFinish'] = function(){};
		if(!args['animationSpeed']) args['animationSpeed'] = 100;

		/*----------------------
		Loading Screen Lock Div
		----------------------*/
		screenLockDiv.id = "loadingWindow_screenLockDiv";
		if(args['animationSpeed'] < 500)
		{
			screenLockDiv.style.opacity = 0.0;
			screenLockDiv.style.filter = "alpha(opacity=0)";
		}
		screenLockDiv.style.width = window.getScroll().x + 'px';
		screenLockDiv.style.height = window.getScroll().y + 'px';
		screenLockDiv.style.position = "absolute";
		screenLockDiv.style.top = '0px';
		screenLockDiv.style.left = '0px';
		screenLockDiv.style.zIndex = args['zIndex'] - 2;
		screenLockDiv.style.backgroundColor = "transparent";
		screenLockDiv.style.backgroundImage = "url(" + LTSunSettings['bshe_indexphp_path'] + "/media/windows/screen-lock2.gif)";

		AddOnResize(function()
		{
			if($("loadingWindow_screenLockDiv"))
				$("loadingWindow_screenLockDiv").style.width = document.body.clientWidth;
		});

		/*--------------
		Landing Pad Div
		--------------*/
		landingPadDiv.id = "window_landingPadDiv";
		if(args['animationSpeed'] < 500)
			landingPadDiv.style.opacity = 0;
		landingPadDiv.style.filter = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='" + LTSunSettings['bshe_indexphp_path'] + "/media/windows/loading-pad.png')" + (args['animationSpeed'] < 500?" alpha(opacity=0)":"");
		landingPadDiv.innerHTML = "<img style='filter:progid:DXImageTransform.Microsoft.Alpha(opacity=0)' src='" + LTSunSettings['bshe_indexphp_path'] + "/media/windows/loading-pad.png' border='0' width='60' height='60' />";
		landingPadDiv.style.width = 60 + 'px';
		landingPadDiv.style.height = 60 + 'px';
		landingPadDiv.style.position = "absolute";
		landingPadDiv.style.top = (screenCenterTop - 30) + 'px';
		landingPadDiv.style.left = (screenCenterLeft - 30) + 'px';
		landingPadDiv.style.zIndex = args['zIndex'] - 1;
/*alert(
	  "document.body.scrollHeight: " + document.body.scrollHeight + "\n" +
	  "document.body.scrollTop: " + document.body.scrollTop + "\n" +
	  "document.body.offsetHeight: " + document.body.offsetHeight + "\n" +
	  "document.body.clientHeight: " + document.body.clientHeight + "\n" +
	  "window.innerHeight: " + window.innerHeight + "\n" +
	  "screenCenterTop: " + screenCenterTop + "\n"
	  );
*/

		/*----------
		Loading Div
		----------*/
		loadingDiv.id = "window_loadingDiv";
		if(args['animationSpeed'] < 500)
		{
			loadingDiv.style.opacity = 0;
			loadingDiv.style.filter = "alpha(opacity=0)";
		}
		loadingDiv.style.width = 42 + 'px';
		loadingDiv.style.height = 42 + 'px';
		loadingDiv.style.position = "absolute";
		loadingDiv.style.top = (screenCenterTop - 21) + 'px';
		loadingDiv.style.left = (screenCenterLeft - 21) + 'px';
		loadingDiv.style.zIndex = args['zIndex'];
		loadingDiv.innerHTML = "<img src=\"" + LTSunSettings['bshe_indexphp_path'] + "/media/loading.png\" width=\"41\" height=\"41\" alt=\"Loading...\" />";

		document.body.appendChild(screenLockDiv);
		document.body.appendChild(landingPadDiv);

		if(args['animationSpeed'] < 500)
		{
			new Effect.Parallel([
				new Effect.Opacity($("loadingWindow_screenLockDiv"), {sync: true, from: 0.0, to: 0.8}),
				new Effect.Opacity($("window_landingPadDiv"), {sync: true, from: 0.0, to: 1.0})
			], {duration: 0.3 * (100/args['animationSpeed']), afterFinish: function()
			{
				document.body.appendChild(loadingDiv);
				AddOnResize(function()
				{
					if($("window_landingPadDiv") && $("window_loadingDiv"))
					{
						//screenCenterTop = Math.ceil(document.body.clientHeight!=document.body.scrollHeight?document.body.clientHeight/2:window.innerHeight/2) + document.body.scrollTop;
						//screenCenterLeft = Math.ceil(document.body.clientWidth / 2) + document.body.scrollLeft;
						screenCenterTop = Math.ceil(window.getSize().y/2) + window.getScroll().y;
						screenCenterLeft = Math.ceil(window.getSize().x / 2) + window.getScroll().x;

						$("window_landingPadDiv").style.top = screenCenterTop - 30;
						$("window_landingPadDiv").style.left = screenCenterLeft - 30;

						$("window_loadingDiv").style.top = screenCenterTop - 21;
						$("window_loadingDiv").style.left = screenCenterLeft - 21;
					}
				});
				new Effect.Opacity($("window_loadingDiv"), {duration: 0.2 * (100/args['animationSpeed']), from: 0.0, to: 1.0,
					afterFinish: function()
					{
						args['afterFinish']();
					}
				});

			}});
		}
		else
		{
			document.body.appendChild(loadingDiv);
			args['afterFinish']();
		}
	},



	/*----------------------------------------------------------------------------------------
		Hide Loading Window
		-------------------
		Destroys Loading Window
		--------------------------------------------------------------------------------------
		afterFinish     = function that will execute after the loading screen appears.
		animationSpeed  = speed for fading
	----------------------------------------------------------------------------------------*/
	hideLoadingWindow : function(args)
	{
		/*-------------
		Default Values
		-------------*/
		if(!args['afterFinish']) args['afterFinish'] = function(){};
		if(!args['animationSpeed']) args['animationSpeed'] = 100;

		LTSun.resizeLoadingWindow();

		new Effect.Opacity($("window_loadingDiv"), {duration: 0.2 * (100/args['animationSpeed']), from: 1.0, to: 0.0,
			afterFinish: function()
			{
				LTSun.resizeLoadingWindow();

				new Effect.Parallel([
					new Effect.Opacity($("window_landingPadDiv"), {sync: true, from: 1.0, to: 0.0}),
					new Effect.Opacity($("loadingWindow_screenLockDiv"), {sync: true, from: 1.0, to: 0.0})
				], {duration: 0.3 * (100/args['animationSpeed']), afterFinish: function()
				{
					args['afterFinish']();
					document.body.removeChild($("window_loadingDiv"));
					document.body.removeChild($("window_landingPadDiv"));
					document.body.removeChild($("loadingWindow_screenLockDiv"));
				}});
			}
		});
	},


	/*----------------------------------------------------------------------------------------
		Resize Loading Window
		---------------------
		Resizes Loading Window: The main purpose of this function is due to the client's
		window size changes during the loading process, therefore we always want the screen
		lock to fill the entire window.
	----------------------------------------------------------------------------------------*/
	resizeLoadingWindow : function()
	{
		if($("loadingWindow_screenLockDiv").style.width != window.getScroll().x)
			$("loadingWindow_screenLockDiv").style.width = window.getScroll().x+'px';

		if($("loadingWindow_screenLockDiv").style.height != window.getScroll().y)
			$("loadingWindow_screenLockDiv").style.height = window.getScroll().y+'px';
	},


	/*----------------------------------------------------------------------------------------
		Change Element Opacity
		----------------------
		Simply sets the element's opacity.
	----------------------------------------------------------------------------------------*/
	changeOpacity : function(opacity, elementObject)
	{
		elementObject.style.opacity = (opacity / 100);
		elementObject.style.MozOpacity = (opacity / 100);
		elementObject.style.KhtmlOpacity = (opacity / 100);
		elementObject.style.filter = "alpha(opacity=" + opacity + ")";
	},




	/*----------------------------------------------------------------------------------------
		Get Element Width
		-----------------
		Simply gets element width by ID.
	----------------------------------------------------------------------------------------*/
	getElementWidth : function (elementId)
	{
		if(document.getElementById)
			var elementObject = document.getElementById(elementId);
		else if(document.all)
			var elementObject = document.all[elementId];

		var xPos = elementObject.offsetWidth;

		return xPos;
	},


	/*----------------------------------------------------------------------------------------
		Get Element Height
		------------------
		Simply gets element height by ID.
	----------------------------------------------------------------------------------------*/
	getElementHeight : function (elementId)
	{
		if(document.getElementById)
			var elementObject = document.getElementById(elementId);
		else if(document.all)
			var elementObject = document.all[elementId];

		var xPos = elementObject.offsetHeight;

		return xPos;
	},


	/*----------------------------------------------------------------------------------------
		Get Element Left
		----------------
		Simply gets element left by ID.
	----------------------------------------------------------------------------------------*/
	getElementLeft : function(elementId)
	{
		if(document.getElementById)
			var elementObject = document.getElementById(elementId);
		else if (document.all)
			var elementObject = document.all[elementId];

		var xPos = elementObject.offsetLeft;
		var tempEl = elementObject.offsetParent;

		while (tempEl != null)
		{
			xPos += tempEl.offsetLeft;
			tempEl = tempEl.offsetParent;
		}

		return xPos;
	},


	/*----------------------------------------------------------------------------------------
		Get Element Top
		---------------
		Simply gets element top by ID.
	----------------------------------------------------------------------------------------*/
	getElementTop : function(elementId)
	{
		if(document.getElementById)
			var elementObject = document.getElementById(elementId);
		else if (document.all)
			var elementObject = document.all[elementId];

		var yPos = elementObject.offsetTop;
		var tempEl = elementObject.offsetParent;

		while (tempEl != null)
		{
			yPos += tempEl.offsetTop;
			tempEl = tempEl.offsetParent;
		}

		return yPos;
	}


};


/*----------------------------------------------------------------------------------------
	Main Declaration
	----------------
	Create the master application.
----------------------------------------------------------------------------------------*/
var LTSun = new LTSun_Engine();

/*----------------------------------------------------------------------------------------
	Loading Window
	--------------
	So the Must wait till the Editor Loads
----------------------------------------------------------------------------------------*/
//	LTSun.showLoadingWindow({animationSpeed: 600});
//	var loadingWindowInterval = setInterval(LTSun.resizeLoadingWindow, 10);
var loadingWindowInterval;

/*----------------------------------------------------------------------------------------
	Load LTSun Engine
	-----------------
	First Load LTSun Engine, then load all the modules.
----------------------------------------------------------------------------------------*/
//window.onload = function(){LTSun.init(LTSunSettings);};
