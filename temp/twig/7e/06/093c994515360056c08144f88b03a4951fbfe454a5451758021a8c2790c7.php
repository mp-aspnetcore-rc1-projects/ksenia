<?php

/* admin_layout */
class __TwigTemplate_7e06093c994515360056c08144f88b03a4951fbfe454a5451758021a8c2790c7 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("layout");

        $this->blocks = array(
            'styles' => array($this, 'block_styles'),
            'content' => array($this, 'block_content'),
            'admin_content' => array($this, 'block_admin_content'),
            'scripts' => array($this, 'block_scripts'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "layout";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_styles($context, array $blocks = array())
    {
        // line 3
        echo "\t\t";
        // line 4
        echo "\t\t<link rel='stylesheet' href='//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css'>
\t\t";
        // line 6
        echo "\t\t<link rel='stylesheet' href='//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css'>
\t\t";
        // line 8
        echo "\t\t<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
\t\t";
        // line 10
        echo "\t\t<link rel=\"stylesheet\" href=\"/static/css/styles.css\">
\t";
    }

    // line 12
    public function block_content($context, array $blocks = array())
    {
        // line 13
        echo "\t\t<section class='row'>
\t\t<aside class='col-md-3'>
\t\t";
        // line 15
        $this->env->loadTemplate("admin_nav")->display($context);
        // line 16
        echo "\t\t</aside>
\t\t<article class='col-md-9'>
\t\t\t";
        // line 18
        $this->displayBlock('admin_content', $context, $blocks);
        // line 20
        echo "\t\t</article>
\t\t</section>
\t";
    }

    // line 18
    public function block_admin_content($context, array $blocks = array())
    {
        // line 19
        echo "\t\t\t";
    }

    // line 23
    public function block_scripts($context, array $blocks = array())
    {
        // line 24
        echo "\t\t";
        // line 25
        echo "\t\t<script src='https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'></script>
\t\t";
        // line 27
        echo "\t\t<script src='//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js'></script>
\t";
    }

    public function getTemplateName()
    {
        return "admin_layout";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  86 => 27,  83 => 25,  81 => 24,  78 => 23,  74 => 19,  71 => 18,  65 => 20,  63 => 18,  59 => 16,  57 => 15,  53 => 13,  50 => 12,  45 => 10,  42 => 8,  39 => 6,  36 => 4,  34 => 3,  31 => 2,  28 => 2,);
    }
}
