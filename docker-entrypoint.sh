#!/bin/bash
set -e
# Fix MPM au démarrage (Railway peut charger plusieurs MPMs)
a2dismod mpm_event mpm_worker 2>/dev/null || true
rm -f /etc/apache2/mods-enabled/mpm_*.conf /etc/apache2/mods-enabled/mpm_*.load 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true
# Permissions uploads (Railway : volume monté en root, 777 garantit l'écriture pour www-data)
mkdir -p /var/www/html/public/uploads
chown -R www-data:www-data /var/www/html/public/uploads 2>/dev/null || true
chmod -R 777 /var/www/html/public/uploads
exec apache2-foreground
