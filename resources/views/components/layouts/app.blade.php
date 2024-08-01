<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('style.css')}}">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Tiro+Bangla:ital@0;1&display=swap" rel="stylesheet">
    <title>Stamp Selling | Admin Panel</title>
</head>

<body>

    <div class="container">
        <!-- Sidebar Section -->
        <aside>
            <div class="toggle">
                <div class="logo align-items-center">
                    <img src="{{asset('favicon.png')}}">
                    <h2 >Stamp<span class="text-blue-500"> Selling</span></h2>
                </div>
                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">
                        close
                    </span>
                </div>
            </div>
            {{-- {{ request()->routeIs('dashboard') ? 'active' : '' }} --}}
            <div class="sidebar">
                <a href="{{route('dashboard')}}"  class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        dashboard
                    </span>
                    <h3>Dashboard</h3>
                </a>

                <a href="{{ route('money_manage') }}" class="{{ request()->routeIs('money_manage') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        attach_money
                    </span>
                    <h3>Money Management</h3>
                </a>

                <a href="{{ route('stocks') }}" class="{{ request()->routeIs('stocks') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        insert_chart_outlined
                    </span>
                    <h3>Purchase (Stock)</h3>
                </a>
                
                <a href="{{ route('branches') }}" class="{{ request()->routeIs('branches') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        location_city
                    </span>
                    <h3>Branches</h3>
                </a>
                
                <a href="{{ route('set_price') }}" class="{{ request()->routeIs('set_price') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        attach_money
                    </span>
                    <h3>Unit Price (Branch)</h3>
                </a>
                
                <a href="{{ route('branch_sale') }}" class="{{ request()->routeIs('branch_sale') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        local_grocery_store
                    </span>
                    <h3>Branch Management</h3>
                </a>
                
                <a href="{{ route('office_sale') }}" class="{{ request()->routeIs('office_sale') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        store
                    </span>
                    <h3>Head Office Sale</h3>
                </a>
                
                <a href="{{ route('reject_free') }}" class="{{ request()->routeIs('reject_free') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        delete
                    </span>
                    <h3>Reject or Free</h3>
                </a>

                <a href="{{ route('expences') }}" class="{{ request()->routeIs('expences') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        monetization_on
                    </span>
                    <h3>Expences</h3>
                </a>
                
                <a href="{{ route('balance_sheet') }}" class="{{ request()->routeIs('balance_sheet') ? 'active' : '' }}">
                    <span class="material-icons-sharp">
                        account_balance
                    </span>
                    <h3>Balance Sheet</h3>
                </a>

                

                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <span class="material-icons-sharp">
                        logout
                    </span>
                    <h3>Logout</h3>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                </form>
            </div>
        </aside>


        <!-- Main Content -->
        <main>
            <div class="right-section">
                <div class="nav">
     
                    <button id="menu-btn">
                        <span class="material-icons-sharp">
                            menu
                        </span>
                    </button>

    
                    <div class="dark-mode">
                        <span class="material-icons-sharp active">
                            light_mode
                        </span>
                        <span class="material-icons-sharp">
                            dark_mode
                        </span>
                    </div>
                </div>
            </div>  
            {{ $slot }}
        </main>
        <!-- End of Main Content -->
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="{{asset('index.js')}}"></script>
    <script>
        document.querySelectorAll('.dropdown-toggle').forEach(item => {
            item.addEventListener('click', event => {
                event.preventDefault();
                const dropdownContent = item.nextElementSibling;
                dropdownContent.classList.toggle('active');
                const icon = item.querySelector('.icon-rotate');
                icon.classList.toggle('rotate-180');
            });
        });

        // Add active-link class to the current link
        document.querySelectorAll('.sidebar a').forEach(link => {
            if (link.href === window.location.href) {
                link.classList.add('active-link');
                // Expand dropdown if the link is inside it
                if (link.closest('.dropdown-content')) {
                    link.closest('.dropdown-content').classList.add('active');
                    link.closest('.dropdown').querySelector('.icon-rotate').classList.add('rotate-180');
                }
            }
        });

    </script>
</body>

</html>