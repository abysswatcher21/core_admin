/*! Copyright (c) 2013 Brandon Aaron (http://brandonaaron.net)
 * Licensed under the MIT License (LICENSE.txt).
 *
 * Thanks to: http://adomas.org/javascript-mouse-wheel/ for some pointers.
 * Thanks to: Mathias Bank(http://www.mathias-bank.de) for a scope bug fix.
 * Thanks to: Seamus Leahy for adding deltaX and deltaY
 *
 * Version: 3.1.0
 *
 * Requires: 1.2.2+
 */
(function(b){if(typeof define==="function"&&define.amd){define(["jquery"],b);}else{b(jQuery);}}(function(l){var m=["wheel","mousewheel","DOMMouseScroll"];var j="onwheel" in document||document.documentMode>=9?["wheel"]:["mousewheel","DomMouseScroll","MozMousePixelScroll"];var k,i;if(l.event.fixHooks){for(var h=m.length;h;){l.event.fixHooks[m[--h]]=l.event.mouseHooks;}}l.event.special.mousewheel={setup:function(){if(this.addEventListener){for(var a=j.length;a;){this.addEventListener(j[--a],n,false);}}else{this.onmousewheel=n;}},teardown:function(){if(this.removeEventListener){for(var a=j.length;a;){this.removeEventListener(j[--a],n,false);}}else{this.onmousewheel=null;}}};l.fn.extend({mousewheel:function(a){return a?this.bind("mousewheel",a):this.trigger("mousewheel");},unmousewheel:function(a){return this.unbind("mousewheel",a);}});function n(p){var b=p||window.event,c=[].slice.call(arguments,1),g=0,d=0,e=0,f=0,a=0;p=l.event.fix(b);p.type="mousewheel";if(b.wheelDelta){g=b.wheelDelta;}if(b.detail){g=b.detail*-1;}if(b.deltaY){e=b.deltaY*-1;g=e;}if(b.deltaX){d=b.deltaX;g=d*-1;}if(b.wheelDeltaY!==undefined){e=b.wheelDeltaY;}if(b.wheelDeltaX!==undefined){d=b.wheelDeltaX*-1;}f=Math.abs(g);if(!k||f<k){k=f;}a=Math.max(Math.abs(e),Math.abs(d));if(!i||a<i){i=a;}c.unshift(p,Math.floor(g/k),Math.floor(d/i),Math.floor(e/i));return(l.event.dispatch||l.event.handle).apply(this,c);}}));