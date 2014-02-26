<?php
/**
 * KSENIA PORTFOLIO
 * @copyright 2014 mparaiso <mparaiso@online.fr>
 * all rights reserved
 * this code was open sourced for educational purpose only.
 */
$templates = array();

$templates['layout']=<<<HERE
<!doctype html>
<html lang='{{app.locale}}'>
	<head>
		<meta charset='UTF-8' />
		<meta http-equiv='X-UA-Compatible' content='IE=edge'/>
		<meta name='viewport' content='width=device-width, initial-scale=1'/>
		<title>{{app.title}}</title>
		{% block styles %}
		{% endblock %}

	</head>
	<body>
		<main class='container'>
			<noscript><h2 class="alert alert-warning">Please Enable Javascript!</h2></noscript>
			<h1>{{app.title}}</h1>
			{%block content%}{%endblock%}
		</main>
		{% block scripts %}
		{% endblock %}
	</body>
</html>
HERE;

$templates['index'] = "
{% extends 'layout' %}
{% block content %}
	<h2>HomePage</h2>
{% endblock %}
";

/** administration */
$templates['admin_layout']=<<<HERE
{% extends 'layout'%}
{% block styles %}
	{# Latest compiled and minified CSS #}
	<link rel='stylesheet' href='//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css'>
	{# Optional theme #}
	<link rel='stylesheet' href='//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css'>
	{# google font #}
	<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
	{# custom styles #}
	<link rel="stylesheet" href="/static/css/styles.css">
{% endblock %}
{% block content %}
	<section class='row'>
	<aside class='col-md-3'>
	{%include 'admin_nav'%}
	</aside>
	<article class='col-md-9'>
		{% block admin_content %}
		{% endblock %}
	</article>
	</section>
{% endblock %}
{% block scripts %}
	{# jQuery (necessary for Bootstrap's JavaScript plugins) #}
	<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'></script>
	{# Latest compiled and minified JavaScript #}
	<script src='//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js'></script>
{% endblock %}
HERE;

$templates['admin_nav']=<<<HERE
<ul class="list-group">
	<li class="list-group-item"><strong><a href="{{path('admin_index')}}">DASHBOARD</a></strong></li>
	<li class="list-group-item text-muted uppercase">
		<strong>PROJECTS</strong>
	</li>
	<li class="list-group-item"><a href="{{path('project_index')}}">Manage Projects</a></li>
	<li class="list-group-item"><a href="{{path('project_new')}}">Create a new project</a></li>
</ul>
HERE;

$templates['admin_index']="
{% extends 'admin_layout' %}
{% block admin_content %}
    <h2>ADMINISTRATION</h2>
{% endblock %}
";

$templates['admin_upload'] = "
{% extends 'admin_layout' %}
{% block content %}
<h2>Upload</h2>
{% endblock %}
";

/** Project management */

/* list all projects */
$templates['project_index']=<<<HERE
{% extends 'admin_layout' %}
{% block admin_content %}
<h2>PROJECTS</h2>
{% endblock%}
HERE;
/* create a new project */
$templates['project_new']=<<<HERE
	{%extends 'admin_layout' %}
	{% block admin_content %}
		<h2>NEW PROJECT</h2>
		<form action="" name="project"></form>
	{% endblock %}
	{% block scripts %}
	{{ parent() }}
	<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
	<script src="/static/javascript/jquery-plugins.js"></script>
	<script src="/static/javascript/project-new.js"></script>
	{% endblock %}
HERE;
return $templates;