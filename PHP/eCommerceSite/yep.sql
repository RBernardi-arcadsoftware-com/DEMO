CREATE TABLE 'client' (
  'id_client int(11) NOT NULL auto_increment,
   'num_facture' varchar(20) NOT NULL,
  `montant_facture` varchar NOT NULL,
  `valider` int(11) default '0' COMMENT 'formulaire de confirmation valid� ?',
  `date_validation` date default NULL COMMENT 'date de validation du formulaire de confirmation',
  `date_saisie` date NOT NULL COMMENT 'date de saisie du 1er formulaire',
  `produit` varchar NOT NULL COMMENT 'nom du produit � telecharger',
  `envoi_cd` int(11) default '0' COMMENT 'envoyer un cd ?',
  `adresse_ip` varchar default NULL COMMENT 'adresse ip du client',
  `message_mail` longtext NOT NULL COMMENT 'message envoyer dans le mail client',
  `md5` varchar NOT NULL COMMENT 'cl� pour l''url',
  `nom_societe` varchar NOT NULL COMMENT 'nom de la soci�t�',
  `mail_client` varchar NOT NULL,
  PRIMARY KEY  (`id_client`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=18 ;
