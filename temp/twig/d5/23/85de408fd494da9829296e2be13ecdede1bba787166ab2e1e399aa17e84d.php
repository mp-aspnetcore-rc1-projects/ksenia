<?php

/* image_create */
class __TwigTemplate_d52385de408fd494da9829296e2be13ecdede1bba787166ab2e1e399aa17e84d extends Twig_Template
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
        echo "\t\t<header class=\"lead\">Add a new image to \"<em>";
        echo twig_escape_filter($this->env, $this->getAttribute((isset($context["project"]) ? $context["project"] : $this->getContext($context, "project")), "title"), "html", null, true);
        echo "</em> \"</header>
        ";
        // line 4
        $this->env->loadTemplate("image_form")->display(array_merge($context, array("form" => (isset($context["form"]) ? $context["form"] : $this->getContext($context, "form")))));
        // line 5
        echo "\t";
    }

    public function getTemplateName()
    {
        return "image_create";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  38 => 5,  36 => 4,  31 => 3,  28 => 2,);
    }
}
