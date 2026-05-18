<?php
require 'config.php';
$isLoggedIn = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MegazenTrack – Inventory Management System</title>
    <link rel="stylesheet" href="assets/css/landing.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body class="landing-body">

    <!-- ── 1. Global Navigation Headers ── -->
    <header class="landing-header">
        <div class="landing-header-left">
            <a href="index.php" class="landing-logo">
                <span class="oracle">Megazen</span>
                <span class="netsuite">Track</span>
            </a>
            <nav class="landing-nav-links">
                <a href="#about" class="landing-nav-link">Overview</a>
                <a href="#benefits" class="landing-nav-link">Benefits</a>
                <a href="#features" class="landing-nav-link">Features</a>
                <a href="#pricing" class="landing-nav-link">Pricing</a>
                <a href="#faq" class="landing-nav-link">Q&A</a>
            </nav>
        </div>
        <div class="landing-header-right">
            <a href="login.php" class="landing-btn-login">Log In</a>
            <a href="signup.php" class="landing-btn-tour">Free Product Tour</a>
        </div>
    </header>

    <!-- Sticky Sub-Navigation Bar -->
    <div class="sticky-anchor-bar">
        <div class="anchor-bar-links">
            <a href="#about" class="anchor-link active">What is Inventory?</a>
            <a href="#benefits" class="anchor-link">Benefits</a>
            <a href="#features" class="anchor-link">Features</a>
            <a href="#pricing" class="anchor-link">Pricing</a>
            <a href="#faq" class="anchor-link">Q&A</a>
            <a href="#resources" class="anchor-link">Resources</a>
        </div>
    </div>

    <!-- ── 2. A. The Hero Section (Above the Fold) ── -->
    <section class="landing-hero" id="about">
        <div class="hero-grid">
            <div class="hero-text">
                <h1>Take Absolute Control of Your Operations with <span>MegazenTrack Inventory Management</span></h1>
                <p>Optimize inventory levels, ensure product availability, and track multi-channel fulfillment in real-time. Boost profitability using our <strong>AI-powered supply chain predictive forecasting</strong>.</p>
                <div class="hero-ctas">
                    <a href="signup.php" class="landing-btn-tour">Start Free Product Tour &nbsp;<i class="fa-solid fa-arrow-right"></i></a>
                    <a href="login.php" class="landing-btn-login" style="padding: 9px 18px;"><i class="fa-solid fa-right-to-bracket" style="margin-right: 6px;"></i>Log In to Console</a>
                </div>
            </div>
            <div class="hero-image">
                <img src="assets/images/netsuite_hero_dashboard.png" alt="MegazenTrack Inventory Management Dashboard Mockup">
            </div>
        </div>
    </section>

    <!-- ── 2. B. The Social Proof Block (Testimonial) ── -->
    <section class="testimonial-block">
        <div class="testimonial-container">
            <p class="testimonial-quote">"MegazenTrack gave us 100% real-time clarity across our five national fulfillment warehouses. We optimized our stock levels by 30% and slashed backorders to zero in under three months."</p>
            <div class="testimonial-author">Melvin Rodriguez <span>— Chief Operating Officer, Mielle Organics</span></div>
            <div class="testimonial-logo"><i class="fa-solid fa-leaf" style="margin-right: 6px; color: #fbbf24;"></i>mielle organics</div>
        </div>
    </section>

    <!-- ── 2. C. Grid-Based Matrix Variations ── -->
    <!-- The Benefits Matrix (2x2 Grid) -->
    <section class="section section-white" id="benefits">
        <div class="section-container">
            <div class="section-header">
                <h2>Optimize Supply Chain Profitability</h2>
                <p>MegazenTrack equips your business with cloud-based automation tools to eliminate overhead waste, reduce carry costs, and scale transactions seamlessly.</p>
            </div>
            <div class="benefits-matrix">
                <!-- Benefit 1 -->
                <div class="benefit-card">
                    <div class="benefit-icon"><i class="fa-solid fa-chart-line"></i></div>
                    <h3>Optimize Stock Levels</h3>
                    <p>Prevent expensive stockouts and carry fees by aligning stock volumes with target metrics.</p>
                    <ul class="benefit-bullets">
                        <li>Automated safety stock and reorder alert calculations.</li>
                        <li>Demand planning tools adjusted to seasonal market trends.</li>
                        <li>Real-time stock ratio audits per location.</li>
                    </ul>
                </div>
                <!-- Benefit 2 -->
                <div class="benefit-card">
                    <div class="benefit-icon"><i class="fa-solid fa-dollar-sign"></i></div>
                    <h3>Boost Enterprise Profitability</h3>
                    <p>Free up cash flow bound in stagnant warehouse stock and redirect capital to business scaling.</p>
                    <ul class="benefit-bullets">
                        <li>Dramatically reduced stock storage footprint and logistics waste.</li>
                        <li>Immediate alerts on slow-moving inventory batches.</li>
                        <li>Dynamic product profit margin tracking.</li>
                    </ul>
                </div>
                <!-- Benefit 3 -->
                <div class="benefit-card">
                    <div class="benefit-icon"><i class="fa-solid fa-scale-down"></i></div>
                    <h3>Decrease COGS</h3>
                    <p>Reduce Cost of Goods Sold by integrating purchases directly with active suppliers.</p>
                    <ul class="benefit-bullets">
                        <li>Wholesale volume price tier mapping.</li>
                        <li>Automated purchase orders linked to optimal lead times.</li>
                        <li>Fulfillment audit trails eliminating delivery discrepancies.</li>
                    </ul>
                </div>
                <!-- Benefit 4 -->
                <div class="benefit-card">
                    <div class="benefit-icon"><i class="fa-solid fa-brain"></i></div>
                    <h3>AI-Powered Operations Reporting</h3>
                    <p>Leverage machine learning to predict stock depletion cycles and automate ordering queues.</p>
                    <ul class="benefit-bullets">
                        <li>Intelligent item demand prediction curves.</li>
                        <li>Automated replenishment cycles based on live forecasts.</li>
                        <li>Detailed graphical KPIs and semicircular balance meters.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Asymmetrical Z-Pattern Feature Showcase -->
    <section class="section section-grey" id="features">
        <div class="section-container">
            <div class="section-header">
                <h2>Deep-Dive Feature Showcase</h2>
                <p>Explore the sophisticated, automated features that make MegazenTrack the global standard in inventory management.</p>
            </div>

            <!-- Row 1: Text Left, Image Right -->
            <div class="feature-showcase-row">
                <div class="feature-content">
                    <span style="font-size:11px; font-weight:800; color:#e51c24; text-transform:uppercase; letter-spacing:1px; display:block; margin-bottom:8px;">Enterprise Scale Logistics</span>
                    <h3>Multi-Location Fulfillment</h3>
                    <p>Distribute inventory strategically across multiple warehouses and fulfillment centers. MegazenTrack dynamically calculates the optimal shipping locations to minimize transit times and slash courier rates.</p>
                    <ul class="feature-list">
                        <li><i class="fa-solid fa-circle-check" style="color:#2c8a4c; margin-right:8px;"></i> Distributed warehouse stock grids.</li>
                        <li><i class="fa-solid fa-circle-check" style="color:#2c8a4c; margin-right:8px;"></i> Inter-location inventory transfer workflows.</li>
                        <li><i class="fa-solid fa-circle-check" style="color:#2c8a4c; margin-right:8px;"></i> Intelligent routing automation.</li>
                    </ul>
                    <a href="signup.php" class="landing-btn-tour">Try Multi-Location Logs</a>
                </div>
                <div class="feature-image">
                    <img src="assets/images/netsuite_feature_warehouse.png" alt="Multi-Location Logistics Mockup in MegazenTrack">
                </div>
            </div>

            <!-- Row 2: Image Left, Text Right (Flipped Z-pattern) -->
            <div class="feature-showcase-row flipped">
                <div class="feature-image">
                    <img src="assets/images/netsuite_feature_replenishment.png" alt="Automated Inventory Replenishment Mockup in MegazenTrack">
                </div>
                <div class="feature-content">
                    <span style="font-size:11px; font-weight:800; color:#e51c24; text-transform:uppercase; letter-spacing:1px; display:block; margin-bottom:8px;">AI-Driven Purchasing</span>
                    <h3>Intelligent Replenishment</h3>
                    <p>Say goodbye to manual spreadsheets. MegazenTrack constantly monitors stock ratios, calculates reorder points, and automatically prepares purchase orders for your review to ensure you never run out of core products.</p>
                    <ul class="feature-list">
                        <li><i class="fa-solid fa-circle-check" style="color:#2c8a4c; margin-right:8px;"></i> Automated safety stock calibrations.</li>
                        <li><i class="fa-solid fa-circle-check" style="color:#2c8a4c; margin-right:8px;"></i> Draft purchase order recommendations.</li>
                        <li><i class="fa-solid fa-circle-check" style="color:#2c8a4c; margin-right:8px;"></i> Forecasting graphs and depletion cycles.</li>
                    </ul>
                    <a href="signup.php" class="landing-btn-tour">Explore Replenishment</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ── 2. D. The Pricing & Value Proposition Block ── -->
    <section class="section section-white" id="pricing">
        <div class="section-container">
            <div class="pricing-block">
                <div class="pricing-details">
                    <h3>Flexible Licensing Built for Your Growth</h3>
                    <p>MegazenTrack is licensed through a modular annual subscription. You only pay for what you use, starting with the core platform and adding specialized inventory features as your logistics scale.</p>
                    <div class="pricing-features">
                        <div class="pricing-feature-item"><i class="fa-solid fa-circle-check"></i> Core ERP Platform</div>
                        <div class="pricing-feature-item"><i class="fa-solid fa-circle-check"></i> Unlimited Warehouses</div>
                        <div class="pricing-feature-item"><i class="fa-solid fa-circle-check"></i> Advanced Inventory Module</div>
                        <div class="pricing-feature-item"><i class="fa-solid fa-circle-check"></i> Smart AI Replenishment</div>
                        <div class="pricing-feature-item"><i class="fa-solid fa-circle-check"></i> Multi-Channel Sales Orders</div>
                        <div class="pricing-feature-item"><i class="fa-solid fa-circle-check"></i> 24/7 Premium Enterprise Support</div>
                    </div>
                </div>
                <div class="pricing-action">
                    <div class="pricing-icon"><i class="fa-solid fa-handshake-simple"></i></div>
                    <h4>Request Customized Quote</h4>
                    <p>Consult with our solutions engineers to build an annual license mapped precisely to your business size.</p>
                    <a href="tel:1-877-638-7848" class="landing-btn-tour" style="display:block; margin-bottom:12px; text-align:center;">Contact MegazenTrack Now</a>
                    <span style="font-size:10px; color:#777; display:block;">Call local phone: 1-877-638-7848</span>
                </div>
            </div>
        </div>
    </section>

    <!-- ── 2. E. Structured Accordion FAQ (Q&A) ── -->
    <section class="section section-grey" id="faq">
        <div class="section-container">
            <div class="section-header">
                <h2>Frequently Answered Queries</h2>
                <p>Have questions about integrating MegazenTrack inventory software? We have compiled technical answers for your review.</p>
            </div>
            <div class="faq-accordion">
                <!-- FAQ 1 -->
                <div class="faq-item">
                    <div class="faq-question">
                        What is MegazenTrack Inventory Management?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        MegazenTrack Inventory Management is a cloud-based software solution that provides real-time visibility into stock levels, multi-warehouse logistics, customer demands, and supplier order cycles. It consolidates purchase orders, sales transactions, and physical logistics into a unified cloud system.
                    </div>
                </div>
                <!-- FAQ 2 -->
                <div class="faq-item">
                    <div class="faq-question">
                        How does the dynamic AI-driven replenishment system work?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        MegazenTrack constantly calculates sales velocities, lead times, and current stock levels. By leveraging machine learning models, it automatically predicts when products will drop below safety thresholds, generating draft purchase order recommendations and notifying purchasing agents instantly to prevent backorders.
                    </div>
                </div>
                <!-- FAQ 3 -->
                <div class="faq-item">
                    <div class="faq-question">
                        Can the platform handle multiple warehouses and log inventory transfers?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Yes, MegazenTrack supports distributed warehouse layouts. You can track exact inventory locations, initiate and authorize secure stock movement transfer requests, and maintain audit trails of products moving across borders or supply points.
                    </div>
                </div>
                <!-- FAQ 4 -->
                <div class="faq-item">
                    <div class="faq-question">
                        Is my data isolated and secure under your multi-tenant structure?
                        <i class="fa-solid fa-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Absolutely. MegazenTrack operates under an enterprise-grade multi-tenant architecture. Strict database keys isolate company data, enforcing secure user permission tiers (Admin, Manager, Staff, Customer) and CSRF token locks to keep your operational data locked and secure.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ── 3. Micro-Interactions & Overlays ── -->
    <!-- Persistent Chat Widget -->
    <div class="sales-chat-widget">
        <button class="chat-trigger" id="chatTrigger">
            <i class="fa-regular fa-comments"></i> MegazenTrack Sales Chatbot
        </button>
        <div class="chat-window" id="chatWindow">
            <div class="chat-header">
                <span>MegazenTrack Assistant</span>
                <span class="chat-close" id="chatClose"><i class="fa-solid fa-times"></i></span>
            </div>
            <div class="chat-body" id="chatBody">
                <div class="chat-msg bot">Hello! I am your MegazenTrack Sales Assistant. Are you interested in taking a Free Product Tour of our inventory systems?</div>
            </div>
            <div class="chat-footer">
                <input type="text" placeholder="Type a message..." id="chatInput">
                <button id="chatSend">Send</button>
            </div>
        </div>
    </div>

    <!-- Exit-Intent / Scroll Lead Capture Slide-out Modal -->
    <div class="exit-intent-card" id="exitIntentCard">
        <span class="exit-close" id="exitClose"><i class="fa-solid fa-times"></i></span>
        <span class="exit-tag">Free Resource</span>
        <h4>Before you go...</h4>
        <p>Get our exclusive executive guide: <strong>"7 Strategies to Streamline Warehouse Logistics and Slash Carry Costs."</strong></p>
        <form id="exitForm" onsubmit="handleExitSubmit(event)">
            <input type="email" placeholder="Enter your business email" required>
            <button type="submit">Get My Free Guide</button>
        </form>
    </div>

    <!-- ── 4. Footer Ecosystem ── -->
    <footer class="landing-footer" id="resources">
        <div class="footer-container">
            <div class="footer-grid">
                <div class="footer-brand">
                    <h4>MegazenTrack</h4>
                    <p>The global leader in ERP and cloud-based inventory operations, powering over 40,000 corporate groups globally.</p>
                </div>
                <div class="footer-col">
                    <h5>Product</h5>
                    <ul class="footer-links">
                        <li><a href="#about">Overview</a></li>
                        <li><a href="#benefits">Benefits</a></li>
                        <li><a href="#features">Features</a></li>
                        <li><a href="#pricing">Pricing</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h5>Company</h5>
                    <ul class="footer-links">
                        <li><a href="#resources">Resources</a></li>
                        <li><a href="#faq">Q&A</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact</a></li>
                    </ul>
                </div>
                <div class="footer-col">
                    <h5>Regulatory</h5>
                    <ul class="footer-links">
                        <li><a href="#">Terms of Service</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                        <li><a href="#">Global Compliance</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?= date('Y') ?> MegazenTrack. All rights reserved. StockMaster IMS integrated MegazenTrack replica module.</p>
                <div class="footer-legal-links">
                    <a href="#">Terms of Service</a>
                    <a href="#">Privacy Policy</a>
                    <a href="#">Cookies & Ads</a>
                    <a href="#">United States (English)</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // ── FAQ Accordion Toggles ──
        const faqItems = document.querySelectorAll('.faq-item');
        faqItems.forEach(item => {
            const question = item.querySelector('.faq-question');
            question.addEventListener('click', () => {
                const isActive = item.classList.contains('active');
                faqItems.forEach(i => i.classList.remove('active'));
                if (!isActive) {
                    item.classList.add('active');
                }
            });
        });

        // ── Persistent Chatbot Trigger & Script ──
        const chatTrigger = document.getElementById('chatTrigger');
        const chatWindow = document.getElementById('chatWindow');
        const chatClose = document.getElementById('chatClose');
        const chatInput = document.getElementById('chatInput');
        const chatSend = document.getElementById('chatSend');
        const chatBody = document.getElementById('chatBody');

        chatTrigger.addEventListener('click', () => {
            chatWindow.style.display = chatWindow.style.display === 'flex' ? 'none' : 'flex';
        });

        chatClose.addEventListener('click', (e) => {
            e.stopPropagation();
            chatWindow.style.display = 'none';
        });

        chatSend.addEventListener('click', sendChatMessage);
        chatInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter') sendChatMessage();
        });

        function sendChatMessage() {
            const text = chatInput.value.trim();
            if (!text) return;

            // Render user message
            const userMsg = document.createElement('div');
            userMsg.className = 'chat-msg user';
            userMsg.textContent = text;
            chatBody.appendChild(userMsg);
            chatInput.value = '';

            chatBody.scrollTop = chatBody.scrollHeight;

            // Delayed bot response
            setTimeout(() => {
                const botMsg = document.createElement('div');
                botMsg.className = 'chat-msg bot';
                botMsg.textContent = "Thank you for reaching out! A MegazenTrack logistics engineer has been paged and will follow up with you. Or call us directly at 1-877-638-7848.";

                chatBody.appendChild(botMsg);
                chatBody.scrollTop = chatBody.scrollHeight;
            }, 800);
        }

        // ── Exit-Intent / Scroll Lead Capture Modal ──
        const exitCard = document.getElementById('exitIntentCard');
        const exitClose = document.getElementById('exitClose');
        let exitShown = false;

        // Show after scrolling 500px down (lead capture behavior)
        window.addEventListener('scroll', () => {
            if (window.scrollY > 400 && !exitShown) {
                exitShown = true;
                exitCard.classList.add('active');
            }
        });

        // Closing slide-out
        exitClose.addEventListener('click', () => {
            exitCard.classList.remove('active');
        });

        function handleExitSubmit(e) {
            e.preventDefault();
            const form = document.getElementById('exitForm');
            form.innerHTML = `<div style="font-size:12px; font-weight:700; color:#2c8a4c; text-align:center; padding:10px 0;"><i class="fa-solid fa-circle-check" style="margin-right:6px;"></i>Success! We have sent the warehouse guide to your email. Check your inbox!</div>`;
            setTimeout(() => {
                exitCard.classList.remove('active');
            }, 3000);
        }

        // ── Smooth Scroll & Active Anchor Highlights ──
        const anchors = document.querySelectorAll('.anchor-link');
        const sections = document.querySelectorAll('section');

        window.addEventListener('scroll', () => {
            let current = "";
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                if (window.scrollY >= sectionTop - 120) {
                    current = section.getAttribute('id');
                }
            });

            anchors.forEach(anchor => {
                anchor.classList.remove('active');
                if (anchor.getAttribute('href') === `#${current}`) {
                    anchor.classList.add('active');
                }
            });
        });
    </script>
</body>
</html>
