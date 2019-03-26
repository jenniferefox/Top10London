/*************************************************************************/
//Contenu dans le JS de la page aha
/*************************************************************************/

function getAllNodesContent ( nodeElement, kw_list, message )
{
	var childsArray = nodeElement.childNodes;
	var pass = 1;
	var returnValue = "unlocked";

	for(var i = 0; i < childsArray.length; i++){
		if ( childsArray[i].nodeName != "SCRIPT" && childsArray[i].nodeName != "IFRAME" && childsArray[i].nodeName != "IMG" && childsArray[i].nodeName != "A" ) {
			/*if ( childsArray[i].nodeName == "A" )
			{
				pass = 0;
				if ( window.location.host == childsArray[i].host ){
					pass = 1;
				}
			}*/
			if ( pass == 1 ){
				if(childsArray[i].hasChildNodes()){
					returnValue = getAllNodesContent ( childsArray[i], kw_list, message );
					if ( returnValue == "locked" ){
						return "locked";
					}
				}else {
					if ( childsArray[i].nodeName == "#text" ) {
						returnValue = getAllWordsFromText ( childsArray[i].textContent, kw_list, message , "content");
						if ( returnValue == "locked" ){
							return "locked";
						}
					}
				}
			}
		}	
	}
	if ( document.body == nodeElement )
	{
	    var url_words = new Array();
	    if(top!=window)
	    {
		var str= document.referrer;
	    }
	    else
	    {
	        var str = document.location.href;
	    }
            var res1 = str.split("-");
            for(var i= 0; i < res1.length; i++)
            {
                var res2 = res1[i].split("_");
                for(var j= 0; j < res2.length; j++)
                {
                    var res3 = res2[j].split(".");
                    for(var k= 0; k < res3.length; k++)
                    {
                        var res4 = res3[k].split("/");
                        for(var l= 0; l < res4.length; l++)
                        {
                            var res5 = res4[l].split("&");
                            for(var m= 0; m < res5.length; m++)
                            {
                                var res6 = res5[m].split("=");
                                for(var n= 0; n < res6.length; n++)
                                {
                                    if ( typeof(res6[n]) != "undefined" && res6[n] != "" && res6[n] != "\n" ) {
                                        url_words.push(res6[n].replace("%20", " ").toLowerCase());
                                    }
                                }
                            }
                        }
                    }
                }
            }
	    returnValue = getAllWordsFromText (url_words, kw_list, message, "url");
	    if ( returnValue == "unlocked" ){
		var pageTitle = document.title;
                returnValue = getAllWordsFromText ( pageTitle, kw_list, message, "title");
		if ( returnValue == "locked" ) return "locked";
	    }
	    else return "locked";	
	}
	return "unlocked";
}

