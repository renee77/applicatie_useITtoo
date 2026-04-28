USE useITtooApplicatieDB;

-- ============================================================
-- PRODUCTEN
-- Voorraad wordt automatisch aangemaakt via de trigger
-- product_insert_voorraad bij elke INSERT op product.
-- ============================================================
INSERT INTO `product` (`naam`, `omschrijving`, `prijs`, `foto_url`, `leverancier`, `verkoop_gewicht`)
VALUES
('Wortel', 'Een frisse oranje wortel uit eigen bodem! Boer Jan kwam helaas te zitten met een overschot, waardoor wij nu deze prachtige biologische wortels kunnen aanbieden!', 1.99, 'wortel_jan', 'Boer Jan uit Oosterhout', 'per kg'),
('Aardappel', 'Biologische aardappels, vers van het land van Boer Piet. Puur, smaakvol en met zorg geteeld in onze regio.', 1.49, 'aardappel_piet', 'Boer Piet uit Langeweg', 'per kg'),
('Aubergine', 'Biologische aubergines van boer Ellen, vers geoogst en vol smaak. Duurzaam geteeld en direct uit de streek.', 2.29, 'aubergine_ellen', 'Boer Ellen uit Zevenbergen', 'per kg'),
('Boerenkool', 'Biologische boerenkool van boer Anton, vers van het veld. Een pure en eerlijke groente uit eigen regio.', 2.09, 'boerenkool_anton', 'Boer Anton uit Dinteloord', 'per kg'),
('Champignons', 'Biologische champignons, met zorg geteeld door onze boerin Ingrid. Vers, smaakvol en rechtstreeks uit de regio', 1.49, 'champignons_ingrid', 'Boer Ingrid uit Made', 'per 500 gram'),
('Cherry tomaatjes', 'Zoete biologische cherry tomaatjes van boer Kees. Vers geplukt en vol natuurlijke smaak uit onze regio.', 2.49, 'cherrytomaat_kees', 'Boer Kees uit Breda', 'per 500 gram'),
('Groene asperges', 'Biologische groene asperges van boerin Marijke. Vers van het land en met zorg geteeld in Noord-Brabant.', 3.99, 'groeneasperge_marijke', 'Boerin Marijke uit Etten-Leur', 'per 500 gram'),
('Paprika', 'Biologische paprikas van boer Tom, met liefde geteeld in onze streek. Vers, knapperig en vol smaak.', 2.79, 'paprika_tom', 'Boer Tom uit Rucphen', 'per kg'),
('Pompoen', 'Biologische pompoenen van boer Henk, vers geoogst van het Brabantse land. Heerlijk zacht en vol van smaak.', 3.49, 'pompoen_henk', 'Boer Henk uit Roosendaal', 'per stuk'),
('Spinazie', 'Verse biologische spinazie van boerin Lisa. Duurzaam geteeld en rechtstreeks uit de regio.', 1.99, 'spinazie_lisa', 'Boerin Lisa uit Oudenbosch', 'per 400 gram'),
('Spruiten','Biologische spruiten van Boer Willem. Deze typische wintergroente heeft een stevige smaak en is heerlijk geroosterd, gekookt of verwerkt in stamppot.',2.39,'spruiten_willem','Boer Willem uit Steenbergen','per kg'),
('Tomaten','Sappige biologische tomaten van Boer Bas. Vol van smaak en perfect voor salades, sauzen, soepen of om vers te eten.',2.59,'tomaat_bas','Boer Bas uit Tilburg','per kg'),
('Uien','Biologische uien van Boer Martijn. Deze veelzijdige groente vormt de basis van talloze gerechten en geeft een heerlijke hartige smaak aan soepen en sauzen.',1.29,'ui_martijn','Boer Martijn uit Waalwijk','per kg'),
('Zoete aardappel','Biologische zoete aardappels van boerin Sanne. Deze aardappels hebben een zachte, lichtzoete smaak en zijn heerlijk geroosterd, gepureerd of gebakken.',2.99,'zoeteaardappel_sanne','Boerin Sanne uit Bergen op Zoom','per kg'),
('Elstar appel','Frisse Elstar appels uit de boomgaard van Boer Dirk. Deze appel heeft een perfecte balans tussen zoet en friszuur en is heerlijk als snack of in appeltaart.',2.49,'elstar_dirk','Boer Dirk uit Uden','per kg'),
('Jonagold appel','Grote Jonagold appels met een zoete en licht frisse smaak. Ideaal om vers te eten of te verwerken in desserts en gebak.',2.49,'jonagold_dirk','Boer Dirk uit Uden','per kg'),
('Gala appel','Zoete en knapperige Gala appels. Deze populaire appelsoort is perfect als gezonde snack en geliefd bij jong en oud.',2.59,'gala_dirk','Boer Dirk uit Uden','per kg'),
('Conference peer','Sappige Conference peren van boerin Anja. Deze peer heeft een zachte structuur en een zoete smaak, ideaal om vers te eten of in desserts.',2.79,'conference_anja','Boerin Anja uit Sint-Oedenrode','per kg'),
('Doyenne du Comice peer','Een bijzonder zoete en aromatische peer. Deze peer staat bekend om zijn sappige vruchtvlees en rijke smaak.',2.99,'doyenne_anja','Boerin Anja uit Sint-Oedenrode','per kg'),
('Aardbeien','Zoete biologische aardbeien van Boer Koen. Vers geoogst en heerlijk sappig, perfect als zomerse snack of in desserts.',3.49,'aardbei_koen','Boer Koen uit Zundert','per 500 gram'),
('Blauwe bessen','Biologische blauwe bessen van boerin Maaike. Klein maar krachtig van smaak en heerlijk in yoghurt, smoothies of als gezonde snack.',3.99,'blauwebes_maaike','Boerin Maaike uit Chaam','per 250 gram'),
('Pruimen','Zoete en sappige pruimen van Boer Erik. Deze vruchten zijn heerlijk om vers te eten of te verwerken in jam en desserts.',2.99,'pruim_erik','Boer Erik uit Hilvarenbeek','per kg'),
('Broccoli','Verse broccoli vol vitamines en mineralen. Deze groene groente is perfect om te koken, stomen of roerbakken.',2.19,'broccoli_karel','Boer Karel uit Rijen','per stuk'),
('Bloemkool','Biologische bloemkool met een zachte smaak en stevige structuur. Ideaal om te koken, roosteren of verwerken in ovengerechten.',2.49,'bloemkool_henk','Boer Henk uit Roosendaal','per stuk'),
('Courgette','Verse courgettes van boerin Ellen. Deze veelzijdige groente is heerlijk om te grillen, bakken of verwerken in pasta en ovenschotels.',1.89,'courgette_ellen','Boer Ellen uit Zevenbergen','per stuk'),
('Rode kool','Biologische rode kool van Boer Anton. Deze stevige kool heeft een lichtzoete smaak en is heerlijk gestoofd of rauw in salades.',2.29,'rodekool_anton','Boer Anton uit Dinteloord','per stuk'),
('Witte kool','Stevige witte kool met een frisse smaak. Ideaal voor salades, roerbakgerechten of traditionele gerechten.',2.09,'wittekool_anton','Boer Anton uit Dinteloord','per stuk'),
('Prei','Verse prei uit Brabantse grond. Deze groente heeft een zachte uiensmaak en is perfect voor soepen, roerbakgerechten en quiches.',1.79,'prei_martijn','Boer Martijn uit Waalwijk','per stuk'),
('Sla','Knapperige kropsla van boerin Lisa. Vers geoogst en perfect voor frisse salades.',1.29,'sla_lisa','Boerin Lisa uit Oudenbosch','per stuk'),
('Komkommer','Verse komkommer met een frisse en sappige smaak. Perfect voor salades, sandwiches of als gezonde snack.',1.39,'komkommer_tom','Boer Tom uit Rucphen','per stuk'),
('Radijs','Pittige radijsjes met een knapperige structuur. Heerlijk in salades of als frisse toevoeging op brood.',1.49,'radijs_ingrid','Boerin Ingrid uit Made','per bos'),
('Rucola','Pittige rucola met een licht nootachtige smaak. Ideaal voor salades, pasta of als topping op pizza.',1.69,'rucola_lisa','Boerin Lisa uit Oudenbosch','per 100 gram'),
('Paksoi','Frisse paksoi met een zachte smaak. Perfect voor Aziatische roerbakgerechten.',1.99,'paksoi_karel','Boer Karel uit Rijen','per stuk'),
('Knolselderij','Grote knolselderij met een aromatische smaak. Perfect voor soep, puree of als geroosterde groente.',2.39,'knolselderij_jan','Boer Jan uit Oosterhout','per stuk'),
('Biet','Biologische rode bieten met een lichtzoete aardse smaak. Heerlijk geroosterd, gekookt of in salades.',1.99,'biet_piet','Boer Piet uit Langeweg','per kg'),
('Frambozen','Zoete en zachte frambozen van boerin Maaike. Perfect voor desserts, yoghurt of als gezonde snack.',3.99,'framboos_maaike','Boerin Maaike uit Chaam','per 250 gram'),
('Bramen','Verse braambessen met een diepe zoete smaak. Heerlijk in smoothies, desserts of jam.',3.49,'braam_maaike','Boerin Maaike uit Chaam','per 250 gram'),
('Kersen','Zoete en sappige kersen van Boer Erik. Perfect om vers te eten of te verwerken in gebak.',4.49,'kers_erik','Boer Erik uit Hilvarenbeek','per 500 gram'),
('Nectarines','Sappige nectarines met een zoete smaak en stevige structuur. Heerlijk als zomerse snack.',3.29,'nectarine_erik','Boer Erik uit Hilvarenbeek','per kg'),
('Perziken','Zoete perziken met zacht vruchtvlees en een heerlijke zomerse smaak.',3.19,'perzik_erik','Boer Erik uit Hilvarenbeek','per kg'),
('Abrikozen','Zoete abrikozen met een zachte structuur. Heerlijk vers of in desserts.',3.59,'abrikoos_erik','Boer Erik uit Hilvarenbeek','per kg'),
('Meloen','Zoete meloen met sappig vruchtvlees. Perfect als verfrissende zomersnack.',3.99,'meloen_koen','Boer Koen uit Zundert','per stuk'),
('Watermeloen','Grote verfrissende watermeloen met zoet sappig vruchtvlees.',4.49,'watermeloen_koen','Boer Koen uit Zundert','per stuk'),
('Druiven','Zoete druiven met een frisse smaak. Perfect als snack of in fruitsalades.',2.99,'druif_dirk','Boer Dirk uit Uden','per 500 gram'),
('Kiwi','Frisse kiwi met een lichtzoete en lichtzure smaak. Heerlijk in yoghurt of fruitsalades.',2.49,'kiwi_anja','Boerin Anja uit Sint-Oedenrode','per kg'),
('Appelmoes appels','Appels speciaal geschikt voor het maken van appelmoes of appeltaart.',1.99,'moesappel_dirk','Boer Dirk uit Uden','per kg'),
('Stoofperen','Peren die perfect zijn om langzaam te stoven met kruiden en suiker.',2.49,'stoofpeer_anja','Boerin Anja uit Sint-Oedenrode','per kg'),
('Citroen','Frisse citroenen met een krachtige citrus smaak. Ideaal voor drankjes, desserts en koken.',2.29,'citroen_bas','Boer Bas uit Tilburg','per kg'),
('Limoen','Frisse limoenen met een aromatische citrus smaak. Perfect voor cocktails en gerechten.',2.49,'limoen_bas','Boer Bas uit Tilburg','per kg'),
('Granaatappel','Zoetzure granaatappel vol sappige pitjes. Heerlijk in salades, yoghurt of als snack.',3.49,'granaatappel_bas','Boer Bas uit Tilburg','per stuk'),

