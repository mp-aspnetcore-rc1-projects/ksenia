<?php

/* admin_nav */
class __TwigTemplate_de6a27a03e217c29c0016634a71bcb5babeff45a70317412839e083fdd0a7958 extends Twig_Template
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
        echo "\t<ul class=\"list-group\">
\t\t<li class=\"list-group-item\"><strong><a href=\"";
        // line 2
        echo $this->env->getExtension('routing')->getPath("admin_index");
        echo "\">DASHBOARD</a></strong></li>
\t\t<li class=\"list-group-item text-muted uppercase\">
\t\t\t<strong>PROJECTS</strong>
\t\t</li>
\t\t<li class=\"list-group-item\"><a href=\"";
        // line 6
        echo $this->env->getExtension('routing')->getPath("project_index");
        echo "\">Manage Projects</a></li>
\t\t<li class=\"list-group-item\"><a href=\"";
        // line 7
        echo $this->env->getExtension('routing')->getPath("project_new");
        echo "\">Create a new project</a></li>
\t</ul>";
    }

    public function getTemplateName()
    {
        return "admin_nav";
    }

    public function isTraitable()
    {
        return false;
    }

    public function getDebugInfo()
    {
        return array (  33 => 7,  29 => 6,  22 => 2,  19 => 1,  85 => 19,  82 => 18,  77 => 16,  72 => 10,  62 => 5,  55 => 18,  52 => 17,  46 => 15,  40 => 11,  37 => 10,  35 => 5,  26 => 2,  23 => 1,  86 => 27,  83 => 25,  81 => 24,  78 => 23,  74 => 19,  71 => 18,  65 => 6,  63 => 18,  59 => 16,  57 => 20,  53 => 13,  50 => 16,  45 => 10,  42 => 8,  39 => 6,  36 => 4,  34 => 3,  31 => 4,  28 => 2,);
    }
}
