<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laundry Express - @yield('title')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary: #2c7be5;
            --secondary: #6c757d;
            --success: #00d97e;
            --info: #39afd1;
            --warning: #f6c343;
            --danger: #e63757;
            --light: #f8f9fa;
            --dark: #1e2a35;
            --sidebar-width: 250px;
            --sidebar-collapsed-width: 70px;
            --transition: all 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }
        
        body {
            background-color: #f5f7f9;
            color: #3b4863;
            overflow-x: hidden;
        }
        
        .login-container {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7f9 0%, #e2e8f0 100%);
            padding: 20px;
        }
        
        .login-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 100%;
            max-width: 400px;
        }
        
        .login-header {
            background: var(--primary);
            color: white;
            padding: 25px;
            text-align: center;
        }
        
        .login-header h2 {
            font-weight: 600;
            margin-bottom: 5px;
        }
        
        .login-body {
            padding: 25px;
        }
        
        .form-control {
            padding: 12px 15px;
            border-radius: 8px;
            border: 1px solid #e3e6f0;
            transition: var(--transition);
        }
        
        .form-control:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(44, 123, 229, 0.25);
        }
        
        .btn-primary {
                        background-color: var(--primary);
            border-color: var(--primary);
            padding: 10px 15px;
            border-radius: 8px;
            font-weight: 500;
            transition: var(--transition);
        }
        
        .btn-primary:hover {
            background-color: #1a68d1;
            border-color: #1a68d1;
            transform: translateY(-2px);
        }
        
        .stats-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            padding: 20px;
            height: 100%;
            transition: var(--transition);
            border: 1px solid rgba(0, 0, 0, 0.05);
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }
        
        .stats-card .icon {
            width: 50px;
            height: 50px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 15px;
        }
        
        .stats-card h2 {
            font-weight: 600;
            margin-bottom: 5px;
            color: var(--dark);
        }
        
        .stats-card p {
            color: var(--secondary);
            margin-bottom: 0;
            font-size: 14px;
        }
        
        .table-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }
        
        .table th {
            background-color: #f8f9fa;
            border-top: none;
            font-weight: 600;
            color: var(--dark);
            padding: 15px;
        }
        
        .table td {
            padding: 15px;
            vertical-align: middle;
        }
        
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(0, 0, 0, 0.02);
        }
        
        .badge {
            padding: 6px 10px;
            border-radius: 6px;
            font-weight: 500;
        }
        
        #wrapper {
            position: relative;
            overflow-x: hidden;
            min-height: 100vh;
        }
        
        #sidebar-wrapper {
            min-height: 100vh;
            margin-left: 0;
            transition: all 0.3s ease;
            position: fixed;
            z-index: 1000;
            width: 250px;
            background: var(--dark);
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
        }
        
        #sidebar-wrapper .sidebar-heading {
            padding: 20px 15px;
            font-size: 1.2rem;
            color: white;
            text-align: center;
            background: rgba(0, 0, 0, 0.2);
            font-weight: 600;
        }
        
        #sidebar-wrapper .list-group {
            width: 250px;
            padding: 10px 0;
        }
        
        #sidebar-wrapper .list-group-item {
            background: transparent;
            border: none;
            border-radius: 0;
            padding: 12px 20px;
            color: rgba(255, 255, 255, 0.8);
            font-weight: 500;
            transition: var(--transition);
        }
        
        #sidebar-wrapper .list-group-item:hover,
        #sidebar-wrapper .list-group-item.active {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            border-left: 4px solid var(--primary);
        }
        
        #sidebar-wrapper .list-group-item i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        #page-content-wrapper {
            width: 100%;
            min-width: 0;
            transition: all 0.3s ease;
            margin-left: 250px;
        }
        
        #wrapper.sidebar-closed #sidebar-wrapper {
            margin-left: -250px;
        }
        
        #wrapper.sidebar-closed #page-content-wrapper {
            margin-left: 0;
        }
        
        .navbar-light {
            background: white !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            padding: 10px 20px;
        }
        
        .main-content {
            padding: 20px;
        }
        
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid #e3e6f0;
            padding: 15px 20px;
            font-weight: 600;
            color: var(--dark);
            border-radius: 12px 12px 0 0 !important;
        }
        
        .card-body {
            padding: 20px;
        }
        
        .form-label {
            font-weight: 500;
            margin-bottom: 8px;
            color: var(--dark);
        }
        
        .btn {
            border-radius: 8px;
            font-weight: 500;
            padding: 8px 16px;
            transition: var(--transition);
        }
        
        .btn-sm {
            padding: 5px 10px;
            font-size: 12px;
        }
        
        .clickable-card {
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .clickable-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
        
        .clickable-card.card-hover {
            transform: translateY(-3px);
            box-shadow: 0 7px 20px rgba(0, 0, 0, 0.12);
        }
        
        .clickable-card.card-clicked {
            transform: scale(0.98);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .card-hover-effect {
            position: absolute;
            bottom: -40px;
            left: 0;
            right: 0;
            background: rgba(44, 123, 229, 0.9);
            color: white;
            text-align: center;
            padding: 8px;
            transition: all 0.3s ease;
            opacity: 0;
        }
        
        .clickable-card:hover .card-hover-effect {
            bottom: 0;
            opacity: 1;
        }
        
        .stats-card .icon.bg-success {
            background-color: rgba(40, 167, 69, 0.2) !important;
            color: #28a745;
        }
        
        .stats-card .icon.bg-warning {
            background-color: rgba(255, 193, 7, 0.2) !important;
            color: #ffc107;
        }
        
        .stats-card .icon.bg-danger {
            background-color: rgba(220, 53, 69, 0.2) !important;
            color: #dc3545;
        }
        
        .stats-card .icon.bg-info {
            background-color: rgba(23, 162, 184, 0.2) !important;
            color: #17a2b8;
        }
        
        .stats-card .icon.bg-secondary {
            background-color: rgba(108, 117, 125, 0.2) !important;
            color: #6c757d;
        }
        
        .stats-card .icon.bg-dark {
            background-color: rgba(52, 58, 64, 0.2) !important;
            color: #343a40;
        }
        
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: block;
        }
        
        @media (min-width: 768px) {
            #sidebar-wrapper {
                margin-left: 0;
            }
            
            #page-content-wrapper {
                margin-left: 250px;
            }
            
            #wrapper.sidebar-closed #sidebar-wrapper {
                margin-left: -250px;
            }
            
            #wrapper.sidebar-closed #page-content-wrapper {
                margin-left: 0;
            }
        }
        
        @media (max-width: 767.98px) {
            #sidebar-wrapper {
                margin-left: -250px;
                z-index: 1000;
            }
            
            #page-content-wrapper {
                margin-left: 0;
            }
            
            #wrapper.sidebar-open #sidebar-wrapper {
                margin-left: 0;
            }
            
            #wrapper.sidebar-open #page-content-wrapper {
                margin-left: 0;
            }
            
            .stats-card {
                margin-bottom: 15px;
            }
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease forwards;
        }
        
        @media print {
            body * {
                visibility: hidden;
            }
            #struk-print, #struk-print * {
                visibility: visible;
            }
            #struk-print {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
    
    @stack('styles')
