<?php
$counts=[];foreach(['produtos','transportadores','fretes'] as $t)$counts[$t]=(int)db()->query("SELECT COUNT(*) FROM $t")->fetchColumn();
$pending=(int)db()->query("SELECT COUNT(*) FROM fretes WHERE status='Pendente'")->fetchColumn();
$recent=db()->query("SELECT f.*, eo.cidade AS origem,eo.uf AS origem_uf,ed.cidade AS destino,ed.uf AS destino_uf,t.nome AS transportador FROM fretes f JOIN enderecos eo ON eo.id=f.origem_id JOIN enderecos ed ON ed.id=f.destino_id JOIN transportadores t ON t.id=f.transportador_id ORDER BY f.id DESC LIMIT 6")->fetchAll();
?>
<div class="kpi-grid">
<?php foreach([['Produtos cadastrados',$counts['produtos'],'▣','blue'],['Transportadores',$counts['transportadores'],'▰','purple'],['Fretes realizados',$counts['fretes'],'⇄','green'],['Fretes pendentes',$pending,'◷','orange']] as $k): ?>
<div class="kpi-card"><div class="kpi-top"><span><?=h($k[0])?></span><span class="kpi-icon <?=h($k[3])?>"><?=h($k[2])?></span></div><div class="kpi-number"><?=h($k[1])?></div><div class="kpi-sub">Atualizado em tempo real</div></div>
<?php endforeach; ?></div>
<div class="section-grid"><section class="panel"><div class="panel-header"><div><h2>Fretes recentes</h2><p>Últimos registros da operação</p></div><a class="btn btn-purple btn-sm" href="index.php?page=fretes">+ Novo frete</a></div><div class="table-responsive"><table class="table app-table"><thead><tr><th>Frete</th><th>Rota</th><th>Transportador</th><th>Valor</th><th>Status</th></tr></thead><tbody>
<?php if(!$recent):?><tr><td colspan="5" class="empty-state">Nenhum frete registrado. Cadastre seu primeiro frete.</td></tr><?php endif;?>
<?php foreach($recent as $r):?><tr><td><b>#<?=str_pad($r['id'],4,'0',STR_PAD_LEFT)?></b><small><?=date('d/m/Y',strtotime($r['data_frete']))?></small></td><td><?=h($r['origem'])?> → <?=h($r['destino'])?></td><td><?=h($r['transportador'])?></td><td><?=money($r['valor_frete'])?></td><td><span class="pill"><?=h($r['status'])?></span></td></tr><?php endforeach;?></tbody></table></div></section>
<aside class="panel quick-actions"><div class="panel-header"><div><h2>Acesso rápido</h2><p>Continue seu trabalho</p></div></div><a href="index.php?page=produtos">▣ &nbsp;Cadastrar produto <span>›</span></a><a href="index.php?page=enderecos">⌖ &nbsp;Cadastrar endereço <span>›</span></a><a href="index.php?page=transportadores">▰ &nbsp;Cadastrar transportador <span>›</span></a><a href="index.php?page=fretes">⇄ &nbsp;Calcular frete <span>›</span></a><a href="index.php?page=relatorios">▤ &nbsp;Gerar relatório <span>›</span></a></aside></div>
