var menu = document.querySelector("#menu_holder");
var header = document.querySelector(".header");
var headerHolder = document.querySelector(".header .header-holder");

// Does not run in IE8
if(typeof HTMLElement !== 'undefined'){
	HTMLElement.prototype.addClass = function(theClass){
		theClass = theClass.classNameFormat();
		// Check if each class name exists before adding
		var classes = theClass.split(' ');
		for(var i=0, len = classes.length; i<len; i++){
			if(this.hasClass(classes[i]) === false) {
				var existingClass = this.getAttribute('class');
				this.setAttribute('class', existingClass === null ? classes[i] : existingClass + ' ' + classes[i]);
			}
		}
		return this;
	}
	HTMLElement.prototype.removeClass = function(theClass){
		theClass = theClass.classNameFormat();
		var existingClass = this.getAttribute('class') === null ? '' : this.getAttribute('class');
		// Generate a regular expression from the class names supplied and replace all classes with an empty string
		this.setAttribute('class',
			existingClass.replace(
				new RegExp(theClass.split(' ').join('|'), 'g'), '' // create a class1|class2|class3 regex
			).classNameFormat()
		);
		return this;
	}
	HTMLElement.prototype.hasClass = function(theClass){
		theClass = theClass.classNameFormat();
		var classes = theClass.split(' ');

		// Split class names into an array and call this function on all items
		for(var i=0, len = classes.length; i<len; i++){
			if(!new RegExp('(^|\\s)('+classes[i]+')($|\\s)').test(this.getAttribute('class'))) return false;
		}
		return true;
	}

	// Converts ' class1  class2     class3  ' to 'class1 class2 class3'
	String.prototype.classNameFormat = function(){
		if(this === '' || typeof this.replace === 'undefined') return '';
		return this	.replace(/^\s+|\s+$/g, '') // String.trim() replacement, to allow for IE < 9
					.replace(/\s{2,}/g, ' '); // Remove multiple spaces, replace with single space
	}

	function OffCanvasMenu(){
		// Set some defaults
		this.transitionDuration = typeof getComputedStyle === 'undefined' ? 300 : parseFloat(getComputedStyle(document.getElementById('shifter')).transitionDuration) * 1000;
		this.contentWidth = 995;

		// Set up event listeners and force correct context using This
		var This = this;
		window.addEventListener('resize', function(e){ This.resizeListener() }, false);
		document.getElementById('show-menu').addEventListener('click', function(e){ This.toggle() });
		document.getElementById('shifter').addEventListener('click', function(e){ if(e.target.tagName.toLowerCase() !== 'a') This.close(); }, true);
		document.body.addEventListener('click', function(e){ if(e.target.tagName.toLowerCase() !== 'a') This.close(); }, true);
	}
	OffCanvasMenu.prototype.toggle = function(){
		if(this.isOpen()) this.close();
		else this.open();
	}
	OffCanvasMenu.prototype.open = function(){
		clearTimeout(this.closeTimeout);
		document.body.removeClass('show-nothing').addClass('show-menu');
		document.querySelector('.header-holder').style.top = Math.floor(getScrollY() - get_offset(document.querySelector("#menu_holder"))) + 'px';
		document.querySelector('.header').addClass('above');

		document.querySelector(".header .header-holder").style.top = 0; // Follow the user up as they scroll

		// Turn transitions on for the header holder, so that it animates in
		headerHolder.addClass('transition');

		setTimeout(function(){
			// Turn transitions off for the header holder, so that it does not animate when it goes from absolute to fixed
			headerHolder.removeClass('transition');

			// Figure out how to position menu - fixed or absolute
			positionMenu();
		}, this.transitionDuration);

	}
	OffCanvasMenu.prototype.close = function(){
		if(this.isOpen()){
			document.body.addClass('show-close');
			setAbsolute(); // Set to absolute positioning so that it will transition closed
			this.closeTimeout = setTimeout(function(){
				document.body.removeClass('show-menu show-close');
				document.body.addClass('show-nothing');
				document.querySelector('.header-holder').style.top = 0;
			}, this.transitionDuration);

		}
	}
	OffCanvasMenu.prototype.isOpen = function(){
		return document.body.hasClass('show-menu');
	}
	OffCanvasMenu.prototype.resizeListener = function(){
		if(window.innerWidth > this.contentWidth && this.isOpen()){
			document.body.addClass('show-nothing');
			document.body.removeClass('show-menu');
		}
	}

	window.addEventListener('load', function(){
		window.resMenu = new OffCanvasMenu();
	});
}



