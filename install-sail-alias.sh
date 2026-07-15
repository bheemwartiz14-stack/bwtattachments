#!/usr/bin/env bash

set -e

ALIAS_LINE="alias sail='sh \$([ -f sail ] && echo sail || echo vendor/bin/sail)'"
BASHRC="$HOME/.bashrc"

echo "Installing Laravel Sail alias..."

# Create .bashrc if it doesn't exist
touch "$BASHRC"

# Add the alias only if it doesn't already exist
if grep -Fxq "$ALIAS_LINE" "$BASHRC"; then
    echo "✅ Sail alias already exists in ~/.bashrc"
else
    echo "" >> "$BASHRC"
    echo "# Laravel Sail" >> "$BASHRC"
    echo "$ALIAS_LINE" >> "$BASHRC"
    echo "✅ Sail alias added to ~/.bashrc"
fi

# Reload Bash configuration
source "$BASHRC"

echo ""
echo "Done!"
echo "Verify with:"
echo "  alias sail"
echo ""
echo "You can now use commands like:"
echo "  sail up -d"
echo "  sail artisan migrate"
echo "  sail composer install"
