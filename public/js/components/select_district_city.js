
  // Simplified version - works with your existing HTML
const cityOptions = {
    'colombo': ['Colombo 1', 'Colombo 2', 'Colombo 3', 'Colombo 4', 'Colombo 5', 'Colombo 6', 'Colombo 7', 'Colombo 8', 'Colombo 9', 'Colombo 10', 'Colombo 11', 'Colombo 12', 'Colombo 13', 'Colombo 14', 'Colombo 15', 'Dehiwala', 'Mount Lavinia', 'Sri Jayawardenepura Kotte', 'Moratuwa', 'Kolonnawa', 'Kesbewa', 'Kaduwela', 'Maharagama', 'Ratmalana', 'Homagama', 'Athurugiriya', 'Avissawella', 'Piliyandala', 'Boralesgamuwa', 'Nugegoda', 'Kohuwala', 'Wellampitiya', 'Borella', 'Maradana', 'Pettah', 'Slave Island', 'Havelock Town', 'Bambalapitiya', 'Kirulapana', 'Narahenpita', 'Rajagiriya', 'Ethul Kotte', 'Madiwela', 'Kottawa', 'Pannipitiya', 'Thalawathugoda', 'Battaramulla'],
    
    'gampaha': ['Gampaha', 'Negombo', 'Kelaniya', 'Kadawatha', 'Ragama', 'Minuwangoda', 'Mirigama', 'Veyangoda', 'Wattala', 'Ja-Ela', 'Kandana', 'Nittambuwa', 'Divulapitiya', 'Katunayake', 'Seeduwa', 'Biyagama', 'Dompe', 'Mahara', 'Kiribathgoda', 'Delgoda', 'Ganemulla', 'Peliyagoda', 'Attanagalla', 'Udugampola', 'Ekala', 'Henegama', 'Mahabage', 'Pamunugama', 'Kochchikade', 'Wennappuwa (Part)', 'Dalugama', 'Kelani Mulla'],
    
    'kalutara': ['Kalutara', 'Panadura', 'Horana', 'Wadduwa', 'Bandaragama', 'Matugama', 'Ingiriya', 'Beruwala', 'Aluthgama', 'Bentota (Part)', 'Mathugama', 'Agalawatta', 'Palindanuwara', 'Bulathsinhala', 'Millaniya', 'Baduraliya', 'Pitigala', 'Walallawita', 'Dodangoda', 'Madurawela', 'Maggona', 'Katukurunda', 'Payagala', 'Miriswatta', 'Nagoda', 'Kuda Uduwa', 'Welipenna'],
    
    'kandy': ['Kandy', 'Peradeniya', 'Katugastota', 'Gampola', 'Nawalapitiya', 'Kadugannawa', 'Akurana', 'Digana', 'Pilimatalawa', 'Kundasale', 'Galagedara', 'Madawala Bazaar', 'Gurudeniya', 'Teldeniya', 'Ulapane', 'Wattegama', 'Hanguranketha', 'Galaha', 'Hunnasgiriya', 'Ambatenna', 'Deltota', 'Dolapihilla', 'Poojapitiya', 'Panvila', 'Harispattuwa', 'Yatinuwara', 'Udunuwara', 'Udapalatha', 'Medadumbara', 'Minipe', 'Ganga Ihala Korale', 'Pathadumbara', 'Hatharaliyadda'],
    
    'matale': ['Matale', 'Dambulla', 'Sigiriya', 'Ukuwela', 'Rattota', 'Palapathwela', 'Galewela', 'Naula', 'Wilgamuwa', 'Yatawatta', 'Pallepola', 'Rikillagaskada', 'Laggala', 'Aluvihare', 'Handungamuwa', 'Nalanda', 'Ambana', 'Dankanda', 'Elkaduwa', 'Kandy Road', 'Udasgiriya', 'Bambaragaswewa', 'Dewahuwa', 'Kaikawala'],
    
    'nuwara eliya': ['Nuwara Eliya', 'Bandarawela', 'Hatton', 'Talawakelle', 'Welimada', 'Haputale', 'Ragala', 'Nanu Oya', 'Maskeliya', 'Norton Bridge', 'Watawala', 'Dayagama', 'Diyatalawa', 'Pundaluoya', 'Lindula', 'Kotagala', 'Agarapatana', 'Hanguranketha (Part)', 'Ambagamuwa', 'Walapane', 'Kandapola', 'Uva Paranagama', 'Passara (Part)', 'Keppetipola', 'Bopaththalawa', 'Madulla (Part)', 'Beragala', 'Ella', 'Idalgashinna', 'Glenanore', 'Rozella', 'Coonoor'],
    
    'galle': ['Galle', 'Ambalangoda', 'Hikkaduwa', 'Elpitiya', 'Baddegama', 'Udugama', 'Ahangama', 'Imaduwa', 'Karapitiya', 'Bentota (Part)', 'Akmeemana', 'Yakkalamulla', 'Neluwa', 'Nagoda (Galle)', 'Thawalama', 'Niyagama', 'Hiniduma', 'Kosgoda', 'Habarakada', 'Dodanduwa', 'Balapitiya', 'Ratgama', 'Boossa', 'Magalle', 'Kaluwella', 'Mahamodara', 'Richmond Hill', 'Wakwella', 'Unawatuna', 'Koggala', 'Talpe', 'Dalumpitiya', 'Miriswatta (Galle)', 'Kataluwa', 'Gonapinuwala', 'Pitigala (Galle)', 'Thalagaha'],
    
    'matara': ['Matara', 'Weligama', 'Dikwella', 'Hakmana', 'Akuressa', 'Kamburupitiya', 'Devinuwara', 'Mirissa', 'Kekanadurra', 'Malimbada', 'Athuraliya', 'Kotapola', 'Kirinda', 'Pitabeddara', 'Mulatiyana', 'Morawaka', 'Denipitiya', 'Waralla', 'Polhena', 'Medawatta', 'Urugamuwa', 'Handiya', 'Pasgoda', 'Pallekele', 'Nadugala', 'Walgama', 'Midigama', 'Gandara', 'Puhulwella', 'Palatuwa', 'Urubokka', 'Kamburugamuwa', 'Kakunadura', 'Ratmale', 'Horawala'],
    
    'hambantota': ['Hambantota', 'Tangalle', 'Tissamaharama', 'Beliatta', 'Ambalantota', 'Walasmulla', 'Weeraketiya', 'Angunakolapelessa', 'Bundala', 'Katuwana', 'Lunugamwehera', 'Okewela', 'Sooriyawewa', 'Middeniya', 'Ranna', 'Nonagama', 'Goda Koggalla', 'Gonagamuwa', 'Hungama', 'Kirinda (Hambantota)', 'Pallamaduwa', 'Mawella', 'Rekawa', 'Galamuna', 'Ussangoda', 'Getamanna', 'Tihagoda', 'Weligatta', 'Kadurugasmankada', 'Udamalala'],
    
    'jaffna': ['Jaffna', 'Chavakachcheri', 'Nallur', 'Point Pedro', 'Karainagar', 'Udupiddy', 'Chunnakam', 'Maruthankerny', 'Tellippalai', 'Kopay', 'Sandilipay', 'Neervely', 'Kondavil', 'Kodikamam', 'Mallakam', 'Kankesanthurai', 'Maviddapuram', 'Sangiliyan Thoppu', 'Navatkuli', 'Araly', 'Manipay', 'Urumpirai', 'Atchuvely', 'Puthur', 'Inuvil', 'Thirunelvely', 'Kaithady', 'Mirusuvil', 'Puttur', 'Tholpuram', 'Valvettithurai', 'Sithankerny', 'Myliddy'],
    
    'kilinochchi': ['Kilinochchi', 'Pallai', 'Kandavalai', 'Paranthan', 'Iranaimadu', 'Akathiyanoor', 'Poonakary', 'Mulliyan', 'Elephant Pass', 'Mankulam (Part)', 'Kanagarayankulam', 'Uruthirapuram', 'Sivapuram', 'Keranchi', 'Chempankunru', 'Mamadhu', 'Tharmapuram', 'Palai', 'Karachchi', 'Pachchilaipalli'],
    
    'mannar': ['Mannar', 'Nanattan', 'Madhu', 'Musalai', 'Erukkalampiddy', 'Pesalai', 'Talaimannar', 'Murungan', 'Vankalai', 'Adampan', 'Uyilankulam', 'Tharapuram', 'Puthukkudiyiruppu (Part)', 'Iluppaikkadavai', 'Vellankulam', 'Periyamadhu', 'Alankulam', 'Parappankandal', 'Thiruketheeswaram', 'Silavathurai', 'Pallimunai', 'Erukkulampiddy'],
    
    'vavuniya': ['Vavuniya', 'Nedunkerny', 'Cheddikulam', 'Omanthai', 'Poovarasankulam', 'Vavuniya South', 'Vavuniya North', 'Vavuniya Town', 'Pampaimadu', 'Puliyankulam', 'Maraiyadithakulam', 'Kannaddi', 'Nelukkulam', 'Akkarayankulam', 'Sivapuram', 'Nainamadhu', 'Pandarikulam', 'Kovilkulam', 'Mamaduwa', 'Palamoddai', 'Thandikulam', 'Mulliyawalai', 'Periya Ulukkulam', 'Maruthamadu'],
    
    'mullaitivu': ['Mullaitivu', 'Oddusuddan', 'Puthukudiyiruppu', 'Alampil', 'Mankulam (Part)', 'Mulliyawalai (Part)', 'Kumulamunai', 'Visvamadhu', 'Mallavi', 'Thunukkai', 'Udayarkaddu', 'Thanniyutthu', 'Kokkilai', 'Karunaadampan', 'Vellankulam (Part)', 'Mullaitivu Town', 'Puthumathalan', 'Chundikulam', 'Kokuthoduvai', 'Mullivaikkal', 'Valayanmadam', 'Alampil East', 'Alampil West'],
    
    'batticaloa': ['Batticaloa', 'Kalkudah', 'Valaichchenai', 'Eravur', 'Kattankudy', 'Chenkalady', 'Vakarai', 'Kiran', 'Paddiruppu', 'Mankerni', 'Vellavely', 'Arayampathy', 'Mamangam', 'Palameenmadu', 'Vanathavillu', 'Chengkaladi', 'Punanai', 'Vantharumoolai', 'Koddamunai', 'Kaluwanchikudi', 'Kurukkalmadam', 'Puliyantivu', 'Mylambaveli', 'Oddamavadi', 'Mavadivembu', 'Pankudavely', 'Mandur', 'Miravodai', 'Navatkadu', 'Karadiyanaru', 'Koralai', 'Mullipothana'],
    
    'ampara': ['Ampara', 'Kalmunai', 'Sainthamaruthu', 'Akkaraipattu', 'Pottuvil', 'Uhana', 'Mahaoya', 'Damana', 'Dehiattakandiya', 'Sammanthurai', 'Navithanveli', 'Addalaichenai', 'Alayadi Vembu', 'Nintavur', 'Karaitivu (Ampara)', 'Thirukkovil', 'Lahugala', 'Hingurana', 'Gonagolla', 'Maha Oya', 'Padiyathalawa', 'Namal Oya', 'Damana Town', 'Irakkamam', 'Karandeniya (Ampara)', 'Mawadichchenai', 'Oluvil', 'Komari', 'Panama', 'Palamunai'],
    
    'trincomalee': ['Trincomalee', 'Kinniya', 'Muttur', 'Kantalai', 'Gomarankadawala', 'Seruvila', 'Thampalagamam', 'Nilaveli', 'Kuchchaveli', 'Morawewa', 'Padavisripura', 'Kappalthurai', 'Sampur', 'Kiliveddy', 'Vellamanal', 'Kaddaiparichchan', 'Kanniya', 'China Bay', 'Eachchilampattai', 'Kanniya Hot Springs', 'Kumburupiddy', 'Muthur Nagar', 'Pankulam', 'Pathaviyar', 'Pulmoddai', 'Rottawewa', 'Salli', 'Tiriyayi', 'Vaharai (Part)', 'Wanela'],
    
    'kurunegala': ['Kurunegala', 'Kuliyapitiya', 'Narammala', 'Pannala', 'Wariyapola', 'Giriulla', 'Mawathagama', 'Polgahawela', 'Alawwa', 'Bingiriya', 'Nikaweratiya', 'Maho', 'Galgamuwa', 'Ganewatta', 'Hettipola', 'Kobeigane', 'Kotawehera', 'Ibbagamuwa', 'Rasnayakapura', 'Udubaddawa', 'Ambanpola', 'Bamunakotuwa', 'Dambadeniya', 'Giribawa', 'Mallawapitiya', 'Panduwasnuwara', 'Rideegama', 'Weerambugedara', 'Yapahuwa', 'Hiriyala', 'Katupota', 'Melsiripura', 'Pothuhera', 'Bogamulla', 'Dankotuwa (Part)'],
    
    'puttalam': ['Puttalam', 'Chilaw', 'Wennappuwa', 'Dankotuwa (Part)', 'Marawila', 'Nattandiya', 'Anamaduwa', 'Kalpitiya', 'Pallama', 'Arachchikattuwa', 'Diamond Hill', 'Eluwankulama', 'Karuwalagaswewa', 'Mundalama', 'Nawagattegama', 'Pallivasalturai', 'Sembukattiya', 'Serakkuliya', 'Thoduwawa', 'Vanathawilluwa', 'Waikkal', 'Wilpotha', 'Ihala Kottaramulla', 'Kakkapalliya', 'Karagoda', 'Karativponparappi', 'Lunuwila (Part)', 'Madampe', 'Madurankuliya', 'Mahakumbukkadawala', 'Mampuri', 'Mudalakkuliya', 'Nallachchiya', 'Nuraicholai', 'Palavi', 'Saliyawewa', 'Tabbowa', 'Thambapanni', 'Udappuwa'],
    
    'anuradhapura': ['Anuradhapura', 'Medawachchiya', 'Talawa', 'Kekirawa', 'Mihintale', 'Nochchiyagama', 'Kahatagasdigiliya', 'Galnewa', 'Rambewa', 'Palugaswewa', 'Rajanganaya', 'Thalawa', 'Nachchaduwa', 'Thirappane', 'Ipalogama', 'Mahawilachchiya', 'Nuwaragam Palatha', 'Kebithigollewa', 'Padaviya', 'Horowpathana', 'Medagama', 'Galenbindunuwewa', 'Maha Vilachchiya', 'Palagala', 'Rambadagalla', 'Sippukulama', 'Tantirimale', 'Vijithapura', 'Wahalkada', 'Wilachchiya', 'Pabahinna', 'Pubbogama', 'Galkadawala', 'Galkulama', 'Ganewalpola', 'Kadawala', 'Kapugolla', 'Mulkiriyawa', 'Nelumwewa', 'Pandulagama', 'Parakumpura', 'Seeppukulama'],
    
    'polonnaruwa': ['Polonnaruwa', 'Kaduruwela', 'Hingurakgoda', 'Medirigiriya', 'Lankapura', 'Welikanda', 'Dimbulagala', 'Elahera', 'Thamankaduwa', 'Lakshauyana', 'Manampitiya', 'Aralaganwila', 'Bakamuna', 'Diyasenpura', 'Galamuna', 'Giritale', 'Jayanthipura', 'Kaudulla', 'Medirigiriya Town', 'Minneriya', 'Pansal Godella', 'Pimburattewa', 'Sevanapitiya', 'Sungavila', 'Talpotha', 'Thalakolawewa', 'Unagalavehera', 'Welikanda Town', 'Yodaganawa'],
    
    'badulla': ['Badulla', 'Bandarawela', 'Hali Ela', 'Mahiyanganaya', 'Passara', 'Welimada', 'Ella', 'Haputale', 'Diyatalawa', 'Kandaketiya', 'Lunugala', 'Meegahakivula', 'Rideemaliyadda', 'Soranatota', 'Uva Paranagama (Part)', 'Wiyaluwa', 'Bibile (Part)', 'Girandurukotte', 'Haldummulla', 'Kahataruppa', 'Koslanda', 'Madulsima', 'Namunukula', 'Pitamaruwa', 'Spring Valley', 'Uva Uduwara', 'Welimada Town', 'Aluketiyawa', 'Bogahakumbura', 'Dambagalla', 'Demodara', 'Gurupokuna', 'Hettimulla', 'Hunnasgiriya (Part)', 'Kandegedara', 'Keppetipola (Part)', 'Ketawatta', 'Kuruwitenna', 'Mirahawatta', 'Namunukula Town', 'Nelumgama', 'Pahalagama', 'Pattiyagedara', 'Rabukkana', 'Randenigala', 'Rilpola', 'Sirimalgoda', 'Taldena', 'Uraniya'],
    
    'monaragala': ['Monaragala', 'Wellawaya', 'Bibile', 'Buttala', 'Kataragama', 'Siyambalanduwa', 'Thanamalwila', 'Madulla', 'Badalkumbura', 'Kotiyagala', 'Medagana', 'Sevanagala', 'Suriyakanda', 'Tanamalwila', 'Wilaoya', 'Athimale', 'Bakinigahawela', 'Dambagalla (Part)', 'Ethimalewewa', 'Hulandawa', 'Kandaudapanguwa', 'Kotagama', 'Kumbukkana', 'Lunugala (Part)', 'Madawala Ulpotha', 'Malliyadda', 'Miyanakandura', 'Nakkala', 'Obbegoda', 'Okkampitiya', 'Pangura', 'Pitakumbura', 'Ruwalwela', 'Sellakataragama', 'Siyambalagune', 'Uva Karandagolla', 'Weherayaya', 'Wila Oya', 'Yudaganawa'],
    
    'ratnapura': ['Ratnapura', 'Balangoda', 'Embilipitiya', 'Pelmadulla', 'Eheliyagoda', 'Kuruwita', 'Godakawela', 'Nivithigala', 'Kahawatta', 'Ayagama', 'Kalawana', 'Kolonna', 'Opanayaka', 'Weligepola', 'Elapatha', 'Imbulpe', 'Kiriella', 'Dehiowita (Part)', 'Rakwana', 'Sri Palabaddala', 'Dela', 'Dodampe', 'Gillimale', 'Hidellana', 'Kaltota', 'Mulgama', 'Panamura', 'Pinnawala', 'Pothupitiya', 'Ratnapura Town', 'Udaha Hawupe', 'Waleboda', 'Watura', 'Weddagala'],
    
    'kegalle': ['Kegalle', 'Mawanella', 'Rambukkana', 'Warakapola', 'Dehiowita', 'Galigamuwa', 'Yatiyantota', 'Aranayaka', 'Ruwanwella', 'Deraniyagala', 'Kitulgala', 'Bulathkohupitiya', 'Galigamuwa Town', 'Kegalle Town', 'Mawanella Town', 'Rambukkana Town', 'Warakapola Town', 'Alawathugoda', 'Ambanpola (Part)', 'Amithirigala', 'Atale', 'Beligammana', 'Bopitiya', 'Dedugala', 'Dewalegama', 'Gantuna', 'Hettimulla (Kegalle)', 'Hingula', 'Hiriwadunna', 'Kadugannawa (Part)', 'Kahathuduwa', 'Kithulhitta', 'Kotiyakumbura', 'Kumbukwela', 'Madampe (Part)', 'Mahapallegama', 'Malalpola', 'Malgammana', 'Mapalagama', 'Morontota', 'Nelundeniya', 'Pannila', 'Pattampitiya', 'Pilawala', 'Pothukoladeniya', 'Seethawaka', 'Thalduwa', 'Ussapitiya', 'Waharaka', 'Weeoya', 'Wegalla']
};

