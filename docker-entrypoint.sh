#!/bin/bash
set -e
# Fix MPM au démarrage (Railway peut charger plusieurs MPMs)
a2dismod mpm_event mpm_worker 2>/dev/null || true
rm -f /etc/apache2/mods-enabled/mpm_*.conf /etc/apache2/mods-enabled/mpm_*.load 2>/dev/null || true
a2enmod mpm_prefork 2>/dev/null || true
exec apache2-foreground
