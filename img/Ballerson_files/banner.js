//create backup images
var makeBackup = false;

//set devTools
var pre = "pre", dev = "dev", off = "off";
var previewTools = off;

//devTools
var devTools = {};
var tl; //for devTools

//set banner width and height
var ad_width = 300;
var ad_height = 250;

var body = document.getElementsByTagName("body");
var banner = document.getElementById("banner");
var border = document.getElementById("border");
    body[0].style.width = ad_width + "px";
    body[0].style.height = ad_height + "px";
    banner.style.width = ad_width + "px";
    banner.style.height = ad_height + "px";
    border.style.width = (ad_width - 2) + "px";
    border.style.height = (ad_height - 2) + "px";

function startBanner(){ 
    tl = new TimelineMax({repeat:1, repeatDelay:2, onStart:setStaticData, onUpdate:updateStats});
    tl.to('#banner', 0.3, { opacity:1, ease: Power2.easeOut},'=.3')      

        .from('#copy1', 0.55, {opacity:0, ease: Power2.easeOutIn})
        
        .to('#copy1', 1.2, {x: '+=150', opacity:0, ease: Power2.easeOut}, '=2.7')
        
        .from('#van', 0.9, {x: '-=151', opacity:0, ease: Power2.easeOutIn}, '-=1.3')
        .from('#copy2', 0.7, {opacity:0, ease: Power2.easeOutIn})
        
        .to('#copy2', 1, {x: '+=150', opacity:0, ease: Power2.easeOut}, '=1.8')
        .to('#dog', 1, {opacity:0, x: '+=50', ease: Power2.easeOutIn}, '-=1')
        .to('#van', 1.2, {x: '-=151', ease: Power2.easeOutIn}, '-=1')
        
        
        //start end frame transitions
        .from('#copy4', 0.5, {opacity:0, ease: Power2.easeOutIn}, '-=.5')

        .from('#disclaimer', 0.7, {bottom:-100, ease: Power2.easeOutIn}, '-=.55')
        
        .from('.cta', 0.3, {opacity:0, ease: Power2.easeNone})
        .to('.arrow', 0.2, {x:"-=4", opacity:1, ease: Power0.easeNone}, '-=.2')
        .to('.arrow', 0.2, {x:"+=6", ease: Power0.easeNone})
        .to('.arrow', 0.2, {x:"-=4", ease: Power0.easeNone})
        .to('.arrow', 0.2, {x:"+=2", ease: Power0.easeNone})

    var total = tl.totalDuration();
    console.log(total);

    if (makeBackup == true){
        //use this to go straight to the end of the timeline to makeup backup images
        tl.totalTime(tl.totalDuration(), false);
    };
    devTools.tl = tl;
};

//DEV TOOLS
function setStaticData() {
    if (previewTools == pre){
        window.parent.devTools.style.opacity = 1;
        window.parent.devTools.style.visibility = "visible";
        window.parent.getStaticData();
    }
    if (previewTools == dev){
        localSetStaticData();
    }
};
function updateStats(){
    if (previewTools == pre){
    window.parent.updateStats();
    }
    if (previewTools == dev){
        localUpdateStats();
    }
}
//DEV TOOLS END

// preload all background images
imageBgPreload(startBanner); 