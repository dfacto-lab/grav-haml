<?php
/**
 * © Copyright D-facto lab sàrl
 * User: Numa Schmeder
 * Date: 04.01.18
 * Time: 15:32
 * Project: Fabbrica
 */

namespace Grav\Plugin;

use MtHaml\Support\Twig\Loader;

class GravHamlLoader  extends Loader
{

    /**
     * {@inheritdoc}
     */
    public function getSource($name)
    {
        $source = $this->loader->getSource($name);
        if ('haml' === pathinfo($name, PATHINFO_EXTENSION) || preg_match('#\.haml\.twig$#', $name, $match)) {
            $source = $this->env->compileString($source, $name);
        } elseif (preg_match('#^\s*{%\s*haml\s*%}#', $source, $match)) {
            $padding = str_repeat(' ', strlen($match[0]));
            $source = $padding . substr($source, strlen($match[0]));
            $source = $this->env->compileString($source, $name);
        }

        return $source;
    }
}