-- LANGER HOUDBAAR
('Tomatensaus','Ambachtelijke pastasaus gemaakt van de rijpe tomaten van Boer Bas uit Tilburg. De tomaten worden langzaam gekookt met kruiden om een rijke en volle saus te creëren, perfect voor pasta en ovengerechten.',3.49,NULL,'Boer Bas uit Tilburg','per pot 500 gram'),
('Appelmoes','Verse appelmoes gemaakt van Elstar en Jonagold appels uit de boomgaard van Boer Dirk uit Uden. De appels worden langzaam gestoofd tot een zachte en lichtzoete moes zonder kunstmatige toevoegingen.',2.99,'appelmoes','Boer Dirk uit Uden','per pot 500 gram'),
('Perencompote','Zachte perencompote gemaakt van Conference peren van boerin Anja uit Sint-Oedenrode. De peren worden langzaam gekookt met een vleugje kaneel voor een heerlijke zoete smaak.',3.29,NULL,'Boerin Anja uit Sint-Oedenrode','per pot 500 gram'),
('Pompoensoep','Romige pompoensoep gemaakt van pompoenen van Boer Henk uit Roosendaal. De pompoenen worden geroosterd en gemengd met kruiden voor een zachte en verwarmende soep.',3.79,'pompoensoep','Boer Henk uit Roosendaal','per pot 750 ml'),
('Aardbeienjam','Zoete aardbeienjam gemaakt van verse aardbeien van Boer Koen uit Zundert. De aardbeien worden met zorg gekookt tot een rijke jam vol natuurlijke smaak.',3.49,'aardbeienjam','Boer Koen uit Zundert','per pot 350 gram'),
('Blauwe bessenjam','Ambachtelijke jam gemaakt van blauwe bessen van boerin Maaike uit Chaam. Deze jam heeft een diepe fruitsmaak en is heerlijk op brood of in yoghurt.',3.79,'blauwebessenjam','Boerin Maaike uit Chaam','per pot 350 gram'),
('Pruimenjam','Smeuige pruimenjam gemaakt van de zoete pruimen van Boer Erik uit Hilvarenbeek. Perfect voor op brood, pannenkoeken of in desserts.',3.49,'pruimenjam','Boer Erik uit Hilvarenbeek','per pot 350 gram'),
('Groentesoep','Rijkgevulde groentesoep gemaakt van wortels van Boer Jan, prei van Boer Martijn en tomaten van Boer Bas. Een voedzame soep gemaakt van verse Brabantse groenten.',3.99,NULL,'Boer Jan uit Oosterhout','per pot 750 ml'),
('Paprikasaus','Smaakvolle saus gemaakt van de paprikas van Boer Tom uit Rucphen. De paprikas worden geroosterd en gemengd met kruiden voor een zachte en lichtzoete saus.',3.29,NULL,'Boer Tom uit Rucphen','per pot 400 gram'),
('Appelstroop','Traditionele appelstroop gemaakt van de appels van Boer Dirk uit Uden. Door het langzaam inkoken ontstaat een dikke en rijke stroop met een intense appelsmaak.',2.79,NULL,'Boer Dirk uit Uden','per pot 300 gram'),
('Gedroogde appelchips','Knapperige appelchips gemaakt van Gala appels van Boer Dirk uit Uden. De appels worden dun gesneden en langzaam gedroogd voor een gezonde snack.',2.99,NULL,'Boer Dirk uit Uden','per zak 150 gram'),
('Gedroogde pruimen','Zoete gedroogde pruimen gemaakt van de pruimen van Boer Erik uit Hilvarenbeek. Door het drogen blijven de natuurlijke suikers en smaak goed behouden.',3.49,NULL,'Boer Erik uit Hilvarenbeek','per zak 200 gram'),
('Tomatenchutney','Hartige chutney gemaakt van de tomaten van Boer Bas uit Tilburg, gecombineerd met kruiden en een vleugje azijn. Heerlijk bij kaas, brood of vleesgerechten.',3.59,NULL,'Boer Bas uit Tilburg','per pot 300 gram'),
('Pompoenchutney','Zoetzure chutney gemaakt van pompoenen van Boer Henk uit Roosendaal. De pompoen wordt langzaam gekookt met specerijen voor een rijke smaak.',3.49,NULL,'Boer Henk uit Roosendaal','per pot 300 gram'),
('Groente pastasaus','Rijke pastasaus gemaakt van tomaten van Boer Bas, wortels van Boer Jan en paprikas van Boer Tom. Een volle saus vol Brabantse groenten, ideaal voor pasta.',3.69,NULL,'Boer Bas uit Tilburg','per pot 500 gram'),
('Zuurkool','Traditionele zuurkool gemaakt van witte kool van Boer Anton uit Dinteloord. De kool wordt op natuurlijke wijze gefermenteerd waardoor een frisse en lichtzure smaak ontstaat.',2.99,NULL,'Boer Anton uit Dinteloord','per pot 500 gram'),
('Ingelegde komkommer','Knapperige ingelegde komkommers gemaakt van de komkommers van Boer Tom uit Rucphen. Ingelegd in een lichtzoete azijn met kruiden voor een frisse smaak.',3.29,NULL,'Boer Tom uit Rucphen','per pot 500 gram'),
('Bieten in het zuur','Biologische rode bieten van Boer Piet uit Langeweg, langzaam gekookt en ingelegd in een lichtzure marinade. Heerlijk bij salades en warme maaltijden.',3.19,NULL,'Boer Piet uit Langeweg','per pot 500 gram'),
('Appel-peren jam','Ambachtelijke jam gemaakt van Elstar appels van Boer Dirk en Conference peren van boerin Anja. Een heerlijke combinatie van frisse appel en zoete peer.',3.59,NULL,'Boer Dirk uit Uden','per pot 350 gram'),
('Courgette relish','Hartige relish gemaakt van courgettes van boerin Ellen uit Zevenbergen. Perfect als smaakmaker bij broodjes, burgers of kaasplankjes.',3.49,NULL,'Boer Ellen uit Zevenbergen','per pot 300 gram'),
('Paprika chutney','Zoetzure chutney gemaakt van paprikas van Boer Tom uit Rucphen. De paprikas worden langzaam gekookt met kruiden voor een volle smaak.',3.49,NULL,'Boer Tom uit Rucphen','per pot 300 gram'),
('Gedroogde aardbeien','Zoete gedroogde aardbeien gemaakt van aardbeien van Boer Koen uit Zundert. Door het drogen blijven de natuurlijke smaken geconcentreerd.',3.99,NULL,'Boer Koen uit Zundert','per zak 150 gram'),
('Frambozen jam','Ambachtelijke jam gemaakt van frambozen van boerin Maaike uit Chaam. Vol van smaak en perfect op brood of bij desserts.',3.79,NULL,'Boerin Maaike uit Chaam','per pot 350 gram'),
('Groente bouillon','Hartige bouillon gemaakt van wortels van Boer Jan, prei van Boer Martijn en selderij van Brabantse bodem. Ideaal als basis voor soepen en sauzen.',3.29,NULL,'Boer Jan uit Oosterhout','per pot 500 ml'),
('Tomaten salsa','Pittige salsa gemaakt van tomaten van Boer Bas uit Tilburg. De tomaten worden gecombineerd met kruiden en specerijen voor een frisse en licht pittige saus.',3.49,NULL,'Boer Bas uit Tilburg','per pot 300 gram');


