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
  authorId INT NOT NULL,
  slug VARCHAR(80) UNIQUE NOT NULL,
  title VARCHAR(80) NOT NULL,
  text TEXT,
  created TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (authorId) REFERENCES spot_cusers (id)
);
INSERT INTO spot_cquestions (authorId, slug, title, text) VALUES
  (2, 'hur-gor-jag-for-att-skapa-en-ny-spellista', 'Hur gör jag för att skapa en ny spellista?', 'Jag vet inte riktigt hur jag skapar en ny spellista, hur gör jag smidigast?')
;


