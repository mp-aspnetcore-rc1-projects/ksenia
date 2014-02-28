<?php

/* project_update */
class __TwigTemplate_ec69da21d766441d92bb50924488033eade38939918325ea3c4f45a745efe39e extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("admin_layout");

        $this->blocks = array(
            'admin_content' => array($this, 'block_admin_content'),
            'scripts' => array($this, 'block_scripts'),
        );
    }

    protected function doGetParent(array $context)
    {
        return "admin_layout";
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        $this->parent->display($context, array_merge($this->blocks, $blocks));
    }

    // line 2
    public function block_admin_content($context, array $blocks = array())
    {
        // line 3
        echo "\t\t<h2>EDIT PROJECT</h2>
\t\t<p>";
        // line 4
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "title"), "html", null, true);
        echo "</p>
\t\t<a class=\"btn btn-default\" href=\"";
        // line 5
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("image_create", array("projectId" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
        echo "\">
\t\tAdd a new image</a>
\t\t";
        // line 7
        $this->env->loadTemplate("project_form")->display(array_merge($context, array("form" => (isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")))));
        // line 8
        echo "\t";
    }

    // line 9
    public function block_scripts($context, array $blocks = array())
    {
        // line 10
        echo "\t";
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
\t<script src=\"//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.5.2/underscore-min.js\"></script>
\t<script src=\"/static/javascript/jquery-plugins.js\"></script>
\t";
        // line 14
        echo "\t";
    }

    public function getTemplateName()
    {
        return "project_update";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  60 => 14,  53 => 10,  50 => 9,  46 => 8,  44 => 7,  39 => 5,  35 => 4,  32 => 3,  29 => 2,);
    }
}
