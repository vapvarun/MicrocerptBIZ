<?
 class Utils{
        public function Utils(){}

        public function renderMonthsOfTheYearOptions($selected_value = ""){
            $this_array = array("1"=>"1",
                            "2"=>"2",
                            "3"=>"3",
                            "4"=>"4",
                            "5"=>"5",
                            "6"=>"6",
                            "7"=>"7",
                            "8"=>"8",
                            "9"=>"9",
                            "10"=>"10",
                            "11"=>"11",
                            "12"=>"12"
                            );
            return $this->createDropDownListOptionFields($this_array, $selected_value);
        }
		
        public function renderTopicListToDDL($array, $selected_value = ""){
			$limit_array = array();//Don't want the name showing up twice in this list
			foreach($array as $index=>$value){
					if(!in_array($value["topic_title"], $limit_array) || $value["topic_id"]==""){
						if($value["topic_id"]==""){
							$this_array[$value["topic_title"]] = $value["topic_title"];
						}else{
							$this_array[$value["topic_id"]] = $value["topic_title"];
						}
						$limit_array[] = $value["topic_title"];
					}
				}
            return $this->createDropDownListOptionFields($this_array, $selected_value);
        }
		
		public function getNonUserCreateAccountProd($tool_name=""){
			$message = "<p class='padding-tb-15'><font style='font-weight:bold; color:red;'>This Tool is for Members Only!</font><br />
						Before you can access our ".$tool_name.", you'll need to register with The Kidela Network.
						Don't worry, registration is free, and it will only take you a few seconds to complete.<br /><br />
						<a href='/register/?action=register' class='roundedButtonOff'>REGISTER NOW</a>
						</p>
						
						<p class='padding-tb-15'>
						
						<font style='font-weight:bold; color:red;'>Already a Memeber?</font><br />
						Login to your account for instant access.<br /><br />
						<a href='/login/' class='roundedButtonOff'>LOGIN TO MY ACCOUNT</a>
						</p>
						";
			return $message;
		}
		
        public function getTwiiterValidatedNewsProviders($site_id=5){
			
			
			
			
			if($site_id==5){
				$this_array[] = "mining";
				//$this_array[] = "SeekingAlpha";
				$this_array[] = "Aluminum_News";
				$this_array[] = "AluminumNews";
				$this_array[] = "CMEGroup";
				$this_array[] = "CobaltInvesting";
				$this_array[] = "CommoditiesNow";
				$this_array[] = "commodityonline";
				$this_array[] = "CopperInvestor";
				$this_array[] = "DigGold";
				$this_array[] = "DigLithium";
				$this_array[] = "DigVanadium";
				$this_array[] = "DTNStockMarket";
				$this_array[] = "Earth_News";
				$this_array[] = "FiPath";
				$this_array[] = "forrester";
				$this_array[] = "goldminernews";
				$this_array[] = "GoldMoneyNews";
				$this_array[] = "HAInvestor";
				$this_array[] = "InfoMine";
				$this_array[] = "IronOreTeam";
				$this_array[] = "KitcoNewsNow";
				$this_array[] = "marketreturn";
				$this_array[] = "medical_network";
				$this_array[] = "Metalbanker";
				$this_array[] = "MiningConnect";
				$this_array[] = "MiningWatch";
				$this_array[] = "MiningWeekly";
				$this_array[] = "mStockWire";
				$this_array[] = "NanotechWeek";
				$this_array[] = "NewsHour";
				$this_array[] = "PotashInvestor";
				$this_array[] = "proactive_NA";
				$this_array[] = "proformative";
				$this_array[] = "RareEarthNews";
				$this_array[] = "Rareearthwatch";
				$this_array[] = "RareMetalMining";
				$this_array[] = "ResourceInvest";
				$this_array[] = "ReutersMarkets";
				$this_array[] = "revenuewatch";
				$this_array[] = "SearchMining";
				$this_array[] = "SilverNews";
				$this_array[] = "StockBotWire";
				$this_array[] = "stockhouse";
				$this_array[] = "timseymour";
				$this_array[] = "tradepips";
				$this_array[] = "TungstenNews";
				$this_array[] = "worldtradenews";
				$this_array[] = "WSJPersFinance";
				$this_array[] = "YahooFinance";
				$this_array[] = "YESInvestor";
				$this_array[] = "ZincInvesting";
				$this_array[] = "energycollectiv";
				$this_array[] = "globebusiness";
				$this_array[] = "marketwatch";
				$this_array[] = "Copper_News";
				
				$this_array[] = "BBCWorld";
				$this_array[] = "AP";
				$this_array[] = "FT";
				$this_array[] = "Google_News";
				$this_array[] = "APNews";
				$this_array[] = "CNETNews";
				$this_array[] = "CTV";
				$this_array[] = "AKNewswire";
				$this_array[] = "CBC";
				$this_array[] = "CBSNews";
				$this_array[] = "cnnbrk";
				$this_array[] = "CNW_General";
				$this_array[] = "arstechnica";
				$this_array[] = "abcnews";
				$this_array[] = "BBC";
				$this_array[] = "bbcbusiness";
				$this_array[] = "digg";
				$this_array[] = "BankingFinance";
				$this_array[] = "BreakingNews";
				$this_array[] = "BBCNews";
				$this_array[] = "ABCWorldNews";
				$this_array[] = "FoxNews";
				$this_array[] = "CBCNews";
				$this_array[] = "China_Daily";
				$this_array[] = "cnni";
				$this_array[] = "drudge_report";
				$this_array[] = "CNN";
				$this_array[] = "Business_Review";
				$this_array[] = "BusinessWire";
				$this_array[] = "businessnews";
				$this_array[] = "Biotech_News";
				$this_array[] = "guardian";
				$this_array[] = "GuardianUSA";
			}elseif($site_id==2){
				$this_array[] = "BioNewsUK";
				$this_array[] = "biotechstocks";
				$this_array[] = "CDC_Cancer";
				$this_array[] = "CDCgov";
				$this_array[] = "CIHR_IRSC";
				$this_array[] = "CMA_Docs";
				$this_array[] = "daily_briefing";
				$this_array[] = "eMedicine";
				$this_array[] = "FDA_Drug_Info";
				$this_array[] = "FDADeviceInfo";
				$this_array[] = "FierceBiotech";
				$this_array[] = "FiercePharma";
				$this_array[] = "FluGov";
				$this_array[] = "HealthCanada";
				$this_array[] = "healthcare";
				$this_array[] = "HealthCareGov";
				$this_array[] = "healthcouncilca";
				$this_array[] = "healthjournal";
				$this_array[] = "HealthLeaders";
				$this_array[] = "Healthline";
				$this_array[] = "HealthNewsBlogs";
				$this_array[] = "hillhealthwatch";
				$this_array[] = "HITNewsTweet";
				$this_array[] = "IAmBiotech";
				$this_array[] = "Life_Sciences_";
				$this_array[] = "MedBlogger";
				$this_array[] = "MedDevicesDaily";
				$this_array[] = "NewsMedical";
				$this_array[] = "NHCouncil";
				$this_array[] = "NIHforHealth";
				$this_array[] = "PharmaTimes";
				$this_array[] = "pharminews";
				$this_array[] = "ReutersScience";
				$this_array[] = "science";
				$this_array[] = "scienceline";
				$this_array[] = "TheHSF";
				$this_array[] = "TheKidsDoctor";
				$this_array[] = "TheOTCInvestor";
				$this_array[] = "ThePharmaLetter";
				$this_array[] = "TIMEHealthland";
				$this_array[] = "TJCommission";
				$this_array[] = "UNDP";
				$this_array[] = "VacciNewsNet";
				$this_array[] = "WebMD";
				$this_array[] = "WebMD_Blogs";
				$this_array[] = "womenshealth";
				$this_array[] = "WSJHealthBlog";
				$this_array[] = "AmerMedicalAssn";
				$this_array[] = "Biotech_News";
				$this_array[] = "Cardiology";
				$this_array[] = "GlobeHealth";
				$this_array[] = "goodhealth";
				$this_array[] = "HarvardHealth";
				$this_array[] = "Health_IT";
				$this_array[] = "health_tweets";
				$this_array[] = "healthcarewire";
				$this_array[] = "healthreformGPS";
				$this_array[] = "healthwikinews";
				$this_array[] = "HealthyAmerica1";
				$this_array[] = "HopkinsMedNews";
				$this_array[] = "modrnhealthcr";
				$this_array[] = "msnbc_health";
				$this_array[] = "NPRHealth";
				$this_array[] = "nursingworld";
				$this_array[] = "RedCross";
				$this_array[] = "WSJHealth";
				$this_array[] = "WyldeOnHealth";
				$this_array[] = "HarvardHealth";
				$this_array[] = "Health_IT";
				$this_array[] = "health_tweets";
				$this_array[] = "healthcarewire";
				$this_array[] = "healthreformGPS";
				$this_array[] = "healthwikinews";
				$this_array[] = "HealthyAmerica1";
				$this_array[] = "HHSGov";
				$this_array[] = "HopkinsMedNews";
				$this_array[] = "msnbc_health";
				$this_array[] = "NPRHealth";
				$this_array[] = "nursingworld";
				$this_array[] = "RedCross";
				$this_array[] = "WSJHealth";
				$this_array[] = "WyldeOnHealth";
			}elseif($site_id==3){
				$this_array[] = "ALA_TechSource";
				$this_array[] = "AppStore";
				$this_array[] = "eMarketer";
				$this_array[] = "MakeUseOf";
				$this_array[] = "NetworkWorld";
				$this_array[] = "OpenMedia_ca";
				$this_array[] = "sengineland";
				$this_array[] = "SEO_Exp";
				$this_array[] = "sewatch";
				$this_array[] = "socialmedia2day";
				$this_array[] = "SocialMedia411";
				$this_array[] = "Techland";
				$this_array[] = "TechnoBuffalo";
				$this_array[] = "technorati";
				$this_array[] = "telecoms";
				$this_array[] = "tgdaily";
				$this_array[] = "TheCloudNetwork";
				$this_array[] = "TheNextWeb";
				$this_array[] = "Ustream";
				$this_array[] = "VentureBeat";
				$this_array[] = "virusbtn";
				$this_array[] = "Xconomy";
				$this_array[] = "cnet";
				$this_array[] = "digitalspy";
				$this_array[] = "arstechnica";
				$this_array[] = "CrackBerry";
				$this_array[] = "engadget";
				$this_array[] = "geekforever";
				$this_array[] = "gigaom";
				$this_array[] = "gizmodo";
				$this_array[] = "GoogleTech";
				$this_array[] = "IntoMobile";
				$this_array[] = "LATimestech";
				$this_array[] = "MacObserver";
				$this_array[] = "MikeTechShow";
				$this_array[] = "PCMag";
				$this_array[] = "stumbleupon";
				$this_array[] = "Techcrunch";
				$this_array[] = "Techmeme";
				$this_array[] = "Technibble";
				$this_array[] = "TechRepublic";
				$this_array[] = "Techwatch";
				$this_array[] = "techwatching";
				$this_array[] = "techweb";
				$this_array[] = "TheNextWeb";
				$this_array[] = "Wired";
				$this_array[] = "ZDNetBlogs";
				$this_array[] = "LATimestech";
				$this_array[] = "MacObserver";
				$this_array[] = "mashable";
				$this_array[] = "MikeTechShow";
				$this_array[] = "NASA";
				$this_array[] = "nytimesscience";
				$this_array[] = "pchere";
				$this_array[] = "PCMag";
				$this_array[] = "Techcrunch";
				$this_array[] = "Techmeme";
				$this_array[] = "Technibble";
				$this_array[] = "TechRepublic";
				$this_array[] = "Techwatch";
				$this_array[] = "techwatching";
				$this_array[] = "techweb";
				$this_array[] = "Wired";
				$this_array[] = "ZDNetBlogs";


			}else{
				
				$this_array[] = "mining_news";
				$this_array[] = "miningbridge";
				$this_array[] = "Metal_Miner";
				$this_array[] = "BBCWorld";
				$this_array[] = "jimcramer";
				$this_array[] = "AP";
				$this_array[] = "abcnews";
				$this_array[] = "sfgate";
				$this_array[] = "ABCWorldNews";
				$this_array[] = "BBCNews";
				$this_array[] = "BBC";
				$this_array[] = "CBC";
				$this_array[] = "CBCNews";
				$this_array[] = "CTV";
				$this_array[] = "NBCNews";
				$this_array[] = "FoxNews";
				$this_array[] = "CNN";
				$this_array[] = "cnnbrk";
				$this_array[] = "cnni";
				$this_array[] = "BreakingNews";
				$this_array[] = "nasdaq";
				$this_array[] = "ETIPO";
				$this_array[] = "IPOtweet";
				$this_array[] = "ipowatch";
				$this_array[] = "EINIPONews";
				$this_array[] = "JointVentureCNW";
				$this_array[] = "GoogleVentures";
				$this_array[] = "Venture_Capital";
				$this_array[] = "WorldBank";
				$this_array[] = "EU_Commission";
				$this_array[] = "CommerceGov";
				$this_array[] = "Reuters";
				$this_array[] = "CBSNews";
				$this_array[] = "AJEnglish";
				$this_array[] = "SNCopperNews";
				$this_array[] = "Copper_News";
				$this_array[] = "proactive_au";
				$this_array[] = "LabourNews";
				$this_array[] = "OilEnergyNews";
				$this_array[] = "Biotech_News";
				$this_array[] = "CNW_Financial";
				$this_array[] = "CNW_General";
				$this_array[] = "Retail_News";
				$this_array[] = "miningtweets";
				$this_array[] = "moneycontrolcom";
				$this_array[] = "BankingFinance";
				$this_array[] = "BusinessWire";
				$this_array[] = "Business_Review";
				$this_array[] = "YouTube";
				$this_array[] = "GuardianUSA";
				$this_array[] = "msnbc";
				$this_array[] = "nytimes";
				$this_array[] = "drudge_report";
				$this_array[] = "Wikipedia";
				$this_array[] = "FT";
				$this_array[] = "Yahoo";
				$this_array[] = "TheNextWeb";
				$this_array[] = "GoogleTech";
				$this_array[] = "Google";
				$this_array[] = "arstechnica";
				$this_array[] = "CNETNews";
				$this_array[] = "engadget";
				$this_array[] = "geekforever";
				$this_array[] = "gigaom";
				$this_array[] = "gizmodo";
				$this_array[] = "MacObserver";
				$this_array[] = "MikeTechShow";
				$this_array[] = "pchere";
				$this_array[] = "PCMag";
				$this_array[] = "Techcrunch";
				$this_array[] = "Technibble";
				$this_array[] = "CrackBerry";
				$this_array[] = "TechRepublic";
				$this_array[] = "Techwatch";
				$this_array[] = "techwatching";
				$this_array[] = "Wired";
				$this_array[] = "ZDNetBlogs";
				$this_array[] = "IntoMobile";
				$this_array[] = "HHSGov";
				$this_array[] = "DrOz";
				$this_array[] = "sanjayguptaCNN";
				$this_array[] = "WSJHealth";
				$this_array[] = "healthcarewire";
				$this_array[] = "WSJEurope";
				$this_array[] = "KHNNews";
				$this_array[] = "modrnhealthcr";
				$this_array[] = "nursingworld";
				$this_array[] = "HealthyAmerica1";
				$this_array[] = "healthreformGPS";
				$this_array[] = "AmerMedicalAssn";
				$this_array[] = "Cardiology";
				$this_array[] = "WyldeOnHealth";
				$this_array[] = "HuffingtonPost";
				$this_array[] = "mashable";
				$this_array[] = "AKNewswire";
				$this_array[] = "ChrisPirillo";
				$this_array[] = "chicagotribune";
				$this_array[] = "LATimesnews";
				$this_array[] = "TIME";
				$this_array[] = "economictimes";
				$this_array[] = "APNews";
				$this_array[] = "japantimes";
				$this_array[] = "africanewsfeed";
				$this_array[] = "guardian";
				$this_array[] = "TelegraphNews";
				$this_array[] = "China_Daily";
				$this_array[] = "New_Europe";
				$this_array[] = "insideeurope";
				$this_array[] = "Google_News";
				$this_array[] = "YahooNews";
				$this_array[] = "MSNdotcom";
				$this_array[] = "investopedia";
				$this_array[] = "digg";
				$this_array[] = "reddit";
				$this_array[] = "stumbleupon";
				$this_array[] = "espn";
				$this_array[] = "financialtimes";
				$this_array[] = "Reuters_Biz";
				$this_array[] = "LATimesbiz";
				$this_array[] = "LATimesmoneyco";
				$this_array[] = "businessnews";
				$this_array[] = "CNNMoney";
				$this_array[] = "FortuneMagazine";
				$this_array[] = "entmagazine";
				$this_array[] = "nytimesbusiness";
				$this_array[] = "bbcbusiness";
				$this_array[] = "amazon";
				$this_array[] = "GlobeHealth";
				$this_array[] = "goodhealth";
				$this_array[] = "NatGeoSociety";
				$this_array[] = "NASA";
				$this_array[] = "SciNewsBlog";
				$this_array[] = "nytimesscience";
				$this_array[] = "newscientist";
				$this_array[] = "DiscoverMag";
				$this_array[] = "sciencedaily";
				$this_array[] = "Techmeme";
				$this_array[] = "LATimestech";
				$this_array[] = "truemors";
				$this_array[] = "techweb";
				$this_array[] = "FinRoad";
				$this_array[] = "WorldOil";
				$this_array[] = "miningnuggets";
				$this_array[] = "NPRHealth";
				$this_array[] = "Health_IT";
				$this_array[] = "msnbc_health";
				$this_array[] = "health_tweets";
				$this_array[] = "HopkinsMedNews";
				$this_array[] = "healthwikinews";
				$this_array[] = "RedCross";
				$this_array[] = "HarvardHealth";
				
				
				//Old Array - I guess now will be used as a a default
				/*$this_array[] = "mining_news";
				$this_array[] = "miningbridge";
				$this_array[] = "Metal_Miner";
				$this_array[] = "BBCWorld";
				$this_array[] = "jimcramer";
				$this_array[] = "AP";
				$this_array[] = "abcnews";
				$this_array[] = "ABCWorldNews";
				$this_array[] = "BBCNews";
				$this_array[] = "BBC";
				$this_array[] = "CBC";
				$this_array[] = "CBCNews";
				$this_array[] = "CTV";
				$this_array[] = "NBCNews";
				$this_array[] = "FoxNews";
				$this_array[] = "CNN";
				$this_array[] = "cnnbrk";
				$this_array[] = "cnni";
				$this_array[] = "BreakingNews";
				$this_array[] = "Reuters";
				$this_array[] = "CBSNews";
				$this_array[] = "AJEnglish";
				$this_array[] = "SNCopperNews";
				$this_array[] = "Copper_News";
				$this_array[] = "proactive_au";
				$this_array[] = "LabourNews";
				$this_array[] = "OilEnergyNews";
				$this_array[] = "Biotech_News";
				$this_array[] = "CNW_Financial";//
				$this_array[] = "CNW_General";
				$this_array[] = "Retail_News";
				$this_array[] = "miningtweets";
				$this_array[] = "BankingFinance";
				$this_array[] = "BusinessWire";
				$this_array[] = "Business_Review";
				$this_array[] = "YouTube";
				$this_array[] = "GuardianUSA";
				$this_array[] = "msnbc";
				$this_array[] = "nytimes";
				$this_array[] = "drudge_report";
				$this_array[] = "Wikipedia";
				$this_array[] = "FT";
				$this_array[] = "Yahoo";
				$this_array[] = "TheNextWeb";
				$this_array[] = "GoogleTech";
				$this_array[] = "Google";*/
			}
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
			
            return $this_array;
        }
		
		public function getSiteID($url){
			if(str_replace("resources","",$url) != $url){
				return 5;
			}
			if(str_replace("healthcare","",$url) != $url){
				return 2;
			}
			if(str_replace("technology","",$url) != $url){
				return 3;
			}
			if(str_replace("money","",$url) != $url){
				return 6;
			}
			if(str_replace("ioww","",$url) != $url){
				return 7;
			}
			return 1;
		}
		
		public function getSiteName($url){
			if(str_replace("resources","",$url) != $url){
				return "Resources";
			}
			if(str_replace("healthcare","",$url) != $url){
				return "Healthcare";
			}
			if(str_replace("technology","",$url) != $url){
				return "Technology";
			}
			if(str_replace("money","",$url) != $url){
				return "Money";
			}
			if(str_replace("ioww","",$url) != $url){
				return "Imagine Our World Without";
			}
			return "Resources";
		}
		
        public function renderCategoryTopicOptions($selected_value = "",$limit_array = array(),$use_name_for_value=false){
			$this_array = $this->getAlertTopicArray();
			$return_array = array();
			foreach($this_array as $index=>$value){
				if(!in_array($index, $limit_array) ){
					if($use_name_for_value){
						$return_array[$value] = $value;
					}else{
						$return_array[$index] = $value;
					}
				}
			}
			
            return $this->createDropDownListOptionFields($return_array, $selected_value);
        }

		public function convertMysqlResultToArray($result){
			$all = array();
				foreach($result as $index=>$value){
					$all[] = (array) $value;
				}
			return $all;
		}
		
		public function getAlertTopicName($id){
			$array_list = $this->getAlertTopicArray();
			return $array_list[$id];
		}
		
		public function getAlertTopicArray($selected_value = ""){
			
            $this_array = array("1"=>"Precious Metals",
                            "2"=>"Base Metals",
                            "3"=>"Critical/Strategic Metals",
                            "4"=>"Rare Earth Elements",
                            "5"=>"Industrial Minerals",
                            "6"=>"Nuclear",
							"7"=>"Renewable",
							"8"=>"Power Storage",
							"9"=>"Oil & Gas",
							"10"=>"Forestry",
							"11"=>"Pharmaceuticals",
							"12"=>"Drug Companies",
							"13"=>"Conditions & Diseases",
							"14"=>"Treatments & Therapies",
							"15"=>"Life Sciences",
							"16"=>"Healthcare Services",
							"17"=>"Computers",
							"18"=>"Communication Equipment",
							"19"=>"Broadband & Cable Providers",
							"20"=>"Mobile Phones/Devices",
							"21"=>"Internet Services"
                            );
            return $this_array;
		}
		

		public function getCellPhoneCarriersOptions($selected_value){
            $this_array = array(
							"cingularme.com"=>"Cingular",
                            "messaging.nextel.com"=>"Nextel",
                            "messaging.sprintpcs.com"=>"Sprint",
                            "tmomail.net"=>"T-Mobile",
                            "vtext.com"=>"Verizon",
                            "vmobl.com"=>"Virgin Mobile",
							"pcs.rogers.com"=>"Rogers (CA)",
							"txt.bell.ca"=>"Bell Mobility (CA)", 
							"msg.telus.com "=>"Telus (CA)"
                            );
            return $this->createDropDownListOptionFields($this_array, $selected_value);
		}
		
		public function getCellPhoneCarrierEmailFromName($selected_value){
			$selected_value = strtolower($selected_value);
			$this_array["sprint"] = "messaging.sprintpcs.com";
			$this_array["nextel"] = "messaging.nextel.com";
			$this_array["t-mobile"] = "tmomail.net";
			$this_array["verizon"] = "vtext.com";
			$this_array["virgin mobile"] = "vmobl.com";
			$this_array["rogers (ca)"] = "pcs.rogers.com";
			$this_array["bell mobility (ca)"] = "txt.bell.ca";
			$this_array["telus (ca)"] = "msg.telus.com";
			$this_array["cingular"] = "cingularme.com";
			$return = $this_array[$selected_value];
            return $return;
		}
		
		public function renderYearsOptions($selected_value = ""){
			$loop=2011;
			while($loop<=date("Y")){
				$this_array[$loop] = $loop;
				$loop++;
			}
            return $this->createDropDownListOptionFields($this_array, $selected_value);
        }
		
		public function renderDaysOfTheMonthOptions($selected_value = ""){
			$loop=1;
			while($loop<32){
				$this_array[$loop] = $loop;
				$loop++;
			}
            return $this->createDropDownListOptionFields($this_array, $selected_value);
        }
		
        public function createDropDownListOptionFields($array, $selected_value=""){
			
            foreach($array as $key => $value){
                if($key == $selected_value){
                    $return_string .= "<option selected='true' value='".$key."'>".$value."</option>";
                }else{
                    $return_string .= "<option value='".$key."'>".$value."</option>";
                }
            }
            return $return_string;
        }
		
		public function getStringBetweenPoints($start_string, $end_string, $content){
			$return_var = "";
			$explode1 = explode($start_string, $content);
			$explode2 = explode($end_string, $explode1[1]);
			$return_var = $explode2[0];
			return $return_var;
		}
		
		public function cleanPostArrayVariables($array){
			if(count($array)>0){	
				foreach($array as $index=>$value){
						$this_value = $value; 
						$this_value = str_replace('"' ,'&quot;',$this_value);
						$this_value = str_replace("'" ,"&#39;",$this_value);
						
						$this_value = str_replace("’" ,"&#39;",$this_value);
						$this_value = stripslashes(stripslashes(stripslashes(stripslashes($this_value))));
						$this_value = trim($this_value);
						$array[$index] = $this_value;
					}
			}
			return $array;
		}
		
		
		public function getPostUrlForLink($site_id,$id){
			if($site_id==5){
				$url = "http://resources.kidela.com/?p=".$id;
			}elseif($site_id==2){
				$url = "http://healthcare.kidela.com/?p=".$id;
			}elseif($site_id==3){
				$url = "http://technology.kidela.com/?p=".$id;
			}elseif($site_id==6){
				$url = "http://money.kidela.com/?p=".$id;
			}
			return $url;
		}

	public function getNewPostsFromAllSites($wpdb,$limit=5){		
			$query = "(SELECT  '5' AS site_id, 'resources' as site_name, w.post_date, w.post_title,w.ID FROM wp_5_posts w WHERE  w.post_status='publish' AND w.post_type = 'post' ORDER BY w.post_date DESC LIMIT 0,".$limit.")";
		$query .= " UNION ( SELECT  '2' AS site_id,'healthcare' as site_name,  w.post_date, w.post_title,w.ID  FROM wp_2_posts w  WHERE w.post_status='publish' AND w.post_type = 'post' ORDER BY w.post_date DESC LIMIT 0,".$limit.")";
		// Don't want to go live with money right away... uncomment when ready to go live
		//$query .= " UNION ( SELECT  '6' AS site_id,'money' as site_name,  w.post_date, w.post_title,w.ID  FROM wp_2_posts w  WHERE post_status='publish' AND post_type = 'post' ORDER BY post_date DESC LIMIT 0,".$limit.")";
		$query .= " UNION ( SELECT  '3' AS site_id, 'technology' as site_name, w.post_date, w.post_title,w.ID  FROM wp_3_posts w  WHERE  w.post_status='publish' AND w.post_type = 'post' ORDER BY w.post_date DESC  LIMIT 0,".$limit.")  ORDER BY post_date DESC LIMIT 0,".$limit;
		$results1 = $wpdb->get_results($query); 
		return $this->convertMysqlResultToArray($results1);
	}
		
	public function objectToArray($d) {
		if (is_object($d)) {
			// Gets the properties of the given object
			// with get_object_vars function
			$d = get_object_vars($d);
		}
 
		if (is_array($d)) {
			/*
			* Return array converted to object
			* Using __FUNCTION__ (Magic constant)
			* for recursive call
			*/
			return array_map(__FUNCTION__, $d);
		}
		else {
			// Return array
			return $d;
		}
	}
		
 }
?>