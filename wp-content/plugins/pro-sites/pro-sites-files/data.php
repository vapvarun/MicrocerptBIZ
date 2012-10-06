<?php
/*
Pro Sites Data Structures
*/

//country list
$this->countries = array(
  "AF" => "Afghanistan",
  "AL" => "Albania",
  "DZ" => "Algeria",
  "AS" => "American Samoa",
  "AD" => "Andorra",
  "AO" => "Angola",
  "AI" => "Anguilla",
  "AQ" => "Antarctica",
  "AG" => "Antigua And Barbuda",
  "AR" => "Argentina",
  "AM" => "Armenia",
  "AW" => "Aruba",
  "AU" => "Australia",
  "AT" => "Austria",
  "AZ" => "Azerbaijan",
  "BS" => "Bahamas",
  "BH" => "Bahrain",
  "BD" => "Bangladesh",
  "BB" => "Barbados",
  "BY" => "Belarus",
  "BE" => "Belgium",
  "BZ" => "Belize",
  "BJ" => "Benin",
  "BM" => "Bermuda",
  "BT" => "Bhutan",
  "BO" => "Bolivia",
  "BA" => "Bosnia And Herzegowina",
  "BW" => "Botswana",
  "BV" => "Bouvet Island",
  "BR" => "Brazil",
  "IO" => "British Indian Ocean Territory",
  "BN" => "Brunei Darussalam",
  "BG" => "Bulgaria",
  "BF" => "Burkina Faso",
  "BI" => "Burundi",
  "KH" => "Cambodia",
  "CM" => "Cameroon",
  "CA" => "Canada",
  "CV" => "Cape Verde",
  "KY" => "Cayman Islands",
  "CF" => "Central African Republic",
  "TD" => "Chad",
  "CL" => "Chile",
  "CN" => "China",
  "CX" => "Christmas Island",
  "CC" => "Cocos (Keeling) Islands",
  "CO" => "Colombia",
  "KM" => "Comoros",
  "CG" => "Congo",
  "CD" => "Congo, The Democratic Republic Of The",
  "CK" => "Cook Islands",
  "CR" => "Costa Rica",
  "CI" => "Cote D'Ivoire",
  "HR" => "Croatia (Local Name: Hrvatska)",
  "CU" => "Cuba",
  "CY" => "Cyprus",
  "CZ" => "Czech Republic",
  "DK" => "Denmark",
  "DJ" => "Djibouti",
  "DM" => "Dominica",
  "DO" => "Dominican Republic",
  "TP" => "East Timor",
  "EC" => "Ecuador",
  "EG" => "Egypt",
  "SV" => "El Salvador",
  "GQ" => "Equatorial Guinea",
  "ER" => "Eritrea",
  "EE" => "Estonia",
  "ET" => "Ethiopia",
  "FK" => "Falkland Islands (Malvinas)",
  "FO" => "Faroe Islands",
  "FJ" => "Fiji",
  "FI" => "Finland",
  "FR" => "France",
  "FX" => "France, Metropolitan",
  "GF" => "French Guiana",
  "PF" => "French Polynesia",
  "TF" => "French Southern Territories",
  "GA" => "Gabon",
  "GM" => "Gambia",
  "GE" => "Georgia",
  "DE" => "Germany",
  "GH" => "Ghana",
  "GI" => "Gibraltar",
  "GR" => "Greece",
  "GL" => "Greenland",
  "GD" => "Grenada",
  "GP" => "Guadeloupe",
  "GU" => "Guam",
  "GT" => "Guatemala",
  "GN" => "Guinea",
  "GW" => "Guinea-Bissau",
  "GY" => "Guyana",
  "HT" => "Haiti",
  "HM" => "Heard And Mc Donald Islands",
  "VA" => "Holy See (Vatican City State)",
  "HN" => "Honduras",
  "HK" => "Hong Kong",
  "HU" => "Hungary",
  "IS" => "Iceland",
  "IN" => "India",
  "ID" => "Indonesia",
  "IR" => "Iran (Islamic Republic Of)",
  "IQ" => "Iraq",
  "IE" => "Ireland",
  "IL" => "Israel",
  "IT" => "Italy",
  "JM" => "Jamaica",
  "JP" => "Japan",
  "JO" => "Jordan",
  "KZ" => "Kazakhstan",
  "KE" => "Kenya",
  "KI" => "Kiribati",
  "KP" => "Korea, Democratic People's Republic Of",
  "KR" => "Korea, Republic Of",
  "KW" => "Kuwait",
  "KG" => "Kyrgyzstan",
  "LA" => "Lao People's Democratic Republic",
  "LV" => "Latvia",
  "LB" => "Lebanon",
  "LS" => "Lesotho",
  "LR" => "Liberia",
  "LY" => "Libyan Arab Jamahiriya",
  "LI" => "Liechtenstein",
  "LT" => "Lithuania",
  "LU" => "Luxembourg",
  "MO" => "Macau",
  "MK" => "Macedonia, Former Yugoslav Republic Of",
  "MG" => "Madagascar",
  "MW" => "Malawi",
  "MY" => "Malaysia",
  "MV" => "Maldives",
  "ML" => "Mali",
  "MT" => "Malta",
  "MH" => "Marshall Islands",
  "MQ" => "Martinique",
  "MR" => "Mauritania",
  "MU" => "Mauritius",
  "YT" => "Mayotte",
  "MX" => "Mexico",
  "FM" => "Micronesia, Federated States Of",
  "MD" => "Moldova, Republic Of",
  "MC" => "Monaco",
  "MN" => "Mongolia",
  "MS" => "Montserrat",
  "MA" => "Morocco",
  "MZ" => "Mozambique",
  "MM" => "Myanmar",
  "NA" => "Namibia",
  "NR" => "Nauru",
  "NP" => "Nepal",
  "NL" => "Netherlands",
  "AN" => "Netherlands Antilles",
  "NC" => "New Caledonia",
  "NZ" => "New Zealand",
  "NI" => "Nicaragua",
  "NE" => "Niger",
  "NG" => "Nigeria",
  "NU" => "Niue",
  "NF" => "Norfolk Island",
  "MP" => "Northern Mariana Islands",
  "NO" => "Norway",
  "OM" => "Oman",
  "PK" => "Pakistan",
  "PW" => "Palau",
  "PA" => "Panama",
  "PG" => "Papua New Guinea",
  "PY" => "Paraguay",
  "PE" => "Peru",
  "PH" => "Philippines",
  "PN" => "Pitcairn",
  "PL" => "Poland",
  "PT" => "Portugal",
  "PR" => "Puerto Rico",
  "QA" => "Qatar",
  "RE" => "Reunion",
  "RO" => "Romania",
  "RU" => "Russian Federation",
  "RW" => "Rwanda",
  "KN" => "Saint Kitts And Nevis",
  "LC" => "Saint Lucia",
  "VC" => "Saint Vincent And The Grenadines",
  "WS" => "Samoa",
  "SM" => "San Marino",
  "ST" => "Sao Tome And Principe",
  "SA" => "Saudi Arabia",
  "SN" => "Senegal",
  "RS" => "Serbia",
  "SC" => "Seychelles",
  "SL" => "Sierra Leone",
  "SG" => "Singapore",
  "SK" => "Slovakia (Slovak Republic)",
  "SI" => "Slovenia",
  "SB" => "Solomon Islands",
  "SO" => "Somalia",
  "ZA" => "South Africa",
  "GS" => "South Georgia, South Sandwich Islands",
  "ES" => "Spain",
  "LK" => "Sri Lanka",
  "SH" => "St. Helena",
  "PM" => "St. Pierre And Miquelon",
  "SD" => "Sudan",
  "SR" => "Suriname",
  "SJ" => "Svalbard And Jan Mayen Islands",
  "SZ" => "Swaziland",
  "SE" => "Sweden",
  "CH" => "Switzerland",
  "SY" => "Syrian Arab Republic",
  "TW" => "Taiwan",
  "TJ" => "Tajikistan",
  "TZ" => "Tanzania, United Republic Of",
  "TH" => "Thailand",
  "TG" => "Togo",
  "TK" => "Tokelau",
  "TO" => "Tonga",
  "TT" => "Trinidad And Tobago",
  "TN" => "Tunisia",
  "TR" => "Turkey",
  "TM" => "Turkmenistan",
  "TC" => "Turks And Caicos Islands",
  "TV" => "Tuvalu",
  "UG" => "Uganda",
  "UA" => "Ukraine",
  "AE" => "United Arab Emirates",
  "GB" => "United Kingdom",
  "US" => "United States",
  "UM" => "United States Minor Outlying Islands",
  "UY" => "Uruguay",
  "UZ" => "Uzbekistan",
  "VU" => "Vanuatu",
  "VE" => "Venezuela",
  "VN" => "Viet Nam",
  "VG" => "Virgin Islands (British)",
  "VI" => "Virgin Islands (U.S.)",
  "WF" => "Wallis And Futuna Islands",
  "EH" => "Western Sahara",
  "YE" => "Yemen",
  "YU" => "Yugoslavia",
  "ZM" => "Zambia",
  "ZW" => "Zimbabwe"
);

