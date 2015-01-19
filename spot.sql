DROP TABLE IF EXISTS spot_Texts;
DROP TABLE IF EXISTS spot_Users;


DROP TABLE IF EXISTS spot_Users;

CREATE TABLE spot_Users (
  userId INT AUTO_INCREMENT PRIMARY KEY,
  acronym VARCHAR(20) UNIQUE,
  name VARCHAR(80),
  email VARCHAR(254),
  password VARCHAR(255)
);

INSERT INTO spot_Users (acronym, name, email, password) VALUES 
  ('marcus', 'Marcus Törnroth', 'm@rcus.se', '$2y$10$X8PgR1uU9coCfok/25odueLeAtMXWB.ryhqc8ZvYXwnSsD7O8mIEW'),
  ('john', 'John Doe', 'john@doe.com', '$2y$10$LLX8ShbWxzpuQ/sgRaUNPemcjiTVG0Kg/LNdeqAFY.zjdwaq2.XuS'),
  ('jane', 'Jane Doe', 'jane@doe.com', '$2y$10$pAx6Rfz2hmqzk1N2SUsKRuoS21aqOw6WZiFqoHKpClZd7f8RSEdhS')
;


DROP TABLE IF EXISTS spot_Texts;
CREATE TABLE spot_Texts (
  textId INT AUTO_INCREMENT PRIMARY KEY,
  text TEXT,
  author INT NOT NULL,
  FOREIGN KEY (author) REFERENCES spot_Users (userId)
);