-- ============================================================
-- CATEGORIE
-- ============================================================
INSERT INTO `categorie` (`categorie`, `omschrijving`)
VALUES
('Groenten', 'Verse biologische groenten rechtstreeks van lokale boeren uit Noord-Brabant.'),
('Fruit', 'Biologisch fruit uit Brabantse boomgaarden en kassen.'),
('Langer Houdbare producten', 'De langer houdbare producten, gemaakt van het groente en fruit wat overblijft bij onze biologische boeren!');


-- ============================================================
-- PRODUCT_CATEGORIE
-- ============================================================
INSERT INTO `product_categorie` (`categorie`, `product_id`)
VALUES
('Groenten', 1), ('Groenten', 2), ('Groenten', 3), ('Groenten', 4),
('Groenten', 5), ('Groenten', 6), ('Groenten', 7), ('Groenten', 8),
('Groenten', 9), ('Groenten', 10), ('Groenten', 11), ('Groenten', 12),
('Groenten', 13), ('Groenten', 14),
('Fruit', 15), ('Fruit', 16), ('Fruit', 17), ('Fruit', 18), ('Fruit', 19),
('Fruit', 20), ('Fruit', 21), ('Fruit', 22),
('Groenten', 23), ('Groenten', 24), ('Groenten', 25), ('Groenten', 26),
('Groenten', 27), ('Groenten', 28), ('Groenten', 29), ('Groenten', 30),
('Groenten', 31), ('Groenten', 32), ('Groenten', 33), ('Groenten', 34),
('Groenten', 35),
('Fruit', 36), ('Fruit', 37), ('Fruit', 38), ('Fruit', 39), ('Fruit', 40),
('Fruit', 41), ('Fruit', 42), ('Fruit', 43), ('Fruit', 44), ('Fruit', 45),
('Fruit', 46), ('Fruit', 47), ('Fruit', 48), ('Fruit', 49), ('Fruit', 50),
('Langer Houdbare producten', 51), ('Langer Houdbare producten', 52),
('Langer Houdbare producten', 53), ('Langer Houdbare producten', 54),
('Langer Houdbare producten', 55), ('Langer Houdbare producten', 56),
('Langer Houdbare producten', 57), ('Langer Houdbare producten', 58),
('Langer Houdbare producten', 59), ('Langer Houdbare producten', 60),
('Langer Houdbare producten', 61), ('Langer Houdbare producten', 62),
('Langer Houdbare producten', 63), ('Langer Houdbare producten', 64),
('Langer Houdbare producten', 65), ('Langer Houdbare producten', 66),
('Langer Houdbare producten', 67), ('Langer Houdbare producten', 68),
('Langer Houdbare producten', 69), ('Langer Houdbare producten', 70),
('Langer Houdbare producten', 71), ('Langer Houdbare producten', 72),
('Langer Houdbare producten', 73), ('Langer Houdbare producten', 74),
('Langer Houdbare producten', 75);