//USA states list
$this->usa_states = array(
  'AL'=>"Alabama",
	'AK'=>"Alaska",
	'AZ'=>"Arizona",
	'AR'=>"Arkansas",
	'CA'=>"California",
	'CO'=>"Colorado",
	'CT'=>"Connecticut",
	'DE'=>"Delaware",
	'DC'=>"District Of Columbia",
	'FL'=>"Florida",
	'GA'=>"Georgia",
	'HI'=>"Hawaii",
	'ID'=>"Idaho",
	'IL'=>"Illinois",
	'IN'=>"Indiana",
	'IA'=>"Iowa",
	'KS'=>"Kansas",
	'KY'=>"Kentucky",
	'LA'=>"Louisiana",
	'ME'=>"Maine",
	'MD'=>"Maryland",
	'MA'=>"Massachusetts",
	'MI'=>"Michigan",
	'MN'=>"Minnesota",
	'MS'=>"Mississippi",
	'MO'=>"Missouri",
	'MT'=>"Montana",
	'NE'=>"Nebraska",
	'NV'=>"Nevada",
	'NH'=>"New Hampshire",
	'NJ'=>"New Jersey",
	'NM'=>"New Mexico",
	'NY'=>"New York",
	'NC'=>"North Carolina",
	'ND'=>"North Dakota",
	'OH'=>"Ohio",
	'OK'=>"Oklahoma",
	'OR'=>"Oregon",
	'PA'=>"Pennsylvania",
	'RI'=>"Rhode Island",
	'SC'=>"South Carolina",
	'SD'=>"South Dakota",
	'TN'=>"Tennessee",
	'TX'=>"Texas",
	'UT'=>"Utah",
	'VT'=>"Vermont",
	'VA'=>"Virginia",
	'WA'=>"Washington",
	'WV'=>"West Virginia",
	'WI'=>"Wisconsin",
	'WY'=>"Wyoming"
);

