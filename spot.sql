DROP TABLE IF EXISTS spot_cquestions;
DROP TABLE IF EXISTS spot_cusers;

DROP TABLE IF EXISTS spot_cusers;
CREATE TABLE spot_cusers (
  id INT AUTO_INCREMENT PRIMARY KEY,
  acronym VARCHAR(20) UNIQUE,
  name VARCHAR(80),
  email VARCHAR(254),
  password VARCHAR(255),
  created TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO spot_cusers (acronym, name, email, password) VALUES 
  ('marcus', 'Marcus Törnroth', 'm@rcus.se', '$2y$10$X8PgR1uU9coCfok/25odueLeAtMXWB.ryhqc8ZvYXwnSsD7O8mIEW'),
  ('john', 'John Doe', 'john@doe.com', '$2y$10$LLX8ShbWxzpuQ/sgRaUNPemcjiTVG0Kg/LNdeqAFY.zjdwaq2.XuS'),
  ('jane', 'Jane Doe', 'jane@doe.com', '$2y$10$pAx6Rfz2hmqzk1N2SUsKRuoS21aqOw6WZiFqoHKpClZd7f8RSEdhS')
;

DROP TABLE IF EXISTS spot_cquestions;
CREATE TABLE spot_cquestions (
  id INT AUTO_INCREMENT PRIMARY KEY,
  qNo INT NOT NULL,
  commentTo INT,
  type CHAR(1) NOT NULL,
  authorId INT NOT NULL,
  title VARCHAR(80),
  text TEXT,
  created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (qNo) REFERENCES spot_cquestions (id),
  FOREIGN KEY (authorId) REFERENCES spot_cusers (id)
);
-- Some questions
INSERT INTO spot_cquestions (qNo, type, authorId, title, text) VALUES
  (1, 'Q', 2, 'Hur gör jag för att skapa en ny spellista?', 'Jag vet inte riktigt hur jag skapar en ny spellista, hur gör jag smidigast?'),
  (2, 'Q', 3, 'Hur döljer jag mina spellistor för vänner?', 'En del vänner hånar mig för att jag lyssnar på Electric Banana Band. Jag vill inte sluta med det, så hur gör jag för att dölja mina spellistor?'),
  (3, 'Q', 5, 'Kan jag lyssna på musik på flera enheter samtidigt?', 'Jag har Spotify till dator, surfplatta, stereo och mobilen. Kan jag lyssna på dem samtidigt?')
;

-- Some answers
INSERT INTO spot_cquestions (qNo, type, authorId, text) VALUES
  (1, 'A', 4, 'Du klickar på "+ Ny spellista" i fältet till vänster. Svårare än så är det inte...'),
  (3, 'A', 6, 'Det går inte, Spotify tillåter inte att man använder flera enheter samtidigt.'),
  (2, 'A', 4, 'Högerklicka på listan och välj "Gör hemlig". Enkelt!'),
  (1, 'A', 6, 'Du trycker CTRL + N. Lycka till!')
;

-- Some comments
INSERT INTO spot_cquestions (qNo, commentTo, type, authorId, text) VALUES
  (2, 2, 'C', 2, 'Det gör väl inget att det syns vad du lyssnar på?! Varför vara anonym?'),
  (3, 5, 'C', 7, 'Jo, det tror jag. Jag känner en som säger att det går, men jag vet inte hur hon gör. Jag får ta reda på det.'),
  (3, 5, 'C', 3, '"Jag känner en som ..." Hehe, det brukar låta så :)')
;

CREATE TABLE spot_tags (
  id INT NOT NULL AUTO_INCREMENT,
  tag VARCHAR(45) NOT NULL,
  PRIMARY KEY (id));
  
-- Add some tags
INSERT INTO spot_tags (tag) VALUES
  ('premium'), ('spellista'), ('nyhet'), ('app'), ('desktop'), ('webbläsare');

-- Connect tags with questions
INSERT INTO spot_tagged (qNo, tagId) VALUES
  (1, 1), (1, 2), (2, 3), (2, 5), (2, 6), (3, 2), (3, 3), (3, 4);