-- ============================================================
-- TAG
-- ============================================================
INSERT INTO `tag` (`tag`)
VALUES
('Winterproducten'), ('Herfstproducten'), ('Zomerproducten'), ('Lenteproducten'),
('Klasse 1'), ('Klasse 2'), ('Bladgroenten'), ('Knolgroenten'),
('Slagroenten'), ('Appels en Peren'), ('Steenfruit');


-- ============================================================
-- PRODUCT_TAG
-- ============================================================
INSERT INTO `product_tag` (`product_id`, `tag`)
VALUES
(1,'Knolgroenten'), (1,'Herfstproducten'), (1,'Klasse 1'),
(2,'Knolgroenten'),  (2,'Herfstproducten'),  (2,'Klasse 1'),
(4,'Bladgroenten'),  (4,'Winterproducten'),   (4,'Klasse 1'),
(6,'Slagroenten'),   (6,'Zomerproducten'),    (6,'Klasse 1'),
(7,'Lenteproducten'),(7,'Klasse 1'),
(8,'Slagroenten'),   (8,'Zomerproducten'),    (8,'Klasse 1'),
(9,'Herfstproducten'),(9,'Klasse 1'),
(10,'Bladgroenten'), (10,'Lenteproducten'),   (10,'Klasse 1'),
(11,'Winterproducten'),(11,'Klasse 1'),
(12,'Slagroenten'),  (12,'Zomerproducten'),   (12,'Klasse 1'),
(13,'Herfstproducten'),(13,'Klasse 1'),
(14,'Knolgroenten'), (14,'Herfstproducten'),  (14,'Klasse 1'),
(15,'Appels en Peren'),(15,'Herfstproducten'),(15,'Klasse 1'),
(16,'Appels en Peren'),(16,'Herfstproducten'),(16,'Klasse 1'),
(17,'Appels en Peren'),(17,'Herfstproducten'),(17,'Klasse 1'),
(18,'Appels en Peren'),(18,'Herfstproducten'),(18,'Klasse 1'),
(19,'Appels en Peren'),(19,'Herfstproducten'),(19,'Klasse 1'),
(20,'Zomerproducten'),(20,'Klasse 1'),
(21,'Zomerproducten'),(21,'Klasse 1'),
(22,'Steenfruit'),   (22,'Zomerproducten'),   (22,'Klasse 1'),
(26,'Herfstproducten'),(26,'Klasse 1'),
(27,'Herfstproducten'),(27,'Klasse 1'),
(28,'Herfstproducten'),(28,'Klasse 1'),
(29,'Bladgroenten'), (29,'Zomerproducten'),   (29,'Klasse 1'),
(30,'Slagroenten'),  (30,'Zomerproducten'),   (30,'Klasse 1'),
(31,'Slagroenten'),  (31,'Zomerproducten'),   (31,'Klasse 1'),
(32,'Bladgroenten'), (32,'Zomerproducten'),   (32,'Klasse 1'),
(33,'Bladgroenten'), (33,'Lenteproducten'),   (33,'Klasse 1'),
(35,'Knolgroenten'), (35,'Herfstproducten'),  (35,'Klasse 1'),
(36,'Zomerproducten'),(36,'Klasse 1'),
(37,'Zomerproducten'),(37,'Klasse 1'),
(38,'Steenfruit'),   (38,'Zomerproducten'),   (38,'Klasse 1'),
(39,'Steenfruit'),   (39,'Zomerproducten'),   (39,'Klasse 1'),
(40,'Steenfruit'),   (40,'Zomerproducten'),   (40,'Klasse 1'),
(41,'Steenfruit'),   (41,'Zomerproducten'),   (41,'Klasse 1'),
(42,'Zomerproducten'),(42,'Klasse 1'),
(43,'Zomerproducten'),(43,'Klasse 1'),
(44,'Zomerproducten'),(44,'Klasse 1'),
(45,'Herfstproducten'),(45,'Klasse 1'),
(46,'Appels en Peren'),(46,'Herfstproducten'),
(47,'Appels en Peren'),(47,'Herfstproducten'),
(48,'Winterproducten'),(48,'Klasse 1'),
(49,'Winterproducten'),(49,'Klasse 1'),
(50,'Herfstproducten'),(50,'Klasse 1'),
(51,'Herfstproducten'),(51,'Klasse 1'),
(52,'Herfstproducten'),(52,'Appels en Peren'),
(53,'Herfstproducten'),(53,'Appels en Peren'),
(54,'Herfstproducten'),(54,'Klasse 1'),
(55,'Zomerproducten'), (55,'Klasse 1'),
(56,'Zomerproducten'), (56,'Klasse 1'),
(57,'Zomerproducten'), (57,'Steenfruit'),
(58,'Winterproducten'),(58,'Klasse 1'),
(59,'Zomerproducten'), (59,'Klasse 1'),
(60,'Herfstproducten'),(60,'Appels en Peren'),
(61,'Herfstproducten'),(61,'Appels en Peren'),
(62,'Herfstproducten'),(62,'Steenfruit'),
(63,'Herfstproducten'),(63,'Klasse 1'),
(64,'Herfstproducten'),(64,'Klasse 1'),
(65,'Zomerproducten'), (65,'Klasse 1'),
(66,'Herfstproducten'),(66,'Klasse 1'),
(67,'Zomerproducten'), (67,'Klasse 1'),
(68,'Herfstproducten'),(68,'Klasse 1'),
(69,'Herfstproducten'),(69,'Appels en Peren'),
(70,'Zomerproducten'), (70,'Klasse 1'),
(71,'Zomerproducten'), (71,'Klasse 1'),
(72,'Zomerproducten'), (72,'Klasse 1'),
(73,'Zomerproducten'), (73,'Klasse 1'),
(74,'Winterproducten'),(74,'Klasse 1'),
(75,'Zomerproducten'), (75,'Klasse 1');