//UK counties/provinces
$this->uk_counties = array("Aberdeenshire"=>"Aberdeenshire","Angus/Forfarshire"=>"Angus/Forfarshire","Argyllshire"=>"Argyllshire",
"Ayrshire"=>"Ayrshire","Banffshire"=>"Banffshire","Bedfordshire"=>"Bedfordshire","Berkshire"=>"Berkshire",
"Berwickshire"=>"Berwickshire","Blaenau Gwent"=>"Blaenau Gwent","Bridgend"=>"Bridgend",
"Buckinghamshire"=>"Buckinghamshire","Buteshire"=>"Buteshire","Caerphilly"=>"Caerphilly","Caithness"=>"Caithness",
"Cambridgeshire"=>"Cambridgeshire","Cardiff"=>"Cardiff","Carmarthenshire"=>"Carmarthenshire",
"Ceredigion"=>"Ceredigion","Cheshire"=>"Cheshire","Clackmannanshire"=>"Clackmannanshire",
"Conwy"=>"Conwy","Cornwall"=>"Cornwall","Cromartyshire"=>"Cromartyshire","Cumbria"=>"Cumbria",
"Denbighshire"=>"Denbighshire","Derbyshire"=>"Derbyshire","Devon"=>"Devon","Dorset"=>"Dorset",
"Dumfriesshire"=>"Dumfriesshire","Dunbartonshire/Dumbartonshire"=>"Dunbartonshire/Dumbartonshire",
"Durham"=>"Durham","East Lothian/Haddingtonshire"=>"East Lothian/Haddingtonshire","Essex"=>"Essex","Fife"=>"Fife",
"Flintshire"=>"Flintshire","Gloucestershire"=>"Gloucestershire","Greater Manchester"=>"Greater Manchester","Gwynedd"=>"Gwynedd","Hampshire"=>"Hampshire",
"Herefordshire"=>"Herefordshire","Hertfordshire"=>"Hertfordshire","Huntingdonshire"=>"Huntingdonshire",
"Inverness-shire"=>"Inverness-shire","Isle of Anglesey"=>"Isle of Anglesey","Kent"=>"Kent",
"Kincardineshire"=>"Kincardineshire","Kinross-shire"=>"Kinross-shire","Kirkcudbrightshire"=>"Kirkcudbrightshire",
"Lanarkshire"=>"Lanarkshire","Lancashire"=>"Lancashire","Leicestershire"=>"Leicestershire",
"Lincolnshire"=>"Lincolnshire","Merthyr Tydfil"=>"Merthyr Tydfil","Middlesex"=>"Middlesex",
"Midlothian/Edinburghshire"=>"Midlothian/Edinburghshire","Monmouthshire"=>"Monmouthshire",
"Morayshire"=>"Morayshire","Nairnshire"=>"Nairnshire","Neath Port Talbot"=>"Neath Port Talbot",
"Newport"=>"Newport","Norfolk"=>"Norfolk","Northamptonshire"=>"Northamptonshire",
"Northumberland"=>"Northumberland","Nottinghamshire"=>"Nottinghamshire","Orkney"=>"Orkney",
"Oxfordshire"=>"Oxfordshire","Peeblesshire"=>"Peeblesshire","Pembrokeshire"=>"Pembrokeshire",
"Perthshire"=>"Perthshire","Powys"=>"Powys","Renfrewshire"=>"Renfrewshire",
"Rhondda Cynon Taff"=>"Rhondda Cynon Taff","Ross-shire"=>"Ross-shire","Roxburghshire"=>"Roxburghshire",
"Rutland"=>"Rutland","Selkirkshire"=>"Selkirkshire","Shetland"=>"Shetland","Shropshire"=>"Shropshire",
"Somerset"=>"Somerset","Staffordshire"=>"Staffordshire","Stirlingshire"=>"Stirlingshire","Suffolk"=>"Suffolk",
"Surrey"=>"Surrey","Sussex"=>"Sussex","Sutherland"=>"Sutherland","Swansea"=>"Swansea","Torfaen"=>"Torfaen",
"Vale of Glamorgan"=>"Vale of Glamorgan","Warwickshire"=>"Warwickshire",
"West Lothian/Linlithgowshire"=>"West Lothian/Linlithgowshire","Westmorland"=>"Westmorland",
"Wigtownshire"=>"Wigtownshire","Wiltshire"=>"Wiltshire",
"Worcestershire"=>"Worcestershire","Wrexham"=>"Wrexham","Yorkshire"=>"Yorkshire");

