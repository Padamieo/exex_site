 /*!
 * Buttons helper for fancyBox
 * version: 1.0.5 (Mon, 15 Oct 2012)
 * @requires fancyBox v2.0 or later
 *
 * Usage:
 *     $(".fancybox").fancybox({
 *         helpers : {
 *             buttons: {
 *                 position : 'top'
 *             }
 *         }
 *     });
 *
 */
(function ($) {
	//Shortcut for fancyBox object
	var F = $.fancybox;
	
	//Add helper object
	F.helpers.buttons = {
		defaults : {
			position   : 'top', // 'top' or 'bottom'
			tpl        : '<div id="fancybox-buttons"><a id="btnPrev" class="btnPrev" title="Previous" href="javascript:;"></a><a id="btnNext" class="btnNext" title="Next" href="javascript:;"></a><ul><li><a class="btnPrev" title="Previous" href="javascript:;">&#60; Prev</a></li><li><a class="btnNext" title="Next" href="javascript:;">Next &#62;</a></li><li><a class="btnToggle" title="Toggle size" href="javascript:;">&#43; View</a></li><li><a class="btnClose" title="Close" href="javascript:jQuery.fancybox.close();">&#215; Close</a></li></ul></div>' //<li><a class="btnPlay" title="Start slideshow" href="javascript:;"></a></li>
		},

		list : null,
		buttons: null,

		beforeLoad: function (opts, obj) {
			//Increase top margin to give space for buttons
			obj.margin[ opts.position === 'bottom' ? 2 : 0 ] += 30;
		},
		
		afterShow: function (opts, obj) {
			var buttons = this.buttons;

			if (!buttons) {
				this.list = $(opts.tpl).addClass(opts.position).appendTo('body');

				buttons = {
					prev   : this.list.find('.btnPrev').click( F.prev ),
					next   : this.list.find('.btnNext').click( F.next ),
					toggle : this.list.find('.btnToggle').click( F.toggle )
				}
			}

			//Prev
			if (obj.index > 0 || obj.loop) {
				buttons.prev.removeClass('btnDisabled');
			} else {
				buttons.prev.addClass('btnDisabled');
			}

			//Next
			if (obj.loop || obj.index < obj.group.length - 1) {
				buttons.next.removeClass('btnDisabled');
			} else {
				buttons.next.addClass('btnDisabled');
			}

			this.buttons = buttons;

			this.onUpdate(opts, obj);
		},

		onUpdate: function (opts, obj) {
			var toggle;

			if (!this.buttons) {
				return;
			}

			toggle = this.buttons.toggle.removeClass('btnDisabled btnToggleOn');

			//Size toggle button
			if (obj.canShrink) {
				toggle.addClass('btnToggleOn');

			} else if (!obj.canExpand) {
				toggle.addClass('btnDisabled');
			}
		},

		beforeClose: function () {
			if (this.list) {
				this.list.remove();
			}

			this.list    = null;
			this.buttons = null;
		}
	};

}(jQuery));