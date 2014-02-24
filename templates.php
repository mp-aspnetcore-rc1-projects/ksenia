<?php

$templates = array();

$templates['layout']="
<!doctype html>
<html lang='en'>
	<head>
		<meta charset='UTF-8' />
		<title>{{title}}</title>
	</head>
	<body>
	<main class='container'></main>
		{%block content%}{%endblock%}
	</body>
</html>
";

$templates['index'] = "
{% extends 'layout' %}
{% block content %}
	<h1>HomePage</h1>
{% endblock %}
";

$templates['admin_upload'] = "
{% extends 'layout' %}
{% block content %}
{% endblock %}
";

return $templates;