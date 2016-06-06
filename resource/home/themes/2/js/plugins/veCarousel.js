$(document).ready(function() { 	   
	carousel();
 });

var carousel_round = 0;

function carousel()
{
	$(document).delegate('.carousel .next, .carousel .previous', 'click', function(event)
	{
		// prevent the default anchor action
		event.preventDefault();
		
		// get the current carousel
		var $carousel = $(this).parents('.carousel');
		
		// check if we're already in the middle of a movement
		if ($carousel.prop('moving'))
		{
			return true;
		}
		
		// if we actually clicked it, then stop any running timers
		if (event.clientX)
		{
			stop($carousel);
		}
		
		// localize the index, so we know where we are
		var index = $carousel.prop('index');
		
		// determine if we're going forward or backward
		var movingForward = $(this).hasClass('next');
		
		// grab all the slides
		var $slides = $carousel.find('.carousel-item');
		
		// grab the currently focused slide
		var $focus = $slides.eq(index);
		
		// figure out where're we going from here
		var nextObject = movingForward ? nextSlide($carousel, index) : previousSlide($carousel, index);
		
		// locaalize the next div to be shown
		var $next = nextObject.element;
		
		// localize the index of the next element to be shown
		var newIndex = nextObject.index;
		
		// determine where we should place the next element so it slides from the correct side
		var initialLeft = movingForward ? $(document.body).outerWidth() : -$next.outerWidth();
		
		// save the current zero position, everything will move to/from here
		var zeroPosition = $focus.offset().left;
		
		// determine where the focus is moving to
		var targetLeft = zeroPosition + (movingForward ? -$next.outerWidth() : $next.outerWidth());
		
		// we're comitted to moving now so set the flag to true so we don't duplicate animations
		$carousel.prop('moving', true);
		
		// reset all z-indexes to 1
		$slides.css('z-index', 1);
		
		// make the currently focused slide higher than all the rest
		$focus.css('z-index', 2);
		
		// setup the current slide so it can animate out
		$focus.css({
			"position": "absolute",
			"top": 0,
			"left": zeroPosition
		});
		
		// setup the next slide to slide in, moving it above the focused slide and off screen
		$next.css({
			"opacity": 0,
			"position": "absolute",
			"top": 0,
			"left": initialLeft,
			"z-index": 3
		});
		
		// animate the current slide out
		$focus.animate({
			"opacity": 0,
			"left": targetLeft
		}, 300);
		
		// set up what we're animating
		var animation = {
			"opacity": 1,
			"left": zeroPosition
		}
		
		// horrible ie fix
		if ($.browser.msie && parseInt($.browser.version) <= 8)
		{
			delete animation.opacity;
			$focus.get(0).style.removeAttribute('filter');
			$next.get(0).style.removeAttribute('filter');
		}
		
		
		
		// animate in the next slide, then upon completion reset everything. switch it back to
		// relative positioning, remove our animation flag and hide the previous element
		$next.show().animate(animation, 300, function()
		{
			$focus.hide();
			$(this).css({
				"position": "relative",
				"left": 0
			});
			
			// fix msie
			if ($.browser.msie && parseInt($.browser.version) <= 8)
			{
				this.style.removeAttribute('filter');
			}
			
			$carousel.prop('moving', false);
		});
		
		// animate the height of our carousel, because things are abosulte the box model is shot
		$carousel.animate({
			//"min-height": $next.outerHeight()
		});
		
		// finally update our index to reflect the current view
		$carousel.prop('index', newIndex);
	});
	
	$(document).delegate('.carousel .pause', 'click', function(event)
	{
		// prevent the default anchor action
		event.preventDefault();
		
		// localize the carousel
		var $carousel = $(this).parents('.carousel');
		
		// get the current timer, if it exists
		var timer = $carousel.prop('timer');
		
		// no timer? start it
		if (!timer)
		{
			start($carousel);
		}
		
		// timer? stop it
		else
		{
			stop($carousel);
		}
	});
	
	// start a new timer, additionally update the play/pause button to the correct visual state
	function start($carousel)
	{
		timer = setInterval(function()
		{
			$carousel.find('.next').eq(0).trigger('click');
			
			//N.C.: added to stop carousel after one round.
			var index = $carousel.prop('index');
			if ( index==0 && carousel_round > 0 ) {
				stop($carousel);
			}
			else if ( index==1 ) {
				carousel_round++;
			}
			
		}, 5000);
		
		$carousel.prop('timer', timer);
		$carousel.find('.play.pause').removeClass('play');
	}
	
	// stop any existing timers, additionally update the play/pause button to the correct
	// visual state
	function stop($carousel)
	{
		clearInterval(timer);
		
		$carousel.prop('timer', false);
		$carousel.find('.pause').addClass('play');
		
		//N.C.: added to stop carousel after one round.
		carousel_round = 0;
	}
	
	function nextSlide($carousel, index)
	{
		var $slides = $carousel.find('.carousel-item');
		
		if (index+1 < $slides.size())
		{
			return {"index":index+1, "element":$slides.eq(index+1)};
		}
		
		return {"index":0, "element":$slides.eq(0)};
	}
	
	function previousSlide($carousel, index)
	{
		var $slides = $carousel.find('.carousel-item');
		
		if (index-1 >= 0)
		{
			return {"index":index-1, "element":$slides.eq(index-1)};
		}
		
		return {"index":$slides.size()-1, "element":$slides.eq(-1)};
	}
	
	// build the controls for inclusion into the page
	var $previousBtn = $('<a />', {"class": "previous", "href": "#", "text": "Previous"});
	var $playPauseBtn = $('<a />', {"class": "play pause", "href": "#", "text": "Play/Pause"});
	var $nextBtn = $('<a />', {"class": "next", "href": "#", "text": "Next"});
	var $controlsDiv = $('<div />', {"class": "carousel-controls"});
	$controlsDiv.append($previousBtn);
	$controlsDiv.append($playPauseBtn);
	$controlsDiv.append($nextBtn);
	
	// loop through each carousel and set some default properties/styles
	$('.carousel').each(function()
	{
		// localize the carousel to this function
		var $carousel = $(this);
		
		// set the positioning and a default height, becuase we're going to be taken out of the
		// flow once our animation starts
		$carousel.css({
			"position": "relative"
			//"min-height": $carousel.find('.carousel-item').eq(0).outerHeight() //N.C. commented out
		});
		
		// store the currently visible slide's index
		$carousel.prop('index', 0);
		
		// hide subsequent slides
		$carousel.find('.carousel-item:gt(0)').hide();
		
		// append in our controls
		$carousel.prepend($controlsDiv.clone(true));
		
		// add the previous/next images
		$carousel.find('.main-image').each(function(index)
		{
			// get the previous image
			var $prevImage = $(previousSlide($carousel, index).element).find('.main-image').clone();
			
			// remove the class
			$prevImage.removeClass('main-image');
			
			// create a link for the previous image
			var $previousAnchor = $('<a />', {
				"href": "#",
				"class": "prev-image",
				"html": $prevImage
			});
			$previousAnchor.css('opacity', 0.2);
			
			// add in the previous image/anchor
			$(this).before($previousAnchor);
			
			// get the next image
			var $nextImage = $(nextSlide($carousel, index).element).find('.main-image').clone();
			
			// remove the class
			$nextImage.removeClass('main-image');
			
			// create a link for the previous image
			var $nextAnchor = $('<a />', {
				"href": "#",
				"class": "next-image",
				"html": $nextImage
			});
			$nextAnchor.css('opacity', 0.2);
			
			// add in the next image/anchor
			$(this).after($nextAnchor);
		});
		
		// start the carousel by default
		start($carousel);
	});
}