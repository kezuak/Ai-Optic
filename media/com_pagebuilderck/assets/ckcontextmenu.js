/**
 * @name		Context Menu CK
 * @copyright	Copyright (C) 2020. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 * @author		Cedric Keiflin - https://www.template-creator.com - https://www.joomlack.fr
 * Original script © 2015 Context Menu Madness! Demo by Nick Salloum
 */

(function () {

	"use strict";

	//////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////
	//
	// H E L P E R    F U N C T I O N S
	//
	//////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////

	/**
	 * Function to check if we clicked inside an element with a particular class
	 * name.
	 * 
	 * @param {Object} e The event
	 * @param {String} className The class name to check against
	 * @return {Boolean}
	 */
	function clickInsideElement(e, className) {
		var el = e.srcElement || e.target;
		if (el.classList.contains(className)) {
			return el;
		} else {
			while (el = el.parentNode) {
				if (el.classList && el.classList.contains(className)) {
					return el;
				}
			}
		}

		return false;
	}

	/**
	 * Get's exact position of event.
	 * 
	 * @param {Object} e The event passed in
	 * @return {Object} Returns the x and y position
	 */
	function getPosition(e) {
		var posx = 0;
		var posy = 0;

		if (!e)
			var e = window.event;
			parentPos = document.getElementById('workspaceparentck').getBoundingClientRect();

		if (e.pageX || e.pageY) {
			posx = e.pageX - parentPos.x - window.scrollX;
			posy = e.pageY - parentPos.y - window.scrollY;
		} else if (e.clientX || e.clientY) {
			posx = e.clientX + document.body.scrollLeft + document.documentElement.scrollLeft - parentPos.x - window.scrollX;
			posy = e.clientY + document.body.scrollTop + document.documentElement.scrollTop - parentPos.y - window.scrollY;
		}

		return {
			x: posx,
			y: posy
		}
	}

	//////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////
	//
	// C O R E    F U N C T I O N S
	//
	//////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////

	/**
	 * Variables.
	 */
//	var contextMenuClassName = "context-menu-ck";
//	var contextMenuItemClassName = "context-menu-ck__item";
	var contextMenuLinkClassName = "context-menu-ck__link";
	var contextMenuActive = "context-menu-ck--active";

//	var taskItemClassName = "rowck";
	var taskItemInContext;
	var taskColumnInContext;
	var taskRowInContext;

	var clickCoords;
	var clickCoordsX;
	var clickCoordsY;
	var parentPos;

	var menu = document.querySelector("#context-menu-ck");
	var menuItems = menu.querySelectorAll(".context-menu-ck__item");
	var menuState = 0;
	var menuWidth;
	var menuHeight;
//	var menuPosition;
//	var menuPositionX;
//	var menuPositionY;

	var windowWidth;
	var windowHeight;

	/**
	 * Initialise our application's code.
	 */
	function init() {
		contextListener();
		eventListener();
		keyupListener();
		resizeListener();
	}

	/**
	 * Listens for contextmenu events.
	 */
	function contextListener() {
		document.addEventListener("contextmenu", function (e) {
			taskRowInContext = clickInsideElement(e, 'rowck');
			taskColumnInContext = clickInsideElement(e, 'blockck');
			taskItemInContext = clickInsideElement(e, 'cktype');

			if (taskRowInContext) {
				// disable if ACL not allow
				var el = e.srcElement || e.target;
				if (el.classList.contains('cknoacl')) return false;

				e.preventDefault();
				fillMenu();
				toggleMenuOn();
				positionMenu(e);
			} else {
				taskRowInContext = null;
				toggleMenuOff();
			}
		});
	}

	/**
	 * Listens for click events.
	 */
	function eventListener() {
		document.addEventListener("click", function (e) {
			var clickeElIsLink = clickInsideElement(e, contextMenuLinkClassName);

			if (clickeElIsLink) {
				e.preventDefault();
				menuItemListener(clickeElIsLink);
			} else {
				var button = e.which || e.button;
				if (button === 1) {
					toggleMenuOff();
				}
			}
		});
		// position the submenu
		menu.querySelectorAll('.context-menu-ck__item.parent').forEach(listItem => listItem.addEventListener("mouseover", function (e) {
			positionSubmenu(listItem);
			highlightNode(listItem);
		}));
		menu.querySelectorAll('.context-menu-ck__item.parent').forEach(listItem => listItem.addEventListener("mouseleave", function (e) {
			removeHighlights();
		}));
		menu.querySelectorAll('.context-menu-ck__item.parent').forEach(listItem => listItem.addEventListener("click", function (e) {
			menu.classList.add('context-menu-ck__submenuopened');
			var submenu = listItem.querySelector('ul');
			if (! submenu.classList.contains('context-menu-ck__submenushow')) {
				if (menu.querySelector('.context-menu-ck__submenushow')) {
					var prevsubmenu = menu.querySelector('.context-menu-ck__submenushow');
					prevsubmenu.classList.remove('context-menu-ck__submenushow');
					prevsubmenu.style.display = "";
				}
				if (menu.querySelector('.context-menu-ck__item.active')) menu.querySelector('.context-menu-ck__item.active').classList.remove('active');
				submenu.classList.add('context-menu-ck__submenushow');
				listItem.classList.add('active');
				submenu.style.display = "block";
				positionSubmenu(listItem);
				highlightNode(listItem);
			} else {
				submenu.classList.remove('context-menu-ck__submenushow');
				listItem.classList.remove('active');
				submenu.style.display = "";
				removeHighlights();
				menu.classList.remove('context-menu-ck__submenuopened');
			}
		}));
	}

	/**
	 * Calculate the correct position for the submenu
	 * 
	 * @param node listItem
	 * @returns void
	 */
	function positionSubmenu(listItem) {
		var submenu = listItem.querySelector('ul');
		windowWidth = window.innerWidth;
		windowHeight = window.innerHeight;

		if ((windowWidth - parseInt(menu.style.left) - parentPos.x) < 480) {
			submenu.style.left = - 240 + "px";
		} else {
			submenu.style.left = 230 + "px";
		}

		// reset submenu position
		submenu.style.top = -25 + "px";
		var submenuHeight = submenu.offsetHeight + 4;
		var overflowH = (windowHeight - parseInt(submenu.getBoundingClientRect().top)) - submenuHeight;

		if ((windowHeight - 55 - parseInt(submenu.getBoundingClientRect().top)) < submenuHeight) {
			submenu.style.top = overflowH - 25 - 55 + "px";
		} else {
			submenu.style.top = -25 + "px";
		}
	}

	/**
	 * Visual help to target the focus
	 * 
	 * @param node listItem
	 * @returns void
	 */
	function highlightNode(listItem) {
		if (listItem.classList.contains('context-menu-ck_row')) {
			taskRowInContext.classList.add('ckhighlight');
		} else if (listItem.classList.contains('context-menu-ck_column')) {
			taskColumnInContext.classList.add('ckhighlight');
		} else if (listItem.classList.contains('context-menu-ck_item')) {
			taskItemInContext.classList.add('ckhighlight');
		} 
	}

	/**
	 * Remove the hightlight
	 * 
	 * @returns void
	 */
	function removeHighlights() {
		if(document.querySelector('.ckhighlight')) document.querySelector('.ckhighlight').classList.remove('ckhighlight');
	}

	/**
	 * Listens for keyup events.
	 */
	function keyupListener() {
		window.onkeyup = function (e) {
			if (e.keyCode === 27) {
				toggleMenuOff();
			}
		}
	}

	/**
	 * Window resize event listener
	 */
	function resizeListener() {
		window.onresize = function (e) {
			toggleMenuOff();
		};
	}

	/**
	 * Turns the custom context menu on.
	 */
	function toggleMenuOn() {
		if (menuState !== 1) {
			menuState = 1;
			menu.classList.add(contextMenuActive);
		}
	}

	/**
	 * 
	 * @param node taskItemInContext
	 * @returns void
	 */
	function fillMenu() {
		var rowEnabled = taskRowInContext ? 'list-item' : 'none';
		var columnEnabled = taskColumnInContext ? 'list-item' : 'none';
		var itemEnabled = taskItemInContext ? 'list-item' : 'none';
		menu.querySelector('.context-menu-ck_row').style.display = rowEnabled;
		menu.querySelector('.context-menu-ck_column').style.display = columnEnabled;
		menu.querySelector('.context-menu-ck_item').style.display = itemEnabled;
	}

	/**
	 * Turns the custom context menu off.
	 */
	function toggleMenuOff() {
		if (menuState !== 0) {
			menuState = 0;
			menu.classList.remove(contextMenuActive);
			menu.classList.remove('context-menu-ck__submenuopened');
			menu.querySelectorAll('ul').forEach(submenu => submenu.style.display = '');
			menu.querySelectorAll('li').forEach(listItem => listItem.classList.remove('active'));
		}
	}

	/**
	 * Positions the menu properly.
	 * 
	 * @param {Object} e The event
	 */
	function positionMenu(e) {
		clickCoords = getPosition(e);
		clickCoordsX = clickCoords.x;
		clickCoordsY = clickCoords.y;

		menuWidth = menu.offsetWidth + 4;
		menuHeight = menu.offsetHeight + 4;

		windowWidth = window.innerWidth;
		windowHeight = window.innerHeight;

		if ((windowWidth - clickCoordsX - parentPos.x) < menuWidth) {
			menu.style.left = windowWidth - menuWidth - parentPos.x + "px";
		} else {
			menu.style.left = clickCoordsX + "px";
		}

//		if ((windowHeight - clickCoordsY) < menuHeight) {
//			menu.style.top = windowHeight - menuHeight + "px";
//		} else {
			menu.style.top = clickCoordsY + "px";
//		}
	}

	/**
	 * Dummy action function that logs an action when a menu item link is clicked
	 * 
	 * @param {HTMLElement} link The link that was clicked
	 */
	function menuItemListener(link) {
		var action = link.getAttribute("data-action");
		if (action == 'parentItem') return;
		var actions = action.split('.');
		var target = actions[0];
		var task = actions[1];

		switch (target) {
			case 'row' :
				var targetEl = taskRowInContext;
				var listOfTasks = {
						'columns' : 'ckShowColumnsEdition',
						'fullwidth' : 'ckShowFullwidthRowEdition',
						'save' : 'ckSaveItem',
						'remove' : 'ckRemoveRow',
						'duplicate' : 'ckDuplicateRow',
						'edit' : 'ckShowCssPopup',
						'favorite' : 'ckShowFavoritePopup',
						'stylecopy' : 'ckCopyStyles',
						'stylepaste' : 'ckPasteStyles'
				};
				break;
			case 'column' :
				var targetEl = taskColumnInContext;
				var listOfTasks = {
						'remove' : 'ckRemoveBlock',
						'duplicate' : 'ckDuplicateColumn',
						'edit' : 'ckShowCssPopup',
						'favorite' : 'ckShowFavoritePopup',
						'stylecopy' : 'ckCopyStyles',
						'stylepaste' : 'ckPasteStyles'
				};
				break;
			case 'item' :
				var targetEl = taskItemInContext;
				var listOfTasks = {
						'save' : 'ckSaveItem',
						'remove' : 'ckRemoveItem',
						'clearstyles' : 'ckClearStyles',
						'duplicate' : 'ckDuplicateItem',
						'edit' : 'ckShowEditionPopup',
						'favorite' : 'ckShowFavoritePopup',
						'stylecopy' : 'ckCopyStyles',
						'stylepaste' : 'ckPasteStyles'
				};
				break;
			default :
				console.error('No target element found');
				return;
				break;
		}
		var targetId = targetEl.getAttribute("id");
		var targetTask = listOfTasks[task];
		toggleMenuOff();
		window[targetTask](targetId);
	}

	/**
	 * Run the app.
	 */
	init();

})();