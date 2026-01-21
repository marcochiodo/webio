#!/bin/sh

cat "$1" | jq -c '.' | base64 -w 0