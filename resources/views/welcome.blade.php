<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DriveEasy - Aluguel de Carros Premium</title>

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary: #2563eb;
            --primary-dark: #1d4ed8;
            --secondary: #10b981;
            --dark: #1e293b;
            --light: #f8fafc;
            --gray: #64748b;
            --shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            --radius: 12px;
            --transition: all 0.3s ease;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
            color: var(--dark);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* Header & Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 1.2rem 2rem;
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 1000;
            box-shadow: var(--shadow);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 1.8rem;
            font-weight: 800;
            color: var(--primary);
        }

        .logo i {
            font-size: 2rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            list-style: none;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
            transition: var(--transition);
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
        }

        .nav-links a:hover {
            color: var(--primary);
            background: rgba(37, 99, 235, 0.1);
        }

        /* Auth Links - Estilo do Laravel original */
        .auth-links {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .auth-links a {
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 600;
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            transition: var(--transition);
        }

        .auth-links .login-link {
            color: var(--dark);
        }

        .auth-links .login-link:hover {
            color: var(--primary);
            background: rgba(37, 99, 235, 0.1);
        }

        .auth-links .register-link {
            background: var(--primary);
            color: white;
        }

        .auth-links .register-link:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(37, 99, 235, 0.2);
        }

        /* Usuário logado */
        .user-menu {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            border-radius: var(--radius);
            background: rgba(37, 99, 235, 0.1);
            cursor: pointer;
            transition: var(--transition);
        }

        .user-menu:hover {
            background: rgba(37, 99, 235, 0.2);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
        }

        .user-name {
            font-weight: 600;
            color: var(--dark);
        }

        /* CTA Button */
        .cta-button {
            background: var(--primary);
            color: white;
            padding: 0.8rem 1.8rem;
            border-radius: 50px;
            font-weight: 700;
            transition: var(--transition);
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .cta-button:hover {
            background: var(--primary-dark);
            transform: translateY(-3px);
            box-shadow: 0 10px 20px rgba(37, 99, 235, 0.2);
        }

        /* Hero Section */
        .hero {
            padding: 10rem 2rem 6rem;
            background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)),
                        url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80');
            background-size: cover;
            background-position: center;
            color: white;
            text-align: center;
            border-radius: 0 0 40px 40px;
            margin-bottom: 4rem;
        }

        .hero h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .hero p {
            font-size: 1.3rem;
            max-width: 700px;
            margin: 0 auto 2rem;
            opacity: 0.9;
        }

        /* Search Form */
        .search-container {
            background: white;
            padding: 2.5rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            max-width: 1000px;
            margin: -5rem auto 4rem;
            position: relative;
            z-index: 10;
        }

        .search-form {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: var(--dark);
        }

        .form-group input,
        .form-group select {
            padding: 0.9rem 1.2rem;
            border: 2px solid #e2e8f0;
            border-radius: var(--radius);
            font-size: 1rem;
            transition: var(--transition);
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
        }

        /* Features Section */
        .features {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            color: var(--dark);
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
        }

        .feature-card {
            background: white;
            padding: 2rem;
            border-radius: var(--radius);
            text-align: center;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .feature-card:hover {
            transform: translateY(-10px);
        }

        .feature-icon {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem;
        }

        .feature-icon i {
            font-size: 1.8rem;
            color: white;
        }

        /* Cars Section */
        .cars-section {
            padding: 4rem 2rem;
            background: var(--light);
        }

        .cars-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .car-card {
            background: white;
            border-radius: var(--radius);
            overflow: hidden;
            box-shadow: var(--shadow);
            transition: var(--transition);
        }

        .car-card:hover {
            transform: translateY(-10px);
        }

        .car-image {
            height: 200px;
            background: var(--gray);
            background-size: cover;
            background-position: center;
        }

        .car-info {
            padding: 1.5rem;
        }

        .car-price {
            color: var(--primary);
            font-size: 1.5rem;
            font-weight: 700;
            margin: 0.5rem 0;
        }

        .car-features {
            display: flex;
            gap: 1rem;
            margin: 1rem 0;
            font-size: 0.9rem;
            color: var(--gray);
        }

        .car-features span {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        /* Stats */
        .stats {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 4rem 2rem;
            text-align: center;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .stat-item h3 {
            font-size: 3rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }

        /* Testimonials */
        .testimonials {
            padding: 4rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .testimonial-card {
            background: white;
            padding: 2rem;
            border-radius: var(--radius);
            box-shadow: var(--shadow);
            margin: 1rem;
        }

        .testimonial-text {
            font-style: italic;
            margin-bottom: 1rem;
        }

        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: var(--gray);
        }

        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            padding: 4rem 2rem 2rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-section h4 {
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: #cbd5e1;
            text-decoration: none;
            transition: var(--transition);
        }

        .footer-links a:hover {
            color: white;
            padding-left: 5px;
        }

        .social-links {
            display: flex;
            gap: 1rem;
            margin-top: 1rem;
        }

        .social-links a {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            transition: var(--transition);
        }

        .social-links a:hover {
            background: var(--primary);
            transform: translateY(-3px);
        }

        /* Laravel Info Footer */
        .laravel-info {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.9rem;
            color: #cbd5e1;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }

            .nav-links {
                gap: 1rem;
                flex-wrap: wrap;
                justify-content: center;
            }

            .auth-links {
                margin-top: 1rem;
            }

            .hero h1 {
                font-size: 2.5rem;
            }

            .hero p {
                font-size: 1.1rem;
            }

            .search-container {
                margin: -3rem 1rem 4rem;
                padding: 1.5rem;
            }

            .search-form {
                grid-template-columns: 1fr;
            }
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate {
            animation: fadeInUp 0.6s ease-out;
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        ::-webkit-scrollbar-thumb {
            background: var(--primary);
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">
            <i class="fas fa-car"></i>
            <span>DriveEasy</span>
        </div>
        <ul class="nav-links">
            <li><a href="#home">Início</a></li>
            <li><a href="#cars">Carros</a></li>
            <li><a href="#features">Vantagens</a></li>
            <li><a href="#testimonials">Depoimentos</a></li>
            <li><a href="#contact">Contato</a></li>
        </ul>
        
        <!-- Auth Links - Estilo similar ao Laravel original -->
        <div class="auth-links">
            @if (Route::has('login'))
                @auth
                    <div class="user-menu">
                        <div class="user-avatar">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                        <span class="user-name">{{ Auth::user()->name }}</span>
                    </div>
                    <a href="{{ url('/home') }}" class="login-link">
                        <i class="fas fa-home"></i> Home
                    </a>
                @else
                    <a href="{{ route('login') }}" class="login-link">
                        <i class="fas fa-sign-in-alt"></i> Log in
                    </a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="register-link">
                            <i class="fas fa-user-plus"></i> Register
                        </a>
                    @endif
                @endauth
            @endif
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero" id="home">
        <div class="hero-content animate">
            <h1>Alugue o carro perfeito para sua viagem</h1>
            <p>+ de 100 modelos disponíveis com entrega em todo o Brasil. Economize até 30% comparado às locadoras tradicionais.</p>
            <button class="cta-button" style="font-size: 1.2rem; padding: 1rem 2.5rem;">
                <i class="fas fa-search"></i> Buscar Carros Agora
            </button>
        </div>
    </section>

    <!-- Search Form -->
    <div class="search-container animate">
        <form class="search-form" id="searchForm">
            <div class="form-group">
                <label><i class="fas fa-map-marker-alt"></i> Local de Retirada</label>
                <select>
                    <option>São Paulo - Aeroporto GRU</option>
                    <option>Rio de Janeiro - Aeroporto GIG</option>
                    <option>Brasília - Aeroporto BSB</option>
                    <option>Salvador - Aeroporto SSA</option>
                </select>
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> Data de Retirada</label>
                <input type="date" id="pickupDate">
            </div>
            <div class="form-group">
                <label><i class="fas fa-calendar-alt"></i> Data de Devolução</label>
                <input type="date" id="returnDate">
            </div>
            <div class="form-group">
                <label><i class="fas fa-car"></i> Tipo de Carro</label>
                <select>
                    <option>Econômico</option>
                    <option>SUV</option>
                    <option>Luxo</option>
                    <option>Familiar</option>
                </select>
            </div>
            <div class="form-group" style="grid-column: span 2;">
                <button type="submit" class="cta-button" style="width: 100%; margin-top: 1.8rem;">
                    <i class="fas fa-search"></i> Ver 156 Carros Disponíveis
                </button>
            </div>
        </form>
    </div>

    <!-- Features -->
    <section class="features" id="features">
        <h2 class="section-title animate">Por que escolher a DriveEasy?</h2>
        <div class="features-grid">
            <div class="feature-card animate">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Segurança Total</h3>
                <p>Proteção completa em todos os aluguéis com seguro incluso e assistência 24h.</p>
            </div>
            <div class="feature-card animate">
                <div class="feature-icon">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3>Reserva Rápida</h3>
                <p>Reserve em 2 minutos com nossa plataforma simples e intuitiva.</p>
            </div>
            <div class="feature-card animate">
                <div class="feature-icon">
                    <i class="fas fa-tag"></i>
                </div>
                <h3>Melhor Preço</h3>
                <p>Garantia do menor preço ou devolvemos a diferença.</p>
            </div>
            <div class="feature-card animate">
                <div class="feature-icon">
                    <i class="fas fa-headset"></i>
                </div>
                <h3>Suporte 24/7</h3>
                <p>Nossa equipe está disponível a qualquer hora para te ajudar.</p>
            </div>
        </div>
    </section>

    <!-- Cars Section -->
    <section class="cars-section" id="cars">
        <h2 class="section-title animate">Carros Mais Populares</h2>
        <div class="cars-grid">
            <!-- Car 1 -->
            <div class="car-card animate">
                <div class="car-image" style="background-image: url('https://images.unsplash.com/photo-1549399542-7e3f8b79c341?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80');"></div>
                <div class="car-info">
                    <h3>Volkswagen Polo</h3>
                    <div class="car-price">R$ 129<small>/dia</small></div>
                    <div class="car-features">
                        <span><i class="fas fa-user"></i> 5 pessoas</span>
                        <span><i class="fas fa-suitcase"></i> 2 malas</span>
                        <span><i class="fas fa-gas-pump"></i> 14km/L</span>
                    </div>
                    <button class="cta-button" style="width: 100%;">
                        <i class="fas fa-calendar-check"></i> Reservar Agora
                    </button>
                </div>
            </div>
            <!-- Car 2 -->
            <div class="car-card animate">
                <div class="car-image" style="background-image: url('https://images.unsplash.com/photo-1503376780353-7e6692767b70?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80');"></div>
                <div class="car-info">
                    <h3>Jeep Renegade</h3>
                    <div class="car-price">R$ 189<small>/dia</small></div>
                    <div class="car-features">
                        <span><i class="fas fa-user"></i> 5 pessoas</span>
                        <span><i class="fas fa-suitcase"></i> 4 malas</span>
                        <span><i class="fas fa-gas-pump"></i> 11km/L</span>
                    </div>
                    <button class="cta-button" style="width: 100%;">
                        <i class="fas fa-calendar-check"></i> Reservar Agora
                    </button>
                </div>
            </div>
            <!-- Car 3 -->
            <div class="car-card animate">
                <div class="car-image" style="background-image: url('https://images.unsplash.com/photo-1553440569-bcc63803a83d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80');"></div>
                <div class="car-info">
                    <h3>BMW 320i</h3>
                    <div class="car-price">R$ 289<small>/dia</small></div>
                    <div class="car-features">
                        <span><i class="fas fa-user"></i> 5 pessoas</span>
                        <span><i class="fas fa-suitcase"></i> 3 malas</span>
                        <span><i class="fas fa-bolt"></i> Automático</span>
                    </div>
                    <button class="cta-button" style="width: 100%;">
                        <i class="fas fa-calendar-check"></i> Reservar Agora
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stats">
        <div class="stats-grid">
            <div class="stat-item animate">
                <h3>50K+</h3>
                <p>Clientes Satisfeitos</p>
            </div>
            <div class="stat-item animate">
                <h3>100+</h3>
                <p>Modelos de Carros</p>
            </div>
            <div class="stat-item animate">
                <h3>200+</h3>
                <p>Locais de Retirada</p>
            </div>
            <div class="stat-item animate">
                <h3>24/7</h3>
                <p>Suporte Disponível</p>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section class="testimonials" id="testimonials">
        <h2 class="section-title animate">O que nossos clientes dizem</h2>
        <div class="features-grid">
            <div class="testimonial-card animate">
                <div class="testimonial-text">
                    "Melhor experiência de aluguel que já tive! Processo rápido, carro impecável e preço justo. Recomendo!"
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar"></div>
                    <div>
                        <h4>Ana Silva</h4>
                        <p>Viagem a negócios</p>
                    </div>
                </div>
            </div>
            <div class="testimonial-card animate">
                <div class="testimonial-text">
                    "Perfeito para nossa viagem em família. O SUV estava novo e o atendimento foi excelente. Voltaremos!"
                </div>
                <div class="testimonial-author">
                    <div class="author-avatar"></div>
                    <div>
                        <h4>Carlos Mendes</h4>
                        <p>Férias em família</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contact">
        <div class="footer-content">
            <div class="footer-section">
                <h4>DriveEasy</h4>
                <p>A melhor plataforma de aluguel de carros do Brasil. Conectamos você ao carro perfeito para cada momento.</p>
                <div class="social-links">
                    <a href="#"><i class="fab fa-facebook-f"></i></a>
                    <a href="#"><i class="fab fa-instagram"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
            <div class="footer-section">
                <h4>Links Rápidos</h4>
                <ul class="footer-links">
                    <li><a href="#home">Início</a></li>
                    <li><a href="#cars">Carros</a></li>
                    <li><a href="#features">Vantagens</a></li>
                    <li><a href="#testimonials">Depoimentos</a></li>
                    <li><a href="#contact">Contato</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Contato</h4>
                <ul class="footer-links">
                    <li><i class="fas fa-phone"></i> (11) 99999-9999</li>
                    <li><i class="fas fa-envelope"></i> contato@driveeasy.com</li>
                    <li><i class="fas fa-map-marker-alt"></i> São Paulo, SP</li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Baixe nosso App</h4>
                <p>Disponível na App Store e Google Play</p>
                <div style="margin-top: 1rem;">
                    <button class="cta-button" style="margin-bottom: 0.5rem; width: 100%;">
                        <i class="fab fa-apple"></i> App Store
                    </button>
                    <button class="cta-button" style="background: #10b981; width: 100%;">
                        <i class="fab fa-google-play"></i> Google Play
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Laravel Info (similar ao original) -->
        <div class="laravel-info">
            <div style="display: flex; align-items: center; justify-content: center; gap: 1rem; margin-bottom: 1rem;">
                <svg style="width: 24px; height: 24px; color: #10b981;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <a href="#" style="color: #10b981; text-decoration: none;">Shop</a>
                <svg style="width: 24px; height: 24px; color: #ef4444;" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                </svg>
                <a href="#" style="color: #ef4444; text-decoration: none;">Sponsor</a>
            </div>
            <p>Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})</p>
            <p>&copy; 2024 DriveEasy. Todos os direitos reservados.</p>
        </div>
    </footer>

    <script>
        // Set minimum dates for date inputs
        const today = new Date().toISOString().split('T')[0];
        const pickupDate = document.getElementById('pickupDate');
        const returnDate = document.getElementById('returnDate');
        
        pickupDate.min = today;
        returnDate.min = today;

        // Animation on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate');
                }
            });
        }, observerOptions);

        // Observe all elements with data-animate class
        document.querySelectorAll('.animate').forEach(el => {
            observer.observe(el);
        });

        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Form submission
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            alert('Busca realizada! Redirecionando para os resultados...');
            // In a real application, you would submit the form here
        });

        // Update return date min based on pickup date
        pickupDate.addEventListener('change', function() {
            returnDate.min = this.value;
            if (returnDate.value < this.value) {
                returnDate.value = this.value;
            }
        });

        // Add some interactivity to car cards
        document.querySelectorAll('.car-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-10px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Simulate user login state (remover em produção)
        // Para fins de demonstração, vamos simular um usuário logado
        const simulateLoggedIn = false; // Mude para true para ver o estado logado
        
        if (simulateLoggedIn) {
            // Esta parte seria substituída pelo blade do Laravel
            console.log('Usuário logado - estado simulado');
        }
    </script>
</body>
</html>