//Australian states
$this->australian_states = array(
  "NSW"=>"New South Wales",
  "VIC"=>"Victoria",
  "QLD"=>"Queensland",
  "TAS"=>"Tasmania",
  "SA"=>"South Australia",
  "WA"=>"Western Australia",
  "NT"=>"Northern Territory",
  "ACT"=>"Australian Capital Territory"
);

//Canadian provinces
$this->canadian_provinces = array(
  "BC"=>"British Columbia",
  "ON"=>"Ontario",
  "NL"=>"Newfoundland and Labrador",
  "NS"=>"Nova Scotia",
  "PE"=>"Prince Edward Island",
  "NB"=>"New Brunswick",
  "QC"=>"Quebec",
  "MB"=>"Manitoba",
  "SK"=>"Saskatchewan",
  "AB"=>"Alberta",
  "NT"=>"Northwest Territories",
  "NU"=>"Nunavut",
  "YT"=>"Yukon Territory"
);

//European Union Member countries in ISO 3166-1 alpha-2. Used for shipping and tax calculations
$this->eu_countries = array(
  'AT',
  'BE',
  'BG',
  'CY',
  'CZ',
  'DK',
  'EE',
  'FI',
  'FR',
  'DE',
  'GB',
  'GR',
  'HU',
  'IE',
  'IT',
  'LV',
  'LT',
  'LU',
  'MT',
  'NL',
  'PL',
  'PT',
  'RO',
  'SK',
  'SI',
  'ES',
  'SE'
);