// Fixed menu on scroll
// Wrapped in IIFE to prevent variable names clashing
var scrollDirection = 'down';

(function(){
	var is_menu_at_top = true;
	var timeout;

	function scrollListener(e){
		var header_offset = get_offset(header)  + (window.innerWidth <= 1000 ? 0 : 30);

		if( is_menu_at_top && getScrollY() > header_offset) {
			window.clearTimeout(timeout);
			header.addClass('fixed_top');
			is_menu_at_top = false;

		} else if ( getScrollY() < header_offset && is_menu_at_top == false ) {
			window.clearTimeout(timeout);
			header.removeClass('fixed_top');
			is_menu_at_top = true;
			header.style.top = 0;
		}

		if(typeof window.lastScrollY != 'undefined') window.scrollDirection = window.lastScrollY < getScrollY() ? 'down' : 'up';
		window.lastScrollY = getScrollY();

		if(typeof resMenu != 'undefined' && typeof lastScrollY != 'undefined'){
			positionMenu();
		}

		window.setTimeout(function(){
			document.getElementById('logo').addClass('transitions');
		}, 0);

	}

	window.addEventListener('scroll', scrollListener);
	window.addEventListener('load', scrollListener);

})();


// Figures out whether to set to absolute or fixed positioning
function positionMenu(){
	if(resMenu.isOpen()){
		if(scrollDirection == 'up' && (getScrollY() <= parseInt(headerHolder.style.top))){
			header.addClass('above');
			headerHolder.style.top = 0; // Follow the user up as they scroll
		} else if(scrollDirection == 'down'){
			setAbsolute();
		}
	}
}

// Set menu to absolute mode, allowing user to scroll
function setAbsolute(){
	header.removeClass('above');
	if(parseInt(headerHolder.style.top) <= 1) {
		headerHolder.style.top = Math.floor(getScrollY() - get_offset(document.querySelector("#menu_holder"))) + 'px';
	}
}

// Returns a pixel value for the top offset of a given element
function get_offset(e, sub) {
	if(sub) {
		 get_offset.iTop =  get_offset.iTop + e.offsetTop;
		if(e.offsetParent) get_offset(e.offsetParent, true);
		return true;
	}

	//set counter & element_cache if they are not set
	if(!get_offset.key_counter) get_offset.key_counter = 0;
	if(!get_offset.element_cache) get_offset.element_cache = [];

	//add key to element if not already set
	if( ! e.get_offset_key) e.get_offset_key = ++get_offset.key_counter;

	if(get_offset.element_cache[e.get_offset_key]) return get_offset.element_cache[e.get_offset_key];
	else {

		//get this elements offest and recusivly work out its parents
		get_offset.iTop = e.offsetTop
		if(e.offsetParent) get_offset(e.offsetParent, true);

		//set the cache and retrun it at the same time
		return get_offset.element_cache[e.get_offset_key] =  get_offset.iTop;
	}
}

// Cross-browser compatible functions for accessing scrollX and Y
function getScrollY(){
	var supportPageOffset = window.pageXOffset !== undefined;
	var isCSS1Compat = ((document.compatMode || "") === "CSS1Compat");
	return supportPageOffset ? window.pageYOffset : isCSS1Compat ? document.documentElement.scrollTop : document.body.scrollTop;
}
function getScrollX(){
	var supportPageOffset = window.pageXOffset !== undefined;
	var isCSS1Compat = ((document.compatMode || "") === "CSS1Compat");
	return supportPageOffset ? window.pageXOffset : isCSS1Compat ? document.documentElement.scrollLeft : document.body.scrollLeft;
}

