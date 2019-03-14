
# LBS : une application exemple à base de micro-services

## Description du projet
LeBonSandwich est un vendeur de sandwich à la carte bien connu sur la place locale, caractérisé par le type et la qualité des produits proposés, issus de producteur locaux et en majorité avec le label "Bio". Pour garantir la qualité et la fraicheur de ses produits, tous les sandwichs sont réalisés au moment de la commande, ce qui peut conduire à des temps d'attente parfois un peu long. Pour améliorer cela, la boutique souhaite se doter d'un service de commande en ligne de sandwichs.

Le principe est de commander son (ses) sandwichs à l'aide d'une application web/mobile. Cette webapp fonctionne sur tous types de terminaux. Cette application permet de créer une commande, de suivre sa commande (planifiée, en cours de préparation, prête ...) et de la payer. 

En complément, une application back-office de gestion et de suivi de la fabrication des commandes est utilisée par le point de vente. Cette webapp back-office permet de visualiser les commandes et les paiements, d'enregistrer la prise en charge d'une commande et sa fabrication puis sa livraison. 

Une 3ème application permet au staff point de vente de gérer le catalogue de produits et les tarifs des sandwichs proposés à la vente.

## Services

2 services :
* 1 api REST pour la gestion du catalogue des sandwiches
* 1 api REST pour la gestion des commandes passé par les clients

## Bases de données

* 1 base de données catalogue
* 1 base de données commandes

## Setup
	
	$ docker-compose up --no-start # Create the containers
	$ docker-compose start # Start the containers
	
## Catalogue
	$ docker exec -it {catalogue_ctid} /bin/bash
	$ cd catalogue/src
	$ composer install

Renommer le fichier **catalogue/api_catalogue/configuration.ini.dist** par **configuration.ini**

##  Commandes
	$ docker exec -it {commandes_ctid} /bin/bash
	$ cd commandes/src
	$ composer install

Renommer le fichier **commande/api_commande/configuration.ini.dist** par **configuration.ini**


## Routes
