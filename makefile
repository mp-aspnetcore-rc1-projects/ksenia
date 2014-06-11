test:
	@phpunit.sh
commit:
	@git add .
	@git commit -am"$(message) `date`" | :
	@if [ "$(message)" ]; then echo "- `date` : $(message)" >> CHANGELOG.md ;fi;
push: commit
	@git push origin master
run:
	@php -S localhost:3000 -t web web/index.php &
deploy: push
	@git push heroku master
.PHONY: commit run test push deploy