//currency list
$this->currencies = array(
  "ALL"=> array("Albania, Leke", "4c, 65, 6b"),
  "AFN"=> array("Afghanistan, Afghanis", "60b"),
  "ARS"=> array("Argentina, Pesos", "24"),
  "AWG"=> array("Aruba, Guilders (also called Florins)", "192"),
  "AUD"=> array("Australia, Dollars", "24"),
  "AZN"=> array("Azerbaijan, New Manats", "43c, 430, 43d"),
  "BSD"=> array("Bahamas, Dollars", "24"),
  "BBD"=> array("Barbados, Dollars", "24"),
  "BYR"=> array("Belarus, Rubles", "70, 2e"),
  "BZD"=> array("Belize, Dollars", "42, 5a, 24"),
  "BMD"=> array("Bermuda, Dollars", "24"),
  "BOB"=> array("Bolivia, Bolivianos", "24, 62"),
  "BAM"=> array("Bosnia and Herzegovina, Convertible Marka", "4b, 4d"),
  "BWP"=> array("Botswana, Pulas", "50"),
  "BGN"=> array("Bulgaria, Leva", "43b, 432"),
  "BRL"=> array("Brazil, Reais", "52, 24"),
  "BND"=> array("Brunei Darussalam, Dollars", "24"),
  "KHR"=> array("Cambodia, Riels", "17db"),
  "CAD"=> array("Canada, Dollars", "24"),
  "KYD"=> array("Cayman Islands, Dollars", "24"),
  "CLP"=> array("Chile, Pesos", "24"),
  "CNY"=> array("China, Yuan Renminbi", "a5"),
  "COP"=> array("Colombia, Pesos", "24"),
  "CRC"=> array("Costa Rica, Colon", "20a1"),
  "HRK"=> array("Croatia, Kuna", "6b, 6e"),
  "CUP"=> array("Cuba, Pesos", "20b1"),
  "CZK"=> array("Czech Republic, Koruny", "4b, 10d"),
  "DKK"=> array("Denmark, Kroner", "6b, 72"),
  "DOP"=> array("Dominican Republic, Pesos", "52, 44, 24"),
  "XCD"=> array("East Caribbean, Dollars", "24"),
  "EGP"=> array("Egypt, Pounds", "a3"),
  "SVC"=> array("El Salvador, Colones", "24"),
  "EEK"=> array("Estonia, Krooni", "6b, 72"),
  "EUR"=> array("Euro", "20ac"),
  "FKP"=> array("Falkland Islands, Pounds", "a3"),
  "FJD"=> array("Fiji, Dollars", "24"),
  "GEL"=> array("Georgia, lari", "6c, 61, 72, 69"),
  "GHC"=> array("Ghana, Cedis", "a2"),
  "GIP"=> array("Gibraltar, Pounds", "a3"),
  "GTQ"=> array("Guatemala, Quetzales", "51"),
  "GGP"=> array("Guernsey, Pounds", "a3"),
  "GYD"=> array("Guyana, Dollars", "24"),
  "HNL"=> array("Honduras, Lempiras", "4c"),
  "HKD"=> array("Hong Kong, Dollars", "24"),
  "HUF"=> array("Hungary, Forint", "46, 74"),
  "ISK"=> array("Iceland, Kronur", "6b, 72"),
  "INR"=> array("India, Rupees", "20a8"),
  "IDR"=> array("Indonesia, Rupiahs", "52, 70"),
  "IRR"=> array("Iran, Rials", "fdfc"),
  "IMP"=> array("Isle of Man, Pounds", "a3"),
  "ILS"=> array("Israel, New Shekels", "20aa"),
  "JMD"=> array("Jamaica, Dollars", "4a, 24"),
  "JPY"=> array("Japan, Yen", "a5"),
  "JEP"=> array("Jersey, Pounds", "a3"),
  "KZT"=> array("Kazakhstan, Tenge", "43b, 432"),
  "KES"=> array("Kenyan Shilling", "4B, 68, 73"),
  "KWD"=> array("Kuwait, dinar", "4B, 2E, 44, 2E"),
  "KGS"=> array("Kyrgyzstan, Soms", "43b, 432"),
  "LAK"=> array("Laos, Kips", "20ad"),
  "LVL"=> array("Latvia, Lati", "4c, 73"),
  "LBP"=> array("Lebanon, Pounds", "a3"),
  "LRD"=> array("Liberia, Dollars", "24"),
  "LTL"=> array("Lithuania, Litai", "4c, 74"),
  "MKD"=> array("Macedonia, Denars", "434, 435, 43d"),
  "MYR"=> array("Malaysia, Ringgits", "52, 4d"),
  "MUR"=> array("Mauritius, Rupees", "20a8"),
  "MXN"=> array("Mexico, Pesos", "24"),
  "MNT"=> array("Mongolia, Tugriks", "20ae"),
  "MAD"=> array("Morocco, dirhams", "64, 68"),
  "MZN"=> array("Mozambique, Meticais", "4d, 54"),
  "NAD"=> array("Namibia, Dollars", "24"),
  "NPR"=> array("Nepal, Rupees", "20a8"),
  "ANG"=> array("Netherlands Antilles, Guilders (also called Florins)", "192"),
  "NZD"=> array("New Zealand, Dollars", "24"),
  "NIO"=> array("Nicaragua, Cordobas", "43, 24"),
  "NGN"=> array("Nigeria, Nairas", "20a6"),
  "KPW"=> array("North Korea, Won", "20a9"),
  "NOK"=> array("Norway, Krone", "6b, 72"),
  "OMR"=> array("Oman, Rials", "fdfc"),
  "PKR"=> array("Pakistan, Rupees", "20a8"),
  "PAB"=> array("Panama, Balboa", "42, 2f, 2e"),
  "PYG"=> array("Paraguay, Guarani", "47, 73"),
  "PEN"=> array("Peru, Nuevos Soles", "53, 2f, 2e"),
  "PHP"=> array("Philippines, Pesos", "50, 68, 70"),
  "PLN"=> array("Poland, Zlotych", "7a, 142"),
  "QAR"=> array("Qatar, Rials", "fdfc"),
  "RON"=> array("Romania, New Lei", "6c, 65, 69"),
  "RUB"=> array("Russia, Rubles", "440, 443, 431"),
  "SHP"=> array("Saint Helena, Pounds", "a3"),
  "SAR"=> array("Saudi Arabia, Riyals", "fdfc"),
  "RSD"=> array("Serbia, Dinars", "414, 438, 43d, 2e"),
  "SCR"=> array("Seychelles, Rupees", "20a8"),
  "SGD"=> array("Singapore, Dollars", "24"),
  "SBD"=> array("Solomon Islands, Dollars", "24"),
  "SOS"=> array("Somalia, Shillings", "53"),
  "ZAR"=> array("South Africa, Rand", "52"),
  "KRW"=> array("South Korea, Won", "20a9"),
  "LKR"=> array("Sri Lanka, Rupees", "20a8"),
  "SEK"=> array("Sweden, Kronor", "6b, 72"),
  "CHF"=> array("Switzerland, Francs", "43, 48, 46"),
  "SRD"=> array("Suriname, Dollars", "24"),
  "SYP"=> array("Syria, Pounds", "a3"),
  "TWD"=> array("Taiwan, New Dollars", "4e, 54, 24"),
  "THB"=> array("Thailand, Baht", "e3f"),
  "TTD"=> array("Trinidad and Tobago, Dollars", "54, 54, 24"),
  "TRY"=> array("Turkey, Lira", "54, 4c"),
  "TRL"=> array("Turkey, Liras", "20a4"),
  "TVD"=> array("Tuvalu, Dollars", "24"),
  "UAH"=> array("Ukraine, Hryvnia", "20b4"),
  "GBP"=> array("United Kingdom, Pounds", "a3"),
  "USD"=> array("United States of America, Dollars", "24"),
  "UYU"=> array("Uruguay, Pesos", "24, 55"),
  "UZS"=> array("Uzbekistan, Sums", "43b, 432"),
  "VEF"=> array("Venezuela, Bolivares Fuertes", "42, 73"),
  "VND"=> array("Vietnam, Dong", "20ab"),
  "XAF"=> array("BEAC, CFA Francs", "46, 43, 46, 41"),
  "XOF"=> array("BCEAO, CFA Francs", "46, 43, 46, 41"),
  "YER"=> array("Yemen, Rials", "fdfc"),
  "ZWD"=> array("Zimbabwe, Zimbabwe Dollars", "5a, 24")
);

?>