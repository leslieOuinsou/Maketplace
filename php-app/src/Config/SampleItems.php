<?php
namespace App\Config;

class SampleItems {

    public static function getAll(): array {
        return self::ITEMS;
    }

    public static function getById(int $id): ?array {
        foreach (self::ITEMS as $item) {
            if ((int) $item['id'] === $id) {
                return $item;
            }
        }
        return null;
    }

    private const ITEMS = [
        ['id' => 1001, 'title' => 'Jean Levi\'s vintage bleu', 'price' => 45.00, 'category' => 'Vêtements', 'image' => 'https://picsum.photos/seed/item1/800/600', 'description' => "Jean Levi's 501 vintage, taille 32/34. Porté quelques fois seulement, état impeccable. Coupe droite classique, couleur bleu délavé. Idéal pour un look casual."],
        ['id' => 1002, 'title' => 'Sweat à capuche Nike gris', 'price' => 65.00, 'category' => 'Vêtements', 'image' => 'https://picsum.photos/seed/item2/800/600', 'description' => "Sweat Nike oversized en gris chiné. Taille L, 100% coton. Doublure douce et chaude. Parfait pour l'hiver ou les soirées cosy. Logo brodé."],
        ['id' => 1003, 'title' => 'Robe d\'été imprimée florale', 'price' => 38.50, 'category' => 'Vêtements', 'image' => 'https://picsum.photos/seed/item3/800/600', 'description' => "Belle robe d'été légère à imprimé floral. Taille M, matière fluide. Dos découvert, coupe ajustée. Parfaite pour les vacances ou les sorties."],
        ['id' => 1004, 'title' => 'iPhone 13 Pro - État neuf', 'price' => 750.00, 'category' => 'Électronique', 'image' => 'https://picsum.photos/seed/item4/800/600', 'description' => "iPhone 13 Pro 128 Go, couleur Graphite. Jamais utilisé, encore sous garantie Apple. Vendu avec boîte d'origine, chargeur et écran protecteur."],
        ['id' => 1005, 'title' => 'MacBook Air M2 256 Go', 'price' => 950.00, 'category' => 'Électronique', 'image' => 'https://picsum.photos/seed/item5/800/600', 'description' => "MacBook Air M2, 256 Go SSD, 8 Go RAM. Couleur Midnight. Parfait état, batterie à 95%. Idéal pour étudiant ou télétravail."],
        ['id' => 1006, 'title' => 'Casque Sony WH-1000XM4', 'price' => 220.00, 'category' => 'Électronique', 'image' => 'https://picsum.photos/seed/item6/800/600', 'description' => "Casque Bluetooth Sony WH-1000XM4, réduction de bruit active. Noir, avec étui et câble. Son exceptionnel, autonomie 30h."],
        ['id' => 1007, 'title' => 'Lampadaire Scandinave design', 'price' => 89.90, 'category' => 'Maison', 'image' => 'https://picsum.photos/seed/item7/800/600', 'description' => "Lampadaire design nordique, pied en bois clair, abat-jour blanc. Hauteur 1,65 m. Parfait pour salon ou chambre. Ampoule fournie."],
        ['id' => 1008, 'title' => 'Canapé 3 places velours vert', 'price' => 450.00, 'category' => 'Maison', 'image' => 'https://picsum.photos/seed/item8/800/600', 'description' => "Canapé convertible 3 places en velours vert émeraude. Très confortable, état neuf. Dimensions : 210x90 cm. Livraison possible sur Paris."],
        ['id' => 1009, 'title' => 'Lampe de bureau LED design', 'price' => 34.99, 'category' => 'Maison', 'image' => 'https://picsum.photos/seed/item9/800/600', 'description' => "Lampe de bureau LED avec pied articulé. Réglage d'intensité. Idéale pour télétravail ou études. Couleur blanc mat."],
        ['id' => 1010, 'title' => 'PlayStation 5 + 2 manettes', 'price' => 480.00, 'category' => 'Divertissement', 'image' => 'https://picsum.photos/seed/item10/800/600', 'description' => "PS5 édition disque avec 2 manettes DualSense. Console en parfait état, tous les accessoires d'origine. Livrée avec 3 jeux numériques."],
        ['id' => 1011, 'title' => 'Collection livres Harry Potter', 'price' => 55.00, 'category' => 'Divertissement', 'image' => 'https://picsum.photos/seed/item11/800/600', 'description' => "Intégrale Harry Potter en 7 volumes, édition française. Bon état, quelques marques d'usage. Parfaite pour collectionneur ou enfant."],
        ['id' => 1012, 'title' => 'Vélo électrique pliant', 'price' => 899.00, 'category' => 'Sport', 'image' => 'https://picsum.photos/seed/item12/800/600', 'description' => "Vélo électrique pliant 20 pouces. Autonomie 50 km, moteur 250W. Pliage rapide pour transport. Idéal ville ou multimodal."],
    ];
}
