<?php

/* layout */
class __TwigTemplate_a772433e1b9646c0a01524b90348b861f530ccbe96722ebb718fb5a1ac406d71 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
            'metas' => array($this, 'block_metas'),
            'styles' => array($this, 'block_styles'),
            'content' => array($this, 'block_content'),
            'scripts' => array($this, 'block_scripts'),
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "\t<!doctype html>
\t<html lang='";
        // line 2
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "locale"), "html", null, true);
        echo "'>
\t\t<head>
\t\t\t<title>";
        // line 4
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "title"), "html", null, true);
        echo "</title>
\t\t\t";
        // line 5
        $this->displayBlock('metas', $context, $blocks);
        // line 10
        echo "\t\t\t";
        $this->displayBlock('styles', $context, $blocks);
        // line 11
        echo "\t\t</head>
\t\t<body>
\t\t\t<main class='container'>
\t\t\t\t<noscript><h2 class=\"alert alert-warning\">Please Enable Javascript!</h2></noscript>
\t\t\t\t<h1>";
        // line 15
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["app"]) ? $context["app"] : $this->getContext($context, "app")), "title"), "html", null, true);
        echo "</h1>
\t\t\t\t";
        // line 16
        $this->displayBlock('content', $context, $blocks);
        // line 17
        echo "\t\t\t</main>
\t\t\t";
        // line 18
        $this->displayBlock('scripts', $context, $blocks);
        // line 20
        echo "\t\t</body>
\t</html>";
    }

    // line 5
    public function block_metas($context, array $blocks = array())
    {
        // line 6
        echo "\t\t\t\t<meta charset='UTF-8' />
\t\t\t\t<meta http-equiv='X-UA-Compatible' content='IE=edge'/>
\t\t\t\t<meta name='viewport' content='width=device-width, initial-scale=1'/>
\t\t\t";
    }

    // line 10
    public function block_styles($context, array $blocks = array())
    {
    }

    // line 16
    public function block_content($context, array $blocks = array())
    {
    }

    // line 18
    public function block_scripts($context, array $blocks = array())
    {
        // line 19
        echo "\t\t\t";
    }

    public function getTemplateName()
    {
        return "layout";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  85 => 19,  82 => 18,  77 => 16,  72 => 10,  62 => 5,  55 => 18,  52 => 17,  46 => 15,  40 => 11,  37 => 10,  35 => 5,  26 => 2,  23 => 1,  86 => 27,  83 => 25,  81 => 24,  78 => 23,  74 => 19,  71 => 18,  65 => 6,  63 => 18,  59 => 16,  57 => 20,  53 => 13,  50 => 16,  45 => 10,  42 => 8,  39 => 6,  36 => 4,  34 => 3,  31 => 4,  28 => 2,);
    }
}
