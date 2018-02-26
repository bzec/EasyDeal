Drop database BDD;
create database BDD;
USE BDD;
DROP TABLE IF EXISTS Concerne,Produits,OrigineProduits,TypeProduits,Commande,Dates,Utilisateur,Entreprise,LocalisationEntreprise,TypeEntreprise,Avis;

CREATE TABLE Avis(
	id_avis int(50) AUTO_INCREMENT NOT NULL,
	note_avis int(50),
	PRIMARY KEY (id_avis)
)DEFAULT CHARSET=utf8;

INSERT INTO Avis (id_avis, note_avis)
 VALUES
 (1 ,5),
 (2 ,6),
 (3 ,10),
 (4 ,2);

CREATE TABLE TypeEntreprise(
	id_typeEntreprise int AUTO_INCREMENT NOT NULL,
	libelle_typeEntreprise varchar(255),
	PRIMARY KEY (id_typeEntreprise)
)DEFAULT CHARSET=utf8;

INSERT INTO TypeEntreprise (id_typeEntreprise, libelle_typeEntreprise)
 VALUES
 (NULL ,'universite'),
 (NULL ,'PME');

CREATE TABLE LocalisationEntreprise(
	id_localisation int AUTO_INCREMENT NOT NULL,
	adresse varchar(255),
	latitude float (6,3),
	longitude float (6,3),
	PRIMARY KEY (id_localisation)
)DEFAULT CHARSET=utf8;

INSERT INTO LocalisationEntreprise (id_localisation, adresse,latitude, longitude)
 VALUES
 (1 ,'45 Rue de Mulhouse, 90000 Belfort', 47.6438709,6.850686099999962),
 (2 ,'19 Avenue du Maréchal Juin, 90000 Belfort, France',47.6443353,6.8384465999999975);

CREATE TABLE Entreprise(
	id_entreprise	int AUTO_INCREMENT NOT NULL,
	libelle_entreprise varchar(255),
	id_localisation int,
	id_typeEntreprise int,
	id_avis int,
	siren varchar(255),
	PRIMARY KEY (id_entreprise),
	CONSTRAINT fk_LocalisatioinEntreprise_Entreprise FOREIGN KEY (id_localisation) REFERENCES LocalisationEntreprise(id_localisation),
	CONSTRAINT fk_LocalisatioinEntreprise_Entreprise FOREIGN KEY (id_localisation) REFERENCES LocalisationEntreprise(id_localisation),
	CONSTRAINT fk_Type_Entreprise FOREIGN KEY (id_typeEntreprise) REFERENCES TypeEntreprise(id_typeEntreprise),
	CONSTRAINT fk_Avis_Entreprise FOREIGN KEY (id_avis) REFERENCES Avis(id_avis)
)DEFAULT CHARSET=utf8;

INSERT INTO Entreprise (id_entreprise, libelle_entreprise,id_localisation, id_typeEntreprise,id_avis)
 VALUES
 (1 ,'iut belfort', 2,1,2),
 (2 ,'Boulangerie du coin',1,2,1);

CREATE TABLE Utilisateur(
	id_utilisateur int  auto_increment not NULL ,
	sexe_utilisateur varchar(255),
	nom_utilisateur varchar(255),
	prenom_utilisateur varchar(255),
	adresse_utilisateur varchar(255),
	adresseMail_utilisateur VARCHAR(255),
	password_utilisateur varchar(255),
	ville varchar(255),
	droit varchar(255),
	id_entreprise int,
	PRIMARY KEY (id_utilisateur),
	CONSTRAINT fk_Entreprise_Utilisateur FOREIGN KEY (id_entreprise) REFERENCES Entreprise(id_entreprise)
)DEFAULT CHARSET=utf8;

/*
algo de cryptga md5 et mot de passe de 1 : client et du 2 :vendeur , mot de passe de 3:admin
 */
