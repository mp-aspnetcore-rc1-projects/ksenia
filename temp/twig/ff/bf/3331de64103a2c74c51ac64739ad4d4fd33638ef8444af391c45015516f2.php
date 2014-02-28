<?php

/* project_index */
class __TwigTemplate_ffbf3331de64103a2c74c51ac64739ad4d4fd33638ef8444af391c45015516f2 extends Twig_Template
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
        echo "\t\t\t\t<header class=\"lead\">
            <ol class=\"breadcrumb\">
                <li> class=\"active\">Projects</li>
            </ol>
        </header>
\t\t";
        // line 8
        if (((isset($context["projects"]) ? $context["projects"] : $this->getContext($context, "projects")) && (twig_length_filter($this->env, (isset($context["projects"]) ? $context["projects"] : $this->getContext($context, "projects"))) > 0))) {
            // line 9
            echo "\t\t<table class=\"table\">
\t\t\t\t<thead>
\t\t\t\t<tr>
\t\t\t\t\t<th>Title</th>
\t\t\t\t\t<th></th>
\t\t\t\t\t<th></th>
\t\t\t\t\t<th></th>
\t\t\t\t</tr>
\t\t\t</thead>
\t\t\t<tbody>
\t\t\t\t";
            // line 19
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["projects"]) ? $context["projects"] : $this->getContext($context, "projects")));
            foreach ($context['_seq'] as $context["_key"] => $context["project"]) {
                // line 20
                echo "\t\t\t\t<tr>
\t\t\t\t\t<td><a href=\"";
                // line 21
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("project_read", array("id" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "title"), "html", null, true);
                echo "</a></td>
\t\t\t\t\t<td>";
                // line 22
                echo twig_escape_filter($this->env, (twig_slice($this->env, $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "description"), 0, 50) . "..."), "html", null, true);
                echo "</td>
\t\t\t\t\t<td><a class=\"btn btn-link\"  href=\"";
                // line 23
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("image_index", array("projectId" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
                echo "\">Manage Images</a></td>
\t\t\t\t\t<td>
\t\t\t\t\t    <form class=\"inline\" action=\"";
                // line 25
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("project_clone", array("id" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
                echo "\" method=\"POST\">
\t\t\t\t\t    <button class=\"btn btn-link\" type=\"submit\">Clone</button>
\t\t\t\t\t    </form>
\t\t\t\t\t\t<a class=\"btn btn-link\" href=\"";
                // line 28
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("project_update", array("id" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
                echo "\">Edit</a>
\t\t\t\t\t\t<a class=\"btn btn-link\" href=\"";
                // line 29
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("project_delete", array("id" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
                echo "\">Remove</a>
\t\t\t\t\t</td>
\t\t\t\t</tr>
\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['project'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 33
            echo "\t\t\t</tbody>
\t\t</table>
\t\t";
        } else {
            // line 36
            echo "\t\t\t<h3 class=\"text-warning\">No Project found</h3>
\t\t\t<p><a href=\"";
            // line 37
            echo $this->env->getExtension('routing')->getPath("project_new");
            echo "\">Create a project</a></p>
\t\t";
        }
        // line 39
        echo "\t";
    }

    public function getTemplateName()
    {
        return "project_index";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  107 => 39,  102 => 37,  99 => 36,  94 => 33,  84 => 29,  80 => 28,  74 => 25,  69 => 23,  65 => 22,  59 => 21,  56 => 20,  52 => 19,  40 => 9,  38 => 8,  31 => 3,  28 => 2,);
    }
}
