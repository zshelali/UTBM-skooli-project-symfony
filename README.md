## I. Technologies à utiliser !

- [x] Utiliser **Symfony 7**
- [x] Utiliser **Apache + MySQL** (via XAMPP ou équivalent)
- [x] Ne pas utiliser d'autres librairies que :
  - [ ] **Bootstrap** (optionnel)
  - [x] **JQuery** (optionnel)

---

## II. Rôles à implémenter

### II-A) Administrateur

- [x] Accès à une zone réservée après login
- [x] Peut créer des **UE**
- [x] Peut assigner des **professeurs** à une UE
- [x] Peut assigner des **étudiants** à une UE
- [x] Peut créer des **comptes utilisateurs** (admin, prof, étudiant) avec mot de passe par défaut
- [x] Ne pas permettre la création de compte via un formulaire public
- [x] Un admin peut être aussi prof, mais un étudiant ne peut pas être admin
- [x] Un prof-admin peut passer d’une vue à l’autre via des liens/navigation
- [x] Un admin **non professeur** ne peut pas modifier les contenus d’UE (objectif minimal)
- [ ] (Nice to Have) Un admin peut modifier le contenu d’une UE et déclencher une **alerte** pour le prof

### II-B) Étudiant

- [x] Arrive sur la page de choix d’UE après login
- [x] Peut voir les posts de l’UE
- [x] Ne peut que **lire** les posts (texte ou fichiers)
- [x] Ne peut pas poster ou modifier quoi que ce soit

### II-C) Professeur

- [x] Arrive aussi sur la page de choix d’UE
- [x] Peut voir les mêmes pages que l’étudiant, mais avec plus de permissions
- [x] Peut **ajouter, modifier, supprimer** les posts dans les UE assignées
- [x] Peut accéder à des formulaires de création/modification de posts (pages séparées ou dynamiques)
- [x] (Nice to Have) Peut modifier les posts **sans quitter la page**

---

## III. Pages nécessaires

### III-A) Page de Login (tout le monde)

- [x] Formulaire de login (accessible sans être connecté)
- [ ] Redirection automatique ici si non connecté
- [x] **Javascript requis :**
  - [x] Montrer/cacher le mot de passe
  - [x] Refuser l’envoi du formulaire si champ vide
- [ ] (Nice to Have) Afficher des stats (nombre de posts, comptes...)

### III-B) Page(s) d’administration (admin uniquement)

#### III-B1) Catalogue

- [x] Afficher tous les **utilisateurs** et toutes les **UE**
- [x] Affichage par **onglets** via Javascript
- [x] Bouton **Créer** en haut de chaque onglet
- [x] Pour chaque entrée : lien **Modifier** et **Effacer**
- [x] **Javascript requis :**
  - [x] Effacement en AJAX
  - [x] Popup de confirmation avant effacement
- [x] (Nice to Have) Formulaire d’ajout/modif affiché dynamiquement (AJAX)

#### III-B2) Création / Modification d’un utilisateur

- [x] Champs : nom, prénom, email, rôle, mot de passe (défaut à la création), UE assignées
- [x] Login via couple email / mot de passe
- [x] Pré-remplir les champs pour la modification
- [x] Permettre à un utilisateur de changer son propre mot de passe
- [x] Interface de sélection de **UE assignées**
  - [x] Ajouter une UE à la liste
  - [x] Retirer une UE de la liste
- [ ] (Nice to Have) Créer une UE sans quitter le formulaire utilisateur

#### III-B3) Création / Modification d’une UE

- [x] Champs : code, intitulé, image
- [x] (Nice to Have) Ajouter ou retirer des utilisateurs assignés à l’UE
- [x] (Nice to Have) Recherche d’utilisateur par nom (AJAX)

### III-C) Page de choix d’UE (prof/étudiant)

- [x] Afficher **les UE assignées** sous forme de **boîtes imagées**
- [x] Afficher les **activités récentes**
- [x] Activités triées par ordre **chronologique décroissant**
- [x] Afficher 10 à 20 activités
- [ ] (Nice to Have) Bouton AJAX "charger plus d’activités"

### III-D) Page de contenu d’une UE

- [x] Afficher tous les **posts** de l’UE (ordre décroissant)
- [x] Affichage différent pour :
  - [x] **Messages texte**
  - [x] **Posts de fichiers**
- [x] Pour les professeurs :
  - [x] Bouton "Créer un post"
  - [x] Boutons "Modifier" / "Supprimer" sur chaque post
- [x] **Javascript requis :**
  - [x] Suppression en AJAX avec confirmation
- [x] (Nice to Have) Modifier les posts sans quitter la page

### III-E) Page de création / modification de Post

- [x] Deux boutons pour choisir le type de post :
  - [x] **Message texte**
  - [ ] **Partage de fichier**
- [ ] Formulaires dynamiques qui s’affichent/cachent selon le type choisi (onglets)
- [ ] Chaque type de post a son propre formulaire

### III-F) Liste des inscrits à une UE

- [x] Afficher la liste des **professeurs** et **étudiants** assignés à une UE
- [x] Informations affichées : nom, prénom, email
- [x] Emails = liens `mailto:`
- [x] Séparer les professeurs et les étudiants

### III-G) Page de gestion de compte (tout le monde)

- [x] Permettre à l’utilisateur de modifier : nom, prénom, mot de passe
- [ ] Email et UE ne sont pas modifiables
- [ ] (Nice to Have) Menu déroulant + AJAX pour mise à jour directe
- [ ] (Nice to Have) Ajouter avatar, téléphone, etc.

---

## IV. Types de posts

### IV-A) Message texte

- [ ] Champs requis : titre, date/heure, type de message, texte
- [ ] Types : **Information** et **Important** (minimum)
- [ ] Icône différente selon le type de message
- [ ] Étudiant : lecture seule
- [ ] Professeur : peut modifier/supprimer
- [ ] **Javascript requis :**
  - [ ] Suppression AJAX avec popup de confirmation
- [ ] (Nice to Have) Modification AJAX sans quitter la page

### IV-B) Dépôt de fichiers

- [ ] Champs : titre, texte, date/heure, fichier `.zip`
- [ ] Affichage comme un post texte + lien de téléchargement
- [ ] Étudiant : lecture + téléchargement
- [ ] Professeur : peut modifier/supprimer
- [ ] **Javascript requis :**
  - [ ] Suppression AJAX avec popup de confirmation
- [ ] (Nice to Have) Support d'autres types de fichiers + visuel différent

---

## V. Nice to Have (Bonus)

### V-1) Épingler / désépingler un post

- [ ] Ajouter champ `pinned` dans la BDD
- [ ] Bouton pour épingler un post texte (hors devoirs)
- [ ] Les posts épinglés apparaissent en haut, triés par date
- [ ] Un post épinglé est distinct visuellement

### V-2) Admin = Modérateur

- [ ] Un admin peut accéder aux UE même s’il n’est pas prof
- [ ] Peut ajouter/modifier/supprimer les posts
- [ ] Ajoute une **alerte** dans le fil d’activité
  - [ ] Alerte prioritaire (style et couleur)
  - [ ] Lien vers la page UE concernée
  - [ ] Bouton "J’ai compris" pour supprimer l’alerte
  - [ ] Alerte enregistrée en BDD

### V-3) Ordre personnalisé des posts

- [ ] Liste déroulante pour déplacer un post
- [ ] Choix : "Avant [titre]", ou "À la fin"
- [ ] Ordre sauvegardé en BDD
- [ ] (Nice to Have ++): drag-and-drop ou déclenchement AJAX
- [ ] Ne pas appliquer aux posts épinglés (ou les gérer séparément)