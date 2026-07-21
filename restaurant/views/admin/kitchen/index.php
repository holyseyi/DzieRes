<?php
/**
 * Admin: Kitchen Display System
 * Large-screen view with auto-refresh.
 */
?>
<style>
.kitchen-board { background: #0d0d14; min-height: calc(100vh - 120px); padding: 20px; }
.kitchen-col { background: rgba(255,255,255,0.03); border-radius: 16px; padding: 16px; }
.kitchen-col h5 { color: #fff; border-bottom: 2px solid #001a4a; padding-bottom: 10px; margin-bottom: 16px; }
.kds-card { background: #16161f; border-radius: 12px; padding: 16px; margin-bottom: 14px; border-left: 5px solid #001a4a; }
.kds-card.priority { border-left-color: #e74c3c; }
.kds-card.ready { border-left-color: #2ecc71; }
.kds-order-no { font-size: 1.4rem; font-weight: 700; color: #003380; }
.kds-timer { font-size: 0.9rem; color: #aaa; }
.kds-item { display: flex; justify-content: space-between; padding: 4px 0; border-bottom: 1px dashed rgba(255,255,255,0.08); color: #eee; }
.kds-actions .btn { margin-top: 10px; }
</style>

<div class="kitchen-board">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="text-white mb-0"><i class="fas fa-kitchen-set text-gold me-2"></i>Kitchen Display</h3>
        <div class="text-white-50"><i class="fas fa-clock me-1"></i><span id="kdsClock"></span></div>
    </div>
    <div class="row g-3" id="kdsBoard">
        <div class="col-lg-2 col-md-4"><div class="kitchen-col"><h5>Pending</h5><div id="col-pending"></div></div></div>
        <div class="col-lg-2 col-md-4"><div class="kitchen-col"><h5>Accepted</h5><div id="col-accepted"></div></div></div>
        <div class="col-lg-2 col-md-4"><div class="kitchen-col"><h5>Preparing</h5><div id="col-preparing"></div></div></div>
        <div class="col-lg-2 col-md-4"><div class="kitchen-col"><h5>Cooking</h5><div id="col-cooking"></div></div></div>
        <div class="col-lg-2 col-md-4"><div class="kitchen-col"><h5>Ready</h5><div id="col-ready"></div></div></div>
        <div class="col-lg-2 col-md-4"><div class="kitchen-col"><h5>Completed</h5><div id="col-completed"></div></div></div>
    </div>
</div>

<script>
const kdsActions = {
    'pending': ['accepted'],
    'accepted': ['preparing'],
    'preparing': ['cooking'],
    'cooking': ['ready'],
    'ready': ['delivered']
};
function kdsTimer(created) {
    const diff = Math.floor((Date.now() - new Date(created.replace(' ','T')).getTime())/60000);
    return diff + ' min';
}
function renderKds(data) {
    const cols = {pending:[],accepted:[],preparing:[],cooking:[],ready:[],'delivered':[],'cancelled':[]};
    data.orders.forEach(o => { (cols[o.status] = cols[o.status]||[]).push(o); });
    ['pending','accepted','preparing','cooking','ready','delivered'].forEach(st => {
        const el = document.getElementById('col-'+st);
        if(!el) return;
        el.innerHTML = (cols[st]||[]).map(o => {
            const items = (data.items[o.id]||[]).map(i=>`<div class="kds-item"><span>${i.quantity}× ${escapeHtml(i.food_name)}</span></div>`).join('');
            const next = (kdsActions[st]||[])[0];
            const btn = next ? `<button class="btn btn-gold btn-sm w-100" onclick="kdsUpdate(${o.id},'${next}')">Mark ${next}</button>` : '';
            return `<div class="kds-card ${st==='ready'?'ready':''}">
                <div class="d-flex justify-content-between"><span class="kds-order-no">#${o.order_number}</span><span class="kds-timer">${kdsTimer(o.created_at)}</span></div>
                <div class="small text-muted mb-2">${o.customer_name||'Guest'} · ${o.order_type}</div>
                ${items}${btn}</div>`;
        }).join('');
    });
}
function escapeHtml(s){return (s||'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));}
function loadKds(){ fetch('<?= \baseUrl('api/kitchen/orders') ?>').then(r=>r.json()).then(d=>{ if(d.success) renderKds(d.data); }); }
function kdsUpdate(id,status){
    const f=new FormData(); f.append('order_id',id); f.append('status',status); f.append('_csrf_token','<?= \csrfToken() ?>');
    fetch('<?= \baseUrl('api/kitchen/update-status') ?>',{method:'POST',body:f}).then(()=>loadKds());
}
function tickClock(){ document.getElementById('kdsClock').textContent = new Date().toLocaleTimeString(); }
loadKds(); setInterval(loadKds, 10000); setInterval(tickClock,1000); tickClock();
</script>
