<?php

/* project_index */
class __TwigTemplate_e3d91be65ccb5d2f435ce582ac55448e7b68aa7e464daa333203c127a058a1c5 extends Twig_Template
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
        echo "\t\t<h2>Projects</h2>
\t\t";
        // line 4
        if (((isset($context["projects"]) ? $context["projects"] : $this->getContext($context, "projects")) && (twig_length_filter($this->env, (isset($context["projects"]) ? $context["projects"] : $this->getContext($context, "projects"))) > 0))) {
            // line 5
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
            // line 15
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable((isset($context["projects"]) ? $context["projects"] : $this->getContext($context, "projects")));
            foreach ($context['_seq'] as $context["_key"] => $context["project"]) {
                // line 16
                echo "\t\t\t\t<tr>
\t\t\t\t\t<td><a href=\"";
                // line 17
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("project_read", array("id" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
                echo "\">";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "title"), "html", null, true);
                echo "</a></td>
\t\t\t\t\t<td>";
                // line 18
                echo twig_escape_filter($this->env, (twig_slice($this->env, $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "description"), 0, 50) . "..."), "html", null, true);
                echo "</td>
\t\t\t\t\t<td><a href=\"";
                // line 19
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("image_index", array("projectId" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
                echo "\">Manage Images</a></td>
\t\t\t\t\t<td>
\t\t\t\t\t    <form class=\"inline\" action=\"";
                // line 21
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("project_clone", array("id" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
                echo "\" method=\"POST\">
\t\t\t\t\t    <button class=\"btn btn-link\" type=\"submit\">Clone</button>
\t\t\t\t\t    </form>
\t\t\t\t\t\t<a class=\"btn btn-link\" href=\"";
                // line 24
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("project_update", array("id" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
                echo "\">Edit</a>
\t\t\t\t\t\t<a class=\"btn btn-link\" href=\"";
                // line 25
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("project_delete", array("id" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
                echo "\">Remove</a>
\t\t\t\t\t</td>
\t\t\t\t</tr>
\t\t\t\t";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['project'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 29
            echo "\t\t\t</tbody>
\t\t</table>
\t\t";
        } else {
            // line 32
            echo "\t\t\t<h3 class=\"text-warning\">No Project found</h3>
\t\t\t<p><a href=\"";
            // line 33
            echo $this->env->getExtension('routing')->getPath("project_new");
            echo "\">Create a project</a></p>
\t\t";
        }
        // line 35
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
        return array (  103 => 35,  98 => 33,  95 => 32,  90 => 29,  80 => 25,  76 => 24,  70 => 21,  65 => 19,  61 => 18,  55 => 17,  52 => 16,  48 => 15,  36 => 5,  34 => 4,  31 => 3,  28 => 2,);
    }
}
