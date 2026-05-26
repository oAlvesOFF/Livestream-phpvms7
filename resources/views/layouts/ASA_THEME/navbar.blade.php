
<!-- CDN Links for Phosphor Icons -->
<link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
<link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/fill/style.css">

<!-- Navbar (Top) -->
<nav class="navbar navbar-expand-lg main-navbar px-4" style="background-color: var(--bg-card); border-bottom: 1px solid #eef2f6; height: 80px; display: flex; align-items: center; justify-content: space-between;">
    <!-- Left Side: Toggle and Branding -->
    <div class="d-flex align-items-center gap-3">
        <a href="#" id="menu-toggle-button" class="nav-link-toggle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; border-radius: 50%; background-color: var(--primary-light); color: var(--primary); transition: all 0.2s;">
            <i class="ph-fill ph-list" style="font-size: 20px;"></i>
        </a>
        <span class="fw-bold text-heading" style="font-family: 'Inter', sans-serif; letter-spacing: -0.5px; font-size: 18px; line-height: 1;">
            Atlantic Star Airways
        </span>
    </div>
    
    <!-- Right Side: Action Controls and Pilot Dropdown -->
    <ul class="navbar-nav navbar-right d-flex align-items-center" style="flex-direction: row; gap: 15px; margin: 0; padding: 0; list-style: none;">
        <!-- 1. Language dropdown -->
        <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle p-0 d-flex align-items-center justify-content-center" id="langDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="width: 42px; height: 42px; border-radius: 50%; transition: background-color 0.2s; background-color: var(--primary-light); color: var(--text-main);">
                <i class="ph ph-globe-simple" style="font-size: 20px;"></i>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm" aria-labelledby="langDropdown" style="background-color: var(--bg-card); border: 1px solid #eef2f6;">
                <li><a class="dropdown-item d-flex align-items-center gap-2" href="?lang=en"><span class="fi fi-us"></span> English</a></li>
                <li><a class="dropdown-item d-flex align-items-center gap-2" href="?lang=pt"><span class="fi fi-pt"></span> Português</a></li>
            </ul>
        </li>
        
        <!-- 2. Dark Mode Toggle Switch -->
        <li class="nav-item">
            <a href="#" id="theme-toggle-switch" class="nav-link p-0 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; border-radius: 50%; transition: background-color 0.2s; background-color: var(--primary-light); color: var(--text-main);" data-toggle="tooltip" title="Toggle Theme">
                <i class="ph ph-moon-stars" style="font-size: 20px;"></i>
            </a>
        </li>

        <!-- 3. Collapsed Sidebar Toggle (horizontal lines / document icon) -->
        <li class="nav-item">
            <a href="#" id="collapsed-sidebar-toggle-button" class="nav-link p-0 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; border-radius: 50%; transition: background-color 0.2s; background-color: var(--primary-light); color: var(--text-main);" data-toggle="tooltip" title="Toggle Sidebar Mode">
                <i class="ph ph-sidebar-simple" style="font-size: 20px;"></i>
            </a>
        </li>
        
        <!-- 4. ATC stats modal trigger -->
        <li class="nav-item">
            <a href="#" class="nav-link p-0 d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#atcStatsModal" style="width: 42px; height: 42px; border-radius: 50%; transition: background-color 0.2s; background-color: var(--primary-light); color: var(--text-main);" data-toggle="tooltip" title="ATC Stats">
                <i class="ph ph-broadcast" style="font-size: 20px;"></i>
            </a>
        </li>
        
        <!-- 5. Fullscreen Mode Toggle -->
        <li class="nav-item">
            <a href="#" id="toggle-fullscreen" class="nav-link p-0 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px; border-radius: 50%; transition: background-color 0.2s; background-color: var(--primary-light); color: var(--text-main);" data-toggle="tooltip" title="Fullscreen">
                <i class="ph ph-arrows-out" style="font-size: 20px;"></i>
            </a>
        </li>

        <!-- 6. Right-side sliding drawer (Who is online) trigger -->
        <li class="nav-item">
            <a href="#" class="nav-link p-0 d-flex align-items-center justify-content-center" data-bs-toggle="modal" data-bs-target="#onlinePilotsModal" style="width: 42px; height: 42px; border-radius: 50%; transition: background-color 0.2s; background-color: var(--primary-light); color: var(--text-main); position: relative;" data-toggle="tooltip" title="Who is online">
                <i class="ph ph-smiley" style="font-size: 20px;"></i>
                <span class="position-absolute badge rounded-circle bg-success" style="font-size: 9px; top: 3px; right: 3px; padding: 0; min-width: 16px; height: 16px; display: flex; align-items: center; justify-content: center; line-height: 1; color: white;">
                    {{ $online_count }}
                </span>
            </a>
        </li>

        <!-- Separator -->
        <li style="width: 1px; height: 24px; background-color: #eef2f6; margin: 0 4px;"></li>

        <!-- User Dropdown (if logged in) -->
        @if(Auth::check())
            <li class="dropdown nav-item">
                <a href="#" data-bs-toggle="dropdown" class="nav-link dropdown-toggle nav-link-user p-0 d-flex align-items-center gap-2" style="background: none; border: none; height: auto;">
                    @if (Auth::user()->avatar == null)
                        <img src="{{ Auth::user()->gravatar(36) }}" class="rounded-circle border" style="width:36px; height:36px; object-fit:cover; border-color: #eef2f6 !important;">
                    @else
                        <img src="{{ Auth::user()->avatar->url }}" class="rounded-circle border" style="width:36px; height:36px; object-fit:cover; border-color: #eef2f6 !important;">
                    @endif
                    <span class="d-none d-md-inline ms-1" style="font-size: 14px; font-weight: 600; color: var(--text-heading);">Hi, {{ Auth::user()->name_private }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-end shadow-sm" style="background-color: var(--bg-card); border: 1px solid #eef2f6; margin-top: 10px;">
                    <div class="dropdown-title text-muted small px-3 py-2" style="border-bottom: 1px solid #eef2f6;">Pilot Center</div>
                    <a href="{{ route('frontend.profile.show', [Auth::user()->id]) }}" class="dropdown-item d-flex align-items-center gap-2 px-3 py-2 text-decoration-none" style="color: var(--text-heading);">
                        <i class="ph-fill ph-user text-primary" style="font-size: 18px;"></i> @lang('common.profile')
                    </a>
                    <a href="{{ route('frontend.profile.index') }}" class="dropdown-item d-flex align-items-center gap-2 px-3 py-2 text-decoration-none" style="color: var(--text-heading);">
                        <i class="ph-fill ph-gear text-primary" style="font-size: 18px;"></i> Settings
                    </a>
                    <a href="{{ route('livestream.profile.index') }}" class="dropdown-item d-flex align-items-center gap-2 px-3 py-2 text-decoration-none" style="color: var(--text-heading);">
                        <i class="fas fa-video text-danger" style="font-size: 18px;"></i> Stream Settings
                    </a>
                    <div class="dropdown-divider border-0" style="background-color: #eef2f6; height: 1px;"></div>
                    @ability('admin', 'admin-access')
                        <a href="{{ url('/admin') }}" class="dropdown-item d-flex align-items-center gap-2 px-3 py-2 text-decoration-none" style="color: var(--text-heading);">
                            <i class="ph-fill ph-circle-notch text-primary" style="font-size: 18px;"></i> @lang('common.administration')
                        </a>
                    @endability
                    <a href="{{ url('/logout') }}" class="dropdown-item d-flex align-items-center gap-2 px-3 py-2 text-danger text-decoration-none">
                        <i class="ph-fill ph-sign-out-alt" style="font-size: 18px;"></i> @lang('common.logout')
                    </a>
                </div>
            </li>
        @endif
    </ul>
</nav>

<!-- ATC Statistics Modal -->
<style>
#networkStatsTabs .nav-link {
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
    opacity: 0.6;
}
#networkStatsTabs .nav-link.active {
    border-bottom: 2px solid #518ce5 !important;
    opacity: 1;
}
#networkStatsTabs .nav-link:hover:not(.active) {
    border-bottom: 2px solid #353b43;
    opacity: 0.8;
}
</style>
<div class="modal fade" id="atcStatsModal" tabindex="-1" aria-labelledby="atcStatsModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content" style="background-color: #282c32; border: 1px solid #353b43; border-radius: 8px;">
      <div class="modal-header border-bottom-0 pb-0 pt-3 px-3">
        <h5 class="modal-title fw-bold d-flex align-items-center" id="atcStatsModalLabel" style="color: #fff; font-size: 1.1rem;">
            <i class="ph-fill ph-control-tower me-2" style="font-size: 20px;"></i> Online Network Statistics
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" style="background: none; border: none; color: #fff; font-size: 20px; line-height: 1; padding: 0;">
            <i class="ph ph-minus"></i>
        </button>
      </div>
      <div class="modal-body p-0 mt-3">
        <!-- Tabs -->
        <ul class="nav nav-tabs px-3" id="networkStatsTabs" role="tablist" style="border-bottom: 1px solid #353b43;">
            <li class="nav-item" role="presentation" style="flex: 1; text-align: center;">
                <button class="nav-link active w-100 bg-transparent border-0" id="vatsim-tab" data-bs-toggle="tab" data-bs-target="#vatsim-stats" type="button" role="tab" style="padding-bottom: 15px; border-bottom: 2px solid #518ce5 !important;">
                    <img src="https://virtualcargo.international/sp_vci/images/vatsim_pirep_button.png" style="height: 24px;">
                </button>
            </li>
            <li class="nav-item" role="presentation" style="flex: 1; text-align: center;">
                <button class="nav-link w-100 bg-transparent border-0" id="ivao-tab" data-bs-toggle="tab" data-bs-target="#ivao-stats" type="button" role="tab" style="padding-bottom: 15px; border-bottom: 2px solid transparent;">
                    <img src="https://virtualcargo.international/sp_vci/images/ivao_pirep_button.png" style="height: 24px; filter: brightness(0.7);">
                </button>
            </li>
        </ul>

        <!-- Tab Content -->
        <div class="tab-content" id="networkStatsTabsContent">
            <!-- VATSIM Tab -->
            <div class="tab-pane fade show active" id="vatsim-stats" role="tabpanel">
                <ul class="list-group list-group-flush" style="background-color: transparent;">
                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: transparent; border-color: #353b43; color: #cbd5e1; padding: 15px 20px;">
                        <div><i class="ph ph-triangle me-2" style="color: #94a3b8; transform: rotate(90deg); display: inline-block;"></i> Pilots</div>
                        <span class="badge" style="background-color: #848b94; color: #fff; border-radius: 4px; padding: 5px 8px; font-weight: 600;">{{ $network_stats['vatsim']['pilots'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: transparent; border-color: #353b43; color: #cbd5e1; padding: 15px 20px;">
                        <div><i class="ph ph-triangle me-2" style="color: #94a3b8; transform: rotate(90deg); display: inline-block;"></i> Controllers</div>
                        <span class="badge" style="background-color: #848b94; color: #fff; border-radius: 4px; padding: 5px 8px; font-weight: 600;">{{ $network_stats['vatsim']['controllers'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: transparent; border-color: #353b43; color: #cbd5e1; padding: 15px 20px;">
                        <div><i class="ph ph-triangle me-2" style="color: #94a3b8; transform: rotate(90deg); display: inline-block;"></i> Atis</div>
                        <span class="badge" style="background-color: #848b94; color: #fff; border-radius: 4px; padding: 5px 8px; font-weight: 600;">{{ $network_stats['vatsim']['atis'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: transparent; border-color: #353b43; color: #cbd5e1; padding: 15px 20px;">
                        <div><i class="ph ph-triangle me-2" style="color: #94a3b8; transform: rotate(90deg); display: inline-block;"></i> Total Users</div>
                        <span class="badge" style="background-color: #848b94; color: #fff; border-radius: 4px; padding: 5px 8px; font-weight: 600;">{{ $network_stats['vatsim']['total'] }}</span>
                    </li>
                    @if($network_stats['vatsim']['status'] == 'Offline')
                        <li class="list-group-item text-center text-danger" style="background-color: transparent; border-color: #353b43; font-size: 0.8rem; padding: 10px;">
                            <i class="ph-fill ph-warning"></i> Network data currently offline
                        </li>
                    @endif
                </ul>
            </div>
            
            <!-- IVAO Tab -->
            <div class="tab-pane fade" id="ivao-stats" role="tabpanel">
                 <ul class="list-group list-group-flush" style="background-color: transparent;">
                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: transparent; border-color: #353b43; color: #cbd5e1; padding: 15px 20px;">
                        <div><i class="ph ph-triangle me-2" style="color: #94a3b8; transform: rotate(90deg); display: inline-block;"></i> Pilots</div>
                        <span class="badge" style="background-color: #848b94; color: #fff; border-radius: 4px; padding: 5px 8px; font-weight: 600;">{{ $network_stats['ivao']['pilots'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: transparent; border-color: #353b43; color: #cbd5e1; padding: 15px 20px;">
                        <div><i class="ph ph-triangle me-2" style="color: #94a3b8; transform: rotate(90deg); display: inline-block;"></i> Controllers</div>
                        <span class="badge" style="background-color: #848b94; color: #fff; border-radius: 4px; padding: 5px 8px; font-weight: 600;">{{ $network_stats['ivao']['controllers'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: transparent; border-color: #353b43; color: #cbd5e1; padding: 15px 20px;">
                        <div><i class="ph ph-triangle me-2" style="color: #94a3b8; transform: rotate(90deg); display: inline-block;"></i> Observers</div>
                        <span class="badge" style="background-color: #848b94; color: #fff; border-radius: 4px; padding: 5px 8px; font-weight: 600;">{{ $network_stats['ivao']['observers'] }}</span>
                    </li>
                    <li class="list-group-item d-flex justify-content-between align-items-center" style="background-color: transparent; border-color: #353b43; color: #cbd5e1; padding: 15px 20px;">
                        <div><i class="ph ph-triangle me-2" style="color: #94a3b8; transform: rotate(90deg); display: inline-block;"></i> Total Users</div>
                        <span class="badge" style="background-color: #848b94; color: #fff; border-radius: 4px; padding: 5px 8px; font-weight: 600;">{{ $network_stats['ivao']['total'] }}</span>
                    </li>
                    @if($network_stats['ivao']['status'] == 'Offline')
                        <li class="list-group-item text-center text-danger" style="background-color: transparent; border-color: #353b43; font-size: 0.8rem; padding: 10px;">
                            <i class="ph-fill ph-warning"></i> Network data currently offline
                        </li>
                    @endif
                </ul>
            </div>
        </div>
      </div>
      <div class="modal-footer border-top-0 pt-0 pb-3 pe-3">
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal" style="background-color: #518ce5; border-color: #518ce5; border-radius: 4px; font-weight: 600; padding: 6px 16px;">Close</button>
      </div>
    </div>
  </div>
</div>


<!-- JavaScript for Navbar Functions -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // 1. Mobile Sidebar Toggle (Hamburger Button)
        $('#menu-toggle-button').on('click', function(e) {
            e.preventDefault();
            $('.main-sidebar').toggleClass('show');
        });

        // 2. Collapsed Sidebar Toggle (Horizontal lines / simple sidebar icon)
        $('#collapsed-sidebar-toggle-button').on('click', function(e) {
            e.preventDefault();
            $('body').toggleClass('page-sidebar-collapsed');
            localStorage.setItem('sidebar-collapsed', $('body').hasClass('page-sidebar-collapsed'));
        });

        // Restore sidebar state
        if (localStorage.getItem('sidebar-collapsed') === 'true') {
            $('body').addClass('page-sidebar-collapsed');
        }

        // 3. Dark/Light Theme Switcher
        $('#theme-toggle-switch').on('click', function(e) {
            e.preventDefault();
            var currentTheme = $('html').attr('data-bs-theme');
            var newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            $('html').attr('data-bs-theme', newTheme);
            localStorage.setItem('theme', newTheme);
            
            var $icon = $(this).find('i');
            if (newTheme === 'dark') {
                $icon.removeClass('ph-sun').addClass('ph-moon-stars');
            } else {
                $icon.removeClass('ph-moon-stars').addClass('ph-sun');
            }
        });

        // Restore theme state
        var storedTheme = localStorage.getItem('theme') || 'light';
        $('html').attr('data-bs-theme', storedTheme);
        if (storedTheme === 'dark') {
            $('#theme-toggle-switch i').removeClass('ph-sun').addClass('ph-moon-stars');
        } else {
            $('#theme-toggle-switch i').removeClass('ph-moon-stars').addClass('ph-sun');
        }

        // 4. Fullscreen Toggle
        $('#toggle-fullscreen').on('click', function(e) {
            e.preventDefault();
            if (!document.fullscreenElement) {
                document.documentElement.requestFullscreen().catch(err => {
                    console.error(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
                });
            } else {
                document.exitFullscreen();
            }
        });
    });
</script>
