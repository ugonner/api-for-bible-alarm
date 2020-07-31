<?php
//ADD prayertypeid INT(3) to prayer table
include $_SERVER['DOCUMENT_ROOT'].'/api/includes/db/connect.php';
//create prayergroup prayer user;


$sql3='CREATE TABLE prayergroupprayeraction(
	id INT(6) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	prayeractiontext TEXT,
	userid INT(6),
	ownerid INT(6),
	prayerid INT(6),
	dateofpublication VARCHAR(255),
	actiontypeid INT(6),
	UNIQUE(userid,prayerid),
	INDEX prayeractionuserid(userid),
	INDEX prayeractionprayerid(prayerid),
	INDEX prayeractionownerid(ownerid)
	)DEFAULT CHARSET UTF8 ENGINE INNODB';

if(!mysqli_query($link,$sql3)){
    $error = mysqli_error($link).' unable create prayergroupprayeraction';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}

//create prayergroup prayer user;

$sql3='CREATE TABLE prayer(
	id INT(6) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	prayertext TEXT,
	userid INT(6),
	prayergroupid INT(6),
	noofprayeractions INT DEFAULT 0,
	dateofpublication VARCHAR(255),
	prayertypeid INT(3),
	answered ENUM("Y", "N"),
	public ENUM("Y", "N"),
	INDEX prayeruserid(userid),
	INDEX prayerprayergroupid(prayergroupid),
	INDEX prayerpublic(public)
	)DEFAULT CHARSET UTF8 ENGINE INNODB';

if(!mysqli_query($link,$sql3)){
    $error = mysqli_error($link).' unable create prayergroupprayer';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}




//create pastor user;

$sql3='CREATE TABLE pastoruser(
	userid INT(6),
	pastorid INT(6),
	PRIMARY KEY(pastorid,userid)
	)DEFAULT CHARSET UTF8 ENGINE INNODB';

if(!mysqli_query($link,$sql3)){
    $error = mysqli_error($link).' unable create pastoruser';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}


//create PASTOR;
$sql3='CREATE TABLE pastor(
	id INT(6) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	userid INT(6),
	ministryid INT(6),
	UNIQUE(userid)
	)DEFAULT CHARSET UTF8 ENGINE INNODB';

if(!mysqli_query($link,$sql3)){
    $error = mysqli_error($link).' unable create pastor';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}


//CREATE MINISTRY;

//create PASTOR;
$sql3='CREATE TABLE ministry(
	id INT(6) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(255),
	address TEXT,
	profilepic VARCHAR(255),
	overseerid INT(6),
	about TEXT
	)DEFAULT CHARSET UTF8 ENGINE INNODB';

if(!mysqli_query($link,$sql3)){
    $error = mysqli_error($link).' unable create MINISTRY';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}


//create pastor alarm;
$sql3='CREATE TABLE pastoralarm(
	id INT(6) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	pastorid INT(3),
	alarmtext TEXT,
	alarmtypeid INT(2),
	title VARCHAR(255),
	setdate VARCHAR(255),
	INDEX alarmpastorid (pastorid),
    index alarmsetdate (setdate)
	)DEFAULT CHARSET UTF8 ENGINE INNODB';

if(!mysqli_query($link,$sql3)){
    $error = mysqli_error($link).' unable create pastorALARM';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}

//create group alarm;
$sql3='CREATE TABLE groupalarm(
	id INT(6) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	groupid INT(3),
	alarmtext TEXT,
	alarmtypeid INT(2),
	title VARCHAR(255),
	setdate VARCHAR(255),
	INDEX alarmgroupid (groupid),
    index alarmsetdate (setdate)
	)DEFAULT CHARSET UTF8 ENGINE INNODB';

if(!mysqli_query($link,$sql3)){
    $error = mysqli_error($link).' unable create groupALARM';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}

//create group alarm;
$sql3='CREATE TABLE groupuser(
	userid INT(6),
	groupid INT(5),
	PRIMARY KEY(groupid,userid)
	)DEFAULT CHARSET UTF8 ENGINE INNODB';

if(!mysqli_query($link,$sql3)){
    $error = mysqli_error($link).' unable create groupadmin';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}


//create group;
$sql3='CREATE TABLE groups(
	id INT(6) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(255),
	adminid INT(6)
	)DEFAULT CHARSET UTF8 ENGINE INNODB';