// Prevent fixed social buttons obscuring featured image
function shareButtonScrollListener(){
// Only run in wide desktop mode
if(window.innerWidth <= 1150) return;

var socialButtonsHeight = 194; 	// for convenience

// Social share buttons should align with the first P tag,
// Until the user scrolls past, when they should be fixed to the side

if(getScrollY() > get_offset(firstPTag) - ((innerHeight - socialButtonsHeight)/2)){
	sidebarShareButtons.style.top = ((window.innerHeight/2) - (socialButtonsHeight/2)) + 'px';
	sidebarShareButtons.addClass('fixed');
	sidebarShareButtons.removeClass('absolute');
} else {
	sidebarShareButtons.style.top = get_offset(firstPTag) + 'px';
	sidebarShareButtons.addClass('absolute');
	sidebarShareButtons.removeClass('fixed');
}
}

if(document.querySelector('.share-buttons') !== null ) {
	window.addEventListener('scroll', shareButtonScrollListener);
	window.addEventListener('resize', shareButtonScrollListener);
	window.addEventListener('load', function(){
		window.sidebarShareButtons = document.querySelector('.share-buttons.sidebar');
		window.firstPTag = document.querySelector('.content .left > p');
		shareButtonScrollListener();
	});
}

// Dynamic sidebar
if(document.getElementById('sidebar') !== null){
	window.addEventListener("resize", reset_sidebar, false);
	window.addEventListener("load", run_sidebar, false);
	window.addEventListener("scroll", run_sidebar ,false);
}

function run_sidebar() {

	if(window.innerWidth <= 1000) {
		reset_sidebar();
		return false;
	}

	var menu_height = 80;
	var sidebar_offset = get_offset(document.getElementById('sidebar')) - menu_height; // A
	var sidebar_height = document.getElementById('sidebar_inner').offsetHeight; //  B
	var the_max = get_offset(document.querySelector('.content')) + document.querySelector('.content').offsetHeight - menu_height;

	if( (sidebar_offset + sidebar_height + (window.pageYOffset - sidebar_offset)) > the_max )  {
		document.getElementById('sidebar_inner').style.position = 'relative';
		document.getElementById('sidebar_inner').style.top = (the_max - sidebar_height - sidebar_offset) + 'px';

	} else if (window.pageYOffset > sidebar_offset) {
		document.getElementById('sidebar_inner').style.position = 'fixed';
		document.getElementById('sidebar_inner').style.top = '80px';

	} else {
		document.getElementById('sidebar_inner').style.position = 'static';
		document.getElementById('sidebar_inner').style.top = 'auto';

	}

}

function reset_sidebar(){
get_offset.element_cache = [];

if(window.innerWidth <= 1000) {
	document.getElementById('sidebar_inner').style.position = 'static';
	document.getElementById('sidebar_inner').style.top = 'auto';
} else run_sidebar();
}


function ResidentSlider(el){
	this.el = el;
	this.inner = null;
	this.outer = null;
	this.totalSlides = null;
	this.slidesInView = 3;
	this.currentSlide = 1;
	this.maxLeft = 0;
	this.minLeft = null;
	this.slideWidth = 340;
	this.margin = 20;
	this.middle = null;

	// References to DOM elements
	this.inner = this.el.querySelector('.sliding_article_list_holder');
	this.outer = this.el; // TODO: remove outer reference
	this.buttonLeft = this.el.querySelector('.sliding_article_left');
	this.buttonRight = this.el.querySelector('.sliding_article_right');

	var This = this;
	this.buttonLeft.addEventListener('click', function(){
		This.next();
	});

	this.buttonRight.addEventListener('click', function(){
		This.prev();
	});

	window.addEventListener('load', function(e){ This.resizeListener.call(This, e); });
	window.addEventListener('resize', function(e){ This.resizeListener.call(This, e); });
	this.el.addEventListener('touchstart', function(e){ This.touchStart.call(This, e); });
	this.el.addEventListener('touchmove', function(e){ This.touchMove.call(This, e); });
	this.el.addEventListener('touchend', function(e){ This.touchEnd.call(This, e); });
};

ResidentSlider.prototype.resizeListener = function(){
	this.transitionsOff();
	// Figure out how many slides are supposed to be in view. This corresponds to the media queries.
	var w = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
	if(w <= 500) this.slidesInView = 1;
		else if(w <= 760) this.slidesInView = 2;
		else this.slidesInView = 3;

	this.slideWidth = document.querySelector('.sliding_article_list .article_item_large').offsetWidth + 20;

	// Calculate total number of slides
	this.totalSlides = this.el.querySelectorAll('.sliding_article_list_holder a').length;

	// Calculate min value of left property
	this.minLeft = -((this.slideWidth) * (this.totalSlides-this.slidesInView));

	this.transitionsOn();

	this.update();
};

