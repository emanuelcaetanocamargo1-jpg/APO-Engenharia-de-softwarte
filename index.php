<?php
require_once __DIR__.'/includes/functions.php';
try {do_action(); $db=db();}
catch(PDOException $e) {http_response_code(503);?><!doctype html><html lang="pt-BR"><meta charset="utf-8"><title>Configuração necessária</title><style>body{font:16px Arial;max-width:760px;margin:80px auto;padding:20px;color:#263248}code{background:#eee;padding:3px}</style><h1>Banco de dados ainda não configurado</h1><p>Abra o phpMyAdmin, clique em <b>Importar</b>, escolha <code>database/schema.sql</code> e execute. Confirme também as credenciais em <code>config/database.php</code>.</p><p>Depois atualize esta página.</p></html><?php exit;}
$pages=['dashboard'=>'Dashboard','produtos'=>'Produtos','enderecos'=>'Endereços','transportadores'=>'Transportadores','fretes'=>'Fretes','relatorios'=>'Relatórios'];
$page=(string)($_GET['page']??'dashboard');if(!isset($pages[$page]))$page='dashboard';
if($page==='relatorios'&&($_GET['export']??'')==='csv'){require __DIR__.'/pages/export_csv.php';exit;}
$flash=$_SESSION['flash']??null;unset($_SESSION['flash']);
?><!doctype html>
<html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title><?=h($pages[$page])?> | Gestão Logística</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="assets/css/style.css" rel="stylesheet"></head>
<body><div class="app-shell">
<aside class="sidebar"><a href="index.php" class="brand"><span class="brand-icon">◈</span> LOGÍSTICA<small>Gestão de transportes</small></a>
<div class="nav-caption">MENU PRINCIPAL</div><nav class="side-nav">
<?php foreach(['dashboard'=>'▦','produtos'=>'▣','enderecos'=>'⌖','transportadores'=>'▰','fretes'=>'⇄','relatorios'=>'▤'] as $id=>$icon): ?>
<a class="<?= $page===$id?'active':'' ?>" href="index.php?page=<?=h($id)?>"><span class="nav-icon"><?=h($icon)?></span><?=h($pages[$id])?></a>
<?php endforeach; ?></nav><div class="sidebar-bottom">UNIPAR EAD<br><small>Atividade Prática Orientada</small></div></aside>
<main class="main-content"><header class="topbar"><span>Sistema de Gestão Logística</span><span class="topbar-status"><span class="status-dot"></span> Ambiente local</span></header>
<section class="page-content"><div class="page-heading"><div><span class="eyebrow">PAINEL ADMINISTRATIVO</span><h1><?=h($pages[$page])?></h1><p><?=h(['dashboard'=>'Visão geral da operação logística','produtos'=>'Gerencie mercadorias e pesos para o transporte','enderecos'=>'Cadastre origens e destinos dos fretes','transportadores'=>'Gerencie os responsáveis pelas entregas','fretes'=>'Registre cargas e calcule o valor do transporte','relatorios'=>'Consulte e exporte os fretes por data'][$page])?></p></div><div class="heading-date"><?=date('d/m/Y')?></div></div>
<?php if($flash): ?><div class="alert alert-<?=h($flash['type'])?> alert-dismissible fade show" role="alert"><?=h($flash['message'])?><button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fechar"></button></div><?php endif; ?>
<?php require __DIR__.'/pages/'.$page.'.php'; ?></section><footer>Gestão Logística • Projeto acadêmico • Dados de demonstração</footer>
</main></div><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script><script src="assets/js/main.js"></script></body></html>