if(!mysqli_query($link,$sql3)){
    $error = mysqli_error($link).' unable create GROUP';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}


//create user table;
$sql3='CREATE TABLE user(
	id INT(6) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	firstname VARCHAR(200),
	surname VARCHAR(200),
	email VARCHAR(255) UNIQUE,
	password CHAR(32),
    facebookid VARCHAR(255),
    mobile VARCHAR(32),
	gender ENUM("M", "F"),
	dateofbirth DATE,
	dateofregistration DATE,
	about TEXT,
	profilepic VARCHAR(255),
	stateid INT(3),
	LGAid INT(3),
	smscount INT(3),
	roleid INT(3),
	functionid INT(3),
	pushid VARCHAR(255),
	rolenote VARCHAR(255),
	public ENUM("Y", "N"),
	lastactivity VARCHAR(255),
	INDEX userroleid (roleid),
    index Facebookid (facebookid),
    INDEX userLGAid (LGAid),
    INDEX userstateid (stateid),
    INDEX useremailpsaaword (email,password)
	)DEFAULT CHARSET UTF8 ENGINE INNODB';

if(!mysqli_query($link,$sql3)){
    $error = mysqli_error($link).' unable create Person';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}

$sql2='CREATE TABLE role(
	id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(32)
	)DEFAULT CHARSET UTF8';

if(!mysqli_query($link,$sql2)){
    $error = mysqli_error($link).' unable create role';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}

$sql2='INSERT INTO role (name) VALUES
("Marketer"),("Normal User"),("Supplier"),("Agro-Manager"),("Agricultural Economist"),
("Agro Professional"),("Producer Farmer")';

if(!mysqli_query($link,$sql2)){
    $error = mysqli_error($link).' unable populate role';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}


$sql5='CREATE TABLE state(
	id INT(6) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(255)
	)DEFAULT CHARSET UTF8';

if(!mysqli_query($link,$sql5)){
    $error = mysqli_error($link).' unable create State';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}

$sql5='CREATE TABLE LGA(
	id INT(6) NOT NULL PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(255),
	stateid INT(3),
	INDEX LGAstateid (stateid)
	)DEFAULT CHARSET UTF8 ENGINE INNODB';

if(!mysqli_query($link,$sql5)){
    $error = mysqli_error($link).' unable create LGA';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}



//create wardadmin users;
$sql4='CREATE TABLE blockeduser(
	userid INT(3) PRIMARY KEY
	)DEFAULT CHARSET UTF8';

if(!mysqli_query($link,$sql4)){
    $error = mysqli_error($link).' unable create block user';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}
//end of wardadmin user;


//create wardadmin users;
$sql4='CREATE TABLE superadmin(
	userid INT(3) PRIMARY KEY
	)DEFAULT CHARSET UTF8';

if(!mysqli_query($link,$sql4)){
    $error = mysqli_error($link).' unable create superadmin';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}
//end of superadmin user;




