# deploy the app on the server
# http://www.opussoftware.com/tutorial/TutMakefile.htm
deploy:
	scp -r * $(PHALCON_TUTORIAL_SERVER)
# start app with php built in server
# http://docs.phalconphp.com/en/latest/reference/built-in.html
start:
	php -S localhost:8000 -t web .htrouter.php

.PHONY: deploy start
