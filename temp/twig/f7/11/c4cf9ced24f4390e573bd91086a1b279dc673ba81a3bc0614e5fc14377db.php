<?php

/* project_read */
class __TwigTemplate_f711c4cf9ced24f4390e573bd91086a1b279dc673ba81a3bc0614e5fc14377db extends Twig_Template
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
        echo "\t\t<header class=\"lead\">
            <ol class=\"breadcrumb\">
                <li><a href=\"";
        // line 5
        echo $this->env->getExtension('routing')->getPath("project_index");
        echo "\">Projects</a></li>
                <li class=\"active\">";
        // line 6
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "title"), "html", null, true);
        echo "</li>
            </ol>
        </header>
\t\t<dl class=\"dl-horizontal\">
\t\t    <dt>Title</dt>
\t\t    <dd>";
        // line 11
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "title"), "html", null, true);
        echo "</dd>
            <dt>Description</dt>
            <dd>";
        // line 13
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "description"), "html", null, true);
        echo "</dd>
            <dt>Client</dt>
            <dd>";
        // line 15
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "client"), "html", null, true);
        echo "</dd>
            <dt>Tags</dt>
            <dd>";
        // line 17
        echo twig_escape_filter($this->env, twig_join_filter($this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "tags"), ", "), "html", null, true);
        echo "</dd>
\t\t</dl>
\t\t<p class=\"row\">
\t\t\t<a class=\"btn btn-default\" href=\"";
        // line 20
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("project_update", array("id" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
        echo "\">Edit</a>
\t\t    <a class=\"btn btn-default\" href=\"";
        // line 21
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("image_index", array("projectId" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
        echo "\">Manage Images</a>
\t\t    <a class=\"btn btn-default\" href=\"";
        // line 22
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("image_create", array("projectId" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
        echo "\">Add a new Image</a>
\t\t</p>
\t\t";
        // line 24
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable(twig_array_batch($this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "images"), 5));
        foreach ($context['_seq'] as $context["_key"] => $context["row"]) {
            // line 25
            echo "\t    <p class=\"row\">
\t\t";
            // line 26
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["row"]) ? $context["row"] : $this->getContext($context, "row")));
            foreach ($context['_seq'] as $context["_key"] => $context["image"]) {
                // line 27
                echo "\t\t<div class=\"col-md-2 thumbnail\">
\t\t    <a href=\"";
                // line 28
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("image_read", array("projectId" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"), "imageId" => $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "id"))), "html", null, true);
                echo "\">
\t\t        <img height=\"150\" src=\"";
                // line 29
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("image_load", array("imageId" => $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "id"))), "html", null, true);
                echo "\" alt=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "title"), "html", null, true);
                echo "\"/>
\t\t    </a>
\t\t    <h4 class=\"text-muted\"><small>";
                // line 31
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "title"), "html", null, true);
                echo "</small></h4>
\t\t    <small><a class=\"btn btn-default btn-xs\" href=\"";
                // line 32
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("image_update", array("projectId" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"), "imageId" => $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "id"))), "html", null, true);
                echo "\">Edit</a></small>
\t\t    <form class=\"inline\" method=\"POST\" action=\"";
                // line 33
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("image_delete", array("projectId" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"), "imageId" => $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "id"))), "html", null, true);
                echo "\">
\t\t        <input type=\"hidden\" name=\"_method\" value=\"DELETE\"/>
\t\t        <button type=\"submit\" class=\"btn btn-default btn-xs\">Remove</button>
\t\t    </form>

\t\t</div>
\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['image'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 40
            echo "\t\t</p>
\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['row'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 42
        echo "\t";
    }

    // line 43
    public function block_scripts($context, array $blocks = array())
    {
        // line 44
        echo "\t";
        $this->displayParentBlock("scripts", $context, $blocks);
        echo "
\t";
    }

    public function getTemplateName()
    {
        return "project_read";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  142 => 44,  139 => 43,  135 => 42,  128 => 40,  115 => 33,  111 => 32,  107 => 31,  100 => 29,  96 => 28,  93 => 27,  89 => 26,  86 => 25,  82 => 24,  77 => 22,  73 => 21,  69 => 20,  63 => 17,  58 => 15,  53 => 13,  48 => 11,  40 => 6,  36 => 5,  32 => 3,  29 => 2,);
    }
}
