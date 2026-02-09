# Déploiement Marketplace sur Railway

## Prérequis
- Compte [Railway](https://railway.app) (connexion via GitHub)
- Projet poussé sur GitHub

---

## Étape 1 : Créer le projet Railway

1. Va sur [railway.app](https://railway.app)
2. Clique sur **"Login"** → connecte-toi avec **GitHub**
3. Clique sur **"New Project"**
4. Choisis **"Deploy from GitHub repo"**
5. Sélectionne ton dépôt **Maketplace**

---

## Étape 2 : Ajouter MySQL

1. Dans ton projet, clique sur **"+ New"**
2. Choisis **"Database"** → **"MySQL"**
3. Railway crée la base et affiche les variables (MYSQLHOST, MYSQLPORT, etc.)
4. Clique sur le service MySQL → onglet **"Variables"** pour les voir
5. **Important** : exécute le schéma SQL. Clique sur **"Data"** ou connecte-toi avec un client MySQL et exécute le contenu de `database/schema.sql`

---

## Étape 3 : Déployer le service PHP

1. Clique sur **"+ New"** → **"GitHub Repo"** (ou **"Empty Service"** puis lie le repo)
2. Sélectionne à nouveau le dépôt Maketplace
3. Dans les paramètres du service :
   - **Root Directory** : laisse vide (racine du repo)
   - **Dockerfile Path** : `Dockerfile.php`
4. Clique sur le service PHP → **"Variables"** → **"Add Variable"**
5. Ajoute les variables (depuis le service MySQL, utilise **"Connect"** ou copie les valeurs) :
   ```
   DB_HOST=<MYSQLHOST du service MySQL>
   DB_NAME=railway
   DB_USER=root
   DB_PASSWORD=<MYSQLPASSWORD du service MySQL>
   ```
   *Note : Utilise `marketplace_db` si tu exécutes le schéma qui crée cette base (voir Étape 6)*
6. Si Railway lie automatiquement MySQL, les variables peuvent être pré-remplies. Sinon, récupère-les dans le service MySQL.
7. Clique sur **"Settings"** → **"Generate Domain"** pour obtenir une URL publique

---

## Étape 4 : Déployer le service Java

1. Clique sur **"+ New"** → **"GitHub Repo"**
2. Sélectionne le même dépôt Maketplace
3. Paramètres :
   - **Root Directory** : `java-service`
   - **Dockerfile Path** : `Dockerfile` (ou `./Dockerfile`)
4. Variables (lie le service MySQL ou ajoute manuellement) :
   ```
   SPRING_DATASOURCE_URL=jdbc:mysql://MYSQLHOST:MYSQLPORT/railway
   SPRING_DATASOURCE_USERNAME=root
   SPRING_DATASOURCE_PASSWORD=<MYSQLPASSWORD>
   ```
   Remplace MYSQLHOST, MYSQLPORT, MYSQLPASSWORD par les valeurs du service MySQL.
5. **Generate Domain** pour ce service
6. **Copie l’URL** du service Java (ex: `https://xxx.up.railway.app`)

---

## Étape 5 : Lier PHP au service Java

1. Retourne sur le service **PHP**
2. Variables → ajoute :
   ```
   JAVA_SERVICE_URL=https://TON-URL-JAVA.up.railway.app
   ```
   (remplace par l’URL réelle du service Java, sans slash final)

---

## Étape 6 : Initialiser la base MySQL

Railway MySQL utilise une base par défaut. Tu dois exécuter le schéma :

1. Dans le service MySQL, ouvre l’onglet **"Data"** ou **"Connect"**
2. Si possible, exécute le SQL. Sinon, utilise un client (DBeaver, MySQL Workbench) avec les identifiants fournis
3. Exécute le contenu de `database/schema.sql`
4. Adapte si besoin le nom de la base (Railway utilise souvent `railway`)

---

## Variables récapitulatives

### Service PHP
| Variable | Valeur |
|----------|--------|
| DB_HOST | (depuis MySQL) |
| DB_NAME | railway |
| DB_USER | root |
| DB_PASSWORD | (depuis MySQL) |
| JAVA_SERVICE_URL | https://java-service-xxx.up.railway.app |

### Service Java
| Variable | Valeur |
|----------|--------|
| SPRING_DATASOURCE_URL | jdbc:mysql://host:port/railway |
| SPRING_DATASOURCE_USERNAME | root |
| SPRING_DATASOURCE_PASSWORD | (depuis MySQL) |

---

## Accès au site

Une fois déployé, l’URL du site est celle du service PHP (ex: `https://maketplace-production-xxx.up.railway.app`).

---

## Dépannage

- **Erreur de connexion MySQL** : vérifie DB_HOST, DB_NAME, DB_USER, DB_PASSWORD
- **Recherche sans résultats Java** : vérifie JAVA_SERVICE_URL dans le service PHP
- **Base vide** : exécute `database/schema.sql` dans MySQL
