test:
	@phpunit.sh
commit:
	@git add .
	@git commit -am"$(message) `date`" | :
	ifdef 
	@echo "- `date` $(message)" >> CHANGELOG ;
	endif
push: commit
	@git push origin master
run:
	@php -S localhost:3000 -t web web/index.php &
deploy: commit
	@git push heroku master
.PHONY: commit run deploy-af test push deploy-heroku