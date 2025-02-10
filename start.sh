#!/bin/bash
# Make sure this file has executable permissions, run `chmod +x start.sh`
# Run migrations, set up nginx conf and run nginx
./vendor/bin/sail artisan migrate
