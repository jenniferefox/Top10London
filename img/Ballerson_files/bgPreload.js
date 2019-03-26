function imageBgPreload(onComplete) {
    
    // preload all background images
    try {
        runBgPreload(onComplete); 
        console.log("preload try successful");
    }
    catch(err) {
        console.log("preload skipped");
        onComplete();
    }
    
        function runBgPreload(onComplete) {
        var imageHolder = document.createElement("div");
        imageHolder.setAttribute("id", "preBgHolder");
        imageHolder.style.display = "none";
        document.getElementsByTagName("body")[0].appendChild(imageHolder);
        var preloads = document.getElementsByClassName("preload");
        var imageBank = document.getElementById("preBgHolder");
        var preloadUrls = [];
        var images = [];
        var pre = [];
        var preStatus = [];
        var allImagesReady;
        var onCompleteFired = false;
        // grab all background images from CSS and preload in a CSS hidden div called preBgHolder.
        function getAllBackgroundImages() {
            for (i = 0; i < preloads.length; i++) {
                    
                    if (window.getComputedStyle(preloads[i]).getPropertyValue("background-image") != "none") {
                        preloadUrls[i] = window.getComputedStyle(preloads[i]).getPropertyValue("background-image");
                    preloadUrls[i] = preloadUrls[i].replace(/\"/g, ''); // removes url quotes as computed different in safari
                    preloadUrls[i] = preloadUrls[i].substring(4, preloadUrls[i].length - 1);
                    images[i] = new Image();
                    images[i].src = preloadUrls[i];
                    images[i].classList.add("preBg");
                    imageBank.appendChild(images[i]);      
                    } else {
                        console.error( "#" + preloads[i].id + " does not contain a background image");
                    }
            }
        }
        // check each img tag in the hidden div has loaded
        function statusListeners() {
            pre = document.getElementsByClassName("preBg");
            for (i = 0; i < pre.length; i++) {
                pre[i].addEventListener("load", checkEachStatus, false);
                preStatus[i] = pre[i].complete;
                //console.log([i] + " load status: " + preStatus[i]);
            }
        }

        function checkEachStatus() {
            //console.log("checkEachStatus()");
            function imageLoadedTrue(loadStatuses) {
                return loadStatuses == true;
            }
            for (i = 0; i < pre.length; i++) {
                preStatus[i] = pre[i].complete;
                //console.log(pre[i].complete + i);
                allImagesReady = preStatus.every(imageLoadedTrue);
                if (allImagesReady == true && onCompleteFired == false) {
                    // mainFired used to stop runMain firing more than once if images load from cache
                    //console.log("onComplete()");
                    onComplete();
                    onCompleteFired = true;
                }
            }
        };
        getAllBackgroundImages();
        statusListeners();
        // if there are no preloads to work with run the fallack function anyway
        if (preloads.length == 0) {
            onComplete();
            console.log("there was nothing to preload");
        }
    }   
}