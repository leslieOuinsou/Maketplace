# Déploiement Marketplace sur Render

## Prérequis
- Compte [Render](https://render.com) (connexion via GitHub)
- Projet poussé sur GitHub

---

## Vue d'ensemble

Tu vas créer **3 services** sur Render :
1. **MySQL** (base de données, service privé)
2. **PHP** (ton application principale)
3. **Java** (API de recherche)

---

## Étape 1 : Déployer MySQL

1. Va sur [render.com](https://render.com) → **Login** avec GitHub
2. Clique sur **"New +"** → **"Private Service"**
3. Connecte ton compte GitHub si pas déjà fait
4. Choisis **"Build and deploy from a Git repository"**
5. Pour le repo, utilise le template MySQL de Render :
   - Clique sur **"Use existing repository"** ou va sur [Deploy MySQL](https://render.com/deploy?repo=https://github.com/render-examples/mysql)
   - Ou crée un repo depuis le template : [github.com/render-examples/mysql](https://github.com/render-examples/mysql) → "Use this template"
6. Configure le service :
   - **Name** : `marketplace-mysql` (note ce nom, tu en auras besoin)
   - **Region** : choisir la plus proche
   - **Environment Variables** :
     | Key | Value |
     |-----|-------|
     | MYSQL_DATABASE | marketplace_db |
     | MYSQL_USER | user |
     | MYSQL_PASSWORD | *(choisis un mot de passe)* |
     | MYSQL_ROOT_PASSWORD | *(choisis un mot de passe)* |
7. **Advanced** → **Add Disk** :
   - **Mount Path** : `/var/lib/mysql`
   - **Size** : 1 GB (minimum)
8. Clique sur **"Create Private Service"**
9. Attends que MySQL soit déployé (2-5 min)
10. Note l’**Internal URL** affichée (ex: `marketplace-mysql:3306`)

---

## Étape 2 : Initialiser la base de données

1. Clique sur ton service MySQL
2. Onglet **"Shell"** (ou **"Connect"**)
3. Exécute le contenu de `database/schema.sql` pour créer les tables
   - Tu peux aussi utiliser Adminer : [Guide Render Adminer](https://render.com/docs/deploy-adminer)

---

## Étape 3 : Déployer l’application PHP

1. **"New +"** → **"Web Service"**
2. Connecte ton repo **Maketplace** (ton dépôt GitHub)
3. Paramètres :
   - **Name** : `marketplace-php`
   - **Region** : même que MySQL
   - **Root Directory** : *(laisser vide)*
   - **Environment** : **Docker**
   - **Dockerfile Path** : `Dockerfile.php`
4. **Environment Variables** → **Add Environment Variable** :
   | Key | Value |
   |-----|-------|
   | DB_HOST | marketplace-mysql *(ou le nom exact de ton service MySQL)* |
   | DB_NAME | marketplace_db |
   | DB_USER | user |
   | DB_PASSWORD | *(le MYSQL_PASSWORD que tu as mis)* |
5. **Advanced** : Plan **Free**
6. Clique sur **"Create Web Service"**
7. Une fois déployé, **Settings** → **Generate URL** pour avoir l’URL publique
8. Note l’URL (ex: `https://marketplace-php.onrender.com`)

---

## Étape 4 : Déployer le service Java

1. **"New +"** → **"Web Service"**
2. Sélectionne le **même repo** Maketplace
3. Paramètres :
   - **Name** : `marketplace-java`
   - **Region** : même que les autres
   - **Root Directory** : `java-service`
   - **Environment** : **Docker**
   - **Dockerfile Path** : `Dockerfile`
4. **Environment Variables** :
   | Key | Value |
   |-----|-------|
   | SPRING_DATASOURCE_URL | jdbc:mysql://marketplace-mysql:3306/marketplace_db |
   | SPRING_DATASOURCE_USERNAME | user |
   | SPRING_DATASOURCE_PASSWORD | *(ton MYSQL_PASSWORD)* |
5. **Advanced** : Plan **Free**
6. **Create Web Service**
7. **Settings** → **Generate URL**
8. **Copie l’URL du service Java** (ex: `https://marketplace-java.onrender.com`)

---

## Étape 5 : Lier PHP au service Java

1. Retourne sur le service **marketplace-php**
2. **Environment** → **Add Environment Variable** :
   | Key | Value |
   |-----|-------|
   | JAVA_SERVICE_URL | https://marketplace-java.onrender.com |
   *(remplace par ton URL Java, sans slash final)* |
3. **Save Changes** → Render redéploiera automatiquement

---

## Accès au site

Ton site est accessible à l’URL du service PHP, par exemple :
**https://marketplace-php.onrender.com**

---

## Notes Render (offre gratuite)

- Les services **s’endorment** après ~15 min sans visite (démarrage lent au premier clic)
- 750 heures gratuites par mois
- Les services privés (MySQL) communiquent via le réseau interne Render

---

## Dépannage

| Problème | Solution |
|----------|----------|
| Erreur de connexion MySQL | Vérifie DB_HOST = nom exact du service MySQL |
| Recherche sans résultats Java | Vérifie JAVA_SERVICE_URL dans le service PHP |
| Page blanche | Consulte les logs dans Render Dashboard |
