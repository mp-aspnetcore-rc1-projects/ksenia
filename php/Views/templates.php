<?php
/**
 * KSENIA PORTFOLIO
 * @copyright 2014 mparaiso <mparaiso@online.fr>
 * all rights reserved
 * this code was open sourced for educational purpose only.
 */
$templates = array();

$templates['layout'] = <<<HERE
	<!doctype html>
	<html lang='{{app.locale}}'>
		<head>
			<title>{{app.title}}</title>
			{% block metas %}
				<meta charset='UTF-8' />
				<meta http-equiv='X-UA-Compatible' content='IE=edge'/>
				<meta name='viewport' content='width=device-width, initial-scale=1'/>
			{% endblock %}
			{% block styles %}{% endblock %}
		</head>
		<body>
			<main class='container'>
				<noscript><h2 class="alert alert-warning">Please Enable Javascript!</h2></noscript>
				<h1>{{app.title}}</h1>
				{%block content%}{%endblock%}
			</main>
			<footer class="container">
			{%block footer%}
			{%endblock%}
			</footer>
			{% block scripts %}
			{% endblock %}
		</body>
	</html>
HERE;

$templates['index'] = <<<HERE
	{% extends 'layout' %}
	{% block content %}
		<h1>HOMEPAGE</h1>
	{% endblock %}
HERE;

