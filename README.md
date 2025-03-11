# Symfony - Anotações gerais

- No Symfony, todo método deve retornar um response, sendo esse response um objeto

- As rotas são definidas usando attributes(#[Route('endpont')]), ou seja, basta utilizar o namespace use Symfony\Component\Routing\Attribute\Route;

- Symfony Flex é um plugin ou pacote do composer que atribui dois super poderes ao composer, aliases e recipes(receitas). Aliases pode ser entidido da seguinte forma, imagine que você deseja baixar o pacote symfony/http-client, ao invés de digitar tudo isso basta digitar composer require http-client, ou seja, o flex irá traduzir para o nome do pacote original. Para saber todas as receitas basta visitar o [symfony/recipes](https://github.com/symfony/recipes/blob/flex/main/RECIPES.md). Já recipes(receitas) funciona da seguinte forma, imagine que você adicionou um novo pacote, pode ser que esse pacote tenha uma recipe(receita), o que basicamente é um conjunto de arquivos que serão adicionados no nosso projeto, dessa forma, todos os arquivos que são inicializados nos diretórios bin, config e public são oriundos de recipes e pacotes que instalamos. No arquivo composer.json temos o pacote symfony/framework-bundle que é um core package do framework symfony. Se acessarmos o link [symfony/framework-bundle](https://github.com/symfony/recipes/tree/main/symfony/framework-bundle) veremos várias versões desse pacote, ao abrir o diretório de alguma dessas versões veremos alguns arquivos e diretórios como config, public e src, além dos arquivos manifest.json e post-install.txt. Ou seja, tudo isso vem dessa recipe.

- Para visualizar nossas recipes, basta utilizar o comando composer recipes que irá listar todas as recipes disponíveis. Para verificar os detalhes de uma recipe específica, basta digitar composer recipes nome_da_recipe, por exemplo, composer recipes symfony/framework-bundle, o resultado será os diretórios e arquivos que essa recipe gera.

- Para verificarmos se a padronização do nosso código está de acordo com os padrões estabelecidos para o desenvolvimento usando php, iremos instalar um package chamado cs-fixer-shim usando o comando composer require cs-fixer-shim. Ao rodar o comando, sera baixado um arquivo chamado .php-cs-fixer.dist.php que é o arquivo de configuração utilizado para ajustar o nosso código. Para executar o cs-fixer-shim basta digitar o comando ./vendor/bin/php-cs-fixer fix que irá ajustar a padronização do nosso código.

- Twig é um [template engine](https://twig.symfony.com/doc/3.x/index.html) semelhante ao blade do laravel que é utilizado para renderizar as views. Para instalar o twig basta digitar composer require twig. Note que twig é um alias para para symfony/twig-pack. Packages com terminação pack são pacotes que contém vários outros pacotes(dependências). Ao instalar o twig, temos que o arquivo bundles.php será modificado, ou seja, um novo bundle(plugin) séra instalado. Além disso, é adicionado um diretório chamado templates que é onde nossos templates/views serão criados. Algo importante, uma padronização é que o nome da controller seja compatível com o nome do diretório da view, por exemplo, se a controller se chama HomeController, o diretório da view deve ser home. Para renderizar a view, basta utilizar o método render do objeto `$this->render('home/index.html.twig')`. Para passar dados para a view, basta passar um array associativo como segundo parâmetro do método render, por exemplo, `$this->render('main/homepage.html.twig', ['numberOfStarships' => $starshipCount])`. Para acessar o valor passado para a view, basta utilizar a variável numberOfStarships, ou seja, {{ numberOfStarships }}.

- Twig possui 3 tipos de sintaxe, a primeira é a de interpolação, que é utilizada para exibir valores de variáveis, por exemplo, {{ name }}. A segunda é a de comentários, que é utilizada para comentar trechos de código, por exemplo, {# comentário #}. A terceira é a de tags, que é utilizada para controlar o fluxo do código, por exemplo, {% if name == 'Lucas' %} Olá <strong>Lucas</strong>  {% endif %}

- Twig cria um arquivo base.html.twig que é um arquivo base que contém estilos, javascript, conteudo etc... e é usado como base para nossas paginas. Para utilizar esse arquivo base, basta utilizar o método extends('base.html.twig') no arquivo filho, esse extends é uma forma de herdar o conteúdo do arquivo base. Para informar onde o conteúdo do arquivo filho será renderizado, basta utilizar o código {% block body %} {% endblock %} dentro do nosso arquivo base.html.twig que é onde o conteúdo do arquivo filho será renderizado. Para setar o que vai ser renderizado nesse bloco de código, basta utilizar o código {% block body %} {% endblock %} no arquivo filho. Ou seja, após o extends adicionar o trecho {% block body %} conteudo_aqui_dentro {% endblock %}. Dessa forma todo o conteúdo desejado será renderizado nesse bloco de código. Agora imagine que queremos altear o título do site, para isso basta adicionar o bloco {% block title %} Título do site {% endblock %} no arquivo filho, logo acima da declaração do bloco body anterior. Se quisermos herdar o título do arquivo base, basta adicionar o trecho {{ parent() }} dentro do bloco title do arquivo filho. Ou seja, o parent() é utilizado para herdar o conteúdo do bloco pai e concatenar com o conteúdo do bloco filho.

- Para debugar o código com o Symfony, basta instalar o pacote debug que instala outros pacotes como dependência. Para instalar o pacote debug, basta digitar composer require debug. O pacote debug instala também o pacote monolog que é um pacote de logs que é utilizado para debugar o código. Para visualizar os logs, basta acessar o diretório var/log/dev.log. Para visualizar os logs em tempo real, basta digitar tail -f var/log/dev.log. 

- O comando ./bin/console exibe todos os comandos disponíveis no console do Symfony. É possível adicionar os nossos próprios comandos, mas veremos mais sobre isso lá para frente. Ao executar um comando específico, como ./bin/console debug:router, teremos informações sobre as rotas disponíveis no nosso projeto. Outro comando interessante é o ./bin/console debug:twig que exibe todas as informações e funcionalidades disponíveis no twig.

- Se quisermos criar uma API JSON com o Symfony, podemos utilizar a API-PLATFORM que é um framework criando em cima do Symfony. No código abaixo, temos a nossa controller e a nossa model, no entanto, estamos tentando retornar um array de objetos como JSON, isso resultará em uma estrutura vazia, visto que o Symfony não consegue serializar objetos. Para resolver esse problema, basta instalar o pacote symfony/serializer que é um pacote que serializa objetos. Para instalar o pacote, basta digitar composer require serializer. Após isso, o código irá retornar um JSON com os objetos serializados.

```php
<?php

namespace App\Controller;

use App\Model\Starship;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StarshipApiController extends AbstractController
{
    #[Route('/api/starships')]
    public function getCollection(): Response
    {
        $starships = [
            new Starship(
                1,
                'USS LeafyCruiser (NCC-0001)',
                'Garden',
                'Jean-Luc Pickles',
                'taken over by Q'
            ),
            new Starship(
                2,
                'USS Espresso (NCC-1234-C)',
                'Latte',
                'James T. Quick!',
                'repaired',
            ),
            new Starship(
                3,
                'USS Wanderlust (NCC-2024-W)',
                'Delta Tourist',
                'Kathryn Journeyway',
                'under construction',
            ),
        ];

        return $this->json($starships);
    }
}

<?php

namespace App\Model;

class Starship
{
    public function __construct(
        private int $id,
        private string $name,
        private string $class,
        private string $captain,
        private string $status,
    ) {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getCaptain(): string
    {
        return $this->captain;
    }

    public function getStatus(): string
    {
        return $this->status;
    }
}
```

- Service Container no Symfony é uma forma de agruparmos vários serviços em um único lugar. Um serviço é uma classe que faz algo, por exemplo, uma classe que envia e-mails, outra classe de logs etc. Para visualizarmos os serviços disponíveis basta executar o comando ./bin/console debug:container que irá listar todos os services. Cada service tem um nome e namespaces para instanciar a classe. Se quisermos saber se um service está disponível, basta executar o comando ./bin/console debug:container nome_do_service. Exemplo, ./bin/console debug:autowiring log(irá exibir todos os services de log disponíveis para uso). Para exemplo, podemos utilizar o logger principal, que é o service monolog.logger.main. Para utilizar o service, basta injetar o service no construtor da classe e utilizar o service como desejar. O código final seria algo como:

```php
<?php

namespace App\Controller;

use App\Model\Starship;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StarshipApiController extends AbstractController
{
    #[Route('/api/starships')]
    public function getCollection(LoggerInterface $logger): Response // Injeta o service logger
    {
        dd($logger); // Printa e mata a aplicação

        $starships = [
            new Starship(
                1,
                'USS LeafyCruiser (NCC-0001)',
                'Garden',
                'Jean-Luc Pickles',
                'taken over by Q'
            ),
            new Starship(
                2,
                'USS Espresso (NCC-1234-C)',
                'Latte',
                'James T. Quick!',
                'repaired',
            ),
            new Starship(
                3,
                'USS Wanderlust (NCC-2024-W)',
                'Delta Tourist',
                'Kathryn Journeyway',
                'under construction',
            ),
        ];

        return $this->json($starships);
    }
}
```

- Podemos criar nossos próprios services, no código abaixo temos uma classe chamada StarshipRepository que é um service que possui um método findAll e retorna um array de objetos, note que passamos o LoggerInterface como argumento no construtor e removemos da controller. O ideal é utilizar o autowiring para injetar os services no construtor, mas caso queira delimitar a um método é possível utilizar.

```php
<?php

namespace App\Repository;

use App\Model\Starship;
use Psr\Log\LoggerInterface;

class StarshipRepository
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function findAll(): array
    {
        $this->logger->info('Starship collection retrieved');

        return [
            new Starship(
                1,
                'USS LeafyCruiser (NCC-0001)',
                'Garden',
                'Jean-Luc Pickles',
                'taken over by Q'
            ),
            new Starship(
                2,
                'USS Espresso (NCC-1234-C)',
                'Latte',
                'James T. Quick!',
                'repaired',
            ),
            new Starship(
                3,
                'USS Wanderlust (NCC-2024-W)',
                'Delta Tourist',
                'Kathryn Journeyway',
                'under construction',
            ),
        ];
    }
}

<?php

namespace App\Controller;

use App\Repository\StarshipRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StarshipApiController extends AbstractController
{
    #[Route('/api/starships')]
    public function getCollection(StarshipRepository $repository): Response
    {

        $starships = $repository->findAll();

        return $this->json($starships);
    }
}

<?php

namespace App\Controller;

use App\Repository\StarshipRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class MainController extends AbstractController
{
    #[Route('/')]
    public function homepage(StarshipRepository $starshipRepository): Response
    {
        $ships = $starshipRepository->findAll();
        $myShip = $ships[array_rand($ships)];

        return $this->render('main/homepage.html.twig', [
            'myShip' => $myShip,
            'ships' => $ships,
        ]);
    }
}


{% extends 'base.html.twig' %}

{% block title %}Starshop: Beam up some parts!{% endblock %}

{% block body %}

<h1>Starshop: your monopoly-busting option for Starship parts</h1>

<div>
    Browse through {{ ships|length }} starships!
</div>

<div>
    <h2>My Ship</h2>

    <table>
        <tr>
            <th>Name</th>
            <td>{{ myShip.name }}</td>
        </tr>
        <tr>
            <th>Class</th>
            <td>{{ myShip.class }}</td>
        </tr>
        <tr>
            <th>Captain</th>
            <td>{{ myShip.captain }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ myShip.status }}</td>
        </tr>
    </table>
</div>
{% endblock %}
```

- Podemos definir wildcards nas rotas, por exemplo, imagine que queremos acessar uma rota que recebe um id como parâmetro, para isso, basta adicionar o wildcard na rota, por exemplo, #[Route('/api/starships/{id}')]. Para acessar o id passado como parâmetro, basta adicionar o argumento na controller, por exemplo, public function get(int $id): Response. Dessa forma, o id passado como parâmetro será acessado na controller. Além disso, é possível delimitar o tipo de dado que se espera passar como parâmetro, no caso de um id, é esperado um int, o código final seria algo como:

```php
<?php

namespace App\Controller;

use App\Repository\StarshipRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/starships')]
class StarshipApiController extends AbstractController
{
    public function __construct()
    {
    }

    #[Route('', methods: ['GET'])]
    public function getCollection(StarshipRepository $repository): Response
    {

        $starships = $repository->findAll();

        return $this->json($starships);
    }

    #[Route('/{id<\d+>}', methods: ['GET'])]
    public function get(int $id, StarshipRepository $repository): Response
    {
        $starship = $repository->find($id);

        if (!$starship) {
            throw $this->createNotFoundException('Starship not found');
        }

        return $this->json($starship);
    }
}

<?php

namespace App\Repository;

use App\Model\Starship;
use Psr\Log\LoggerInterface;

class StarshipRepository
{
    public function __construct(private LoggerInterface $logger)
    {
    }

    public function findAll(): array
    {
        $this->logger->info('Starship collection retrieved');

        return [
            new Starship(
                1,
                'USS LeafyCruiser (NCC-0001)',
                'Garden',
                'Jean-Luc Pickles',
                'taken over by Q'
            ),
            new Starship(
                2,
                'USS Espresso (NCC-1234-C)',
                'Latte',
                'James T. Quick!',
                'repaired',
            ),
            new Starship(
                3,
                'USS Wanderlust (NCC-2024-W)',
                'Delta Tourist',
                'Kathryn Journeyway',
                'under construction',
            ),
        ];
    }

    public function find(int $id): ?Starship
    {
        $this->logger->info('Starship retrieved', ['id' => $id]);

        $starships = $this->findAll();

        foreach ($starships as $starship) {
            if ($starship->getId() === $id) {
                return $starship;
            }
        }

        return null;
    }
}
```

- Ou seja, delimitamos o valor que se espear passar na rota como argumento utilizando o código {id<\d+>}, ou seja, esperamos um valor inteiro. Além disso, utilizamos o método find na nossa repository para buscar o id passado como parâmetro. Caso o id não seja encontrado, é lançado uma exceção(404) informando que o id não foi encontrado. Por fim, delimitamos o método que a rota aceita, no caso, apenas o método GET.

- Podemos gerar URLs de forma dinâmica, por exemplo, ao clicar em um link que redireciona para outra página, podemos utilizar o método path('nome_da_rota') que irá gerar a URL da rota de forma dinâmica. Caso a rota possua wildcards, basta passar o valor como segundo parâmetro do método path, por exemplo, path('nome_da_rota', ['id' => $id]). O código final seria algo como:

```php
<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\StarshipRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class StarshipController extends AbstractController
{
    #[Route('/starships/{id<\d+>}', name: 'app_starship_show')] // Define o nome da rota
    public function show(int $id, StarshipRepository $repository): Response
    {
        $ship = $repository->find($id);

        if (!$ship) {
            throw $this->createNotFoundException('Starship not found');
        }

        return $this->render('starship/show.html.twig', [
            'ship' => $ship,
        ]);
    }
}

{% extends 'base.html.twig' %}

{% block title %}Starshop: Beam up some parts!{% endblock %}

{% block body %}

<h1>Starshop: your monopoly-busting option for Starship parts</h1>

<div>
    Browse through {{ ships|length }} starships!
</div>

<div>
    <h2>My Ship</h2>

    <table>
        <tr>
            <th>Name</th>
            <td>
                <a href="{{ path('app_starship_show', { id: myShip.id }) }}"> <!-- Gera a URL dinamicamente -->
                    {{ myShip.name }}
                </a>
            </td>
        </tr>
        <tr>
            <th>Class</th>
            <td>{{ myShip.class }}</td>
        </tr>
        <tr>
            <th>Captain</th>
            <td>{{ myShip.captain }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ myShip.status }}</td>
        </tr>
    </table>
</div>
{% endblock %}
```

- Um componente bem interessante do Symfony é o Asset-Mapper que é um componente que mapeia os assets(css, js, imagens) do nosso projeto. Para instalar o asset-mapper, basta digitar composer require symfony/asset-mapper. Após instalado, o asset-mapper irá criar um diretório chamado assets que irá conter um outro diretório chamado css com um arquivo app.css, além disso, é criado um arquivo app.js dentro do diretório assets. O diretório assets é mapeado como se estivesse dentro do diretório public, dessa forma nossas imagens, arquivos css e js se tornam públicos para acesso.

- Para visualiar os assets disponíveis basta executar o comando ./bin/console debug:asset que irá listar todos os assets disponíveis. Note que há duas colunas, Logical Path e Filesystem Path. A coluna Logical Path é o caminho lógico do asset, ou seja, o caminho que utilizamos para acessar o asset, por exemplo, /assets/css/app.css. A coluna Filesystem Path é o caminho físico do asset, ou seja, o caminho real do asset, por exemplo, /var/www/html/assets/css/app.css. Para acessar o asset, basta utilizar o método asset('caminho_do_asset') que irá gerar o caminho do asset de forma dinâmica. O código final seria algo como:

```php
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>{% block title %}Welcome!{% endblock %}</title>
        <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 128 128%22><text y=%221.2em%22 font-size=%2296%22>⚫️</text><text y=%221.3em%22 x=%220.2em%22 font-size=%2276%22 fill=%22%23fff%22>sf</text></svg>">
        {% block stylesheets %}
        {% endblock %}

        {% block javascripts %}
            {% block importmap %}{{ importmap('app') }}{% endblock %}
        {% endblock %}
    </head>
    <body>
        <img src="{{ asset('images/starshop-logo.png') }}", alt="Starshop Logo"> <!-- Acessa o asset de forma dinâmica -->
        {% block body %}{% endblock %}
    </body>
</html>
```

- No entanto, se atualizarmos a página teremos um erro informando que o pacote symfony/asset não está instalado. Dessa forma, é necessário instalar esse pacote usando o comando composer require symfony/asset. Com isso o erro será resolvido e o asset será acessado de forma correta. Uma coisa interessante a respeito a respeito do asset é que ele aplica version hash nos assets(css, js, imagens) para evitar cache, ou seja, toda vez que o asset é modificado, o hash é alterado e o cache é atualizado.

- E se quisermos utilizar tailwindcss? Bem, é possível utilizando o pacote symfonycasts/tailwind-bundle que é um pacote que instala o tailwindcss no nosso projeto. Para baixar basta digitar o comando composer require symfonycasts/tailwind-bundle. Após isso, basta executar o tailwind digitando o comando ./bin/console tailwind:init que irá alterar o nosso arquivo app.css localizado em assets/styles/app.css para adicionar as diretivas do tailwind. Por fim, cria um arquivo tailwind.config.js que é o arquivo de configuração do tailwind.

- Para executarmos o tailwindcss basta digitar o comando ./bin/console tailwind:build -w que irá compilar o tailwindcss e ficar escutando as alterações no arquivo app.css.

- No Symfony, é possível criar arquivos de configurações, nesse caso, criamos o arquivo .symfony.local.yaml que possui um worker para executar o tailwindcss em background para nós, a configuração foi dada por:

```yaml
# Quando o servidor é iniciado, o symfony irá executar o tailwindcss em background(apenas local).
workers:
  tailwind:
    cmd: ['symfony', 'console', 'tailwind:build', '--watch']
```

- Como deixar nossa interface interativa? Bem, podemos utilizar um framework bem legal chamado Stimulus que é um framework JavaScript utilizado na construção de interfaces dinâmicas. Para baixar o pacote basta digitar no terminal composer require symfony/stimulus-bundle. Após instalado, temos que o arquivo app.js será modificado para importar o arquivo bootstrap.js, além da criação de quatro novos arquivos chamados bootstrap.js, controllers.json, hello_controller.js e csrf_protection_controller no diretório controllers.

- Mas como funciona essas controllers do stimulus? Bem, funciona da seguinte forma, é adicionado um atributo chamado data-controller no elemento que desejamos adicionar a interatividade, por exemplo, imagine que queremos adicionar um botão que ao ser clicado exibe um alerta, para isso, basta adicionar o atributo data-controller="hello" no botão e criar um arquivo chamado hello_controller.js no diretório controllers com o seguinte conteúdo:

```javascript
import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    connect() {
        console.log('Hello from Stimulus!');
    }

    greet() {
        alert('Hello from Stimulus!');
    }
}
```

- Ou seja, o método connect é executado quando o elemento é conectado ao DOM e o método greet é executado quando o botão é clicado. Para adicionar o botão, basta adicionar o código abaixo no arquivo twig:

```php
<!-- evento de clique chama o método greet da controller hello_controller -->
<button data-controller="hello" data-action="click->hello#greet">Greet</button>
```

- Veja que o data-action é utilizado para chamar o método greet da controller hello quando o botão é clicado. Dessa forma, ao clicar no botão, um alerta será exibido.

- Um problema com essa abordagem é que quando atualizamos a página, é realizado um full reload, ou seja, carrega tudo o que pode tornar o site lento. Para resolver isso, o pesosal que criou o Stimulus também criou o Turbo que é um pacote que faz com que o site seja carregado de forma assíncrona, ou seja, apenas o conteúdo que foi modificado é carregado. Para instalar o pacote basta digitar composer require symfony/ux-turbo. Após instalado, é criado um arquivo chamado importmap.php que contém algumas configurações do Turbo. Também é criado um arquivo chamado controllers.json dentro de assets que contém as controllers do Stimulus e configurações. Com isso, ao interagir com o site, apenas o conteúdo que foi modificado é carregado, tornando o site mais rápido.

- Uma outra ferramente interessante é o maker-bundle do Symfony que é útil para criar comandos. Para instalar basta digitar composer require symfony/maker-bundle --dev e instalar como uma dev dependency.

- Para criar um comando personalizado, basta digitar symfony console make:command e seguir as instruções. O comando que eu criei foi o app:ship-report, ao criar o comand é gerado uma classe chamada ShipReportCommand que é um service que contém métodos e atributos. Com isso, podemos utilizar para testar digitando symfony console app:ship-report no terminal, argumentos podem ser passados também.

## Setup, Services e Service Container - Fundamentos

- O que é um service? Basicamente um service é uma classe que faz algo, por exemplo, uma classe que envia e-mails, outra classe que faz logs, outra classe que realiza querys no banco de dados etc.
- Os services são "servidos" pelo service container que é um container que contém todos os services do nosso projeto.
- Para visualizar todos so services disponíveis no nosso projeto, basta digitar symfony console debug:container ou ./bin/console debug:container.
- A maioria desses services são fornecidos através de bundles que são pacotes(ou plugins) que contém services, configurações e funcionalidades.
- Os bundles são registrados no arquivo bundles.php que contém todos os bundles disponíveis no nosso projeto.
- Note que no arquivo temos alguns bundles disponíveis apenas para certos ambientes, por exemplo, `Symfony\Bundle\DebugBundle\DebugBundle::class => ['dev' => true]` que é um bundle que só é carregado no ambiente de desenvolvimento.

- Instalando o knplabs/knp-time-bundle para extender funcionalidades no twig, como formatação de datas mais avançadas. Para instalar basta digitar no terminal `composer require knplabs/knp-time-bundle`.
- Bundles nos fornece services, para saber quais services esse bundle fornece basta digitar no terminal `./bin/console debug:container time`, selecionar a opcao `Knp\Bundle\TimeBundle\DateTimeFormatter` e com isso teremos todas as informações sobre o service. Para saber se esse service pode ser injetado(autowired) basta executar no terminal o comando `./bin/console debug:autowiring time`, caso o service `Knp\Bundle\TimeBundle\DateTimeFormatter` seja exibido, significa que o service pode ser injetado. Mas não só isso, esse bundle possui integração com o twig fornecendo várias outras funcionalidades, por exemplo, o filtro ago que é utilizado para exibir a data em formato de tempo decorrido, por exemplo, `{{ post.publishedAt|ago }}`. Para verificar as funcionalidades disponíveis basta executar o comando `./bin/console debug:twig`.

- No Symfony, temos um service para http client que é um service que faz requisições http. Para instalar o client http basta digitar no terminal `composer require symfony/http-client`.
- Abaixo temos um exemplo de como utilizar o http client, note que ele é passado via autowiring no construtor da classe, como exemplo estamos fazendo uma requisição para a api que retorna a localização da ISS em tempo real.
```php
<?php

namespace App\Controller;

use App\Repository\StarshipRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_homepage')]
    public function homepage(
        StarshipRepository $starshipRepository,
        HttpClientInterface $client,
    ): Response
    {
        $ships = $starshipRepository->findAll();
        $myShip = $ships[array_rand($ships)];

        # Requisição GET para a api
        $response = $client->request('GET', 'https://api.wheretheiss.at/v1/satellites/25544');
        $issData = $response->toArray(); # Converte a resposta para um array

        return $this->render('main/homepage.html.twig', [
            'myShip' => $myShip,
            'ships' => $ships,
            'issData' => $issData, # Passa os dados da ISS para a view
        ]);
    }
}

# Exemplo de como utilizar os dados na view do twig.
<div>
    <h2 class="text-4xl font-semibold my-8">ISS Location</h2>
    <p>Time: {{ issData.timestamp|date }}</p>
    <p>Altitude: {{ issData.altitude }}</p>
    <p>Latitude {{ issData.altitude }}</p>
    <p>Longitude: {{ issData.longitude }}</p>
    <p>Visibility: {{ issData.visibility }}</p>
</div>
```

- Para trabalharmos com Cache Service e Cache Pools e evitar o carregamento lento de requisições http podemos utilizar cache. Para verificar se temos algum service relacionado a cache podemos rodar o comando `./bin/console debug:autowiring cache`. Note que temos o service `Symfony\Contracts\Cache\CacheInterface` para trabalharmos com cache. Abaixo temos um exemplo de como utilizar o cache para armazenar os dados da ISS.
```php
<?php

namespace App\Controller;

use App\Repository\StarshipRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_homepage')]
    public function homepage(
        StarshipRepository $starshipRepository,
        HttpClientInterface $client,
        CacheInterface $cache, # Injeta o service cache
    ): Response
    {
        $ships = $starshipRepository->findAll();
        $myShip = $ships[array_rand($ships)];

        # Primeiro argumento é a chave do cache e o segundo é uma função anônima que retorna os dados da requisição
        $issData = $cache->get('iss_location_data', function (ItemInterface $item) use ($client): array {
            $item->expiresAfter(5); # Expira após 5 segundos

            # Realiza a requisição para a api
            $response = $client->request('GET', 'https://api.wheretheiss.at/v1/satellites/25544');
            return $response->toArray();
        });

        return $this->render('main/homepage.html.twig', [
            'myShip' => $myShip,
            'ships' => $ships,
            'issData' => $issData,
        ]);
    }
}
```

- Note que os dados são armazenados no cache e limpos após 5 segundos, ou seja, após 5 segundos os dados são atualizados.
- Para visualizar a lista de cache's disponíveis basta rodar o comando `./bin/console cache:pool:list`.
- Para limpar o cache manualmente basta executar o comando `./bin/console cache:pool:clear cache.app` sendo cache.app o nome do cache.