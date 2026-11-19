<?php
$date=(string)($_GET['data']??date('Y-m-d'));
$dt=DateTimeImmutable::createFromFormat('!Y-m-d',$date);
if(!$dt||$dt->format('Y-m-d')!==$date){http_response_code(400);exit('Data inválida.');}
$s=db()->prepare('SELECT f.id,f.data_frete,e1.cidade origem,e1.uf origem_uf,e2.cidade destino,e2.uf destino_uf,t.nome transportador,f.distancia_km,f.peso_total,f.valor_frete,f.status FROM fretes f JOIN enderecos e1 ON e1.id=f.origem_id JOIN enderecos e2 ON e2.id=f.destino_id JOIN transportadores t ON t.id=f.transportador_id WHERE f.data_frete=? ORDER BY f.id');$s->execute([$date]);
header('Content-Type: text/csv; charset=UTF-8');header('Content-Disposition: attachment; filename="relatorio_fretes_'.$date.'.csv"');header('Cache-Control: no-store');
$out=fopen('php://output','wb');fwrite($out,"\xEF\xBB\xBF");fputcsv($out,['ID','Data','Origem','UF origem','Destino','UF destino','Transportador','Distância km','Peso kg','Valor R$','Status'],';');
foreach($s as $r){$cells=array_values($r);foreach($cells as &$cell){$cell=(string)$cell;if(preg_match('/^[\s]*[=+\-@]/u',$cell))$cell="'".$cell;}unset($cell);fputcsv($out,$cells,';');}fclose($out);
