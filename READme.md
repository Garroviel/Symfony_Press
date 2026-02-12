## ✅ Fonctionnalités

### Front
- Page d’accueil `/` : liste d’articles + tags catégories
- Page article `/article/{slug}` : détail d’un article
- Page catégorie `/category/{slug}` : liste des articles d’une catégorie

### Back-office
- CRUD Articles (création / édition / suppression)
- Routes préfixées en `/admin/...`
- Formulaires Symfony + validation (Validator)

### Auth & sécurité (Jour 3)
- Inscription `/register`
- Connexion `/login`
- Déconnexion `/logout`
- Accès `/admin` réservé aux utilisateurs authentifiés
- **Ownership** : un utilisateur ne peut pas modifier/supprimer les articles d’un autre utilisateur  
  (sauf admin)
- Pour les ['ROLE_USER'] le bouton admin est modifié en mes articles, possible de se connecter depuis le bouton connexion qui retourne sur la page d'acceuil. 
- Pour accéder à mes articles, cliquer sur bouton mes articles depuis la page d'acceuil, si non connecter il faudra alors s'authentifier. 
- Pour le ['ROLE_ADMIN'] c'est identique aux user exepté que le bouton mes articles est alors admin et que l'on peut voir tous les articles.

- Les pages de connexion comporte un bouton pour la création de compte

### Bonus
- Pages d’erreur personnalisées (403 / 404 / 500)

---

## 🧱 Stack technique

- Symfony **7.4 LTS**
- PHP **8.3+**
- Twig
- Doctrine ORM + Migrations
- MySQL
- CSS maison

---

## 📦 Prérequis

- PHP 8.3+
- Composer
- Symfony CLI 
- MariaDB/MySQL 

---

## 🚀 Installation

### 1) Cloner le projet
```bash
git clone <URL_DU_REPO>
cd SymfonyPress
```

Le fichier SQL est dans :

docs/database/fixtures-jour3.sql


Option A (phpMyAdmin)
Importer le fichier dans la base symfony_press.

Option B (CLI MySQL/MariaDB)

mysql -u root -p symfony_press < docs/database/fixtures-jour3.sql

## ▶️ Lancer le serveur

Avec Symfony CLI :

symfony serve


Puis ouvrir :

http://127.0.0.1:8000

## 🔑 Comptes de test

Admin

email : admin@example.com

password : password

User

email : user@example.com

password : password

## 🧭 Routes principales
Page	URL	Nom de route
Accueil	/	home
Article	/article/{slug}	article_show
Catégorie	/category/{slug}	category_show
Admin articles	/admin/article	admin_article_index
Login	/login	app_login
Register	/register	app_register
Logout	/logout	app_logout
🔒 Sécurité & règles métier (ownership)

/admin/... nécessite un utilisateur authentifié.

Un utilisateur ne peut éditer/supprimer que ses propres articles.

L’admin peut gérer tous les articles.

## 🧩 Requête Doctrine personnalisée

Le projet contient au moins une requête métier via QueryBuilder dans un Repository
(ex : articles visibles dans le back-office selon le rôle).

❗ Pages d’erreur

Templates personnalisés :

templates/bundles/TwigBundle/Exception/error403.html.twig

templates/bundles/TwigBundle/Exception/error404.html.twig

templates/bundles/TwigBundle/Exception/error.html.twig

📁 Structure Twig (simplifiée)
templates/
├── base.html.twig
├── components/
│   └── article_card.html.twig
├── layout/
│   ├── header.html.twig
│   └── footer.html.twig
└── pages/
    ├── home/
    ├── article/
    ├── category/
    └── admin/
        └── article/

## 🧯 Dépannage rapide

Après modification des routes / templates / config :

php bin/console cache:clear


Vérifier les routes :

php bin/console debug:router