document.addEventListener('DOMContentLoaded', function() {
    const districtInput = document.getElementById('district');
    const cityField = document.getElementById('city-field');
    const cityInput = document.getElementById('city');
    
    // Convert district input to datalist
    createDataList(districtInput, Object.keys(cityOptions));
    
    // Listen for district input changes
    districtInput.addEventListener('change', function() {
        const district = this.value.toLowerCase();
        
        if (cityOptions[district]) {
            // Show city field
            cityField.style.display = 'block';
            
            // Create datalist for cities
            createDataList(cityInput, cityOptions[district]);
        } else {
            // Hide city field
            cityField.style.display = 'none';
            cityInput.value = '';
        }
    });
    
    // Trigger change event if district has value on load
    if (districtInput.value) {
        districtInput.dispatchEvent(new Event('change'));
    }
});

function createDataList(inputElement, options) {
    const listId = inputElement.id + '-list';
    
    // Remove existing datalist
    const existingList = document.getElementById(listId);
    if (existingList) {
        existingList.remove();
    }
    
    // Create new datalist
    const dataList = document.createElement('datalist');
    dataList.id = listId;
    
    // Add options
    options.forEach(option => {
        const listOption = document.createElement('option');
        listOption.value = option.charAt(0).toUpperCase() + option.slice(1);
        dataList.appendChild(listOption);
    });
    
    // Append to body and link to input
    document.body.appendChild(dataList);
    inputElement.setAttribute('list', listId);
}
