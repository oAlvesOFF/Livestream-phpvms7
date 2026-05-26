@extends('app')

@section('title', __('common.profile'))

@php
    $ivao_id = optional($user->fields->firstWhere('name', Theme::getSetting('gen_ivao_field')))->value;
    $vatsim_id = optional($user->fields->firstWhere('name', Theme::getSetting('gen_vatsim_field')))->value;
    $discord_id = optional($user->fields->firstWhere('name', 'discord id'))->value
        ?? optional($user->fields->firstWhere('name', 'Discord ID'))->value
        ?? $user->discord_id;
    $balance = optional($user->journal)->balance;

    if (!function_exists('safe_money_format')) {
        function safe_money_format($val)
        {
            if (is_object($val)) {
                if (method_exists($val, 'toFloat'))
                    return number_format($val->toFloat(), 2);
                if (method_exists($val, 'getAmount')) {
                    $amt = $val->getAmount();
                    if (is_object($amt) && method_exists($amt, 'toFloat'))
                        return number_format($amt->toFloat(), 2);
                    return number_format((float) $amt, 2);
                }
                return "0.00";
            }
            return number_format((float) $val, 2);
        }
    }
@endphp

@section('content')
<style>
/* Modern Profile Styles matching ASA_THEME */
.sp-profile-view { font-family: 'Inter', sans-serif; }

