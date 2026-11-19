# Roteiro de gravação - 7 a 8 minutos

**Obrigatório:** aparecer no vídeo no início e informar nome, RA, curso, polo e que é aluno UNIPAR EAD. Substitua os campos entre colchetes por seus dados verdadeiros.

## 0:00 a 0:50 - Apresentação (apareça na câmera)

"Olá, meu nome é [SEU NOME COMPLETO], meu RA é [SEU RA], sou aluno do curso de Engenharia de Software, do polo [SEU POLO] da UNIPAR EAD. Neste vídeo vou apresentar minha Atividade Prática Orientada da disciplina de Fundamentos de Programação para Internet. O projeto é um Sistema de Gestão Logística, desenvolvido em PHP, HTML, CSS, JavaScript, Bootstrap e MariaDB."

## 0:50 a 1:35 - Contexto e Dashboard

[Compartilhe a tela e abra http://localhost/gestao-logistica/]

"O sistema foi pensado para o transporte de produtos, como calças jeans, de Toledo no Paraná para São Paulo. Neste painel principal são mostrados os totais de produtos, transportadores e fretes cadastrados, além dos últimos fretes. As informações vêm do banco de dados."

## 1:35 a 2:30 - Protótipo e navegação

[Mostre o PDF das seis telas e volte ao site. Compare menu, cores e disposição das informações.]

"Antes de implementar, planejei seis telas: Dashboard, Produtos, Endereços, Transportadores, Fretes e Relatórios. Utilizei esse protótipo para manter uma identidade visual consistente durante a implementação."

## 2:30 a 3:30 - Cadastro ao vivo (obrigatório)

[Abra Produtos; cadastre um novo produto de teste, por exemplo 'Calça jeans premium', código CJ004, peso 0,700 kg, quantidade 20. Se o código já existir, use CJ005. Clique em Salvar, mostre a listagem e Editar.]

"Aqui é possível cadastrar e editar produtos. Cada produto tem código, nome, peso unitário e quantidade. O PHP recebe os dados do formulário e salva no banco de dados com comandos SQL preparados."

## 3:30 a 4:20 - Endereços e transportadores

[Visite Endereços: mostre Toledo e São Paulo; depois Transportadores e mostre Transportadora ABC.]

"Os endereços representam a origem e o destino da carga. Os transportadores são cadastrados separadamente e podem ser escolhidos na criação do frete."

## 4:20 a 5:50 - Gerar um frete (obrigatório)

[Abra Fretes. Selecione a data atual, Toledo, São Paulo e a transportadora. Digite uma distância de EXEMPLO, como 300 km; deixe claro que não é a distância real. Selecione Calça jeans, quantidade 150. Clique em Adicionar produto, escolha outro item como Bermuda jeans, quantidade 10. Mostre a prévia e clique em Gerar frete.]

"O sistema permite incluir vários produtos no mesmo frete, vinculados a um único transportador. O JavaScript gera uma prévia de preço em tempo real e o PHP realiza novamente o cálculo no servidor para garantir que o valor salvo não dependa somente do navegador. A fórmula de demonstração usa distância multiplicada por R$ 1,20, mais o peso total multiplicado por R$ 0,50, e uma taxa fixa de R$ 50."

## 5:50 a 6:40 - Relatórios

[Abra Relatórios, selecione a data do frete e clique Consultar. Clique Baixar CSV e mostre o arquivo baixado.]

"Nesta tela, o usuário pode filtrar os fretes por dia e baixar um relatório CSV que pode ser aberto em planilhas. Os dados são consultados pelo PHP no banco de dados."

## 6:40 a 7:40 - Código e GitHub

[Mostre o VS Code: index.php, config/database.php, includes/functions.php, pages/fretes.php, assets/js/main.js e database/schema.sql. Mostre o README e o histórico de commits no GitHub, se já estiverem publicados.]

"O index.php organiza o layout e a navegação; em pages ficam as telas; config possui a conexão PDO; includes/functions.php concentra a validação e as regras de negócio; e assets contém o CSS e o JavaScript. O banco MariaDB armazena os relacionamentos entre produtos, fretes, locais e transportadores. Utilizei Git para registrar as versões do projeto."

## 7:40 a 8:00 - Encerramento

"Com isso, demonstrei o fluxo de cadastramento, geração de frete com múltiplos produtos, cálculo de preço e emissão de relatório. Obrigado pela atenção."

## Checklist antes de gravar

- [ ] Apache e MySQL iniciados no XAMPP
- [ ] Banco `gestao_logistica` importado
- [ ] Site abre sem erros
- [ ] Cadastros existem e você consegue gerar um frete de teste
- [ ] Tela do PDF das seis telas disponível para comparação
- [ ] VS Code pronto para mostrar os arquivos
- [ ] Webcam ou câmera pronta para apresentação inicial
- [ ] RA e polo corretos (não inventar)
- [ ] Gravação entre 5 e 10 minutos
- [ ] YouTube configurado como Não listado
- [ ] Repositório GitHub público com README
- [ ] Enviar o PDF, o link do GitHub e o link do vídeo no AVA
