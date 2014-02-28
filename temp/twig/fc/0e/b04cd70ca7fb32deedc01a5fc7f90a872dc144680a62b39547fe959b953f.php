<?php

/* image_index */
class __TwigTemplate_fc0eb04cd70ca7fb32deedc01a5fc7f90a872dc144680a62b39547fe959b953f extends Twig_Template
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
        echo "    \t<header class=\"lead\">
            <ol class=\"breadcrumb\">
                <li><a href=\"";
        // line 5
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("project_read", array("id" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
        echo "\">";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "title"), "html", null, true);
        echo "</a></li>
                <li class=\"active\">Images from project ";
        // line 6
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "title"), "html", null, true);
        echo "</li>
            </ol>
        </header>
        <a class=\"btn btn-default\" href=\"";
        // line 9
        echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("image_create", array("projectId" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"))), "html", null, true);
        echo "\">Add New</a>
        ";
        // line 10
        if ((twig_length_filter($this->env, $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "images")) > 0)) {
            // line 11
            echo "        <table class=\"table\">
            <thead>
                <tr>
                <td></td>
                <td>Title</td>
                <td>Description</td>
                <td></td>
                </tr>
            </thead>
            <tbody>
            ";
            // line 21
            $context['_parent'] = (array) $context;
            $context['_seq'] = twig_ensure_traversable($this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "images"));
            foreach ($context['_seq'] as $context["_key"] => $context["image"]) {
                // line 22
                echo "            <tr>
                <td>
                    <a href=\"";
                // line 24
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("image_read", array("projectId" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"), "imageId" => $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "id"))), "html", null, true);
                echo "\">
                        <img width=\"100\" src=\"";
                // line 25
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("image_load", array("imageId" => $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "id"))), "html", null, true);
                echo "\" alt=\"";
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "title"), "html", null, true);
                echo "\"/>
                    </a>
                <td>";
                // line 27
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "title"), "html", null, true);
                echo "</td>
                <td>";
                // line 28
                echo twig_escape_filter($this->env, $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "description"), "html", null, true);
                echo "</td>
                <td>
                <a class=\"btn btn-link\" href=\"";
                // line 30
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("image_update", array("projectId" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"), "imageId" => $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "id"))), "html", null, true);
                echo "\">Edit</a>
                <form class=\"inline\"
                action=\"";
                // line 32
                echo twig_escape_filter($this->env, $this->env->getExtension('routing')->getPath("image_delete", array("projectId" => $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "id"), "imageId" => $this->getAttribute((isset($context["image"]) ? $context["image"] : $this->getContext($context, "image")), "id"))), "html", null, true);
                echo "\"
                method=\"POST\">
                    <input type=\"hidden\" id=\"_method\" name=\"_method\" value=\"DELETE\" />
                    <button class=\"btn btn-link\" type=\"submit\">Remove</button>
                </form>
                </td>
            </tr>
            ";
            }
            $_parent = $context['_parent'];
            unset($context['_seq'], $context['_iterated'], $context['_key'], $context['image'], $context['_parent'], $context['loop']);
            $context = array_intersect_key($context, $_parent) + $_parent;
            // line 40
            echo "            </tbody>
        </table>
        ";
        } else {
            // line 43
            echo "        <p>No image yet</p>
        ";
        }
        // line 45
        echo "
    ";
    }

    public function getTemplateName()
    {
        return "image_index";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  121 => 45,  117 => 43,  112 => 40,  98 => 32,  93 => 30,  88 => 28,  84 => 27,  77 => 25,  73 => 24,  69 => 22,  65 => 21,  53 => 11,  51 => 10,  47 => 9,  41 => 6,  35 => 5,  31 => 3,  28 => 2,);
    }
}
