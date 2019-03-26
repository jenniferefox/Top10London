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
var win_t = is_in_friendly_iframe ? top.window : window;var firstNode = win_t.document.body;var contentTab_2 = ["Racial+attack","Racist+attack","Paki","White+trash","Coon","Nigger","Neo+nazi","ku-klux-klan","anti-semitic","weapons+of+mass+destruction","nuclear+weapons","British+national+party","KKK","fascism","BNP","Nazi","drugs+killed","drugs+kill","drugs+death","drug+overdose","drug+kills","drug+killed","drug+addict","crack+cocaine","drug+mule","drug+abuse","methamphetamine","meth","heroin","cocaine","house+flooded","volcanic+eruption","house+destroyed","tidal+wave","flash+flood","tsunami","home+flooded","famine","home+destroyed","earthquake","tornado","drought","landslide","hurricane","flood","gun+shot","terrorist+attack","stabbed+death","mowingdown","moweddown","knife-wieldingattacker","knifewielding+attack","keithpalmer","carnage+westminster+brigde","ayshafrade","killed+knife","killed+gun","explosion+terrorist","explosion+car","explosion+attack","carnage+on+westminster+brigde","terrorist+threat","knife-wielding+attacker","Al-Shabaab","RevolutionaryArmedForcesofColombia","Lashkar-e-Tayyiba","Tehrik-e Taliban+Pakistan","Tehrik-e+Taliban","suicide+bomber","misile","islamicstate","The Lords Resistance Army","asylum+seeker","knife+wielding+attacker","AlQaeda","Lords Resistance Army","catastrophic+wounds","allahu+akbar","behead","FARC","suicide+attack","economic+migrants","raqqa","beheading","jihad","illegal+immigrant","executions","LRA","Hezbollah","AK47","Boko+Haram","jihadi","mosul","Taliban","mowed+down","terror+day","burqa","mowing+down","extremism","trafficking","extremists","aleppo","extremist","lone+wolf","Police+Stabbed","hostage","ira","bomber","September+11","isis","Knifeman","execution","explosion","terrorists","weapons","terror+attack","police+shot","weapon","bomb","terror","terrorism","guns","terrorist","nuclear","shooting","assad","london+attack","stabbed","killed","kickasstorrent","torrentz","utorrent","limewire","putlocker","bittorrent","torrents","napster","pirate+bay","piracy","torrent","tit+fuck","female+ejaculation","cum+shot","zoosex","underagegirls","teensex","sodomitical","sodomitic","sodomists","sodomist","sexvideo","rimjobs","peepshows","paederast","groupsex","buttfucker","escortservice","slutbag","callgirl","cumshot","strapon","bitchass","facesitting","oralsex","deepthroat","rimjob","cameltoe","sodomites","sodomite","gangbang","sexxx","necrophilia","lesbo","erotika","pornos","interracial+sex","creampie","handjob","sodomy","clusterfuck","bukkake","shemale","dildos","milfs","kamasutra","wanking","clit","golden+shower","striptease","orgie","transsexual","pussies","paedo","sadist","ketamine","playmates","sado","fucker","milf","erotica","wank","testicle","faggot","dildo","vibrator","boner","mugging","arsehole","bondage","pegging","squirting","cunt","dicks","rapes","bjs","ejaculation","pissing","whore","masturbation","homo","nudes","horny","orgy","incest","blowjob","raping","tits","fetish","slut","threesomes","nudity","pussy","bitches","prostitute","erotic","rapist","lesbian","fag","slaves","anal","kinky","tit","bitchy","transgender","orgasm","squirt","vagina","choking","piss","boob","hooker","paedophile","blow+job","penthouse","stripping","cock","arse","bitch","sexism","sexist","hardcore","porn","penis","sexual","cum","fucking","fuck","sexually","raped","pornographic","anus","dick","hoe","shit","rape","ass","butt","pornography","trauma+death","passengers+killed","accident+emergency","plane+vanished","plane+hijack","flight+vanished","plane+disappeared","flight+disappeared","passengers+dead","trauma+accident","infant+died","women+died","passengers+died","train+crash","plane+missing","homicide","flight+missing","girl+died","men+died","dead+bodies","boy+died","flight+crashed","flight+crash","children+died","plane+crash","woman+died","people+died","murdered","family+died","father+died","man+died","mother+died","found+dead","car+crash","car+accident","murder","death","terminal+illness","illness+mental","illness+disorder","hodgkins+disease","Pneumonias","precancerous","metastasis","cancer+killed","misdiagnosis","cancer+spotted","sarcoma","metastatic","malignancy","cancer+kills","trauma+stress","disease+abnormal","carcinoma","misdiagnosed","leukemia","mastectomy","cancer+patient","melanoma","lymphoma","cancerous","terminal+cancer","tumours","carcinogen","Pneumonia","cancer+cells","tumour","cancer+skin","cancer+treatment","cancer+found","disease+condition","HIV","tumor","cancer+diagnosed","traumatic","cancer+risk","chemotherapy","cancer+breast","carcinogenic","heart+attack"];
var message_2 = "//data65.adlooxtracking.com/ads/ic.php?ads_forceblock=1&log=1&adloox_io=1&campagne=47&banniere=0&plat=7&adloox_transaction_id=null&bp=&visite_id=26529843894&client=Msix&ctitle=&id_editeur=5879457_ADLOOX_ID_10134811_ADLOOX_ID_143354165_ADLOOX_ID_2095028_ADLOOX_ID_108249281_ADLOOX_ID_0_ADLOOX_ID__ADLOOX_ID__ADLOOX_ID__ADLOOX_ID__ADLOOX_ID_vitality_ADLOOX_ID__ADLOOX_ID__ADLOOX_ID_display_ADLOOX_ID__ADLOOX_ID__ADLOOX_ID__ADLOOX_ID__ADLOOX_ID__ADLOOX_ID_&os=&navigateur=&appname=Netscape&timezone=0&fai=frame%20without%20title&alerte=&alerte_desc=&data=-1728552751tttttttttttfttttttftftfftttfttttf&js=https%3A%2F%2Fj.adlooxtracking.com%2Fads%2Fjs%2Ftfav_adl_47.js%23platform%3D7%26scriptname%3Dadl_47%26tagid%3D225%26typejs%3Dtvaf%26fwtype%3D1%26creatype%3D2%26targetelt%3D%26custom1area%3D50%26custom1sec%3D1%26custom2area%3D0%26custom2sec%3D0%26id11%3Dvitality%26id12%3D%26id13%3D%26id14%3Ddisplay%26id1%3D5879457%26id2%3D10134811%26id3%3D143354165%26id4%3D2095028%26id5%3D108249281%26id6%3D0&commitid=-dirty&fw=1&version=1&iframe=1&hadnxs=&ua=Mozilla%2F5.0%20%28Macintosh%3B%20Intel%20Mac%20OS%20X%2010_12_6%29%20AppleWebKit%2F537.36%20%28KHTML%2C%20like%20Gecko%29%20Chrome%2F72.0.3626.121%20Safari%2F537.36&url_referrer=https%3A%2F%2Fwww.theresident.co.uk%2F&resolution=1280x800&nb_cpu=4&nav_lang=en-US&date_regen=2019-03-20%2011%3A00%3A31&debug=6%3A%20top%20%21%3D%20window%20-%3E%20document.referrer%20https%3A%2F%2Ftpc.googlesyndication.com%2Fsafeframe%2F1-0-32%2Fhtml%2Fcontainer.html&ao=https%3A%2F%2Fwww.theresident.co.uk&fake=010000&popup_history=9&popup_visible=true&type_crea=2&tagid=225&popup_menubar=true&popup_locationbar=true&popup_personalbar=true&popup_scrollbars=true&popup_statusbar=true&popup_toolbar=true&id11=vitality&id12=&id13=&id14=display&id1=5879457&id2=10134811&id3=143354165&id4=2095028&id5=108249281&id6=0&version=3";getAllNodesContent ( firstNode, contentTab_2, message_2 );
var adloox_impression=1;