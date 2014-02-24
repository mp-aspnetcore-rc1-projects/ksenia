commit:
	git add .
	git commit -am"$(message) `date`"
push: commit
	git push origin master
run:
	@php -S localhost:3000 router.php &
deploy-af:
	@af update mparaiso-blog
deploy-heroku: commit
	git push heroku master
.PHONY: commit run deploy-af