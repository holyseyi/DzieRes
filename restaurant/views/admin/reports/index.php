<?php
/**
 * Admin: Reports & Analytics
 */
?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Reports & Analytics</h4>
    <div class="btn-group btn-group-sm">
        <button class="btn btn-outline-secondary active" data-range="7" id="rangeBtn">Last 7 Days</button>
        <button class="btn btn-outline-secondary" data-range="30">Last 30 Days</button>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3"><div class="stat-card-admin"><div class="stat-icon bg-success bg-opacity-10 text-success"><i class="fas fa-money-bill-wave"></i></div><div class="stat-number" id="kpiToday">0</div><div class="text-muted">Today Revenue</div></div></div>
    <div class="col-md-3"><div class="stat-card-admin"><div class="stat-icon bg-info bg-opacity-10 text-info"><i class="fas fa-calendar-month"></i></div><div class="stat-number" id="kpiMonth">0</div><div class="text-muted">Month Revenue</div></div></div>
    <div class="col-md-3"><div class="stat-card-admin"><div class="stat-icon bg-warning bg-opacity-10 text-warning"><i class="fas fa-chart-line"></i></div><div class="stat-number" id="kpiTotal">0</div><div class="text-muted">Total Revenue</div></div></div>
    <div class="col-md-3"><div class="stat-card-admin"><div class="stat-icon bg-primary bg-opacity-10 text-primary"><i class="fas fa-utensils"></i></div><div class="stat-number" id="kpiOrders">0</div><div class="text-muted">Period Orders</div></div></div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm"><div class="card-header bg-white"><h6 class="mb-0"><i class="fas fa-chart-area me-2 text-gold"></i>Revenue & Orders</h6></div><div class="card-body"><canvas id="reportChart" height="260"></canvas></div></div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm"><div class="card-header bg-white"><h6 class="mb-0"><i class="fas fa-star me-2 text-gold"></i>Top Meals</h6></div><div class="card-body" id="topMeals"><div class="text-muted">Loading...</div></div></div>
    </div>
</div>

<div class="row g-4 mt-1">
    <div class="col-lg-4"><div class="card border-0 shadow-sm"><div class="card-header bg-white"><h6 class="mb-0">Peak Hours</h6></div><div class="card-body" id="peakHours"><div class="text-muted">Loading...</div></div></div></div>
    <div class="col-lg-4"><div class="card border-0 shadow-sm"><div class="card-header bg-white"><h6 class="mb-0">Reservation Trends</h6></div><div class="card-body" id="resTrends"><div class="text-muted">Loading...</div></div></div></div>
    <div class="col-lg-4"><div class="card border-0 shadow-sm"><div class="card-header bg-white"><h6 class="mb-0">Customer Growth</h6></div><div class="card-body" id="custGrowth"><div class="text-muted">Loading...</div></div></div></div>
</div>

<script>
function fmt(n){ return '<?= \config('currency.symbol') ?>' + Number(n).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2}); }
function loadReports(range){
    fetch('<?= \baseUrl('api/reports/revenue') ?>?range='+range).then(r=>r.json()).then(d=>{
        if(!d.success) return;
        const data=d.data;
        document.getElementById('kpiToday').textContent = fmt(data.today);
        document.getElementById('kpiMonth').textContent = fmt(data.month);
        document.getElementById('kpiTotal').textContent = fmt(data.total);
        document.getElementById('kpiOrders').textContent = (data.top_meals.reduce((s,m)=>s+Number(m.qty),0)) || 0;

        const labels = (data.top_meals||[]).map(m=>m.name);
        const vals = (data.top_meals||[]).map(m=>Number(m.qty));
        document.getElementById('topMeals').innerHTML = labels.length ? labels.map((l,i)=>`<div class="d-flex justify-content-between mb-1"><span>${escapeHtml(l)}</span><strong>${vals[i]}</strong></div>`).join('') : '<div class="text-muted">No data</div>';

        renderList('peakHours', (data.peak_hours||[]).map(h=>`<div class="d-flex justify-content-between"><span>${h.hour}:00</span><strong>${h.orders}</strong></div>`));
        renderList('resTrends', (data.reservation_trends||[]).map(r=>`<div class="d-flex justify-content-between"><span>${r.reservation_date}</span><strong>${r.count}</strong></div>`));
        renderList('custGrowth', (data.customer_growth||[]).reverse().map(c=>`<div class="d-flex justify-content-between"><span>${c.month}</span><strong>${c.count}</strong></div>`));

        const ctx = document.getElementById('reportChart').getContext('2d');
        if(window._reportChart) window._reportChart.destroy();
        window._reportChart = new Chart(ctx, {
            type:'line',
            data:{ labels:(data.top_meals||[]).map(()=> 'Top'), datasets:[] },
            options:{ responsive:true }
        });
        // Simpler: show top meals as bar
        window._reportChart.destroy();
        window._reportChart = new Chart(ctx, {
            type:'bar',
            data:{ labels, datasets:[{ label:'Orders', data:vals, backgroundColor:'#001a4a' }] },
            options:{ responsive:true, plugins:{ legend:{display:false} } }
        });
    });
}
function renderList(id, items){ document.getElementById(id).innerHTML = items.length ? items.join('') : '<div class="text-muted">No data</div>'; }
function escapeHtml(s){return (s||'').replace(/[&<>"']/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));}
document.querySelectorAll('#rangeBtn, [data-range]').forEach(b=>b.addEventListener('click',function(){
    document.querySelectorAll('[data-range]').forEach(x=>x.classList.remove('active'));
    this.classList.add('active');
    document.getElementById('rangeBtn').textContent = this.dataset.range==='30'?'Last 30 Days':'Last 7 Days';
    loadReports(this.dataset.range);
}));
loadReports(7);
</script>
