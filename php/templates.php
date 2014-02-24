<?php

$templates = array();

$templates['layout']="
<!doctype html>
<html lang='en'>
	<head>
		<meta charset='UTF-8' />
		<title>{{app.title}}</title>
	</head>
	<body>
	<main class='container'>
		<h1>{{app.title}}</h1>
		{%block content%}{%endblock%}
	</main>
	</body>
</html>
";

$templates['index'] = "
{% extends 'layout' %}
{% block content %}
	<h2>HomePage</h2>
{% endblock %}
";

$templates['admin_upload'] = "
{% extends 'layout' %}
{% block content %}
<h2>Upload</h2>
{% endblock %}
";

return $templates;