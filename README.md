# LearningSymfony


Nos últimos dias tenho me dedicado a estudar **Symfony**,framework PHP, e quero compartilhar alguns pontos importantes que aprendi.  

---

## Conceitos principais

- **ORM (object-relational mapping):**  
  Utilizando o **Doctrine**, é possível mapear classes em entidades para manipular dados no banco sem escrever SQL direto.  
  Exemplo de entidade:

```php
#[ORM\Entity(repositoryClass: BookRepository::class)] 
// Diz que essa classe é uma entidade do Doctrine (vai virar uma tabela no banco).
// Informa também que ela está ligada a um repositório customizado chamado BookRepository.

#[ORM\Table(name: 'book')] 
// Define o nome da tabela no banco de dados que vai armazenar essa entidade (book).

class Book 
// Declaração da classe Book, que será a entidade.

{
    #[ORM\Id] 
    // Diz que essa coluna é a chave primária (primary key).

    #[ORM\GeneratedValue] 
    // Fala que o valor do ID será gerado automaticamente (auto incremento, por exemplo).

    #[ORM\Column] 
    // Define que isso é uma coluna no banco.

    private ?int $id = null; 
    // Propriedade que guarda o ID do livro. Pode ser nulo até ser salvo no banco.

    #[ORM\Column(length: 255)] 
    // Define uma coluna de texto com tamanho máximo de 255 caracteres.

    private ?string $title = null; 
    // Propriedade que guarda o título do livro.

    #[ORM\Column(length: 255)] 
    // Outra coluna de texto, também com até 255 caracteres.

    private ?string $isbn = null; 
    // Propriedade que guarda o ISBN do livro.

    #[ORM\Column] 
    // Cria uma coluna no banco (tipo DateTimeImmutable).

    private ?\DateTimeImmutable $createdAt = null; 
    // Guarda a data/hora de criação do registro.

    #[ORM\Column] 
    // Outra coluna no banco, também DateTimeImmutable.

    private ?\DateTimeImmutable $updatedAt = null; 
    // Guarda a data/hora da última atualização do registro.

    // --- MÉTODOS GETTERS E SETTERS ---
}

---

* **Entity Manager:**  
  É a camada do Doctrine que **gerencia as entidades** (criação, leitura, atualização e exclusão) e faz a ponte entre as classes PHP e o banco de dados.  

  Exemplo de uso com a entidade `Book`:

  ```php
  // Criando um novo livro
  $book = new Book();
  $book->setTitle("Clean Code");
  $book->setIsbn("978-0132350884");
  $book->setCreatedAt(new \DateTimeImmutable());
  $book->setUpdatedAt(new \DateTimeImmutable());

  // Salvando no banco com o Entity Manager
  $entityManager->persist($book);
  $entityManager->flush();
  ```

---

* **Migrations (semelhante ao Laravel um framework que ja estudei antes):**
  Usadas para versionar o banco de dados de forma organizada.

  ```terminal(mac)
  php console make:migration
  php console doctrine:migrations:migrate
  ```

---
# 📸 Prints dos Endpoints da API

Abaixo alguns testes realizados no Postman para validar a API:

---

###  GET /books
Lista todos os livros do banco de dados.  

![GET Books](/Prints/GET_LIST.png)

---

###  GET /books/{id}
Retorna um livro específico pelo **ID**.  

![GET Book ID](/Prints/GET_ID.png)

---

###  POST /books
Cria um novo livro enviando os dados no corpo da requisição.  

![POST Book](/Prints/POST.png)

---

###  PUT /books/{id}
Atualiza um livro existente.  

![PUT Book](/Prints/PUT.png)

---

###  DELETE /books/{id}
Remove um livro pelo **ID**.  

![DELETE Book](/Prints/DELETE.png)

Confirmando o delete:
![DELETE confirmado](/Prints/confirmandoDELETE.png)

---
## O que aprendi com este projeto

- **Doctrine:**  
  Aprendi a usar o Doctrine, um ORM que facilita a comunicação entre o código PHP (orientado a objetos) e o banco de dados.

- **Data Mapper:**  
  Entendi como o padrão Data Mapper é aplicado para separar a lógica de negócio da persistência de dados, tornando o código mais organizado e flexível.

- **PDO:**  
  Compreendi que o Doctrine utiliza o PDO como uma camada de abstração de banco de dados, o que permite a conexão com diferentes sistemas (MySQL, PostgreSQL, etc.) sem alterar o código.

- **Symfony:**  
  Pratiquei a integração do Doctrine com o framework Symfony, utilizando-o para gerenciar operações de banco de dados de forma automatizada.

**Fluxograma ilustrativo:**  
![Fluxograma explicando PDO e Data Mapper](/Prints/fluxoAPI.png)
---

## 📂 Estrutura do Symfony (exemplo de caminhos)

```
meu_projeto_symfony/
│
├── config/              # Arquivos de configuração
├── migrations/          # Histórico das migrations
├── public/              # Ponto de entrada (index.php)
├── src/                 # Código-fonte (Controllers, Entities, Services)
│   ├── Controller/      # Controladores da aplicação
│   └── Entity/          # Entidades (mapeadas pelo Doctrine)
    └── Repository/      # Repositórios(customizados) 

```

---

