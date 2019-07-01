<?php 

namespace App\Controller\Page;

class Page
{

    protected $twig;
    private $options = [
        "header" => true,
        "footer" => true
    ];
    

    public function __construct($container = null, $cache = false, $optsTpl = "/site")
    {
        $loader = new \Twig\Loader\FilesystemLoader(SITE_ROOT.'/views'.$optsTpl);
        

        
        $this->twig = new \Twig\Environment($loader, [
            // 'cache' => ($cache)? SITE_ROOT.'/views/cache' : null
        ]);
        
        if($container !== null){
            $router = $container->get('router');
            $uri = \Slim\Http\Uri::createFromEnvironment(new \Slim\Http\Environment($_SERVER));
            $this->twig->addExtension(new \Slim\Views\TwigExtension($router, $uri));
        }
        
        $this->twig->addExtension(new \nochso\HtmlCompressTwig\Extension());

    }

    public function setTpl($tpl, $data = [], $opts = [])
    {
        $this->options = array_merge($this->options, $opts);

        if($this->options["header"]) echo $this->twig->render("header.html", !empty($data["header"])? $data["header"] : []);
        echo $this->twig->render($tpl, $data);
        if($this->options["footer"]) echo $this->twig->render("footer.html", !empty($data["footer"])? $data["footer"] : []);
    }

}