INSERT INTO Utilisateur (id_utilisateur, sexe_utilisateur,nom_utilisateur, prenom_utilisateur,adresse_utilisateur,adresseMail_utilisateur,password_utilisateur,ville,droit,id_entreprise)
 VALUES
 ( 1,'Homme','Sanchez','Pedro','2 rue chikita','pedro@gmail.com','62608e08adc29a8d6dbc9754e659f125','Mexico','Droit_Client',1),
 ( 3,'','admin','admin','','admin@gmail.com','21232f297a57a5a743894a0e4a801fc3','','Droit_Admin',0),
 ( 2 ,'Femme','Irina','Domohov','5 rue de moscou','irina@gmail.com','34fdd771c0b05faaf5f16b3b0ea12702','Moscva','Droit_Vendeur',2);



CREATE TABLE Commande(
	id_commande int AUTO_INCREMENT NOT NULL,
	libelle_commande varchar(255),
	prix_commande int,
	dates DateTime,
	id_utilisateur int,
	PRIMARY KEY(id_commande),
	CONSTRAINT fk_Utilisateur_Commande FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur)
)DEFAULT CHARSET=utf8;

INSERT INTO Commande (id_commande, libelle_commande,prix_commande,dates,id_utilisateur)
 VALUES
 (1 ,'commande1',5,NOW(),1),
 (2 ,'commande2',10,NOW(),1);

CREATE TABLE TypeProduits(
	id_typeProduits int AUTO_INCREMENT NOT NULL,
	libelle_typeProduits varchar(255),
	PRIMARY KEY (id_typeProduits)
)DEFAULT CHARSET=utf8;

INSERT INTO TypeProduits (id_typeProduits, libelle_typeProduits)
 VALUES
 (1 ,'Patisserie'),
 (2 ,'Pizza');

CREATE TABLE OrigineProduits(
	id_origineProduits int AUTO_INCREMENT NOT NULL,
	libelle_origineProduits varchar(255),
	PRIMARY KEY (id_origineProduits)
)DEFAULT CHARSET=utf8;

INSERT INTO OrigineProduits (id_origineProduits, libelle_origineProduits)
 VALUES
 (1 ,'FR'),
 (2 ,'IT');

CREATE TABLE Produits(
	id_produits int AUTO_INCREMENT NOT NULL,
	libelle_produits varchar(255),
	prix_produits float (4,2),
	heure_fin_vente DateTime,
	stockProduit int,
	id_typeProduits int,
	id_origineProduits int,
	id_utilisateur int,
	image varchar(255),
	PRIMARY KEY (id_produits),
	CONSTRAINT fk_typeProduits_Produits FOREIGN KEY (id_typeProduits) REFERENCES TypeProduits(id_typeProduits),
	CONSTRAINT fk_utilisateur_Produits FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur),
	CONSTRAINT fk_origineProduits_Produits FOREIGN KEY (id_origineProduits) REFERENCES OrigineProduits(id_origineProduits)
)DEFAULT CHARSET=utf8;

INSERT INTO Produits (id_produits, libelle_produits,prix_produits,heure_fin_vente,stockProduit,id_typeProduits,id_origineProduits,id_utilisateur)
 VALUES
 (1 ,'Baguette',1,NULL ,5,1,1,2),
 (2 ,'Pizza raclette',5,NULL ,2,2,2,2);

CREATE TABLE Concerne (
	id_commande int,
	id_produits int,
	quantite int,
	PRIMARY KEY(id_commande,id_produits),
	CONSTRAINT fk_Produit_Concerne FOREIGN KEY (id_produits) REFERENCES Produits(id_produits),
	CONSTRAINT fk_Commande_Concerne FOREIGN KEY (id_commande) REFERENCES Commande(id_commande)
)DEFAULT CHARSET=utf8;

INSERT INTO Concerne (id_commande, id_produits,	quantite)
 VALUES
 (2 ,1,1),
 (1 ,2,1);