-- ============================================================
-- ACCOUNT
-- Accounts 1-12: klanten | Accounts 13-15: beheermedewerkers
-- ============================================================
INSERT INTO `account` (`email`, `gebruikersnaam`, `wachtwoord_hash`, `voornaam`, `achternaam`, `geboortedatum`, `telefoon`)
VALUES
('jan.vermeer@example.com',      'JVermeer',        '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA001', 'Jan',    'Vermeer',     '1985-03-21', '+31610000001'),
('sara.jansen@example.com',      'Saraatje1992',    '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA002', 'Sara',   'Jansen',      '1992-07-14', '+31610000002'),
('mark.devries@example.com',     'MVriezz',         '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA003', 'Mark',   'De Vries',    '1978-11-30', '+31610000003'),
('lisa.bakker@example.com',      'BakkerLiesje',    '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA004', 'Lisa',   'Bakker',      '1995-01-08', '+31610000004'),
('tom.smit@example.com',         'TommieTomaatje',  '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA005', 'Tom',    'Smit',        '1989-05-19', '+31610000005'),
('emma.meijer@example.com',      'EmmieMe',         '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA006', 'Emma',   'Meijer',      '1998-04-12', '+31610000006'),
('noah.mulder@example.com',      'CoolVeggies1991', '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA007', 'Noah',   'Mulder',      '1991-09-27', '+31610000007'),
('olivia.dejong@example.com',    'Oliviaa',         '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA008', 'Olivia', 'De Jong',     '1994-02-10', '+31610000008'),
('lucas.vandenberg@example.com', 'Lucass',          '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA009', 'Lucas',  'Van den Berg','1987-06-03', '+31610000009'),
('mia.prins@example.com',        'PrinsesMia',      '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA010', 'Mia',    'Prins',       '1996-12-18', '+31610000010'),
('daan.kok@example.com',         'Daantje',         '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA011', 'Daan',   'Kok',         '1983-08-07', '+31610000011'),
('zoe.bos@example.com',          'BoskabouterZoe',  '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA012', 'Zoë',   'Bos',         '2000-03-02', '+31610000012'),
('pieterwillems@example.com',    'PWillems',        '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA013', 'Peter',  'Willems',     '1975-10-11', '+31610000013'),
('mariekehendriks@example.com',  'MHendriks',       '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA014', 'Marieke','Hendriks',    '1982-07-22', '+31610000014'),
('jeroendijkstra@example.com',   'JDijkstra',       '$2b$12$AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA015', 'Jeroen', 'Dijkstra',    '1980-01-30', '+31610000015');