/** administration */
$templates['admin_layout'] = <<<HERE
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
	{%block footer%}
	    &copy; {{"now"|date("Y")}} mparaiso mparaiso@online.fr
		{% block admin_footer %}
		{% endblock %}
	{% endblock %}
	{% block scripts %}
		{# jQuery (necessary for Bootstrap's JavaScript plugins) #}
		<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'></script>
		{# Latest compiled and minified JavaScript #}
		<script src='//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js'></script>
	{% endblock %}
HERE;

$templates['admin_nav'] = <<<HERE
	<ul class="list-group">
		<li class="list-group-item"><strong><a href="{{path('admin_index')}}">DASHBOARD</a></strong></li>
		<li class="list-group-item text-muted uppercase">
			<strong>PROJECTS</strong>
		</li>
		<li class="list-group-item"><a href="{{path('project_index')}}">Manage Projects</a></li>
		<li class="list-group-item"><a href="{{path('project_new')}}">Create a new project</a></li>
	    <li class="list-group-item text-muted uppercase">
			<strong>PAGES</strong>
		</li>
		<li class="list-group-item"><a href="{{path('page_index')}}">Manage Pages</a></li>
		<li class="list-group-item"><a href="{{path('page_create')}}">Create a new page</a></li>
	</ul>
HERE;

$templates['admin_index'] = <<<HERE
	{% extends 'admin_layout' %}
	{% block admin_content %}
        <header class="lead text-muted">ADMINISTRATION</header>
	{% endblock %}
HERE;

$templates['admin_upload'] = <<<HERE
	{% extends 'admin_layout' %}
	{% block content %}
	<h2>Upload</h2>
	{% endblock %}
HERE;

/** Project management */

/* list all projects */
$templates['project_index'] = <<<HERE
	{% extends 'admin_layout' %}
	{% block admin_content %}
				<header class="lead">
            <ol class="breadcrumb">
                <li class="active">Projects</li>
            </ol>
        </header>
		{% if projects and projects|length > 0 %}
		<table class="table">
			<thead>
				<tr>
					<th>Title</th>
					<th></th>
					<th></th>
					<th class="col-md-3"></th>
				</tr>
			</thead>
			<tbody>
				{% for project in projects %}
				<tr>
					<td><a href="{{ path('project_read',{id:project.id}) }}">{{project.title}}</a></td>
					<td>{{project.description[:50]~"..."}}</td>
					<td><a class="btn btn-link"  href="{{path('project_read',{id:project.id}) }}">Manage Images</a></td>
					<td>
					    <form class="inline" action="{{path('project_clone',{id:project.id}) }}" method="POST">
					    <button class="btn btn-link" type="submit">Clone</button>
					    </form>
						<a class="btn btn-link" href="{{ path('project_update',{id:project.id}) }}">Edit</a>
						<form class="inline" action="{{ path('project_delete',{id:project.id}) }}" method="POST">
						    <input type="hidden" name="_method" id="_method" value="DELETE"/>
						    <button class="btn btn-link" type="submit">Remove</a>
						</form>
					</td>
				</tr>
				{% endfor %}
			</tbody>
		</table>
		{% else %}
			<h3 class="text-warning">No Project found</h3>
			<p><a href="{{path('project_new')}}">Create a project</a></p>
		{% endif %}
	{% endblock%}
HERE;


$templates['project_form'] = <<<HERE
		{# @node @silex display a Symfony form by field #}
		{{form_start(form)}}
		{{ form_errors(form) }}
		{% for field in form %}
		<div class="form-group">
		        {{form_row(field,{attr:{class:'form-control'}})}}
		 </div>
		{% endfor %}
		<button type="reset" class="btn btn-default">Reset</button>
		<button type="submit" class="btn btn-default">Save</button>
		{{form_end(form)}}
HERE;

/* create a new project */
$templates['project_new'] = <<<HERE
	{%extends 'admin_layout' %}
	{% block admin_content %}
		<header class="lead">
            <ol class="breadcrumb">
                <li><a href="{{path('project_index') }}">Projects</a></li>
                <li class="active">Create</li>
            </ol>
        </header>
		<p class='text-muted'>You'll be able to add images after the project is saved</p>
		{% include 'project_form' with {form:form} %}
	{% endblock %}
	{% block scripts %}
	{{ parent() }}
	{% endblock %}
HERE;

$templates['project_update'] = <<<HERE
	{%extends 'admin_layout' %}
	{% block admin_content %}
        <header class="lead">
        <ol class="breadcrumb">
            <li><a href="{{path('project_index') }}">Projects</a></li>
            <li><a href="{{path('project_read',{id:project.id}) }}">{{project.title}}</a></li>
            <li class="active">Edit</li>
        </ol>
        </header>
		<a class="btn btn-default" href="{{path('image_create',{projectId:project.id})}}">
		Add a new image</a>
		{% include 'project_form' with {form:form} %}
	{% endblock %}
	{% block scripts %}
	{{ parent() }}
	<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js"></script>
	<script src="/static/javascript/jquery-plugins.js"></script>
	{#<script src="/static/javascript/project-new.js"></script>#}
	{% endblock %}
HERE;

$templates['project_read'] = <<<HERE
	{%extends 'admin_layout' %}
	{% block admin_content %}
		<header class="lead">
            <ol class="breadcrumb">
                <li><a href="{{path('project_index')}}">Projects</a></li>
                <li class="active">{{project.title}}</li>
            </ol>
        </header>
		<dl class="dl-horizontal">
		    <dt>Title</dt>
		    <dd>{{project.title}}</dd>
            <dt>Description</dt>
            <dd>{{project.description}}</dd>
            <dt>Client</dt>
            <dd>{{project.client}}</dd>
            <dt>Tags</dt>
            <dd>{{ project.tags | join(', ')}}</dd>
		</dl>
		<p class="row">
			<a class="btn btn-default" href="{{path('project_update',{id:project.id})}}">Edit Project Infos</a>
		    {#<a class="btn btn-default" href="{{path('image_create',{projectId:project.id})}}">Add a new Image</a>#}
		    <a class="btn btn-default" href="{{path('image_upload',{projectId:project.id})}}">Add Images</a>
		    {#<a class="btn btn-default" href="{{path('image_index',{projectId:project.id})}}">Manage Images</a>#}
		</p>
	    <p class="row">
		{% for image in project.images %}
		<article class="col-sm-3 " data-role="image" data-id="{{image.id}}">
		    <section class="thumbnail">
                <figure style="overflow:hidden;height:100px;">
                <a href="{{path('image_read',{projectId:project.id,imageId:image.id}) }}">
                     <img  src="{{path('image_load',{imageId:image.id}) }}" title="{{image.title}} alt="{{image.title}}"/>
                </a>
                </figure>
                <figcaption class="text-muted"><small data-title="{{image.title}}">{{image.title[:20]}}</small></figcaption>
                    <a class="btn btn-default btn-xs"
                        href="{{path('image_update',{projectId:project.id,imageId:image.id}) }}">Edit</a>
                    <form role="form" data-role="image-delete" data-image-id="{{image.id}}"
                    class="inline" method="POST"
                    action="{{path('image_delete',{projectId:project.id,imageId:image.id}) }}">
                        <input type="hidden" name="_method" value="DELETE"/>
                        <button type="submit" data-role="image-delete" class="btn btn-default btn-xs">Remove</button>
                    </form>
                <form role="form"
                    data-role="image-publish" data-image-id="{{image.id}}" class="inline" method="POST"
                    action="{{path('image_publish',{projectId:project.id,imageId:image.id}) }}">
                    <button title="Publish or Unpublish image" class="btn btn-default btn-xs" data-role="image-publish">
                        {% if image.isPublished%}UnPublish{%else%}Publish{%endif%}
                    </button>
                </form>
		    </section>
		</article>
		{%endfor%}
		</p>
	{% endblock %}
    {%block scripts %}
    	{{parent()}}
    	{% include 'jquery' %}
    	{% include 'underscore' %}
        <script type="text/javascript" src="/static/javascript/jquery-observable.js"></script>
    	<!-- index_image scripts -->
    	<script src="/static/javascript/project-read.js"></script>
    {%endblock%}
HERE;

/**
 * IMAGES
 */

$templates['image_index'] = <<<HERE
    {%extends 'admin_layout'%}
    {%block admin_content%}
    	<header class="lead">
            <ol class="breadcrumb">
                <li><a href="{{path('project_index') }}">Projects</a></li>
                <li><a href="{{path('project_read',{id:project.id}) }}">{{project.title}}</a></li>
                <li class="active">Images</li>
            </ol>
        </header>
        <a class="btn btn-default" href="{{path('image_create',{projectId:project.id})}}">Add New</a>
        {%if  project.images|length>0%}
        <table class="table">
            <thead>
                <tr>
                <td></td>
                <td>Title</td>
                <td>Description</td>
                <td></td>
                </tr>
            </thead>
            <tbody>
            {%for image in project.images%}
            <tr>
                <td>
                    <a href="{{path('image_read',{projectId:project.id,imageId:image.id})}}">
                        <img width="100" src="{{path('image_load',{imageId:image.id}) }}" alt="{{image.title}}"/>
                    </a>
                <td>{{image.title}}</td>
                <td>{{image.description}}</td>
                <td>
                <a class="btn btn-link" href="{{path('image_update',{projectId:project.id,imageId:image.id}) }}">Edit</a>
                <form class="inline"
                action="{{path('image_delete',{projectId:project.id,imageId:image.id}) }}"
                method="POST">
                    <input type="hidden" id="_method" name="_method" value="DELETE" />
                    <button class="btn btn-link" type="submit">Remove</button>
                </form>
                </td>
            </tr>
            {%endfor%}
            </tbody>
        </table>
        {%else%}
        <p>No image yet</p>
        {%endif%}
    {%endblock%}
HERE;

$templates['image_read'] = <<<HERE
    {% extends 'admin_layout'%}
    {%block admin_content%}
    <header class="lead">
        <ol class="breadcrumb">
            <li><a href="{{path('project_index') }}">Projects</a></li>
            <li><a href="{{path('project_read',{id:project.id}) }}">{{project.title}}</a></li>
            <li><a href="{{path('image_index',{projectId:project.id}) }}">Images</a></li>
            <li class="active">{{image.title}}</li>
        </ol>
    </header>
    <div class="row">
        <div class="thumbnail">
            <img src="{{path('image_load',{imageId:image.id}) }}" alt="{{image.title}}"/>
        </div>
    </div>
    <dl class="dl-horizontal">
        <dt>title</dt>
        <dd>{{image.title}}</dd>
        <dt>description</dt>
        <dd>{{image.description}}</dd>
    </dl>
    {%endblock%}
HERE;

$templates['image_form'] = <<<HERE
    {{form_start(form)}}
    {% for field in form %}
    <div class="form-group">
        {{ form_row(field,{attr:{class:'form-control'}}) }}
    </div>
    {% endfor %}
    <button type="reset">Reset</button>
    <button type="submit">Submit</button>
    {{form_end(form)}}
HERE;

$templates['image_create'] = <<<HERE
	{%extends 'admin_layout'%}
	{%block admin_content %}
	    <header class="lead">
        <ol class="breadcrumb">
            <li><a href="{{path('project_index',{id:project.id}) }}">Projects</a></li>
            <li><a href="{{path('project_read',{id:project.id}) }}">{{project.title}}</a></li>
            <li><a href="{{path('image_index',{projectId:project.id}) }}">Images</a></li>
            <li class="active">Create</a></li>
        </ol>
        </header>
        {%include 'image_form' with {form:form}%}
	{%endblock%}
HERE;

$templates['image_update'] = <<<HERE
	{%extends 'admin_layout'%}
	{%block admin_content %}
	    <header class="lead">
        <ol class="breadcrumb">
            <li><a href="{{path('project_index',{id:project.id}) }}">Projects</a></li>
            <li><a href="{{path('project_read',{id:project.id}) }}">{{project.title}}</a></li>
            <li><a href="{{path('image_index',{projectId:project.id}) }}">Images</a></li>
            <li><a href="{{path('image_read',{projectId:project.id,imageId:image.id}) }}">{{image.title}}</a></li>
            <li class="active">Update</a></li>
        </ol>
        </header>
		<div class="row">
		<a class="thumbnail col-md-5"
          target="_blank" href="{{path('image_load',{imageId:image.id})}}">
            <img title="{{image.title}}"
            src="{{path('image_load',{imageId:image.id})}}"
            alt="{{image.title}}"/>
        </a>
        </div>
        <hr>
        {%include 'image_form' with {form:form}%}
	{%endblock%}
HERE;

$templates['image_upload'] = <<<HERE
	{%extends 'admin_layout'%}
	{% block admin_content %}
	<header class="lead">
        <ol class="breadcrumb">
            <li><a href="{{path('project_index')}}">Projects</a></li>
            <li><a href="{{path('project_read',{id:project.id}) }}">{{project.title}}</a></li>
            <li><a href="{{path('image_index',{projectId:project.id})}}">Images</a></li>
            <li class="active">Upload Images</li>
        </ol>
    </header>
    <div class="row drop-zone">
        <div class="lead strong no-select background-text">Drop Some Image Files Here</div>
    </div>
    <h3>&nbsp;</h3>
    {{form_start(form,{attr:{id:'upload-form'}}) }}
        <div class="hidden">{{form_widget(form)}}</div>
        <button class="btn btn-lg" type="reset">Clear</button>
        <button class="btn btn-primary btn-lg" type="submit">
             <span class="text-btn-upload">Upload Images</span><span class="glyphicon glyphicon-cloud-upload"> </span>
        </button>
        <a class="btn btn-success btn-lg done" href="{{path('project_read',{id:project.id}) }}">
             Done <span class="glyphicon glyphicon-ok"> </span>
        </a>
    {{form_end(form)}}
    {%endblock%}
    {% block scripts%}
    {{parent()}}
    {%include 'jquery'%}
    {%include 'underscore'%}
    <script type="text/javascript" src="/static/javascript/jquery-observable.js"></script>
    <script type="text/javascript" src="/static/javascript/image-upload.js"></script>
    {%endblock%}
HERE;

/**
 * PAGES
 */
$templates['page_index'] = <<<HERE
    {%extends 'admin_layout'%}
    {%block admin_content%}
    	<header class="lead">
            <ol class="breadcrumb">
                <li class="active">Pages</li>
            </ol>
        </header>
        <a class="btn btn-default" href="{{path('page_create')}}">Add New</a>
        {%if  pages|length>0%}
        <table class="table">
            <thead>
                <tr>
                <td>Title</td>
                <td>Description</td>
                <td style="width:30%"></td>
                </tr>
            </thead>
            <tbody>
            {%for page in pages%}
            <tr>
                <td>
                    <a href="{{path('page_read',{id:page.id})}}">
                        {{page.title}}
                    </a>
                <td>{{page.description}}</td>
                <td>
                <a class="btn btn-link" href="{{path('page_update',{id:page.id}) }}">Edit</a>
                <form class="inline"
                action="{{path('page_delete',{id:page.id}) }}"
                method="POST">
                    <input type="hidden" id="_method" name="_method" value="DELETE" />
                    <button class="btn btn-link" type="submit">Remove</button>
                </form>
                </td>
            </tr>
            {%endfor%}
            </tbody>
        </table>
        {%else%}
        <p>No Page yet</p>
        {%endif%}
    {%endblock%}
HERE;
$templates['page_create'] = <<<HERE
	{%extends 'admin_layout'%}
	{%block admin_content %}
	    <header class="lead">
        <ol class="breadcrumb">
            <li><a href="{{path('page_index') }}">Pages</a></li>
            <li class="active">New</li>
        </ol>
        </header>
        {{form_start(form)}}
            {% for field in form %}
            <div class="form-group">
                {{form_row(field,{attr:{class:'form-control'}}) }}
            </div>
            {%endfor%}
            <button type="reset">Reset</button>
            <button type="submit">Save</button>
        {{form_end(form)}}
	{%endblock%}
HERE;
$templates['page_update'] = <<<HERE
	{%extends 'admin_layout'%}
	{%block admin_content %}
	    <header class="lead">
        <ol class="breadcrumb">
            <li><a href="{{path('page_index') }}">Pages</a></li>
            <li><a href="{{path('page_read',{id:page.id}) }}">{{page.title}}</a></li>
            <li class="active">Update</li>
        </ol>
        </header>
        {{form_start(form)}}
            {% for field in form %}
            <div class="form-group">
                {{form_row(field,{attr:{class:'form-control'}}) }}
            </div>
            {%endfor%}
            <button type="reset">Reset</button>
            <button type="submit">Save</button>
        {{form_end(form)}}
	{%endblock%}
HERE;
$templates['page_read'] = <<<HERE
	{%extends 'admin_layout'%}
	{%block admin_content %}
	    <header class="lead">
        <ol class="breadcrumb">
            <li><a href="{{path('page_index') }}">Pages</a></li>
            <li class="active">{{page.title}}</li>
        </ol>
        </header>
        <div class="row">
        <dl>
        <dt>Title</dt>
        <dd>{{page.title}}</dd>
         <dt>Category</dt>
        <dd>{{page.category}}</dd>
        <dt>Language</dt>
        <dd>{{page.language}}</dd>
         <dt>Description</dt>
        <dd>{{page.description}}</dd>
         <dt>Content</dt>
        <dd>{{page.content}}</dd>
        </dl>
        </div>
	{%endblock%}
HERE;
/**
 * UTITITLES
 */
$templates['jquery'] = <<<HERE
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
HERE;
$templates['underscore'] = <<<HERE
	<script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.6.0/underscore-min.js">
	</script>
HERE;
return $templates;