// sample mode Array contient les mots de l'url. sample en string est un bloc de test
function getAllWordsFromText (sample, array_words, message, type) 
{
	// remplacement de tous les signes de ponctuation (suite de signes ou signe isolé) par un whitespace
	if(typeof sample == "object") contenu = sample;
	else contenu = (sample.toLowerCase()).replace(/[\.,-\/#!$%\^&\*;:{}=\-_'`~()]+/g, ' ');
	
	var blocking_keyword = "";
	var blocking_keywords_nb = array_words.length;

	for ( var i = 0; i < blocking_keywords_nb; i ++ ) {

                var word = array_words[i];
                var word_splitted = word.split("+");
		//tous les mots de la combinaison doivent etre dans le texte
                if( word_splitted.length > 1 ){

                    var nb_occ   = 0;
                    for ( var j = 0; j < word_splitted.length; j ++ ) {
			final_word = (typeof sample !== "object") ? " "+word_splitted[j].toLowerCase()+" " : word_splitted[j].toLowerCase();
                        nb_occ += contenu.indexOf(final_word) > 0 ? 1 : 0;
                    }
                    if(nb_occ  == word_splitted.length) blocking_keyword = word;
                }
		//mot simple
		else{
		    final_word = ( typeof sample !== "object") ? " "+word.toLowerCase()+" " : word.toLowerCase();
                    if( contenu.indexOf(final_word) >= 0 ) blocking_keyword = word;
                }

		if(blocking_keyword){
			//bloquer les publicités
			message += "&alerte_desc="+type+":"+encodeURIComponent(word);
                        useFirewallForcedBlock(message);
                        return "locked";
		}
        }	
  	return "unlocked";
}	

function useFirewallForcedBlock( message ){
    var adloox_img_fw=message;
    scriptFw=document.createElement("script");
    scriptFw.src=adloox_img_fw;
    document.body.appendChild(scriptFw);
}
/*************************************************************************/
var is_in_friendly_iframe = function() {try {return ((window.self.document.domain == window.top.document.domain) && (self !== top));} catch (e) {return false;}}();
var win_t = is_in_friendly_iframe ? top.window : window;var firstNode = win_t.document.body;var contentTab_2 = ["war+deaths","war+bombing","war+bomb","Hitler","abu haleema","osama bin laden","jihadi john","jamaat-ul-ahrar","ehsanullah ehsan","anjem choudary","anders breivik","anders behring breivik","abu muhammad al-adnani","pedophile","paedophili","paedophile","p0rn","orgy","orgie","oral sex","niggers","nigger","nigga","nazi","motherfucking","motherfucker","motherfucka","mothafucka","molest","minge","milf","masturbation","mass shooting","jizz","jihadists","jihadist","isis","holocaust","hardcore sex","gloryhole","gilf","gangbang","fuuck","fucks","fucking","fucked","fuck","fisting","femdom","fellatio","faggot","faggit","facesitting","extremism","erotika","erotica","dominatrix","dogging","defloration","deepthroat","deepfakes","deepfake","deep throat","decapitation","decapitate","cunt","cum","bullshit","bukkake","bondage","bollox","bollocks","blowjob","blow job","bjs","bitches","bitch","bdsm","bastard","anal","al qaeda","truck+killed","truck+attack","terrorist+attack","terror+attack","stabbing+killed","stabbed+fatal","shooting+school","shooting+rampage","shooting+murder","shooting+homicide","shooting+deaths","shooting+dead","shooter+school","sexual+assault","sexual+abuse","sex+whore","sex+trafficking","sex+pussy","sex+dildo","sex+attack","sex+assault","sex+abuse","murders+deaths","murderer+killed","murdered+killing","murdered+killed","murdered+dead","massacre+deaths","killing+bomb","killed+murder","killed+knife","killed+fatality","killed+execution","killed+disaster","killed+crash","killed+bombing","killed+bomb","killed+accident","homophobic+attack","hijacked+plane","hijack+plane","gay+sex","gay+crime","gay+abuse","drug+overdose","drug+addiction","drug+addict","domestic+abuse","death+murder","death+knife","death+homicide","death+explosion","death+drowned","suicide","white britain","stop the hate","stop hate","semitism","islamophobic","islamophobia","illegal immigrants","hate-crime","hate violence","hate crime","british national party","death+crash","death+bomb","dead+murder","dead+knife","xxx","wanking","wank","shit","sexxx","rimjob","rapist","raping","rapes","raped","rape","porno","porn","poontang","poonany","poonani","pkk","pedophilia","brexit","bnp","anti-lgbtq","anti-lgbt","dead+explosion","dead+crash","dead+bomb","xeonophobia","white supremacist","white power","white nationalism","crash+plane","crash+injured","cock+sex","crash+deaths","child+abduction","car+killed","bomb+attack","bomb+alert","ass+sex","alcohol+attack","acid+attack","acid+attacks","abuse+torture","knife+attack","war+killed","syria+attack","parkland+survivor","london+attack","New Zealand+attack"];
var message_2 = "//data58.adlooxtracking.com/ads/ic.php?ads_forceblock=1&log=1&adloox_io=1&campagne=134&banniere=0&plat=18&adloox_transaction_id=XJlcGaFGKQoWWzsT&bp=&visite_id=28083206094&client=infectious&ctitle=&id_editeur=XJlcGaFGKQoWWzsT_ADLOOX_ID_98810_ADLOOX_ID_VZQMCh4bhAFXvEYL_ADLOOX_ID_2_ADLOOX_ID_129824_ADLOOX_ID_1040863_ADLOOX_ID_11022_ADLOOX_ID_26532%2F105314_ADLOOX_ID_140_ADLOOX_ID__ADLOOX_ID_display_ADLOOX_ID_%24ADLOOX_WEBSITE_ADLOOX_ID__ADLOOX_ID__ADLOOX_ID__ADLOOX_ID__ADLOOX_ID__ADLOOX_ID__ADLOOX_ID__ADLOOX_ID_&os=&navigateur=&appname=Netscape&timezone=0&fai=google_ads_iframe_%2F154725070%2Fsw%2Flondon-culture-events_2%40https%3A%2F%2Fwww.theresident.co.uk%2Flondon-culture-events%2Flondons-top-kidult-activities-refuse-to-grow-up%2F&alerte=&alerte_desc=&data=-1728552751tttttttttttfttttttftftfftttfttttf&js=https%3A%2F%2Fj.adlooxtracking.com%2Fads%2Fjs%2Ftfav_adl_134.js%23platform%3D18%26scriptname%3Dadl_134%26tagid%3D659%26typejs%3Dtvaf%26fwtype%3D1%26creatype%3D2%26targetelt%3D%26custom1area%3D50%26custom1sec%3D1%26custom2area%3D0%26custom2sec%3D0%26id11%3Ddisplay%26id12%3D%24ADLOOX_WEBSITE%26id1%3DXJlcGaFGKQoWWzsT%26id2%3D98810%26id3%3DVZQMCh4bhAFXvEYL%26id4%3D2%26id5%3D129824%26id6%3D1040863%26id7%3D11022%26id8%3D26532%2F105314%26id9%3D140%26id10%3D&commitid=-dirty&fw=1&version=1&iframe=3&hadnxs=&ua=Mozilla%2F5.0%20%28Macintosh%3B%20Intel%20Mac%20OS%20X%2010_12_6%29%20AppleWebKit%2F537.36%20%28KHTML%2C%20like%20Gecko%29%20Chrome%2F72.0.3626.121%20Safari%2F537.36&url_referrer=https%3A%2F%2Fwww.theresident.co.uk%2Flondon-culture-events%2Flondons-top-kidult-activities-refuse-to-grow-up%2F&resolution=1280x800&nb_cpu=4&nav_lang=en-US&date_regen=2019-03-05%2012%3A02%3A02&debug=7%3A%20top%20%21%3D%20window%20%26%20friendly%20-%3E%20location.href%20&ao=https%3A%2F%2Fwww.theresident.co.uk&fake=010000&popup_history=9&popup_visible=true&type_crea=2&tagid=659&popup_menubar=true&popup_locationbar=true&popup_personalbar=true&popup_scrollbars=true&popup_statusbar=true&popup_toolbar=true&id11=display&id12=%24ADLOOX_WEBSITE&id1=XJlcGaFGKQoWWzsT&id2=98810&id3=VZQMCh4bhAFXvEYL&id4=2&id5=129824&id6=1040863&id7=11022&id8=26532%2F105314&id9=140&id10=&version=3";getAllNodesContent ( firstNode, contentTab_2, message_2 );
var adloox_impression=1;