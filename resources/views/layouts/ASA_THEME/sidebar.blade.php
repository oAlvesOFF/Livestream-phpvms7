<!-- Main Sidebar (Left with menus) -->
<div class="main-sidebar">
    <aside id="sidebar-wrapper">
        <!-- Sidebar Brand Header (ID and Logo) -->
        <div class="sidebar-brand">
            @if(Auth::check())
                <div class="user-id d-flex align-items-center gap-2">
                    @if(Auth::user()->state === \App\Models\Enums\UserState::ON_LEAVE)
                        <span class="status-dot bg-warning" data-toggle="tooltip" title="Status: On Leave"></span>
                    @else
                        <span class="status-dot bg-success" data-toggle="tooltip" title="Status: Active"></span>
                    @endif
                    <span class="fw-bold text-heading" style="font-size: 14px; letter-spacing: 0.5px;">ID:
                        {{ Auth::user()->ident }}</span>
                </div>
            @else
                <div class="user-id d-flex align-items-center gap-2">
                    <span class="status-dot bg-primary" data-toggle="tooltip" title="Status: Guest"></span>
                    <span class="fw-bold text-heading" style="font-size: 14px; letter-spacing: 0.5px;">Welcome</span>
                </div>
            @endif
        </div>

        <ul class="sidebar-menu">
            <!-- MENU Header -->
            <li class="menu-header d-flex align-items-center gap-2">
                <i class="ph ph-network" style="font-size: 14px;"></i>
                <span>Menu</span>
            </li>

            @if(Auth::check())
                <!-- News -->
                <li class="accordion-menu-item">
                    <a class="nav-link" href="{{ route('frontend.dashboard.index') }}" data-toggle="tooltip"
                        title="Read our latest news">
                        <i class="ph ph-newspaper"></i>
                        <span>News</span>
                    </a>
                </li>

                <!-- Welcome (Dropdown) -->
                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="ph ph-hand-peace"></i>
                        <span>Welcome</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="nav-link" href="{{ url('/') }}">
                                <i class="ph ph-house"></i>
                                <span>Home</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ route('frontend.pilots.index') }}">
                                <i class="ph ph-users"></i>
                                <span>Pilots</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ route('frontend.flights.index') }}">
                                <i class="ph ph-airplane-tilt"></i>
                                <span>Flights</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ route('frontend.livemap.index') }}">
                                <i class="ph ph-globe-simple-x"></i>
                                <span>Live Map</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/airline-info-pulse') }}">
                                <i class="ph ph-heartbeat"></i>
                                <span>Airline Pulse</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Pilot Center (Dropdown) -->
                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="ph ph-user-circle-check"></i>
                        <span>Pilot Center</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="nav-link" href="{{ route('frontend.profile.show', [Auth::user()->id]) }}">
                                <i class="ph ph-user-square"></i>
                                <span>Profile</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ route('frontend.profile.index') }}">
                                <i class="ph ph-gear"></i>
                                <span>Settings</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dmarket/' . Auth::user()->id) }}">
                                <i class="ph ph-shopping-cart"></i>
                                <span>Bought Items</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dhubs/' . (Auth::user()->home_airport_id ?: 'LPPD')) }}">
                                <i class="ph ph-crosshair-simple"></i>
                                <span>My HUB</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dairline/' . (Auth::user()->airline_id ?: 1)) }}">
                                <i class="ph ph-building"></i>
                                <span>My Airline</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dscenery') }}">
                                <i class="ph ph-trolley"></i>
                                <span>My Sceneries</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dassignments') }}">
                                <i class="ph ph-file-archive"></i>
                                <span>Assignments</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ route('frontend.pireps.index') }}">
                                <i class="ph ph-books"></i>
                                <span>My Reports</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ route('frontend.flights.bids') }}">
                                <i class="ph ph-address-book"></i>
                                <span>My Bids</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ route('paxstudio.index') }}">
                                <i class="ph ph-airplane-in-flight"></i>
                                <span>PaxStudio Dispatch</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ route('liveoverlay.manager') }}">
                                <i class="ph ph-screencast"></i>
                                <span>OBS Live Overlay</span>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Company (Dropdown) -->
                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="ph ph-buildings"></i>
                        <span>Company</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="nav-link" href="{{ route('frontend.pilots.index') }}">
                                <i class="ph ph-users"></i>
                                <span>Pilots</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dairlines') }}">
                                <i class="ph ph-building"></i>
                                <span>Airline</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dfleet') }}">
                                <i class="ph ph-airplane-tilt"></i>
                                <span>Fleet</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dmaintenance') }}">
                                <i class="ph ph-screencast"></i>
                                <span>Maintenance</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dmarket') }}">
                                <i class="ph ph-bag"></i>
                                <span>Pilot Shop</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/sptransfer/hub') }}">
                                <i class="ph ph-repeat"></i>
                                <span>HUB Transfer</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/sptransfer/airline') }}">
                                <i class="ph ph-repeat"></i>
                                <span>Airline Transfer</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dranks') }}">
                                <i class="ph ph-medal"></i>
                                <span>Ranks</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dawards') }}">
                                <i class="ph ph-trophy"></i>
                                <span>Awards</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dstats') }}">
                                <i class="ph ph-chart-bar"></i>
                                <span>Statistics</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ route('frontend.downloads.index') }}">
                                <i class="ph ph-tray-arrow-down"></i>
                                <span>Downloads</span>
                            </a>
                        </li>
                        <!-- Dynamic Pages -->
                        @if(isset($page_links) && $page_links)
                            @foreach($page_links as $page)
                                <li>
                                    <a class="nav-link" href="{{ url(route('frontend.pages.show', ['slug' => $page->slug])) }}">
                                        <i class="ph ph-file-text"></i>
                                        <span>{{ $page['name'] }}</span>
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    </ul>
                </li>

                <!-- Operation (Dropdown) -->
                <li class="dropdown">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="ph ph-hard-drives"></i>
                        <span>Operation</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li>
                            <a class="nav-link" href="{{ route('frontend.flights.index') }}">
                                <i class="ph ph-book-open-text"></i>
                                <span>Bookings</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dfreeflight') }}">
                                <i class="ph ph-compass-rose"></i>
                                <span>Freeflight</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dmissions') }}">
                                <i class="ph ph-siren"></i>
                                <span>Missions</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dtours') }}">
                                <i class="ph ph-bounding-box"></i>
                                <span>Tours</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dhubs') }}">
                                <i class="ph ph-crosshair-simple"></i>
                                <span>Our HUBs</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ route('frontend.pireps.index') }}">
                                <i class="ph ph-books"></i>
                                <span>Pilot Reports</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ route('frontend.livemap.index') }}">
                                <i class="ph ph-map-trifold"></i>
                                <span>Live Flights</span>
                            </a>
                        </li>
                        <li>
                            <a class="nav-link" href="{{ url('/dlivewx') }}">
                                <i class="ph ph-cloud-sun"></i>
                                <span>Live Weather</span>
                            </a>
                        </li>
                    </ul>
                </li>

            @else
                <!-- Visitor Menu -->
                <li class="accordion-menu-item">
                    <a class="nav-link" href="{{ url('/') }}">
                        <i class="ph ph-house"></i>
                        <span>Home</span>
                    </a>
                </li>
                <li class="accordion-menu-item">
                    <a class="nav-link" href="{{ route('frontend.pilots.index') }}">
                        <i class="ph ph-users"></i>
                        <span>Pilots</span>
                    </a>
                </li>
                <li class="accordion-menu-item">
                    <a class="nav-link" href="{{ route('frontend.livemap.index') }}">
                        <i class="ph ph-globe-simple-x"></i>
                        <span>Live Map</span>
                    </a>
                </li>
            @endif

            <!-- PRIVATE Header -->
            <li class="menu-header d-flex align-items-center gap-2 mt-3">
                <i class="ph ph-lock-key" style="font-size: 14px;"></i>
                <span>Private</span>
            </li>

            @if(Auth::check())
                <li class="accordion-menu-item">
                    <a class="nav-link" href="{{ route('frontend.dashboard.index') }}" data-toggle="tooltip"
                        title="Start your journey">
                        <i class="ph ph-desktop"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

            @else
                <li class="accordion-menu-item">
                    <a class="nav-link" href="{{ url('/register') }}" data-toggle="tooltip" title="Start your career, now!">
                        <i class="ph ph-note-pencil"></i>
                        <span>Register</span>
                    </a>
                </li>
                <li class="accordion-menu-item">
                    <a class="nav-link" href="{{ url('/login') }}" data-toggle="tooltip" title="Welcome back, Pilot!">
                        <i class="ph ph-sign-in"></i>
                        <span>Log In</span>
                    </a>
                </li>
            @endif
        </ul>
    </aside>