-- ============================================================
-- KLANT (accounts 1-12)
-- ============================================================
INSERT INTO `klant` (`account_id`, `startdatum_lidmaatschap`)
VALUES
(1, '2023-01-15'), (2, '2022-06-01'), (3, '2024-02-20'), (4, '2023-09-12'),
(5, '2021-11-03'), (6, '2024-01-10'), (7, '2022-04-25'), (8, '2023-07-30'),
(9, '2022-12-05'), (10,'2024-03-01'), (11,'2021-05-19'), (12,'2023-10-14');


-- ============================================================
-- BEHEER (accounts 13-15)
-- datum_in_dienst is verplicht (NOT NULL)
-- ============================================================
INSERT INTO `beheer` (`account_id`, `rol`, `datum_in_dienst`)
VALUES
(13, 'voorraadbeheerder', '2021-03-01'),
(14, 'voorraadbeheerder', '2022-06-15'),
(15, 'klantenservice',    '2023-01-10');


-- ============================================================
-- ADRES
-- Adressen 1-24: klantadressen | 25-27: afhaallocaties
-- ============================================================
INSERT INTO `adres` (`adres_id`, `straat`, `huisnummer`, `postcode`, `plaats`, `land`)
VALUES
(1, 'Wilhelminastraat',            '12',  '4818SH', 'Breda',       'Nederland'),
(2, 'Kerkstraat',                  '45',  '4701KS', 'Roosendaal',  'Nederland'),
(3, 'Markt',                       '8',   '4871CN', 'Etten-Leur',  'Nederland'),
(4, 'Schoolstraat',                '23',  '4901KB', 'Oosterhout',  'Nederland'),
(5, 'Dorpsstraat',                 '56',  '5121AZ', 'Rijen',       'Nederland'),
(6, 'Molenstraat',                 '14',  '4761BM', 'Zevenbergen', 'Nederland'),
(7, 'Hoofdstraat',                 '91',  '4921ER', 'Made',        'Nederland'),
(8, 'Stationsstraat',              '72',  '4702VN', 'Roosendaal',  'Nederland'),
(9, 'Lindelaan',                   '19',  '5122AJ', 'Rijen',       'Nederland'),
(10,'Beukenlaan',                  '4',   '4904BG', 'Oosterhout',  'Nederland'),
(11,'Kastanjelaan',                '33',  '4812VA', 'Breda',       'Nederland'),
(12,'Pastoor van Spaandonkstraat', '6',   '4872TL', 'Etten-Leur',  'Nederland'),
(13,'Ginnekenweg',                 '214', '4835NL', 'Breda',       'Nederland'),
(14,'Kade',                        '18',  '4703GA', 'Roosendaal',  'Nederland'),
(15,'Anna van Berchemlaan',        '44',  '4873BL', 'Etten-Leur',  'Nederland'),
(16,'Bredaseweg',                  '90',  '4902NS', 'Oosterhout',  'Nederland'),
(17,'Julianastraat',               '12',  '5121LP', 'Rijen',       'Nederland'),
(18,'Hazeldonkse Zandweg',         '7',   '4761PV', 'Zevenbergen', 'Nederland'),
(19,'Stuivezandsestraat',          '21',  '4921BB', 'Made',        'Nederland'),
(20,'Boulevard',                   '65',  '4701EV', 'Roosendaal',  'Nederland'),
(21,'Vreedestraat',                '29',  '5122BR', 'Rijen',       'Nederland'),
(22,'Arendshof',                   '11',  '4901LH', 'Oosterhout',  'Nederland'),
(23,'Teteringsedijk',              '133', '4817ME', 'Breda',       'Nederland'),
(24,'Kloosterlaan',                '58',  '4871TK', 'Etten-Leur',  'Nederland'),
-- Adressen voor afhaallocaties
(25,'Bosdreef',                    '3',   '4904RA', 'Oosterhout',  'Nederland'),
(26,'Industrieweg',                '18',  '4702AH', 'Roosendaal',  'Nederland'),
(27,'Kerklaan',                    '7',   '4871AE', 'Etten-Leur',  'Nederland');