</head>
<body>
    @yield('content')
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <script>
        $(document).ready(function() {
            function initSidebarState() {
                if ($(window).width() < 768) {
                    $("#wrapper").addClass("sidebar-closed");
                    $("#menu-toggle i").removeClass('fa-times').addClass('fa-bars');
                } else {
                    $("#wrapper").removeClass("sidebar-closed");
                    $("#menu-toggle i").removeClass('fa-bars').addClass('fa-times');
                }
            }
            
            $("#menu-toggle").click(function(e) {
                e.preventDefault();
                $("#wrapper").toggleClass("sidebar-closed");
                
                $("#menu-toggle i").toggleClass('fa-bars fa-times');
                
                if ($(window).width() < 768) {
                    $("#wrapper").toggleClass("sidebar-open");
                    
                    if ($("#wrapper").hasClass("sidebar-open")) {
                        $('<div class="sidebar-overlay"></div>').appendTo('body').click(function() {
                            $("#wrapper").removeClass("sidebar-open sidebar-closed");
                            $("#menu-toggle i").removeClass('fa-times').addClass('fa-bars');
                            $(this).remove();
                        });
                    } else {
                        $('.sidebar-overlay').remove();
                    }
                }
            });

            setTimeout(function() {
                $('.alert').fadeTo(500, 0).slideUp(500, function(){
                    $(this).remove();
                });
            }, 5000);
            
            $('[data-bs-toggle="tooltip"]').tooltip();
            
            $('[data-bs-toggle="popover"]').popover();
            
            $('.stats-card').each(function(index) {
                $(this).css('animation-delay', (index * 0.1) + 's').addClass('fade-in');
            });
            
            $('.clickable-card').on('click', function() {
                const url = $(this).data('url');
                const title = $(this).data('title');
                
                $(this).addClass('card-clicked');
                setTimeout(() => {
                    $(this).removeClass('card-clicked');
                    window.location.href = url;
                }, 300);
            });

            $('.clickable-card').hover(
                function() {
                    $(this).addClass('card-hover');
                },
                function() {
                    $(this).removeClass('card-hover');
                }
            );
            
            $(document).on('shown.bs.modal', '.modal', function() {
                $(this).find('input:first').focus();
            });

            $(document).on('hidden.bs.modal', '.modal', function() {
                $(this).removeAttr('aria-hidden');
            });
            
            initSidebarState();
            
            $(window).resize(function() {
                initSidebarState();
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>