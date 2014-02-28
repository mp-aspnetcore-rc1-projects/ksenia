<?php

/* image_form */
class __TwigTemplate_534bcee8951ed4264f25c211c4ff79da7f1cd7efe934e798cac739dd28cb9a11 extends Twig_Template
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
        echo "    ";
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_start');
        echo "
    ";
        // line 2
        $context['_parent'] = (array) $context;
        $context['_seq'] = twig_ensure_traversable((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")));
        foreach ($context['_seq'] as $context["_key"] => $context["field"]) {
            // line 3
            echo "    <div class=\"form-group\">
        ";
            // line 4
            echo $this->env->getExtension('form')->renderer->searchAndRenderBlock((isset($context["field"]) ? $context["field"] : $this->getContext($context, "field")), 'row', array("attr" => array("class" => "form-control")));
            echo "
    </div>
    ";
        }
        $_parent = $context['_parent'];
        unset($context['_seq'], $context['_iterated'], $context['_key'], $context['field'], $context['_parent'], $context['loop']);
        $context = array_intersect_key($context, $_parent) + $_parent;
        // line 7
        echo "    <button type=\"reset\">Reset</button>
    <button type=\"submit\">Submit</button>
    ";
        // line 9
        echo         $this->env->getExtension('form')->renderer->renderBlock((isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")), 'form_end');
    }

    public function getTemplateName()
    {
        return "image_form";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  24 => 2,  19 => 1,  61 => 14,  59 => 13,  52 => 9,  48 => 8,  44 => 9,  40 => 7,  31 => 4,  28 => 3,);
    }
}
