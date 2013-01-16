-- phpMyAdmin SQL Dump
-- version 3.5.1
-- http://www.phpmyadmin.net
--
-- Machine: localhost
-- Genereertijd: 16 jan 2013 om 15:05
-- Serverversie: 5.5.24-log
-- PHP-versie: 5.3.13

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Databank: `kbs`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `agenda`
--

CREATE TABLE IF NOT EXISTS `agenda` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `naam` varchar(128) NOT NULL,
  `last_update` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `start_tijd` time DEFAULT NULL,
  `eind_tijd` time DEFAULT NULL,
  `start_datum` date NOT NULL,
  `eind_datum` date NOT NULL,
  `hele_dag` enum('false','true') NOT NULL DEFAULT 'false',
  `locatie` varchar(64) NOT NULL,
  `beschrijving` text NOT NULL,
  `dienst` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=54 ;

--
-- Gegevens worden uitgevoerd voor tabel `agenda`
--

INSERT INTO `agenda` (`id`, `naam`, `last_update`, `start_tijd`, `eind_tijd`, `start_datum`, `eind_datum`, `hele_dag`, `locatie`, `beschrijving`, `dienst`) VALUES
(10, 'test', '2013-01-07 17:45:53', NULL, NULL, '2012-12-13', '0000-00-00', 'false', '', '', 0),
(11, 'test', '2013-01-07 17:45:50', NULL, NULL, '2012-11-26', '0000-00-00', 'false', '', '', 0),
(13, 'test', '2013-01-07 17:45:48', NULL, NULL, '2013-01-07', '0000-00-00', 'false', '', '', 0),
(15, 'blaatr', '2013-01-07 17:45:43', NULL, NULL, '2012-11-25', '0000-00-00', 'false', '', '', 0),
(17, 'Nog een test', '2013-01-07 17:45:29', NULL, NULL, '2012-12-12', '2012-12-12', 'false', 'test', 'test', 0),
(18, 'test2', '2013-01-07 17:45:21', NULL, NULL, '2012-12-11', '0000-00-00', 'false', '', '', 0),
(19, 'dsadasdasf', '2013-01-07 17:45:17', NULL, NULL, '2012-11-27', '0000-00-00', 'false', '', '', 0),
(21, 'test', '0000-00-00 00:00:00', '18:50:08', '18:50:08', '2012-12-17', '2012-12-17', 'false', 'waar', 'desc', 0),
(22, 'test', '0000-00-00 00:00:00', NULL, NULL, '2012-12-05', '0000-00-00', 'false', '', '', 0),
(23, 'teste', '0000-00-00 00:00:00', NULL, NULL, '2012-12-05', '0000-00-00', 'false', '', '', 0),
(27, 'teate', '0000-00-00 00:00:00', NULL, NULL, '2012-12-05', '0000-00-00', 'false', '', '', 0),
(28, 'asdsda', '0000-00-00 00:00:00', NULL, NULL, '2012-12-05', '0000-00-00', 'false', '', '', 0),
(30, 'teats', '2013-01-07 17:39:48', '02:00:00', '03:00:00', '2013-01-23', '2013-01-25', 'false', 'dit is een test', 'teste', 0),
(31, 'teate', '2013-01-07 17:45:10', NULL, NULL, '2013-01-23', '2013-01-24', 'false', '', '', 0),
(35, 'dsadas', '0000-00-00 00:00:00', NULL, NULL, '2012-12-12', '0000-00-00', 'false', '', '', 0),
(36, 'dsadas', '0000-00-00 00:00:00', NULL, NULL, '2012-12-12', '0000-00-00', 'false', '', '', 0),
(37, 'dsadas', '0000-00-00 00:00:00', NULL, NULL, '2012-12-12', '0000-00-00', 'false', '', '', 0),
(38, 'dsdas', '0000-00-00 00:00:00', NULL, NULL, '2012-12-12', '0000-00-00', 'false', '', '', 0),
(39, 'dsdsadas', '0000-00-00 00:00:00', NULL, NULL, '2012-12-12', '0000-00-00', 'false', '', '', 0),
(40, 'dsdas', '0000-00-00 00:00:00', NULL, NULL, '2012-12-12', '0000-00-00', 'false', '', '', 0),
(41, 'dsadas', '0000-00-00 00:00:00', NULL, NULL, '2012-12-12', '0000-00-00', 'false', '', '', 0),
(42, 'dasdas', '0000-00-00 00:00:00', NULL, NULL, '2012-12-12', '0000-00-00', 'false', '', '', 0),
(47, 'dsadas', '0000-00-00 00:00:00', NULL, NULL, '2013-01-06', '0000-00-00', 'false', '', '', 0),
(49, 'dit is een afspraak met een hele lange titel om te kijken wat er gebeurd als dit te lang wordt', '2013-01-07 20:01:09', NULL, NULL, '2013-01-09', '0000-00-00', 'true', 'Zwolle', 'dsad', 9),
(51, 'dsadsa', '0000-00-00 00:00:00', NULL, NULL, '2013-01-02', '0000-00-00', 'false', '', '', 0),
(52, 'dsdas', '0000-00-00 00:00:00', NULL, NULL, '2013-01-06', '0000-00-00', 'false', '', '', 0),
(53, 'dsdas', '0000-00-00 00:00:00', NULL, NULL, '2013-01-06', '0000-00-00', 'false', '', '', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `article`
--

CREATE TABLE IF NOT EXISTS `article` (
  `ID` int(4) NOT NULL AUTO_INCREMENT,
  `cat_id` int(11) NOT NULL,
  `date_added` datetime NOT NULL,
  `date_edited` datetime NOT NULL,
  `title` varchar(200) NOT NULL,
  `text` longtext NOT NULL,
  `published` int(1) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=31 ;

--
-- Gegevens worden uitgevoerd voor tabel `article`
--

INSERT INTO `article` (`ID`, `cat_id`, `date_added`, `date_edited`, `title`, `text`, `published`) VALUES
(9, 10, '2012-12-13 12:34:26', '2012-12-16 16:05:43', 'Particulier / Wonen', '<p>Hier komt een verhaaltje over wonen</p>', 1),
(10, 10, '2012-12-13 15:08:07', '2012-12-16 16:05:56', 'Particulier / Reizen', '<p>Welke rechten heb ik m.b.t. reiskostenvergoeding etc.</p>', 1),
(11, 10, '2012-12-16 15:34:59', '2012-12-16 15:37:27', 'Bedrijven / Werk en Ontslag', '<p>Wanneer kan ik mijn personeel ontslaan?</p>', 1),
(12, 10, '2012-12-16 15:36:10', '2012-12-16 15:37:16', 'Bedrijven / Uitkering', '<p>Informatie betreft uitkeringen.</p>\r\n<p>&nbsp;</p>\r\n<p>Wanneer wel/geen recht op uitkering?</p>\r\n<p>&nbsp;</p>\r\n<p>Welk percentage dient de werkgever te betalen?</p>', 1),
(13, 10, '2012-12-16 15:38:19', '2012-12-16 15:38:19', 'Bedrijven / Wonen', '<p>Wat voor hypotheek kan ik afsluiten als ik een eigen bedrijf heb.</p>', 1),
(14, 10, '2012-12-16 15:39:55', '2012-12-16 15:39:55', 'Bedrijven / Reizen', '<p>Moet ik mijn personeel reiskostenvergoeding geven en zo ja hoeveel?</p>', 1),
(15, 10, '2012-12-16 15:40:36', '2012-12-16 15:40:36', 'Bedrijven / Verkeer', '<p>Wat kan ik doen tegen telaatkomen van mijn personeel door files.</p>', 1),
(16, 10, '2012-12-16 15:41:40', '2012-12-16 15:41:40', 'Bedrijven / Internet/energie', '<p>Hoeveel kosten voor energie moet ik betalen?</p>\r\n<p>&nbsp;</p>\r\n<p>Welk internetabbonnement zal ik nemen?</p>', 1),
(17, 10, '2012-12-16 16:04:16', '2012-12-16 16:04:16', 'Particulier / Werk en Ontslag', '<p>Hier komt beetje over werk en ontslag</p>', 1),
(18, 10, '2012-12-16 16:05:00', '2012-12-16 16:05:31', 'Particulier / Uitkering', '<p>Hier komt info over uitkering</p>', 1),
(19, 10, '2012-12-16 16:06:10', '2012-12-16 16:07:21', 'Particulier / Verkeer', '<p>Hier komt info over verkeer</p>', 1),
(20, 10, '2012-12-16 16:06:37', '2012-12-16 16:06:37', 'Particulier / Internet/Energie', '<p>Hier komt info over internet/energie</p>', 1),
(21, 1, '2012-12-16 17:05:08', '2012-12-16 17:05:08', 'Onderhandelingen', '<p>Hier komt info over dienst onderhandelingen</p>', 1),
(22, 11, '2012-12-02 12:00:34', '2012-12-14 09:55:18', 'Actualiteit 1', '<p>Tinhoudend ondernomen de na er slikbanken. Wij echter jungle duurde zekere arbeid een zes. Dik talrijke gelijken nog verloren omgeving. Afneemt grooten plaatse er te nu waarmee duivels. Gold na voet te kost klei is en. Interest laatsten getracht zij had nam scheppen. Winnen bekend te deelen de.</p>\r\n<p>Eindelijk gebruiken wij mag visschers wijselijk eigenaars federatie. Van huwelijken ter mislukking verwijderd zes. Bevorderen in besproeien werktuigen er europeesch onderwoeld kwartslaag. Om af besparing vreedzame en arabische. Volledige uit zoo chineezen wij ingericht. Dat gezond zoo marmer sterke ceylon slecht. Rente nadat zes toe dat sap stiet holen zesde.</p>\r\n<p>Nu bezorgden bezwarend verdiende om te ingenieur ongunstig brandhout. Witte ellen ook prijs langs eerst reden wel die per. Welks elk drong lange stuit loopt ploeg per had. Gronds er parijs noodig of de. Den bevel gayah rijen nam hun zij. Gebruiken zee besluiten dan bloeiende oog aardschok. Geval om nu steek waren er mogen goten al.</p>', 1),
(23, 11, '2012-12-03 12:02:25', '2012-12-14 09:55:31', 'Actualiteit 2', '<p>Getracht brazilie nu nu systemen al fransche. Ad is goten ficus op ander. Of ze weggevoerd wetenschap losgemaakt op. Niet na al vele geen ze te. En is op alluviale krachtige provincie om behoeften. Ad gropeng te of terrein stellen gemaakt afkomst inkomen. Per schepping oog stroomend uit onzuivere belovende dat. Voordeel deeltjes al geheelen er af. Na ze maleiers lateriet bestuurd geslaagd is bedraagt pogingen. Tot zout maal zes ader bord.</p>\r\n<p>Op de afkoopen brazilie rekening inwoners er ze. Nu toezicht contract speurzin en te vijftien ad gebruikt. Vliegen ze vervoer opnieuw ernstig ze al. Duizend brokken te ad bedroeg bekkens. Nu in gold er wiel ziet rijk te. Er tien boom om ziet deze. Noodlottig er locomobiel economisch al. Leven zij met zal diepe wonen zee welks.</p>\r\n<p>De vluchten welvaart in meenemen al centraal verbindt. Tegelijk te verrezen de nu snelsten slaagden. Zeven om liput welks ze nacht. Te vergissing insnijding uitgevoerd traliewerk nu bijzonders. Het elkander menschen vreemden den goa men afkoopen. Lijnen groene kleine ter dit eerste wij als zilver sumper. Gezond boomen omtrek ik is graven. Recht oog met steun geeft zelfs heb.</p>', 1),
(24, 11, '2012-10-05 12:03:10', '2012-12-14 09:55:37', 'Actualiteit 3', '<p>Lage soms deel stad ad vast nu erin. Zij wie met vermijden nutteloos tinmijnen. Kan wegen wilde drong reden dal naast tin van. Stampers roestige pyrieten ad te. Beroemde eveneens te laatsten contract te. Ten gronds weldra gevolg die passen steeds zonder. Singapore inderdaad zee elk gedeelten ons tin afscheidt plaatsing afstanden. En deze in ze dure mier liet. Al anson af noemt op kreeg omdat china.</p>\r\n<p>Wordt of ad begin varen en. Deel is ik alle te geen. Nu wijk zout te ze is acre. Andere ceylon om te kriang lieden. Monopolie bezwarend stroomend gesteente na of de afstanden overwaard nu. Ongunstige schoonheid karrijders af nu europeanen geruineerd weelderige. Is heuvel ruimer slotte er om.</p>\r\n<p>Af ad nu maleische al versteend bezwarend liverpool arabieren. Ongebruikt tembunmijn ik ongunstige vergrooten in. Nu er wetenschap ondernomen vergrooten al en verscholen. Vreemde inhouds en bewogen al gezocht amboina ze er. Mag omwonden gas mogelijk ernstige vreemden. Mogelijk gebeuren zes verbruik wel elastica het gevonden pyrieten. Elk wedijveren verdwijnen ptolomaeus interesten tin dit uitgevoerd. Mengeling dat opgericht degelijke een het nutteloos.</p>', 1),
(25, 11, '2012-12-01 19:09:22', '2012-12-14 09:55:01', 'Actualiteit 0', '<p>Duim uit der als hand toe erin. Vinden sedert na omtrek binnen op tunnel ze zuiger. Hij assam werkt hun komen groei ons zeker klein. Wiel ook tien dan eens deze. Holte er ouder zelfs de peper naast en. Dal tinwinning wij voertuigen handelaars woekeraars die bak. Spelen altijd are sumper ook toppen rug wij. Alluviale of wijselijk belovende ze ingericht. Uren meer na kilo vorm twee te. Tinwinning om nu karrijders voorloopig handelaars kwartspuin locomobiel.</p>\r\n<p>En vergoeding uitstekend denzelfden ik. Dik daar acre zijn voor ver veel. Ter allen den telde kan heeft. Verklaart om voldoende degelijke er overvloed al afstanden weerstand. Vijf tot meer woud zoo dik bron. Ze snelleren nu bezorgden krachtige af na wonderwel. Afscheidt nu aangelegd vernieuwd ad overvloed. Forten andere streek te in er europa nu.</p>\r\n<p>Zoo laten wel tegen nog ouder. Spelen wat wereld heuvel tin den wij dragen hoopen. Voorschot mee hellingen are wie overvloed besparing. Nog van sap dit heele diepe ijzer. Zou afgestaan gewijzigd afstanden maleische een. Antwerpen ver hun nabijheid evenwicht herhaling per den.</p>', 1),
(26, 1, '2012-12-18 17:26:25', '2012-12-18 17:26:25', 'Juridisch Advies', 'BLABLA', 1),
(27, 1, '2013-01-09 10:55:55', '2013-01-09 10:55:55', 'Juridisch advies', '<p>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>', 1),
(28, 1, '2013-01-09 10:57:00', '2013-01-09 10:57:00', 'Algemene voorwaarden/overeenkomst Opstellen', '<p>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>', 1),
(29, 1, '2013-01-09 10:57:34', '2013-01-09 10:57:34', 'Het voeren van procedures', '<p>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>', 1),
(30, 1, '2013-01-09 11:01:31', '2013-01-09 11:01:31', 'Incasso', '<p>"Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum."</p>', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `berichten`
--

CREATE TABLE IF NOT EXISTS `berichten` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titel` varchar(45) NOT NULL,
  `afzender` int(11) NOT NULL,
  `ontvanger` int(11) NOT NULL,
  `bericht` varchar(1024) NOT NULL,
  `datum` datetime NOT NULL,
  `gelezen` int(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=16 ;

--
-- Gegevens worden uitgevoerd voor tabel `berichten`
--

INSERT INTO `berichten` (`id`, `titel`, `afzender`, `ontvanger`, `bericht`, `datum`, `gelezen`) VALUES
(13, 'Lipsum', 2, 1, '<p>Damet</p>', '2013-01-16 15:00:34', 1),
(14, 'Lipsum', 1, 2, '<p>mmmmmmmmmmmmmmmmmmmmmmm<strong>bbbbbbbbbbbbbbbbbbb</strong></p>', '2013-01-16 15:00:50', 1),
(15, 'Lipsum 2', 2, 1, '<p>Ongelezen</p>', '2013-01-16 15:01:10', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `category`
--

CREATE TABLE IF NOT EXISTS `category` (
  `cat_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(200) NOT NULL,
  `discription` varchar(300) NOT NULL,
  `published` int(1) NOT NULL,
  PRIMARY KEY (`cat_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=12 ;

--
-- Gegevens worden uitgevoerd voor tabel `category`
--

INSERT INTO `category` (`cat_id`, `name`, `discription`, `published`) VALUES
(1, 'Diensten', 'Categorie voor de diensten', 1),
(2, 'Tarieven', 'Hier moet de beschrijving komen', 1),
(9, 'test', 'testdinsdagmiddag', 1),
(10, 'Menu-Items', 'Hier dienen artikelen die aan een menu-items gelinkt dienen te worden opgeslagen te worden.', 1),
(11, 'Actualiteiten', ' In deze categorie zitten de actualiteiten', 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `dienst_aanvragen`
--

CREATE TABLE IF NOT EXISTS `dienst_aanvragen` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `naam` varchar(64) NOT NULL,
  `email` varchar(128) NOT NULL,
  `adres` varchar(64) NOT NULL,
  `postcode` varchar(6) NOT NULL,
  `woonplaats` varchar(64) NOT NULL,
  `telefoon` varchar(16) NOT NULL,
  `mobiel` varchar(16) NOT NULL,
  `datum` date NOT NULL,
  `start_tijd` time NOT NULL,
  `eind_tijd` time NOT NULL,
  `dienst_id` int(11) NOT NULL,
  `status` varchar(32) NOT NULL,
  `beschrijving` text NOT NULL,
  `locatie` varchar(64) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=11 ;

--
-- Gegevens worden uitgevoerd voor tabel `dienst_aanvragen`
--

INSERT INTO `dienst_aanvragen` (`id`, `naam`, `email`, `adres`, `postcode`, `woonplaats`, `telefoon`, `mobiel`, `datum`, `start_tijd`, `eind_tijd`, `dienst_id`, `status`, `beschrijving`, `locatie`) VALUES
(1, 'Robert-John', 'rjvandoesburg@gmail.com', 'eurosingel 40', '8253EB', 'Dronten', '0321-380322', '', '0000-00-00', '01:30:00', '05:00:00', 9, 'aangevraagd', '', ''),
(5, 'Robert-John', 'rj@exed.nl', 'aasd', '8253EB', 'dron', '', '06-11118353', '2013-01-06', '04:30:00', '02:00:00', 9, 'goedgekeurd', 'test', ''),
(8, 'Robert-John', 'rjvandoesburg@gmail.com', 'Eurosingel 40', '8253EB', 'Dronten', '', '06-11118353', '2013-01-08', '02:30:00', '04:30:00', 9, 'aangevraagd', 'dit is een beschrijving', 'Zwolle');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `downloads`
--

CREATE TABLE IF NOT EXISTS `downloads` (
  `ID` int(11) NOT NULL,
  `file` varchar(80) DEFAULT NULL,
  `size` int(4) DEFAULT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden uitgevoerd voor tabel `downloads`
--

INSERT INTO `downloads` (`ID`, `file`, `size`) VALUES
(0, 'Wisseling contactpersoon.docx', 18),
(145, 'Abonnement opzeggen.docx', 19),
(146, 'Reactie klachtenbrief.docx', 22),
(147, 'Ontslag nemen.docx', 20),
(148, 'Aankondiging bedrijfsovername.docx', 20),
(149, 'Afwijzing sponsorverzoek.docx', 19),
(150, 'Openingszinnen voor zakelijke brief.docx', 17),
(151, 'Voorbeeld ontvangstbevestiging.docx', 18),
(152, 'Briefindeling.docx', 46),
(153, 'Begeleidende brieven voor offertes.docx', 19);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `menu_item`
--

CREATE TABLE IF NOT EXISTS `menu_item` (
  `id` int(4) NOT NULL,
  `parent_item` varchar(32) NOT NULL,
  `child_item` varchar(32) NOT NULL,
  `article_id` int(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden uitgevoerd voor tabel `menu_item`
--

INSERT INTO `menu_item` (`id`, `parent_item`, `child_item`, `article_id`) VALUES
(1, 'Bedrijven', 'Werk-en-Ontslag', 11),
(2, 'Bedrijven', 'Uitkering', 12),
(3, 'Bedrijven', 'Wonen', 13),
(4, 'Bedrijven', 'Reizen', 14),
(5, 'Bedrijven', 'Verkeer', 15),
(6, 'Bedrijven', 'Internet-energie', 16),
(7, 'Particulier', 'Werk-en-Ontslag', 17),
(8, 'Particulier', 'Uitkering', 18),
(9, 'Particulier', 'Wonen', 9),
(10, 'Particulier', 'Reizen', 10),
(11, 'Particulier', 'Verkeer', 19),
(12, 'Particulier', 'Internet-energie', 20);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `services`
--

CREATE TABLE IF NOT EXISTS `services` (
  `service_id` int(11) NOT NULL,
  `servicename` varchar(45) NOT NULL,
  `servicetext` longtext NOT NULL,
  `pph` double DEFAULT NULL,
  `avgcost` double DEFAULT NULL,
  `image` varchar(45) NOT NULL,
  `article_id` int(4) NOT NULL,
  `published` int(1) NOT NULL,
  PRIMARY KEY (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden uitgevoerd voor tabel `services`
--

INSERT INTO `services` (`service_id`, `servicename`, `servicetext`, `pph`, `avgcost`, `image`, `article_id`, `published`) VALUES
(1, 'Echtscheiding', 'Heeft u vragen over echtscheiding en alles wat daar bij komt kijken?', 20, 85, 'afbeelding.png', 5, 1),
(2, 'Arbeidsrecht', 'Heeft u vragen over arbeidsrecht en alles wat daar bij komt kijken?', 30, 90, 'afbeelding.png', 8, 1),
(8, 'Testservice 1', '<p>Test</p>', 20, 25, '', 21, 1),
(9, 'Juridisch advies', '<p>Heeft u een juridische kwestie?</p>', 20, 120, '', 26, 1),
(10, 'test', '<p>leuk verhaaltje</p>', 100, 20, '', 21, 1);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `password` varchar(64) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

--
-- Gegevens worden uitgevoerd voor tabel `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `admin`) VALUES
(1, 'test', '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08', 1),
(2, 'testclient', '9f86d081884c7d659a2feaa0c55ad015a3bf4f1b2b0b822cd15d6c15b0f00a08', 0);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `user_data`
--

CREATE TABLE IF NOT EXISTS `user_data` (
  `user_id` int(11) NOT NULL,
  `naam` varchar(64) DEFAULT NULL,
  `adres` varchar(32) DEFAULT NULL,
  `postcode` varchar(6) DEFAULT NULL,
  `woonplaats` varchar(32) DEFAULT NULL,
  `telefoon` varchar(15) DEFAULT NULL,
  `mobiel` varchar(15) DEFAULT NULL,
  `email` varchar(128) NOT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden uitgevoerd voor tabel `user_data`
--

INSERT INTO `user_data` (`user_id`, `naam`, `adres`, `postcode`, `woonplaats`, `telefoon`, `mobiel`, `email`) VALUES
(2, 'Lorem Ipsum', 'Dolor Sit', '1234AB', 'Amet', '0527918242', '0618283482', 'test@test.nl');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
