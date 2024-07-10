#! /bin/bash

read -r -p "Enter Theme Name: " input
THEME_NAME=$input
THEME_LOWER=$(echo $THEME_NAME | tr '[:upper:]' '[:lower:]')
THEME_UNDERSCORES=$(echo $THEME_LOWER | tr ' ' '_')
THEME_DASHES="${THEME_LOWER// /-}"

# Rename CSS file
mv ./source/css/watt-theme.css ./source/css/$THEME_DASHES.css

# Replace all instances of 'watt-theme' with the new dashed theme name
find . -type f \
	-not -path "*png" \
	-not -path "*jpg" \
	-not -path "*jpeg" \
	-not -path "*webp" \
	-not -path "*update_theme_name.sh*" \
	-not -path "*acf-json*" \
	-not -path "*node_modules*" \
	-not -path "*vendor*" \
	-not -path "*dist*" \
	-not -path "*.git*" \
	-not -path "*.idea*" \
	| xargs sed -i '' -e "s/watt-theme/$THEME_DASHES/g"

# Replace all instances of 'watt_' with the new dashed theme name
find . -type f \
	-not -path "*png" \
	-not -path "*jpg" \
	-not -path "*jpeg" \
	-not -path "*webp" \
	-not -path "*update_theme_name.sh*" \
	-not -path "*acf-json*" \
	-not -path "*node_modules*" \
	-not -path "*vendor*" \
	-not -path "*dist*" \
	-not -path "*.git*" \
	-not -path "*.idea*" \
	| xargs sed -i '' -e "s/watt_/$THEME_UNDERSCORES/g"

# Replace all instances of 'watt-theme' with the new dashed theme name
find . -type f \
	-not -path "*png" \
	-not -path "*jpg" \
	-not -path "*jpeg" \
	-not -path "*webp" \
	-not -path "*update_theme_name.sh*" \
	-not -path "*acf-json*" \
	-not -path "*node_modules*" \
	-not -path "*vendor*" \
	-not -path "*dist*" \
	-not -path "*.git*" \
	-not -path "*.idea*" \
	| xargs sed -i '' -e "s/WATT theme/$THEME_NAME/g"
