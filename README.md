# Grav Haml Plugin

**Warning this plugin is in Alpha stage**

The **Haml** Plugin is for [Grav CMS](http://github.com/getgrav/grav). Haml Twig template support. 
With this plugin installed you can write your template files in HAML using all the twig power. 
The benefit are simple 

## Installation

Installing the Haml plugin can be done in one of two ways. The GPM (Grav Package Manager) installation method enables you to quickly and easily install the plugin with a simple terminal command, while the manual method enables you to do so via a zip file.

### GPM Installation (Preferred)

The simplest way to install this plugin is via the [Grav Package Manager (GPM)](http://learn.getgrav.org/advanced/grav-gpm) through your system's terminal (also called the command line).  From the root of your Grav install type:

    bin/gpm install haml

This will install the Haml plugin into your `/user/plugins` directory within Grav. Its files can be found under `/your/site/grav/user/plugins/haml`.

### Manual Installation

To install this plugin, just download the zip version of this repository and unzip it under `/your/site/grav/user/plugins`. Then, rename the folder to `haml`. You can find these files on [GitHub](https://github.com/d-facto/grav-plugin-haml) or via [GetGrav.org](http://getgrav.org/downloads/plugins#extras).

You should now have all the plugin files under

    /your/site/grav/user/plugins/haml
	
> NOTE: This plugin is a modular component for Grav which requires [Grav](http://github.com/getgrav/grav) and the [Error](https://github.com/getgrav/grav-plugin-error) and [Problems](https://github.com/getgrav/grav-plugin-problems) to operate.

## Configuration

Before configuring this plugin, you should copy the `user/plugins/haml/haml.yaml` to `user/config/plugins/haml.yaml` and only edit that copy.

Here is the default configuration and an explanation of available options:

```yaml
enabled: true
```

## Usage

You have 2 options to use haml in your twig template. One options is fairly simple, inside your html.twig file you add a special line so the parser recognize it's haml markup instead of twig html.
The second options requires to modify core grav files, so grav scans for files ending with *.haml.twig on top of files ending with *.html.twig. You won't need to add a special header line in your file 
as the plugin will kick in if you file name ends with haml.twig. 

### 1. Using haml inside twig template
Add the following line to your template files where you need haml code to be parsed:
```haml
{%haml%}
!!! 5
%html(lang="en")
  %head  
    %title
      Hello World
  %body      
    %p
      Hello World
``` 
Warning! You cannot mix normal html/twig with haml/twig. 

### 2. Auto detecting haml twig template based on file name
You can modify the core of grav, so it loads templates with extension *.haml.twig. By default Grav only loads file with extension *.html.twig.
Below are the modifications:

#### Modify Grav core to auto detect Haml templates

Line 60 of Grav\Common\Twig\Twig
Add
```
    public $templateExt = TEMPLATE_EXT;
```

Ligne 240 modify
```
 if ($item->modularTwig()) {
                $twig_vars['content'] = $content;
                $template = $item->template() . $this->templateExt;
                $output = $content = $local_twig->render($template, $twig_vars);
 }
```

Line 352 modify
````
 if ($ext != $this->templateExt) {
                try {
                    $page->templateFormat('html');
                    $output = $this->twig->render($page->template() . $this->templateExt, $vars + $twig_vars);

                } catch (\Twig_Error_Loader $e) {
                    throw new \RuntimeException($error_msg, 400, $e);
                }
            } else {
                throw new \RuntimeException($error_msg, 400, $e);
 }
````

Line 72 et 74 de Grav\Common\Page\Types
```php 
public function scanTemplates($uri) {
       $options = [
            'compare' => 'Filename',
            'pattern' => '/\.(haml|html)\.twig$/',
            'filters' => [
                'value' => '/\.(haml|html)\.twig$/'
            ],
            'value' => 'Filename',
            'recursive' => false
        ];
} 
```

### 3. Haml twig template example
```haml
- set theme_config = attribute(config.themes, config.system.pages.theme)
!!! 5
%html(lang="#{grav.language.getActive ?: theme_config.default_lang}")
  %head
    - block head
      %meta(charset="utf-8")
      - set page_title = header.title | replace({',':''})
      %title
        #{ page_title ? page_title ~ ' |' | e('html') : ''} #{site.title|e('html')}

      %meta(http-equiv="X-UA-Compatible" content="IE=edge")
      %meta(name="viewport" content="width=device-width, initial-scale=1")

      %link(rel="icon" type="image/png" href="#{ url('theme://images/logo.png') }")
      %link(rel="canonical" href="#{ page.url(true, true) }")
      - block stylesheets
        - do assets.addCss('theme://css/style.css?698866', 98)
      = assets.css()
      - include 'partials/metadata.haml.twig'
  %body(id="#{ page.slug }" class="#{ page.header.body_classes } #{body_class} ")
    - block navigation
      //include navigation partial 
      - include 'partials/navigation.haml.twig'

    - block body
      .body.clearfix
        - block content
          Page content starts here


    - block javascripts
      - do assets.addJs('theme://js/3d.js')
      
  %script{src:"#{url('theme://bower_components/requirejs/require.js')}", type: 'text/javascript'}
  :javascript
      requirejs.config({
        baseUrl: '#{url("theme://js/")}',
        paths: {
          jquery: '../bower_components/jquery/dist/jquery.min',
        },
        shim: {
        }
      });
  = assets.js()

```

You can fin more information on using haml php twig compatibility on Arnaud Le Blanc website.
 <https://github.com/arnaud-lb/MtHaml>

## Credits

**This code is based on mthaml by Arnaud Le Blanc** 
More information and tutorials on <https://github.com/arnaud-lb/MtHaml>


## To Do

- [ ] A simpler and better integration with Grav, add more options to integrate scssphp, coffescript etc...

