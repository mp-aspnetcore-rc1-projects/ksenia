<?php

/* project_form */
class __TwigTemplate_10fe3dc614854936d2c86d49695ade6d8c1d179678ff46897d1354d57dad6e2b extends Twig_Template
{
    public function __construct(Twig_Environment $env)
    {
        parent::__construct($env);

        $this->parent = false;

        $this->blocks = array(
        );
    }

    protected function doDisplay(array $context, array $blocks = array())
    {
        // line 1
        echo "\t\t";
        // line 2
        echo "\t\t";
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_start');
        echo "
\t\t";
        // line 3
        echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'errors');
        echo "
\t\t";
        // line 4
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")));
        foreach ($context['_seq'] as $context["_key"] => $context["field"]) {
            // line 5
            echo "\t\t<div class=\"form-group\">
\t\t        ";
            // line 6
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["field"]) ? $context["field"] : $this->getContext($context, "field")), 'row', array("attr" => array("class" => "form-control")));
            echo "
\t\t </div>
\t\t";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['field'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 9
        echo "\t\t<button type=\"reset\" class=\"btn btn-default\">Reset</button>
\t\t<button type=\"submit\" class=\"btn btn-default\">Save</button>
\t\t";
        // line 11
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_end');
    }

    public function getTemplateName()
    {
        return "project_form";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  37 => 6,  34 => 5,  30 => 4,  26 => 3,  21 => 2,  19 => 1,  60 => 14,  53 => 10,  50 => 11,  46 => 9,  44 => 7,  39 => 5,  35 => 4,  32 => 3,  29 => 2,);
    }
}
