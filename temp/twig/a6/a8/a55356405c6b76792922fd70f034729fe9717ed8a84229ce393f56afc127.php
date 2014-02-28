<?php

/* image_update */
class __TwigTemplate_a6a8a55356405c6b76792922fd70f034729fe9717ed8a84229ce393f56afc127 extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = $this->env->loadTemplate("admin_layout");

        $this->blocks = array(
            'admin_content' => array($this, 'block_admin_content'),
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
        echo "\t\t<header class=\"lead\">Update image for project <a href=\"";
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("project_read", array("id" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "title"), "html", null, true);
        echo "</a></header>
\t\t<div class=\"row\">
\t\t<a class=\"thumbnail col-md-5\"
          target=\"_blank\" href=\"";
        // line 6
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("image_load", array("imageId" => $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "id"))), "html", null, true);
        echo "\">
            <img title=\"";
        // line 7
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "title"), "html", null, true);
        echo "\"
            src=\"";
        // line 8
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("image_load", array("imageId" => $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "id"))), "html", null, true);
        echo "\"
            alt=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "title"), "html", null, true);
        echo "\"/>
        </a>
        </div>
        <hr>
        ";
        // line 13
        $this->env->loadTemplate("image_form")->display(array_merge($context, array("form" => (isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")))));
        // line 14
        echo "\t";
    }

    public function getTemplateName()
    {
        return "image_update";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  61 => 14,  59 => 13,  52 => 9,  48 => 8,  44 => 7,  40 => 6,  31 => 3,  28 => 2,);
    }
}