-- ============================================================
-- KLANT_ADRES
-- Elke klant heeft precies één 'primair' adres.
-- type ENUM: 'prive', 'werk', 'primair'
-- ============================================================
INSERT INTO `klant_adres` (`account_id`, `adres_id`, `type`)
VALUES
(1, 1,  'primair'),
(1, 11, 'werk'),
(1, 13, 'prive'),
(2, 2,  'primair'),
(3, 3,  'primair'),
(3, 12, 'prive'),
(4, 4,  'primair'),
(4, 10, 'werk'),
(4, 22, 'prive'),
(5, 5,  'primair'),
(6, 6,  'primair'),
(6, 18, 'prive'),
(7, 7,  'primair'),
(7, 19, 'werk'),
(7, 21, 'prive'),
(8, 8,  'primair'),
(9, 9,  'primair'),
(9, 17, 'prive'),
(10,10, 'primair'),
(10,16, 'werk'),
(10,20, 'prive'),
(11,23, 'primair'),
(12,24, 'primair'),
(12,14, 'prive');


-- ============================================================
-- AFHAAL_LOCATIE
-- ============================================================
INSERT INTO `afhaal_locatie` (`naam`, `type`, `adres_id`)
VALUES
('Boerderij Boer Jan',   'boer',        25),
('Magazijn Roosendaal',  'magazijn',    26),
('Vrijwilligerspunt Etten-Leur', 'vrijwilliger', 27);


-- ============================================================
-- WINKELWAGEN
-- Elke klant heeft één winkelwagen. Hier vier klanten met items.
-- ============================================================
INSERT INTO `winkelwagen` (`winkelwagen_id`, `account_id`)
VALUES
(1, 1),  -- Jan
(2, 2),  -- Sara
(3, 3),  -- Mark
(4, 4);  -- Lisa


-- ============================================================
-- WINKELWAGEN_REGEL
-- Gekoppeld aan winkelwagen via winkelwagen_id (niet account_id).
-- ============================================================
INSERT INTO `winkelwagen_regel` (`winkelwagen_id`, `product_id`, `aantal`)
VALUES
(1, 3,  1),  -- Jan: Aubergine
(1, 10, 2),  -- Jan: Spinazie
(2, 5,  1),  -- Sara: Champignons
(2, 55, 1),  -- Sara: Aardbeienjam
(3, 8,  2),  -- Mark: Paprika
(4, 14, 1),  -- Lisa: Zoete aardappel
(4, 52, 1);  -- Lisa: Appelmoes


-- ============================================================
-- BESTELLING
-- Bestellingen 1-8: bezorgen (adres_id ingevuld, afhaal_locatie_id NULL)
-- Bestellingen 9-10: afhalen (afhaal_locatie_id ingevuld, adres_id NULL)
-- ============================================================
INSERT INTO `bestelling` (`bestelling_id`, `account_id`, `bestel_datum`, `bezorg_kosten`, `totaal_prijs_bestelling`, `status`, `adres_id`, `afhaal_locatie_id`, `verzendmethode`)
VALUES
(1,  1,  '2026-03-10', 2.99, 13.73, 'geleverd',       1,    NULL, 'bezorgen'),
(2,  2,  '2026-03-10', 2.99, 10.95, 'geleverd',       2,    NULL, 'bezorgen'),
(3,  3,  '2026-03-11', 2.99, 17.04, 'verzonden',      3,    NULL, 'bezorgen'),
(4,  4,  '2026-03-11', 2.99,  8.46, 'geleverd',       4,    NULL, 'bezorgen'),
(5,  5,  '2026-03-12', 2.99, 16.84, 'in behandeling', 5,    NULL, 'bezorgen'),
(6,  6,  '2026-03-12', 2.99, 12.75, 'geleverd',       6,    NULL, 'bezorgen'),
(7,  7,  '2026-03-13', 2.99, 14.24, 'verzonden',      7,    NULL, 'bezorgen'),
(8,  8,  '2026-03-13', 2.99, 17.95, 'in behandeling', 8,    NULL, 'bezorgen'),
(9,  9,  '2026-03-14', 0.00,  9.76, 'geannuleerd',    NULL, 1,   'afhalen'),
(10, 10, '2026-03-14', 0.00, 13.05, 'in behandeling', NULL, 2,   'afhalen');