// Increments slide counter without exceeding maximum
ResidentSlider.prototype.next = function(){
	if(this.inner === null) return;
	this.currentSlide = this.isAtStart() ? this.totalSlides - this.slidesInView : this.currentSlide - 1;
	this.update();
};

// Decrements slide counter without exceeding minimum
ResidentSlider.prototype.prev = function(){
	if(this.inner === null) return;
	this.currentSlide = this.isAtEnd() ? 0 : this.currentSlide + 1;
	this.update();
};

// For convenience
ResidentSlider.prototype.isAtStart = function(){	return this.currentSlide === 0; };

// For convenience
ResidentSlider.prototype.isAtEnd = function(){	return this.currentSlide >= this.totalSlides-this.slidesInView; };

// Sends slider to current slide
ResidentSlider.prototype.update = function(){
	var left = -(this.currentSlide * this.slideWidth);
	this.inner.style.left = left + 'px';
};

ResidentSlider.prototype.touchStart = function(e){

	// Capture the start position of the drag
	this.startX = e.targetTouches[0].pageX;
	this.startY = e.targetTouches[0].pageY;
	this.touchStartTime = new Date().getTime();

	this.left = parseInt(this.inner.style.left) ? parseInt(this.inner.style.left) : this.maxLeft;

	this.touchMoved = false; // reset this, ready for touchend
};

ResidentSlider.prototype.touchMove = function(e){
	this.transitionsOff();

	this.swipeDistance = e.targetTouches[0].pageX - this.startX;

	// Only consider it a swipe if moved more than 10 pixels
	if(Math.abs(this.swipeDistance) > 10) this.touchMoved = true;

	// Return if dragging vertically...
	if(Math.abs(e.targetTouches[0].pageY - this.startY) > Math.abs(this.swipeDistance)) return;

	// ...otherwise, prevent scroll and continue with swipe
	if(e.preventDefault) e.preventDefault();
		else e.returnValue = false;

	// Calculate new left value, and make sure it isn't too large or small
	var newLeft = this.left + this.swipeDistance;
	newLeft = Math.min(this.maxLeft + 50,newLeft);
	newLeft = Math.max(this.minLeft - 50, newLeft);

	this.inner.style.left = String(newLeft + 'px');
};

ResidentSlider.prototype.touchEnd = function(e){
	this.transitionsOn();
	// Only prevent scroll if touchMove event has been fired, otherwise the user is trying to click.
	if(this.touchMoved){
		if(e.preventDefault) e.preventDefault();
			else e.returnValue = false; // IE

		this.rePosition(); // Animates back to the correct position
	}
	this.touchMoved = false;
};

ResidentSlider.prototype.rePosition = function(){
	this.left = parseInt(this.inner.style.left);
	this.left = Math.min(this.maxLeft, this.left);
	this.left = Math.max(-(this.slideWidth * (this.totalSlides-this.slidesInView)), this.left);

	// Figure out which slide we're on
	this.currentSlide = Math.abs(Math.round(this.left / this.slideWidth));

	this.update();
}

ResidentSlider.prototype.transitionsOn = function(){
	this.el.addClass('transitions');
}
ResidentSlider.prototype.transitionsOff = function(){
	this.el.removeClass('transitions');
}

if(typeof document.addEventListener !== 'undefined'){
	var sliders = document.querySelectorAll('.sliding_article_list');
	for(var i=0; i<sliders.length; i++){
		var tr_slider = new ResidentSlider(sliders[i]);
		//tr_slider.resizeListener();
	}

}

function highlightSearchField(){
	var searchField = document.querySelector('#s');
	searchField.focus();
	window.scroll(getScrollX(), get_offset(searchField) - 120);
	searchField.addClass('highlight');
	setTimeout(function(){
		searchField.removeClass('highlight');
	},2000);
}

window.addEventListener('load', function(){
	window.lastScrollY = getScrollY();


});