.sp-tabs {
    border-bottom: 1px solid var(--border-card, #353b43);
    margin-bottom: 0;
}
.sp-tabs .nav-link {
    color: var(--text-muted, #94a3b8);
    background: transparent;
    border: none;
    border-bottom: 2px solid transparent;
    padding: 15px 20px;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
}
.sp-tabs .nav-link.active {
    color: var(--primary, #518ce5) !important;
    border-bottom: 2px solid var(--primary, #518ce5) !important;
}
.sp-tabs .nav-link:hover:not(.active) {
    color: var(--text-main, #cbd5e1);
    border-bottom: 2px solid var(--border-card, #353b43);
}

/* Metric mini card */
.sp-metric-box {
    background: var(--bg-card, #282c32);
    border: 1px solid var(--border-card, #353b43);
    border-radius: 8px;
    padding: 20px;
    display: flex;
    gap: 15px;
    align-items: center;
    height: 100%;
}
.sp-metric-val {
    font-size: 20px;
    font-weight: 800;
    color: #fff;
}
.sp-metric-lbl {
    font-size: 10px;
    color: var(--text-muted, #94a3b8);
    font-weight: 700;
    text-transform: uppercase;
}
</style>

<div class="sp-profile-view">
    <div class="row">
        <!-- Breadcrumbs & UTC -->
        <div class="col-12">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; font-size: 13px; color: var(--text-muted, #94a3b8);">
                <div>
                    <span style="color: #518ce5; font-weight: 700;">Atlantic Star Airways</span>
                    <i class="ph-fill ph-caret-right" style="font-size: 10px; margin: 0 5px;"></i> Profile
                </div>
                <div>
                    <i class="ph-fill ph-clock" style="margin-right: 5px;"></i> UTC {{ now()->format('H:i:s') }}
                </div>
            </div>
        </div>

        <!-- Left Sidebar (Pilot ID / Profile Details) -->
        <div class="col-lg-4">
            <div class="card mb-4" style="background: #282c32; border: 1px solid #353b43; border-radius: 8px;">
                <div class="card-body" style="padding: 25px;">
                    <!-- Avatar and Networks -->
                    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                        <img src="{{ $user->avatar ? $user->avatar->url : $user->gravatar(120) }}"
                             style="width: 70px; height: 70px; border-radius: 50%; border: 1px solid #353b43;">
                        
                        <div class="d-flex gap-3 text-center flex-grow-1 justify-content-around">
                            <div>
                                <img src="https://virtualcargo.international/sp_vci/images/vatsim_pirep_button.png"
                                    style="height: 18px; margin-bottom: 5px; filter: brightness(0) invert(1);">
                                <div style="font-size: 10px; font-weight: 700; color: var(--text-muted, #94a3b8);">ID: {{ $vatsim_id ?: '-' }}</div>
                            </div>
                            <div>
                                <img src="https://virtualcargo.international/sp_vci/images/ivao_pirep_button.png"
                                    style="height: 18px; margin-bottom: 5px; filter: brightness(0) invert(1);">
                                <div style="font-size: 10px; font-weight: 700; color: var(--text-muted, #94a3b8);">ID: {{ $ivao_id ?: '-' }}</div>
                            </div>
                             <div>
                                 <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 127.14 96.36" style="height: 18px; margin-bottom: 5px; fill: #fff; display: inline-block; vertical-align: middle;">
                                     <path d="M107.7,8.07A105.15,105.15,0,0,0,77.26,0a77,77,0,0,0-3.3,6.83A96.67,96.67,0,0,0,53.22,6.83,77,77,0,0,0,49.88,0,105.15,105.15,0,0,0,19.44,8.07C3.66,31.58-1.86,54.65,1,77.53A105.73,105.73,0,0,0,32,96.36a77.7,77.7,0,0,0,6.63-10.85,68.43,68.43,0,0,1-10.5-5c2.58-1.9,5.09-4,7.41-6.2a73.12,73.12,0,0,0,63.18,0c2.32,2.2,4.83,4.3,7.41,6.2a68.43,68.43,0,0,1-10.5,5,77.7,77.7,0,0,0,6.63,10.85,105.73,105.73,0,0,0,31-18.83C129,54.65,122.54,31.58,107.7,8.07ZM42.45,65.69C36.18,65.69,31,60,31,53S36.18,40.36,42.45,40.36,53.83,46,53.83,53,48.72,65.69,42.45,65.69Zm42.24,0C78.41,65.69,73.24,60,73.24,53S78.41,40.36,84.69,40.36,96.07,46,96.07,53,91,65.69,84.69,65.69Z"/>
                                 </svg>
                                 <div style="font-size: 10px; font-weight: 700; color: var(--text-muted, #94a3b8);">ID: {{ $discord_id ?: '-' }}</div>
                             </div>
                        </div>
                    </div>

                    <!-- Name and Title -->
                    <div style="margin-bottom: 20px; border-bottom: 3px solid #518ce5; padding-bottom: 10px;">
                        <div style="display: flex; align-items: center; gap: 10px; flex-wrap: wrap;">
                            @if(filled($user->country))
                                <img src="https://flagcdn.com/24x18/{{ strtolower($user->country) }}.png"
                                    style="border-radius: 2px; height: 14px;">
                            @endif
                            <h5 style="margin: 0; font-weight: 800; color: #fff;">
                                {{ optional($user->rank)->name }}, {{ $user->name_private }}
                            </h5>
                        </div>
                    </div>

                    <!-- Pilot ID Badge -->
                    <div style="background: rgba(245, 176, 65, 0.15); color: #f5b041; border: 1px solid rgba(245, 176, 65, 0.3); padding: 12px; border-radius: 4px; text-align: center; font-weight: 700; font-size: 13px; margin-bottom: 20px; display: flex; align-items: center; justify-content: center; gap: 8px;">
                        <i class="ph-fill ph-identification-card"></i> Pilot ID: {{ $user->ident }}
                    </div>

                    <!-- Info Table -->
                    <div style="display: flex; flex-direction: column;">
                        @if(Auth::check() && $user->id === Auth::user()->id)
                            <!-- API Key -->
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #353b43;">
                                <span style="font-size: 13px; font-weight: 700; color: var(--text-main, #cbd5e1);">API Key</span>
                                <span style="font-size: 13px; font-weight: 700; color: var(--text-muted, #94a3b8);">
                                    <span id="apiKey_show" style="display: none;">
                                        {{ $user->api_key }} 
                                        <i class="ph-fill ph-eye-slash ms-2" onclick="apiKeyHide()" style="cursor: pointer; color: #518ce5;"></i>
                                    </span>
                                    <span id="apiKey_hide" style="cursor: pointer;" onclick="apiKeyShow()">
                                        API Key <i class="ph-fill ph-eye ms-2" style="color: #518ce5;"></i>
                                    </span>
                                </span>
                            </div>

                            <!-- E-mail -->
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #353b43;">
                                <span style="font-size: 13px; font-weight: 700; color: var(--text-main, #cbd5e1);">E-mail</span>
                                <span style="font-size: 13px; font-weight: 700; color: var(--text-muted, #94a3b8);">
                                    <span id="email_show" style="display: none;">
                                        {{ $user->email }} 
                                        <i class="ph-fill ph-eye-slash ms-2" onclick="emailHide()" style="cursor: pointer; color: #518ce5;"></i>
                                    </span>
                                    <span id="email_hide" style="cursor: pointer;" onclick="emailShow()">
                                        Email <i class="ph-fill ph-eye ms-2" style="color: #518ce5;"></i>
                                    </span>
                                </span>
                            </div>
                        @endif

                        <!-- Rank -->
                        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #353b43;">
                            <span style="font-size: 13px; font-weight: 700; color: var(--text-main, #cbd5e1);">Rank</span>
                            <span style="font-size: 13px; font-weight: 700; color: #fff; display: flex; align-items: center; gap: 8px;">
                                {{ optional($user->rank)->name }}
                                @if(optional($user->rank)->image_url)
                                    <img src="{{ $user->rank->image_url }}" style="max-height: 20px;">
                                @endif
                            </span>
                        </div>

                        <!-- Home Airport -->
                        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #353b43;">
                            <span style="font-size: 13px; font-weight: 700; color: var(--text-main, #cbd5e1);">Home Airport</span>
                            <span style="background: #518ce5; color: white; padding: 4px 10px; border-radius: 4px; font-size: 11px; font-weight: 800; display: inline-flex; align-items: center; gap: 4px;">
                                <i class="ph-fill ph-home" style="font-size: 9px;"></i>
                                {{ $user->home_airport_id }}
                            </span>
                        </div>

                        <!-- Timezone -->
                        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #353b43;">
                            <span style="font-size: 13px; font-weight: 700; color: var(--text-main, #cbd5e1);">Timezone</span>
                            <span style="font-size: 13px; font-weight: 700; color: #fff;">{{ $user->timezone ?: 'GMT' }}</span>
                        </div>

                        <!-- Member since -->
                        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #353b43;">
                            <span style="font-size: 13px; font-weight: 700; color: var(--text-main, #cbd5e1);">Member since</span>
                            <span style="font-size: 13px; font-weight: 700; color: var(--text-muted, #94a3b8);">{{ $user->created_at->diffForHumans(null, true) }} ago</span>
                        </div>

                        <!-- Your last flight was -->
                        <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #353b43;">
                            <span style="font-size: 13px; font-weight: 700; color: var(--text-main, #cbd5e1);">Your last flight was</span>
                            <span style="font-size: 13px; font-weight: 700; color: var(--text-muted, #94a3b8);">{{ $user->last_pirep ? $user->last_pirep->created_at->diffForHumans(null, true) . ' ago' : 'Never' }}</span>
                        </div>

                        <!-- Last seen -->
                        <div class="d-flex justify-content-between align-items-center py-2">
                            <span style="font-size: 13px; font-weight: 700; color: var(--text-main, #cbd5e1);">Last seen</span>
                            <span style="font-size: 13px; font-weight: 700; color: var(--text-muted, #94a3b8);">
                                {{ $user->last_pirep ? $user->last_pirep->created_at->format('l, d.M.Y') : $user->created_at->format('l, d.M.Y') }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Content Column -->
        <div class="col-lg-8">
            <!-- Header buttons: ACARS Config & Edit -->
            @if(Auth::check() && $user->id === Auth::user()->id)
                <div class="d-flex justify-content-between gap-3 mb-4 flex-wrap">
                    @if(isset($acars) && $acars === true)
                        <a href="{{ route('frontend.profile.acars') }}" class="btn btn-primary flex-grow-1 fw-bold py-2" style="background-color: #518ce5; border-color: #518ce5; border-radius: 4px;" onclick="alert('Copy or Save to \'My Documents/phpVMS\'')">
                            <i class="ph-fill ph-download-simple me-2"></i> ACARS Config
                        </a>
                    @else
                        <a href="#" class="btn btn-secondary flex-grow-1 fw-bold py-2 disabled" style="background-color: #3b434c; border-color: #353b43; color: #777; border-radius: 4px;">
                            <i class="ph-fill ph-download-simple me-2"></i> ACARS Config
                        </a>
                    @endif
                    <a href="{{ route('frontend.profile.edit', [$user->id]) }}" class="btn btn-warning flex-grow-1 fw-bold py-2 text-dark" style="background-color: #f5b041; border-color: #f5b041; border-radius: 4px;">
                        <i class="ph-fill ph-pencil-simple me-2"></i> Edit
                    </a>
                </div>
            @endif

            <!-- Forum Signature / Badge Banner -->
            @php
                $signature_url = null;
                if (Route::has('frontend.profile.signature')) {
                    $signature_url = route('frontend.profile.signature', [$user->id]);
                } elseif (Route::has('frontend.signatures.show')) {
                    $signature_url = route('frontend.signatures.show', [$user->id]);
                } else {
                    $signature_url = 'https://images.unsplash.com/photo-1436491865332-7a61a109cc05?auto=format&fit=crop&w=1200&q=80';
                }
            @endphp
            <div class="card mb-4" style="background: #282c32; border: 1px solid #353b43; border-radius: 8px; overflow: hidden;">
                <div class="card-body p-0 text-center" style="min-height: 100px; display: flex; align-items: center; justify-content: center; background: #1f2327;">
                    <img src="{{ $signature_url }}" 
                         style="width: 100%; max-height: 200px; object-fit: cover;" 
                         alt="Signature Badge"
                         onerror="this.style.display='none';">
                </div>
            </div>

            <!-- Biography Card -->
            <div class="card mb-4" style="background: #282c32; border: 1px solid #353b43; border-radius: 8px;">
                <div class="card-header" style="background: transparent; border-bottom: 1px solid #353b43; padding: 15px 20px;">
                    <h5 style="margin: 0; color: #fff; font-size: 15px; font-weight: 700; display: flex; align-items: center; gap: 8px;">
                        <i class="ph-fill ph-book-open" style="color: #518ce5;"></i> Pilots biography
                    </h5>
                </div>
                <div class="card-body" style="padding: 20px;">
                    <div style="font-size: 13px; line-height: 1.8; color: var(--text-main, #cbd5e1);">
                        {{ $user->name_private }} joined {{ config('app.name') }} on
                        {{ $user->created_at->format('F d, Y') }} and is based in <b>{{ $user->home_airport_id }}</b> -
                        {{ optional($user->home_airport)->name }}.
                        {{ $user->name_private }} was given the Pilot ID of <b>{{ $user->ident }}</b> and is currently
                        holding the rank of <b>{{ optional($user->rank)->name }}</b>, having successfully completed
                        <b>{{ $user->flights }}</b>
                        flight(s) totalling <b>@minutestotime($user->flight_time)</b> hours, whereas the last flight was
                        {{ $user->last_pirep ? $user->last_pirep->created_at->diffForHumans() : 'never' }}.
                    </div>
                    <div style="margin-top: 15px; font-size: 12px; font-weight: 600; color: var(--text-muted, #94a3b8); display: flex; align-items: center; gap: 8px;">
                        <i class="ph-fill ph-arrow-right"></i> We are thankful to have {{ $user->name_private }} on board.
                    </div>
                </div>
            </div>

            <!-- Metrics Grid -->
            <div class="row mb-4">
                <!-- Flights -->
                <div class="col-md-6 mb-3">
                    <div class="sp-metric-box">
                        <i class="ph-fill ph-airplane-takeoff" style="font-size: 22px; color: #518ce5; transform: rotate(-45deg);"></i>
                        <div>
                            <div class="sp-metric-val">{{ $user->flights }}</div>
                            <div class="sp-metric-lbl">Flights</div>
                        </div>
                    </div>
                </div>

                <!-- Flight Hours -->
                <div class="col-md-6 mb-3">
                    <div class="sp-metric-box">
                        <i class="ph-fill ph-clock" style="font-size: 22px; color: #518ce5;"></i>
                        <div>
                            <div class="sp-metric-val">@minutestotime($user->flight_time)</div>
                            <div class="sp-metric-lbl">Flight Hours</div>
                        </div>
                    </div>
                </div>

                <!-- Current Airport -->
                <div class="col-md-6 mb-3">
                    <div class="sp-metric-box">
                        <i class="ph-fill ph-globe" style="font-size: 22px; color: #518ce5;"></i>
                        <div>
                            <div class="sp-metric-val">{{ $user->curr_airport_id }}</div>
                            <div class="sp-metric-lbl">Current Airport</div>
                        </div>
                    </div>
                </div>

                <!-- Transferred Hours -->
                <div class="col-md-6 mb-3">
                    <div class="sp-metric-box">
                        <i class="ph-fill ph-history" style="font-size: 22px; color: #518ce5;"></i>
                        <div>
                            <div class="sp-metric-val">@minutestotime($user->transfer_time)</div>
                            <div class="sp-metric-lbl">Transferred Hours</div>
                        </div>
                    </div>
                </div>

                <!-- Current Balance -->
                <div class="col-md-6 mb-3">
                    <div class="sp-metric-box">
                        <i class="ph-fill ph-money" style="font-size: 22px; color: #518ce5;"></i>
                        <div>
                            <div class="sp-metric-val">{{ safe_money_format($balance) }}</div>
                            <div class="sp-metric-lbl">Current Balance</div>
                        </div>
                    </div>
                </div>

                <!-- Total Distance -->
                <div class="col-md-6 mb-3">
                    <div class="sp-metric-box">
                        <i class="ph-fill ph-info" style="font-size: 22px; color: #518ce5;"></i>
                        <div>
                            @php
                                $total_dist = $user->pireps()->sum('distance');
                            @endphp
                            <div class="sp-metric-val">{{ number_format($total_dist) }} nm</div>
                            <div class="sp-metric-lbl">Total Distance</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabbed Panel container -->
            <div class="sp-sidebar-card p-0" style="overflow: hidden; background: #282c32; border: 1px solid #353b43; border-radius: 8px;">
                <!-- Tabs Header -->
                <div style="border-bottom: 1px solid #353b43; padding: 0 20px;">
                    <ul class="nav nav-tabs border-0 sp-tabs" id="ProfileTabs" role="tablist" style="margin-bottom: 0;">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="awards-tab" data-bs-toggle="tab" data-bs-target="#awards-panel" type="button" role="tab" aria-controls="awards-panel" aria-selected="true">
                                <i class="ph-fill ph-trophy me-1"></i> Your Awards
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="typeratings-tab" data-bs-toggle="tab" data-bs-target="#typeratings-panel" type="button" role="tab" aria-controls="typeratings-panel" aria-selected="false">
                                <i class="ph-fill ph-airplane me-1"></i> Type Rating
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="stats-tab" data-bs-toggle="tab" data-bs-target="#stats-panel" type="button" role="tab" aria-controls="stats-panel" aria-selected="false">
                                <i class="ph-fill ph-chart-bar me-1"></i> Statistics
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pireps-tab" data-bs-toggle="tab" data-bs-target="#pireps-panel" type="button" role="tab" aria-controls="pireps-panel" aria-selected="false">
                                <i class="ph-fill ph-file-text me-1"></i> PIREPs
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="passport-tab" data-bs-toggle="tab" data-bs-target="#passport-panel" type="button" role="tab" aria-controls="passport-panel" aria-selected="false">
                                <i class="ph-fill ph-passport me-1"></i> Passport
                            </button>
                        </li>
                    </ul>
                </div>

                <!-- Tab Contents -->
                <div class="tab-content" id="ProfileTabsContent" style="padding: 20px;">
                    <!-- Awards Tab -->
                    <div class="tab-pane fade show active" id="awards-panel" role="tabpanel" aria-labelledby="awards-tab">
                        @if(filled($user->awards))
                            <div class="row">
                                @foreach($user->awards as $award)
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100 text-center" style="background: #20242a; border: 1px solid #353b43; border-radius: 6px; overflow: hidden; padding: 15px;">
                                            <div style="font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 10px;">
                                                {{ $award->name }}
                                            </div>
                                            <div class="text-center mb-2" style="height: 120px; display: flex; align-items: center; justify-content: center;">
                                                @if($award->image_url)
                                                    <img src="{{ $award->image_url }}" style="max-height: 100px; max-width: 100%; object-fit: contain;" alt="{{ $award->name }}">
                                                @else
                                                    <div style="background: #2a2f35; width: 100px; height: 100px; display: flex; align-items: center; justify-content: center; border-radius: 5px; color: var(--text-muted, #94a3b8);">
                                                        Award
                                                    </div>
                                                @endif
                                            </div>
                                            <div style="font-size: 11px; color: var(--text-muted, #94a3b8); margin-top: 10px; min-height: 33px;">
                                                {{ $award->description }}
                                            </div>
                                            <div style="font-size: 10px; color: #518ce5; font-weight: 600; margin-top: 5px;">
                                                {{ $award->pivot ? $award->pivot->created_at->format('d. F Y') : '' }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4" style="color: var(--text-muted, #94a3b8); font-size: 13px;">
                                No awards earned yet.
                            </div>
                        @endif
                    </div>

                    <!-- Type Ratings Tab -->
                    <div class="tab-pane fade" id="typeratings-panel" role="tabpanel" aria-labelledby="typeratings-tab">
                        @if($user->typeratings->count() > 0)
                            <div class="row">
                                @foreach($user->typeratings->sortBy('type', SORT_NATURAL) as $tr)
                                    <div class="col-md-4 mb-3">
                                        <div class="card h-100 text-center" style="background: #20242a; border: 1px solid #353b43; border-radius: 6px; padding: 15px;">
                                            <div style="font-size: 13px; font-weight: 700; color: #fff; margin-bottom: 10px;">
                                                {{ $tr->type }}
                                            </div>
                                            <div class="text-center" style="height: 100px; display: flex; align-items: center; justify-content: center;">
                                                @if(filled($tr->image_url))
                                                    <img src="{{ $tr->image_url }}" style="max-height: 80px; max-width: 100%; object-fit: contain;" alt="{{ $tr->name }}">
                                                @else
                                                    <div style="background: #2a2f35; width: 80px; height: 80px; display: flex; align-items: center; justify-content: center; border-radius: 5px; color: var(--text-muted, #94a3b8); font-size: 20px;">
                                                        <i class="ph-fill ph-airplane"></i>
                                                    </div>
                                                @endif
                                            </div>
                                            <div style="font-size: 11px; color: var(--text-muted, #94a3b8); margin-top: 10px;">
                                                {{ $tr->name }}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4" style="color: var(--text-muted, #94a3b8); font-size: 13px;">
                                No type ratings assigned yet.
                            </div>
                        @endif
                    </div>

                    <!-- Statistics Tab -->
                    <div class="tab-pane fade" id="stats-panel" role="tabpanel" aria-labelledby="stats-tab">
                        @if($user->flights > 0)
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    @widget('DBasic::PersonalStats', ['disp' => 'full', 'user' => $user->id, 'type' => 'avgscore'])
                                </div>
                                <div class="col-md-6 mb-3">
                                    @widget('DBasic::PersonalStats', ['disp' => 'full', 'user' => $user->id, 'type' => 'avglanding'])
                                </div>
                                <div class="col-md-6 mb-3">
                                    @widget('DBasic::PersonalStats', ['disp' => 'full', 'user' => $user->id, 'type' => 'avgtime'])
                                </div>
                                <div class="col-md-6 mb-3">
                                    @widget('DBasic::PersonalStats', ['disp' => 'full', 'user' => $user->id, 'type' => 'avgdistance'])
                                </div>
                            </div>
                        @else
                            <div class="text-center py-4" style="color: var(--text-muted, #94a3b8); font-size: 13px;">
                                No statistics available. Please submit a PIREP first.
                            </div>
                        @endif
                    </div>

                    <!-- PIREPs Tab -->
                    <div class="tab-pane fade" id="pireps-panel" role="tabpanel" aria-labelledby="pireps-tab">
                        <div class="table-responsive">
                            @include('pireps.table', ['pireps' => $user->pireps()->orderBy('submitted_at', 'desc')->take(10)->get()])
                        </div>
                    </div>

                    <!-- Passport Tab -->
                    <div class="tab-pane fade" id="passport-panel" role="tabpanel" aria-labelledby="passport-tab">
                        @widget('Modules\SPPassport\Widgets\PassportStamps', ['user_id' => $user->id])
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        function apiKeyShow() {
            document.getElementById("apiKey_show").style.display = "inline";
            document.getElementById("apiKey_hide").style.display = "none";
        }
        function apiKeyHide() {
            document.getElementById("apiKey_show").style.display = "none";
            document.getElementById("apiKey_hide").style.display = "inline";
        }
        function emailShow() {
            document.getElementById("email_show").style.display = "inline";
            document.getElementById("email_hide").style.display = "none";
        }
        function emailHide() {
            document.getElementById("email_show").style.display = "none";
            document.getElementById("email_hide").style.display = "inline";
        }
    </script>
@endsection