-- ============================================================
-- BESTEL_REGEL
-- ============================================================
INSERT INTO `bestel_regel` (`bestel_regel_id`, `bestelling_id`, `product_id`, `aantal`, `prijs_per_stuk`)
VALUES
-- Bestelling 1
(1,  1, 1,  2, 1.99),  -- Wortel
(2,  1, 2,  2, 1.49),  -- Aardappel
(3,  1, 13, 1, 1.29),  -- Ui
(4,  1, 15, 1, 2.49),  -- Elstar appel
-- Bestelling 2
(5,  2, 6,  1, 2.49),  -- Cherry tomaat
(6,  2, 30, 1, 1.39),  -- Komkommer
(7,  2, 29, 1, 1.29),  -- Sla
(8,  2, 18, 1, 2.79),  -- Peer
-- Bestelling 3
(9,  3, 9,  1, 3.49),  -- Pompoen
(10, 3, 8,  2, 2.79),  -- Paprika
(11, 3, 5,  1, 1.49),  -- Champignons
(12, 3, 51, 1, 3.49),  -- Tomatensaus
-- Bestelling 4
(13, 4, 10, 1, 1.99),  -- Spinazie
(14, 4, 23, 1, 2.19),  -- Broccoli
(15, 4, 13, 1, 1.29),  -- Ui
-- Bestelling 5
(16, 5, 14, 2, 2.99),  -- Zoete aardappel
(17, 5, 26, 1, 2.29),  -- Rode kool
(18, 5, 52, 1, 2.99),  -- Appelmoes
(19, 5, 17, 1, 2.59),  -- Gala appel
-- Bestelling 6
(20, 6, 7,  1, 3.99),  -- Asperges
(21, 6, 12, 1, 2.59),  -- Tomaten
(22, 6, 31, 1, 1.49),  -- Radijs
(23, 6, 32, 1, 1.69),  -- Rucola
-- Bestelling 7
(24, 7, 4,  1, 2.09),  -- Boerenkool
(25, 7, 2,  2, 1.49),  -- Aardappel
(26, 7, 11, 1, 2.39),  -- Spruiten
(27, 7, 54, 1, 3.79),  -- Pompoensoep
-- Bestelling 8
(28, 8, 20, 1, 3.49),  -- Aardbeien
(29, 8, 21, 1, 3.99),  -- Blauwe bessen
(30, 8, 36, 1, 3.99),  -- Frambozen
(31, 8, 55, 1, 3.49),  -- Aardbeienjam
-- Bestelling 9
(32, 9, 24, 1, 2.49),  -- Bloemkool
(33, 9, 25, 1, 1.89),  -- Courgette
(34, 9, 28, 1, 1.79),  -- Prei
(35, 9, 63, 1, 3.59),  -- Tomatenchutney
-- Bestelling 10
(36, 10, 15, 2, 2.49), -- Elstar appel
(37, 10, 18, 1, 2.79), -- Peer
(38, 10, 49, 1, 2.49), -- Limoen
(39, 10, 60, 1, 2.79); -- Appelstroop


-- ============================================================
-- LOG_REGEL
-- FK verwijst naar beheer: alleen account_ids 13, 14, 15 zijn geldig.
-- Kolomnaam is log_regel (niet log_tekst).
-- ============================================================
INSERT INTO `log_regel` (`account_id`, `log_regel`, `soort_log`)
VALUES
(13, 'Medewerker ingelogd',                           'inlog'),
(13, 'Voorraad bijgewerkt voor product 1 (Wortel)',   'actie'),
(13, 'Voorraad bijgewerkt voor product 5 (Champignons)', 'actie'),
(14, 'Medewerker ingelogd',                           'inlog'),
(14, 'Nieuw product toegevoegd',                      'actie'),
(14, 'Product 3 (Aubergine) bijgewerkt',              'actie'),
(15, 'Klantenservice ingelogd',                       'inlog'),
(15, 'Contactformulier afgehandeld van Jan Vermeer',  'actie'),
(15, 'Bestelling 9 geannuleerd op verzoek klant',     'actie'),
(13, 'Mislukte inlogpoging: ongeldig wachtwoord',     'fout');


-- ============================================================
-- CONTACT_FORMULIER
-- account_id is nullable: ook niet-ingelogde bezoekers kunnen contact opnemen.
-- ============================================================
INSERT INTO `contact_formulier` (`naam`, `email`, `bericht`, `account_id`)
VALUES
('Jan Vermeer',  'jan.vermeer@example.com', 'Ik had een vraag over mijn bestelling 1, wanneer wordt die geleverd?', 1),
('Sara Jansen',  'sara.jansen@example.com', 'Zijn er ook seizoensabonnementen mogelijk?', 2),
('Onbekende bezoeker', 'bezoeker@example.com', 'Hoe kan ik mij aanmelden als vrijwilligerspunt voor een afhaallocatie?', NULL),
('Mark de Vries','mark.devries@example.com', 'Ik zou graag weten of jullie ook bulk orders accepteren voor horeca.', 3);


-- ============================================================
-- ZOEKTERM
-- Meest gezochte termen op het platform.
-- ============================================================
INSERT INTO `zoekterm` (`zoekterm`, `aantal`)
VALUES
('wortel',           42),
('aardappel',        38),
('appel',            31),
('tomaat',           27),
('spinazie',         24),
('biologisch',       19),
('jam',              17),
('champignons',      15),
('pompoen',          13),
('asperges',         11);
