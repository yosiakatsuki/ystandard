#!/usr/bin/env bash
error=`find . -path "./vendor" -prune -o -path "./node_modules" -prune -o -name "*.php" -exec php -l "{}" \; | grep -v "No syntax errors"`
if [ -n "$error" ]; then
	echo "エラーあり"
  echo ${error}
  exit 1
else
	echo "エラーなし"
	exit 0
fi
