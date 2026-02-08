-- Migration : ajouter la colonne role à la table users (pour bases existantes)
-- À exécuter si votre base existait avant cette mise à jour
ALTER TABLE users ADD COLUMN role ENUM('acheteur', 'vendeur', 'les_deux') DEFAULT 'les_deux' AFTER password_hash;
