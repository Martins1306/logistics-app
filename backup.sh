#!/bin/bash

# -----------------------------------------
# SCRIPT DE BACKUP AUTOMÁTICO - Logística App
# -----------------------------------------

echo "🚀 Iniciando backup automático..."

# Ruta del proyecto
PROYECTO="/Applications/MAMP/htdocs/logistica-app"
BACKUPS_DIR="$HOME/Documentos/backups"
FECHA=$(date +%Y-%m-%d_%H-%M)
ARCHIVO="logistica-app-backup-$FECHA.zip"

# Crear carpeta de backups si no existe
mkdir -p "$BACKUPS_DIR"

# Ir al proyecto
cd "$PROYECTO" || { echo "❌ Error: No se encontró el proyecto"; exit 1; }

# Limpiar caché de Laravel
echo "🧹 Limpiando caché de Laravel..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Crear el ZIP (sin carpetas pesadas)
echo "📦 Creando archivo de backup: $ARCHIVO..."
zip -r "$BACKUPS_DIR/$ARCHIVO" . \
  -x "vendor/*" \
  -x "node_modules/*" \
  -x "bootstrap/cache/*" \
  -x "storage/framework/*" \
  -x "storage/logs/*" \
  -x ".git/*" \
  -x ".DS_Store" \
  -x "Thumbs.db"

# Verificar si el ZIP se creó
if [ -f "$BACKUPS_DIR/$ARCHIVO" ]; then
    echo "✅ Backup creado con éxito!"
    echo "💾 Guardado en: $BACKUPS_DIR/$ARCHIVO"
    echo "📦 Tamaño: $(du -h "$BACKUPS_DIR/$ARCHIVO" | cut -f1)"
else
    echo "❌ Error: No se pudo crear el backup."
    exit 1
fi