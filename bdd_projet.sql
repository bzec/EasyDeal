USE BDD;
DROP TABLE IF EXISTS Concerne,Produits,OrigineProduits,TypeProduits,Commande,Dates,Utilisateur,Entreprise,LocalisationEntreprise,TypeEntreprise,Avis;

CREATE TABLE Avis(
	id_avis int(50) AUTO_INCREMENT NOT NULL,
	note_avis int(50),
	PRIMARY KEY (id_avis)
)DEFAULT CHARSET=utf8;

CREATE TABLE TypeEntreprise(
	id_typeEntreprise int AUTO_INCREMENT NOT NULL,
	libelle_typeEntreprise varchar(255),
	PRIMARY KEY (id_typeEntreprise)
)DEFAULT CHARSET=utf8;

CREATE TABLE LocalisationEntreprise(
	id_localisation int AUTO_INCREMENT NOT NULL,
	adresse varchar(255),
	latitude float (6,3),
	longitude float (6,3),
	PRIMARY KEY (id_localisation)
)DEFAULT CHARSET=utf8;

CREATE TABLE Entreprise(
	id_entreprise	int AUTO_INCREMENT NOT NULL,
	libelle_entreprise varchar(255),
	id_localisation int,
	id_typeEntreprise int,
	id_avis int,
	PRIMARY KEY (id_entreprise),
	CONSTRAINT fk_LocalisatioinEntreprise_Entreprise FOREIGN KEY (id_localisation) REFERENCES LocalisationEntreprise(id_localisation)
)DEFAULT CHARSET=utf8;

CREATE TABLE Utilisateur(
	id_utilisateur int,
	sexe_utilisateur varchar(255),
	nom_utilisateur varchar(255),
	prenom_utilisateur varchar(255),
	adresse_utilisateur varchar(255),
	password_utilisateur varchar(255),
	ville varchar(255),
	droit varchar(255),
	id_entreprise int,
	PRIMARY KEY (id_utilisateur),
	CONSTRAINT fk_Entreprise_Utilisateur FOREIGN KEY (id_entreprise) REFERENCES Entreprise(id_entreprise)
)DEFAULT CHARSET=utf8;


CREATE TABLE Dates(
	id_date int,
	dates DateTime,
	PRIMARY KEY (id_date)
)DEFAULT CHARSET=utf8;

CREATE TABLE Commande(
	id_commande int,
	libelle_commande varchar(255),
	prix_commande int,
	id_date DateTime,
	id_utilisateur int,
	PRIMARY KEY(id_commande),
	CONSTRAINT fk_Utilisateur_Commande FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur),
	CONSTRAINT fk_Date_Commande FOREIGN KEY (id_date) REFERENCES Dates(id_date)
)DEFAULT CHARSET=utf8;

CREATE TABLE TypeProduits(
	id_typeProduits int AUTO_INCREMENT NOT NULL,
	libelle_typeProduits varchar(255),
	PRIMARY KEY (id_typeProduits)
)DEFAULT CHARSET=utf8;

CREATE TABLE OrigineProduits(
	id_origineProduits int AUTO_INCREMENT NOT NULL,
	libelle_origineProduits varchar(255),
	PRIMARY KEY (id_origineProduits)
)DEFAULT CHARSET=utf8;

CREATE TABLE Produits(
	id_produits int AUTO_INCREMENT NOT NULL,
	libelle_produits varchar(255),
	prix_produits float (4,2),
	heure_fin_vente DateTime,
	stockProduit int,
	id_typeProduits int,
	id_origineProduits int,
	id_utilisateur int,

	PRIMARY KEY (id_produits),
	CONSTRAINT fk_typeProduits_Produits FOREIGN KEY (id_typeProduits) REFERENCES TypeProduits(id_typeProduits),
	CONSTRAINT fk_utilisateur_Produits FOREIGN KEY (id_utilisateur) REFERENCES Utilisateur(id_utilisateur),
	CONSTRAINT fk_origineProduits_Produits FOREIGN KEY (id_origineProduits) REFERENCES OrigineProduits(id_origineProduits)
)DEFAULT CHARSET=utf8;


CREATE TABLE Concerne (
	id_commande int,
	id_produits int,
	quantite int,
	PRIMARY KEY(id_commande,id_produits),
	CONSTRAINT fk_Produit_Concerne FOREIGN KEY (id_produits) REFERENCES Produits(id_produits),
	CONSTRAINT fk_Commande_Concerne FOREIGN KEY (id_commande) REFERENCES Commande(id_commande)
)DEFAULT CHARSET=utf8;