$sql2='INSERT INTO state (name) VALUES
    ("FCT"),("
    Abia"),("
    Adamawa"),("
    Anambra"),("
    Akwa Ibom"),("
    Bauchi"),("
    Bayelsa"),("
    Benue"),("
    Borno"),("
    Cross River"),("
    Delta"),("
    Ebonyi"),("
    Enugu"),("
    Edo"),("
    Ekiti"),("
    Gombe"),("
    Imo"),("
    Jigawa"),("
    Kaduna"),("
    Kano"),("
    Katsina"),("
    Kebbi"),("
    Kogi"),("
    Kwara"),("
    Lagos"),("
    Nasarawa"),("
    Niger"),("
    Ogun"),("
    Ondo"),("
    Osun"),("
    Oyo"),("
    Plateau"),("
    Rivers"),("
    Sokoto"),("
    Taraba"),("
    Yobe"),("
    Zamfara"),("
    NA")';

if(!mysqli_query($link,$sql2)){
    $error = mysqli_error($link).' unable create role';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}


$sql2='INSERT INTO LGA (Name,stateid) VALUES
("Gwagwalada",1),(
 "Kuje",1),(
 "Abaji",1),(
" Abuja Municipal",1),(
 "Bwari",1),(
 "Kwali",1),(
"Aba North",2),(
"Aba South",2),(
 "Arochukwu",2),(
 "Bende",2),(
 "Ikwuano",2),(
 "Isiala-Ngwa North",2),(
 "Isiala-Ngwa South",2),(
 "Isuikwato",2),(
 "Obi Nwa",2),(
 "Ohafia",2),(
 "Osisioma",2),(
 "Ngwa",2),(
 "Ugwunagbo",2),(
 "Ukwa East",2),(
 "Ukwa West",2),(
 "Umuahia North",2),(
 "Umuahia South",2),(
 "Umu-Neochi",2),(
"Demsa",3),(
"Fufore",3),(
"Ganaye",3),(
"Gireri",3),(
"Gombi",3),(
"Guyuk",3),(
"Hong",3),(
"Jada",3),(
"Lamurde",3),(
"Madagali",3),(
"Maiha" ,3),(
"Mayo-Belwa",3),(
"Michika",3),(
"Mubi North",3),(
"Mubi South",3),(
"Numan",3),(
"Shelleng",3),(
"Song",3),(
"Toungo",3),(
"Yola North",3),(
"Yola South",3),(
"Abak",4),(
"Eastern Obolo",4),(
"Eket",4),(
"Esit Eket",4),(
"Essien Udim",4),(
"Etim Ekpo",4),(
"Etinan",4),(
"Ibeno",4),(
"Ibesikpo-Asutan",4),(
"Ibiono-Ibom",4),(
"Ika",4),(
"Ikono",4),(
"Ikot-Abasi",4),(
"Ikot-Ekpene",4),(
"Ini",4),(
"Itu",4),(
"Mbo",4),(
"Mkpat-Enin",4),(
"Nsit-Atai",4),(
"Nsit-Ibom",4),(
"Nsit-Ubium",4),(
"Obot-Akara",4),(
"Okobo",4),(
"Onna",4),(
"Oron" ,4),("Oruk-Anam",4)
,("Udung-Uko",4)
,("Ukanafun",4)
,("Uruan",4
),("Urue-Offong/Oruko",4),
("Uyo",4),
("Aguata",5),("
Anambra East",5),("
Anambra West",5),("
Anaocha",5),("
Awka North",5),("
Awka South",5),("
Ayamelum",5),("
Dunukofia",5),("
Ekwusigo",5),("
Idemili North",5),("
Idemili south",5),("
Ihiala",5),("
Njikoka",5),("
Nnewi North",5),("
Nnewi South",5),("
Ogbaru",5),("
Onitsha North",5),("
Onitsha South",5),("
Orumba North",5),("
Orumba South",5),("
Oyi ",5),("
Alkaleri",6),("
Bauchi ",6),("
Bogoro",6),("
Damban",6),("
Darazo",6),("
Dass",6),("
Ganjuwa",6),("
Giade",6),("
Itas/Gadau",6),("
Jama-are",6),("
Katagum",6),("
Kirfi",6),("
Misau",6),("
Ningi",6),("
Shira",6),("
Tafawa-Balewa",6),("
Toro",6),("
Warji",6),("
Zaki ",6),("

Brass",7),("
Ekeremor",7),("
Kolokuma/Opokuma",7),("
Nembe",7),("
Ogbia",7),("
Sagbama",7),("
Southern Jaw",7),("
Yenegoa ",7),("

Ado",8),("
Agatu",8),("
Apa",8),("
Buruku",8),("
Gboko",8),("
Guma",8),("
Gwer East",8),("
Gwer West",8),("
Katsina-Ala",8),("
Konshisha",8),("
Kwande",8),("
Logo",8),("
Makurdi",8),("
Obi",8),("
Ogbadibo",8),("
Oju",8),("
Okpokwu",8),("
Ohimini",8),("
Oturkpo",8),("
Tarka",8),("
Ukum",8),("
Ushongo",8),("
Vandeikya ",8),("

Abadam",9),("
Askira/Uba" , 9 ),("
Bama" , 9 ),("
Bayo" , 9 ),("
Biu" , 9 ),("
Chibok" , 9 ),("
Damboa" , 9 ),("
Dikwa" , 9 ),("
Gubio" , 9 ),("
Guzamala" , 9 ),("
Gwoza" , 9 ),("
Hawul" , 9 ),("
Jere" , 9 ),("
Kaga" , 9 ),("
Kala/Balge" , 9 ),("
Konduga" , 9 ),("
Kukawa" , 9 ),("
Kwaya Kusar" , 9 ),("
Mafa" , 9 ),("
Magumeri" , 9 ),("
Maiduguri" , 9 ),("
Marte" , 9 ),("
Mobbar" , 9 ),("
Monguno" , 9 ),("
Ngala" , 9 ),("
Nganzai" , 9 ),("
Shani " , 9 ),("

Akpabuyo " , 10 ),("
Odukpani" , 10 ),("
Akamkpa" , 10 ),("
Biase" , 10 ),("
Abi" , 10 ),("
Ikom" , 10 ),("
Yarkur" , 10 ),("
Odubra" , 10 ),("
Boki" , 10 ),("
Ogoja" , 10 ),("
Yala" , 10 ),("
Obanliku" , 10 ),("
Obudu" , 10 ),("
Calabar South" , 10 ),("
Etung" , 10 ),("
Bekwara" , 10 ),("
Bakassi" , 10 ),("
Calabar Municipal " , 10 ),("

Oshimili" , 11 ),("
Aniocha" , 11 ),("
Aniocha South" , 11 ),("
Ika South" , 11 ),("
Ika North-East" , 11 ),("
Ndokwa West" , 11 ),("
Ndokwa East" , 11 ),("
Isoko south" , 11 ),("
Isoko North" , 11 ),("
Bomadi" , 11 ),("
Burutu" , 11 ),("
Ughelli South" , 11 ),("
Ughelli North" , 11 ),("
Ethiope West" , 11 ),("
Ethiope East" , 11 ),("
Sapele" , 11 ),("
Okpe" , 11 ),("
Warri North" , 11 ),("
Warri South" , 11 ),("
Uvwie" , 11 ),("
Udu" , 11 ),("
Warri Central" , 11 ),("
Ukwani" , 11 ),("
Oshimili North" , 11 ),("
Patani" , 11 ),("

Afikpo South" , 12 ),("
Afikpo North" , 12 ),("
Onicha" , 12 ),("
Ohaozara" , 12 ),("
Abakaliki" , 12 ),("
Ishielu" , 12 ),("
lkwo" , 12 ),("
Ezza" , 12 ),("
Ezza South" , 12 ),("
Ohaukwu" , 12 ),("
Ebonyi" , 12 ),("
Ivo " , 12 ),("

Esan North-East" , 13 ),("
Esan Central" , 13 ),("
Esan West" , 13 ),("
Egor" , 13 ),("
Ukpoba" , 13 ),("
Etsako Central" , 13 ),("
Igueben" , 13 ),("
Oredo" , 13 ),("
Ovia SouthWest" , 13 ),("
Ovia South-East" , 13 ),("
Orhionwon" , 13 ),("
Uhunmwonde" , 13 ),("
Etsako East " , 13 ),("
Esan South-East " , 13 ),("

Ado" , 14 ),("
Ekiti-East" , 14 ),("
Ekiti-West" , 14 ),("
Emure/Ise/Orun" , 14 ),("
Ekiti South-West" , 14 ),("
Ikare" , 14 ),("
Irepodun" , 14 ),("
Ijero, " , 14 ),("
Ido/Osi" , 14 ),("
Oye" , 14 ),("
Ikole" , 14 ),("
Moba" , 14 ),("
Gbonyin" , 14 ),("
Efon" , 14 ),("
Ise/Orun " , 14 ),("
Ilejemeje." , 14 ),("

Enugu South" , 15 ),("
Igbo-Eze South" , 15 ),("
Enugu North" , 15 ),("
Nkanu" , 15 ),("
Udi Agwu" , 15 ),("
Oji-River" , 15 ),("
Ezeagu" , 15 ),("
IgboEze North" , 15 ),("
Isi-Uzo" , 15 ),("
Nsukka" , 15 ),("
Igbo-Ekiti" , 15 ),("
Uzo-Uwani" , 15 ),("
Enugu Eas" , 15 ),("
Aninri" , 15 ),("
Nkanu East" , 15 ),("
Udenu. " , 15 ),("

Akko" , 16 ),("
Balanga" , 16 ),("
Billiri" , 16 ),("
Dukku" , 16 ),("
Kaltungo" , 16 ),("
Kwami" , 16 ),("
Shomgom" , 16 ),("
Funakaye" , 16 ),("
Gombe" , 16 ),("
Nafada/Bajoga " , 16 ),("
Yamaltu/Delta. " , 16 ),("

Aboh-Mbaise" , 17 ),("
Ahiazu-Mbaise" , 17 ),("
Ehime-Mbano" , 17 ),("
Ezinihitte" , 17 ),("
Ideato North" , 17 ),("
Ideato South" , 17 ),("
Ihitte/Uboma" , 17 ),("
Ikeduru" , 17 ),("
Isiala Mbano" , 17 ),("
Isu" , 17 ),("
Mbaitoli" , 17 ),("
Mbaitoli" , 17 ),("
Ngor-Okpala" , 17 ),("
Njaba" , 17 ),("
Nwangele" , 17 ),("
Nkwerre" , 17 ),("
Obowo" , 17 ),("
Oguta" , 17 ),("
Ohaji/Egbema" , 17 ),("
Okigwe" , 17 ),("
Orlu" , 17 ),("
Orsu" , 17 ),("
Oru East" , 17 ),("
Oru West" , 17 ),("
Owerri-Municipal" , 17 ),("
Owerri North" , 17 ),("
Owerri West " , 17 ),("

Auyo" , 18 ),("
Babura" , 18 ),("
Birni Kudu" , 18 ),("
Biriniwa" , 18 ),("
Buji" , 18 ),("
Dutse" , 18 ),("
Gagarawa" , 18 ),("
Garki" , 18 ),("
Gumel" , 18 ),("
Guri" , 18 ),("
Gwaram" , 18 ),("
Gwiwa" , 18 ),("
Hadejia" , 18 ),("
Jahun" , 18 ),("
Kafin Hausa" , 18 ),("
Kaugama Kazaure" , 18 ),("
Kiri Kasamma" , 18 ),("
Kiyawa" , 18 ),("
Maigatari" , 18 ),("
Malam Madori" , 18 ),("
Miga" , 18 ),("
Ringim" , 18 ),("
Roni" , 18 ),("
Sule-Tankarkar" , 18 ),("
Taura " , 18 ),("
Yankwashi " , 18 ),("

Birni-Gwari" , 19 ),("
Chikun" , 19 ),("
Giwa" , 19 ),("
Igabi" , 19 ),("
Ikara" , 19 ),("
jaba" , 19 ),("
Jema-a" , 19 ),("
Kachia" , 19 ),("
Kaduna North" , 19 ),("
Kaduna South" , 19 ),("
Kagarko" , 19 ),("
Kajuru" , 19 ),("
Kaura" , 19 ),("
Kauru" , 19 ),("
Kubau" , 19 ),("
Kudan" , 19 ),("
Lere" , 19 ),("
Makarfi" , 19 ),("
Sabon-Gari" , 19 ),("
Sanga" , 19 ),("
Soba" , 19 ),("
Zango-Kataf" , 19 ),("
Zaria " , 19 ),("

Ajingi" , 20 ),("
Albasu" , 20 ),("" , 20 ),("
Bagwai" , 20 ),("
Bebeji" , 20 ),("
Bichi" , 20 ),("
Bunkure" , 20 ),("
Dala" , 20 ),("
Dambatta" , 20 ),("
Dawakin Kudu" , 20 ),("
Dawakin Tofa" , 20 ),("
Doguwa" , 20 ),("
Fagge" , 20 ),("
Gabasawa" , 20 ),("
Garko" , 20 ),("
Garum" , 20 ),("
Mallam" , 20 ),("
Gaya" , 20 ),("
Gezawa" , 20 ),("
Gwale" , 20 ),("
Gwarzo" , 20 ),("
Kabo" , 20 ),("
Kano Municipal" , 20 ),("
Karaye" , 20 ),("
Kibiya" , 20 ),("
Kiruv
kumbo" , 20 ),("tso
Kunchi" , 20 ),("
Kura" , 20 ),("
Madobi" , 20 ),("
Makoda" , 20 ),("
Minjibir" , 20 ),("
Nasarawa" , 20 ),("
Rano" , 20 ),("
Rimin Gado" , 20 ),("
Rogo" , 20 ),("
Shanono" , 20 ),("
Sumaila" , 20 ),("
Takali" , 20 ),("
Tarauni" , 20 ),("
Tofa" , 20 ),("
Tsanyawa" , 20 ),("
Tudun Wada" , 20 ),("
Ungogo" , 20 ),("
Warawa" , 20 ),("
Wudil" , 20 ),("

Bakori" , 21 ),("
Batagarawa" , 21 ),("
Batsari" , 21 ),("
Baure" , 21 ),("
Bindawa" , 21 ),("
Charanchi" , 21 ),("
Dandume" , 21 ),("
Danja" , 21 ),("
Dan Musa" , 21 ),("
Daura" , 21 ),("
Dutsi" , 21 ),("
Dutsin-Ma" , 21 ),("
Faskari" , 21 ),("
Funtua" , 21 ),("
Ingawa" , 21 ),("
Jibia" , 21 ),("
Kafur" , 21 ),("
Kaita" , 21 ),("
Kankara" , 21 ),("
Kankia" , 21 ),("
Katsina" , 21 ),("
Kurfi" , 21 ),("
Kusada" , 21 ),("
Mai-Adua" , 21 ),("
Malumfashi" , 21 ),("
Mani" , 21 ),("
Mashi" , 21 ),("
Matazuu" , 21 ),("
Musawa" , 21 ),("
Rimi" , 21 ),("
Sabuwa" , 21 ),("
Safana" , 21 ),("
Sandamu" , 21 ),("
Zango " , 21 ),("

Aleiro" , 22 ),("
Arewa-Dandi" , 22 ),("
Argungu" , 22 ),("
Augie" , 22 ),("
Bagudo" , 22 ),("
Birnin Kebbi" , 22 ),("
Bunza" , 22 ),("
Dandi " , 22 ),("
Fakai" , 22 ),("
Gwandu" , 22 ),("
Jega" , 22 ),("
Kalgo " , 22 ),("
Koko/Besse" , 22 ),("
Maiyama" , 22 ),("
Ngaski" , 22 ),("
Sakaba" , 22 ),("
Shanga" , 22 ),("
Suru" , 22 ),("
Wasagu/Danko" , 22 ),("
Yauri" , 22 ),("
Zuru " , 22 ),("

Adavi" , 23 ),("
Ajaokuta" , 23 ),("
Ankpa" , 23 ),("
Bassa" , 23 ),("
Dekina" , 23 ),("
Ibaji" , 23 ),("
Idah" , 23 ),("
Igalamela-Odolu" , 23 ),("
Ijumu" , 23 ),("
Kabba/Bunu" , 23 ),("
Kogi" , 23 ),("
Lokoja" , 23 ),("
Mopa-Muro" , 23 ),("
Ofu" , 23 ),("
Ogori/Mangongo" , 23 ),("
Okehi" , 23 ),("
Okene" , 23 ),("
Olamabolo" , 23 ),("
Omala" , 23 ),("
Yagba East " , 23 ),("
Yagba West" , 23 ),("

Asa" , 24 ),("
Baruten" , 24 ),("
Edu" , 24 ),("
Ekiti" , 24 ),("
Ifelodun" , 24 ),("
Ilorin East" , 24 ),("
Ilorin West" , 24 ),("
Irepodun" , 24 ),("
Isin" , 24 ),("
Kaiama" , 24 ),("
Moro" , 24 ),("
Offa" , 24 ),("
Oke-Ero" , 24 ),("
Oyun" , 24 ),("
Pategi " , 24 ),("

Agege" , 25 ),("
Ajeromi-Ifelodun" , 25 ),("
Alimosho" , 25 ),("
Amuwo-Odofin" , 25 ),("
Apapa" , 25 ),("
Badagry" , 25 ),("
Epe" , 25 ),("
Eti-Osa" , 25 ),("
Ibeju/Lekki" , 25 ),("
Ifako-Ijaye " , 25 ),("
Ikeja" , 25 ),("
Ikorodu" , 25 ),("
Kosofe" , 25 ),("
Lagos Island" , 25 ),("
Lagos Mainland" , 25 ),("
Mushin" , 25 ),("
Ojo" , 25 ),("
Oshodi-Isolo" , 25 ),("
Shomolu" , 25 ),("
Surulere" , 25 ),("

Akwanga" , 26 ),("
Awe" , 26 ),("
Doma" , 26 ),("
Karu" , 26 ),("
Keana" , 26 ),("
Keffi" , 26 ),("
Kokona" , 26 ),("
Lafia" , 26 ),("
Nasarawa" , 26 ),("
Nasarawa-Eggon" , 26 ),("
Obi" , 26 ),("
Toto" , 26 ),("
Wamba " , 26 ),("

Agaie" , 27 ),("
Agwara" , 27 ),("
Bida" , 27 ),("
Borgu" , 27 ),("
Bosso" , 27 ),("
Chanchaga" , 27 ),("
Edati" , 27 ),("
Gbako" , 27 ),("
Gurara" , 27 ),("
Katcha" , 27 ),("
Kontagora " , 27 ),("
Lapai" , 27 ),("
Lavun" , 27 ),("
Magama" , 27 ),("
Mariga" , 27 ),("
Mashegu" , 27 ),("
Mokwa" , 27 ),("
Muya" , 27 ),("
Pailoro" , 27 ),("
Rafi" , 27 ),("
Rijau" , 27 ),("
Shiroro" , 27 ),("
Suleja" , 27 ),("
Tafa" , 27 ),("
Wushishi" , 27 ),("

Abeokuta North" , 28 ),("
Abeokuta South" , 28 ),("
Ado-Odo/Ota" , 28 ),("
Egbado North" , 28 ),("
Egbado South" , 28 ),("
Ewekoro" , 28 ),("
Ifo" , 28 ),("
Ijebu East" , 28 ),("
Ijebu North" , 28 ),("
Ijebu North East" , 28 ),("
Ijebu Ode" , 28 ),("
Ikenne" , 28 ),("
Imeko-Afon" , 28 ),("
Ipokia" , 28 ),("
Obafemi-Owode" , 28 ),("
Ogun Waterside" , 28 ),("
Odeda" , 28 ),("
Odogbolu" , 28 ),("
Remo North" , 28 ),("
Shagamu" , 28 ),("

Akoko North East" , 29 ),("
Akoko North West" , 29 ),("
Akoko South Akure East" , 29 ),("
Akoko South West" , 29 ),("
Akure North" , 29 ),("
Akure South" , 29 ),("
Ese-Odo" , 29 ),("
Idanre" , 29 ),("
Ifedore" , 29 ),("
Ilaje" , 29 ),("
Ile-Oluji" , 29 ),("
Okeigbo" , 29 ),("
Irele" , 29 ),("
Odigbo" , 29 ),("
Okitipupa" , 29 ),("
Ondo East" , 29 ),("
Ondo West" , 29 ),("
Ose" , 29 ),("
Owo " , 29 ),("

Aiyedade" , 30 ),("
Aiyedire" , 30 ),("
Atakumosa East" , 30 ),("
Atakumosa West" , 30 ),("
Boluwaduro" , 30 ),("
Boripe" , 30 ),("
Ede North" , 30 ),("
Ede South" , 30 ),("
Egbedore" , 30 ),("
Ejigbo" , 30 ),("
Ife Central" , 30 ),("
Ife East" , 30 ),("
Ife North" , 30 ),("
Ife South" , 30 ),("
Ifedayo" , 30 ),("
Ifelodun" , 30 ),("
Ila" , 30 ),("
Ilesha East" , 30 ),("
Ilesha West" , 30 ),("
Irepodun" , 30 ),("
Irewole" , 30 ),("
Isokan" , 30 ),("
Iwo" , 30 ),("
Obokun" , 30 ),("
Odo-Otin" , 30 ),("
Ola-Oluwa" , 30 ),("
Olorunda" , 30 ),("
Oriade" , 30 ),("
Orolu" , 30 ),("
Osogbo" , 30 ),("

Afijio" , 31 ),("
Akinyele" , 31 ),("
Atiba" , 31 ),("
Atigbo" , 31 ),("
Egbeda" , 31 ),("
IbadanCentral" , 31 ),("
Ibadan North" , 31 ),("
Ibadan North West" , 31 ),("
Ibadan South East" , 31 ),("
Ibadan South West" , 31 ),("
Ibarapa Central" , 31 ),("
Ibarapa East" , 31 ),("
Ibarapa North" , 31 ),("
Ido" , 31 ),("
Irepo" , 31 ),("
Iseyin" , 31 ),("
Itesiwaju" , 31 ),("
Iwajowa" , 31 ),("
Kajola" , 31 ),("
Lagelu Ogbomosho North" , 31 ),("
Ogbmosho South" , 31 ),("
Ogo Oluwa" , 31 ),("
Olorunsogo" , 31 ),("
Oluyole" , 31 ),("
Ona-Ara" , 31 ),("
Orelope" , 31 ),("
Ori Ire" , 31 ),("
Oyo East" , 31 ),("
Oyo West" , 31 ),("
Saki East" , 31 ),("
Saki West" , 31 ),("
Surulere" , 31 ),("

Barikin Ladi" , 32 ),("
Bassa" , 32 ),("
Bokkos" , 32 ),("
Jos East" , 32 ),("
Jos North" , 32 ),("
Jos South" , 32 ),("
Kanam" , 32 ),("
Kanke" , 32 ),("
Langtang North" , 32 ),("
Langtang South" , 32 ),("
Mangu" , 32 ),("
Mikang" , 32 ),("
Pankshin" , 32 ),("
Qua-an Pan" , 32 ),("
Riyom" , 32 ),("
Shendam" , 32 ),("
Wase" , 32 ),("

Abua/Odual" , 33 ),("
Ahoada East" , 33 ),("
Ahoada West" , 33 ),("
Akuku Toru" , 33 ),("
Andoni" , 33 ),("
Asari-Toru" , 33 ),("
Bonny" , 33 ),("
Degema" , 33 ),("
Emohua" , 33 ),("
Eleme" , 33 ),("
Etche" , 33 ),("
Gokana" , 33 ),("
Ikwerre" , 33 ),("
Khana" , 33 ),("
Obia/Akpor" , 33 ),("
Ogba/Egbema/Ndoni" , 33 ),("
Ogu/Bolo" , 33 ),("
Okrika" , 33 ),("
Omumma" , 33 ),("
Opobo/Nkoro" , 33 ),("
Oyigbo" , 33 ),("
Port-Harcourt" , 33 ),("
Tai " , 33 ),("


Binji" , 34 ),("
Bodinga" , 34 ),("
Dange-shnsi" , 34 ),("
Gada" , 34 ),("
Goronyo" , 34 ),("
Gudu" , 34 ),("
Gawabawa" , 34 ),("
Illela" , 34 ),("
Isa" , 34 ),("
Kware" , 34 ),("
kebbe" , 34 ),("
Rabah" , 34 ),("
Sabon birni" , 34 ),("
Shagari" , 34 ),("
Silame" , 34 ),("
Sokoto North" , 34 ),("
Sokoto South" , 34 ),("
Tambuwal" , 34 ),("
Tqngaza" , 34 ),("
Tureta" , 34 ),("
Wamako" , 34 ),("
Wurno" , 34 ),("
Yabo" , 34 ),("

Ardo-kola" , 35 ),("
Bali" , 35 ),("
Donga" , 35 ),("
Gashaka" , 35 ),("
Cassol" , 35 ),("
Ibi" , 35 ),("
Jalingo" , 35 ),("
Karin-Lamido" , 35 ),("
Kurmi" , 35 ),("
Lau" , 35 ),("
Sardauna" , 35 ),("
Takum" , 35 ),("
Ussa" , 35 ),("
Wukari" , 35 ),("
Yorro" , 35 ),("
Zing" , 35 ),("

Bade" , 36 ),("
Bursari" , 36 ),("
Damaturu" , 36 ),("
Fika" , 36 ),("
Fune" , 36 ),("
Geidam" , 36 ),("
Gujba" , 36 ),("
Gulani" , 36 ),("
Jakusko" , 36 ),("
Karasuwa" , 36 ),("
Karawa" , 36 ),("
Machina" , 36 ),("
Nangere" , 36 ),("
Nguru Potiskum" , 36 ),("
Tarmua" , 36 ),("
Yunusari" , 36 ),("
Yusufari" , 36 ),("

Anka " , 37 ),("
Bakura " , 37 ),("
Birnin Magaji " , 37 ),("
Bukkuyum " , 37 ),("
Bungudu " , 37 ),("
Gummi " , 37 ),("
Gusau " , 37 ),("
Kaura " , 37 ),("
Namoda " , 37 ),("
Maradun " , 37 ),("
Maru " , 37),("
Shinkafi " , 37 ),("
Talata Mafara " , 37 ),("
Tsafe " , 37 ),("
NA" , 38 )';


if(!mysqli_query($link,$sql2)){
    $error = mysqli_error($link).' unable INSERT LGAS';
    include $_SERVER['DOCUMENT_ROOT'].'/api/includes/errors/error.html.php';
    exit();
}




?>