</div>

<!-- Accordion Dropdown Sub-menus Styles -->
<style>
    /* Accordion Dropdown Sub-menus Styles */
    .main-sidebar .sidebar-menu li.dropdown .dropdown-menu {
        display: none !important;
        position: relative !important;
        top: 0 !important;
        left: 0 !important;
        width: 100% !important;
        float: none !important;
        background-color: var(--primary-light) !important;
        padding: 5px 0 !important;
        margin: 5px 0 10px 0 !important;
        border-radius: 6px !important;
        border: none !important;
        box-shadow: none !important;
        z-index: 1 !important;
    }

    .main-sidebar .sidebar-menu li.dropdown.active .dropdown-menu {
        display: block !important;
    }

    .main-sidebar .sidebar-menu li.dropdown .dropdown-menu li {
        background: none !important;
        border: none !important;
        box-shadow: none !important;
        padding: 0 !important;
        margin: 0 !important;
    }

    .main-sidebar .sidebar-menu li.dropdown .dropdown-menu li a {
        background: none !important;
        border: none !important;
        box-shadow: none !important;
        display: flex !important;
        align-items: center !important;
        gap: 12px !important;
        height: 38px !important;
        line-height: 38px !important;
        padding: 0 20px 0 45px !important;
        /* Indent sub items */
        color: var(--text-main) !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        transition: all 0.2s ease-in-out;
        text-decoration: none !important;
    }

    .main-sidebar .sidebar-menu li.dropdown .dropdown-menu li a:hover {
        color: var(--primary) !important;
        background-color: rgba(56, 141, 250, 0.08) !important;
        padding-left: 50px !important;
        /* Beautiful micro-animation */
    }

    .main-sidebar .sidebar-menu li.dropdown .dropdown-menu li a i {
        font-size: 14px !important;
        margin: 0 !important;
        width: 18px !important;
        text-align: center !important;
        color: var(--text-muted) !important;
    }

    .main-sidebar .sidebar-menu li.dropdown .dropdown-menu li a:hover i {
        color: var(--primary) !important;
    }

    .main-sidebar .sidebar-menu li.dropdown>a.has-dropdown::after {
        content: "" !important;
        position: absolute;
        right: 22px;
        top: 50%;
        width: 6px;
        height: 6px;
        border-right: 2px solid var(--text-muted);
        border-bottom: 2px solid var(--text-muted);
        transform: translateY(-55%) rotate(-45deg);
        transition: transform 0.2s ease-in-out;
        opacity: 0.7;
    }

    .main-sidebar .sidebar-menu li.dropdown.active>a.has-dropdown::after {
        transform: translateY(-55%) rotate(45deg) !important;
        border-color: var(--primary) !important;
        opacity: 1;
    }
