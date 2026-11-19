<?php
require_once __DIR__ . '/../config/database.php';
if (session_status() !== PHP_SESSION_ACTIVE) session_start();
function h($v): string {return htmlspecialchars((string)$v, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');}
function money($v): string {return 'R$ '.number_format((float)$v, 2, ',', '.');}
function redirect(string $page, string $extra=''): never { header('Location: index.php?page='.rawurlencode($page).$extra); exit; }
function flash(string $type, string $message): void { $_SESSION['flash']=['type'=>$type,'message'=>$message]; }
function csrf(): string {if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32));return $_SESSION['csrf'];}
function verify_csrf(): void {if(!hash_equals(csrf(), (string)($_POST['csrf'] ?? ''))) {http_response_code(403);exit('Token de segurança inválido. Atualize a página e tente novamente.');}}
function all(string $table, string $order='id DESC'): array {
 $allowed=['produtos','enderecos','transportadores','fretes'];
 if(!in_array($table,$allowed,true))throw new RuntimeException('Tabela inválida');
 return db()->query("SELECT * FROM $table ORDER BY $order")->fetchAll();
}
function one(string $table, int $id): ?array {
 if(!in_array($table,['produtos','enderecos','transportadores','fretes'],true))throw new RuntimeException('Tabela inválida');
 $s=db()->prepare("SELECT * FROM $table WHERE id=?");$s->execute([$id]);return $s->fetch()?:null;
}
function required(string $v,string $label): string {
 $v=trim($v);if($v==='')throw new InvalidArgumentException("Preencha: $label");return $v;
}
function positive($v,string $label,bool $allowZero=false): float {
 if(!is_numeric($v) || ($allowZero?(float)$v<0:(float)$v<=0))throw new InvalidArgumentException("Valor inválido: $label");return (float)$v;
}
function freight_total(float $distance,float $weight): float {
 // Demonstration tariff; not a real market quotation.
 return round($distance*1.20+$weight*0.50+50.00,2);
}
function do_action(): void {
 if($_SERVER['REQUEST_METHOD'] !== 'POST')return;
 verify_csrf();$action=(string)($_POST['action']??'');
 $page=['save_product'=>'produtos','save_address'=>'enderecos','save_carrier'=>'transportadores','save_freight'=>'fretes','delete'=>'dashboard'][$action]??'dashboard';
 try {
   switch($action){
    case 'save_product':
     $name=required((string)($_POST['nome']??''),'Nome');
     $code=required((string)($_POST['codigo']??''),'Código');
     $weight=positive($_POST['peso_unitario']??'','Peso unitário');
     $quantity=(int)positive($_POST['quantidade']??'','Quantidade',true);
     if($quantity>100000000)throw new InvalidArgumentException('Quantidade excessiva');
     $id=(int)($_POST['id']??0);
     if($id){$s=db()->prepare('UPDATE produtos SET codigo=?,nome=?,peso_unitario=?,quantidade=? WHERE id=?');$s->execute([$code,$name,$weight,$quantity,$id]);}
     else{$s=db()->prepare('INSERT INTO produtos(codigo,nome,peso_unitario,quantidade) VALUES(?,?,?,?)');$s->execute([$code,$name,$weight,$quantity]);}
     flash('success','Produto salvo com sucesso.');break;
    case 'save_address':
     $name=required((string)($_POST['nome']??''),'Nome / local');
     $city=required((string)($_POST['cidade']??''),'Cidade');
     $state=strtoupper(required((string)($_POST['uf']??''),'UF'));
     if(!preg_match('/^[A-Z]{2}$/',$state))throw new InvalidArgumentException('UF deve ter 2 letras.');
     $street=trim((string)($_POST['logradouro']??''));$cep=trim((string)($_POST['cep']??''));
     $id=(int)($_POST['id']??0);
     if($id){$s=db()->prepare('UPDATE enderecos SET nome=?,cidade=?,uf=?,logradouro=?,cep=? WHERE id=?');$s->execute([$name,$city,$state,$street,$cep,$id]);}
     else{$s=db()->prepare('INSERT INTO enderecos(nome,cidade,uf,logradouro,cep) VALUES(?,?,?,?,?)');$s->execute([$name,$city,$state,$street,$cep]);}
     flash('success','Endereço salvo com sucesso.');break;
    case 'save_carrier':
     $name=required((string)($_POST['nome']??''),'Transportador');
     $doc=trim((string)($_POST['documento']??''));$phone=trim((string)($_POST['telefone']??''));$vehicle=trim((string)($_POST['veiculo']??''));
     $id=(int)($_POST['id']??0);
     if($id){$s=db()->prepare('UPDATE transportadores SET nome=?,documento=?,telefone=?,veiculo=? WHERE id=?');$s->execute([$name,$doc,$phone,$vehicle,$id]);}
     else{$s=db()->prepare('INSERT INTO transportadores(nome,documento,telefone,veiculo) VALUES(?,?,?,?)');$s->execute([$name,$doc,$phone,$vehicle]);}
     flash('success','Transportador salvo com sucesso.');break;
    case 'save_freight':
     $source=(int)($_POST['origem_id']??0);$target=(int)($_POST['destino_id']??0);$carrier=(int)($_POST['transportador_id']??0);
     if(!$source||!$target||!$carrier||$source===$target)throw new InvalidArgumentException('Selecione origem, destino diferentes e transportador.');
     if(!one('enderecos',$source)||!one('enderecos',$target)||!one('transportadores',$carrier))throw new InvalidArgumentException('Cadastro relacionado não encontrado.');
     $distance=positive($_POST['distancia_km']??'','Distância (km)');
     $date=(string)($_POST['data_frete']??'');
     $d=DateTimeImmutable::createFromFormat('!Y-m-d',$date);
     if(!$d||$d->format('Y-m-d')!==$date)throw new InvalidArgumentException('Data inválida.');
     $ids=$_POST['produto_id']??[];$qty=$_POST['quantidade_item']??[];
     if(!is_array($ids)||!is_array($qty)||count($ids)===0||count($ids)!==count($qty))throw new InvalidArgumentException('Adicione ao menos um produto.');
     $rows=[];$totalWeight=0.0;
     foreach($ids as $i=>$id){
       $product=one('produtos',(int)$id);
       $number=(int)($qty[$i]??0);
       if(!$product||$number<=0)throw new InvalidArgumentException('Produto ou quantidade inválidos.');
       $weight=$number*(float)$product['peso_unitario'];$totalWeight+=$weight;
       $rows[]=['id'=>(int)$id,'q'=>$number,'unit'=>(float)$product['peso_unitario'],'total'=>$weight];
     }
     $price=freight_total($distance,$totalWeight);
     db()->beginTransaction();
     try {
      $s=db()->prepare('INSERT INTO fretes(data_frete,origem_id,destino_id,transportador_id,distancia_km,peso_total,valor_frete,status) VALUES(?,?,?,?,?,?,?,?)');
      $s->execute([$date,$source,$target,$carrier,$distance,$totalWeight,$price,'Pendente']);
      $fid=(int)db()->lastInsertId();
      $s=db()->prepare('INSERT INTO frete_itens(frete_id,produto_id,quantidade,peso_unitario,peso_total) VALUES(?,?,?,?,?)');
      foreach($rows as $row)$s->execute([$fid,$row['id'],$row['q'],$row['unit'],$row['total']]);
      db()->commit();
     }catch(Throwable $e){db()->rollBack();throw $e;}
     flash('success','Frete #'.$fid.' gerado com sucesso. Valor: '.money($price));break;
    case 'delete':
     $table=(string)($_POST['table']??'');$id=(int)($_POST['id']??0);
     $pages=['produtos'=>'produtos','enderecos'=>'enderecos','transportadores'=>'transportadores'];
     if(!isset($pages[$table])||!$id)throw new InvalidArgumentException('Exclusão inválida.');
     $page=$pages[$table];$s=db()->prepare("DELETE FROM $table WHERE id=?");$s->execute([$id]);
     flash('success','Registro excluído.');break;
    default:throw new InvalidArgumentException('Ação desconhecida.');
   }
 }catch(PDOException $e){flash('danger','Não foi possível salvar. Verifique se o código já existe ou se o registro está sendo usado por um frete.');}
 catch(Throwable $e){flash('danger',$e->getMessage());}
 redirect($page);
}
