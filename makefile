test:
	@phpunit.sh &
karma:
	@karma start &
commit:
	@git add .
	@git commit -am"$(message) `date`" | :
	@if [ "$(message)" ]; then echo "- `date` : $(message)" >> CHANGELOG.md ;fi;
push: commit
	@git push origin master
start:
	@php -S localhost:3000 -t web web/index.php &
deploy: push
	@git push heroku master
os: commit
	@git push openshift master
.PHONY: commit start test push deploy karma
