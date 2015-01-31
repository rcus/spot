<?php

namespace Rcus\Setup;
 
/**
 * Model for setup.
 *
 */
class CSetup extends \Mos\Database\CDatabaseBasic
{

    /**
     * Constructor, make the database connection and call create methods.
     *
     * @return void
     */
    public function __construct($options = []) {
        parent::__construct($options);
        $this->connect();
        $this->createTables();
        $this->createViews();
    }


    /**
     * Create tables
     *
     * @return void
     */
    public function createTables() {
        // Drop all tables, if they exist
        $this->dropTables();

        // Create table for users
        $sql = "CREATE TABLE `spot_cusers` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `acronym` varchar(20) DEFAULT NULL,
                    `name` varchar(80) DEFAULT NULL,
                    `email` varchar(254) DEFAULT NULL,
                    `password` varchar(255) DEFAULT NULL,
                    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (`id`),
                    UNIQUE KEY `acronym` (`acronym`)
                );";

        // Create table for questions and other texts
        $sql .= "CREATE TABLE `spot_cquestions` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `qNo` int(11) DEFAULT NULL,
                    `commentTo` int(11) DEFAULT NULL,
                    `type` char(1) NOT NULL,
                    `authorId` int(11) NOT NULL,
                    `title` varchar(80) DEFAULT NULL,
                    `text` text,
                    `created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                    `edited` timestamp NULL DEFAULT NULL,
                    PRIMARY KEY (`id`),
                    KEY `authorId` (`authorId`),
                    FOREIGN KEY (`authorId`) REFERENCES `spot_cusers` (`id`)
                );";

        // Create table for tags
        $sql .= "CREATE TABLE `spot_tags` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `tag` varchar(45) NOT NULL,
                    PRIMARY KEY (`id`)
                );";

        // Create table to connect tags and questions
        $sql .= "CREATE TABLE `spot_tagged` (
                    `qNo` int(11) NOT NULL,
                    `tagId` int(11) NOT NULL,
                    UNIQUE KEY `tagged_index` (`qNo`,`tagId`)
                );";

        // Finally, execute all
        $this->execute($sql);
    }


    /**
     * Drop tables
     *
     * @return void
     */
    public function dropTables() {
        // Drop all tables, if they exist
        $sql = "DROP TABLE IF EXISTS `spot_tagged`;";
        $sql .= "DROP TABLE IF EXISTS `spot_tags`;";
        $sql .= "DROP TABLE IF EXISTS `spot_cquestions`;";
        $sql .= "DROP TABLE IF EXISTS `spot_cusers`;";

        // Execute sql
        $this->execute($sql);
    }


    /**
     * Create views
     *
     * @return void
     */
    public function createViews() {
        // Drop all views, if they exist
        $this->dropViews();

        // View for all info about a question
        $sql = "CREATE VIEW `spot_VInfo` AS
                SELECT
                    `spot_cquestions`.`id` AS `id`,
                    `spot_cquestions`.`qNo` AS `qNo`,
                    `spot_cquestions`.`commentTo` AS `commentTo`,
                    `spot_cquestions`.`type` AS `type`,
                    `spot_cquestions`.`authorId` AS `authorId`,
                    `spot_cquestions`.`title` AS `title`,
                    `spot_cquestions`.`text` AS `text`,
                    `spot_cquestions`.`created` AS `created`,
                    `spot_cquestions`.`edited` AS `edited`,
                    `spot_cusers`.`acronym` AS `acronym`,
                    `spot_cusers`.`name` AS `name`,
                    `spot_cusers`.`email` AS `email`
                FROM `spot_cquestions`
                    LEFT JOIN `spot_cusers`
                    ON `spot_cquestions`.`authorId` = `spot_cusers`.`id`;";

        // View for tags
        $sql .= "CREATE VIEW `spot_VTagged` AS
                SELECT
                    `spot_tagged`.`qNo` AS `qNo`,
                    `spot_tagged`.`tagId` AS `tagId`,
                    `spot_tags`.`tag` AS `tag`
                FROM `spot_tagged`
                    LEFT JOIN `spot_tags`
                    ON `spot_tagged`.`tagId` = `spot_tags`.`id`;";

        // View for connected tags and questions
        $sql .= "CREATE VIEW `spot_VTaggedInfo` AS
                SELECT
                    `spot_cquestions`.`id` AS `id`,
                    `spot_cquestions`.`title` AS `title`,
                    `spot_cquestions`.`created` AS `created`,
                    `spot_cquestions`.`edited` AS `edited`,
                    `spot_tagged`.`tagId` AS `tagId`,
                    `spot_cusers`.`acronym` AS `acronym`,
                    `spot_cusers`.`name` AS `name`,
                    `spot_cusers`.`email` AS `email`
                FROM (`spot_tagged`
                    LEFT JOIN (`spot_cquestions`
                        LEFT JOIN `spot_cusers`
                        ON (`spot_cquestions`.`authorId` = `spot_cusers`.`id`))
                    ON (`spot_cquestions`.`id` = `spot_tagged`.`qNo`));";

        // Execute all
        $this->execute($sql);
    }


    /**
     * Drop views
     *
     * @return void
     */
    public function dropViews() {
        // Drop all views, if they exist
        $sql = "DROP VIEW IF EXISTS `spot_VInfo`;";
        $sql .= "DROP VIEW IF EXISTS `spot_VTagged`;";
        $sql .= "DROP VIEW IF EXISTS `spot_VTaggedInfo`;";

        // Execute sql
        $this->execute($sql);
    }


    /**
     * Add some demodata
     *
     * @return void
     */
    public function addDemo() {
        // Some users
        $sql = "INSERT INTO `spot_cusers` VALUES
            (1,'marcus','Marcus Törnroth','m@rcus.se','\$2y\$10\$ZfSPu44v.cGG4kH/uYrlOukjE09v1ZoDZLWf5FkhGyfnreSYBwXk.','2015-01-19 11:15:26'),
            (2,'john','John Doe','john@doe.com','\$2y\$10\$LLX8ShbWxzpuQ/sgRaUNPemcjiTVG0Kg/LNdeqAFY.zjdwaq2.XuS','2015-01-19 11:15:26'),
            (3,'jane','Jane Doe','jane@doe.com','\$2y\$10\$pAx6Rfz2hmqzk1N2SUsKRuoS21aqOw6WZiFqoHKpClZd7f8RSEdhS','2015-01-19 11:15:26'),
            (4,'anna','Anna Asp','anna@vettig.se','\$2y\$10\$OalTSHRQv1Sbu2l3NEFHuevoom4ibc8u.2rPvVESeQnYD1nLaPGHm','2015-01-19 11:23:45'),
            (5,'bengt','Bengt Björk','bengt@vettig.se','\$2y\$10\$eAo9pP3i9ZwuVaftpFNtWecMNkVqgPJWVY0EgH/nbB7iD72fAK39S','2015-01-19 11:26:10'),
            (6,'cecilia','Cecilia Ceder','cecilia@vettig.se','\$2y\$10\$e7G7YxMudnhiANJYBi0ga.9IDAS5JekFVHnB1FqUEUuR7CqeAwBVm','2015-01-20 07:46:41'),
            (7,'david','David Dahl','david@vettig.se','\$2y\$10\$97HkTRhTkZRju6Xdt6Y2vONAG2tnHzrAZ/1YU0wKydJzlGp9Axh/G','2015-01-20 07:48:13');";

        // Some texts
        $sql .= "INSERT INTO `spot_cquestions` VALUES
            (1,1,NULL,'Q',2,'Hur gör jag för att skapa en ny spellista?','Jag vet inte _riktigt_ hur jag *skapar* en ny spellista, hur gör jag smidigast?','2015-01-20 13:20:28',NULL),
            (2,2,NULL,'Q',3,'Hur döljer jag mina spellistor för vänner?','En del vänner hånar mig för att jag lyssnar på Electric Banana Band. Jag vill inte sluta med det, så hur gör jag för att dölja mina spellistor?','2015-01-20 14:00:36',NULL),
            (3,3,NULL,'Q',5,'Kan jag lyssna på musik på flera enheter samtidigt?','Jag har Spotify till dator, surfplatta, stereo och mobilen. Kan jag lyssna på dem samtidigt?','2015-01-20 15:04:45',NULL),
            (4,1,NULL,'A',4,NULL,'Du klickar på \"+ Ny spellista\" i fältet till vänster. Svårare än så är det inte...','2015-01-20 16:22:52',NULL),
            (5,3,NULL,'A',6,NULL,'Det går inte, Spotify tillåter inte att man använder flera enheter samtidigt.','2015-01-21 15:14:08',NULL),
            (6,2,NULL,'A',4,NULL,'Högerklicka på listan och välj \"Gör hemlig\". Enkelt!','2015-01-21 15:18:57',NULL),
            (7,1,NULL,'A',6,NULL,'Du trycker CTRL + N. Lycka till!','2015-01-21 15:22:52',NULL),
            (8,2,2,'C',2,NULL,'Det gör väl inget att det syns vad du lyssnar på?! Varför vara anonym?','2015-01-22 09:51:36',NULL),
            (9,3,5,'C',7,NULL,'Jo, det tror jag. Jag känner en som säger att det går, men jag vet inte hur hon gör. Jag får ta reda på det.','2015-01-22 10:05:41',NULL),
            (10,3,5,'C',3,NULL,'\"Jag känner en som ...\" Hehe, det brukar låta så :)','2015-01-22 10:25:11',NULL),
            (11,3,NULL,'A',1,NULL,'Visst kan man det!','2015-01-29 22:44:32',NULL),
            (12,12,NULL,'Q',4,'Nu med markdown, eller?','### Elegant!\r\n\r\n    Det finns stunder som jag gillar mer...\r\n    -marcus\r\n\r\nFrågan är enkel: *Fungerar det med **Markdown** nu?*','2015-01-30 10:43:01',NULL),
            (13,12,12,'C',2,NULL,'Är det *så viktigt?*','2015-01-30 10:44:04',NULL),
            (14,12,NULL,'A',1,NULL,'# Superhärligt!!\r\n### Markdown\r\nVisst **fungerar** det!','2015-01-30 11:05:58',NULL),
            (15,12,14,'C',6,NULL,'Vad säger man? *Wow!*','2015-01-30 11:06:21',NULL),
            (16,16,NULL,'Q',6,'Kan man styra Spotify i mobilen från datorn?','Med Spotify Connect kan man ju styra Spotify i datorn med mobilappen. Men går det att göra tvärtom?','2015-01-30 11:13:33',NULL);";

        // Some tags
        $sql .= "INSERT INTO `spot_tags` VALUES
            (1,'premium'),
            (2,'spellista'),
            (3,'nyhet'),
            (4,'app'),
            (5,'desktop'),
            (6,'web player'),
            (7,'android'),
            (8,'ios'),
            (9,'radio'),
            (10,'connect');";

        // Some tagconnections
        $sql .= "INSERT INTO `spot_tagged` VALUES
            (1,1),
            (1,2),
            (2,3),
            (2,5),
            (2,6),
            (3,2),
            (3,3),
            (3,4),
            (12,3),
            (16,4),
            (16,5),
            (16,10);";

        // Execute sql
        $this->execute($sql);
    }
}