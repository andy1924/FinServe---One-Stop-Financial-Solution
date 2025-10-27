<?php
session_start();

// Auth Guard
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

$username = htmlspecialchars($_SESSION['username']);
?>
<!DOCTYPE html>
<html lang="en" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FinServe Dashboard</title>
    <link rel="icon" type="image/png" href="favicon.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8fafc; /* slate-50 */
            transition: background-color 0.3s, color 0.3s;
        }

        .page {
            display: none;
            animation: fadeIn 0.5s;
        }
        .page.active {
            display: block;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background-color: #1d4ed8; border-radius: 10px; }
        ::-webkit-scrollbar-track { background-color: #f1f5f9; }

        /* Premium Navbar Active State */
        .nav-link.active-nav {
            color: #1d4ed8; /* blue-700 */
            border-bottom: 2px solid #1d4ed8;
        }
        
        /* Module Accordion Styles */
        .module-content {
            display: grid;
            grid-template-rows: 0fr;
            overflow: hidden;
            transition: grid-template-rows 0.3s ease-in-out;
        }
        .module-content.active {
            grid-template-rows: 1fr;
        }
        .module-content-inner {
            min-height: 0;
        }

        /* Portfolio Tab Styles */
        .portfolio-tab {
            @apply px-4 py-2 font-medium text-slate-600 rounded-md transition-colors;
        }
        .portfolio-tab.active-tab {
            @apply bg-blue-700 text-white shadow-md;
        }

        /* Profit/Loss Colors */
        .text-profit {
            color: #16a34a; /* green-600 */
        }
        .text-loss {
            color: #dc2626; /* red-600 */
        }
    </style>
</head>
<body class="text-slate-800">

    <header class="bg-white shadow-sm sticky top-0 z-50 border-b border-slate-200">
        <nav class="container mx-auto px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center">
            
            <div class="flex items-center space-x-8">
                <a href="#" data-page="home" class="nav-link text-2xl font-bold text-blue-700">
                    FinServe
                </a>

                <div class="hidden md:flex items-center space-x-2">
                    <a href="#" data-page="home" class="nav-link active-nav px-3 py-2 text-sm font-semibold text-slate-700 hover:text-blue-700">Home</a>
                    <a href="#" data-page="portfolio" class="nav-link px-3 py-2 text-sm font-semibold text-slate-700 hover:text-blue-700">Portfolio</a>
                    <a href="#" data-page="research" class="nav-link px-3 py-2 text-sm font-semibold text-slate-700 hover:text-blue-700">Research Picks</a>
                    <a href="#" data-page="modules" class="nav-link px-3 py-2 text-sm font-semibold text-slate-700 hover:text-blue-700">Modules</a>
                    <a href="#" data-page="calculators" class="nav-link px-3 py-2 text-sm font-semibold text-slate-700 hover:text-blue-700">Calculators</a>
                    <a href="#" data-page="services" class="nav-link px-3 py-2 text-sm font-semibold text-slate-700 hover:text-blue-700">Services</a>
                    <a href="#" data-page="updates" class="nav-link px-3 py-2 text-sm font-semibold text-slate-700 hover:text-blue-700">Updates</a>
                </div>
            </div>

            <div class="flex items-center space-x-2 sm:space-x-4">
                <div class="relative hidden md:block">
                    <button id="user-menu-button" class="flex items-center space-x-2 text-sm font-medium text-slate-700 hover:text-blue-700">
                        <i data-lucide="user-circle-2" class="w-7 h-7"></i>
                        <span>Hi, <?php echo $username; ?></span>
                        <i data-lucide="chevron-down" class="w-4 h-4"></i>
                    </button>
                    <div id="user-menu-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-xl z-50 border border-slate-100 py-1">
                        <a href="#" class="block px-4 py-2 text-sm text-slate-700 hover:bg-slate-100">Profile (coming soon)</a>
                        <a href="logout.php" class="block px-4 py-2 text-sm text-red-600 hover:bg-slate-100">Logout</a>
                    </div>
                </div>

                <div class="md:hidden">
                    <button id="mobile-menu-button" class="text-slate-700 hover:text-blue-700">
                        <i data-lucide="menu" class="w-6 h-6"></i>
                    </button>
                </div>
            </div>
        </nav>

        <div id="mobile-menu" class="hidden md:hidden bg-white shadow-lg border-t border-slate-200">
            <div id="nav-links-mobile" class="flex flex-col px-4 py-3 space-y-3">
                <a href="#" data-page="home" class="nav-link mobile-link block px-3 py-2 rounded-md text-base font-medium text-slate-600 hover:bg-blue-50 hover:text-blue-700">Home</a>
                <a href="#" data-page="portfolio" class="nav-link mobile-link block px-3 py-2 rounded-md text-base font-medium text-slate-600 hover:bg-blue-50 hover:text-blue-700">Portfolio</a>
                <a href="#" data-page="research" class="nav-link mobile-link block px-3 py-2 rounded-md text-base font-medium text-slate-600 hover:bg-blue-50 hover:text-blue-700">Research Picks</a>
                <a href="#" data-page="modules" class="nav-link mobile-link block px-3 py-2 rounded-md text-base font-medium text-slate-600 hover:bg-blue-50 hover:text-blue-700">Modules</a>
                <a href="#" data-page="calculators" class="nav-link mobile-link block px-3 py-2 rounded-md text-base font-medium text-slate-600 hover:bg-blue-50 hover:text-blue-700">Calculators</a>
                <a href="#" data-page="services" class="nav-link mobile-link block px-3 py-2 rounded-md text-base font-medium text-slate-600 hover:bg-blue-50 hover:text-blue-700">Services</a>
                <a href="#" data-page="updates" class="nav-link mobile-link block px-3 py-2 rounded-md text-base font-medium text-slate-600 hover:bg-blue-50 hover:text-blue-700">Updates</a>
                <a href="#" data-page="contact" class="nav-link mobile-link block px-3 py-2 rounded-md text-base font-medium text-slate-600 hover:bg-blue-50 hover:text-blue-700">Contact</a>
            </div>

            <div id="user-links-mobile" class="border-t border-slate-200 px-4 py-4 space-y-3">
                 <span class="block px-3 py-2 text-base font-medium text-slate-700">
                  Hi, <?php echo $username; ?>
                 </span>
                 <a href="#" class="block w-full text-left px-3 py-2 text-base font-medium text-slate-600 hover:bg-slate-100 rounded-md">Profile</a>
                <a href="logout.php" id="logout-mobile" class="block w-full text-center px-4 py-2 text-base font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 shadow-sm transition-colors">
                    Logout
                </a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-4 sm:px-6 lg:px-8 py-8 md:py-12 min-h-[calc(100vh-200px)]">

        <section id="page-home" class="page active">
            <div class="relative bg-gradient-to-r from-blue-700 to-blue-900 text-white rounded-xl shadow-2xl overflow-hidden p-8 md:p-16">
                <div class="absolute -bottom-16 -right-16 w-64 h-64 bg-white opacity-10 rounded-full"></div>
                <div class="absolute -top-10 -left-10 w-48 h-48 bg-white opacity-10 rounded-full"></div>
                
                <div class="relative z-10">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Welcome, <?php echo $username; ?>!</h1>
                    <p class="text-lg md:text-xl text-blue-100 mb-8 max-w-2xl">
                        Your complete financial dashboard. Track, learn, and invest, all in one place.
                    </p>
                    <div class="flex flex-wrap gap-4">
                        <a href="#" data-page="portfolio" class="nav-link px-6 py-3 bg-white text-blue-800 font-semibold rounded-lg shadow-md hover:bg-blue-50 transition-all">
                            View Portfolio
                        </a>
                        <a href="#" data-page="modules" class="nav-link px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-500 transition-all">
                            Start Learning
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid md:grid-cols-3 gap-8 mt-12">
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200 hover:shadow-xl transition-shadow">
                    <i data-lucide="pie-chart" class="w-12 h-12 text-blue-700 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Track Your Portfolio</h3>
                    <p class="text-slate-600 mb-4">
                        See your stock and mutual fund investments with live P&L and interactive charts.
                    </p>
                    <a href="#" data-page="portfolio" class="nav-link font-medium text-blue-700 hover:text-blue-900">
                        Go to Portfolio &rarr;
                    </a>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200 hover:shadow-xl transition-shadow">
                    <i data-lucide="lightbulb" class="w-12 h-12 text-blue-700 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Research Picks</h3>
                    <p class="text-slate-600 mb-4">
                        Explore curated stock and fund ideas based on fundamental and technical analysis.
                    </p>
                    <a href="#" data-page="research" class="nav-link font-medium text-blue-700 hover:text-blue-900">
                        View Picks &rarr;
                    </a>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200 hover:shadow-xl transition-shadow">
                    <i data-lucide="book-open" class="w-12 h-12 text-blue-700 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Learning Modules</h3>
                    <p class="text-slate-600 mb-4">
                         Learn about stock markets, analysis, and options with our guided modules.
                    </p>
                    <a href="#" data-page="modules" class="nav-link font-medium text-blue-700 hover:text-blue-900">
                        Start Learning &rarr;
                    </a>
                </div>
            </div>
        </section>

        <section id="page-portfolio" class="page">
            <h2 class="text-3xl font-bold mb-6">Your Portfolio</h2>
            <p class="text-sm text-slate-500 mb-6 -mt-4">(Note: This is sample data for demonstration purposes.)</p>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200">
                    <h4 class="text-sm font-medium text-slate-500">Total Investment</h4>
                    <p class="text-3xl font-semibold text-slate-800 mt-1">₹1,50,000.00</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200">
                    <h4 class="text-sm font-medium text-slate-500">Current Value</h4>
                    <p class="text-3xl font-semibold text-slate-800 mt-1">₹1,72,500.00</p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200">
                    <h4 class="text-sm font-medium text-slate-500">Total P&L</h4>
                    <p class="text-3xl font-semibold text-profit mt-1">+₹22,500.00 (+15.00%)</p>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200 mb-8">
                <div class="flex flex-col sm:flex-row justify-between sm:items-center">
                    <h3 class="text-xl font-semibold mb-4 sm:mb-0">Portfolio Overview</h3>
                    <button id="show-pie-chart-btn" class="flex items-center justify-center space-x-2 px-4 py-2 text-sm font-medium text-blue-700 bg-blue-50 rounded-lg hover:bg-blue-100">
                        <i data-lucide="pie-chart" class="w-4 h-4"></i>
                        <span>Show Diversification</span>
                    </button>
                </div>
                <div id="pie-chart-container" class="hidden mt-6" style="max-height: 350px; position: relative; margin: auto;">
                    <canvas id="portfolioPieChart"></canvas>
                </div>
            </div>

            <div class="mb-4">
                <div class="bg-white p-2 rounded-lg shadow-md inline-flex space-x-2 border border-slate-200">
                    <button id="show-stocks" class="portfolio-tab active-tab">Stocks (NSE/BSE)</button>
                    <button id="show-mfs" class="portfolio-tab">Mutual Funds</button>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-lg border border-slate-200 overflow-hidden">
                <div id="stocks-holdings">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr class="text-sm font-semibold text-slate-600">
                                <th class="p-4">Instrument</th>
                                <th class="p-4 hidden md:table-cell">Qty.</th>
                                <th class="p-4">Avg. Price</th>
                                <th class="p-4 hidden md:table-cell">CMP</th>
                                <th class="p-4">P&L</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-slate-100">
                                <td class="p-4 font-medium">RELIANCE.NS</td>
                                <td class="p-4 hidden md:table-cell text-slate-600">10</td>
                                <td class="p-4 text-slate-600">₹2,800.00</td>
                                <td class="p-4 hidden md:table-cell text-slate-600">₹2,950.00</td>
                                <td class="p-4 font-medium text-profit">+₹1,500.00 (+5.36%)</td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="p-4 font-medium">HDFCBANK.NS</td>
                                <td class="p-4 hidden md:table-cell text-slate-600">25</td>
                                <td class="p-4 text-slate-600">₹1,550.00</td>
                                <td class="p-4 hidden md:table-cell text-slate-600">₹1,500.00</td>
                                <td class="p-4 font-medium text-loss">-₹1,250.00 (-3.23%)</td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="p-4 font-medium">TCS.NS</td>
                                <td class="p-4 hidden md:table-cell text-slate-600">15</td>
                                <td class="p-4 text-slate-600">₹3,400.00</td>
                                <td class="p-4 hidden md:table-cell text-slate-600">₹3,900.00</td>
                                <td class="p-4 font-medium text-profit">+₹7,500.00 (+14.71%)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="mf-holdings" class="hidden">
                    <table class="w-full text-left">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr class="text-sm font-semibold text-slate-600">
                                <th class="p-4">Fund Name</th>
                                <th class="p-4 hidden md:table-cell">Invested</th>
                                <th class="p-4">Current Value</th>
                                <th class="p-4">P&L</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr class="border-b border-slate-100">
                                <td class="p-4 font-medium">Parag Parikh Flexi Cap Fund</td>
                                <td class="p-4 hidden md:table-cell text-slate-600">₹25,000.00</td>
                                <td class="p-4 text-slate-600">₹32,000.00</td>
                                <td class="p-4 font-medium text-profit">+₹7,000.00 (+28.00%)</td>
                            </tr>
                            <tr class="border-b border-slate-100">
                                <td class="p-4 font-medium">Quant Small Cap Fund</td>
                                <td class="p-4 hidden md:table-cell text-slate-600">₹35,250.00</td>
                                <td class="p-4 text-slate-600">₹43,000.00</td>
                                <td class="p-4 font-medium text-profit">+₹7,750.00 (+22.00%)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="page-research" class="page">
            <h2 class="text-3xl font-bold text-center mb-8">Research Picks</h2>
            
            <div class="mb-8 p-4 bg-yellow-50 border border-yellow-200 text-yellow-800 rounded-lg text-sm">
                <strong>Disclaimer:</strong> The information provided is for educational purposes only and does not constitute financial advice. All investments are subject to market risks. Please consult your financial advisor before investing.
            </div>

            <div class="grid md:grid-cols-2 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200 space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-semibold text-blue-800">Larsen & Toubro (L&T)</h3>
                        <span class="text-sm font-medium bg-blue-100 text-blue-700 px-3 py-1 rounded-full">Stock Pick</span>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-1">Fundamental View:</h4>
                        <p class="text-slate-600 text-sm">
                            Strong order book, diverse revenue streams across infrastructure and defense, and a healthy balance sheet. Positioned well to benefit from the government's capex push.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-1">Technical View:</h4>
                        <p class="text-slate-600 text-sm">
                            The stock is showing a strong breakout from a multi-month consolidation pattern. Support is seen near the 50-day moving average, with potential upside targets in the medium term.
                        </p>
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200 space-y-4">
                    <div class="flex justify-between items-center">
                        <h3 class="text-2xl font-semibold text-green-800">Mirae Asset Nifty 50 ETF</h3>
                        <span class="text-sm font-medium bg-green-100 text-green-700 px-3 py-1 rounded-full">Fund Pick</span>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-1">Fundamental View:</h4>
                        <p class="text-slate-600 text-sm">
                            A low-cost Exchange Traded Fund (ETF) that passively tracks the Nifty 50 index. Ideal for beginners or as a core holding for long-term, diversified exposure to India's top 50 companies.
                        </p>
                    </div>
                    <div>
                        <h4 class="font-semibold mb-1">Strategy:</h4>
                        <p class="text-slate-600 text-sm">
                            We recommend accumulating this ETF via a Systematic Investment Plan (SIP) to average out costs and benefit from long-term compounding.
                        </p>
                    </div>
                </div>
            </div>
        </section>


        <section id="page-about" class="page max-w-4xl mx-auto">
             <h2 class="text-3xl font-bold text-center mb-8">About FinServe</h2>
            <div class="bg-white p-8 rounded-xl shadow-lg border border-slate-200 space-y-6">
                <p class="text-lg text-slate-700">
                    Welcome to FinServe, your dedicated partner in navigating the complexities of the financial world. Our mission is to empower individuals and businesses with the knowledge, tools, and expert guidance needed to achieve financial independence and success.
                </p>
                <p class="text-slate-600">
                    Our team is composed of seasoned financial analysts, certified planners, and tech innovators who are passionate about making finance accessible to everyone. We believe in transparency, integrity, and a client-first approach.
                </p>
                <p class="text-slate-600 font-medium border-t border-slate-200 pt-6 mt-6">
                    This is a mini project for Fundamentals of Web Development (FWD) by Arnav Deshpande(C035), Aditya Kulkarni(C034) and Shaad Naaqi(C036).
                </p>
            </div>
        </section>

        <section id="page-services" class="page">
            <h2 class="text-3xl font-bold text-center mb-8">Our Services</h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200">
                    <i data-lucide="briefcase" class="w-12 h-12 text-blue-700 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Investment Planning</h3>
                    <p class="text-slate-600">
                        Tailored investment strategies to help you grow your wealth.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200">
                    <i data-lucide="landmark" class="w-12 h-12 text-blue-700 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Retirement Planning</h3>
                    <p class="text-slate-600">
                        Secure your golden years with our comprehensive retirement planning.
                    </p>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200">
                    <i data-lucide="piggy-bank" class="w-12 h-12 text-blue-700 mb-4"></i>
                    <h3 class="text-xl font-semibold mb-2">Loan Advisory</h3>
                    <p class="text-slate-600">
                        From home loans to personal loans, we help you find the best rates.
                    </p>
                </div>
            </div>
        </section>

        <section id="page-calculators" class="page">
            <h2 class="text-3xl font-bold text-center mb-8">Financial Calculators</h2>
            <div class="grid lg:grid-cols-2 gap-8">
                
                <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-slate-200">
                    <h3 class="text-2xl font-semibold mb-6 text-blue-800">EMI Calculator</h3>
                    <form id="emi-form" class="space-y-4">
                        <div>
                            <label for="principal" class="block text-sm font-medium text-slate-700 mb-1">Loan Amount (₹)</label>
                            <input type="number" id="principal" name="principal" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., 1000000" required>
                        </div>
                        <div>
                            <label for="interest" class="block text-sm font-medium text-slate-700 mb-1">Annual Interest Rate (%)</label>
                            <input type="number" id="interest" name="interest" step="0.01" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., 8.5" required>
                        </div>
                        <div>
                            <label for="tenure" class="block text-sm font-medium text-slate-700 mb-1">Loan Tenure (Years)</label>
                            <input type="number" id="tenure" name="tenure" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., 20" required>
                        </div>
                        <button type="submit" class="w-full px-6 py-3 bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:bg-blue-800 transition-colors">
                            Calculate EMI
                        </button>
                    </form>
                    <div id="emi-result" class="mt-6 text-center hidden p-6 bg-blue-50 rounded-lg border border-blue-200">
                        <h4 class="text-lg font-medium text-slate-700">Your Monthly EMI</h4>
                        <p id="emi-value" class="text-3xl font-bold text-blue-800 my-2"></p>
                        <div class="text-sm text-slate-600">
                            <p>Total Principal: <span id="emi-principal" class="font-medium"></span></p>
                            <p>Total Interest: <span id="emi-interest" class="font-medium"></span></p>
                            <p>Total Payment: <span id="emi-total" class="font-medium"></span></p>
                        </div>
                    </div>
                </div>

                <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-slate-200">
                    <h3 class="text-2xl font-semibold mb-6 text-green-800">SIP Calculator</h3>
                    <form id="sip-form" class="space-y-4">
                        <div>
                            <label for="sip-amount" class="block text-sm font-medium text-slate-700 mb-1">Monthly Investment (₹)</label>
                            <input type="number" id="sip-amount" name="sip-amount" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="e.g., 5000" required>
                        </div>
                        <div>
                            <label for="sip-rate" class="block text-sm font-medium text-slate-700 mb-1">Expected Annual Return (%)</label>
                            <input type="number" id="sip-rate" name="sip-rate" step="0.01" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="e.g., 12" required>
                        </div>
                        <div>
                            <label for="sip-years" class="block text-sm font-medium text-slate-700 mb-1">Investment Period (Years)</label>
                            <input type="number" id="sip-years" name="sip-years" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-green-500" placeholder="e.g., 10" required>
                        </div>
                        <button type="submit" class="w-full px-6 py-3 bg-green-700 text-white font-semibold rounded-lg shadow-md hover:bg-green-800 transition-colors">
                            Calculate SIP
                        </button>
                    </form>
                    <div id="sip-result" class="mt-6 text-center hidden p-6 bg-green-50 rounded-lg border border-green-200">
                        <h4 class="text-lg font-medium text-slate-700">Your Estimated Returns</h4>
                        <p id="sip-value" class="text-3xl font-bold text-green-800 my-2"></p>
                        <div class="text-sm text-slate-600">
                            <p>Invested Amount: <span id="sip-invested" class="font-medium"></span></p>
                            <p>Est. Returns: <span id="sip-returns" class="font-medium"></span></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="page-modules" class="page">
            <h2 class="text-3xl font-bold text-center mb-8">Learning Modules</h2>
            
            <div class="grid md:grid-cols-2 gap-x-8 gap-y-6 max-w-5xl mx-auto">
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200">
                    <button class="module-trigger w-full text-left">
                        <div class="flex justify-between items-center">
                            <span class="text-4xl font-bold text-slate-300">1</span>
                            <i data-lucide="chevron-down" class="module-icon transition-transform duration-300"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-blue-800 mt-2 border-b-4 border-blue-500 pb-2">Introduction to Stock Markets</h3>
                        <span class="text-sm text-slate-500">15 chapters</span>
                    </button>
                    <div class="module-content">
                        <div class="module-content-inner pt-4 mt-4 border-t border-slate-200">
                            <p class="text-slate-600">
                                The stock market can play a pivotal role in ensuring your financial security. In this module, you will learn how to get started in the stock market, its fundamentals, how it functions, and the various intermediaries that appertain it.
                            </p>
                            <div class="flex space-x-4 mt-4 text-sm font-medium">
                                <a href="#" class="text-blue-700 hover:underline">View module</a>
                                <a href="#" class="text-blue-700 hover:underline">Watch videos</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200">
                    <button class="module-trigger w-full text-left">
                        <div class="flex justify-between items-center">
                            <span class="text-4xl font-bold text-slate-300">2</span>
                            <i data-lucide="chevron-down" class="module-icon transition-transform duration-300"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-sky-800 mt-2 border-b-4 border-sky-500 pb-2">Technical Analysis</h3>
                        <span class="text-sm text-slate-500">22 chapters</span>
                    </button>
                    <div class="module-content">
                        <div class="module-content-inner pt-4 mt-4 border-t border-slate-200">
                            <p class="text-slate-600">
                                Technical Analysis (TA) helps in developing a point of view. In this module, we will discover the complex attributes, various patterns, indicators, and theories of TA that will help you as a trader to find upright trading opportunities in the market.
                            </p>
                            <div class="flex space-x-4 mt-4 text-sm font-medium">
                                <a href="#" class="text-blue-700 hover:underline">View module</a>
                                <a href="#" class="text-blue-700 hover:underline">Watch videos</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200">
                    <button class="module-trigger w-full text-left">
                        <div class="flex justify-between items-center">
                            <span class="text-4xl font-bold text-slate-300">3</span>
                            <i data-lucide="chevron-down" class="module-icon transition-transform duration-300"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-yellow-800 mt-2 border-b-4 border-yellow-500 pb-2">Fundamental Analysis</h3>
                        <span class="text-sm text-slate-500">16 chapters</span>
                    </button>
                    <div class="module-content">
                        <div class="module-content-inner pt-4 mt-4 border-t border-slate-200">
                            <p class="text-slate-600">
                                The Fundamental Analysis (FA) module explores Equity research by reading financial statements and annual reports, calculating and analyzing Financial Ratios, and evaluating the intrinsic value of a stock to find long-term investing opportunities.
                            </p>
                            <div class="flex space-x-4 mt-4 text-sm font-medium">
                                <a href="#" class="text-blue-700 hover:underline">View module</a>
                                <a href="#" class="text-blue-700 hover:underline">Watch videos</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200">
                    <button class="module-trigger w-full text-left">
                        <div class="flex justify-between items-center">
                            <span class="text-4xl font-bold text-slate-300">4</span>
                            <i data-lucide="chevron-down" class="module-icon transition-transform duration-300"></i>
                        </div>
                        <h3 class="text-xl font-semibold text-pink-800 mt-2 border-b-4 border-pink-500 pb-2">Futures Trading</h3>
                        <span class="text-sm text-slate-500">13 chapters</span>
                    </button>
                    <div class="module-content">
                        <div class="module-content-inner pt-4 mt-4 border-t border-slate-200">
                            <p class="text-slate-600">
                                Learn the basics of futures trading, including margin requirements, settlement processes, and strategies for hedging and speculation in the derivatives market.
                            </p>
                            <div class="flex space-x-4 mt-4 text-sm font-medium">
                                <a href="#" class="text-blue-700 hover:underline">View module</a>
                                <a href="#" class="text-blue-700 hover:underline">Watch videos</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="page-updates" class="page">
            <h2 class="text-3xl font-bold text-center mb-8">Latest Financial Updates</h2>
            <div class="space-y-6">
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200 flex flex-col md:flex-row gap-6">
                    <img src="https://placehold.co/300x200/e0e7ff/3730a3?text=Market+News" alt="Market News" class="w-full md:w-1/3 h-48 md:h-auto object-cover rounded-lg">
                    <div class="md:w-2/3">
                        <span class="text-sm text-blue-700 font-medium bg-blue-100 px-3 py-1 rounded-full">Market News</span>
                        <h3 class="text-2xl font-semibold my-2">Global Markets Show Mixed Signals Amid Tech Rally</h3>
                        <p class="text-sm text-slate-500 mb-3">Oct 24, 2025 by Admin</p>
                        <p class="text-slate-600 mb-4">
                            Stock markets across the globe presented a mixed picture today...
                        </p>
                        <a href="#" class="font-medium text-blue-700 hover:text-blue-900">Read More &rarr;</a>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-lg border border-slate-200 flex flex-col md:flex-row gap-6">
                    <img src="https://placehold.co/300x200/d1fae5/047857?text=Economy" alt="Economy" class="w-full md:w-1/3 h-48 md:h-auto object-cover rounded-lg">
                    <div class="md:w-2/3">
                        <span class="text-sm text-green-700 font-medium bg-green-100 px-3 py-1 rounded-full">Economy</span>
                        <h3 class="text-2xl font-semibold my-2">Central Bank Holds Interest Rates Steady</h3>
                        <p class="text-sm text-slate-500 mb-3">Oct 23, 2025 by Admin</p>
                        <p class="text-slate-600 mb-4">
                            In a widely anticipated move, the central bank announced it would maintain the current interest rate...
                        </p>
                        <a href="#" class="font-medium text-blue-700 hover:text-blue-900">Read More &rarr;</a>
                    </div>
                </div>
            </div>
        </section>

        <section id="page-contact" class="page">
            <h2 class="text-3xl font-bold text-center mb-8">Get In Touch</h2>
            <div class="grid lg:grid-cols-2 gap-8">
                <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-slate-200">
                    <h3 class="text-2xl font-semibold mb-6 text-blue-800">Send Us a Message</h3>
                    <form id="contact-form" class="space-y-4">
                        <div>
                            <label for="contact-name" class="block text-sm font-medium text-slate-700 mb-1">Full Name</label>
                            <input type="text" id="contact-name" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label for="contact-email" class="block text-sm font-medium text-slate-700 mb-1">Email Address</label>
                            <input type="email" id="contact-email" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                        </div>
                        <div>
                            <label for="contact-message" class="block text-sm font-medium text-slate-700 mb-1">Message</label>
                            <textarea id="contact-message" rows="5" class="w-full px-4 py-2 border border-slate-300 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500" required></textarea>
                        </div>
                        <button type="submit" class="w-full px-6 py-3 bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:bg-blue-800 transition-colors">
                            Submit Message
                        </button>
                    </form>
                    <div id="contact-success" class="hidden mt-4 p-4 text-center bg-green-100 text-green-800 border border-green-200 rounded-lg">
                        Thank you! Your message has been sent.
                    </div>
                </div>
                <div class="bg-white p-6 md:p-8 rounded-xl shadow-lg border border-slate-200 space-y-6">
                    <h3 class="text-2xl font-semibold mb-6 text-blue-800">Contact Information</h3>
                    <div class="flex items-start space-x-4">
                        <i data-lucide="map-pin" class="w-6 h-6 text-blue-700 mt-1"></i>
                        <div>
                            <h4 class="text-lg font-semibold">Our Office</h4>
                            <p class="text-slate-600">123 Finance St, Money-City, 10001</p>
                        </div>
                    </div>
                    <div class="flex items-start space-x-4">
                        <i data-lucide="mail" class="w-6 h-6 text-blue-700 mt-1"></i>
                        <div>
                            <h4 class="text-lg font-semibold">Email Us</h4>
                            <p class="text-slate-600">contact@finserve.com</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="page-privacy" class="page max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-8">Privacy Policy</h2>
            <div class="bg-white p-8 rounded-xl shadow-lg border border-slate-200 space-y-6 text-slate-600">
                <h3 class="text-xl font-semibold text-slate-800">1. Information We Collect</h3>
                <p>We collect information you provide directly to us when you create an account, such as your name, email address, and username. We do not store any sensitive financial information on our servers.</p>
                <h3 class="text-xl font-semibold text-slate-800">2. How We Use Your Information</h3>
                <p>We use the information we collect to operate, maintain, and provide you with the features and functionality of FinServe, including user authentication and communication.</p>
                <h3 class="text-xl font-semibold text-slate-800">3. Information Sharing</h3>
                <p>We do not sell or share your personal information with third parties for marketing purposes. All data is used solely for the purpose of the application's functionality.</p>
                <p class="mt-4">Last Updated: October 27, 2025</p>
            </div>
        </section>

        <section id="page-terms" class="page max-w-4xl mx-auto">
            <h2 class="text-3xl font-bold text-center mb-8">Terms and Conditions</h2>
            <div class="bg-white p-8 rounded-xl shadow-lg border border-slate-200 space-y-6 text-slate-600">
                <h3 class="text-xl font-semibold text-slate-800">1. Acceptance of Terms</h3>
                <p>By accessing or using FinServe, you agree to be bound by these Terms and Conditions. If you disagree with any part of the terms, you may not access the service.</p>
                <h3 class="text-xl font-semibold text-slate-800">2. Disclaimer</h3>
                <p>The information provided on FinServe is for educational and demonstrative purposes only. It is not financial advice. All sample data related to portfolios and investments is purely fictional.</p>
                <h3 class="text-xl font-semibold text-slate-800">3. User Conduct</h3>
                <p>You are responsible for maintaining the confidentiality of your account and password. You agree to accept responsibility for all activities that occur under your account.</p>
                <p class="mt-4">Last Updated: October 27, 2025</p>
            </div>
        </section>

    </main>

    <footer class="bg-slate-800 text-slate-300 mt-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-8">
                <div class="col-span-2 lg:col-span-2">
                    <h3 class="text-xl font-bold text-white mb-4">FinServe</h3>
                    <p class="text-sm max-w-sm">
                        Empowering your financial journey with expert advice and powerful tools.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-100 mb-3">Quick Links</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" data-page="home" class="nav-link hover:text-white">Home</a></li>
                        <li><a href="#" data-page="about" class="nav-link hover:text-white">About Us</a></li>
                        <li><a href="#" data-page="services" class="nav-link hover:text-white">Services</a></li>
                        <li><a href="#" data-page="contact" class="nav-link hover:text-white">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-100 mb-3">Tools</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" data-page="portfolio" class="nav-link hover:text-white">Portfolio</a></li>
                        <li><a href="#" data-page="research" class="nav-link hover:text-white">Research Picks</a></li>
                        <li><a href="#" data-page="calculators" class="nav-link hover:text-white">Calculators</a></li>
                        <li><a href="#" data-page="modules" class="nav-link hover:text-white">Learning Modules</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold text-slate-100 mb-3">Legal</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="#" data-page="privacy" class="nav-link hover:text-white">Privacy Policy</a></li>
                        <li><a href="#" data-page="terms" class="nav-link hover:text-white">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-slate-700 mt-8 pt-8 text-center text-sm">
                <p>&copy; 2025 FinServe. All rights reserved. Made by: C034,C035,C036</p>
            </div>
        </div>
    </footer>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            
            // --- Global Variables ---
            const pages = document.querySelectorAll('.page');
            const navLinks = document.querySelectorAll('.nav-link');
            const mobileMenu = document.getElementById('mobile-menu');
            let portfolioPieChartInstance = null; // To hold the pie chart object

            // Initialize Lucide icons
            lucide.createIcons();


            // --- Page Navigation & Active Link Handler ---
            function showPage(pageId) {
                const newPage = document.getElementById(`page-${pageId}`);
                if (!newPage) {
                    console.error(`Page with id 'page-${pageId}' not found.`);
                    return;
                }
                
                pages.forEach(page => page.classList.remove('active'));
                newPage.classList.add('active');
                
                // Update active nav links
                navLinks.forEach(link => {
                    if (link.getAttribute('data-page') === pageId) {
                        link.classList.add('active-nav');
                    } else {
                        link.classList.remove('active-nav');
                    }
                });
                
                window.scrollTo(0, 0);
            }

            // Add click listener to all navigation links
            navLinks.forEach(link => {
                link.addEventListener('click', (event) => {
                    event.preventDefault(); 
                    const pageId = link.getAttribute('data-page');
                    if (pageId) {
                        showPage(pageId);
                        if (link.classList.contains('mobile-link')) {
                            mobileMenu.classList.add('hidden');
                        }
                    }
                });
            });

            // --- Mobile Menu Toggle ---
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            if (mobileMenuButton) {
                mobileMenuButton.addEventListener('click', () => {
                    mobileMenu.classList.toggle('hidden');
                });
            }
            
            // --- User Menu Dropdown Toggle ---
            const userMenuButton = document.getElementById('user-menu-button');
            const userMenuDropdown = document.getElementById('user-menu-dropdown');
            if (userMenuButton) {
                userMenuButton.addEventListener('click', () => {
                    userMenuDropdown.classList.toggle('hidden');
                });
            }
            window.addEventListener('click', (e) => {
                if (userMenuButton && !userMenuButton.contains(e.target) && userMenuDropdown && !userMenuDropdown.contains(e.target)) {
                    userMenuDropdown.classList.add('hidden');
                }
            });


            // --- Portfolio Page Logic ---
            const showStocksBtn = document.getElementById('show-stocks');
            const showMfsBtn = document.getElementById('show-mfs');
            const stocksHoldings = document.getElementById('stocks-holdings');
            const mfHoldings = document.getElementById('mf-holdings');

            if (showStocksBtn) {
                showStocksBtn.addEventListener('click', () => {
                    stocksHoldings.classList.remove('hidden');
                    mfHoldings.classList.add('hidden');
                    showStocksBtn.classList.add('active-tab');
                    showMfsBtn.classList.remove('active-tab');
                });
            }
            if (showMfsBtn) {
                showMfsBtn.addEventListener('click', () => {
                    stocksHoldings.classList.add('hidden');
                    mfHoldings.classList.remove('hidden');
                    showStocksBtn.classList.remove('active-tab');
                    showMfsBtn.classList.add('active-tab');
                });
            }
            
            // --- Portfolio Pie Chart Logic ---
            const showPieChartBtn = document.getElementById('show-pie-chart-btn');
            const pieChartContainer = document.getElementById('pie-chart-container');
            
            if (showPieChartBtn) {
                showPieChartBtn.addEventListener('click', () => {
                    const isHidden = pieChartContainer.classList.contains('hidden');
                    
                    if (isHidden) {
                        renderPortfolioPieChart();
                        pieChartContainer.classList.remove('hidden');
                        showPieChartBtn.querySelector('span').textContent = 'Hide Diversification';
                    } else {
                        pieChartContainer.classList.add('hidden');
                        showPieChartBtn.querySelector('span').textContent = 'Show Diversification';
                        if (portfolioPieChartInstance) {
                            portfolioPieChartInstance.destroy();
                            portfolioPieChartInstance = null;
                        }
                    }
                });
            }

            function renderPortfolioPieChart() {
                const ctx = document.getElementById('portfolioPieChart');
                if (!ctx) return;

                if (portfolioPieChartInstance) {
                    portfolioPieChartInstance.destroy();
                }

                const legendTextColor = '#334155'; // slate-700

                const data = {
                    labels: ['Technology (IT)', 'Banking & Finance', 'FMCG', 'Pharmaceuticals', 'Others'],
                    datasets: [{
                        label: 'Portfolio Allocation',
                        data: [35, 25, 15, 10, 15],
                        backgroundColor: [
                            '#2563eb', // blue-600
                            '#16a34a', // green-600
                            '#ca8a04', // yellow-600
                            '#db2777', // pink-600
                            '#7c3aed', // violet-600
                        ],
                        hoverOffset: 4
                    }]
                };

                portfolioPieChartInstance = new Chart(ctx, {
                    type: 'pie',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right',
                                labels: {
                                    color: legendTextColor 
                                }
                            }
                        }
                    }
                });
            }


            // --- Module Accordion ---
            const moduleTriggers = document.querySelectorAll('.module-trigger');
            moduleTriggers.forEach(trigger => {
                trigger.addEventListener('click', () => {
                    const content = trigger.nextElementSibling;
                    const icon = trigger.querySelector('.module-icon');
                    content.classList.toggle('active');
                    icon.classList.toggle('rotate-180');
                });
            });


            // --- Contact Form (Simulation) ---
            const contactForm = document.getElementById('contact-form');
            const contactSuccess = document.getElementById('contact-success');
            if (contactForm) {
                contactForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    contactSuccess.classList.remove('hidden');
                    contactForm.reset();
                    setTimeout(() => contactSuccess.classList.add('hidden'), 4000);
                });
            }
            
            // --- EMI Calculator ---
            const emiForm = document.getElementById('emi-form');
            const emiResult = document.getElementById('emi-result');
            if (emiForm) {
                emiForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const principal = parseFloat(document.getElementById('principal').value);
                    const rate = parseFloat(document.getElementById('interest').value) / 100 / 12;
                    const years = parseFloat(document.getElementById('tenure').value);
                    const n = years * 12;

                    if (principal > 0 && rate > 0 && n > 0) {
                        const emi = (principal * rate * Math.pow(1 + rate, n)) / (Math.pow(1 + rate, n) - 1);
                        const totalPayment = emi * n;
                        const totalInterest = totalPayment - principal;

                        document.getElementById('emi-value').textContent = `₹${emi.toFixed(2)}`;
                        document.getElementById('emi-principal').textContent = `₹${principal.toFixed(2)}`;
                        document.getElementById('emi-interest').textContent = `₹${totalInterest.toFixed(2)}`;
                        document.getElementById('emi-total').textContent = `₹${totalPayment.toFixed(2)}`;
                        
                        emiResult.classList.remove('hidden');
                    } else {
                        emiResult.classList.add('hidden');
                    }
                });
            }

            // --- SIP Calculator ---
            const sipForm = document.getElementById('sip-form');
            const sipResult = document.getElementById('sip-result');
            if (sipForm) {
                sipForm.addEventListener('submit', (e) => {
                    e.preventDefault();
                    const amount = parseFloat(document.getElementById('sip-amount').value);
                    const rate = parseFloat(document.getElementById('sip-rate').value) / 100 / 12;
                    const years = parseFloat(document.getElementById('sip-years').value);
                    const n = years * 12;

                    if (amount > 0 && rate > 0 && n > 0) {
                        const futureValue = amount * ((Math.pow(1 + rate, n) - 1) / rate) * (1 + rate);
                        const investedAmount = amount * n;
                        const returns = futureValue - investedAmount;

                        document.getElementById('sip-value').textContent = `₹${futureValue.toFixed(2)}`;
                        document.getElementById('sip-invested').textContent = `₹${investedAmount.toFixed(2)}`;
                        document.getElementById('sip-returns').textContent = `₹${returns.toFixed(2)}`;

                        sipResult.classList.remove('hidden');
                    } else {
                        sipResult.classList.add('hidden');
                    }
                });
            }

        }); // End of DOMContentLoaded
    </script>
</body>
</html>