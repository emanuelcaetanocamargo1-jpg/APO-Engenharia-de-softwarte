# Sistema de Gestão Logística - APO FPI / UNIPAR EAD

Aplicação web didática em **PHP 8, HTML5, CSS3, JavaScript, Bootstrap e MariaDB** para a disciplina de Fundamentos de Programação para Internet.

## Funcionalidades

- Dashboard com dados reais dos cadastros.
- Cadastro, listagem, edição e exclusão de produtos, endereços e transportadores.
- Criação de fretes contendo **vários produtos** e um transportador.
- Cálculo do valor do frete no **backend PHP** (a prévia é feita em JavaScript).
- Relatório diário e download CSV compatível com Excel.
- Layout responsivo com navegação lateral.

## Executar localmente no XAMPP (Windows)

1. Inicie **Apache** e **MySQL** no XAMPP.
2. Copie a pasta `gestao-logistica` inteira para `C:\xampp\htdocs\gestao-logistica`.
3. Abra `http://localhost/phpmyadmin`.
4. Clique em **Importar**, selecione `database/schema.sql` e execute. O script cria o banco, as tabelas e exemplos de dados.
5. Verifique `config/database.php`: padrão do XAMPP é usuário `root`, senha vazia, porta `3306`. Se configurou senha, atualize o arquivo local.
6. Acesse `http://localhost/gestao-logistica/`.

Se o MySQL do XAMPP usar outra porta, altere `DB_PORT` em `config/database.php`.

## Teste rápido

1. Abra Produtos e cadastre `Calça jeans premium`, código `CJ004`, peso `0.700`, quantidade `20`.
2. Abra Endereços e confirme que Toledo/PR e São Paulo/SP existem.
3. Abra Transportadores e confirme que `Transportadora ABC` existe.
4. Abra Fretes; selecione data atual, origem Toledo, destino São Paulo, transportador, distância de exemplo `300`, produto Calça jeans, quantidade `150`. Clique em **Adicionar produto** e inclua outro produto.
5. O preço deve seguir a regra `distância × 1,20 + peso em kg × 0,50 + 50` e ser recalculado no PHP ao salvar.
6. Acesse Relatórios e baixe o CSV da mesma data.

## Arquitetura

- `index.php` - roteamento, layout comum, menu e títulos.
- `pages/` - seis telas e exportação CSV.
- `includes/functions.php` - regras de negócio, validações, CRUD e cálculo.
- `config/database.php` - conexão PDO com MariaDB.
- `database/schema.sql` - criação das tabelas e dados de exemplo.
- `assets/css/style.css` - identidade visual.
- `assets/js/main.js` - adicionar/remover linhas de produtos e estimativa de preço.
- `docs/roteiro_video.md` - roteiro de vídeo.

## Regras e observações

O cálculo é **uma simplificação acadêmica**; não consulta mapas e não representa uma cotação comercial. A distância em quilômetros é informada manualmente. O peso do frete é a soma de quantidade × peso unitário de cada produto. É permitido adicionar diversos produtos ao mesmo frete.

Os dados de CPF/CNPJ/CEP não são validados quanto à autenticidade, conforme facultado no enunciado. Usa PDO com parâmetros preparados e token CSRF nos formulários.

**Não publicar senhas reais no GitHub.** A configuração padrão presente no projeto serve apenas ao XAMPP local. Esta aplicação é uma demonstração acadêmica e não possui autenticação; não deve ser disponibilizada publicamente sem implementar login, permissões e outras proteções.

## Entrega UNIPAR

- Etapa 1: PDF das 6 telas feitas no Figma (enviar separadamente).
- Etapa 2: link do repositório público do GitHub com este projeto e `README.md`.
- Etapa 3: vídeo de 5 a 10 minutos, YouTube **Não listado**, enviando o link no AVA.

Não copie a pasta para ambiente de produção sem ajustes de segurança.
