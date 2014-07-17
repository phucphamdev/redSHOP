MUX=window.MUX||{},MUX.Loader=new Class({Implements:Options,options:{display:"block",delay:16,id:"",classes:"",run:!1},initialize:function(e){this.setOptions(e),this.elem.addClass("mux-loader "+this.options.classes).setStyle("display","none"),this.elem.id=this.options.id;var t=this;this.elem.start=function(){t.start.apply(t)},this.elem.stop=function(){t.stop.apply(t)},this.gcCounter=0,this.options.run&&this.start()},toElement:function(){return this.elem},_setBackground:function(e,t){var n=";";if(typeof t=="string")n+="background:"+t+";";else if(t instanceof Array)for(var r=0;r<t.length;r++)n+=(/:/.test(t[r])?"":"background:")+t[r]+";";e.style.cssText+=n},_noRadius:function(){return!!(Browser.ie&&typeOf(this.options.background)==="array"||Browser.Platform.ios&&Browser.safari&&Browser.version<5)},__animate:function(){if(this.gcCounter>=500&&(this.elem.getStyle("display")==="hidden"||!this.elem.getParent("body"))){clearInterval(this.intervalId);return}this.gcCounter++,this._animate()},start:function(){var e=this;this.intervalId&&clearInterval(this.intervalId),this.elem.setStyle("display",this.options.display),this.intervalId=setInterval(function(){e.__animate.apply(e)},this.options.delay)},stop:function(){this.elem.setStyle("display","none"),clearInterval(this.intervalId),this.intervalId=undefined}}),MUX.Loader.Bar=new Class({Extends:MUX.Loader,options:{height:11,width:180,delay:11,background:["#7db9e8","-moz-linear-gradient(top, #7db9e8 0%, #1E5799 59%)","-webkit-gradient(linear, left top, left bottom, color-stop(0%,#7db9e8), color-stop(59%,#1E5799))",'filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#7db9e8", endColorstr="#1E5799",GradientType=0 )',"-o-linear-gradient(top, #7db9e8 0%,#1E5799 59%)"],color:"#fff"},initialize:function(e){this.setOptions(e);var t=this;this.elem=new Element("div",{styles:{height:this.options.height,width:this.options.width,overflow:"hidden"}});var n=[],r=Math.ceil(parseInt(this.options.width)/(parseInt(this.options.height)*2)+1);this.runner=(new Element("div",{styles:{"margin-left":-(this.options.height*2),height:this.options.height,width:this.options.height*r*2}})).inject(this.elem);var i={"float":"left",width:"0px",height:"0px","border-style":"solid","border-color":"transparent","border-width":"0px 0px "+this.options.height+"px "+this.options.height+"px"};for(var s=0;s<=r;s++)n.push(new Element("div",{styles:Object.merge(i,{"border-bottom-color":this.options.color,"border-left-color":"transparent"})})),n.push(new Element("div",{styles:Object.merge(i,{"border-bottom-color":"transparent","border-left-color":this.options.color})}));this.runner.adopt(n),this.shift=-parseInt(this.options.height)*2,this._setBackground(this.elem,this.options.background),this.parent(this.options)},_animate:function(){this.runner.setStyle("margin-left",this.shift),this.shift=this.shift>=0?-parseInt(this.options.height)*2:this.shift+1}}),MUX.Loader.Radar=new Class({Extends:MUX.Loader,options:{size:16,delay:30,overrun:20,background:["#7db9e8","-moz-linear-gradient(top, #7db9e8 0%, #1E5799 59%)","-webkit-gradient(linear, left top, left bottom, color-stop(0%,#7db9e8), color-stop(59%,#1E5799))",'filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#7db9e8", endColorstr="#1E5799",GradientType=0 )',"-o-linear-gradient(top, #7db9e8 0%,#1E5799 59%)"],color:"#fff"},initialize:function(e){this.setOptions(e);var t=this._noRadius();this.elem=new Element("div",{styles:{height:this.options.size,width:this.options.size,overflow:"hidden","border-radius":t?0:this.options.size.toInt()/2+"px","-webkit-border-radius":t?0:this.options.size.toInt()/2+"px","-moz-border-radius":this.options.size.toInt()/2+"px"}}),this.options.size<30?this.runnerWidth=2:this.runnerWidth=3,this.shift=-this.runnerWidth,this.runner=(new Element("div",{styles:{height:"100%",width:this.runnerWidth,background:this.options.color,"margin-left":this.shift}})).inject(this.elem),this._setBackground(this.elem,this.options.background),this.parent(this.options)},_animate:function(){this.runner.setStyle("margin-left",this.shift),this.shift=this.shift>=this.options.size+this.options.overrun+this.runnerWidth?-this.runnerWidth:this.shift+1}}),MUX.Loader.Well=new Class({Extends:MUX.Loader,options:{mode:"out",size:16,delay:50,background:["#7db9e8","-moz-linear-gradient(top, #7db9e8 0%, #1E5799 59%)","-webkit-gradient(linear, left top, left bottom, color-stop(0%,#7db9e8), color-stop(59%,#1E5799))",'filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#7db9e8", endColorstr="#1E5799",GradientType=0 )',"-o-linear-gradient(top, #7db9e8 0%,#1E5799 59%)"],color:"#fff"},initialize:function(e){this.setOptions(e),this.options.size=typeof this.options.size=="string"?this.options.size.toInt():this.options.size;var t=this._noRadius();this.elem=new Element("div",{styles:{height:this.options.size,width:this.options.size,position:"relative",overflow:"hidden","border-radius":t?0:this.options.size.toInt()/2+"px","-webkit-border-radius":t?0:this.options.size.toInt()/2+"px","-moz-border-radius":this.options.size.toInt()/2+"px"}}),this.options.mode==="in"||this.options.mode==="post"?(this.initShift=this.options.size-2,this.step=-2,this.controlPoint=this.options.size%2):(this.initShift=this.options.size%2,this.step=2,this.controlPoint=this.options.size-2),this.shift=this.initShift;var n=Math.floor((this.options.size-this.shift)/2);this.runners=[],this.runners.push((new Element("div",{styles:{position:"absolute",width:this.shift,height:this.shift,top:n,left:n,background:this.options.color,"border-radius":t?0:this.options.size/2+"px","-webkit-border-radius":t?0:this.options.size/2+"px","-moz-border-radius":this.options.size/2+"px"}})).inject(this.elem)),this.runners.push(this.runners[0].clone().inject(this.elem)),this.runnerIndex=1,this._setBackground(this.elem,this.options.background),this._setBackground(this.runners[0],this.options.background),this.parent(this.options)},_animate:function(){if(this.shift===this.initShift){this.runners[this.runnerIndex].inject(this.elem);if((this.options.mode==="in"||this.options.mode==="post")&&this.prevRunner){var e=Math.floor((this.options.size-this.shift)/2);this.prevRunner.setStyles({width:this.shift,height:this.shift,top:e,left:e})}}this.shift=this.shift+this.step;var e=Math.floor((this.options.size-this.shift)/2);this.runners[this.runnerIndex].setStyles({width:this.shift,height:this.shift,top:e,left:e}),this.shift===this.controlPoint&&(this.prevRunner=this.runners[this.runnerIndex],this.shift=this.initShift,this.runnerIndex=this.runnerIndex?0:1)}}),MUX.Loader.Circles=new Class({Extends:MUX.Loader,options:{mode:"out",size:16,delay:50,background:["#7db9e8","-moz-linear-gradient(top, #7db9e8 0%, #1E5799 59%)","-webkit-gradient(linear, left top, left bottom, color-stop(0%,#7db9e8), color-stop(59%,#1E5799))",'filter: progid:DXImageTransform.Microsoft.gradient( startColorstr="#7db9e8", endColorstr="#1E5799",GradientType=0 )',"-o-linear-gradient(top, #7db9e8 0%,#1E5799 59%)"],color:"#fff"},initialize:function(e){this.setOptions(e),this.options.size=typeof this.options.size=="string"?this.options.size.toInt():this.options.size;var t=this._noRadius();this.elem=new Element("div",{styles:{height:this.options.size,width:this.options.size,position:"relative",overflow:"hidden","border-radius":t?0:this.options.size.toInt()/2+"px","-webkit-border-radius":t?0:this.options.size.toInt()/2+"px","-moz-border-radius":this.options.size.toInt()/2+"px"}}),this.options.size<30?this.borderWidth=2:this.borderWidth=4,this.options.mode==="in"||this.options.mode==="post"?(this.initShift=this.options.size+this.borderWidth*2,this.step=-2,this.controlPoint=this.options.size%2):(this.initShift=this.options.size%2,this.step=2,this.controlPoint=this.options.size+this.borderWidth*2),this.shift=this.initShift;var n=Math.floor((this.options.size-this.shift)/2);this.mainRunner=(new Element("div",{styles:{position:"absolute",width:this.shift,height:this.shift,top:n,left:n,background:this.options.color,"border-radius":t?0:this.options.size.toInt()/2+"px","-webkit-border-radius":t?0:this.options.size.toInt()/2+"px","-moz-border-radius":this.options.size.toInt()/2+"px"}})).inject(this.elem),this.innerRunner=this.mainRunner.clone().inject(this.elem);var r=Math.max(this.shift-this.borderWidth*2,0),i=Math.floor((this.options.size-r)/2);this.innerRunner.setStyles({width:r,height:r,top:i,left:i}),this._setBackground(this.elem,this.options.background),this._setBackground(this.innerRunner,this.options.background),this.parent(this.options)},_animate:function(){this.shift=this.shift+this.step;var e=Math.floor((this.options.size-this.shift)/2);this.mainRunner.setStyles({width:this.shift,height:this.shift,top:e,left:e});var t=Math.max(this.shift-this.borderWidth*2,0),n=Math.floor((this.options.size-t)/2);this.innerRunner.setStyles({width:t,height:t,top:n,left:n}),this.shift===this.controlPoint&&(this.shift=this.initShift)}}),MUX.Loader.Fb=new Class({Extends:MUX.Loader,options:{height:11,delay:110,background:"#8C9EC3",borderColor:"#526FA7"},initialize:function(e){this.setOptions(e),this.cellHeight=typeof this.options.height=="string"?this.options.height.toInt():this.options.height,this.cellWidth=Math.floor(this.cellHeight*4/11),this.borderWidth=Math.floor(this.cellWidth/4),this.cellSpacing=this.cellWidth-this.borderWidth,this.cellMargin=Math.floor(this.cellWidth/2);var t=(new Element("tr")).inject((new Element("tbody")).inject((new Element("table",{border:0,cellspacing:0})).inject(this.elem=new Element("div",{styles:{height:this.cellHeight,width:this.cellWidth*3+this.cellMargin*2}}))));this.cells=[];for(var n=0;n<5;n++)n%2?(new Element("td",{width:this.cellSpacing})).inject(t):(this.cells.push(new Element("div",{styles:{margin:this.cellMargin+"px 0px",width:this.cellWidth-this.borderWidth*2,height:this.cellHeight-this.cellMargin*2-this.borderWidth*2,background:this.options.background,border:this.borderWidth+"px solid "+this.options.borderColor,visibility:"hidden",opacity:0}})),(new Element("td")).grab(this.cells[this.cells.length-1]).inject(t));this.step=0,this.steps=6,this.parent(this.options)},_animate:function(){this.step<=this.cells.length-1&&this.cells[this.step].setStyles({visibility:"visible",opacity:1,margin:"0px",height:this.cellHeight-this.borderWidth*2}),this.step-1>=0&&this.step-1<=this.cells.length-1&&this.cells[this.step-1].setStyles({visibility:"visible",opacity:.5,margin:this.cellMargin+"px 0px",height:this.cellHeight-this.cellMargin*2-this.borderWidth*2}),this.step-2>=0&&this.step-2<=this.cells.length-1&&this.cells[this.step-2].setStyles({visibility:"visible",opacity:.1,margin:this.cellMargin+"px 0px",height:this.cellHeight-this.cellMargin*2-this.borderWidth*2}),this.step-3>=0&&this.step-3<=this.cells.length-1&&this.cells[this.step-3].setStyles({visibility:"hidden",opacity:0,margin:this.cellMargin+"px 0px",height:this.cellHeight-this.cellMargin*2-this.borderWidth*2}),this.step===this.steps?this.step=0:this.step++}});