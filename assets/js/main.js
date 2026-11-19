document.addEventListener('DOMContentLoaded', () => {
 const form=document.getElementById('freightForm');if(!form)return;
 const rows=document.getElementById('productRows');const add=document.getElementById('addProduct');
 const formatBRL=v=>new Intl.NumberFormat('pt-BR',{style:'currency',currency:'BRL'}).format(v);
 const n=v=>Number(v)||0;
 function update(){
  const distance=Math.max(0,n(document.getElementById('distance').value));let qty=0,weight=0;
  rows.querySelectorAll('.product-row').forEach(row=>{
   const sel=row.querySelector('.product-select');const selected=sel.options[sel.selectedIndex];
   const itemQty=Math.max(0,n(row.querySelector('.product-quantity').value));qty+=itemQty;weight+=itemQty*n(selected?.dataset.weight);
  });
  const kmPrice=distance*n(form.dataset.km), kgPrice=weight*n(form.dataset.kg);
  document.getElementById('sumDistance').textContent=distance.toLocaleString('pt-BR')+' km';
  document.getElementById('sumItems').textContent=qty.toLocaleString('pt-BR')+' unidades';
  document.getElementById('sumWeight').textContent=weight.toLocaleString('pt-BR',{maximumFractionDigits:3})+' kg';
  document.getElementById('sumKm').textContent=formatBRL(kmPrice);
  document.getElementById('sumKg').textContent=formatBRL(kgPrice);
  document.getElementById('sumTotal').textContent=formatBRL(distance>0?kmPrice+kgPrice+n(form.dataset.fixed):0);
 }
 add.addEventListener('click',()=>{const clone=rows.querySelector('.product-row').cloneNode(true);clone.querySelector('select').selectedIndex=0;clone.querySelector('input').value=1;rows.appendChild(clone);update();});
 rows.addEventListener('click',e=>{if(!e.target.closest('.btn-remove'))return;const all=rows.querySelectorAll('.product-row');if(all.length>1)e.target.closest('.product-row').remove();update();});
 form.addEventListener('input',update);form.addEventListener('change',update);update();
});