</style>

<!-- Accordion Dropdown Controller JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        // Toggle Sidebar Dropdowns (Accordion mode)
        $('.main-sidebar .sidebar-menu li.dropdown > a.has-dropdown').on('click', function (e) {
            e.preventDefault();
            e.stopPropagation();

            var $li = $(this).parent();
            var $menu = $li.find('> .dropdown-menu');

            if ($li.hasClass('active')) {
                $menu.slideUp(250, function () {
                    $li.removeClass('active');
                });
            } else {
                // Collapse sibling dropdowns
                $li.siblings('.dropdown.active').each(function () {
                    var $siblingLi = $(this);
                    $siblingLi.find('> .dropdown-menu').slideUp(250, function () {
                        $siblingLi.removeClass('active');
                    });
                });

                $li.addClass('active');
                $menu.slideDown(250);
            }
        });

        // Auto-expand and activate icon logic
        var currentUrl = window.location.href.split('?')[0];
        var baseUrl = '{{ url('/') }}';

        $('.main-sidebar .sidebar-menu a').each(function () {
            var linkUrl = $(this).attr('href');
            if (linkUrl && (currentUrl === linkUrl || (linkUrl !== baseUrl && currentUrl.indexOf(linkUrl) === 0))) {

                var $parentLi = $(this).closest('li');
                var $parentDropdown = $(this).closest('li.dropdown');

                $parentLi.addClass('active');

                // Change current link icon to fill
                var $icon = $(this).find('i.ph');
                if ($icon.length) {
                    $icon.removeClass('ph').addClass('ph-fill');
                }

                // Change parent dropdown icon to fill
                if ($parentDropdown.length) {
                    $parentDropdown.addClass('active');
                    $parentDropdown.find('> .dropdown-menu').show();

                    var $parentIcon = $parentDropdown.find('> a > i.ph');
                    if ($parentIcon.length) {
                        $parentIcon.removeClass('ph').addClass('ph-fill');
                    }
                }
            }
        });
    });
</script>