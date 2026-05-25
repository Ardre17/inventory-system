<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>DISTAN - {{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { font-family: 'Inter', sans-serif; box-sizing: border-box; }
        body { margin: 0; background: #f1f5f9; }

        /* Sidebar */
        #sidebar {
            position: fixed; top: 0; left: 0;
            height: 100vh; width: 260px;
            background: #0f172a;
            display: flex; flex-direction: column;
            transition: width 0.3s ease;
            z-index: 1000;
            overflow: visible;
        }
        #sidebar.collapsed { width: 68px; }

        /* Logo */
        .sidebar-logo {
            display: flex; align-items: center; gap: 12px;
            padding: 20px 16px; border-bottom: 1px solid #1e293b;
            min-height: 72px;
        }
        .sidebar-logo img { width: 36px; height: 36px; object-fit: contain; flex-shrink: 0; }
        .sidebar-logo-text { overflow: visible; white-space: nowrap; }
        .sidebar-logo-text strong { color: white; font-size: 1.1rem; display: block; }
        .sidebar-logo-text span { color: #64748b; font-size: 0.7rem; }
        #sidebar.collapsed .sidebar-logo-text { display: none; }

        /* Toggle button */
        .sidebar-toggle {
            position: absolute; top: 20px; right: -14px;
            background: #1e40af; color: white;
            border: none; border-radius: 50%;
            width: 28px; height: 28px;
            cursor: pointer; font-size: 0.75rem;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.3);
            z-index: 1001; overflow: visible;
        }

        /* Nav items */
        .sidebar-nav { flex: 1; overflow-y: auto; padding: 12px 0; }
        .nav-section {
            padding: 8px 16px 4px;
            font-size: 0.65rem; font-weight: 700;
            color: #475569; text-transform: uppercase;
            letter-spacing: 0.08em; white-space: nowrap;
        }
        #sidebar.collapsed .nav-section { opacity: 0; }

        .nav-item {
            display: flex; align-items: center; gap: 12px;
            padding: 10px 16px; margin: 2px 8px;
            border-radius: 8px; text-decoration: none;
            color: #94a3b8; font-size: 0.875rem; font-weight: 500;
            transition: all 0.2s; white-space: nowrap;
            position: relative;
        }
        .nav-item:hover { background: #1e293b; color: white; }
        .nav-item.active { background: #1e40af; color: white; }
        .nav-item .nav-icon { font-size: 1.1rem; flex-shrink: 0; width: 22px; text-align: center; }
        .nav-item .nav-label { overflow: visible; }
        #sidebar.collapsed .nav-label { display: none; }

        /* Tooltip cuando collapsed */
        #sidebar.collapsed .nav-item:hover::after {
            content: attr(data-tooltip);
            position: absolute; left: 68px; top: 50%;
            transform: translateY(-50%);
            background: #1e293b; color: white;
            padding: 6px 12px; border-radius: 6px;
            font-size: 0.8rem; white-space: nowrap;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
            pointer-events: none; z-index: 9999;
        }

        /* User section */
        .sidebar-user {
            padding: 12px 16px; border-top: 1px solid #1e293b;
            display: flex; align-items: center; gap: 10px;
        }
        .user-avatar {
            width: 34px; height: 34px; border-radius: 50%;
            background: #1e40af; color: white;
            display: flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 0.875rem; flex-shrink: 0;
        }
        .user-info { overflow: visible; }
        .user-info strong { color: white; font-size: 0.85rem; display: block; white-space: nowrap; overflow: visible; text-overflow: ellipsis; }
        .user-info span { color: #64748b; font-size: 0.72rem; }
        #sidebar.collapsed .user-info { display: none; }

        /* Logout */
        .nav-logout {
            display: flex; align-items: center; gap: 12px;
            padding: 8px 16px; margin: 4px 8px;
            border-radius: 8px; cursor: pointer;
            color: #ef4444; font-size: 0.875rem; font-weight: 500;
            transition: all 0.2s; white-space: nowrap; border: none;
            background: transparent; width: calc(100% - 16px);
        }
        .nav-logout:hover { background: #1e293b; }
        #sidebar.collapsed .nav-logout span { display: none; }

        /* Main content */
        #main-content {
            margin-left: 260px;
            transition: margin-left 0.3s ease;
            min-height: 100vh;
        }
        #main-content.expanded { margin-left: 68px; }

        /* Top bar */
        .topbar {
            background: white; padding: 16px 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex; align-items: center; justify-content: space-between;
            position: sticky; top: 0; z-index: 100;
        }
        .topbar-title { font-size: 1.1rem; font-weight: 700; color: #0f172a; }
        .topbar-date { font-size: 0.8rem; color: #64748b; }

        /* Mobile overlay */
        #overlay {
            display: none; position: fixed;
            top: 0; left: 0; width: 100%; height: 100%;
            background: rgba(0,0,0,0.5); z-index: 999;
        }

        /* Mobile */
        @media (max-width: 768px) {
            #sidebar { width: 260px; left: -260px; }
            #sidebar.mobile-open { left: 0; }
            #sidebar.collapsed { width: 260px; left: -260px; }
            #main-content { margin-left: 0 !important; }
            .mobile-toggle { display: flex !important; }
            .sidebar-toggle { display: none; }
        }

        .mobile-toggle {
            display: none; background: #0f172a; color: white;
            border: none; border-radius: 8px; padding: 8px;
            cursor: pointer; font-size: 1.1rem;
        }
    </style>
</head>
<body>

<div id="overlay" onclick="closeMobileSidebar()"></div>

<!-- Sidebar -->
<div id="sidebar">
    <button class="sidebar-toggle" onclick="toggleSidebar()" id="toggleBtn">◀</button>

    <!-- Logo -->
    <div class="sidebar-logo">
        <img src="/images/logo.png" alt="DISTAN">
        <div class="sidebar-logo-text">
            <strong>DISTAN</strong>
            <span>Todo tu logística</span>
        </div>
    </div>

    <!-- Nav -->
    <nav class="sidebar-nav">

        <div class="nav-section">Principal</div>
        <a href="{{ route('dashboard') }}"
           class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"
           data-tooltip="Dashboard">
            <span class="nav-icon">📊</span>
            <span class="nav-label">Dashboard</span>
        </a>

        <div class="nav-section">Comercial</div>
        <a href="{{ route('orders.index') }}"
           class="nav-item {{ request()->routeIs('orders.*') ? 'active' : '' }}"
           data-tooltip="Órdenes">
            <span class="nav-icon">🧾</span>
            <span class="nav-label">Órdenes</span>
        </a>
        <a href="{{ route('clients.index') }}"
           class="nav-item {{ request()->routeIs('clients.*') ? 'active' : '' }}"
           data-tooltip="Clientes">
            <span class="nav-icon">👥</span>
            <span class="nav-label">Clientes</span>
        </a>

        <div class="nav-section">Almacén</div>
        <a href="{{ route('products.index') }}"
           class="nav-item {{ request()->routeIs('products.*') ? 'active' : '' }}"
           data-tooltip="Productos">
            <span class="nav-icon">🏷️</span>
            <span class="nav-label">Productos</span>
        </a>
        <a href="{{ route('categories.index') }}"
           class="nav-item {{ request()->routeIs('categories.*') ? 'active' : '' }}"
           data-tooltip="Categorías">
            <span class="nav-icon">📦</span>
            <span class="nav-label">Categorías</span>
        </a>
        <a href="{{ route('inventory-periods.index') }}"
           class="nav-item {{ request()->routeIs('inventory-periods.*') ? 'active' : '' }}"
           data-tooltip="Inventario">
            <span class="nav-icon">📋</span>
            <span class="nav-label">Inventario</span>
        </a>
        <a href="{{ route('raw-materials.index') }}"
           class="nav-item {{ request()->routeIs('raw-materials.*') ? 'active' : '' }}"
           data-tooltip="Materias Primas">
            <span class="nav-icon">🧪</span>
            <span class="nav-label">Materias Primas</span>
        </a>

        <div class="nav-section">Producción</div>
        <a href="{{ route('production-orders.index') }}"
           class="nav-item {{ request()->routeIs('production-orders.*') ? 'active' : '' }}"
           data-tooltip="Producción">
            <span class="nav-icon">🏭</span>
            <span class="nav-label">Órdenes de Producción</span>
        </a>
        
        
        <a href="{{ route('product-raw-materials.index') }}"
        class="nav-item {{ request()->routeIs('product-raw-materials.*') ? 'active' : '' }}"
        data-tooltip="MP por Producto">
            <span class="nav-icon">🔗</span>
            <span class="nav-label">MP por Producto</span>
        </a>
        <a href="/supplies"
            class="nav-item {{ request()->is('supplies*') ? 'active' : '' }}"
            data-tooltip="Suministros">
                <span class="nav-icon">🏷️</span>
                <span class="nav-label">Suministros</span>
            </a>
        <div class="nav-section">Configuración</div>
        <a href="{{ route('suppliers.index') }}"
           class="nav-item {{ request()->routeIs('suppliers.*') ? 'active' : '' }}"
           data-tooltip="Proveedores">
            <span class="nav-icon">🚚</span>
            <span class="nav-label">Proveedores</span>
        </a>
        <a href="{{ route('profile.edit') }}"
           class="nav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}"
           data-tooltip="Mi Perfil">
            <span class="nav-icon">⚙️</span>
            <span class="nav-label">Mi Perfil</span>
        </a>

    </nav>

    <!-- User -->
    <div class="sidebar-user">
        <div class="user-avatar">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</div>
        <div class="user-info">
            <strong>{{ auth()->user()->name }}</strong>
            <span>{{ auth()->user()->email }}</span>
        </div>
    </div>
    <form method="POST" action="{{ route('logout') }}" style="margin: 0 0 8px 0;">
        @csrf
        <button type="submit" class="nav-logout">
            <span class="nav-icon">🚪</span>
            <span>Cerrar sesión</span>
        </button>
    </form>

</div>

<!-- Main -->
<div id="main-content">
    <!-- Topbar -->
    <div class="topbar">
        <div style="display:flex; align-items:center; gap:12px;">
            <button class="mobile-toggle" onclick="openMobileSidebar()">☰</button>
            <div class="topbar-title">{{ $header ?? '' }}</div>
        </div>
        <div class="topbar-date">{{ now()->format('d/m/Y') }}</div>
    </div>

    <!-- Page Content -->
    <main style="padding: 24px;">
        {{ $slot ?? '' }}
    </main>
</div>

<script>
    let collapsed = false;

    function toggleSidebar() {
        collapsed = !collapsed;
        const sidebar = document.getElementById('sidebar');
        const main = document.getElementById('main-content');
        const btn = document.getElementById('toggleBtn');

        sidebar.classList.toggle('collapsed', collapsed);
        main.classList.toggle('expanded', collapsed);
        btn.textContent = collapsed ? '▶' : '◀';

        localStorage.setItem('sidebarCollapsed', collapsed);
    }

    function openMobileSidebar() {
        document.getElementById('sidebar').classList.add('mobile-open');
        document.getElementById('overlay').style.display = 'block';
    }

    function closeMobileSidebar() {
        document.getElementById('sidebar').classList.remove('mobile-open');
        document.getElementById('overlay').style.display = 'none';
    }

    // Restaurar estado del sidebar
    document.addEventListener('DOMContentLoaded', function() {
        const saved = localStorage.getItem('sidebarCollapsed');
        if (saved === 'true') {
            collapsed = true;
            document.getElementById('sidebar').classList.add('collapsed');
            document.getElementById('main-content').classList.add('expanded');
            document.getElementById('toggleBtn').textContent = '▶';
        }
    });
</script>

</body>
</html>
